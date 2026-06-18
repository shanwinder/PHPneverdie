<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Database;
use App\Core\Request;
use App\Core\Session;
use App\Services\ExecutionQueueService;

class ExecutionController extends Controller
{
    public function run(Request $request): void
    {
        $this->requireCsrf();
        $challenge = $this->runtimeChallenge((int) $request->params['id']);
        $this->ensureRunnable($challenge, 'run_button_enabled');

        try {
            $jobId = (new ExecutionQueueService())->createRunJob(
                (int) current_user()['id'],
                $challenge,
                (string) $request->input('code', ''),
                (string) $request->input('input_data', '')
            );
            redirect('/executions/' . $jobId);
        } catch (\Throwable $exception) {
            Session::flash('error', $exception->getMessage());
            redirect('/challenges/' . $challenge['slug']);
        }
    }

    public function submitRuntime(Request $request): void
    {
        $this->requireCsrf();
        $challenge = $this->runtimeChallenge((int) $request->params['id']);
        $this->ensureRunnable($challenge, 'submit_runtime_enabled');

        $code = substr((string) $request->input('code', ''), 0, 50000);
        $submissionId = Database::insert(
            'INSERT INTO challenge_submissions (user_id, challenge_id, code, status, score, feedback, hints_used, submitted_at)
             VALUES (:user_id, :challenge_id, :code, "failed", 0, "รอ worker ตรวจ runtime test cases", :hints_used, NOW())',
            [
                'user_id' => current_user()['id'],
                'challenge_id' => $challenge['id'],
                'code' => $code,
                'hints_used' => (int) Session::get('challenge_hint_' . $challenge['id'], 0),
            ]
        );

        try {
            $jobId = (new ExecutionQueueService())->createSubmitJob((int) current_user()['id'], $challenge, $submissionId, $code);
            redirect('/executions/' . $jobId);
        } catch (\Throwable $exception) {
            Database::execute('UPDATE challenge_submissions SET feedback = :feedback WHERE id = :id', ['id' => $submissionId, 'feedback' => $exception->getMessage()]);
            Session::flash('error', $exception->getMessage());
            redirect('/challenges/' . $challenge['slug']);
        }
    }

    public function show(Request $request): void
    {
        $job = $this->jobForCurrentUser((int) $request->params['id']);
        if (!$job) {
            http_response_code(404);
            view('errors/404', ['title' => 'Execution not found']);
            return;
        }

        $result = Database::first('SELECT * FROM execution_results WHERE execution_job_id = :id ORDER BY id DESC LIMIT 1', ['id' => $job['id']]);
        $cases = Database::select('SELECT * FROM execution_test_case_results WHERE execution_job_id = :id ORDER BY id', ['id' => $job['id']]);
        $view = in_array($job['status'], ['queued', 'running'], true) ? 'executions/pending' : 'executions/result';
        $this->render($view, ['title' => 'Execution Result', 'job' => $job, 'result' => $result, 'cases' => $cases]);
    }

    public function status(Request $request): void
    {
        $job = $this->jobForCurrentUser((int) $request->params['id']);
        if (!$job) {
            http_response_code(404);
            header('Content-Type: application/json');
            echo json_encode(['error' => 'not_found']);
            return;
        }
        $result = Database::first('SELECT * FROM execution_results WHERE execution_job_id = :id ORDER BY id DESC LIMIT 1', ['id' => $job['id']]);
        header('Content-Type: application/json');
        echo json_encode(['id' => (int) $job['id'], 'status' => $job['status'], 'finished' => !in_array($job['status'], ['queued', 'running'], true), 'result' => $result], JSON_UNESCAPED_UNICODE);
    }

    private function runtimeChallenge(int $id): array
    {
        $challenge = Database::first('SELECT * FROM challenges WHERE id = :id AND is_published = 1', ['id' => $id]);
        if (!$challenge) {
            http_response_code(404);
            view('errors/404', ['title' => 'Challenge not found']);
            exit;
        }
        return $challenge;
    }

    private function ensureRunnable(array $challenge, string $flag): void
    {
        if ((int) ($challenge['runtime_enabled'] ?? 0) !== 1 || (int) ($challenge[$flag] ?? 0) !== 1) {
            Session::flash('error', 'Challenge นี้ยังไม่ได้เปิด runtime execution');
            redirect('/challenges/' . $challenge['slug']);
        }
    }

    private function jobForCurrentUser(int $id): ?array
    {
        return Database::first(
            'SELECT execution_jobs.*, challenges.title AS challenge_title, challenges.slug AS challenge_slug
             FROM execution_jobs
             LEFT JOIN challenges ON challenges.id = execution_jobs.challenge_id
             WHERE execution_jobs.id = :id AND execution_jobs.user_id = :user_id',
            ['id' => $id, 'user_id' => current_user()['id']]
        );
    }
}

