<?php

declare(strict_types=1);

namespace App\Controllers\Discipline;

use App\Models\Discipline;

class IndexController
{
    public function __invoke()
    {
        $disciplineList = Discipline::all(
            request()->get('pesquisar')
        );

        return view('discipline/index', [
            'disciplineList'           => $disciplineList
        ]);
    }
}
