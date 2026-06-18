<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Database;
use App\Core\Request;
use App\Core\Session;

class ExecutionMonitorController extends Controller
{
    public function index(Request $request): void
    {
        $jobs = Database::select(
            'SELECT execution_jobs.*, users.name AS user_name, challenges.title AS challenge_title
             FROM execution_jobs
             JOIN users ON users.id = execution_jobs.user_id
             LEFT JOIN challenges ON challenges.id = execution_jobs.challenge_id
             ORDER BY execution_jobs.created_at DESC LIMIT 100'
        );
        $stats = Database::select('SELECT status, COUNT(*) AS total FROM execution_jobs GROUP BY status');
        $this->render('admin/execution_monitor/index', ['title' => 'Execution Monitor', 'jobs' => $jobs, 'stats' => $stats], 'admin');
    }

    public function show(Request $request): void
    {
        $job = Database::first(
            'SELECT execution_jobs.*, users.name AS user_name, challenges.title AS challenge_title
             FROM execution_jobs
             JOIN users ON users.id = execution_jobs.user_id
             LEFT JOIN challenges ON challenges.id = execution_jobs.challenge_id
             WHERE execution_jobs.id = :id',
            ['id' => $request->params['id']]
        );
        if (!$job) {
            http_response_code(404);
            view('errors/404', ['title' => 'Execution not found']);
            return;
        }
        $result = Database::first('SELECT * FROM execution_results WHERE execution_job_id = :id ORDER BY id DESC LIMIT 1', ['id' => $job['id']]);
        $cases = Database::select('SELECT * FROM execution_test_case_results WHERE execution_job_id = :id ORDER BY id', ['id' => $job['id']]);
        $this->render('admin/execution_monitor/show', ['title' => 'Execution Detail', 'job' => $job, 'result' => $result, 'cases' => $cases], 'admin');
    }

    public function cancel(Request $request): void
    {
        $this->requireCsrf();
        Database::execute('UPDATE execution_jobs SET status = "cancelled", finished_at = NOW(), updated_at = NOW() WHERE id = :id AND status IN ("queued","running")', ['id' => $request->params['id']]);
        Session::flash('success', 'ยกเลิก job แล้ว');
        redirect('/admin/executions/' . $request->params['id']);
    }
}

