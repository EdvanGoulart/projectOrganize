<?php

declare(strict_types=1);

namespace App\Controllers;

use Core\Database;
use Core\Validacao;

class RegisterController
{
    public function index()
    {
        return view('registrar', template: 'guest');
    }

    public function register()
    {
        $validacao = Validacao::validar([
            'name'  => ['required'],
            'password' => ['required', 'confirmed', 'strong'],
            'enrollment'  => ['required'],
            'email'  => ['required', 'email', 'unique:user'],
            'phone'  => ['required', 'min:11', 'max:11'],
            'address'  => ['required'],
            'city'  => ['required'],
            'district'  => ['required'],
            'zipcode'  => ['required', 'min:8', 'max:8'],
        ], request()->all());

        if ($validacao->naoPassou()) {
            return view('registrar', template: 'guest');
        }

        $database = new Database(config('database'));

        $database->query(
            query: 'insert into user (name, password, enrollment, email, phone, address, city, district, zipcode  ) values (:name, :password, :enrollment, :email, :phone, :address, :city, :district, :zipcode)',
            params: [
                'name'  => request()->post('name'),
                'password' => password_hash(request()->post('password'), PASSWORD_DEFAULT),
                'enrollment' => request()->post('enrollment'),
                'email' => request()->post('email'),
                'phone' => request()->post('phone'),
                'address' => request()->post('address'),
                'city' => request()->post('city'),
                'district' => request()->post('district'),
                'zipcode' => request()->post('zipcode'),
            ]
        );

        flash()->push('mensagem', 'Registrado com sucesso! 👍');

        return redirect('/login');
    }
}
