<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Database;
use App\Core\Request;
use App\Services\ProgressService;

class CourseController extends Controller
{
    public function index(Request $request): void
    {
        $courses = Database::select(
            'SELECT courses.*, COUNT(lessons.id) AS lessons_count
             FROM courses
             LEFT JOIN modules ON modules.course_id = courses.id AND modules.is_published = 1
             LEFT JOIN lessons ON lessons.module_id = modules.id AND lessons.is_published = 1
             WHERE courses.is_published = 1
             GROUP BY courses.id
             ORDER BY courses.sort_order, courses.id'
        );

        $this->render('courses/index', ['title' => 'Learning Path', 'courses' => $courses]);
    }

    public function show(Request $request): void
    {
        $course = Database::first('SELECT * FROM courses WHERE slug = :slug AND is_published = 1', ['slug' => $request->params['slug']]);
        if (!$course) {
            http_response_code(404);
            view('errors/404', ['title' => 'Course not found']);
            return;
        }

        $modules = Database::select(
            'SELECT * FROM modules WHERE course_id = :course_id AND is_published = 1 ORDER BY sort_order, id',
            ['course_id' => $course['id']]
        );

        $statuses = current_user() ? ProgressService::statusMap((int) current_user()['id']) : [];
        foreach ($modules as &$module) {
            $module['lessons'] = Database::select(
                'SELECT * FROM lessons WHERE module_id = :module_id AND is_published = 1 ORDER BY sort_order, id',
                ['module_id' => $module['id']]
            );
        }

        $this->render('courses/show', [
            'title' => $course['title'],
            'course' => $course,
            'modules' => $modules,
            'statuses' => $statuses,
        ]);
    }
}
