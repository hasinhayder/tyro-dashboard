<?php

namespace HasinHayder\TyroDashboard\Support;

use Illuminate\Support\Carbon;

class LogFileReader {
    /**
     * Log levels recognized in Laravel's default LineFormatter headers.
     */
    public const LEVELS = [
        'emergency', 'alert', 'critical', 'error',
        'warning', 'notice', 'info', 'debug', 'unknown',
    ];

    /**
     * List the application log files in storage/logs, newest first.
     *
     * @return array<int, array{name: string, sizeBytes: int, sizeForHumans: string, modifiedAt: ?string}>
     */
    public function files(): array {
        $directory = storage_path('logs');

        if (! is_dir($directory)) {
            return [];
        }

        $paths = glob($directory.'/*.log') ?: [];

        $files = [];

        foreach ($paths as $path) {
            $modifiedAt = @filemtime($path);

            $files[] = [
                'name' => basename($path),
                'sizeBytes' => (int) @filesize($path),
                'sizeForHumans' => $this->formatBytes((int) @filesize($path)),
                'modifiedAt' => $modifiedAt !== false ? Carbon::createFromTimestamp($modifiedAt)->format('Y-m-d H:i:s') : null,
            ];
        }

        usort($files, function ($a, $b) {
            return strcmp((string) $b['modifiedAt'], (string) $a['modifiedAt']);
        });

        return $files;
    }

    /**
     * Parse a log file into entries, tail-capped at the configured byte limit.
     *
     * @return array{entries: array<int, array{datetime: string, env: string, level: string, message: string, body: string}>, truncated: bool}
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function read(string $file): array {
        $path = $this->resolvePath($file);

        $maxBytes = max(1, (int) config('tyro-dashboard.log_viewer.max_read_bytes', 16777216));
        $size = (int) @filesize($path);

        $truncated = false;
        $startAt = 0;

        if ($size > $maxBytes) {
            $truncated = true;
            $startAt = $size - $maxBytes;
        }

        $handle = @fopen($path, 'rb');

        if ($handle === false) {
            return ['entries' => [], 'truncated' => $truncated];
        }

        if ($startAt > 0) {
            fseek($handle, $startAt);
            // Skip the (likely partial) line at the cap boundary.
            fgets($handle);
        }

        $entries = [];
        $current = null;

        while (($line = fgets($handle)) !== false) {
            $line = rtrim($line, "\r\n");

            if (preg_match('/^\[(\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2})\]\s+([A-Za-z0-9_-]+)\.([A-Za-z]+):\s?(.*)$/', $line, $matches)) {
                if ($current !== null) {
                    $entries[] = $current;
                }

                $level = strtolower($matches[3]);

                $current = [
                    'datetime' => $matches[1],
                    'env' => $matches[2],
                    'level' => in_array($level, self::LEVELS, true) ? $level : 'unknown',
                    'message' => $matches[4],
                    'body' => '',
                ];
            } elseif ($current !== null) {
                $current['body'] .= ($current['body'] === '' ? '' : "\n").$line;
            } else {
                $current = [
                    'datetime' => '',
                    'env' => '',
                    'level' => 'unknown',
                    'message' => $line,
                    'body' => '',
                ];
            }
        }

        if ($current !== null) {
            $entries[] = $current;
        }

        fclose($handle);

        return ['entries' => $entries, 'truncated' => $truncated];
    }

    /**
     * Resolve a log file name to its absolute path, rejecting anything that
     * is not a direct .log child of storage/logs.
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function resolvePath(string $file): string {
        $basename = basename($file);

        if (
            $file === ''
            || $basename !== $file
            || ! preg_match('/^[A-Za-z0-9][A-Za-z0-9._-]*\.log$/', $file)
        ) {
            abort(404);
        }

        $path = storage_path('logs/'.$file);

        $realPath = realpath($path);
        $realBase = realpath(storage_path('logs'));

        if (
            $realPath === false
            || $realBase === false
            || dirname($realPath) !== $realBase
            || ! is_file($realPath)
        ) {
            abort(404);
        }

        return $realPath;
    }

    /**
     * Format bytes for humans.
     */
    public function formatBytes(int $bytes): string {
        if ($bytes < 1024) {
            return round($bytes).' B';
        }

        if ($bytes < 1048576) {
            return round($bytes / 1024, 1).' KB';
        }

        if ($bytes < 1073741824) {
            return round($bytes / 1048576, 1).' MB';
        }

        return round($bytes / 1073741824, 2).' GB';
    }
}
