<?php

declare(strict_types=1);

namespace App\Models;

use Core\Database;
use DateTime;
use Exception;
use PDO;

enum ResultadoRevisao: string
{
    case ACERTO = 'acerto';
    case ERRO   = 'erro';
}

class Review_Card
{
    public ?int $id;
    public int  $id_deck;
    public int  $id_user;
    public string $date_review;
    public int $time_spent;
    public int $xp_gerado;
    public int $total_correct;
    public int $total_error;
    public ResultadoRevisao $result;



    public static function create($id_deck, $id_card, $result, $time_spent)
    {
        $database = new Database(config('database'));

        $database->query(
            'INSERT INTO deck_review_card (id_user,id_deck, id_card, result, time_spent, date_review)
         VALUES (:id_user,:id_deck, :id_card, :result, :time_spent, :date_review)',
            null,
            [
                ':id_user' => auth()->id,
                ':id_deck' => $id_deck,
                ':id_card' => $id_card,
                ':result' => ResultadoRevisao::from($result)->value,
                ':time_spent' => $time_spent,
                ':date_review' => date('Y-m-d')
            ]
        );

        return (int)$database->lastInsertId();
    }

    public static function all($pesquisar = null)
    {
        $db = new Database(config('database'));

        return $db->query(
            query: 'select * from deck where idUser = :idUser' . (
                $pesquisar ? 'and name like :pesquisar' : null
            ),
            class: self::class,
            params: array_merge(['idUser' => auth()->id], $pesquisar ? ['pesquisar' => "%$pesquisar%"] : [])
        )->fetchAll();
    }

    public static function find(int $id_deck)
    {
        $db = new Database(config('database'));

        $stmt = $db->query(
            'SELECT * FROM deck WHERE id_deck = :id_deck AND idUser = :idUser',
            Deck::class,
            [
                'id_deck' => $id_deck,
                'idUser' => auth()->id
            ]
        );

        return $stmt->fetch(); // já retorna um objeto `Revisao`
    }



    public static function update($id, $title, $description, $idDiscipline)
    {
        $db = new Database(config('database'));

        $set = 'title = :title, description = :description,  idDiscipline = :idDiscipline ';


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

    public static function historyByUser(int $idUser): array
    {
        $db = new Database(config('database'));

        $rows = $db->query(
            query: '
                SELECT
                    d.id AS deck_id,
                    d.title AS deck_title,
                    c.id AS card_id,
                    c.term AS card_term,
                    COUNT(rc.id) AS total_revisoes,
                    SUM(CASE WHEN rc.result = "acerto" THEN 1 ELSE 0 END) AS acertos,
                    SUM(CASE WHEN rc.result = "erro" THEN 1 ELSE 0 END) AS erros,
                    SUM(rc.time_spent) AS tempo_total,
                    AVG(rc.time_spent) AS tempo_medio
                FROM deck_review_card rc
                INNER JOIN deck d
                    ON d.id = rc.id_deck
                    AND d.idUser = rc.id_user
                LEFT JOIN card c
                    ON c.id = rc.id_card
                WHERE rc.id_user = :id_user
                GROUP BY d.id, d.title, c.id, c.term
                ORDER BY d.title ASC, c.term ASC
            ',
            params: [
                'id_user' => $idUser,
            ]
        )->fetchAll();

        if (! $rows) {
            return [];
        }

        $history = [];

        foreach ($rows as $row) {
            $deckId = (int) ($row['deck_id'] ?? 0);

            if ($deckId === 0) {
                continue;
            }

            if (! isset($history[$deckId])) {
                $history[$deckId] = [
                    'deck_id' => $deckId,
                    'deck_title' => (string) ($row['deck_title'] ?? 'Deck sem título'),
                    'total_revisoes' => 0,
                    'acertos' => 0,
                    'erros' => 0,
                    'tempo_total' => 0,
                    'aproveitamento' => 0,
                    'cards' => [],
                ];
            }

            $totalRevisoes = (int) ($row['total_revisoes'] ?? 0);
            $acertos = (int) ($row['acertos'] ?? 0);
            $erros = (int) ($row['erros'] ?? 0);
            $tempoTotal = (int) ($row['tempo_total'] ?? 0);
            $tempoMedio = isset($row['tempo_medio']) ? (float) $row['tempo_medio'] : 0.0;
            $aproveitamento = $totalRevisoes > 0 ? round(($acertos / $totalRevisoes) * 100, 2) : 0;

            $history[$deckId]['total_revisoes'] += $totalRevisoes;
            $history[$deckId]['acertos'] += $acertos;
            $history[$deckId]['erros'] += $erros;
            $history[$deckId]['tempo_total'] += $tempoTotal;

            $history[$deckId]['cards'][] = [
                'card_id' => (int) ($row['card_id'] ?? 0),
                'card_term' => (string) ($row['card_term'] ?? 'Card removido'),
                'total_revisoes' => $totalRevisoes,
                'acertos' => $acertos,
                'erros' => $erros,
                'tempo_total' => $tempoTotal,
                'tempo_medio' => round($tempoMedio, 2),
                'aproveitamento' => $aproveitamento,
            ];
        }

        foreach ($history as &$deck) {
            $deck['aproveitamento'] = $deck['total_revisoes'] > 0
                ? round(($deck['acertos'] / $deck['total_revisoes']) * 100, 2)
                : 0;
        }

        return array_values($history);
    }





    public static function historyByDeckForUser(int $idUser, int $idDeck): ?array
    {
        $history = self::historyByUser($idUser);

        foreach ($history as $deck) {
            if ((int) ($deck['deck_id'] ?? 0) === $idDeck) {
                return $deck;
            }
        }

        return null;
    }
}
