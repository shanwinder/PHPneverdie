<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Database;
use App\Core\Request;
use App\Core\Session;
use App\Services\ProjectReviewService;

class ProjectReviewController extends Controller
{
    public function index(Request $request): void
    {
        $projects = Database::select(
            'SELECT project_submissions.*, users.name AS user_name
             FROM project_submissions JOIN users ON users.id = project_submissions.user_id
             ORDER BY project_submissions.created_at DESC LIMIT 100'
        );
        $this->render('admin/project_reviews/index', ['title' => 'Project Reviews', 'projects' => $projects], 'admin');
    }

    public function show(Request $request): void
    {
        $project = Database::first(
            'SELECT project_submissions.*, users.name AS user_name, users.email AS user_email
             FROM project_submissions JOIN users ON users.id = project_submissions.user_id
             WHERE project_submissions.id = :id',
            ['id' => $request->params['id']]
        );
        if (!$project) {
            http_response_code(404);
            view('errors/404', ['title' => 'Project not found']);
            return;
        }
        $reviews = Database::select('SELECT * FROM project_reviews WHERE project_submission_id = :id ORDER BY reviewed_at DESC', ['id' => $project['id']]);
        $this->render('admin/project_reviews/show', ['title' => 'Review Project', 'project' => $project, 'reviews' => $reviews], 'admin');
    }

    public function review(Request $request): void
    {
        $this->requireCsrf();
        (new ProjectReviewService())->review((int) $request->params['id'], (int) current_user()['id'], $_POST);
        Session::flash('success', 'บันทึกผล review แล้ว');
        redirect('/admin/project-reviews/' . $request->params['id']);
    }
}

