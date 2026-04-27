<?php

declare(strict_types=1);

namespace App\Controllers\Review;

use App\Models\Gamification;
use App\Models\Review_Card;
use Core\Validacao;
use Exception;

class Review_CardController
{

    public function registerReview()
    {

        $idDiscipline = Review_Card::create(
            request()->post('id_deck'),
            request()->post('id_card'),
            request()->post('result'),
            request()->post('time_spent')
        );

        Gamification::onCardAnswered((int) auth()->id, (int) $idDiscipline);

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
