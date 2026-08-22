@extends('tyro-dashboard::layouts.admin')

@section('title', 'Log Viewer')

@section('breadcrumb')
<a href="{{ route($dashboardRoute::name('index')) }}">Dashboard</a>
<span class="breadcrumb-separator">/</span>
<span>Log Viewer</span>
@endsection

@push('styles')
<style>
    .log-stats-grid {
        gap: 0.6rem;
        grid-template-columns: repeat(auto-fit, minmax(132px, 1fr));
    }
    @media (min-width: 1280px) {
        .log-stats-grid {
            grid-template-columns: repeat(8, minmax(0, 1fr));
        }
    }
    .log-stat-link {
        display: block;
        height: 100%;
        text-decoration: none;
        border-radius: var(--radius);
    }
    .log-stat-link .stat-card {
        display: grid;
        grid-template-columns: 44px 1fr;
        grid-template-rows: auto auto;
        column-gap: 0.75rem;
        align-items: center;
        height: 100%;
        min-height: 68px;
        padding: 0.7rem 0.85rem;
        border-width: 1.5px;
        transition: border-color 0.15s ease, box-shadow 0.15s ease, transform 0.15s ease;
    }
    .log-stat-link .stat-icon {
        grid-column: 1;
        grid-row: 1 / span 2;
        width: 44px;
        height: 44px;
        margin-bottom: 0;
        border-radius: 10px;
    }
    .log-stat-link .stat-icon svg {
        width: 22px;
        height: 22px;
    }
    .log-stat-link .stat-label {
        grid-column: 2;
        grid-row: 1;
        margin-bottom: 0.1rem;
        font-size: 0.78rem;
        line-height: 1.1;
    }
    .log-stat-link .stat-value {
        grid-column: 2;
        grid-row: 2;
        font-size: 1.35rem;
        line-height: 1;
    }
    .log-stat-link:hover .stat-card {
        border-color: var(--ring);
        box-shadow: var(--card-shadow);
        transform: translateY(-1px);
    }
    .log-stat-active .stat-card {
        border-color: var(--primary);
        box-shadow: 0 2px 10px -4px rgb(0 0 0 / 0.12);
    }
    .log-file-bar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 0.75rem;
        flex-wrap: wrap;
        padding: 0.85rem 1.25rem;
        border: 1px solid var(--border);
        border-radius: 8px;
        background: var(--card);
        margin-bottom: 1rem;
    }
    .log-file-bar-main {
        display: flex;
        align-items: center;
        gap: 0.65rem;
        min-width: 0;
        flex-wrap: wrap;
    }
    .log-file-bar-name {
        font-size: 0.875rem;
        font-weight: 600;
        color: var(--foreground);
        word-break: break-all;
        display: inline-flex;
        align-items: center;
        gap: 0.45rem;
    }
    .log-file-bar-name svg {
        width: 16px;
        height: 16px;
        color: var(--muted-foreground);
        flex-shrink: 0;
    }
    .log-file-bar-meta {
        font-size: 0.8125rem;
        color: var(--muted-foreground);
        white-space: nowrap;
    }
    .log-truncated {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        font-size: 0.8125rem;
        color: var(--muted-foreground);
        background: var(--muted);
        border: 1px solid var(--border);
        padding: 0.25rem 0.6rem;
        border-radius: 9999px;
        white-space: nowrap;
    }
    .log-truncated svg {
        width: 14px;
        height: 14px;
        flex-shrink: 0;
    }
    .log-list {
        display: flex;
        flex-direction: column;
    }
    .log-entry {
        padding: 1rem 1.25rem;
        border-bottom: 1px solid var(--border);
        border-left: 3px solid transparent;
        display: flex;
        flex-direction: column;
        gap: 0.6rem;
        transition: background-color 0.12s ease;
    }
    .log-entry:last-child {
        border-bottom: none;
    }
    .log-entry:hover {
        background: color-mix(in srgb, var(--muted) 45%, transparent);
    }
    .log-entry--emergency,
    .log-entry--alert,
    .log-entry--critical,
    .log-entry--error {
        border-left-color: var(--destructive);
    }
    .log-entry--warning {
        border-left-color: var(--warning);
    }
    .log-entry--notice,
    .log-entry--info {
        border-left-color: var(--info);
    }
    .log-entry--debug,
    .log-entry--unknown {
        border-left-color: var(--border);
    }
    .log-entry-head {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 0.75rem;
    }
    .log-entry-meta {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        flex-wrap: wrap;
        min-width: 0;
    }
    .log-entry-time {
        font-family: ui-monospace, SFMono-Regular, Menlo, Consolas, "Liberation Mono", monospace;
        font-size: 0.75rem;
        font-weight: 500;
        color: var(--muted-foreground);
        background: var(--muted);
        border: 1px solid var(--border);
        padding: 0.15rem 0.4rem;
        border-radius: 4px;
        white-space: nowrap;
    }
    .log-entry-message {
        margin: 0;
        font-size: 0.875rem;
        line-height: 1.55;
        color: var(--foreground);
        white-space: pre-wrap;
        word-break: break-word;
        overflow-wrap: anywhere;
    }
    .log-entry-details {
        margin-top: 0.15rem;
    }
    .log-entry-details summary {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        cursor: pointer;
        font-size: 0.8125rem;
        font-weight: 500;
        color: var(--muted-foreground);
        user-select: none;
        list-style: none;
    }
    .log-entry-details summary::-webkit-details-marker {
        display: none;
    }
    .log-entry-details summary::before {
        content: '';
        width: 0;
        height: 0;
        border-left: 4px solid currentColor;
        border-top: 3.5px solid transparent;
        border-bottom: 3.5px solid transparent;
        transition: transform 0.15s ease;
        opacity: 0.7;
    }
    .log-entry-details[open] summary::before {
        transform: rotate(90deg);
    }
    .log-entry-details summary:hover {
        color: var(--foreground);
    }
    .log-entry-body {
        margin: 0.6rem 0 0;
        padding: 0.85rem 1rem;
        background: var(--muted);
        border: 1px solid var(--border);
        border-radius: 8px;
        font-family: ui-monospace, SFMono-Regular, Menlo, Consolas, "Liberation Mono", monospace;
        font-size: 0.8125rem;
        line-height: 1.6;
        color: var(--foreground);
        white-space: pre-wrap;
        word-break: break-word;
        overflow-wrap: anywhere;
        max-height: 22rem;
        overflow: auto;
    }
    .log-copy-btn {
        flex-shrink: 0;
        width: 30px;
        height: 30px;
        padding: 0;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 6px;
        border: 1px solid transparent;
        color: var(--muted-foreground);
        background: transparent;
        transition: all 0.15s ease;
    }
    .log-copy-btn svg {
        width: 20px;
        height: 20px;
    }
    .log-copy-btn:hover {
        color: var(--foreground);
        background: var(--accent);
        border-color: var(--border);
    }
    .log-copy-btn:active {
        transform: scale(0.96);
    }
    .log-list-header {
        padding: 0.85rem 1.25rem;
        font-size: 0.8125rem;
        color: var(--muted-foreground);
        background: var(--muted);
        border-bottom: 1px solid var(--border);
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 0.75rem;
        flex-wrap: wrap;
    }
    .log-list-header strong {
        color: var(--foreground);
        font-weight: 600;
    }
    @media (max-width: 640px) {
        .log-entry {
            padding: 0.85rem 1rem;
        }
        .log-file-bar {
            padding: 0.75rem 1rem;
        }
    }
</style>
@endpush

@section('content')
<div class="page-header">
    <div class="page-header-row">
        <div>
            <h1 class="page-title">Log Viewer</h1>
            <p class="page-description">Browse application log files in <code>storage/logs</code>. Read-only — use filters to narrow by file, level, or message.</p>
        </div>
        <div style="display: flex; gap: 0.5rem; align-items: center; flex-wrap: wrap;">
            <button type="button" class="btn btn-secondary btn-sm" onclick="location.reload()" title="Reload log entries">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 12a9 9 0 0 1 15.356-6.386L21 8m0-5v5h-5m5 4a9 9 0 0 1-15.356 6.386L3 16m0 5v-5h5" />
                </svg>
                Refresh
            </button>
            @if($selectedFile && $selectedFile['sizeBytes'] > 0)
            <form action="{{ route($dashboardRoute::name('logs.clear')) }}" method="POST" id="log-clear-form">
                @csrf
                @method('DELETE')
                <input type="hidden" name="file" value="{{ $selectedFile['name'] }}">
                <button type="button" class="btn btn-destructive btn-sm" onclick="event.preventDefault(); showDanger('Clear Log File', {{ Js::from('Clear '.$selectedFile['name'].'? All entries in this file will be permanently removed. The file itself is kept.') }}, { confirmText: 'Clear' }).then(confirmed => { if (confirmed) document.getElementById('log-clear-form').submit(); });">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                    Clear this file
                </button>
            </form>
            @endif
        </div>
    </div>
</div>

@if(empty($files))
    <div class="card">
        <div class="card-body">
            <x-tyro-dashboard::alert variant="info" title="No log files found">
                No <code>.log</code> files were found in <code>storage/logs</code>. Log files appear here once the application writes its first entry.
            </x-tyro-dashboard::alert>
        </div>
    </div>
@else
    @php
        $logLevelIcons = [
            // Emergency: octagon with exclamation - most urgent shape
            'emergency' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M7.86 2h8.28L22 7.86v8.28L16.14 22H7.86L2 16.14V7.86L7.86 2z"/><path d="M12 8v4"/><path d="M12 16h.01"/></svg>',
            // Alert: bell with clapper - distinct from triangle warning
            'alert' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9"/><path d="M10.3 21a1.94 1.94 0 0 0 3.4 0"/></svg>',
            // Critical: flame - unique silhouette
            'critical' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M8.5 14.5A2.5 2.5 0 0 0 11 12c0-1.38-.5-2-1-3-1.072-2.143-.224-4.054 2-6 .5 2.5 2 4.9 4 6.5 2 1.6 3 3.5 3 5.5a7 7 0 1 1-14 0c0-1.153.433-2.294 1-3a2.5 2.5 0 0 0 2.5 2.5z"/></svg>',
            // Error: circle with X - clean, classic
            'error' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="10"/><path d="m15 9-6 6"/><path d="m9 9 6 6"/></svg>',
            // Warning: triangle - universally recognized
            'warning' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/><path d="M12 9v4"/><path d="M12 17h.01"/></svg>',
            // Notice: megaphone - announcement feel, different from info circle
            'notice' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m3 11 18-5v12L3 14v-3z"/><path d="M11.6 16.8a3 3 0 1 1-5.8-1.6"/></svg>',
            // Info: circle with i - informational
            'info' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4"/><path d="M12 8h.01"/></svg>',
            // Debug: bug - instantly recognizable for developers
            'debug' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m8 2 1.88 1.88"/><path d="M14.12 3.88 16 2"/><path d="M9 7.13v-1a3.003 3.003 0 1 1 6 0v1"/><path d="M12 20c-3.3 0-6-2.7-6-6v-3a4 4 0 0 1 4-4h4a4 4 0 0 1 4 4v3c0 3.3-2.7 6-6 6"/><path d="M12 20v-9"/><path d="M6.53 9C4.6 8.8 3 7.1 3 5"/><path d="M6 13H2"/><path d="M3 21c0-2.1 1.7-3.9 3.8-4"/><path d="M20.97 5c0 2.1-1.6 3.8-3.5 4"/><path d="M22 13h-4"/><path d="M17.2 17c2.1.1 3.8 1.9 3.8 4"/></svg>',
        ];
    @endphp
    <div class="stats-grid log-stats-grid">
        @foreach($levelCards as $card)
            <a href="{{ $card['url'] }}" class="log-stat-link {{ $card['active'] ? 'log-stat-active' : '' }}" title="{{ $card['active'] ? 'Remove '.$card['level'].' filter' : 'Show only '.$card['level'].' entries' }}">
                <x-tyro-dashboard::stat :label="ucfirst($card['level'])" :value="(string) $card['count']" :variant="$card['variant']" :icon="$logLevelIcons[$card['level']] ?? ''" />
            </a>
        @endforeach
    </div>

    <div class="card" style="margin-bottom: 1rem;">
        <div class="card-body">
            <form action="{{ route($dashboardRoute::name('logs.index')) }}" method="GET" id="log-filter-form">
                <div class="filters-bar" style="flex-wrap: wrap; gap: 0.75rem;">
                    <div class="filter-group">
                        <label class="filter-label">File</label>
                        <select name="file" class="form-select" style="min-width: 220px;" onchange="this.form.submit()">
                            @foreach($files as $file)
                                <option value="{{ $file['name'] }}" {{ ($filters['file'] ?? '') === $file['name'] ? 'selected' : '' }}>{{ $file['name'] }} — {{ $file['sizeForHumans'] }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="filter-group">
                        <label class="filter-label">Level</label>
                        <select name="level" class="form-select" style="min-width: 150px;" onchange="this.form.submit()">
                            <option value="">All levels</option>
                            @foreach($levelCounts as $levelName => $count)
                                <option value="{{ $levelName }}" {{ ($filters['level'] ?? '') === $levelName ? 'selected' : '' }}>{{ ucfirst($levelName) }} ({{ $count }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="search-box" style="min-width: 220px; flex: 1;">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        <input type="text" name="q" class="form-input" placeholder="Search message or stack trace…" value="{{ $filters['q'] ?? '' }}">
                    </div>

                    <div class="filter-group">
                        <label class="filter-label">Per page</label>
                        <select name="per_page" class="form-select" style="min-width: 90px;" onchange="this.form.submit()">
                            @foreach([25, 50, 100] as $size)
                                <option value="{{ $size }}" {{ (int)($filters['per_page'] ?? 25) === $size ? 'selected' : '' }}>{{ $size }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="filter-group">
                        <button type="submit" class="btn btn-secondary">Filter</button>
                        @if(!empty($filters['level']) || !empty($filters['q']))
                            <a href="{{ route($dashboardRoute::name('logs.index'), array_filter(['file' => $filters['file'] ?? null])) }}" class="btn btn-ghost">Clear</a>
                        @endif
                    </div>
                </div>
            </form>
        </div>
    </div>

    @if($selectedFile)
        <div class="log-file-bar">
            <div class="log-file-bar-main">
                <span class="log-file-bar-name" title="{{ $selectedFile['name'] }}">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    {{ $selectedFile['name'] }}
                </span>
                <span class="log-file-bar-meta">{{ $selectedFile['sizeForHumans'] }}@if($selectedFile['modifiedAt']) · {{ $selectedFile['modifiedAt'] }}@endif · {{ $entries->total() }} {{ \Illuminate\Support\Str::plural('entry', $entries->total()) }}{{ $filters['level'] || $filters['q'] ? ' (filtered)' : '' }}</span>
            </div>
            @if($truncated)
                <span class="log-truncated" title="Large files are tail-capped to avoid loading the entire file into memory">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3m0 4h.01M10.3 3.3L3 10.3c-1 1-1 2.6 0 3.4l7.3 7.3c1 1 2.6 1 3.4 0l7.3-7.3c1-1 1-2.6 0-3.4L13.7 3.3c-1-1-2.6-1-3.4 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v5"/></svg>
                    Showing last {{ $maxReadBytesForHumans }}
                </span>
            @endif
        </div>
    @endif

    <div class="card">
        @if($entries->count())
            <div class="log-list-header">
                <span>Newest first · Page {{ $entries->currentPage() }} of {{ $entries->lastPage() }}</span>
                <span><strong>{{ $entries->total() }}</strong> total</span>
            </div>
            <div class="log-list">
                @foreach($entries as $entry)
                    <div class="log-entry log-entry--{{ $entry['level'] }}">
                        <div class="log-entry-head">
                            <div class="log-entry-meta">
                                @if($entry['datetime'] !== '')
                                    <span class="log-entry-time">{{ $entry['datetime'] }}</span>
                                @endif
                                @if($entry['env'] !== '')
                                    <x-tyro-dashboard::badge variant="secondary">{{ $entry['env'] }}</x-tyro-dashboard::badge>
                                @endif
                                <x-tyro-dashboard::badge :variant="$badgeVariants[$entry['level']] ?? 'secondary'">{{ $entry['level'] }}</x-tyro-dashboard::badge>
                            </div>
                            <button type="button" class="log-copy-btn" title="Copy entry" aria-label="Copy entry" onclick="copyLogEntry(this)">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                </svg>
                            </button>
                        </div>
                        <p class="log-entry-message">{{ $entry['message'] }}</p>
                        @if($entry['body'] !== '')
                            <details class="log-entry-details">
                                <summary>Stack trace &amp; context</summary>
                                <pre class="log-entry-body">{{ $entry['body'] }}</pre>
                            </details>
                        @endif
                    </div>
                @endforeach
            </div>

            @if($entries->hasPages())
                <div class="pagination" style="border-top: 1px solid var(--border);">
                    {{ $entries->links() }}
                </div>
            @endif
        @else
            <div class="empty-state">
                <svg class="empty-state-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <h3 class="empty-state-title">No log entries found</h3>
                <p class="empty-state-description">No entries match your current filters in <code>{{ $selectedFile['name'] ?? 'this file' }}</code>. Try a different file, level, or search term.</p>
            </div>
        @endif
    </div>
@endif
@endsection

@push('scripts')
<script>
    function copyLogEntry(button) {
        var card = button.closest('.log-entry');
        if (!card) return;
        var parts = [];
        var time = card.querySelector('.log-entry-time');
        if (time && time.textContent.trim() !== '') parts.push('[' + time.textContent.trim() + ']');
        var badges = card.querySelectorAll('.log-entry-meta .badge');
        if (badges.length) {
            var labels = [];
            badges.forEach(function (b) { labels.push(b.textContent.trim()); });
            parts.push(labels.join('.') + ':');
        }
        var msg = card.querySelector('.log-entry-message');
        if (msg) parts.push(msg.textContent.trim());
        var body = card.querySelector('.log-entry-body');
        if (body) parts.push(body.textContent.replace(/\s+$/, ''));
        var text = parts.join(' ').replace(/\s+:\s+/, ': ').trim();
        // Re-insert newline before stack trace for readability
        if (body) text = text.replace(body.textContent.trim().slice(0, 20), '\n' + body.textContent.trim().slice(0, 20));
        // Simpler: join with newline for the body part
        var header = [];
        if (time) header.push('[' + time.textContent.trim() + ']');
        if (badges.length) {
            var l = []; badges.forEach(function (b) { l.push(b.textContent.trim()); });
            header.push(l.join('.') + ':');
        }
        if (msg) header.push(msg.textContent.trim());
        var out = header.join(' ');
        if (body) out += '\n' + body.textContent.replace(/\s+$/, '');
        navigator.clipboard.writeText(out).then(function () {
            showToast('Log entry copied to the clipboard.', 'success');
        }).catch(function () {
            showToast('Could not copy the log entry.', 'error');
        });
    }
</script>
@endpush
