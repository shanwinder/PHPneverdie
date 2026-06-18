<?php

declare(strict_types=1);

namespace App\Services;

use App\Core\Database;

class ChallengeSubmissionService
{
    public function submit(int $userId, array $challenge, string $code, int $hintsUsed = 0): array
    {
        $code = $this->cleanCode($code);
        $checker = new ChallengeCheckerService();
        $result = $checker->check($challenge, $code);
        $status = $result['needs_review'] ? 'needs_review' : ($result['passed'] ? 'passed' : 'failed');

        $submissionId = Database::insert(
            'INSERT INTO challenge_submissions (user_id, challenge_id, code, status, score, feedback, hints_used, submitted_at)
             VALUES (:user_id, :challenge_id, :code, :status, :score, :feedback, :hints_used, NOW())',
            [
                'user_id' => $userId,
                'challenge_id' => $challenge['id'],
                'code' => $code,
                'status' => $status,
                'score' => $result['score'],
                'feedback' => $result['feedback'],
                'hints_used' => $hintsUsed,
            ]
        );

        foreach ($result['checks'] as $check) {
            Database::execute(
                'INSERT INTO challenge_submission_checks (submission_id, check_type, check_value, passed, message, created_at)
                 VALUES (:submission_id, :check_type, :check_value, :passed, :message, NOW())',
                [
                    'submission_id' => $submissionId,
                    'check_type' => $check['type'],
                    'check_value' => substr((string) $check['value'], 0, 255),
                    'passed' => $check['passed'] ? 1 : 0,
                    'message' => $check['message'],
                ]
            );
        }

        $xpAwarded = false;
        $newBadges = [];
        if ($status === 'passed' && !user_is_admin()) {
            $xpAwarded = XpService::addOnce(
                $userId,
                'challenge_passed',
                (int) $challenge['id'],
                (int) $challenge['xp_reward'],
                'ผ่าน Challenge: ' . $challenge['title']
            );
            XpService::addOnce($userId, 'first_challenge_passed', 1, 20, 'ผ่าน challenge แรก');
            $newBadges = BadgeService::evaluateForUser($userId);
        }

        return [
            'submission_id' => $submissionId,
            'status' => $status,
            'score' => $result['score'],
            'feedback' => $result['feedback'],
            'checks' => $result['checks'],
            'xp_awarded' => $xpAwarded,
            'new_badges' => $newBadges,
        ];
    }

    public function cleanCode(string $code): string
    {
        $code = substr($code, 0, 20000);
        return preg_replace('/[^\P{C}\t\r\n]/u', '', $code) ?? '';
    }
}
