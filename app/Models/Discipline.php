<?php

declare(strict_types=1);

namespace App\Models;

use Core\Database;
use PDO;

class Discipline
{
    public ?int $id;
    public string $name;
    public string $description;
    public string $color;
    public int $idUser;

    public static function create($data)
    {
        $database = new Database(config('database'));

        $database->query(
            query: 'insert into discipline (name, color, description, idUser)
                values (
                    :name,
                    :color,
                    :description,
                    :idUser
                )
            ',
            params: $data,
        );
        // Retorna o ID gerado pelo banco
        return $database->lastInsertId();
    }

    public static function all($pesquisar = null)
    {
        $db = new Database(config('database'));

        return $db->query(
            query: 'select * from discipline where idUser = :idUser' . (
                $pesquisar ? 'and name like :pesquisar' : null
            ),
            class: self::class,
            params: array_merge(['idUser' => auth()->id], $pesquisar ? ['pesquisar' => "%$pesquisar%"] : [])
        )->fetchAll();
    }

    public static function update($id, $name, $color, $description)
    {
        $db = new Database(config('database'));

        $set = 'name = :name, color = :color,  description = :description ';

        // if ($discipline) {
        //     $set .= ', nota = :nota';
        // }

        $db->query(
            query: "
                update discipline
                set $set
                where id = :id
            ",
            params: array_merge(
                [
                    'id'     => $id,
                    'name' => $name,
                    'color' => $color,
                    'description' => $description,
                ]
            )
        );

        return $id;
    }

    public static function verificaExisteVinculo(int $id)
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

        $stmt = $db->query(
            query: '
            DELETE FROM discipline
            WHERE id = :id
        ',
            params: [
                'id' => $id,
            ]
        );

        return $stmt->rowCount() > 0;
    }

    public static function find($id)
    {
        $db = new Database(config('database'));

        $stmt = $db->query(
            'SELECT * FROM discipline WHERE id = :id AND idUser = :idUser',
            Discipline::class,
            [
                'id' => $id->idDiscipline, // Erro aqui de undefined, mas esta sendo passado o parametro
                'idUser' => auth()->id
            ]
        );

        return $stmt->fetch();
    }
}
