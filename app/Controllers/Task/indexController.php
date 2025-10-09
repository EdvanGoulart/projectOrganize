<?php

declare(strict_types=1);

namespace App\Controllers\Task;

class IndexController
{
    public function __invoke()
    {
        return view('task/index');
    }
}
