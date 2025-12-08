<?php

declare(strict_types=1);

namespace App\Controllers\Deck;

use App\Models\Deck;
use App\Models\Discipline;

class IndexController
{
    public function index()
    {
        $deckList = Deck::all(
            request()->get('pesquisar')
        );

        return view('deck/index', [
            'deckList'           => $deckList
        ]);
    }

    public function formCreateDeck()
    {
        $disciplineList = Discipline::all(
            request()->get('pesquisar')
        );

        return view('deck/create', [
            'disciplineList' => $disciplineList
        ]);
    }
}
