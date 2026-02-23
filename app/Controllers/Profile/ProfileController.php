<?php

declare(strict_types=1);

namespace App\Controllers\Profile;

use App\Models\User;
use Core\Database;
use Core\Validacao;

class ProfileController
{
    public function index()
    {
        return view('profile/edit');
    }

    public function update()
    {
        $validacao = Validacao::validar([
            'name'  => ['required'],
            'enrollment'  => ['required'],
            'email'  => ['required', 'email'],
            'phone'  => ['required', 'min:11', 'max:11'],
            'address'  => ['required'],
            'city'  => ['required'],
            'district'  => ['required'],
            'zipcode'  => ['required', 'min:8', 'max:8'],
        ], request()->all());

        if ($validacao->naoPassou('perfil')) {
            return view('profile/edit');
        }

        $novaSenha = trim((string) request()->post('password', ''));

        if (strlen($novaSenha) > 0) {
            $validacaoSenha = Validacao::validar([
                'password' => ['required', 'confirmed', 'strong'],
            ], request()->all());

            if ($validacaoSenha->naoPassou('perfil')) {
                return view('profile/edit');
            }
        }

        $database = new Database(config('database'));
        $authUser = auth();

        $email = request()->post('email');

        $emailEmUso = $database->query(
            query: 'select id from user where email = :email and id != :id',
            params: [
                'email' => $email,
                'id' => $authUser->id,
            ]
        )->fetch();

        if ($emailEmUso) {
            flash()->push('validacoes_perfil', ['email' => ['O email já está sendo usado.']]);

            return view('profile/edit');
        }

        if (strlen($novaSenha) > 0) {
            $database->query(
                query: 'update user set name = :name, enrollment = :enrollment, email = :email, phone = :phone, address = :address, city = :city, district = :district, zipcode = :zipcode, password = :password where id = :id',
                params: [
                    'name' => request()->post('name'),
                    'enrollment' => request()->post('enrollment'),
                    'email' => $email,
                    'phone' => request()->post('phone'),
                    'address' => request()->post('address'),
                    'city' => request()->post('city'),
                    'district' => request()->post('district'),
                    'zipcode' => request()->post('zipcode'),
                    'password' => password_hash($novaSenha, PASSWORD_DEFAULT),
                    'id' => $authUser->id,
                ]
            );
        } else {
            $database->query(
                query: 'update user set name = :name, enrollment = :enrollment, email = :email, phone = :phone, address = :address, city = :city, district = :district, zipcode = :zipcode where id = :id',
                params: [
                    'name' => request()->post('name'),
                    'enrollment' => request()->post('enrollment'),
                    'email' => $email,
                    'phone' => request()->post('phone'),
                    'address' => request()->post('address'),
                    'city' => request()->post('city'),
                    'district' => request()->post('district'),
                    'zipcode' => request()->post('zipcode'),
                    'id' => $authUser->id,
                ]
            );
        }

        $userAtualizado = $database->query(
            query: 'select * from user where id = :id',
            class: User::class,
            params: ['id' => $authUser->id]
        )->fetch();

        session()->set('auth', $userAtualizado);

        flash()->push('mensagem', 'Perfil atualizado com sucesso! ✅');

        return redirect('/perfil');
    }
}
