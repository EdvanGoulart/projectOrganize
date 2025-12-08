<?php

declare(strict_types=1);

namespace App\Controllers\Deck;

use App\Models\Card;
use App\Models\Deck;
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

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['error' => 'Método não permitido']);
            return;
        }


        $titulo = $_POST['titulo'] ?? null;
        $descricao = $_POST['descricao'] ?? null;
        $discipline = $_POST['discipline'] ?? null;
        $cards = $_POST['cards'] ?? [];

        if (!$titulo || empty($cards)) {
            echo json_encode(['error' => 'Preencha o título e adicione ao menos um cartão.']);
            return;
        }

        $deckId = Deck::create($titulo, $descricao, $discipline);

        foreach ($cards as $card) {
            $termo = $card['termo'] ?? '';
            $definicao = $card['definicao'] ?? '';
            if ($termo && $definicao) {
                Card::create($deckId, $termo, $definicao);
            }
        }

        echo json_encode(['success' => true, 'deckId' => $deckId]);
    }
}
