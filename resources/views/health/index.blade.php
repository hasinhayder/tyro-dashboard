@extends('tyro-dashboard::layouts.admin')

@section('title', 'System Health')

@section('breadcrumb')
<a href="{{ route($dashboardRoute::name('index')) }}">Dashboard</a>
<span class="breadcrumb-separator">/</span>
<span>System Health</span>
@endsection

@push('styles')
<style>
    .health-section-head {
        display: flex;
        align-items: baseline;
        justify-content: space-between;
        gap: 1rem;
        flex-wrap: wrap;
        margin: 1.5rem 0 0.75rem;
    }
    .health-section-head h2 {
        font-size: 1rem;
        font-weight: 600;
        color: var(--foreground);
        margin: 0;
    }
    .health-asof {
        font-size: 0.75rem;
        color: var(--muted-foreground);
    }
    .health-card-head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 0.75rem;
        flex-wrap: wrap;
        width: 100%;
    }
    .health-kv {
        display: flex;
        flex-direction: column;
        gap: 0.85rem;
    }
    .health-kv-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
        flex-wrap: wrap;
    }
    .health-kv-label {
        font-size: 0.875rem;
        color: var(--muted-foreground);
    }
    .health-kv-value {
        font-size: 0.875rem;
        font-weight: 500;
        color: var(--foreground);
        text-align: right;
        word-break: break-word;
    }
    .health-kv-sub {
        font-size: 0.75rem;
        color: var(--muted-foreground);
        margin-top: -0.4rem;
    }
    .health-card-actions {
        display: flex;
        align-items: center;
        gap: 0.375rem;
    }
    .health-copy-btn {
        flex-shrink: 0;
        padding: 0.375rem;
        line-height: 0;
        color: var(--muted-foreground);
        background: transparent;
    }
    .health-copy-btn svg {
        width: 1rem;
        height: 1rem;
    }
    .health-copy-btn:hover {
        color: var(--foreground);
        background: var(--accent);
    }
    .health-copy-btn[disabled] {
        opacity: 0.6;
        cursor: wait;
    }
    .health-copy-page-btn {
        flex-shrink: 0;
        white-space: nowrap;
        color: var(--muted-foreground);
    }
    .health-copy-page-btn svg {
        width: 1rem;
        height: 1rem;
        flex-shrink: 0;
    }
    .health-copy-page-btn:hover {
        color: var(--foreground);
    }
    .health-copy-page-btn[disabled] {
        opacity: 0.6;
        cursor: wait;
    }
    .health-stat-wrap {
        position: relative;
    }
    .health-stat-wrap .stat-card {
        height: 100%;
    }
    .health-stat-wrap .health-copy-btn {
        position: absolute;
        top: 0.5rem;
        right: 0.5rem;
        z-index: 1;
    }
</style>
@endpush

@section('content')
<div class="page-header">
    <div class="page-header-row">
        <div>
            <h1 class="page-title">System Health</h1>
            <p class="page-description">Read-only runtime diagnostics for this application. Nothing on this page changes any setting.</p>
        </div>
        <button type="button" class="btn btn-ghost health-copy-page-btn" title="Copy this page as an image" aria-label="Copy this page as an image" onclick="copyHealthPage(this)">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="9" cy="9" r="2"/><path d="M21 15l-5-5L5 21"/></svg>
            <span>Copy page as image</span>
        </button>
    </div>
</div>

<div class="stats-grid">
    <div class="health-stat-wrap" data-card-title="PHP Version">
        <x-tyro-dashboard::stat label="PHP Version" :value="$php['version']" variant="primary" />
        <button type="button" class="btn btn-icon btn-ghost health-copy-btn" title="Copy card as image" aria-label="Copy card as image" onclick="copyHealthCard(this)">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="9" cy="9" r="2"/><path d="M21 15l-5-5L5 21"/></svg>
        </button>
    </div>
    <div class="health-stat-wrap" data-card-title="Database">
        <x-tyro-dashboard::stat
            label="Database"
            :value="$database['available'] ? $database['driverName'] : '—'"
            :variant="$database['available'] ? 'success' : 'warning'"
        />
        <button type="button" class="btn btn-icon btn-ghost health-copy-btn" title="Copy card as image" aria-label="Copy card as image" onclick="copyHealthCard(this)">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="9" cy="9" r="2"/><path d="M21 15l-5-5L5 21"/></svg>
        </button>
    </div>
    <div class="health-stat-wrap" data-card-title="Storage Disk Free">
        <x-tyro-dashboard::stat label="Storage Disk Free" :value="$diskFreeForHumans ?? '—'" variant="info" />
        <button type="button" class="btn btn-icon btn-ghost health-copy-btn" title="Copy card as image" aria-label="Copy card as image" onclick="copyHealthCard(this)">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="9" cy="9" r="2"/><path d="M21 15l-5-5L5 21"/></svg>
        </button>
    </div>
    <div class="health-stat-wrap" data-card-title="Cache Latency">
        <x-tyro-dashboard::stat
            label="Cache Latency"
            :value="$cache['available'] && $cache['passed'] ? $cache['latencyMs'].' ms' : 'Unavailable'"
            :variant="$cache['available'] && $cache['passed'] ? 'success' : 'danger'"
        />
        <button type="button" class="btn btn-icon btn-ghost health-copy-btn" title="Copy card as image" aria-label="Copy card as image" onclick="copyHealthCard(this)">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="9" cy="9" r="2"/><path d="M21 15l-5-5L5 21"/></svg>
        </button>
    </div>
</div>

<div class="health-section-head">
    <h2>Live probes</h2>
    <span class="health-asof">Always current — never cached</span>
</div>

<div class="grid-2">
    <div class="card" data-card-title="PHP &amp; Memory">
        <div class="card-header">
            <div class="health-card-head">
                <h3 class="card-title">PHP &amp; Memory</h3>
                <div class="health-card-actions">
                    <x-tyro-dashboard::badge variant="info">Live</x-tyro-dashboard::badge>
                    <button type="button" class="btn btn-icon btn-ghost health-copy-btn" title="Copy card as image" aria-label="Copy card as image" onclick="copyHealthCard(this)">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="9" cy="9" r="2"/><path d="M21 15l-5-5L5 21"/></svg>
                    </button>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="health-kv">
                <div class="health-kv-row">
                    <span class="health-kv-label">PHP Version</span>
                    <span class="health-kv-value">{{ $php['version'] }}</span>
                </div>
                <div class="health-kv-row">
                    <span class="health-kv-label">Memory Limit</span>
                    <span class="health-kv-value">{{ $php['unlimited'] ? 'Unlimited' : $php['limit'] }}</span>
                </div>
                <div class="health-kv-row">
                    <span class="health-kv-label">Current Usage (real)</span>
                    <span class="health-kv-value">{{ $php['usageForHumans'] }}</span>
                </div>
                <div class="health-kv-row">
                    <span class="health-kv-label">Peak Usage (real)</span>
                    <span class="health-kv-value">{{ $php['peakForHumans'] }}</span>
                </div>
                <div class="health-kv-row">
                    <span class="health-kv-label">Upload Limit (upload_max_filesize)</span>
                    <span class="health-kv-value">{{ $php['uploadLimitForHumans'] }}</span>
                </div>
                <div class="health-kv-row">
                    <span class="health-kv-label">POST Limit (post_max_size)</span>
                    <span class="health-kv-value">{{ $php['postMaxSizeForHumans'] }}</span>
                </div>
                <div class="health-kv-row">
                    <span class="health-kv-label">Max Execution Time</span>
                    <span class="health-kv-value">{{ $php['maxExecutionTimeForHumans'] }}</span>
                </div>
                @if(! $php['unlimited'] && $php['usagePercent'] !== null)
                    @php
                        $memoryVariant = $php['usagePercent'] >= 90 ? 'error' : ($php['usagePercent'] >= 75 ? 'warning' : 'success');
                    @endphp
                    <x-tyro-dashboard::progress :value="(int) round($php['usagePercent'])" :variant="$memoryVariant" :label="'Memory usage — '.$php['usageForHumans'].' of '.$php['limitForHumans'].' used'" showLabel />
                @else
                    <span class="health-kv-sub">No usage percentage shown: the memory limit is unlimited.</span>
                @endif
            </div>
        </div>
    </div>

    <div class="card" data-card-title="Cache">
        <div class="card-header">
            <div class="health-card-head">
                <h3 class="card-title">Cache</h3>
                <div class="health-card-actions">
                    <x-tyro-dashboard::badge variant="info">Live</x-tyro-dashboard::badge>
                    <button type="button" class="btn btn-icon btn-ghost health-copy-btn" title="Copy card as image" aria-label="Copy card as image" onclick="copyHealthCard(this)">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="9" cy="9" r="2"/><path d="M21 15l-5-5L5 21"/></svg>
                    </button>
                </div>
            </div>
        </div>
        <div class="card-body">
            @if(! $cache['available'])
                <x-tyro-dashboard::alert variant="warning" title="Cache store check failed">
                    {{ $cache['reason'] }} The subsystem probes below therefore run uncached on every request. Fixing the cache store is the diagnosis — this page stays readable either way.
                </x-tyro-dashboard::alert>
            @elseif(! $cache['passed'])
                <x-tyro-dashboard::alert variant="warning" title="Cache round-trip verification failed">
                    A value was written to the configured store but could not be read back. The subsystem probes below run uncached on every request.
                </x-tyro-dashboard::alert>
            @else
                <div class="health-kv">
                    <div class="health-kv-row">
                        <span class="health-kv-label">Default Store</span>
                        <span class="health-kv-value">{{ $cache['store'] }}</span>
                    </div>
                    <div class="health-kv-row">
                        <span class="health-kv-label">Round-trip Latency</span>
                        <span class="health-kv-value">{{ $cache['latencyMs'] }} ms</span>
                    </div>
                    <div class="health-kv-row">
                        <span class="health-kv-label">Write → Read → Delete</span>
                        <x-tyro-dashboard::badge variant="success">Passed</x-tyro-dashboard::badge>
                    </div>
                    <span class="health-kv-sub">The ping key self-deletes (10s TTL plus explicit forget) — nothing accumulates in your store.</span>
                </div>
            @endif
        </div>
    </div>
</div>

<div class="health-section-head">
    <h2>Subsystem probes</h2>
    @if($bucketCached)
        <span class="health-asof">Cached for 60 seconds — as of {{ $bucketAsOf }}</span>
    @else
        <span class="health-asof">Live results — the probe cache is not in use{{ ! $cache['available'] ? ' (cache store failed)' : '' }}</span>
    @endif
</div>

<div class="grid-2">
    <div class="card" data-card-title="Database">
        <div class="card-header">
            <div class="health-card-head">
                <h3 class="card-title">Database</h3>
                <div class="health-card-actions">
                    @if($database['available'])
                        <x-tyro-dashboard::badge variant="success">Connected</x-tyro-dashboard::badge>
                    @else
                        <x-tyro-dashboard::badge variant="warning">Unavailable</x-tyro-dashboard::badge>
                    @endif
                    <button type="button" class="btn btn-icon btn-ghost health-copy-btn" title="Copy card as image" aria-label="Copy card as image" onclick="copyHealthCard(this)">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="9" cy="9" r="2"/><path d="M21 15l-5-5L5 21"/></svg>
                    </button>
                </div>
            </div>
        </div>
        <div class="card-body">
            @if(! $database['available'])
                <x-tyro-dashboard::alert variant="warning" title="Database probe failed">
                    {{ $database['reason'] }}
                </x-tyro-dashboard::alert>
            @else
                <div class="health-kv">
                    <div class="health-kv-row">
                        <span class="health-kv-label">Driver</span>
                        <span class="health-kv-value">{{ $database['driverName'] }}</span>
                    </div>
                    @if(filled($database['database']))
                        <div class="health-kv-row">
                            <span class="health-kv-label">Database</span>
                            <span class="health-kv-value">{{ $database['database'] }}</span>
                        </div>
                    @endif
                    @if(filled($database['serverVersion']))
                        <div class="health-kv-row">
                            <span class="health-kv-label">Server Version</span>
                            <span class="health-kv-value">{{ $database['serverVersion'] }}</span>
                        </div>
                    @endif
                    <div class="health-kv-row">
                        <span class="health-kv-label">Tables</span>
                        <span class="health-kv-value">{{ $database['tableCount'] }}</span>
                    </div>
                    @if(filled($database['sizeForHumans'] ?? null))
                        <div class="health-kv-row">
                            <span class="health-kv-label">Data + Index Size</span>
                            <span class="health-kv-value">{{ $database['sizeForHumans'] }}</span>
                        </div>
                    @endif
                </div>
            @endif
        </div>
    </div>

    <div class="card" data-card-title="Queue">
        <div class="card-header">
            <div class="health-card-head">
                <h3 class="card-title">Queue</h3>
                <div class="health-card-actions">
                    @php
                        $queueBadges = [
                            'reachable' => ['success', 'Reachable'],
                            'configured' => ['info', 'Configured'],
                            'unknown' => ['secondary', 'Unknown'],
                            'unreachable' => ['danger', 'Unreachable'],
                        ];
                        [$queueBadgeVariant, $queueBadgeLabel] = $queueBadges[$queue['status']] ?? ['secondary', $queue['status']];
                    @endphp
                    <x-tyro-dashboard::badge :variant="$queueBadgeVariant">{{ $queueBadgeLabel }}</x-tyro-dashboard::badge>
                    <button type="button" class="btn btn-icon btn-ghost health-copy-btn" title="Copy card as image" aria-label="Copy card as image" onclick="copyHealthCard(this)">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="9" cy="9" r="2"/><path d="M21 15l-5-5L5 21"/></svg>
                    </button>
                </div>
            </div>
        </div>
        <div class="card-body">
            @if(! $queue['available'])
                <x-tyro-dashboard::alert variant="warning" title="Queue probe failed">
                    {{ $queue['reason'] }}
                </x-tyro-dashboard::alert>
            @else
                <div class="health-kv">
                    <div class="health-kv-row">
                        <span class="health-kv-label">Default Connection</span>
                        <span class="health-kv-value">{{ $queue['connection'] }}</span>
                    </div>
                    @if(filled($queue['detail']))
                        <span class="health-kv-sub">{{ $queue['detail'] }}</span>
                    @endif
                    @if($queue['horizon'])
                        <span class="health-kv-sub">Horizon is installed — queue depth and job monitoring belong to Horizon.</span>
                    @endif
                    <span class="health-kv-sub">This page only checks reachability. It never reads, lists, or modifies jobs.</span>
                </div>
            @endif
        </div>
    </div>

    <div class="card" data-card-title="Disk Usage">
        <div class="card-header">
            <div class="health-card-head">
                <h3 class="card-title">Disk Usage</h3>
                <div class="health-card-actions">
                    @if($disk['available'])
                        <x-tyro-dashboard::badge variant="success">Measured</x-tyro-dashboard::badge>
                    @else
                        <x-tyro-dashboard::badge variant="warning">Unavailable</x-tyro-dashboard::badge>
                    @endif
                    <button type="button" class="btn btn-icon btn-ghost health-copy-btn" title="Copy card as image" aria-label="Copy card as image" onclick="copyHealthCard(this)">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="9" cy="9" r="2"/><path d="M21 15l-5-5L5 21"/></svg>
                    </button>
                </div>
            </div>
        </div>
        <div class="card-body">
            @if(! $disk['available'])
                <x-tyro-dashboard::alert variant="warning" title="Disk statistics are unavailable">
                    This host does not report disk totals for the application paths.
                </x-tyro-dashboard::alert>
            @else
                <div class="health-kv">
                    @foreach($disk['rows'] as $row)
                        @if($row['available'])
                            @php
                                $diskVariant = $row['usedPercent'] >= 90 ? 'error' : ($row['usedPercent'] >= 75 ? 'warning' : 'success');
                            @endphp
                            <x-tyro-dashboard::progress
                                :value="(int) round($row['usedPercent'])"
                                :variant="$diskVariant"
                                :label="$row['label'].' — '.$row['usedForHumans'].' of '.$row['totalForHumans'].' used'"
                                showLabel
                            />
                            <span class="health-kv-sub">{{ $row['label'] }}: {{ $row['freeForHumans'] }} free</span>
                        @else
                            <div class="health-kv-row">
                                <span class="health-kv-label">{{ $row['label'] }}</span>
                                <span class="health-kv-value">Unavailable</span>
                            </div>
                        @endif
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    <div class="card" data-card-title="OPcache">
        <div class="card-header">
            <div class="health-card-head">
                <h3 class="card-title">OPcache</h3>
                <div class="health-card-actions">
                    @if($opcache['available'])
                        <x-tyro-dashboard::badge variant="success">Enabled</x-tyro-dashboard::badge>
                    @else
                        <x-tyro-dashboard::badge variant="warning">Unavailable</x-tyro-dashboard::badge>
                    @endif
                    <button type="button" class="btn btn-icon btn-ghost health-copy-btn" title="Copy card as image" aria-label="Copy card as image" onclick="copyHealthCard(this)">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="9" cy="9" r="2"/><path d="M21 15l-5-5L5 21"/></svg>
                    </button>
                </div>
            </div>
        </div>
        <div class="card-body">
            @if(! $opcache['available'])
                <x-tyro-dashboard::alert variant="warning" title="OPcache is unavailable">
                    {{ $opcache['reason'] }}
                </x-tyro-dashboard::alert>
            @else
                <div class="health-kv">
                    <div class="health-kv-row">
                        <span class="health-kv-label">Hit Rate</span>
                        <span class="health-kv-value">{{ $opcache['hitRate'] !== null ? $opcache['hitRate'].'%' : '—' }}</span>
                    </div>
                    <div class="health-kv-row">
                        <span class="health-kv-label">Cached Scripts</span>
                        <span class="health-kv-value">{{ $opcache['numScripts'] ?? '—' }}</span>
                    </div>
                    @if($opcache['usedPercent'] !== null && $opcache['total'] > 0)
                        <x-tyro-dashboard::progress
                            :value="(int) round($opcache['usedPercent'])"
                            variant="primary"
                            :label="'Memory — '.$opcache['usedForHumans'].' of '.$opcache['totalForHumans'].' used'"
                            showLabel
                        />
                        <span class="health-kv-sub">{{ $opcache['wastedForHumans'] }} wasted memory</span>
                    @endif
                </div>
            @endif
        </div>
    </div>

    <div class="card" data-card-title="Storage Writability">
        <div class="card-header">
            <div class="health-card-head">
                <h3 class="card-title">Storage Writability</h3>
                <div class="health-card-actions">
                    @if($storage['available'])
                        <x-tyro-dashboard::badge variant="success">Checked</x-tyro-dashboard::badge>
                    @else
                        <x-tyro-dashboard::badge variant="warning">Unavailable</x-tyro-dashboard::badge>
                    @endif
                    <button type="button" class="btn btn-icon btn-ghost health-copy-btn" title="Copy card as image" aria-label="Copy card as image" onclick="copyHealthCard(this)">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="9" cy="9" r="2"/><path d="M21 15l-5-5L5 21"/></svg>
                    </button>
                </div>
            </div>
        </div>
        <div class="card-body">
            @if(! $storage['available'])
                <x-tyro-dashboard::alert variant="warning" title="Storage probe failed">
                    {{ $storage['reason'] }}
                </x-tyro-dashboard::alert>
            @else
                <div class="health-kv">
                    @foreach($storage['rows'] as $row)
                        <div class="health-kv-row">
                            <span class="health-kv-label">{{ $row['label'] }}</span>
                            @if($row['writable'])
                                <x-tyro-dashboard::badge variant="success">Writable</x-tyro-dashboard::badge>
                            @elseif($row['exists'])
                                <x-tyro-dashboard::badge variant="danger">Not writable</x-tyro-dashboard::badge>
                            @else
                                <x-tyro-dashboard::badge variant="warning">Missing</x-tyro-dashboard::badge>
                            @endif
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    <div class="card" data-card-title="Runtime Context">
        <div class="card-header">
            <div class="health-card-head">
                <h3 class="card-title">Runtime Context</h3>
                <div class="health-card-actions">
                    <x-tyro-dashboard::badge variant="secondary">Read-only</x-tyro-dashboard::badge>
                    <button type="button" class="btn btn-icon btn-ghost health-copy-btn" title="Copy card as image" aria-label="Copy card as image" onclick="copyHealthCard(this)">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="9" cy="9" r="2"/><path d="M21 15l-5-5L5 21"/></svg>
                    </button>
                </div>
            </div>
        </div>
        <div class="card-body">
            @if(! $runtime['available'])
                <x-tyro-dashboard::alert variant="warning" title="Runtime probe failed">
                    {{ $runtime['reason'] }}
                </x-tyro-dashboard::alert>
            @else
                <div class="health-kv">
                    <div class="health-kv-row">
                        <span class="health-kv-label">Laravel</span>
                        <span class="health-kv-value">{{ $runtime['laravel'] }}</span>
                    </div>
                    <div class="health-kv-row">
                        <span class="health-kv-label">Environment</span>
                        <span class="health-kv-value">{{ $runtime['environment'] }}</span>
                    </div>
                    <div class="health-kv-row">
                        <span class="health-kv-label">Debug Mode</span>
                        @if($runtime['debug'])
                            <x-tyro-dashboard::badge variant="danger">On</x-tyro-dashboard::badge>
                        @else
                            <x-tyro-dashboard::badge variant="secondary">Off</x-tyro-dashboard::badge>
                        @endif
                    </div>
                    <div class="health-kv-row">
                        <span class="health-kv-label">SAPI</span>
                        <span class="health-kv-value">{{ $runtime['sapi'] }}</span>
                    </div>
                    <div class="health-kv-row">
                        <span class="health-kv-label">Operating System</span>
                        <span class="health-kv-value">{{ $runtime['os'] }}</span>
                    </div>
                    <div class="health-kv-row">
                        <span class="health-kv-label">App Timezone</span>
                        <span class="health-kv-value">{{ $runtime['appTimezone'] }}</span>
                    </div>
                    <div class="health-kv-row">
                        <span class="health-kv-label">PHP Timezone</span>
                        <span class="health-kv-value">{{ $runtime['phpTimezone'] }}</span>
                    </div>
                    @if($runtime['timezoneMismatch'])
                        <x-tyro-dashboard::alert variant="warning" title="Timezone mismatch">
                            The application timezone ({{ $runtime['appTimezone'] }}) differs from the PHP runtime timezone ({{ $runtime['phpTimezone'] }}). Laravel uses the app timezone for dates/Eloquent; the PHP timezone affects date functions without an explicit timezone. Align them to avoid confusing logs, schedules, and timestamps.
                        </x-tyro-dashboard::alert>
                    @endif
                </div>
            @endif
        </div>
    </div>

    <div class="card" data-card-title="Tyro Ecosystem">
        <div class="card-header">
            <div class="health-card-head">
                <h3 class="card-title">Tyro Ecosystem</h3>
                <div class="health-card-actions">
                    @if($ecosystem['available'])
                        <x-tyro-dashboard::badge variant="info">{{ count($ecosystem['packages']) }} packages</x-tyro-dashboard::badge>
                    @else
                        <x-tyro-dashboard::badge variant="warning">Unavailable</x-tyro-dashboard::badge>
                    @endif
                    <button type="button" class="btn btn-icon btn-ghost health-copy-btn" title="Copy card as image" aria-label="Copy card as image" onclick="copyHealthCard(this)">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="9" cy="9" r="2"/><path d="M21 15l-5-5L5 21"/></svg>
                    </button>
                </div>
            </div>
        </div>
        <div class="card-body">
            @if(! $ecosystem['available'])
                <x-tyro-dashboard::alert variant="warning" title="Ecosystem versions are unavailable">
                    {{ $ecosystem['reason'] }}
                </x-tyro-dashboard::alert>
            @elseif(count($ecosystem['packages']) === 0)
                <span class="health-kv-sub">No hasinhayder/* packages were found in composer.lock.</span>
            @else
                <div class="health-kv">
                    @foreach($ecosystem['packages'] as $package)
                        <div class="health-kv-row">
                            <span class="health-kv-label">hasinhayder/{{ $package['name'] }}</span>
                            <span class="health-kv-value">
                                {{ $package['version'] }}
                                @if($package['dev'])
                                    <x-tyro-dashboard::badge variant="secondary">dev dependency</x-tyro-dashboard::badge>
                                @endif
                            </span>
                        </div>
                    @endforeach
                    <span class="health-kv-sub">Installed versions read from your application's composer.lock.</span>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function healthEscapeXml(text) {
        return String(text == null ? '' : text)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&apos;');
    }

    function healthCssVar(name) {
        var value = getComputedStyle(document.documentElement).getPropertyValue(name);
        return value ? value.trim() : '';
    }

    function healthResolveColors() {
        return {
            background: healthCssVar('--background') || '#fafafa',
            card: healthCssVar('--card') || '#ffffff',
            foreground: healthCssVar('--foreground') || '#18181b',
            muted: healthCssVar('--muted-foreground') || '#71717a',
            mutedBg: healthCssVar('--muted') || '#f4f4f5',
            border: healthCssVar('--border') || '#e4e4e7',
            primary: healthCssVar('--primary') || '#18181b',
            success: healthCssVar('--success') || '#16a34a',
            warning: healthCssVar('--warning') || '#d97706',
            danger: healthCssVar('--destructive') || '#dc2626',
            info: healthCssVar('--info') || '#2563eb',
            accent: healthCssVar('--accent') || '#f4f4f5'
        };
    }

    function healthBadgeColors(colors, variant) {
        switch (variant) {
            case 'success': return { fill: colors.success, text: colors.success };
            case 'warning': return { fill: colors.warning, text: colors.warning };
            case 'danger': return { fill: colors.danger, text: colors.danger };
            case 'info': return { fill: colors.info, text: colors.info };
            default: return { fill: colors.accent, text: colors.muted };
        }
    }

    function healthProgressColor(colors, variant) {
        switch (variant) {
            case 'success': return colors.success;
            case 'warning': return colors.warning;
            case 'error':
            case 'danger': return colors.danger;
            case 'info': return colors.info;
            default: return colors.primary;
        }
    }

    function healthCleanText(el) {
        return el ? el.textContent.replace(/\s+/g, ' ').trim() : '';
    }

    function healthBadgeVariant(el) {
        if (!el) {
            return '';
        }
        var match = el.className.match(/badge-([a-z]+)/);
        return match ? match[1] : '';
    }

    function healthTruncateText(text, maxChars) {
        text = String(text == null ? '' : text);
        if (maxChars < 4) {
            maxChars = 4;
        }
        return text.length > maxChars ? text.slice(0, maxChars - 1).trimEnd() + '…' : text;
    }

    function healthWrapText(text, maxChars) {
        var words = String(text || '').split(/\s+/).filter(Boolean);
        if (!words.length) {
            return [];
        }
        var lines = [];
        var line = '';
        words.forEach(function (word) {
            if (!line) {
                line = word;
            } else if ((line + ' ' + word).length <= maxChars) {
                line += ' ' + word;
            } else {
                lines.push(line);
                line = word;
            }
        });
        lines.push(line);
        return lines;
    }

    function healthEstimateWidth(text, fontSize, factor) {
        return String(text || '').length * fontSize * (factor || 0.55);
    }

    function healthCollectContent(cardEl) {
        var content = { title: '', badge: null, statVariant: '', rows: [] };

        var statCard = cardEl.matches('.stat-card') ? cardEl : cardEl.querySelector('.stat-card');
        if (statCard) {
            content.title = healthCleanText(statCard.querySelector('.stat-label')) || 'Stat';
            content.rows.push({ type: 'stat', value: healthCleanText(statCard.querySelector('.stat-value')) || '—' });
            var iconEl = statCard.querySelector('[class*="stat-icon-"]');
            content.statVariant = iconEl ? (iconEl.className.match(/stat-icon-([a-z]+)/) || [])[1] || '' : '';
            return content;
        }

        content.title = healthCleanText(cardEl.querySelector('.card-title')) || 'Card';

        var headBadge = cardEl.querySelector('.health-card-head .badge');
        if (headBadge) {
            content.badge = { text: healthCleanText(headBadge), variant: healthBadgeVariant(headBadge) };
        }

        var body = cardEl.querySelector('.card-body');
        if (!body) {
            return content;
        }

        var nodes = body.querySelectorAll('.health-kv-row, .health-kv-sub, .progress-wrapper, .alert');
        Array.prototype.forEach.call(nodes, function (el) {
            if (el.classList.contains('health-kv-row')) {
                var valueEl = el.querySelector('.health-kv-value');
                var badge = el.querySelector('.badge');
                content.rows.push({
                    type: 'kv',
                    label: healthCleanText(el.querySelector('.health-kv-label')),
                    value: healthCleanText(valueEl) || healthCleanText(badge) || '—',
                    variant: badge ? healthBadgeVariant(badge) : ''
                });
            } else if (el.classList.contains('health-kv-sub')) {
                content.rows.push({ type: 'sub', text: healthCleanText(el) });
            } else if (el.classList.contains('progress-wrapper')) {
                var track = el.querySelector('.progress-track');
                var bar = el.querySelector('[class*="progress-bar-"]');
                content.rows.push({
                    type: 'progress',
                    label: healthCleanText(el.querySelector('span')),
                    percent: track ? (parseInt(track.getAttribute('aria-valuenow') || '0', 10) || 0) : 0,
                    variant: bar ? ((bar.className.match(/progress-bar-([a-z]+)/) || [])[1] || 'primary') : 'primary'
                });
            } else if (el.classList.contains('alert')) {
                content.rows.push({
                    type: 'alert',
                    title: healthCleanText(el.querySelector('.alert-title')),
                    text: healthCleanText(el.querySelector('.alert-message')) || healthCleanText(el.querySelector('.alert-content')),
                    variant: (el.className.match(/alert-([a-z]+)/) || [])[1] || 'warning'
                });
            }
        });

        return content;
    }

    function healthCaption() {
        var raw = (document.title || '').trim();
        var appName = raw.indexOf(' - ') !== -1 ? raw.split(' - ').pop().trim() : raw;
        var now = new Date();
        var months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        var ts = months[now.getMonth()] + ' ' + now.getDate() + ', '
            + String(now.getHours()).padStart(2, '0') + ':' + String(now.getMinutes()).padStart(2, '0');
        if (!appName || appName.toLowerCase() === 'tyro dashboard') {
            return 'Tyro Dashboard • ' + ts;
        }
        return appName + ' • Tyro Dashboard • ' + ts;
    }

    function healthRenderDetailCard(content, W, colors) {
        var PAD = 28;
        var INNER = W - PAD * 2;
        var body = [];
        var esc = healthEscapeXml;
        var y = PAD + 2;

        var pillW = 0;
        if (content.badge && content.badge.text) {
            pillW = Math.ceil(healthEstimateWidth(content.badge.text, 12, 0.58)) + 22;
            var pillH = 24;
            var pillX = W - PAD - pillW;
            var pillY = y;
            var bc = healthBadgeColors(colors, content.badge.variant);
            var tinted = content.badge.variant !== 'secondary';
            body.push('<rect x="' + pillX + '" y="' + pillY + '" width="' + pillW + '" height="' + pillH + '" rx="' + (pillH / 2) + '" fill="' + bc.fill + '"' + (tinted ? ' fill-opacity="0.12"' : '') + '/>');
            body.push('<text x="' + (pillX + pillW / 2) + '" y="' + (pillY + 16) + '" text-anchor="middle" font-size="12" font-weight="500" fill="' + bc.text + '">' + esc(content.badge.text) + '</text>');
        }

        var titleMaxChars = Math.max(20, Math.floor((INNER - (pillW ? pillW + 16 : 0)) / 9));
        var title = healthTruncateText(content.title, titleMaxChars);
        if (content.statVariant) {
            body.push('<rect x="' + PAD + '" y="' + (y + 1) + '" width="4" height="22" rx="2" fill="' + healthProgressColor(colors, content.statVariant) + '"/>');
            body.push('<text x="' + (PAD + 16) + '" y="' + (y + 18) + '" font-size="16" font-weight="600" fill="' + colors.foreground + '">' + esc(title) + '</text>');
        } else {
            body.push('<text x="' + PAD + '" y="' + (y + 18) + '" font-size="16" font-weight="600" fill="' + colors.foreground + '">' + esc(title) + '</text>');
        }
        y += 30;
        body.push('<line x1="' + PAD + '" y1="' + y + '" x2="' + (W - PAD) + '" y2="' + y + '" stroke="' + colors.border + '" stroke-width="1"/>');
        y += 22;

        content.rows.forEach(function (row) {
            if (row.type === 'kv') {
                var label = healthTruncateText(row.label, 42);
                var valueMaxChars = Math.max(12, Math.floor((INNER - healthEstimateWidth(label, 13) - 24) / 7.2));
                var value = healthTruncateText(row.value, valueMaxChars);
                var valueColor = row.variant ? healthBadgeColors(colors, row.variant).text : colors.foreground;
                body.push('<text x="' + PAD + '" y="' + (y + 13) + '" font-size="13" fill="' + colors.muted + '">' + esc(label) + '</text>');
                body.push('<text x="' + (W - PAD) + '" y="' + (y + 13) + '" text-anchor="end" font-size="13" font-weight="500" fill="' + valueColor + '">' + esc(value) + '</text>');
                y += 26;
            } else if (row.type === 'stat') {
                var statValue = healthTruncateText(row.value, Math.floor(INNER / 13.2));
                body.push('<text x="' + PAD + '" y="' + (y + 26) + '" font-size="24" font-weight="700" fill="' + colors.foreground + '">' + esc(statValue) + '</text>');
                y += 42;
            } else if (row.type === 'sub') {
                healthWrapText(row.text, Math.floor(INNER / 6.1)).forEach(function (line) {
                    body.push('<text x="' + PAD + '" y="' + (y + 11) + '" font-size="11" fill="' + colors.muted + '">' + esc(line) + '</text>');
                    y += 15;
                });
                y += 4;
            } else if (row.type === 'progress') {
                var progressLabel = healthTruncateText(row.label, Math.floor((INNER - 56) / 7.2));
                body.push('<text x="' + PAD + '" y="' + (y + 13) + '" font-size="13" fill="' + colors.muted + '">' + esc(progressLabel) + '</text>');
                body.push('<text x="' + (W - PAD) + '" y="' + (y + 13) + '" text-anchor="end" font-size="13" font-weight="600" fill="' + colors.foreground + '">' + row.percent + '%</text>');
                y += 20;
                body.push('<rect x="' + PAD + '" y="' + y + '" width="' + INNER + '" height="8" rx="4" fill="' + colors.mutedBg + '"/>');
                var fillW = Math.max(0, Math.min(100, row.percent)) / 100 * INNER;
                if (fillW > 0) {
                    body.push('<rect x="' + PAD + '" y="' + y + '" width="' + Math.max(8, Math.round(fillW)) + '" height="8" rx="4" fill="' + healthProgressColor(colors, row.variant) + '"/>');
                }
                y += 22;
            } else if (row.type === 'alert') {
                var alertColor = row.variant === 'error' ? colors.danger : colors.warning;
                var alertMaxChars = Math.floor((INNER - 36) / 6.6);
                var alertLines = healthWrapText(row.text, alertMaxChars);
                var boxH = 12 + (row.title ? 16 : 0) + alertLines.length * 16 + 12;
                body.push('<rect x="' + PAD + '" y="' + y + '" width="' + INNER + '" height="' + boxH + '" rx="8" fill="' + alertColor + '" fill-opacity="0.1" stroke="' + alertColor + '" stroke-opacity="0.35"/>');
                var alertY = y + 24;
                if (row.title) {
                    body.push('<text x="' + (PAD + 12) + '" y="' + alertY + '" font-size="13" font-weight="600" fill="' + alertColor + '">' + esc(healthTruncateText(row.title, alertMaxChars)) + '</text>');
                    alertY += 16;
                }
                alertLines.forEach(function (line) {
                    body.push('<text x="' + (PAD + 12) + '" y="' + alertY + '" font-size="12" fill="' + colors.muted + '">' + esc(line) + '</text>');
                    alertY += 16;
                });
                y += boxH + 12;
            }
        });

        return { body: body, height: y + 10 };
    }

    function healthBuildSvg(content) {
        var colors = healthResolveColors();
        var W = 720;
        var PAD = 28;
        var rendered = healthRenderDetailCard(content, W, colors);
        var body = rendered.body.slice();
        var y = rendered.height + 2;
        body.push('<line x1="' + PAD + '" y1="' + y + '" x2="' + (W - PAD) + '" y2="' + y + '" stroke="' + colors.border + '" stroke-width="1"/>');
        y += 21;
        body.push('<text x="' + PAD + '" y="' + y + '" font-size="11" fill="' + colors.muted + '">' + healthEscapeXml(healthCaption()) + '</text>');
        var H = Math.max(y + 20, 132);

        var svg = '<svg xmlns="http://www.w3.org/2000/svg" width="' + W + '" height="' + H + '" viewBox="0 0 ' + W + ' ' + H + '" font-family="Inter, system-ui, -apple-system, sans-serif">'
            + '<rect x="0.5" y="0.5" width="' + (W - 1) + '" height="' + (H - 1) + '" rx="12" fill="' + colors.card + '" stroke="' + colors.border + '" stroke-opacity="0.6"/>'
            + body.join('')
            + '</svg>';

        return { svg: svg, width: W, height: H };
    }

    function healthRenderStatCard(content, W, colors) {
        var body = [];
        var esc = healthEscapeXml;
        var accent = content.statVariant ? healthProgressColor(colors, content.statVariant) : colors.primary;
        var value = content.rows.length ? content.rows[0].value : '—';
        body.push('<rect x="0.5" y="0.5" width="' + (W - 1) + '" height="79" rx="12" fill="' + colors.card + '" stroke="' + colors.border + '" stroke-opacity="0.6"/>');
        body.push('<rect x="16" y="18" width="4" height="44" rx="2" fill="' + accent + '"/>');
        body.push('<text x="30" y="33" font-size="11" fill="' + colors.muted + '">' + esc(healthTruncateText(content.title, Math.floor((W - 46) / 6))) + '</text>');
        body.push('<text x="30" y="60" font-size="19" font-weight="700" fill="' + colors.foreground + '">' + esc(healthTruncateText(value, Math.floor((W - 46) / 10.5))) + '</text>');
        return { body: body, height: 80 };
    }

    function healthBuildPageSvg() {
        var colors = healthResolveColors();
        var W = 720;
        var PAD = 28;
        var esc = healthEscapeXml;
        var parts = [];
        var y = PAD + 6;

        var pageTitle = healthCleanText(document.querySelector('.page-title')) || 'System Health';
        var pageDesc = healthCleanText(document.querySelector('.page-description'));
        parts.push('<text x="' + PAD + '" y="' + (y + 20) + '" font-size="24" font-weight="700" fill="' + colors.foreground + '">' + esc(healthTruncateText(pageTitle, 46)) + '</text>');
        y += 34;
        if (pageDesc) {
            healthWrapText(pageDesc, 88).forEach(function (line) {
                parts.push('<text x="' + PAD + '" y="' + (y + 12) + '" font-size="12" fill="' + colors.muted + '">' + esc(line) + '</text>');
                y += 16;
            });
        }
        y += 10;

        var section = '';
        var asOf = '';
        var blocks = document.querySelectorAll('.stats-grid, .health-section-head, .grid-2');
        Array.prototype.forEach.call(blocks, function (block) {
            if (block.classList.contains('health-section-head')) {
                section = healthCleanText(block.querySelector('h2'));
                asOf = healthCleanText(block.querySelector('.health-asof'));
                return;
            }
            if (block.classList.contains('stats-grid')) {
                var statW = Math.floor((W - 14) / 2);
                var stats = [];
                Array.prototype.forEach.call(block.querySelectorAll('.health-stat-wrap'), function (wrapEl) {
                    stats.push(healthRenderStatCard(healthCollectContent(wrapEl), statW, colors));
                });
                for (var i = 0; i < stats.length; i += 2) {
                    var rowH = 0;
                    stats.slice(i, i + 2).forEach(function (stat, idx) {
                        parts.push('<g transform="translate(' + idx * (statW + 14) + ',' + y + ')">' + stat.body.join('') + '</g>');
                        rowH = Math.max(rowH, stat.height);
                    });
                    y += rowH + 14;
                }
                y += 4;
                return;
            }
            if (block.classList.contains('grid-2')) {
                if (section) {
                    var label = asOf ? section + ' — ' + asOf : section;
                    parts.push('<text x="' + PAD + '" y="' + (y + 12) + '" font-size="13" font-weight="600" fill="' + colors.foreground + '">' + esc(healthTruncateText(label, 92)) + '</text>');
                    y += 26;
                    section = '';
                    asOf = '';
                }
                Array.prototype.forEach.call(block.querySelectorAll('.card'), function (cardEl) {
                    var rendered = healthRenderDetailCard(healthCollectContent(cardEl), W, colors);
                    parts.push('<g transform="translate(0,' + y + ')">' + rendered.body.join('') + '</g>');
                    y += rendered.height + 6;
                });
            }
        });

        y += 6;
        parts.push('<line x1="' + PAD + '" y1="' + y + '" x2="' + (W - PAD) + '" y2="' + y + '" stroke="' + colors.border + '" stroke-width="1"/>');
        y += 21;
        parts.push('<text x="' + PAD + '" y="' + y + '" font-size="11" fill="' + colors.muted + '">' + esc(healthCaption()) + '</text>');
        var H = y + 20;

        var svg = '<svg xmlns="http://www.w3.org/2000/svg" width="' + W + '" height="' + H + '" viewBox="0 0 ' + W + ' ' + H + '" font-family="Inter, system-ui, -apple-system, sans-serif">'
            + '<rect x="0" y="0" width="' + W + '" height="' + H + '" fill="' + colors.background + '"/>'
            + parts.join('')
            + '</svg>';

        return { svg: svg, width: W, height: H };
    }

    function healthSvgToPng(svgString, width, height) {
        return new Promise(function (resolve, reject) {
            var blob = new Blob([svgString], { type: 'image/svg+xml;charset=utf-8' });
            var url = URL.createObjectURL(blob);
            var img = new Image();
            img.onload = function () {
                try {
                    var canvas = document.createElement('canvas');
                    canvas.width = width * 2;
                    canvas.height = height * 2;
                    var ctx = canvas.getContext('2d');
                    ctx.drawImage(img, 0, 0, canvas.width, canvas.height);
                    URL.revokeObjectURL(url);
                    canvas.toBlob(function (pngBlob) {
                        if (pngBlob) {
                            resolve(pngBlob);
                        } else {
                            reject(new Error('PNG encoding failed'));
                        }
                    }, 'image/png');
                } catch (err) {
                    URL.revokeObjectURL(url);
                    reject(err);
                }
            };
            img.onerror = function () {
                URL.revokeObjectURL(url);
                reject(new Error('SVG rasterization failed'));
            };
            img.src = url;
        });
    }

    function healthCopyPngBlob(blob, nameTitle, successMessage) {
        function downloadFallback() {
            var slug = nameTitle.toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/^-+|-+$/g, '') || 'card';
            var now = new Date();
            var stamp = now.getFullYear()
                + String(now.getMonth() + 1).padStart(2, '0')
                + String(now.getDate()).padStart(2, '0')
                + '-' + String(now.getHours()).padStart(2, '0')
                + String(now.getMinutes()).padStart(2, '0');
            var url = URL.createObjectURL(blob);
            var link = document.createElement('a');
            link.href = url;
            link.download = 'health-' + slug + '-' + stamp + '.png';
            document.body.appendChild(link);
            link.click();
            link.remove();
            setTimeout(function () { URL.revokeObjectURL(url); }, 10000);
            showToast('Clipboard image not supported — the PNG was downloaded instead.', 'warning');
        }
        if (window.ClipboardItem && navigator.clipboard && navigator.clipboard.write) {
            return navigator.clipboard.write([new ClipboardItem({ 'image/png': blob })]).then(function () {
                showToast(successMessage, 'success');
            }).catch(downloadFallback);
        }
        downloadFallback();
        return Promise.resolve();
    }

    function copyHealthCard(btn) {
        var cardEl = btn.closest('.card') || btn.closest('.health-stat-wrap');
        if (!cardEl) {
            return;
        }
        var cardTitle = cardEl.getAttribute('data-card-title') || 'Health Card';
        btn.disabled = true;
        var built = null;
        try {
            built = healthBuildSvg(healthCollectContent(cardEl));
        } catch (err) {
            built = null;
        }
        if (!built) {
            btn.disabled = false;
            showToast('Could not generate the card image.', 'error');
            return;
        }
        healthSvgToPng(built.svg, built.width, built.height).then(function (blob) {
            return healthCopyPngBlob(blob, cardTitle, 'Card image copied to the clipboard.');
        }).catch(function () {
            showToast('Could not generate the card image.', 'error');
        }).finally(function () {
            btn.disabled = false;
        });
    }

    function copyHealthPage(btn) {
        btn.disabled = true;
        var built = null;
        try {
            built = healthBuildPageSvg();
        } catch (err) {
            built = null;
        }
        if (!built) {
            btn.disabled = false;
            showToast('Could not generate the page image.', 'error');
            return;
        }
        healthSvgToPng(built.svg, built.width, built.height).then(function (blob) {
            return healthCopyPngBlob(blob, 'Page', 'Page image copied to the clipboard.');
        }).catch(function () {
            showToast('Could not generate the page image.', 'error');
        }).finally(function () {
            btn.disabled = false;
        });
    }
</script>
@endpush
