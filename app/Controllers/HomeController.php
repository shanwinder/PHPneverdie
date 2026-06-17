<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Database;
use App\Core\Request;

class HomeController extends Controller
{
    public function index(Request $request): void
    {
        $course = Database::first(
            'SELECT courses.*, COUNT(lessons.id) AS lessons_count
             FROM courses
             LEFT JOIN modules ON modules.course_id = courses.id AND modules.is_published = 1
             LEFT JOIN lessons ON lessons.module_id = modules.id AND lessons.is_published = 1
             WHERE courses.is_published = 1
             GROUP BY courses.id
             ORDER BY courses.sort_order
             LIMIT 1'
        );

        $this->render('home', ['title' => 'เรียน PHP แบบเป็นขั้นตอน', 'course' => $course]);
    }
}
