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

    // public function store()
    // {
    //     $validacao = Validacao::validar([
    //         'name' => ['required', 'min:3', 'max:255'],
    //         'description'   => ['required'],
    //     ], request()->all());

    //     if ($validacao->naoPassou()) {
    //         return view('/discipline');
    //     }

    //     Discipline::create([
    //         'idUser' => auth()->id,
    //         'name'     => request()->post('name'),
    //         'color'       => request()->post('color'),
    //         'description'       => request()->post('description'),
    //     ]);

    //     flash()->push('mensagem', 'Disciplina criada com sucesso!');

    //     return redirect('/discipline');
    // }

    public function storeAjax()
    {

        $validacao = Validacao::validar([
            'name'        => ['required', 'min:3', 'max:255'],
            'description' => ['required'],
        ], request()->all());

        if ($validacao->naoPassou()) {

            header('Content-Type: application/json');
            echo json_encode([
                'success' => false,
                'errors'  => flash()->get('validacoes')  // supondo que esse método exista
            ]);
            return;
        }

        $idDiscipline = Discipline::create([
            'idUser'      => auth()->id,
            'name'        => request()->post('name'),
            'color'       => request()->post('color'),
            'description' => request()->post('description'),
        ]);


        header('Content-Type: application/json');
        echo json_encode([
            'success' => true,
            'data' => [
                'id' => $idDiscipline,
                'name' => request()->post('name'),
                'color' => request()->post('color'),
                'description' => request()->post('description'),

            ]
        ]);
    }
}
