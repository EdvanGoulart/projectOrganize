<?php

declare(strict_types=1);

namespace App\Controllers\Dashboard;


class IndexController
{
    public function __invoke()
    {

        return view('dashboard/index');
    }
}
