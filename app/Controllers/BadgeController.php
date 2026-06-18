<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Database;
use App\Core\Request;

class BadgeController extends Controller
{
    public function index(Request $request): void
    {
        $badges = Database::select(
            'SELECT badges.*, user_badges.awarded_at
             FROM badges
             LEFT JOIN user_badges ON user_badges.badge_id = badges.id AND user_badges.user_id = :user_id
             WHERE badges.is_active = 1
             ORDER BY badges.sort_order, badges.id',
            ['user_id' => current_user()['id']]
        );

        $this->render('badges/index', ['title' => 'My Badges', 'badges' => $badges]);
    }
}
