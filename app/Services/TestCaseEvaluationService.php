<?php

declare(strict_types=1);

namespace App\Services;

class TestCaseEvaluationService
{
    public function evaluate(array $testCase, string $stdout, int $exitCode, string $stderr): array
    {
        if ($exitCode !== 0) {
            return ['passed' => false, 'message' => 'โปรแกรมจบด้วย exit code ' . $exitCode];
        }

        $expected = (string) ($testCase['expected_output'] ?? '');
        $actual = $stdout;
        $mode = (string) ($testCase['comparison_mode'] ?? 'trimmed');
        $passed = match ($mode) {
            'exact' => $actual === $expected,
            'contains' => $expected !== '' && str_contains($actual, $expected),
            'regex' => $this->matchesRegex((string) ($testCase['expected_pattern'] ?: $expected), $actual),
            default => trim($actual) === trim($expected),
        };

        return [
            'passed' => $passed,
            'message' => $passed ? 'ผ่าน test case' : 'ผลลัพธ์ยังไม่ตรงกับที่คาดหวัง',
        ];
    }

    private function matchesRegex(string $pattern, string $actual): bool
    {
        if ($pattern === '') {
            return false;
        }
        $regex = str_starts_with($pattern, '/') ? $pattern : '/' . str_replace('/', '\/', $pattern) . '/';
        return @preg_match($regex, $actual) === 1;
    }
}

