<?php

declare(strict_types=1);

namespace App\Models;

use Core\Database;
use DateTime;
use PDO;

class Card
{
    public ?int $id;
    public string $term;
    public string $definition;
    public int $id_deck;



    public static function create($id_deck, $term, $definition)
    {
        $database = new Database(config('database'));

        $database->query(
            'INSERT INTO card (id_deck, term, definition)
         VALUES (:id_deck, :term, :definition)',
            null,
            [
                ':id_deck' => $id_deck,
                ':term' => $term,
                ':definition' => $definition
            ]
        );

        return (int)$database->lastInsertId();
    }

    public static function update($id, $term, $definition, $id_deck)
    {
        $db = new Database(config('database'));

        $set = 'id = :id, term = :term, definition = :definition,  id_deck = :id_deck ';

        $db->query(
            query: "
                update card
                set $set
                where id = :id
            ",
            params: array_merge(
                [
                    'id'     => $id,
                    'term' => $term,
                    'definition' => $definition,
                    'id_deck' => $id_deck,

                ]
            )
        );

        return $id;
    }

    public static function find(int $id)
    {
        $db = new Database(config('database'));

        $stmt = $db->query(
            'SELECT * FROM card WHERE id_deck = :id ORDER BY ID_DECK',
            Card::class,
            [
                'id' => $id
            ]
        );

        return $stmt->fetchAll();
    }

    public static function all($pesquisar = null)
    {
        $db = new Database(config('database'));

        return $db->query(
            query: 'select * from card where idUser = :idUser' . (
                $pesquisar ? 'and name like :pesquisar' : null
            ),
            class: self::class,
            params: array_merge(['idUser' => auth()->id], $pesquisar ? ['pesquisar' => "%$pesquisar%"] : [])
        )->fetchAll();
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


    public static function delete(int $id, int $idDeck): bool
    {
        $db = new Database(config('database'));

        $resultado = $db->query(
            query: "
            DELETE FROM card
            WHERE id = :id
              AND id_deck = :idDeck
        ",
            params: [
                'id' => $id,
                'idDeck' => $idDeck
            ]
        );

        return $resultado !== false;
    }
}
