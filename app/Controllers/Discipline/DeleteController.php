<?php

declare(strict_types=1);

namespace App\Controllers\Discipline;

use App\Models\Discipline;
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

        if (Discipline::verificaExisteVinculo($id)) {
            echo json_encode([
                'success' => false,
                'message' => 'Existe tarefas vinculadas a esta disciplina !',
                'id' => $id
            ]);
            exit;
        }

        $deletado = Discipline::delete($id);

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
}
