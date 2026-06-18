<?php

declare(strict_types=1);

namespace App\Services;

use App\Core\Database;

class CertificateService
{
    public function issue(int $userId, int $courseId, ?int $projectSubmissionId = null): int
    {
        $existing = Database::first(
            'SELECT id FROM certificates WHERE user_id = :user_id AND course_id = :course_id AND revoked_at IS NULL LIMIT 1',
            ['user_id' => $userId, 'course_id' => $courseId]
        );
        if ($existing) {
            return (int) $existing['id'];
        }

        $course = Database::first('SELECT * FROM courses WHERE id = :id', ['id' => $courseId]);
        $number = sprintf('PMQ-PHPB-%s-%06d', date('Y'), random_int(1, 999999));
        $code = bin2hex(random_bytes(16));
        $id = Database::insert(
            'INSERT INTO certificates (user_id, course_id, project_submission_id, certificate_number, verification_code, title, issued_at, created_at, updated_at)
             VALUES (:user_id, :course_id, :project_submission_id, :certificate_number, :verification_code, :title, NOW(), NOW(), NOW())',
            [
                'user_id' => $userId,
                'course_id' => $courseId,
                'project_submission_id' => $projectSubmissionId,
                'certificate_number' => $number,
                'verification_code' => $code,
                'title' => ($course['title'] ?? 'PHP Beginner') . ' Completion Certificate',
            ]
        );
        AuditLogService::record($userId, 'certificate.issued', 'certificate', $id);
        return $id;
    }

    public function revoke(int $certificateId, int $adminId, string $reason): void
    {
        Database::execute(
            'UPDATE certificates SET revoked_at = NOW(), revoke_reason = :reason, updated_at = NOW() WHERE id = :id',
            ['id' => $certificateId, 'reason' => $reason]
        );
        AuditLogService::record($adminId, 'certificate.revoked', 'certificate', $certificateId);
    }
}

