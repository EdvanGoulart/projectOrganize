<?php

declare(strict_types=1);

namespace App\Controllers\Achievement;

use App\Models\Gamification;

class IndexController
{
    public function __invoke()
    {
        $board = Gamification::getAchievementsBoardData((int) auth()->id);

        return view('achievement/index', [
            'board' => $board,
        ]);
    }
}
