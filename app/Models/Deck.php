<?php

declare(strict_types=1);

namespace App\Models;

use Core\Database;
use Exception;
use PDO;

class Deck
{
    public ?int $id;
    public string $title;
    public string $description;
    public int $idDiscipline;
    public int $idUser;


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
}
