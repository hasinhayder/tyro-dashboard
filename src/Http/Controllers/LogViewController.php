<?php

namespace HasinHayder\TyroDashboard\Http\Controllers;

use HasinHayder\TyroDashboard\Support\DashboardRoute;
use HasinHayder\TyroDashboard\Support\LogFileReader;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\File;

class LogViewController extends BaseController {
    /**
     * Stat-card variant per log level.
     */
    private const STAT_VARIANTS = [
        'emergency' => 'danger',
        'alert' => 'danger',
        'critical' => 'danger',
        'error' => 'danger',
        'warning' => 'warning',
        'notice' => 'info',
        'info' => 'info',
        'debug' => 'primary',
    ];

    /**
     * Badge variant per log level.
     */
    private const BADGE_VARIANTS = [
        'emergency' => 'danger',
        'alert' => 'danger',
        'critical' => 'danger',
        'error' => 'danger',
        'warning' => 'warning',
        'notice' => 'info',
        'info' => 'info',
        'debug' => 'secondary',
        'unknown' => 'secondary',
    ];

    /**
     * Browse the application log files in storage/logs.
     */
    public function index(Request $request) {
        $reader = app(LogFileReader::class);

        try {
            $files = $reader->files();
        } catch (\Throwable $e) {
            $files = [];
        }

        // Resolve the selected file: ?file= wins, otherwise the newest file.
        $fileRaw = $request->query('file', '');
        $fileParam = is_string($fileRaw) ? trim($fileRaw) : '';
        $selectedName = null;

        if ($fileParam !== '') {
            // Invalid names (traversal, separators, missing files) abort with 404.
            $selectedName = basename($reader->resolvePath($fileParam));
        } elseif (! empty($files)) {
            $selectedName = $files[0]['name'];
        }

        $selectedFile = null;

        foreach ($files as $file) {
            if ($file['name'] === $selectedName) {
                $selectedFile = $file;
                break;
            }
        }

        $entries = [];
        $truncated = false;
        $levelCounts = array_fill_keys(LogFileReader::LEVELS, 0);

        if ($selectedName !== null) {
            try {
                $parsed = $reader->read($selectedName);
                $entries = $parsed['entries'];
                $truncated = $parsed['truncated'];
            } catch (\Throwable $e) {
                $entries = [];
                $truncated = false;
            }

            foreach ($entries as $entry) {
                $levelCounts[$entry['level']] = ($levelCounts[$entry['level']] ?? 0) + 1;
            }
        }

        // Filters.
        $levelRaw = $request->query('level', '');
        $level = strtolower(trim(is_string($levelRaw) ? $levelRaw : ''));

        if (! in_array($level, LogFileReader::LEVELS, true)) {
            $level = '';
        }

        $searchRaw = $request->query('q', '');
        $search = trim(is_string($searchRaw) ? $searchRaw : '');

        $filtered = array_values(array_filter($entries, function ($entry) use ($level, $search) {
            if ($level !== '' && $entry['level'] !== $level) {
                return false;
            }

            if ($search !== ''
                && stripos($entry['message'].' '.$entry['body'], $search) === false) {
                return false;
            }

            return true;
        }));

        // Newest entries first.
        $filtered = array_reverse($filtered);

        // Pagination.
        $perPageRaw = $request->query('per_page', (int) config('tyro-dashboard.log_viewer.per_page', 25));
        $perPage = (int) (is_numeric($perPageRaw) ? $perPageRaw : 25);
        $perPage = in_array($perPage, [25, 50, 100], true) ? $perPage : 25;

        $page = max(1, (int) $request->query('page', 1));
        $chunk = array_slice($filtered, ($page - 1) * $perPage, $perPage);

        $paginator = new LengthAwarePaginator(
            $chunk,
            count($filtered),
            $perPage,
            $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        // Clickable stat cards that toggle the level filter.
        $levelCards = [];

        foreach (self::STAT_VARIANTS as $levelName => $variant) {
            $isActive = $level === $levelName;

            $levelCards[] = [
                'level' => $levelName,
                'count' => $levelCounts[$levelName] ?? 0,
                'variant' => $variant,
                'active' => $isActive,
                'url' => route(DashboardRoute::name('logs.index'), array_merge(
                    $request->except(['level', 'page']),
                    $isActive ? [] : ['level' => $levelName]
                )),
            ];
        }

        return view('tyro-dashboard::logs.index', $this->getViewData([
            'files' => $files,
            'selectedFile' => $selectedFile,
            'levelCounts' => $levelCounts,
            'badgeVariants' => self::BADGE_VARIANTS,
            'levelCards' => $levelCards,
            'entries' => $paginator,
            'truncated' => $truncated,
            'maxReadBytesForHumans' => $reader->formatBytes((int) config('tyro-dashboard.log_viewer.max_read_bytes', 16777216)),
            'filters' => [
                'file' => $selectedName ?? '',
                'level' => $level,
                'q' => $search,
                'per_page' => $perPage,
            ],
        ]));
    }

    /**
     * Truncate (clear) a single validated log file. Never deletes the file.
     */
    public function clear(Request $request): RedirectResponse {
        $reader = app(LogFileReader::class);

        // Invalid names (traversal, separators, missing files) abort with 404.
        $fileRaw = $request->input('file', '');
        $path = $reader->resolvePath(is_string($fileRaw) ? $fileRaw : '');
        $name = basename($path);

        File::put($path, '');

        return redirect()
            ->route(DashboardRoute::name('logs.index'), $request->except(['_token', '_method']))
            ->with('success', "Log file {$name} was cleared.");
    }
}
