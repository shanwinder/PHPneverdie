<?php

declare(strict_types=1);

namespace App\Services;

use App\Core\Database;

class RateLimitService
{
    public function assertExecutionAllowed(int $userId, string $jobType): void
    {
        $limits = [
            'run' => 10,
            'submit' => 5,
            'sql_run' => 10,
            'sql_submit' => 5,
        ];
        $limit = $limits[$jobType] ?? 5;
        $row = Database::first(
            'SELECT COUNT(*) AS total FROM execution_jobs
             WHERE user_id = :user_id AND job_type = :job_type AND queued_at >= DATE_SUB(NOW(), INTERVAL 1 MINUTE)',
            ['user_id' => $userId, 'job_type' => $jobType]
        );

        if ((int) ($row['total'] ?? 0) >= $limit) {
            throw new \RuntimeException('คุณส่งคำขอรันถี่เกินไป กรุณารอสักครู่แล้วลองใหม่');
        }
    }
}

