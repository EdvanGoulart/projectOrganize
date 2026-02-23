<?php

declare(strict_types=1);

namespace App\Controllers\Revisao;


use App\Models\Revisao_Deck;
use Core\Validacao;
use Exception;

class Revisao_DeckController
{

    public function registerReview()
    {

        $idDiscipline = Revisao_Deck::create(
            request()->post('id_deck'),
            request()->post('tempo_gasto'),
            request()->post('total_acertos'),
            request()->post('total_erros'),
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
