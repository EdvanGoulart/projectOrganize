<?php

declare(strict_types=1);

namespace App\Models;

use Carbon\Carbon;
use Core\Database;
use DateTime;

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
            query: 'select d.color as disciplineColor,t.* from task t
                    left join discipline d on (d.id = t.idDiscipline)
                    where t.idUser = :idUser ' . (
                $pesquisar ? 'and name like :pesquisar' : null
            ),
            class: self::class,
            params: array_merge(['idUser' => auth()->id], $pesquisar ? ['pesquisar' => "%$pesquisar%"] : [])
        )->fetchAll();
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

    public static function update($id, $name, $task)
    {
        $db = new Database(config('database'));

        $set = 'name = :name';

        if ($task) {
            $set .= ', nota = :task';
        }

        $db->query(
            query: "
                update task
                set $set
                where id = :id
            ",
            params: array_merge(
                [
                    'name' => $name,
                    'id'     => $id,
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
}
