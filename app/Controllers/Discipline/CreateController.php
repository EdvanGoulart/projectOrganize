<?php

declare(strict_types=1);

namespace App\Controllers\Discipline;

use App\Models\Discipline;
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
            return view('/discipline');
        }

        Discipline::create([
            'idUser' => auth()->id,
            'name'     => request()->post('name'),
            'color'       => request()->post('color'),
            'description'       => request()->post('description'),
        ]);

        flash()->push('mensagem', 'Disciplina criada com sucesso!');

        return redirect('/discipline');
    }

    public function storeAjax()
    {
        // Validação
        $validacao = Validacao::validar([
            'name'        => ['required', 'min:3', 'max:255'],
            'description' => ['required'],
        ], request()->all());

        if ($validacao->naoPassou()) {
            // Retorna erros em JSON
            header('Content-Type: application/json');
            echo json_encode([
                'success' => false,
                'errors'  => $validacao->erros()  // supondo que esse método exista
            ]);
            return;
        }

        // Criação da disciplina
        $idDiscipline = Discipline::create([
            'idUser'      => auth()->id,
            'name'        => request()->post('name'),
            'color'       => request()->post('color'),
            'description' => request()->post('description'),
        ]);


        // Retorna sucesso em JSON
        header('Content-Type: application/json');
        echo json_encode([
            'success' => true,
            'data' => [
                'id' => $idDiscipline,
                'name' => request()->post('name'),
                'description' => request()->post('description'),
                'color' => request()->post('color')
            ]
        ]);
    }
}
