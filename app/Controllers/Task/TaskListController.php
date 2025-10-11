<?php

declare(strict_types=1);

namespace App\Controllers\Task;

use App\Models\Discipline;
use App\Models\Task;

class TaskListController
{
    public function __invoke()
    {
        $disciplineList = Discipline::all(
            request()->get('pesquisar')
        );

        $taskList = Task::all(
            request()->get('pesquisar')
        );

        echo json_encode(["success" => true, "tasks" => $taskList, "discipline" => $disciplineList]);
    }
}
