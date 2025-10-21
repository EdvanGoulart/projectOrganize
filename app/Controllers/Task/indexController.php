<?php

declare(strict_types=1);

namespace App\Controllers\Task;

use App\Models\Discipline;
use App\Models\Task;
use Core\Database;

class IndexController
{
    public function __invoke()
    {
        $disciplineList = Discipline::all(
            request()->get('pesquisar')
        );

        $taskList = Task::all(
            request()->get('pesquisar')
        );

        return view('task/index', [
            'disciplineList' => $disciplineList,
            'taskList' => $taskList
        ]);
    }

    public function chartStatus()
    {
        $db = new Database(config('database'));
        $result = $db->query(
            query: "SELECT status, COUNT(*) as total 
                FROM task 
                WHERE idUser = :idUser 
                GROUP BY status",
            params: ['idUser' => auth()->id]
        )->fetchAll();

        echo json_encode([
            'success' => true,
            'data' => $result
        ]);
    }
}
