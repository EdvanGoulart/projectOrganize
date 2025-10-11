<?php

declare(strict_types=1);

namespace App\Controllers\Task;

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
        $validacao = Validacao::validar([
            'name' => ['required', 'min:3', 'max:255'],
            'description'   => ['required'],
        ], request()->all());

        if ($validacao->naoPassou()) {
            return view('/task');
        }

        Task::create([
            'idUser' => auth()->id,
            'name'     => request()->post('name'),
            'description'       => request()->post('description'),
            'status'       => request()->post('status'),
            'priority'       => request()->post('priority'),
            'idDiscipline'       => request()->post('discipline'),
            'endDate'       => request()->post('endDate'),
        ]);

        flash()->push('mensagem', 'Tarefa criada com sucesso!');

        return redirect('/discipline');
    }

    public function storeAjax()
    {
        $validacao = Validacao::validar([
            'name'        => ['required', 'min:3', 'max:255'],
            'description' => ['required'],
        ], request()->all());
        // dd(request()->all());
        if ($validacao->naoPassou()) {

            header('Content-Type: application/json');
            echo json_encode([
                'success' => false,
                'errors'  => $validacao->erros()  // supondo que esse método exista
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


        header('Content-Type: application/json');
        echo json_encode([
            'success' => true,
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
