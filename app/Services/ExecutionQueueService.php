<?php

declare(strict_types=1);

namespace App\Services;

use App\Core\Database;

class ExecutionQueueService
{
    public function createRunJob(int $userId, array $challenge, string $code, string $input = ''): int
    {
        return $this->createJob($userId, $challenge, null, 'run', $code, $input);
    }

    public function createSubmitJob(int $userId, array $challenge, int $submissionId, string $code): int
    {
        return $this->createJob($userId, $challenge, $submissionId, 'submit', $code, '');
    }

    public function nextQueuedJob(): ?array
    {
        $pdo = Database::connection();
        $pdo->beginTransaction();
        try {
            $job = Database::first(
                'SELECT * FROM execution_jobs WHERE status = "queued" ORDER BY queued_at, id LIMIT 1 FOR UPDATE'
            );
            if (!$job) {
                $pdo->commit();
                return null;
            }
            $this->markRunning((int) $job['id'], (string) env('WORKER_ID', 'local-worker-1'));
            $pdo->commit();
            return Database::first('SELECT * FROM execution_jobs WHERE id = :id', ['id' => $job['id']]);
        } catch (\Throwable $exception) {
            $pdo->rollBack();
            throw $exception;
        }
    }

    public function markRunning(int $jobId, string $workerId): void
    {
        Database::execute(
            'UPDATE execution_jobs SET status = "running", started_at = NOW(), worker_id = :worker_id, updated_at = NOW() WHERE id = :id',
            ['id' => $jobId, 'worker_id' => $workerId]
        );
    }

    public function markFinished(int $jobId, array $result): void
    {
        Database::execute(
            'UPDATE execution_jobs SET status = :status, finished_at = NOW(), error_message = :error_message, updated_at = NOW() WHERE id = :id',
            ['id' => $jobId, 'status' => $result['status'], 'error_message' => $result['error_message'] ?? null]
        );
    }

    public function markError(int $jobId, string $message): void
    {
        Database::execute(
            'UPDATE execution_jobs SET status = "error", finished_at = NOW(), error_message = :message, updated_at = NOW() WHERE id = :id',
            ['id' => $jobId, 'message' => $message]
        );
    }

    private function createJob(int $userId, array $challenge, ?int $submissionId, string $jobType, string $code, string $input): int
    {
        (new RateLimitService())->assertExecutionAllowed($userId, $jobType);
        $profile = (new RuntimeProfileService())->forChallenge($challenge);
        if (!$profile) {
            throw new \RuntimeException('ยังไม่มี runtime profile ที่เปิดใช้งาน');
        }

        $maxCode = (int) $profile['max_code_bytes'];
        $code = substr($code, 0, $maxCode);
        $jobId = Database::insert(
            'INSERT INTO execution_jobs (user_id, challenge_id, submission_id, runtime_profile_id, job_type, status, code, input_data, queued_at, created_at, updated_at)
             VALUES (:user_id, :challenge_id, :submission_id, :runtime_profile_id, :job_type, "queued", :code, :input_data, NOW(), NOW(), NOW())',
            [
                'user_id' => $userId,
                'challenge_id' => (int) $challenge['id'],
                'submission_id' => $submissionId,
                'runtime_profile_id' => (int) $profile['id'],
                'job_type' => $jobType,
                'code' => $code,
                'input_data' => $input,
            ]
        );
        AuditLogService::record($userId, 'execution.job.created', 'execution_job', $jobId, ['job_type' => $jobType]);
        return $jobId;
    }
}

