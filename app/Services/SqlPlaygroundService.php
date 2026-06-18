<?php

declare(strict_types=1);

namespace App\Services;

use App\Core\Database;
use PDO;

class SqlPlaygroundService
{
    public function run(array $playground, string $query): array
    {
        $query = trim($query);
        $rejection = $this->validateQuery($query);
        if ($rejection !== null) {
            return ['ok' => false, 'rows' => [], 'feedback' => $rejection, 'preview' => null];
        }

        $pdo = new PDO('sqlite::memory:');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        try {
            $pdo->exec((string) $playground['schema_sql']);
            $pdo->exec((string) $playground['seed_sql']);
            $statement = $pdo->query('SELECT * FROM (' . $query . ') AS pmq_result LIMIT 100');
            $rows = $statement ? $statement->fetchAll(PDO::FETCH_ASSOC) : [];
            return [
                'ok' => true,
                'rows' => $rows,
                'feedback' => 'Query สำเร็จ',
                'preview' => json_encode($rows, JSON_UNESCAPED_UNICODE),
            ];
        } catch (\Throwable $exception) {
            return ['ok' => false, 'rows' => [], 'feedback' => $exception->getMessage(), 'preview' => null];
        }
    }

    public function submit(int $userId, array $playground, string $query): array
    {
        $result = $this->run($playground, $query);
        $expected = trim((string) ($playground['expected_result_json'] ?? ''));
        $passed = $result['ok'] && ($expected === '' || json_encode($result['rows'], JSON_UNESCAPED_UNICODE) === $expected);
        Database::execute(
            'INSERT INTO sql_submissions (user_id, sql_playground_id, query_text, status, result_preview, feedback, submitted_at)
             VALUES (:user_id, :playground_id, :query_text, :status, :result_preview, :feedback, NOW())',
            [
                'user_id' => $userId,
                'playground_id' => $playground['id'],
                'query_text' => $query,
                'status' => $passed ? 'passed' : ($result['ok'] ? 'failed' : 'error'),
                'result_preview' => $result['preview'],
                'feedback' => $passed ? 'ผ่าน SQL playground' : $result['feedback'],
            ]
        );
        if ($passed) {
            XpService::addOnce($userId, 'sql_playground_passed', (int) $playground['id'], (int) $playground['xp_reward'], 'ผ่าน SQL Playground: ' . $playground['title']);
        }
        $result['passed'] = $passed;
        return $result;
    }

    private function validateQuery(string $query): ?string
    {
        if (!preg_match('/^\s*select\b/i', $query)) {
            return 'Phase 3 SQL Playground อนุญาตเฉพาะ SELECT เท่านั้น';
        }
        if (substr_count($query, ';') > 0) {
            return 'ไม่อนุญาตหลาย statement หรือ semicolon';
        }
        if (preg_match('/\b(drop|delete|update|insert|alter|create|grant|revoke|attach|detach|pragma|load|file)\b/i', $query)) {
            return 'พบ keyword ที่ไม่อนุญาตใน SQL Playground';
        }
        return null;
    }
}
