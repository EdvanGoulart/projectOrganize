<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Models\Gamification;
use App\Models\User;
use Core\Database;
use Core\Validacao;

class LoginController
{
    public function index()
    {
        return view('login', template: 'guest');
    }

    public function login()
    {
        $email = request()->post('email');
        $password = request()->post('password');

        $validacao = Validacao::validar([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ], request()->all());

        if ($validacao->naoPassou()) {
            return view('login', template: 'guest');
        }

        $database = new Database(config('database'));

        $user = $database->query(
            query: ' select * from user where email = :email',
            class: User::class,
            params: compact('email')
        )->fetch();

        if (! ($user && password_verify($password, $user->password))) {
            flash()->push('validacoes', ['email' => ['Usuário ou senha estão incorretos!']]);

            return view('login', template: 'guest');
        }

        session()->set('auth', $user);

        Gamification::onLogin((int) $user->id);

        flash()->push('mensagem', 'Seja bem-vindo ' . $user->name . '!');

        return redirect('/task');
    }
}
