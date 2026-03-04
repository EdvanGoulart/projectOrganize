<?php

declare(strict_types=1);

namespace App\Controllers\Deck;

use App\Models\Deck;
use App\Models\Discipline;

class IndexController
{
    public function index()
    {
        $filtroEtapa = request()->get('filtro_etapa');
        $filtroEtapaSelecionado = is_string($filtroEtapa) ? $filtroEtapa : '';

        $deckList = Deck::all(
            request()->get('pesquisar'),
            $filtroEtapaSelecionado ?: null
        );

        if (request()->isAjax()) {
            echo json_encode([
                'success' => true,
                'html' => $this->renderDeckGrid($deckList),
            ]);
            return;
        }

        return view('deck/index', [
            'deckList' => $deckList,
            'filtroEtapaSelecionado' => $filtroEtapaSelecionado,
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

    private function renderDeckGrid(array $deckList): string
    {
        ob_start();
        require base_path('views/deck/_deckGrid.view.php');

        return (string) ob_get_clean();
    }
}
