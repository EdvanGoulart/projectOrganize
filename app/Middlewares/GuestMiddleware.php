<?php

declare(strict_types = 1);

namespace App\Middlewares;

class GuestMiddleware
{
    public function handle()
    {
        if (auth()) {
            return redirect('/notas');
        }
    }
}
