<?php

declare(strict_types = 1);

namespace App\Controllers;

class LogoutController
{
    public function __invoke()
    {
        session_destroy();

        return redirect('/login');
    }
}
