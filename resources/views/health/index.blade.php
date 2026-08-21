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
</style>
@endpush

@section('content')
<div class="page-header">
    <div class="page-header-row">
        <div>
            <h1 class="page-title">System Health</h1>
            <p class="page-description">Read-only runtime diagnostics for this application. Nothing on this page changes any setting.</p>
        </div>
    </div>
</div>

<div class="stats-grid">
    <x-tyro-dashboard::stat label="PHP Version" :value="$php['version']" variant="primary" />
    <x-tyro-dashboard::stat
        label="Database"
        :value="$database['available'] ? $database['driverName'] : '—'"
        :variant="$database['available'] ? 'success' : 'warning'"
    />
    <x-tyro-dashboard::stat label="Storage Disk Free" :value="$diskFreeForHumans ?? '—'" variant="info" />
    <x-tyro-dashboard::stat
        label="Cache Latency"
        :value="$cache['available'] && $cache['passed'] ? $cache['latencyMs'].' ms' : 'Unavailable'"
        :variant="$cache['available'] && $cache['passed'] ? 'success' : 'danger'"
    />
</div>

<div class="health-section-head">
    <h2>Live probes</h2>
    <span class="health-asof">Always current — never cached</span>
</div>

<div class="grid-2">
    <div class="card">
        <div class="card-header">
            <div class="health-card-head">
                <h3 class="card-title">PHP &amp; Memory</h3>
                <x-tyro-dashboard::badge variant="info">Live</x-tyro-dashboard::badge>
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

    <div class="card">
        <div class="card-header">
            <div class="health-card-head">
                <h3 class="card-title">Cache</h3>
                <x-tyro-dashboard::badge variant="info">Live</x-tyro-dashboard::badge>
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
    <div class="card">
        <div class="card-header">
            <div class="health-card-head">
                <h3 class="card-title">Database</h3>
                @if($database['available'])
                    <x-tyro-dashboard::badge variant="success">Connected</x-tyro-dashboard::badge>
                @else
                    <x-tyro-dashboard::badge variant="warning">Unavailable</x-tyro-dashboard::badge>
                @endif
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

    <div class="card">
        <div class="card-header">
            <div class="health-card-head">
                <h3 class="card-title">Queue</h3>
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

    <div class="card">
        <div class="card-header">
            <div class="health-card-head">
                <h3 class="card-title">Disk Usage</h3>
                @if($disk['available'])
                    <x-tyro-dashboard::badge variant="success">Measured</x-tyro-dashboard::badge>
                @else
                    <x-tyro-dashboard::badge variant="warning">Unavailable</x-tyro-dashboard::badge>
                @endif
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

    <div class="card">
        <div class="card-header">
            <div class="health-card-head">
                <h3 class="card-title">OPcache</h3>
                @if($opcache['available'])
                    <x-tyro-dashboard::badge variant="success">Enabled</x-tyro-dashboard::badge>
                @else
                    <x-tyro-dashboard::badge variant="warning">Unavailable</x-tyro-dashboard::badge>
                @endif
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

    <div class="card">
        <div class="card-header">
            <div class="health-card-head">
                <h3 class="card-title">Storage Writability</h3>
                @if($storage['available'])
                    <x-tyro-dashboard::badge variant="success">Checked</x-tyro-dashboard::badge>
                @else
                    <x-tyro-dashboard::badge variant="warning">Unavailable</x-tyro-dashboard::badge>
                @endif
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

    <div class="card">
        <div class="card-header">
            <div class="health-card-head">
                <h3 class="card-title">Runtime Context</h3>
                <x-tyro-dashboard::badge variant="secondary">Read-only</x-tyro-dashboard::badge>
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

    <div class="card">
        <div class="card-header">
            <div class="health-card-head">
                <h3 class="card-title">Tyro Ecosystem</h3>
                @if($ecosystem['available'])
                    <x-tyro-dashboard::badge variant="info">{{ count($ecosystem['packages']) }} packages</x-tyro-dashboard::badge>
                @else
                    <x-tyro-dashboard::badge variant="warning">Unavailable</x-tyro-dashboard::badge>
                @endif
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
