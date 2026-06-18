<?php

declare(strict_types=1);

namespace App\Services;

class ChallengeCheckerService
{
    public const DEFAULT_FORBIDDEN = [
        'eval',
        'exec',
        'shell_exec',
        'system',
        'passthru',
        'proc_open',
        'popen',
        'unlink',
        'file_put_contents',
        'fopen',
        'include',
        'require',
        'include_once',
        'require_once',
    ];

    public function check(array $challenge, string $code): array
    {
        $code = trim($code);
        if ($code === '') {
            return [
                'passed' => false,
                'needs_review' => false,
                'score' => 0,
                'feedback' => 'ยังไม่มี code ให้ตรวจ ลองเขียนคำตอบก่อนส่งอีกครั้ง',
                'checks' => [],
            ];
        }

        if (($challenge['checking_mode'] ?? '') === 'manual') {
            return [
                'passed' => false,
                'needs_review' => true,
                'score' => 0,
                'feedback' => 'ระบบรับคำตอบไว้แล้ว โจทย์นี้ต้องรอ admin ตรวจเพิ่มเติม',
                'checks' => [],
            ];
        }

        $checks = [];
        $score = 0;
        $expectedText = trim((string) ($challenge['expected_text'] ?? ''));
        if ($expectedText !== '') {
            $passed = stripos($code, $expectedText) !== false;
            $checks[] = [
                'type' => 'expected_text',
                'value' => $expectedText,
                'passed' => $passed,
                'message' => $passed ? 'พบข้อความที่โจทย์ต้องการแล้ว' : 'ยังไม่พบข้อความ "' . $expectedText . '"',
            ];
            if ($passed) {
                $score += 40;
            }
        }

        $required = $challenge['required_keywords'] ?? [];
        $requiredPassed = 0;
        foreach ($required as $row) {
            $keyword = trim((string) ($row['keyword'] ?? $row));
            if ($keyword === '') {
                continue;
            }
            $passed = stripos($code, $keyword) !== false;
            $checks[] = [
                'type' => 'required_keyword',
                'value' => $keyword,
                'passed' => $passed,
                'message' => $passed
                    ? (($row['message'] ?? '') ?: 'พบ ' . $keyword . ' แล้ว')
                    : (($row['message'] ?? '') ?: 'ยังไม่พบ ' . $keyword),
            ];
            if ($passed) {
                $requiredPassed++;
            }
        }
        if (count($required) > 0 && $requiredPassed === count($required)) {
            $score += 40;
        } elseif (count($required) === 0) {
            $score += 40;
        }

        $forbidden = array_values(array_filter(array_unique(array_merge(
            self::DEFAULT_FORBIDDEN,
            array_map(static fn ($row) => (string) ($row['keyword'] ?? $row), $challenge['forbidden_keywords'] ?? [])
        ))));
        $forbiddenOk = true;
        foreach ($forbidden as $keyword) {
            $keyword = trim($keyword);
            if ($keyword === '') {
                continue;
            }
            $passed = stripos($code, $keyword) === false;
            if (!$passed) {
                $forbiddenOk = false;
            }
            $checks[] = [
                'type' => 'forbidden_keyword',
                'value' => $keyword,
                'passed' => $passed,
                'message' => $passed ? 'ไม่พบคำสั่งเสี่ยง ' . $keyword : 'พบคำสั่งที่ไม่อนุญาต: ' . $keyword,
            ];
        }
        if ($forbiddenOk) {
            $score += 20;
        }

        if (($challenge['checking_mode'] ?? '') === 'pattern' && trim((string) ($challenge['expected_output'] ?? '')) !== '') {
            $pattern = trim((string) $challenge['expected_output']);
            $passed = @preg_match($pattern, $code) === 1;
            $checks[] = [
                'type' => 'pattern',
                'value' => substr($pattern, 0, 255),
                'passed' => $passed,
                'message' => $passed ? 'รูปแบบ code ตรงตาม pattern แล้ว' : 'รูปแบบ code ยังไม่ตรงตามที่โจทย์กำหนด',
            ];
            if (!$passed) {
                $score = min($score, 80);
            }
        }

        $failedChecks = array_filter($checks, static fn (array $check): bool => !$check['passed']);
        $passed = count($failedChecks) === 0 && $score >= 60;

        return [
            'passed' => $passed,
            'needs_review' => false,
            'score' => $passed ? 100 : min($score, 99),
            'feedback' => $passed ? 'ผ่านแล้ว! เงื่อนไขของโจทย์ครบถ้วน' : $this->feedback($failedChecks),
            'checks' => $checks,
        ];
    }

    private function feedback(array $failedChecks): string
    {
        $messages = array_values(array_map(static fn (array $check): string => $check['message'], $failedChecks));
        if ($messages === []) {
            return 'ยังไม่ผ่าน ลองตรวจคำตอบอีกครั้ง';
        }

        return implode("\n", array_slice($messages, 0, 4));
    }
}
