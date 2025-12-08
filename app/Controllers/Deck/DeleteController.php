<?php

declare(strict_types=1);

namespace App\Controllers\Deck;

use App\Models\Card;
use App\Models\Deck;
use Core\Validacao;

class DeleteController
{
    public function __invoke()
    {
        header('Content-Type: application/json');
        $dados = request()->all();

        $validacao = Validacao::validar([
            'id' => ['required'],
        ], $dados);

        if ($validacao->naoPassou()) {
            echo json_encode([
                'success' => false,
                'message' => 'ID não informado.'
            ]);
            return;
        }

        $id = (int)$dados['id'];

        $deletado = Deck::delete($id);

        if ($deletado) {
            echo json_encode([
                'success' => true,
                'message' => 'Registro deletado com sucesso!',
                'id' => $id
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Erro ao deletar registro.'
            ]);
        }
    }

    public static function deleteCardAjax(): bool
    {
        header('Content-Type: application/json');

        try {
            // Verifica se mandou os dados necessários
            if (empty($_POST['id']) || empty($_POST['idDeck'])) {
                echo json_encode([
                    'success' => false,
                    'message' => 'Dados insuficientes para excluir o card.'
                ]);
                return false;
            }

            $id = (int)$_POST['id'];
            $idDeck = (int)$_POST['idDeck'];

            $sucesso = Card::delete($id, $idDeck);

            if ($sucesso) {
                echo json_encode([
                    'success' => true,
                    'message' => 'Card deletado com sucesso!',
                    'id' => $id,
                    'idDeck' => $idDeck
                ]);
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => 'Falha ao deletar card.'
                ]);
            }

            return $sucesso;
        } catch (\Exception $e) {

            echo json_encode([
                'success' => false,
                'message' => 'Erro interno no servidor.'
            ]);

            return false;
        }
    }
}
