<?php

declare(strict_types=1);

namespace App\Services;

use App\Core\Database;

class ProjectReviewService
{
    public function review(int $projectId, int $reviewerId, array $data): void
    {
        $scores = [
            'database_score' => min(20, max(0, (int) ($data['database_score'] ?? 0))),
            'security_score' => min(20, max(0, (int) ($data['security_score'] ?? 0))),
            'ui_score' => min(20, max(0, (int) ($data['ui_score'] ?? 0))),
            'code_structure_score' => min(20, max(0, (int) ($data['code_structure_score'] ?? 0))),
            'feature_score' => min(20, max(0, (int) ($data['feature_score'] ?? 0))),
        ];
        $total = array_sum($scores);
        $decision = in_array($data['decision'] ?? '', ['approved', 'rejected', 'revision_requested'], true) ? $data['decision'] : 'revision_requested';

        Database::execute(
            'INSERT INTO project_reviews (project_submission_id, reviewer_id, database_score, security_score, ui_score, code_structure_score, feature_score, total_score, feedback, decision, reviewed_at)
             VALUES (:project_id, :reviewer_id, :database_score, :security_score, :ui_score, :code_structure_score, :feature_score, :total_score, :feedback, :decision, NOW())',
            ['project_id' => $projectId, 'reviewer_id' => $reviewerId, 'total_score' => $total, 'feedback' => trim((string) ($data['feedback'] ?? '')), 'decision' => $decision] + $scores
        );
        Database::execute(
            'UPDATE project_submissions SET status = :status, reviewed_at = NOW(), reviewed_by = :reviewer_id, updated_at = NOW() WHERE id = :id',
            ['id' => $projectId, 'status' => $decision, 'reviewer_id' => $reviewerId]
        );
        AuditLogService::record($reviewerId, 'project.reviewed', 'project_submission', $projectId, ['decision' => $decision, 'total' => $total]);
    }
}

