<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Request;
use App\Services\LeaderboardService;

class LeaderboardController extends Controller
{
    public function index(Request $request): void
    {
        $rows = LeaderboardService::top();
        $this->render('leaderboard/index', [
            'title' => 'Leaderboard',
            'rows' => $rows,
            'currentUserId' => (int) current_user()['id'],
        ]);
    }
}
