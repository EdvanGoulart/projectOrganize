<?php

declare(strict_types=1);

namespace App\Models;

use Core\Database;
use DateTime;
use Exception;
use PDO;
use App\Models\Revisao_Deck;
use DateTimeImmutable;

class Deck
{
    public ?int $id;
    public string $title;
    public string $description;
    public int $idDiscipline;

    public int $idUser;

    public ?string $ultima_revisao = null;
    public int $total_revisoes = 0;
    public ?string $proxima_revisao = null;
    public int $dias_para_revisao = 0;
    public string $etapa_revisao = 'Sem revisão';
    public ?string $aviso_revisao = null;


    public static function create($titulo, $descricao, $disciplina)
    {
        $database = new Database(config('database'));

        $database->query(
            'INSERT INTO deck (title, description, idDiscipline, idUser)
         VALUES (:title, :description, :idDiscipline, :idUser)',
            null,
            [
                ':title' => $titulo,
                ':description' => $descricao,
                ':idDiscipline' => $disciplina,
                ':idUser' => auth()->id
            ]
        );

        return (int)$database->lastInsertId();
    }

    public static function all($pesquisar = null, ?string $filtroEtapa = null)
    {
        $db = new Database(config('database'));

        $query = '
            SELECT
                d.*,
                MAX(dr.data_revisao) AS ultima_revisao,
                COUNT(dr.id) AS total_revisoes
            FROM deck d
            LEFT JOIN deck_revisao dr
                ON dr.id_deck = d.id
                AND dr.id_user = d.idUser
            WHERE d.idUser = :idUser
        ';

        $params = ['idUser' => auth()->id];

        if ($pesquisar) {
            $query .= ' AND d.title LIKE :pesquisar ';
            $params['pesquisar'] = "%$pesquisar%";
        }

        $query .= ' GROUP BY d.id ORDER BY d.id DESC ';

        $decks = $db->query(
            query: $query,
            class: self::class,

            params: $params
        )->fetchAll();

        foreach ($decks as $deck) {
            $resumo = Revisao_Deck::buildScheduleSummary((int) $deck->id, (int) auth()->id);

            $deck->ultima_revisao = $resumo['ultima_revisao'];
            $deck->proxima_revisao = $resumo['proxima_revisao'];
            $deck->dias_para_revisao = self::calcularDiasParaRevisao($resumo['proxima_revisao']);
            $deck->etapa_revisao = $resumo['etapa_revisao'];
            $deck->total_revisoes = (int) ($resumo['total_revisoes_validas'] ?? 0);
            $deck->aviso_revisao = $resumo['aviso_revisao'];
        }

        if ($filtroEtapa) {
            $decks = array_values(array_filter(
                $decks,
                fn($deck) => self::deckPassaNoFiltroEtapa($deck, $filtroEtapa)
            ));
        }

        return $decks;
    }

    private static function calcularDiasParaRevisao(string $proximaRevisao): int
    {
        $proximaData = DateTimeImmutable::createFromFormat('d/m/Y', $proximaRevisao);

        if (! $proximaData) {
            return 0;
        }

        $hoje = new DateTimeImmutable('today');
        $diferenca = $hoje->diff($proximaData);

        return (int) $diferenca->format('%r%a');
    }

    private static function deckPassaNoFiltroEtapa(self $deck, string $filtroEtapa): bool
    {
        $diasParaRevisao = $deck->dias_para_revisao;

        return match ($filtroEtapa) {
            'hoje' => $diasParaRevisao <= 0,
            'amanha' => $diasParaRevisao === 1,
            '3dias' => $diasParaRevisao >= 2 && $diasParaRevisao <= 3,
            '7dias' => $diasParaRevisao >= 4 && $diasParaRevisao <= 7,
            '30dias' => $diasParaRevisao >= 8 && $diasParaRevisao <= 30,
            '90dias' => $diasParaRevisao >= 90,
            default => true,
        };
    }

    public static function find(int $id)
    {
        $db = new Database(config('database'));

        $stmt = $db->query(
            'SELECT * FROM deck WHERE id = :id AND idUser = :idUser',
            Deck::class,
            [
                'id' => $id,
                'idUser' => auth()->id
            ]
        );

        return $stmt->fetch(); // já retorna um objeto `Card`
    }



    public static function update($id, $title, $description, $idDiscipline)
    {
        $db = new Database(config('database'));

        $set = 'title = :title, description = :description,  idDiscipline = :idDiscipline ';

        // if ($discipline) {
        //     $set .= ', nota = :nota';
        // }

        $db->query(
            query: "
                update deck
                set $set
                where id = :id
            ",
            params: array_merge(
                [
                    'id'     => $id,
                    'title' => $title,
                    'description' => $description,
                    'idDiscipline' => $idDiscipline,

                ]
            )
        );

        return $id;
    }

    public static function verificaExisteVinculo($id)
    {
        $db = new Database(config('database'));
        $stmt = $db->query(
            query: "SELECT COUNT(*) as total FROM task WHERE idDiscipline = :id",
            params: ['id' => $id]
        );

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return (int) ($row['total'] ?? 0);
    }

    public static function delete($id)
    {
        $db = new Database(config('database'));

        try {
            // Inicia uma transação — garante que tudo ocorra junto
            $db->beginTransaction();

            // 1️⃣ Deleta todos os cards vinculados a esse deck
            $db->query(
                query: '
                DELETE FROM card
                WHERE id_deck = :id_deck
            ',
                params: [
                    'id_deck' => $id,
                ]
            );

            // 2️⃣ Agora deleta o deck
            $stmt = $db->query(
                query: '
                DELETE FROM deck
                WHERE id = :id
            ',
                params: [
                    'id' => $id,
                ]
            );

            // Finaliza a transação
            $db->commit();

            // Retorna se o deck foi deletado com sucesso
            return $stmt->rowCount() > 0;
        } catch (Exception $e) {
            // Caso algo dê errado, desfaz a transação
            $db->rollBack();
            throw $e;
        }
    }

    private static function calcularProximaRevisao(?string $ultimaRevisao, int $totalRevisoes): array
    {
        if (! $ultimaRevisao || $totalRevisoes <= 0) {
            return [date('d/m/Y'), '1ª revisão (1 dia)'];
        }

        $intervalos = [1, 3, 7, 30, 90];
        $indice = min($totalRevisoes - 1, count($intervalos) - 1);
        $dias = $intervalos[$indice];

        $proxima = DateTime::createFromFormat('Y-m-d', $ultimaRevisao) ?: new DateTime($ultimaRevisao);
        $proxima->modify("+{$dias} days");

        $etapas = [
            '1ª revisão (1 dia)',
            '2ª revisão (3 dias)',
            '3ª revisão (7 dias)',
            '4ª revisão (30 dias)',
            '5ª+ revisão (90 dias)',
        ];

        return [$proxima->format('d/m/Y'), $etapas[$indice]];
    }
}
