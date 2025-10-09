<?php

declare(strict_types=1);

namespace App\Controllers\Discipline;

use App\Models\Discipline;
use Core\Validacao;

class EditController
{
    public function __invoke()
    {

        $validacao = Validacao::validar(array_merge(
            [
                'id'     => ['required'],
            ],
        ), request()->all());

        if ($validacao->naoPassou()) {
            // Retorna erros em JSON
            header('Content-Type: application/json');
            echo json_encode([
                'success' => false,
                'errors'  => $validacao->erros()  // supondo que esse método exista
            ]);
            return;
        }

        $idDiscipline = Discipline::update(
            request()->post('id'),
            request()->post('name'),
            request()->post('color'),
            request()->post('description')
        );

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
