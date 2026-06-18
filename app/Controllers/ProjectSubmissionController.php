<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Database;
use App\Core\Request;
use App\Core\Session;
use App\Services\ProjectSubmissionService;

class ProjectSubmissionController extends Controller
{
    public function index(Request $request): void
    {
        $rows = Database::select('SELECT * FROM project_submissions WHERE user_id = :user_id ORDER BY created_at DESC', ['user_id' => current_user()['id']]);
        $this->render('projects/index', ['title' => 'My Projects', 'projects' => $rows]);
    }

    public function create(Request $request): void
    {
        $this->render('projects/create', ['title' => 'Submit Project']);
    }

    public function store(Request $request): void
    {
        $this->requireCsrf();
        $id = (new ProjectSubmissionService())->create((int) current_user()['id'], $request->only(['title', 'description', 'submission_type', 'github_url', 'note']));
        Session::flash('success', 'ส่งโปรเจกต์เรียบร้อยแล้ว');
        redirect('/projects/' . $id);
    }

    public function show(Request $request): void
    {
        $project = Database::first('SELECT * FROM project_submissions WHERE id = :id AND user_id = :user_id', ['id' => $request->params['id'], 'user_id' => current_user()['id']]);
        if (!$project) {
            http_response_code(404);
            view('errors/404', ['title' => 'Project not found']);
            return;
        }
        $reviews = Database::select('SELECT * FROM project_reviews WHERE project_submission_id = :id ORDER BY reviewed_at DESC', ['id' => $project['id']]);
        $this->render('projects/show', ['title' => $project['title'], 'project' => $project, 'reviews' => $reviews]);
    }

    public function update(Request $request): void
    {
        $this->requireCsrf();
        $project = $this->editableProject((int) $request->params['id']);
        Database::execute(
            'UPDATE project_submissions SET title = :title, description = :description, submission_type = :submission_type, github_url = :github_url, note = :note, updated_at = NOW() WHERE id = :id',
            [
                'id' => $project['id'],
                'title' => trim((string) $request->input('title')),
                'description' => trim((string) $request->input('description')),
                'submission_type' => in_array($request->input('submission_type'), ['github_url', 'text_note'], true) ? $request->input('submission_type') : 'github_url',
                'github_url' => trim((string) $request->input('github_url')) ?: null,
                'note' => trim((string) $request->input('note')),
            ]
        );
        Session::flash('success', 'อัปเดต project submission แล้ว');
        redirect('/projects/' . $project['id']);
    }

    public function submit(Request $request): void
    {
        $this->requireCsrf();
        $project = $this->editableProject((int) $request->params['id']);
        Database::execute(
            'UPDATE project_submissions SET status = "submitted", submitted_at = NOW(), updated_at = NOW() WHERE id = :id',
            ['id' => $project['id']]
        );
        Session::flash('success', 'ส่ง project ให้ admin review แล้ว');
        redirect('/projects/' . $project['id']);
    }

    private function editableProject(int $id): array
    {
        $project = Database::first('SELECT * FROM project_submissions WHERE id = :id AND user_id = :user_id', ['id' => $id, 'user_id' => current_user()['id']]);
        if (!$project || $project['status'] === 'approved') {
            http_response_code(404);
            view('errors/404', ['title' => 'Project not found']);
            exit;
        }
        return $project;
    }
}
