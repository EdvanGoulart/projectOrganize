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

        // Captura o ID da disciplina passada por GET (ex: /task/status-chart?idDisciplina=3)
        $idDisciplina = isset($_GET['idDisciplina']) ? (int)$_GET['idDisciplina'] : null;

        // Monta a query base
        $query = "SELECT status, COUNT(*) AS total
              FROM task
              WHERE idUser = :idUser";

        $params = ['idUser' => auth()->id];

        // Se tiver disciplina selecionada, adiciona na condição
        if ($idDisciplina) {
            $query .= " AND idDiscipline = :idDisciplina";
            $params['idDisciplina'] = $idDisciplina;
        }

        $query .= " GROUP BY status";

        $result = $db->query($query, null, $params)->fetchAll();

        header('Content-Type: application/json');
        echo json_encode([
            'success' => true,
            'data' => $result
        ]);
    }

    public function chartPriority()
    {
        $db = new Database(config('database'));
        $idDisciplina = isset($_GET['idDisciplina']) ? (int)$_GET['idDisciplina'] : null;

        $query = "SELECT priority, COUNT(*) AS total
              FROM task
              WHERE idUser = :idUser";
        $params = ['idUser' => auth()->id];

        if ($idDisciplina) {
            $query .= " AND idDiscipline = :idDisciplina";
            $params['idDisciplina'] = $idDisciplina;
        }

        $query .= " GROUP BY priority";

        $result = $db->query($query, null, $params)->fetchAll();

        header('Content-Type: application/json');
        echo json_encode([
            'success' => true,
            'data' => $result
        ]);
    }
}
