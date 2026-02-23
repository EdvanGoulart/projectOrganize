<?php

namespace App\Controllers\Deck;

use App\Models\Card;
use App\Models\Deck;
use Core\View;

class PracticeController
{
    public function index()
    {
        $id = $_GET['id'];

        // Carrega o deck com cards
        $deck = Deck::find($id);
        $cards = Card::find($id);

        if (!$deck) {
            die("Deck não encontrado.");
        }

        return view('deck/play', [
            'deck' => $deck,
            'cards' => $cards
        ]);
    }
}
