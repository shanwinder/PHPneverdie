<?php

declare(strict_types=1);

namespace App\Services;

use App\Core\Database;

class AuditLogService
{
    public static function record(?int $userId, string $action, ?string $entityType = null, string|int|null $entityId = null, array $metadata = []): void
    {
        Database::execute(
            'INSERT INTO audit_logs (user_id, action, entity_type, entity_id, ip_address, user_agent, metadata_json, created_at)
             VALUES (:user_id, :action, :entity_type, :entity_id, :ip_address, :user_agent, :metadata_json, NOW())',
            [
                'user_id' => $userId,
                'action' => $action,
                'entity_type' => $entityType,
                'entity_id' => $entityId === null ? null : (string) $entityId,
                'ip_address' => $_SERVER['REMOTE_ADDR'] ?? null,
                'user_agent' => substr((string) ($_SERVER['HTTP_USER_AGENT'] ?? ''), 0, 500),
                'metadata_json' => $metadata ? json_encode($metadata, JSON_UNESCAPED_UNICODE) : null,
            ]
        );
    }
}

