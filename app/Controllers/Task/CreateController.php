<?php

declare(strict_types=1);

namespace App\Controllers\Task;

use App\Models\Discipline;
use App\Models\Gamification;
use App\Models\Task;
use Core\Validacao;

class CreateController
{
    public function index()
    {
        return view('discipline/index');
    }

    public function store()
    {
        $disciplineList = Discipline::all();

        if (count($disciplineList) === 0) {
            flash()->push('mensagem', 'Você precisa criar uma disciplina antes de criar uma tarefa.');
            return redirect('/discipline');
        }

        $validacao = Validacao::validar([
            'name' => ['required', 'min:3', 'max:255'],
            'description'   => ['required'],
        ], request()->all());

        if ($validacao->naoPassou()) {
            return view('/task');
        }

        $idTask = Task::create([
            'idUser' => auth()->id,
            'name'     => request()->post('name'),
            'description'       => request()->post('description'),
            'status'       => request()->post('status'),
            'priority'       => request()->post('priority'),
            'idDiscipline'       => request()->post('discipline'),
            'endDate'       => request()->post('endDate'),
        ]);

        Gamification::onTaskCreated((int) auth()->id, (int) $idTask);

        flash()->push('mensagem', 'Tarefa criada com sucesso!');

        return redirect('/discipline');
    }

    public function storeAjax()
    {

        $disciplineList = Discipline::all();

        if (count($disciplineList) === 0) {
            header('Content-Type: application/json');
            echo json_encode([
                'success' => false,
                'message' => 'Você precisa criar uma disciplina antes de criar uma tarefa.'
            ]);
            return;
        }
        $validacao = Validacao::validar([
            'name'        => ['required', 'min:3', 'max:255'],
            'description' => ['required'],
        ], request()->all());
        // dd(request()->all());
        if ($validacao->naoPassou()) {

            header('Content-Type: application/json');
            echo json_encode([
                'success' => false,
                'message' => 'Preencha todos os campos obrigatórios para criar a tarefa.',
                'errors'  => $validacao->erros()
            ]);
            return;
        }


        $idTask = Task::create([
            'idUser' => auth()->id,
            'name'     => request()->post('name'),
            'description'       => request()->post('description'),
            'status'       => request()->post('status'),
            'priority'       => request()->post('priority'),
            'idDiscipline'       => request()->post('discipline'),
            'endDate'       => request()->post('endDate'),
        ]);

        Gamification::onTaskCreated((int) auth()->id, (int) $idTask);

        header('Content-Type: application/json');
        echo json_encode([
            'success' => true,
            'message' => 'Tarefa criada com sucesso!',
            'data' => [
                'id' => $idTask,
                'name'     => request()->post('name'),
                'description'       => request()->post('description'),
                'status'       => request()->post('status'),
                'priority'       => request()->post('priority'),
                'idDiscipline'       => request()->post('discipline'),
                'endDate'       => request()->post('endDate'),
            ]
        ]);
    }
}
