<?php

declare(strict_types=1);

namespace App\Controllers\Review;

use App\Models\Gamification;
use App\Models\Review_Deck;

class Review_DeckController
{

    public function registerReview()
    {

        $resultado = Review_Deck::create(
            request()->post('id_deck'),
            request()->post('time_spent'),
            request()->post('total_correct'),
            request()->post('total_error'),
        );

        if ((bool) ($resultado['registrado'] ?? false)) {
            Gamification::onDeckReviewCompleted((int) auth()->id, (int) ($resultado['id'] ?? 0));
        }

        header('Content-Type: application/json');
        echo json_encode([
            'success' => (bool) ($resultado['registrado'] ?? false),
            'data' => $resultado,
        ]);
    }
}
