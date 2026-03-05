<?php

declare(strict_types=1);

namespace App\Controllers\Dashboard;

use App\Models\Gamification;

class IndexController
{
    public function __invoke()
    {
        $dashboard = Gamification::getDashboardData((int) auth()->id);

        return view('dashboard/index', [
            'dashboard' => $dashboard,
        ]);
    }
}
