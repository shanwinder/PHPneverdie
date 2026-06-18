<?php

declare(strict_types=1);

require dirname(__DIR__, 2) . '/app/bootstrap.php';

use App\Services\CodeExecutionService;
use App\Services\ExecutionQueueService;

$queue = new ExecutionQueueService();
$executor = new CodeExecutionService();
$maxJobs = (int) env('WORKER_MAX_JOBS', 20);
$sleepMs = (int) env('WORKER_IDLE_SLEEP_MS', 1000);
$processed = 0;

while ($processed < $maxJobs) {
    $job = $queue->nextQueuedJob();
    if (!$job) {
        usleep(max(100, $sleepMs) * 1000);
        $processed++;
        continue;
    }

    try {
        $result = $executor->processJob($job);
        $queue->markFinished((int) $job['id'], $result);
        echo 'Processed job #' . $job['id'] . ' => ' . $result['status'] . PHP_EOL;
    } catch (Throwable $exception) {
        $queue->markError((int) $job['id'], $exception->getMessage());
        echo 'Job #' . $job['id'] . ' error: ' . $exception->getMessage() . PHP_EOL;
    }
    $processed++;
}

