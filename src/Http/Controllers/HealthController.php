<?php

namespace HasinHayder\TyroDashboard\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Schema;
use Laravel\Horizon\Horizon;
use PDO;
use Predis\Client;

class HealthController extends BaseController {
    /**
     * Cache key for the expensive probe bucket (60 seconds).
     */
    private const BUCKET_KEY = 'tyro-dashboard.health';

    /**
     * Self-deleting ping key for the cache round-trip probe (10s TTL + forget).
     */
    private const PING_KEY = 'tyro-dashboard.health-ping';

    /**
     * Show the read-only System Health page.
     *
     * Ordered hybrid strategy: (1) the cache round-trip probe always runs
     * live so the latency metric is honest by construction; (2) only when it
     * passed, the expensive probe bucket is served through Cache::remember
     * for 60 seconds — never otherwise, a broken store degrades to one card
     * instead of an exception; (3) PHP memory probes always run live.
     */
    public function index(Request $request) {
        $php = $this->collectPhp();
        $cache = $this->probeCache();

        $bucket = null;
        $bucketCached = false;

        if (($cache['available'] ?? false) && ($cache['passed'] ?? false)) {
            try {
                $bucket = Cache::remember(self::BUCKET_KEY, now()->addSeconds(60), function () {
                    return $this->collectBucket();
                });
                $bucketCached = true;
            } catch (\Throwable $e) {
                $bucket = null;
            }
        }

        if (! is_array($bucket)) {
            $bucket = $this->collectBucket();
            $bucketCached = false;
        }

        $bucket = $this->decorateBucket($bucket);

        $diskFreeRow = null;
        foreach ($bucket['disk']['rows'] ?? [] as $row) {
            if ($row['available']) {
                $diskFreeRow = $row;
            }
        }

        return view('tyro-dashboard::health.index', $this->getViewData([
            'php' => $php,
            'cache' => $cache,
            'database' => $bucket['database'],
            'queue' => $bucket['queue'],
            'disk' => $bucket['disk'],
            'opcache' => $bucket['opcache'],
            'storage' => $bucket['storage'],
            'runtime' => $bucket['runtime'],
            'ecosystem' => $bucket['ecosystem'],
            'bucketAsOf' => $bucket['generated_at'] ?? null,
            'bucketCached' => $bucketCached,
            'diskFreeForHumans' => $diskFreeRow['freeForHumans'] ?? null,
        ]));
    }

    private function collectBucket(): array {
        return [
            'generated_at' => now()->format('H:i'),
            'database' => $this->guarded(fn () => $this->collectDatabase()),
            'queue' => $this->guarded(fn () => $this->collectQueue()),
            'disk' => $this->guarded(fn () => $this->collectDisk()),
            'opcache' => $this->guarded(fn () => $this->collectOpcache()),
            'storage' => $this->guarded(fn () => $this->collectStorage()),
            'runtime' => $this->guarded(fn () => $this->collectRuntime()),
            'ecosystem' => $this->guarded(fn () => $this->collectEcosystem()),
        ];
    }

    private function guarded(callable $probe): array {
        try {
            $result = $probe();
        } catch (\Throwable $e) {
            return ['available' => false, 'reason' => 'Probe failed ('.class_basename($e).').'];
        }

        if (! is_array($result) || ! array_key_exists('available', $result)) {
            return ['available' => false, 'reason' => 'Probe returned an unexpected result.'];
        }

        return $result;
    }

    private function probeCache(): array {
        $startedAt = microtime(true);

        try {
            Cache::put(self::PING_KEY, 'ping', 10);
            $readBack = Cache::get(self::PING_KEY);
            Cache::forget(self::PING_KEY);
        } catch (\Throwable $e) {
            return [
                'available' => false,
                'passed' => false,
                'store' => (string) config('cache.default'),
                'reason' => 'The configured cache store threw an exception ('.class_basename($e).').',
            ];
        }

        return [
            'available' => true,
            'passed' => $readBack === 'ping',
            'store' => (string) config('cache.default'),
            'latencyMs' => round((microtime(true) - $startedAt) * 1000, 1),
        ];
    }

    private function collectPhp(): array {
        $limit = (string) ini_get('memory_limit');
        $limitBytes = $this->parseMemoryLimitToBytes($limit);
        $usage = memory_get_usage(true);
        $peak = memory_get_peak_usage(true);

        $uploadLimit = (string) ini_get('upload_max_filesize');
        $postMaxSize = (string) ini_get('post_max_size');
        $maxExecutionTime = (string) ini_get('max_execution_time');

        return [
            'available' => true,
            'version' => PHP_VERSION,
            'limit' => $limit,
            'unlimited' => $limitBytes === null,
            'usage' => $usage,
            'peak' => $peak,
            'usagePercent' => ($limitBytes !== null && $limitBytes > 0) ? round($usage / $limitBytes * 100, 1) : null,
            'usageForHumans' => $this->formatBytes($usage),
            'peakForHumans' => $this->formatBytes($peak),
            'limitForHumans' => $limitBytes !== null ? $this->formatBytes($limitBytes) : null,
            'uploadLimitForHumans' => $this->humanizeIniLimit($uploadLimit),
            'postMaxSizeForHumans' => $this->humanizeIniLimit($postMaxSize),
            'maxExecutionTimeForHumans' => $this->humanizeExecutionTime($maxExecutionTime),
        ];
    }

    private function parseMemoryLimitToBytes(string $limit): ?int {
        $limit = trim($limit);

        if ($limit === '' || $limit === '-1' || $limit === '0') {
            return null;
        }

        if (ctype_digit($limit)) {
            return (int) $limit;
        }

        $value = substr($limit, 0, -1);

        if (! ctype_digit($value)) {
            return null;
        }

        return match (strtolower(substr($limit, -1))) {
            'g' => (int) $value * 1024 ** 3,
            'm' => (int) $value * 1024 ** 2,
            'k' => (int) $value * 1024,
            't' => (int) $value * 1024 ** 4,
            default => null,
        };
    }

    private function collectOpcache(): array {
        if (! function_exists('opcache_get_status')) {
            return ['available' => false, 'reason' => 'OPcache is not loaded on this server.'];
        }

        $status = @opcache_get_status(false);

        if ($status === false) {
            return ['available' => false, 'reason' => 'OPcache is loaded but not caching scripts (it may be disabled).'];
        }

        $memory = $status['memory_usage'] ?? [];
        $statistics = $status['opcache_statistics'] ?? [];

        $used = (int) ($memory['used_memory'] ?? 0);
        $free = (int) ($memory['free_memory'] ?? 0);
        $wasted = (int) ($memory['wasted_memory'] ?? 0);
        $total = $used + $free;

        return [
            'available' => true,
            'hitRate' => isset($statistics['opcache_hit_rate']) ? round((float) $statistics['opcache_hit_rate'], 1) : null,
            'numScripts' => isset($statistics['num_cached_scripts']) ? (int) $statistics['num_cached_scripts'] : null,
            'used' => $used,
            'wasted' => $wasted,
            'total' => $total,
            'usedPercent' => $total > 0 ? round($used / $total * 100, 1) : null,
        ];
    }

    private function collectDisk(): array {
        $targets = [
            ['label' => 'Project root', 'path' => base_path()],
            ['label' => 'Storage', 'path' => storage_path()],
        ];

        $rows = [];

        foreach ($targets as $target) {
            $total = @disk_total_space($target['path']);
            $free = @disk_free_space($target['path']);

            $row = [
                'label' => $target['label'],
                'available' => $total !== false && $free !== false && (float) $total > 0,
                'totalBytes' => is_float($total) ? (int) $total : null,
                'freeBytes' => is_float($free) ? (int) $free : null,
                'usedBytes' => null,
                'usedPercent' => null,
            ];

            if ($row['available']) {
                $row['usedBytes'] = $row['totalBytes'] - $row['freeBytes'];
                $row['usedPercent'] = round($row['usedBytes'] / $row['totalBytes'] * 100, 1);
            }

            $rows[] = $row;
        }

        return [
            'available' => in_array(true, array_column($rows, 'available'), true),
            'rows' => $rows,
        ];
    }

    private function collectDatabase(): array {
        $connection = DB::connection();
        $driver = (string) $connection->getDriverName();
        $database = $connection->getDatabaseName();

        $driverNames = [
            'mysql' => 'MySQL',
            'mariadb' => 'MariaDB',
            'pgsql' => 'PostgreSQL',
            'sqlite' => 'SQLite',
            'sqlsrv' => 'SQL Server',
        ];

        return [
            'available' => true,
            'driver' => $driver,
            'driverName' => $driverNames[$driver] ?? ucfirst($driver),
            // Never render a full sqlite path — it leaks filesystem layout.
            'database' => ($driver === 'sqlite' && $database !== null && $database !== '') ? basename($database) : $database,
            'serverVersion' => $this->databaseServerVersion($driver),
            'tableCount' => count(Schema::getTableListing()),
            'sizeBytes' => $this->databaseSizeBytes($driver, $database),
        ];
    }

    private function databaseServerVersion(string $driver): ?string {
        try {
            $version = DB::connection()->getPdo()->getAttribute(PDO::ATTR_SERVER_VERSION);

            if (is_string($version) && $version !== '') {
                return $version;
            }
        } catch (\Throwable $e) {
            // Fall through to the per-driver queries below.
        }

        try {
            $result = match ($driver) {
                'mysql', 'mariadb', 'pgsql' => DB::selectOne('select version() as version'),
                'sqlite' => DB::selectOne('select sqlite_version() as version'),
                default => null,
            };

            return $result?->version ?? null;
        } catch (\Throwable $e) {
            return null;
        }
    }

    private function databaseSizeBytes(string $driver, ?string $database): ?int {
        if (! in_array($driver, ['mysql', 'mariadb'], true) || $database === null || $database === '') {
            return null;
        }

        try {
            $row = DB::selectOne(
                'select coalesce(sum(data_length + index_length), 0) as size from information_schema.tables where table_schema = ?',
                [$database]
            );

            return $row === null ? null : (int) $row->size;
        } catch (\Throwable $e) {
            return null;
        }
    }

    private function collectQueue(): array {
        $connection = (string) config('queue.default');

        $result = [
            'available' => true,
            'connection' => $connection,
            'status' => 'configured',
            'detail' => null,
            'horizon' => class_exists(Horizon::class),
        ];

        if ($connection === 'database') {
            $table = (string) config('queue.connections.database.table', 'jobs');
            $hasTable = config('queue.connections.database.connection')
                ? Schema::connection((string) config('queue.connections.database.connection'))->hasTable($table)
                : Schema::hasTable($table);

            $result['status'] = $hasTable ? 'reachable' : 'unreachable';
            $result['detail'] = $hasTable
                ? "The '{$table}' jobs table exists."
                : "The '{$table}' jobs table was not found — run the migrations.";
        } elseif ($connection === 'redis') {
            $clientAvailable = app()->bound('redis')
                && (class_exists(\Redis::class) || class_exists(Client::class));

            if (! $clientAvailable) {
                $result['status'] = 'unknown';
                $result['detail'] = 'No Redis client (phpredis or predis) is available to this application.';
            } else {
                try {
                    Redis::connection((string) config('queue.connections.redis.connection', 'default'))->ping();

                    $result['status'] = 'reachable';
                    $result['detail'] = 'Redis ping succeeded.';
                } catch (\Throwable $e) {
                    $result['status'] = 'unreachable';
                    $result['detail'] = 'Redis ping failed — the Redis server could not be reached.';
                }
            }
        }

        return $result;
    }

    private function collectStorage(): array {
        $checks = [
            ['label' => 'storage/', 'path' => storage_path()],
            ['label' => 'storage/framework/cache', 'path' => storage_path('framework/cache')],
            ['label' => 'storage/framework/sessions', 'path' => storage_path('framework/sessions')],
        ];

        $rows = [];

        foreach ($checks as $check) {
            $exists = is_dir($check['path']);

            $rows[] = [
                'label' => $check['label'],
                'exists' => $exists,
                'writable' => $exists && @is_writable($check['path']),
            ];
        }

        return [
            'available' => true,
            'rows' => $rows,
        ];
    }

    private function collectRuntime(): array {
        return [
            'available' => true,
            'laravel' => app()->version(),
            'environment' => app()->environment(),
            'debug' => (bool) config('app.debug'),
            'sapi' => PHP_SAPI,
            'os' => PHP_OS,
            'appTimezone' => (string) config('app.timezone'),
            'phpTimezone' => (string) date_default_timezone_get(),
            'timezoneMismatch' => (string) config('app.timezone') !== (string) date_default_timezone_get(),
        ];
    }

    /**
     * Installed versions of hasinhayder/* (tyro ecosystem) packages,
     * read from the application's composer.lock.
     */
    private function collectEcosystem(): array {
        $lockPath = base_path('composer.lock');

        if (! is_file($lockPath) || ! is_readable($lockPath)) {
            return ['available' => false, 'reason' => 'composer.lock was not found in the application root.'];
        }

        $lock = json_decode((string) file_get_contents($lockPath), true);

        if (! is_array($lock)) {
            return ['available' => false, 'reason' => 'composer.lock could not be parsed.'];
        }

        $packages = [];

        foreach (['packages', 'packages-dev'] as $section) {
            foreach ($lock[$section] ?? [] as $package) {
                $name = (string) ($package['name'] ?? '');

                if (! str_starts_with($name, 'hasinhayder/')) {
                    continue;
                }

                $packages[$name] = [
                    'name' => substr($name, strlen('hasinhayder/')),
                    'version' => (string) ($package['version'] ?? 'unknown'),
                    'dev' => $section === 'packages-dev',
                ];
            }
        }

        ksort($packages);

        return [
            'available' => true,
            'packages' => array_values($packages),
        ];
    }

    private function decorateBucket(array $bucket): array {
        if (($bucket['database']['available'] ?? false) && ($bucket['database']['sizeBytes'] ?? null) !== null) {
            $bucket['database']['sizeForHumans'] = $this->formatBytes($bucket['database']['sizeBytes']);
        }

        foreach ($bucket['disk']['rows'] ?? [] as $index => $row) {
            if ($row['available']) {
                $bucket['disk']['rows'][$index]['totalForHumans'] = $this->formatBytes($row['totalBytes']);
                $bucket['disk']['rows'][$index]['freeForHumans'] = $this->formatBytes($row['freeBytes']);
                $bucket['disk']['rows'][$index]['usedForHumans'] = $this->formatBytes($row['usedBytes']);
            }
        }

        if ($bucket['opcache']['available'] ?? false) {
            $bucket['opcache']['usedForHumans'] = $this->formatBytes($bucket['opcache']['used'] ?? 0);
            $bucket['opcache']['totalForHumans'] = $this->formatBytes($bucket['opcache']['total'] ?? 0);
            $bucket['opcache']['wastedForHumans'] = $this->formatBytes($bucket['opcache']['wasted'] ?? 0);
        }

        return $bucket;
    }

    private function humanizeIniLimit(string $value): string {
        $bytes = $this->parseMemoryLimitToBytes($value);

        if ($bytes !== null && $bytes > 0) {
            return $this->formatBytes($bytes);
        }

        return ($value === '-1' || $value === '0') ? 'Unlimited' : $value;
    }

    private function humanizeExecutionTime(string $value): string {
        if ($value === '' || $value === '0' || $value === '-1') {
            return 'Unlimited';
        }

        return ctype_digit($value) ? $value.'s' : $value;
    }

    private function formatBytes($bytes): string {
        $bytes = (float) $bytes;

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
