<?php

declare(strict_types=1);

namespace App\Services;

use App\Core\Database;

class ProjectSubmissionService
{
    public function create(int $userId, array $data): int
    {
        return Database::insert(
            'INSERT INTO project_submissions (user_id, title, description, submission_type, github_url, note, status, submitted_at, created_at, updated_at)
             VALUES (:user_id, :title, :description, :submission_type, :github_url, :note, "submitted", NOW(), NOW(), NOW())',
            [
                'user_id' => $userId,
                'title' => trim((string) $data['title']),
                'description' => trim((string) ($data['description'] ?? '')),
                'submission_type' => (string) ($data['submission_type'] ?? 'github_url'),
                'github_url' => trim((string) ($data['github_url'] ?? '')) ?: null,
                'note' => trim((string) ($data['note'] ?? '')),
            ]
        );
    }
}

