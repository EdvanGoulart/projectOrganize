<?php

declare(strict_types=1);

namespace App\Models;

use Carbon\Carbon;
use Core\Database;
use DateTime;
use PDO;

class Task
{
    public int $idTask;
    public string $name;
    public string $description;
    public string $status;
    public string $priority;
    public int $idDiscipline;
    public int $idUser;
    public string $startDate;
    public string $endDate;
    public int $position;

    public function dataCriacao()
    {
        return Carbon::parse($this->startDate);
    }

    public function dataAtualizacao()
    {
        return Carbon::parse($this->endDate);
    }


    public static function all($pesquisar = null)
    {
        $db = new Database(config('database'));

        return $db->query(
            query: 'SELECT d.color AS disciplineColor, t.* 
                FROM task t
                LEFT JOIN discipline d ON (d.id = t.idDiscipline)
                WHERE t.idUser = :idUser ' . (
                $pesquisar ? 'AND t.name LIKE :pesquisar ' : ''
            ) . '
                ORDER BY 
                    t.status,
                    t.position ASC',
            class: self::class,
            params: array_merge(['idUser' => auth()->id], $pesquisar ? ['pesquisar' => "%$pesquisar%"] : [])
        )->fetchAll();
    }

    public static function find(int $id): ?self
    {
        $db = new Database(config('database'));

        $stmt = $db->query(
            query: 'SELECT d.color AS disciplineColor, t.* 
                FROM task t
                LEFT JOIN discipline d ON d.id = t.idDiscipline
                WHERE t.id = :id AND t.idUser = :idUser',
            class: self::class,
            params: [
                'id' => $id,
                'idUser' => auth()->id
            ]
        );

        $task = $stmt->fetch();

        return $task ?: null;
    }

    public static function create($data)
    {
        $database = new Database(config('database'));

        $database->query(
            query: 'insert into task (name, description, status, priority, idDiscipline, idUser, startDate, endDate)
                values (
                    :name,
                    :description,
                    :status,
                    :priority,
                    :idDiscipline,
                    :idUser,
                    :startDate,
                    :endDate
                )
            ',
            params: array_merge($data, [
                'startDate'     => (new \DateTime())->format('Y-m-d H:i:s'),
            ])
        );
    }

    public static function update($id, $name, $description, $status, $priority, $idDiscipline, $endDate)
    {
        $db = new Database(config('database'));

        $set = 'name = :name, description = :description, status = :status, priority = :priority, idDiscipline = :idDiscipline,  endDate = :endDate';


        $db->query(
            query: "
                update task
                set $set
                where id = :id
            ",
            params: array_merge(
                [
                    'id'     => $id,
                    'name' => $name,
                    'description' => $description,
                    'status' => $status,
                    'priority' => $priority,
                    'idDiscipline' => $idDiscipline,
                    'endDate' => $endDate
                ],
                // $nota ? ['nota' => encrypt($nota)] : []
            )
        );
    }

    public static function delete($id)
    {
        $db = new Database(config('database'));

        $stmt = $db->query(
            query: '
            DELETE FROM task
            WHERE id = :id
        ',
            params: [
                'id' => $id,
            ]
        );

        return $stmt->rowCount() > 0;
    }

    public static function updateStatus(int $id, string $status): bool
    {
        $db = new Database(config('database'));

        $stmt = $db->query(
            query: '
            UPDATE task
            SET status = :status
            WHERE id = :id
        ',
            params: [
                'status' => $status,
                'id' => $id,
            ]
        );

        return $stmt->rowCount() > 0;
    }

    public static function updateOrder(int $id, string $status, int $position): bool
    {
        $db = new Database(config('database'));

        $update = $db->query(
            query: 'UPDATE task SET status = :status, position = :position WHERE id = :id',
            params: [
                'status' => $status,
                'position' => $position,
                'id' => $id
            ]
        );

        if ($update->rowCount() === 0) {
            return false;
        }

        // Reorganiza as posições das outras tarefas da mesma coluna
        $tasks = $db->query(
            query: 'SELECT id FROM task WHERE status = :status AND id != :id ORDER BY position ASC',
            params: [
                'status' => $status,
                'id' => $id
            ]
        )->fetchAll(PDO::FETCH_ASSOC);

        $pos = 0;
        foreach ($tasks as $t) {
            if ($pos == $position) $pos++; // pula a posição da tarefa movida

            $db->query(
                query: 'UPDATE task SET position = :position WHERE id = :id',
                params: [
                    'position' => $pos,
                    'id' => $t['id']
                ]
            );
            $pos++;
        }

        return true;
    }
}
