<?php

declare(strict_types=1);

use App\Controllers\IndexController;
use App\Controllers\LoginController;
use App\Controllers\LogoutController;
use App\Controllers\Notas;
use App\Controllers\RegisterController;
use App\Middlewares\AuthMiddleware;
use App\Middlewares\GuestMiddleware;
use App\Controllers\Task;
use App\Controllers\Discipline;
use App\Controllers\Deck;
use App\Models\Deck as ModelsDeck;
use App\Models\Task as ModelsTask;
use Core\Route;

(new Route())

    // Não autenticado -> responsável por login e registro do usuário
    ->get('/', IndexController::class, GuestMiddleware::class)
    ->get('/login', [LoginController::class, 'index'], GuestMiddleware::class)
    ->post('/login', [LoginController::class, 'login'], GuestMiddleware::class)
    ->get('/registrar', [RegisterController::class, 'index'], GuestMiddleware::class)
    ->post('/registrar', [RegisterController::class, 'register'], GuestMiddleware::class)

    // Autenticado ->
    //Rota para deslogar do sisema
    ->get('/logout', LogoutController::class, AuthMiddleware::class)
    //Rota que traz a página inicial, onde é mostrada as tarefas e o gráfico
    ->get('/task', Task\IndexController::class, AuthMiddleware::class)
    //rota responsável por listar as tarefas
    ->get('/task/list', Task\TaskListController::class, AuthMiddleware::class)
    //rota responsável pela criação das tarefas
    ->post('/task/create', [Task\CreateController::class, 'storeAjax'], AuthMiddleware::class)
    ->post('/task/delete', Task\DeleteController::class, AuthMiddleware::class)
    ->get('/task/findTask', [Task\EditController::class, 'findTask'], AuthMiddleware::class)
    ->post('/task/update', [Task\EditController::class, 'updateAjax'], AuthMiddleware::class)
    ->post('/task/update-status', [Task\EditController::class, 'updateStatus'], AuthMiddleware::class)
    ->post('/task/update-order', [Task\EditController::class, 'updateOrder'], AuthMiddleware::class)
    ->get('/task/status-chart', [Task\IndexController::class, 'chartStatus'], AuthMiddleware::class)
    ->get('/task/prioridade-chart', [Task\IndexController::class, 'chartPriority'], AuthMiddleware::class)
    // ->post('/task/edit', Task\EditController::class, AuthMiddleware::class)


    //Rotas responsáveis pelo crud da página de disciplina
    ->get('/discipline', Discipline\IndexController::class, AuthMiddleware::class)
    ->post('/discipline/create', [Discipline\CreateController::class, 'storeAjax'], AuthMiddleware::class)
    ->post('/discipline/delete', Discipline\DeleteController::class, AuthMiddleware::class)
    ->post('/discipline/edit', Discipline\EditController::class, AuthMiddleware::class)


    ->get('/deck-list', [Deck\IndexController::class, 'index'], AuthMiddleware::class)
    ->get('/deck/formCreateDeck', [Deck\IndexController::class, 'formCreateDeck'], AuthMiddleware::class)
    ->post('/deck/create', [Deck\CreateController::class, 'storeAjax'], AuthMiddleware::class)
    ->post('/deck/delete', Deck\DeleteController::class, AuthMiddleware::class)
    ->post('/deck/edit', [Deck\EditController::class, 'findDeckCards'], AuthMiddleware::class)
    ->post('/deck/update', [Deck\EditController::class, 'updateAjax'], AuthMiddleware::class)
    ->post('/cards/delete', [Deck\DeleteController::class, 'deleteCardAjax'], AuthMiddleware::class)
    // ->get('/deck/practice/{id}', [Deck\PracticeController::class, 'index'], AuthMiddleware::class)


    ->run();
