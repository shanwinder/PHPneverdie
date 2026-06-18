<?php

declare(strict_types=1);

namespace App\Services;

use App\Core\Database;

class CodeExecutionService
{
    public function processJob(array $job): array
    {
        $profile = Database::first('SELECT * FROM runtime_profiles WHERE id = :id', ['id' => $job['runtime_profile_id']]);
        if (!$profile) {
            throw new \RuntimeException('Runtime profile not found');
        }

        $challenge = $job['challenge_id'] ? Database::first('SELECT * FROM challenges WHERE id = :id', ['id' => $job['challenge_id']]) : null;
        if (!$challenge) {
            throw new \RuntimeException('Challenge not found');
        }

        $adapter = new DockerSandboxAdapter();
        if ($job['job_type'] === 'submit' && $challenge['runtime_mode'] === 'testcase') {
            return $this->runTestCases($job, $challenge, $profile, $adapter);
        }

        $run = $adapter->run((string) $job['code'], (string) ($job['input_data'] ?? ''), $profile);
        $passed = $this->singleRunPassed($challenge, $run);
        $status = $this->statusFromRun($run, $passed);
        $this->storeResult((int) $job['id'], $run, $passed, $status === 'passed' ? 'รันโค้ดสำเร็จ' : 'ผลลัพธ์ยังไม่ผ่าน');
        $this->finishSubmissionIfNeeded($job, $challenge, $passed, $status);
        return ['status' => $status, 'error_message' => $run['error']];
    }

    private function runTestCases(array $job, array $challenge, array $profile, DockerSandboxAdapter $adapter): array
    {
        $cases = Database::select('SELECT * FROM challenge_test_cases WHERE challenge_id = :id ORDER BY sort_order, id', ['id' => $challenge['id']]);
        if (!$cases) {
            $run = $adapter->run((string) $job['code'], '', $profile);
            $passed = $this->singleRunPassed($challenge, $run);
            $this->storeResult((int) $job['id'], $run, $passed, 'ไม่มี test case จึงตรวจจาก expected output');
            $this->finishSubmissionIfNeeded($job, $challenge, $passed, $passed ? 'passed' : 'failed');
            return ['status' => $passed ? 'passed' : 'failed', 'error_message' => $run['error']];
        }

        $evaluator = new TestCaseEvaluationService();
        $totalWeight = 0;
        $passedWeight = 0;
        $lastRun = null;
        foreach ($cases as $case) {
            $weight = max(1, (int) $case['weight']);
            $totalWeight += $weight;
            $run = $adapter->run((string) $job['code'], (string) ($case['input_data'] ?? ''), $profile);
            $lastRun = $run;
            $evaluation = $evaluator->evaluate($case, $run['stdout'], (int) $run['exit_code'], $run['stderr']);
            if ($evaluation['passed']) {
                $passedWeight += $weight;
            }
            Database::execute(
                'INSERT INTO execution_test_case_results (execution_job_id, test_case_id, test_name, expected_output, actual_output, stderr, exit_code, passed, duration_ms, message, created_at)
                 VALUES (:job_id, :test_case_id, :test_name, :expected_output, :actual_output, :stderr, :exit_code, :passed, :duration_ms, :message, NOW())',
                [
                    'job_id' => $job['id'],
                    'test_case_id' => $case['id'],
                    'test_name' => $case['name'],
                    'expected_output' => $case['is_hidden'] ? null : ($case['expected_output'] ?? $case['expected_pattern']),
                    'actual_output' => $case['is_hidden'] ? null : $run['stdout'],
                    'stderr' => $case['is_hidden'] ? null : $run['stderr'],
                    'exit_code' => $run['exit_code'],
                    'passed' => $evaluation['passed'] ? 1 : 0,
                    'duration_ms' => $run['duration_ms'],
                    'message' => $case['is_hidden'] && !$evaluation['passed'] ? 'Hidden case ยังไม่ผ่าน' : $evaluation['message'],
                ]
            );
        }

        $passed = $passedWeight === $totalWeight;
        $score = $totalWeight > 0 ? round(($passedWeight / $totalWeight) * 100, 2) : 0;
        $summary = $passed ? 'ผ่านทุก test case' : 'ผ่านบางส่วน: ' . $score . '%';
        $this->storeResult((int) $job['id'], $lastRun ?? [], $passed, $summary);
        $this->finishSubmissionIfNeeded($job, $challenge, $passed, $passed ? 'passed' : 'failed', $score);
        return ['status' => $passed ? 'passed' : 'failed', 'error_message' => null];
    }

    private function singleRunPassed(array $challenge, array $run): bool
    {
        if (!empty($run['timed_out']) || (int) ($run['exit_code'] ?? 1) !== 0) {
            return false;
        }
        $expected = trim((string) ($challenge['expected_output'] ?: $challenge['expected_text']));
        if ($expected === '') {
            return true;
        }
        return trim((string) $run['stdout']) === $expected || str_contains((string) $run['stdout'], $expected);
    }

    private function statusFromRun(array $run, bool $passed): string
    {
        if (!empty($run['timed_out'])) {
            return 'timeout';
        }
        if (!empty($run['error'])) {
            return 'error';
        }
        return $passed ? 'passed' : 'failed';
    }

    private function storeResult(int $jobId, array $run, bool $passed, string $summary): void
    {
        Database::execute(
            'INSERT INTO execution_results (execution_job_id, stdout, stderr, exit_code, duration_ms, output_truncated, passed, result_summary, created_at)
             VALUES (:job_id, :stdout, :stderr, :exit_code, :duration_ms, :output_truncated, :passed, :summary, NOW())',
            [
                'job_id' => $jobId,
                'stdout' => $run['stdout'] ?? '',
                'stderr' => $run['stderr'] ?? '',
                'exit_code' => $run['exit_code'] ?? null,
                'duration_ms' => $run['duration_ms'] ?? null,
                'output_truncated' => !empty($run['output_truncated']) ? 1 : 0,
                'passed' => $passed ? 1 : 0,
                'summary' => $summary,
            ]
        );
    }

    private function finishSubmissionIfNeeded(array $job, array $challenge, bool $passed, string $status, ?float $score = null): void
    {
        if (empty($job['submission_id'])) {
            return;
        }
        $submissionStatus = $passed ? 'passed' : 'failed';
        $finalScore = $score ?? ($passed ? 100 : 0);
        Database::execute(
            'UPDATE challenge_submissions SET status = :status, score = :score, feedback = :feedback WHERE id = :id',
            [
                'id' => $job['submission_id'],
                'status' => $submissionStatus,
                'score' => $finalScore,
                'feedback' => $passed ? 'ผ่าน runtime test cases' : 'runtime status: ' . $status,
            ]
        );
        if ($passed) {
            XpService::addOnce((int) $job['user_id'], 'challenge_passed', (int) $challenge['id'], (int) $challenge['xp_reward'], 'ผ่าน Challenge: ' . $challenge['title']);
            XpService::addOnce((int) $job['user_id'], 'first_challenge_passed', 1, 20, 'ผ่าน challenge แรก');
            BadgeService::evaluateForUser((int) $job['user_id']);
        }
    }
}

