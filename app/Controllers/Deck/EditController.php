<?php

declare(strict_types=1);

namespace App\Controllers\Deck;

use App\Models\Card;
use App\Models\Deck;
use App\Models\Discipline;
use Core\Validacao;
use Exception;

class EditController
{
    public function findCard()
    {
        header('Content-Type: application/json');
        $id = (int) $_GET['id'];

        if (!$task) {
            echo json_encode([
                'success' => false,
                'message' => 'Tarefa não encontrada'
            ]);
            return;
        }

        echo json_encode([
            'success' => true,
            'task' => $task
        ]);
    }

    public function findDeckCards()
    {
        try {
            $id = (int) $_POST['id'];

            if (!$id) {
                echo json_encode([
                    'success' => false,
                    'error' => 'ID do deck não informado.'
                ]);
                return;
            }

            // Busca deck e cards vinculados
            $deck = Deck::find($id);
            $cards = Card::find($id);
            $discipline = Discipline::find($deck);
            $disciplineAll = Discipline::all();

            if (!$deck) {
                echo json_encode([
                    'success' => false,
                    'error' => 'Deck não encontrado.'
                ]);
                return;
            }


            echo json_encode([
                'success' => true,
                'data' => [
                    'deck' => $deck,
                    'cards' => $cards,
                    'discipline_check' => $discipline,
                    'disciplineAll' => $disciplineAll
                ]
            ]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'error' => 'Erro interno: ' . $e->getMessage()
            ]);
        }
    }

    public function updateAjax()
    {

        $deck = $_POST;


        $idDeck = Deck::update($deck['id'], $deck['titulo'], $deck['descricao'], $deck['discipline']);

        foreach ($deck['cards'] as $c) {
            $idCard = Card::update($c['id'], $c['termo'], $c['definicao'], $idDeck);
        }

        if ($deck['cards_new']) {
            foreach ($deck['cards_new'] as $card) {
                // dd();
                if (($card['termo'] == '' or $card['termo'] == null) or ($card['definicao'] == '' or $card['definicao'] == null)) {
                    echo json_encode([
                        'validacao' => 'Os campos devem ser prenchidos !',
                    ]);
                    return;
                }
                $idCard_new = Card::create($idDeck, $card['termo'], $card['definicao']);
            }
        }


        header('Content-Type: application/json');
        echo json_encode([
            'success' => true,
        ]);
    }
}
