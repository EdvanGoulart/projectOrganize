<?php

declare(strict_types = 1);

namespace App\Middlewares;

class AuthMiddleware
{
    public function handle()
    {
        if (! auth()) {
            return redirect('/login');
        }
    }
}
