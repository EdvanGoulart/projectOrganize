<?php

declare(strict_types=1);

namespace App\Models;

use Core\Database;


class Revisao_Deck
{
    public ?int $id;

    public int $id_deck;
    public int $id_user;
    public string $data_revisao;
    public int $tempo_gasto;
    public int $xp_gerado;
    public int $total_acertos;
    public int $total_erros;

    public static function create($id_deck, $tempo_gasto, $total_acertos, $total_erros): array
    {
        $idDeck = (int) $id_deck;
        $idUser = (int) auth()->id;
        $hoje = date('Y-m-d');

        $resumo = self::buildScheduleSummary($idDeck, $idUser);

        if (! $resumo['pode_registrar_hoje']) {
            return [
                'registrado' => false,
                'id' => null,
                'proxima_revisao' => $resumo['proxima_revisao'],
                'etapa_revisao' => $resumo['etapa_revisao'],
                'message' => 'Revisão já registrada antes do prazo da próxima etapa.',
            ];
        }

        $database = new Database(config('database'));

        $database->query(
            'INSERT INTO deck_revisao (id_deck, id_user, data_revisao, tempo_gasto, total_acertos, total_erros)
          VALUES (:id_deck, :id_user, :data_revisao, :tempo_gasto, :total_acertos, :total_erros)',
            null,
            [
                ':id_deck' => $idDeck,
                ':id_user' => $idUser,
                ':data_revisao' => $hoje,
                ':tempo_gasto' => (int) $tempo_gasto,
                'total_acertos' => (int) $total_acertos,
                'total_erros' => (int) $total_erros,
            ]
        );


        $novoResumo = self::buildScheduleSummary($idDeck, $idUser);

        return [
            'registrado' => true,
            'id' => (int) $database->lastInsertId(),
            'proxima_revisao' => $novoResumo['proxima_revisao'],
            'etapa_revisao' => $novoResumo['etapa_revisao'],
            'message' => 'Revisão registrada com sucesso.',
        ];
    }


    public static function buildScheduleSummary(int $idDeck, ?int $idUser = null): array
    {
        $db = new Database(config('database'));
        $idUser = $idUser ?? (int) auth()->id;


        $datas = $db->query(
            query: '
                SELECT DISTINCT DATE(data_revisao) AS data_revisao
                FROM deck_revisao
                WHERE id_deck = :id_deck
                  AND id_user = :id_user
                ORDER BY data_revisao ASC
            ',
            params: [
                'id_deck' => $idDeck,
                'id_user' => $idUser,
            ]

        )->fetchAll();

        if (! $datas) {
            return [
                'ultima_revisao' => null,
                'proxima_revisao' => date('d/m/Y'),
                'etapa_revisao' => '1ª revisão (Hoje)',
                'total_revisoes_validas' => 0,
                'pode_registrar_hoje' => true,
                'atrasada_reset' => false,
                'aviso_revisao' => null,
            ];
        }


        $totalValidas = 0;
        $ultimaValida = null;
        $proximaData = null;

        foreach ($datas as $linha) {
            $dataAtual = (string) ($linha['data_revisao'] ?? '');

            if ($dataAtual === '') {
                continue;
            }

            if ($totalValidas === 0) {
                $totalValidas = 1;
                $ultimaValida = $dataAtual;
                $proximaData = self::addDays($ultimaValida, self::intervaloPorEtapa($totalValidas));
                continue;
            }

            if ($proximaData !== null && $dataAtual > $proximaData && self::deveResetarPorAtraso($totalValidas)) {
                $totalValidas = 1;
                $ultimaValida = $dataAtual;
                $proximaData = self::addDays($ultimaValida, self::intervaloPorEtapa($totalValidas));
                continue;
            }

            if ($proximaData !== null && $dataAtual >= $proximaData) {
                $totalValidas++;
                $ultimaValida = $dataAtual;
                $proximaData = self::addDays($ultimaValida, self::intervaloPorEtapa($totalValidas));
            }
        }


        $hoje = date('Y-m-d');

        if ($proximaData !== null && $hoje > $proximaData && self::deveResetarPorAtraso($totalValidas)) {
            return [
                'ultima_revisao' => null,
                'proxima_revisao' => date('d/m/Y'),
                'etapa_revisao' => '1ª revisão (Hoje)',
                'total_revisoes_validas' => 0,
                'pode_registrar_hoje' => true,
                'atrasada_reset' => true,
                'aviso_revisao' => 'Revisão atrasada: progresso reiniciado para a 1ª etapa por estar abaixo da 5ª revisão.',
            ];
        }

        $podeRegistrarHoje = $proximaData === null || $hoje >= $proximaData;


        return [
            'ultima_revisao' => $ultimaValida,
            'proxima_revisao' => self::formatDate($proximaData),
            'etapa_revisao' => self::descricaoEtapa($totalValidas),
            'total_revisoes_validas' => $totalValidas,
            'pode_registrar_hoje' => $podeRegistrarHoje,
            'atrasada_reset' => false,
            'aviso_revisao' => null,
        ];
    }

    private static function deveResetarPorAtraso(int $totalValidas): bool
    {
        return $totalValidas < 4;
    }

    private static function intervaloPorEtapa(int $totalValidas): int
    {

        return match (true) {
            $totalValidas <= 1 => 1,
            $totalValidas === 2 => 3,
            $totalValidas === 3 => 7,
            $totalValidas === 4 => 30,
            default => 90,
        };
    }


    private static function descricaoEtapa(int $totalValidas): string
    {
        return match (true) {
            $totalValidas <= 0 => '1ª revisão (hoje)',
            $totalValidas === 1 => '2ª revisão (1 dia)',
            $totalValidas === 2 => '3ª revisão (3 dias)',
            $totalValidas === 3 => '4ª revisão (7 dias)',
            $totalValidas === 4 => '5ª revisão (30 dias)',
            default => 'Revisões contínuas (90 dias)',
        };
    }

    private static function addDays(string $date, int $days): string
    {

        $base = new \DateTime($date);
        $base->modify("+{$days} days");


        return $base->format('Y-m-d');
    }


    private static function formatDate(?string $date): string
    {
        if (! $date) {
            return date('d/m/Y');
        }

        return (new \DateTime($date))->format('d/m/Y');
    }
}
