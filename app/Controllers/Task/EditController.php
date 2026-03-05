<?php

declare(strict_types=1);

namespace App\Controllers\Task;

use App\Models\Gamification;
use App\Models\Task;
use Core\Validacao;

class EditController
{
    public function findTask()
    {
        header('Content-Type: application/json');
        $id = (int) $_GET['id'];
        // Busca a task pelo ID
        $task = Task::find($id);

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

    public function updateAjax()
    {
        $validacao = Validacao::validar(array_merge(
            [
                'id'     => ['required'],
            ],
        ), request()->all());

        if ($validacao->naoPassou()) {
            // Retorna erros em JSON
            header('Content-Type: application/json');
            echo json_encode([
                'success' => false,
                'errors'  => $validacao->erros()  // supondo que esse método exista
            ]);
            return;
        }

        $taskBefore = Task::find((int) request()->post('id'));

        $idTask = Task::update(
            request()->post('id'),
            request()->post('name'),
            request()->post('description'),
            request()->post('status'),
            request()->post('priority'),
            request()->post('discipline'),
            request()->post('endDate'),
        );

        $this->awardTaskCompletionXp($taskBefore?->status ?? null, (string) request()->post('status'), (int) request()->post('id'));

        header('Content-Type: application/json');
        echo json_encode([
            'success' => true,
            'data' => [
                'id' => $idTask,
                'name' => request()->post('name'),
                'description' => request()->post('description'),
                'status' => request()->post('status'),
                'priority' => request()->post('priority'),
                'idDiscipline' => request()->post('discipline'),
                'idDiscipline' => request()->post('endDate'),
            ]
        ]);
    }

    public function updateStatus()
    {
        header('Content-Type: application/json');

        $id = isset($_POST['id']) ? (int) $_POST['id'] : null;
        $status = isset($_POST['status']) ? trim($_POST['status']) : null;

        if (!$id || !$status) {
            echo json_encode([
                'success' => false,
                'message' => 'Dados inválidos para atualização de status.'
            ]);
            return;
        }


        $taskBefore = Task::find($id);

        // Atualiza o status via classe Task
        $updated = Task::updateStatus($id, $status);

        if ($updated) {
            $this->awardTaskCompletionXp($taskBefore?->status ?? null, $status, $id);

            echo json_encode([
                'success' => true,
                'message' => 'Status atualizado com sucesso!'
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Falha ao atualizar o status da tarefa.'
            ]);
        }
    }

    public function updateOrder()
    {
        header('Content-Type: application/json');

        $id = $_POST['id'] ?? null;
        $status = $_POST['status'] ?? null;
        $position = $_POST['position'] ?? null;

        if (!$id || !$status) {
            echo json_encode([
                'success' => false,
                'message' => 'Dados incompletos para atualização.'
            ]);
            return;
        }

        $taskBefore = Task::find((int) $id);
        $updated = Task::updateOrder((int)$id, $status, (int)$position);

        if ($updated) {

            $this->awardTaskCompletionXp($taskBefore?->status ?? null, (string) $status, (int) $id);

            echo json_encode([
                'success' => true,
                'message' => 'Ordem e status atualizados com sucesso!'
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Falha ao atualizar ordem ou status.'
            ]);
        }
    }

    private function awardTaskCompletionXp(?string $previousStatus, string $newStatus, int $taskId): void
    {
        if ($newStatus === 'completed' && $previousStatus !== 'completed') {
            Gamification::onTaskCompleted((int) auth()->id, $taskId);
        }
    }
}
