<?php

declare(strict_types=1);

namespace App\Controllers\Revisao;


use App\Models\Revisao_Card;
use Core\Validacao;
use Exception;

class Revisao_CardController
{

    public function registerReview()
    {

        $idDiscipline = Revisao_Card::create(
            request()->post('id_deck'),
            request()->post('id_card'),
            request()->post('resultado'),
            request()->post('tempo_gasto')
        );


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
