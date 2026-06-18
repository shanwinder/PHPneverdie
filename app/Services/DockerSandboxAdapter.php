<?php

declare(strict_types=1);

namespace App\Services;

class DockerSandboxAdapter
{
    public function run(string $code, string $input, array $profile): array
    {
        if (!env('SANDBOX_ENABLED', false)) {
            return $this->mockRun($code, $input, $profile);
        }

        $driver = (string) env('SANDBOX_DRIVER', 'docker');
        if ($driver === 'mock') {
            return $this->mockRun($code, $input, $profile);
        }

        $workDir = base_path((string) env('SANDBOX_WORK_DIR', 'storage/sandbox/jobs'));
        if (!is_dir($workDir)) {
            mkdir($workDir, 0775, true);
        }

        $jobDir = $workDir . '/' . bin2hex(random_bytes(12));
        mkdir($jobDir, 0775, true);
        file_put_contents($jobDir . '/main.php', $code);
        file_put_contents($jobDir . '/input.txt', $input);

        $image = (string) ($profile['docker_image'] ?: env('SANDBOX_PHP_IMAGE', 'php-mastery-sandbox-php:8.3'));
        $memoryMb = max(16, (int) $profile['memory_mb']);
        $network = ((int) $profile['network_enabled'] === 1) ? 'bridge' : 'none';
        $maxOutput = (int) $profile['max_output_bytes'];
        $mount = $jobDir . ':/workspace:ro';

        $command = [
            'docker',
            'run',
            '--rm',
            '--network',
            $network,
            '--memory',
            $memoryMb . 'm',
            '--cpus',
            '0.5',
            '--read-only',
            '--tmpfs',
            '/tmp:rw,noexec,nosuid,size=16m',
            '--user',
            '1000:1000',
            '-v',
            $mount,
            $image,
            'sh',
            '-lc',
            'php /sandbox/runner.php /workspace/main.php < /workspace/input.txt',
        ];

        $started = microtime(true);
        $timeoutMs = max(500, (int) $profile['timeout_ms']);
        $descriptor = [
            1 => ['pipe', 'w'],
            2 => ['pipe', 'w'],
        ];
        $process = proc_open($this->escapeCommand($command), $descriptor, $pipes);
        if (!is_resource($process)) {
            $this->cleanup($jobDir);
            return $this->errorResult('ไม่สามารถเริ่ม Docker sandbox ได้');
        }

        stream_set_blocking($pipes[1], false);
        stream_set_blocking($pipes[2], false);
        $stdout = '';
        $stderr = '';
        $timedOut = false;
        $exitCode = null;

        while (true) {
            $stdout .= stream_get_contents($pipes[1]) ?: '';
            $stderr .= stream_get_contents($pipes[2]) ?: '';
            $status = proc_get_status($process);
            $duration = (int) round((microtime(true) - $started) * 1000);
            if (!$status['running']) {
                $exitCode = $status['exitcode'];
                break;
            }
            if ($duration > $timeoutMs) {
                $timedOut = true;
                proc_terminate($process);
                break;
            }
            if (strlen($stdout) + strlen($stderr) > ($maxOutput * 2)) {
                proc_terminate($process);
                break;
            }
            usleep(20000);
        }

        $stdout .= stream_get_contents($pipes[1]) ?: '';
        $stderr .= stream_get_contents($pipes[2]) ?: '';
        fclose($pipes[1]);
        fclose($pipes[2]);
        $closeCode = proc_close($process);
        $exitCode = $exitCode ?? $closeCode;
        $duration = (int) round((microtime(true) - $started) * 1000);
        $this->cleanup($jobDir);

        $combinedTruncated = false;
        if (strlen($stdout) > $maxOutput) {
            $stdout = substr($stdout, 0, $maxOutput);
            $combinedTruncated = true;
        }
        if (strlen($stderr) > $maxOutput) {
            $stderr = substr($stderr, 0, $maxOutput);
            $combinedTruncated = true;
        }

        return [
            'stdout' => $stdout,
            'stderr' => $stderr,
            'exit_code' => $exitCode,
            'duration_ms' => $duration,
            'output_truncated' => $combinedTruncated,
            'timed_out' => $timedOut || $duration > $timeoutMs,
            'error' => null,
        ];
    }

    private function mockRun(string $code, string $input, array $profile): array
    {
        $maxOutput = (int) ($profile['max_output_bytes'] ?? 20000);
        $stdout = '';
        if (preg_match_all('/echo\s+["\']([^"\']*)["\']/i', $code, $matches)) {
            $stdout = implode('', $matches[1]);
        } elseif (str_contains($code, 'fgets(STDIN)')) {
            $stdout = trim($input);
        } else {
            $stdout = "Sandbox mock mode: job accepted. Enable Docker to execute PHP.\n";
        }

        $truncated = strlen($stdout) > $maxOutput;
        return [
            'stdout' => $truncated ? substr($stdout, 0, $maxOutput) : $stdout,
            'stderr' => '',
            'exit_code' => 0,
            'duration_ms' => 1,
            'output_truncated' => $truncated,
            'timed_out' => false,
            'error' => null,
        ];
    }

    private function errorResult(string $message): array
    {
        return [
            'stdout' => '',
            'stderr' => $message,
            'exit_code' => 1,
            'duration_ms' => 0,
            'output_truncated' => false,
            'timed_out' => false,
            'error' => $message,
        ];
    }

    private function escapeCommand(array $parts): string
    {
        return implode(' ', array_map('escapeshellarg', $parts));
    }

    private function cleanup(string $dir): void
    {
        if (!is_dir($dir)) {
            return;
        }
        foreach (glob($dir . '/*') ?: [] as $file) {
            if (is_file($file)) {
                unlink($file);
            }
        }
        @rmdir($dir);
    }
}
