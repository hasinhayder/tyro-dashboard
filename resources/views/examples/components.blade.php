@extends($isAdmin ? 'tyro-dashboard::layouts.admin' : 'tyro-dashboard::layouts.user')

@section('title', 'Dashboard Components')

@section('breadcrumb')
<a href="{{ route('tyro-dashboard.index') }}">Dashboard</a>
<span class="breadcrumb-separator">/</span>
<span>Components</span>
<span class="breadcrumb-separator">/</span>
<span>Dashboard Components</span>
@endsection

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/quill@1.3.7/dist/quill.snow.css" rel="stylesheet">
@endpush

@section('content')
<div class="page-header">
    <div class="page-header-row">
        <div>
            <h1 class="page-title">Dashboard Components</h1>
            <p class="page-description" style="font-size: 1rem;">Copy-ready building blocks: cards, charts, progress, tables, tabs, dropdowns, forms, and rich text.</p>
        </div>
        <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
            <a href="{{ route('tyro-dashboard.index') }}" class="btn btn-secondary btn-sm">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
                Back to Dashboard
            </a>
            <a href="{{ route('tyro-dashboard.profile') }}" class="btn btn-primary btn-sm">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
                My Profile
            </a>
        </div>
    </div>
</div>

@php($quillInitialHtml = '<h3>Announcement</h3><p>Use <strong>Quill</strong> for rich text content in forms.</p><ul><li>Bold / Italic</li><li>Lists</li><li>Links</li></ul><p><em>Tip:</em> copy the generated HTML below.</p>')

{{-- Alerts (success/warning) --}}
<div class="grid-2" style="margin-bottom: 1.5rem;">
    <div class="alert" style="border-color: color-mix(in srgb, var(--success), transparent 70%); background-color: color-mix(in srgb, var(--success), transparent 92%);">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="color: var(--success);">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
        </svg>
        <div class="alert-content">
            <div class="alert-title">All systems operational</div>
            <div class="alert-message" style="color: var(--muted-foreground);">Queues are healthy, database latency is stable, and error rate is within target.</div>
        </div>
    </div>

    <div class="alert" style="border-color: color-mix(in srgb, var(--warning), transparent 70%); background-color: color-mix(in srgb, var(--warning), transparent 92%);">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="color: var(--warning);">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3m0 4h.01M5.07 19h13.86c1.54 0 2.5-1.67 1.73-3L13.73 4c-.77-1.33-2.69-1.33-3.46 0L3.34 16c-.77 1.33.19 3 1.73 3z" />
        </svg>
        <div class="alert-content">
            <div class="alert-title">Heads up: review pending items</div>
            <div class="alert-message" style="color: var(--muted-foreground);">A few records are waiting for approval. Use badges + tables below to display review queues.</div>
        </div>
    </div>
</div>

{{-- KPI / Stat cards --}}
<div class="stats-grid">
    @foreach($kpis as $kpi)
        <div class="stat-card">
            <div class="stat-icon {{ $kpi['icon_class'] }}">
                {!! $kpi['icon'] !!}
            </div>
            <div class="stat-content">
                <div class="stat-label" style="font-size: 0.9375rem;">{{ $kpi['label'] }}</div>
                <div class="stat-value">{{ $kpi['value'] }}</div>
                <div class="stat-change {{ $kpi['change_class'] }}">
                    {!! $kpi['change_icon'] !!}
                    <span>{{ $kpi['change_text'] }}</span>
                </div>
            </div>
        </div>
    @endforeach
</div>

<div class="grid-2" style="margin-bottom: 1.5rem;">
    {{-- Line chart (SVG) --}}
    <div class="card">
        <div class="card-header">
            <h3 class="card-title" style="font-size: 1.0625rem;">Traffic (Last 14 days)</h3>
            <span class="badge badge-secondary">SVG chart</span>
        </div>
        <div class="card-body">
            <div style="display: flex; align-items: baseline; justify-content: space-between; gap: 1rem; margin-bottom: 1rem;">
                <div>
                    <div style="font-size: 0.875rem; color: var(--muted-foreground);">Total</div>
                    <div style="font-size: 1.75rem; font-weight: 700; letter-spacing: -0.02em;">{{ number_format($charts['traffic_total']) }}</div>
                </div>
                <div class="badge-list">
                    <span class="badge badge-primary">Unique</span>
                    <span class="badge badge-success">+{{ $charts['traffic_growth_pct'] }}%</span>
                </div>
            </div>

            <div style="border: 1px solid var(--border); border-radius: 10px; padding: 1rem; background: var(--muted);">
                <svg viewBox="0 0 600 180" width="100%" height="180" preserveAspectRatio="none" style="display:block; color: var(--primary);">
                    <g opacity="0.35" stroke="currentColor" style="color: var(--muted-foreground);">
                        <path d="M0 150 H600" />
                        <path d="M0 110 H600" />
                        <path d="M0 70 H600" />
                        <path d="M0 30 H600" />
                    </g>
                    <path d="{{ $charts['traffic_area_path'] }}" fill="currentColor" opacity="0.12"></path>
                    <path d="{{ $charts['traffic_line_path'] }}" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"></path>
                </svg>
                <div style="display:flex; justify-content: space-between; margin-top: 0.5rem; font-size: 0.8125rem; color: var(--muted-foreground);">
                    <span>{{ $charts['traffic_range_label_left'] }}</span>
                    <span>{{ $charts['traffic_range_label_right'] }}</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Charts (Tabbed) --}}
    <div class="card">
        <div class="card-header">
            <h3 class="card-title" style="font-size: 1.0625rem;">Charts</h3>
            <span class="badge badge-secondary">Tabbed</span>
        </div>
        <div class="card-body">
            <div data-td-tabset>
                <div class="tabs" style="margin-bottom: 1rem;">
                    <a href="#" class="tab-link active" data-td-tab="donut" onclick="return false;">Donut</a>
                    <a href="#" class="tab-link" data-td-tab="pie" onclick="return false;">Pie</a>
                    <a href="#" class="tab-link" data-td-tab="double-line" onclick="return false;">Double line</a>
                    <a href="#" class="tab-link" data-td-tab="horizontal-bars" onclick="return false;">Horizontal bars</a>
                    <a href="#" class="tab-link" data-td-tab="area" onclick="return false;">Area</a>
                </div>

                {{-- Donut --}}
                <div data-td-tab-panel="donut">
                    <div style="display: grid; grid-template-columns: 140px 1fr; gap: 1.25rem; align-items: center;">
                        <div style="display:flex; align-items:center; justify-content:center;">
                            <svg viewBox="0 0 42 42" width="132" height="132" style="display:block;">
                                <circle cx="21" cy="21" r="15.915" fill="transparent" stroke="var(--border)" stroke-width="6"></circle>
                                @php($offset = 25)
                                @foreach($charts['status_donut'] as $slice)
                                    <circle
                                        cx="21" cy="21" r="15.915"
                                        fill="transparent"
                                        stroke="currentColor"
                                        stroke-width="6"
                                        stroke-dasharray="{{ $slice['pct'] }} {{ 100 - $slice['pct'] }}"
                                        stroke-dashoffset="{{ $offset }}"
                                        stroke-linecap="round"
                                        style="color: {{ $slice['color'] }};"
                                    ></circle>
                                    @php($offset -= $slice['pct'])
                                @endforeach
                            </svg>
                        </div>

                        <div>
                            <div style="display:flex; flex-direction:column; gap: 0.625rem;">
                                @foreach($charts['status_donut'] as $slice)
                                    <div style="display:flex; align-items:center; justify-content: space-between; gap: 1rem;">
                                        <div style="display:flex; align-items:center; gap: 0.5rem; min-width: 0;">
                                            <span style="width: 10px; height: 10px; border-radius: 9999px; background: {{ $slice['color'] }}; display:inline-block;"></span>
                                            <span style="font-size: 0.9375rem; color: var(--foreground); white-space: nowrap; overflow:hidden; text-overflow: ellipsis;">{{ $slice['label'] }}</span>
                                        </div>
                                        <div style="font-size: 0.9375rem; color: var(--muted-foreground);">{{ $slice['count'] }} ({{ $slice['pct'] }}%)</div>
                                    </div>
                                @endforeach
                            </div>
                            <div style="margin-top: 1rem; padding-top: 1rem; border-top: 1px solid var(--border); display:flex; justify-content: space-between;">
                                <span style="font-size: 0.875rem; color: var(--muted-foreground);">Total</span>
                                <strong style="font-size: 0.9375rem;">{{ $charts['status_total'] }}</strong>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Pie --}}
                <div data-td-tab-panel="pie" style="display:none;">
                    <div style="display: grid; grid-template-columns: 140px 1fr; gap: 1.25rem; align-items: center;">
                        <div style="display:flex; align-items:center; justify-content:center;">
                            <svg viewBox="0 0 70 70" width="132" height="132" style="display:block;">
                                <circle cx="35" cy="35" r="15.915" fill="transparent" stroke="var(--border)" stroke-width="32"></circle>
                                @php($offset = 25)
                                @foreach($charts['status_pie'] as $slice)
                                    <circle
                                        cx="35" cy="35" r="15.915"
                                        fill="transparent"
                                        stroke="currentColor"
                                        stroke-width="32"
                                        stroke-dasharray="{{ $slice['pct'] }} {{ 100 - $slice['pct'] }}"
                                        stroke-dashoffset="{{ $offset }}"
                                        stroke-linecap="butt"
                                        style="color: {{ $slice['color'] }};"
                                    ></circle>
                                    @php($offset -= $slice['pct'])
                                @endforeach
                            </svg>
                        </div>

                        <div>
                            <div style="display:flex; flex-direction:column; gap: 0.625rem;">
                                @foreach($charts['status_pie'] as $slice)
                                    <div style="display:flex; align-items:center; justify-content: space-between; gap: 1rem;">
                                        <div style="display:flex; align-items:center; gap: 0.5rem; min-width: 0;">
                                            <span style="width: 10px; height: 10px; border-radius: 9999px; background: {{ $slice['color'] }}; display:inline-block;"></span>
                                            <span style="font-size: 0.9375rem; color: var(--foreground); white-space: nowrap; overflow:hidden; text-overflow: ellipsis;">{{ $slice['label'] }}</span>
                                        </div>
                                        <div style="font-size: 0.9375rem; color: var(--muted-foreground);">{{ $slice['count'] }} ({{ $slice['pct'] }}%)</div>
                                    </div>
                                @endforeach
                            </div>
                            <div style="margin-top: 1rem; padding-top: 1rem; border-top: 1px solid var(--border); display:flex; justify-content: space-between;">
                                <span style="font-size: 0.875rem; color: var(--muted-foreground);">Total</span>
                                <strong style="font-size: 0.9375rem;">{{ $charts['status_total'] }}</strong>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Double line --}}
                <div data-td-tab-panel="double-line" style="display:none;">
                    <div style="display: flex; align-items: baseline; justify-content: space-between; gap: 1rem; margin-bottom: 1rem;">
                        <div>
                            <div style="font-size: 0.875rem; color: var(--muted-foreground);">Total</div>
                            <div style="font-size: 1.75rem; font-weight: 700; letter-spacing: -0.02em;">{{ number_format($charts['compare_total']) }}</div>
                        </div>
                        <div class="badge-list">
                            <span class="badge badge-primary">{{ $charts['compare_line_a_label'] }}</span>
                            <span class="badge badge-secondary">{{ $charts['compare_line_b_label'] }}</span>
                            <span class="badge badge-success">+{{ $charts['compare_growth_pct'] }}%</span>
                        </div>
                    </div>

                    <div style="border: 1px solid var(--border); border-radius: 10px; padding: 1rem; background: var(--muted);">
                        <svg viewBox="0 0 600 180" width="100%" height="180" preserveAspectRatio="none" style="display:block;">
                            <g opacity="0.35" stroke="currentColor" style="color: var(--muted-foreground);">
                                <path d="M0 150 H600" />
                                <path d="M0 110 H600" />
                                <path d="M0 70 H600" />
                                <path d="M0 30 H600" />
                            </g>
                            <path d="{{ $charts['compare_line_b_path'] }}" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" opacity="0.45" style="color: var(--muted-foreground);"></path>
                            <path d="{{ $charts['compare_line_a_path'] }}" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" style="color: var(--foreground);"></path>
                        </svg>
                        <div style="display:flex; justify-content: space-between; margin-top: 0.5rem; font-size: 0.8125rem; color: var(--muted-foreground);">
                            <span>{{ $charts['compare_range_label_left'] }}</span>
                            <span>{{ $charts['compare_range_label_right'] }}</span>
                        </div>
                    </div>
                </div>

                {{-- Horizontal bar --}}
                <div data-td-tab-panel="horizontal-bars" style="display:none;">
                    <div style="display:flex; flex-direction: column; gap: 0.875rem;">
                        @foreach($charts['horizontal_bars'] as $row)
                            <div>
                                <div style="display:flex; justify-content: space-between; gap: 1rem; margin-bottom: 0.5rem;">
                                    <div style="font-weight: 600;">{{ $row['label'] }}</div>
                                    <div style="font-size: 0.875rem; color: var(--muted-foreground);">{{ number_format($row['value']) }}</div>
                                </div>
                                <div style="height: 12px; width: 100%; background: var(--muted); border-radius: 9999px; overflow:hidden; border: 1px solid var(--border);">
                                    <div style="height: 100%; width: {{ $row['pct'] }}%; background: {{ $row['color'] }};"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- Area --}}
                <div data-td-tab-panel="area" style="display:none;">
                    <div style="border: 1px solid var(--border); border-radius: 10px; padding: 1rem; background: var(--muted);">
                        <svg viewBox="0 0 600 180" width="100%" height="180" preserveAspectRatio="none" style="display:block;">
                            <defs>
                                <linearGradient id="td-wave-a" x1="0" y1="0" x2="0" y2="1">
                                    <stop offset="0%" stop-color="{{ $charts['wave_a_color'] }}" stop-opacity="0.55" />
                                    <stop offset="100%" stop-color="{{ $charts['wave_a_color'] }}" stop-opacity="0" />
                                </linearGradient>
                                <linearGradient id="td-wave-b" x1="0" y1="0" x2="0" y2="1">
                                    <stop offset="0%" stop-color="{{ $charts['wave_b_color'] }}" stop-opacity="0.55" />
                                    <stop offset="100%" stop-color="{{ $charts['wave_b_color'] }}" stop-opacity="0" />
                                </linearGradient>
                            </defs>

                            <g opacity="0.35" stroke="currentColor" style="color: var(--muted-foreground);">
                                <path d="M0 150 H600" />
                                <path d="M0 110 H600" />
                                <path d="M0 70 H600" />
                                <path d="M0 30 H600" />
                            </g>

                            <g stroke="currentColor" stroke-dasharray="4 6" opacity="0.25" style="color: var(--muted-foreground);">
                                <path d="M 100 20 V 170" />
                                <path d="M 200 20 V 170" />
                                <path d="M 300 20 V 170" />
                                <path d="M 400 20 V 170" />
                                <path d="M 500 20 V 170" />
                            </g>

                            <path d="{{ $charts['wave_a_area_path'] }}" fill="url(#td-wave-a)"></path>
                            <path d="{{ $charts['wave_b_area_path'] }}" fill="url(#td-wave-b)"></path>

                            <path d="{{ $charts['wave_a_line_path'] }}" fill="none" stroke="{{ $charts['wave_a_color'] }}" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"></path>
                            <path d="{{ $charts['wave_b_line_path'] }}" fill="none" stroke="{{ $charts['wave_b_color'] }}" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"></path>

                            @foreach($charts['wave_points'] as $p)
                                @php($labelWidth = 12 + (strlen($p['label']) * 7))
                                @php($labelX = max(6, min(600 - $labelWidth - 6, $p['x'] - ($labelWidth / 2))))
                                @php($labelY = max(8, $p['y'] - 28))
                                <g>
                                    <rect x="{{ $labelX }}" y="{{ $labelY }}" width="{{ $labelWidth }}" height="20" rx="10" fill="var(--foreground)" opacity="0.85"></rect>
                                    <text x="{{ $labelX + ($labelWidth / 2) }}" y="{{ $labelY + 14 }}" text-anchor="middle" font-size="11" font-weight="600" fill="var(--background)">{{ $p['label'] }}</text>
                                    <circle cx="{{ $p['x'] }}" cy="{{ $p['y'] }}" r="4.2" fill="{{ $charts['wave_b_color'] }}" stroke="var(--background)" stroke-width="2"></circle>
                                </g>
                            @endforeach
                        </svg>
                        <div style="display:flex; justify-content: space-between; margin-top: 0.5rem; font-size: 0.8125rem; color: var(--muted-foreground);">
                            <span>{{ $charts['wave_range_label_left'] }}</span>
                            <span>{{ $charts['wave_range_label_right'] }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- More charts --}}
<div class="grid-2" style="margin-bottom: 1.5rem;">
    {{-- Bar chart --}}
    <div class="card">
        <div class="card-header">
            <h3 class="card-title" style="font-size: 1.0625rem;">Weekly Sales</h3>
            <span class="badge badge-secondary">Bar chart</span>
        </div>
        <div class="card-body">
            <div style="display:flex; align-items: baseline; justify-content: space-between; gap: 1rem; margin-bottom: 1rem;">
                <div>
                    <div style="font-size: 0.875rem; color: var(--muted-foreground);">Total</div>
                    <div style="font-size: 1.75rem; font-weight: 700; letter-spacing: -0.02em;">{{ number_format(collect($charts['weekly_bars'])->sum('value')) }}</div>
                </div>
                <div class="badge-list">
                    <span class="badge badge-primary">{{ count($charts['weekly_bars']) }} days</span>
                </div>
            </div>

            <div style="border: 1px solid var(--border); border-radius: 10px; padding: 1rem; background: var(--muted);">
                <div style="display:grid; grid-template-columns: repeat(7, 1fr); gap: 0.625rem; align-items: end; height: 180px;">
                    @foreach($charts['weekly_bars'] as $bar)
                        <div style="display:flex; flex-direction: column; gap: 0.5rem; align-items: stretch;">
                            <div title="{{ $bar['label'] }}: {{ $bar['value'] }}" style="height: 150px; display:flex; align-items:flex-end; position: relative;">
                                <div style="position:absolute; top: 0; left: 0; right: 0; text-align:center; font-size: 0.75rem; color: var(--muted-foreground);">{{ number_format($bar['value']) }}</div>
                                <div style="width: 100%; height: {{ $bar['pct'] }}%; background: var(--foreground); border-radius: 8px; border: 1px solid var(--border);"></div>
                            </div>
                            <div style="font-size: 0.8125rem; color: var(--muted-foreground); text-align:center;">{{ $bar['label'] }}</div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    {{-- Stacked bars (distribution) --}}
    <div class="card">
        <div class="card-header">
            <h3 class="card-title" style="font-size: 1.0625rem;">Channel Mix</h3>
            <span class="badge badge-secondary">Stacked bars</span>
        </div>
        <div class="card-body">
            <div style="display:flex; flex-direction: column; gap: 1rem;">
                @foreach($charts['channel_mix'] as $row)
                    <div>
                        <div style="display:flex; align-items:center; justify-content: space-between; gap: 1rem; margin-bottom: 0.5rem;">
                            <div style="font-weight: 600;">{{ $row['label'] }}</div>
                            <span class="badge badge-primary">{{ collect($row['segments'])->sum('pct') }}%</span>
                        </div>
                        <div style="height: 12px; width: 100%; background: var(--muted); border-radius: 9999px; overflow:hidden; border: 1px solid var(--border); display:flex;">
                            @foreach($row['segments'] as $segment)
                                <div title="{{ $segment['label'] }}: {{ $segment['pct'] }}%" style="height: 100%; width: {{ $segment['pct'] }}%; background: {{ $segment['color'] }};"></div>
                            @endforeach
                        </div>
                        <div style="display:flex; gap: 0.75rem; flex-wrap: wrap; margin-top: 0.5rem;">
                            @foreach($row['segments'] as $segment)
                                <div style="display:flex; align-items:center; gap: 0.5rem; font-size: 0.8125rem; color: var(--muted-foreground);">
                                    <span style="width: 10px; height: 10px; border-radius: 9999px; background: {{ $segment['color'] }}; display:inline-block;"></span>
                                    <span>{{ $segment['label'] }} ({{ $segment['pct'] }}%)</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<div class="grid-2" style="margin-bottom: 1.5rem;">
    {{-- Progress bars --}}
    <div class="card">
        <div class="card-header">
            <h3 class="card-title" style="font-size: 1.0625rem;">Project Progress</h3>
            <span class="badge badge-secondary">Progress bars</span>
        </div>
        <div class="card-body">
            <div style="display:flex; flex-direction: column; gap: 1rem;">
                @foreach($progress as $item)
                    <div>
                        <div style="display:flex; align-items: center; justify-content: space-between; gap: 1rem; margin-bottom: 0.5rem;">
                            <div style="min-width:0;">
                                <div style="font-size: 0.9375rem; font-weight: 600; color: var(--foreground); white-space: nowrap; overflow:hidden; text-overflow: ellipsis;">{{ $item['title'] }}</div>
                                <div style="font-size: 0.8125rem; color: var(--muted-foreground);">{{ $item['subtitle'] }}</div>
                            </div>
                            <span class="badge {{ $item['badge_class'] }}">{{ $item['badge_text'] }}</span>
                        </div>

                        <div style="height: 10px; width: 100%; background: var(--muted); border-radius: 9999px; overflow:hidden; border: 1px solid var(--border);">
                            <div style="height: 100%; width: {{ $item['pct'] }}%; background: {{ $item['bar_color'] }};"></div>
                        </div>
                        <div style="display:flex; justify-content: space-between; margin-top: 0.375rem; font-size: 0.8125rem; color: var(--muted-foreground);">
                            <span>{{ $item['pct'] }}% complete</span>
                            <span>{{ $item['meta'] }}</span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Info cards / quick insights --}}
    <div class="card">
        <div class="card-header">
            <h3 class="card-title" style="font-size: 1.0625rem;">Info Cards</h3>
            <span class="badge badge-secondary">Layouts</span>
        </div>
        <div class="card-body">
            <div style="display: grid; grid-template-columns: 1fr; gap: 1rem;">
                @foreach($infoCards as $c)
                    <div style="border: 1px solid var(--border); border-radius: 10px; padding: 1rem; background: var(--background);">
                        <div style="display:flex; align-items:flex-start; justify-content: space-between; gap: 1rem;">
                            <div style="min-width: 0;">
                                <div style="font-size: 0.875rem; color: var(--muted-foreground);">{{ $c['eyebrow'] }}</div>
                                <div style="font-size: 1.0625rem; font-weight: 700; letter-spacing: -0.01em; color: var(--foreground);">{{ $c['title'] }}</div>
                            </div>
                            <span class="badge {{ $c['badge_class'] }}">{{ $c['badge'] }}</span>
                        </div>
                        <div style="margin-top: 0.5rem; font-size: 0.9375rem; color: var(--muted-foreground);">{{ $c['description'] }}</div>
                        <div style="display:flex; gap: 0.5rem; flex-wrap: wrap; margin-top: 0.875rem;">
                            <a href="#" class="btn btn-ghost btn-sm" onclick="return false;">Secondary action</a>
                            <a href="#" class="btn btn-secondary btn-sm" onclick="return false;">View details</a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

{{-- Tabs + Dropdown + Forms + Rich text --}}
<div class="grid-2" style="margin-bottom: 1.5rem;">
    {{-- Tabbed section --}}
    <div class="card">
        <div class="card-header">
            <h3 class="card-title" style="font-size: 1.0625rem;">Tabbed Section</h3>
            <span class="badge badge-secondary">Tabs</span>
        </div>
        <div class="card-body">
            <div data-td-tabset>
                <div class="tabs">
                    <a href="#" class="tab-link active" data-td-tab="overview" onclick="return false;">Overview</a>
                    <a href="#" class="tab-link" data-td-tab="forms" onclick="return false;">Forms</a>
                    <a href="#" class="tab-link" data-td-tab="notes" onclick="return false;">Notes</a>
                </div>

                <div data-td-tab-panel="overview">
                    <div style="display:flex; flex-direction: column; gap: 0.75rem;">
                        <div style="font-size: 0.9375rem; color: var(--muted-foreground);">
                            Use tabs for settings pages, profile sections, or dashboards with multiple views.
                        </div>
                        <div class="badge-list">
                            <span class="badge badge-primary">Copy-ready</span>
                            <span class="badge badge-success">No extra CSS needed</span>
                            <span class="badge badge-secondary">Small JS snippet</span>
                        </div>
                        <div style="border: 1px solid var(--border); border-radius: 10px; padding: 1rem; background: var(--muted);">
                            <div style="display:flex; justify-content: space-between; gap: 1rem;">
                                <div>
                                    <div style="font-size: 0.875rem; color: var(--muted-foreground);">Deployments</div>
                                    <div style="font-size: 1.5rem; font-weight: 700;">12</div>
                                </div>
                                <div>
                                    <div style="font-size: 0.875rem; color: var(--muted-foreground);">Incidents</div>
                                    <div style="font-size: 1.5rem; font-weight: 700;">0</div>
                                </div>
                                <div>
                                    <div style="font-size: 0.875rem; color: var(--muted-foreground);">SLA</div>
                                    <div style="font-size: 1.5rem; font-weight: 700;">99.9%</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div data-td-tab-panel="forms" style="display:none;">
                    <div style="display:flex; flex-direction: column; gap: 0.75rem;">
                        <div style="font-size: 0.9375rem; color: var(--muted-foreground);">Quick form layout inside a tab.</div>
                        <div class="form-row">
                            <div class="form-group" style="margin-bottom: 0;">
                                <label class="form-label">Project name</label>
                                <input class="form-input" type="text" placeholder="Acme App" value="Acme App" />
                            </div>
                            <div class="form-group" style="margin-bottom: 0;">
                                <label class="form-label">Environment</label>
                                <select class="form-select">
                                    <option>Production</option>
                                    <option>Staging</option>
                                    <option>Development</option>
                                </select>
                            </div>
                        </div>
                        <div style="display:flex; gap: 0.5rem;">
                            <a href="#" class="btn btn-primary btn-sm" onclick="return false;">Save</a>
                            <a href="#" class="btn btn-ghost btn-sm" onclick="return false;">Cancel</a>
                        </div>
                    </div>
                </div>

                <div data-td-tab-panel="notes" style="display:none;">
                    <div style="font-size: 0.9375rem; color: var(--muted-foreground); margin-bottom: 0.75rem;">
                        Rich text preview example (useful for announcements / release notes).
                    </div>
                    <div style="border: 1px solid var(--border); border-radius: 10px; padding: 1rem; background: var(--background);">
                        <div style="font-weight: 700; font-size: 1.0625rem; margin-bottom: 0.5rem;">Release notes</div>
                        <div style="color: var(--muted-foreground); font-size: 0.9375rem; margin-bottom: 0.75rem;">Today we shipped a few improvements:</div>
                        <ul style="padding-left: 1.25rem; margin-bottom: 0.75rem; color: var(--foreground);">
                            <li><strong>Faster</strong> dashboard load time</li>
                            <li>Improved role management UX</li>
                            <li>Better error messages</li>
                        </ul>
                        <blockquote style="margin: 0; padding: 0.75rem 1rem; border-left: 3px solid var(--border); background: var(--muted); border-radius: 8px; color: var(--muted-foreground);">
                            Tip: keep announcements short and link to details.
                        </blockquote>
                        <div style="margin-top: 0.75rem; font-size: 0.9375rem;">
                            <a href="#" onclick="return false;" style="color: var(--foreground); text-decoration: underline;">View full changelog</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Dropdown menu --}}
    <div class="card">
        <div class="card-header">
            <h3 class="card-title" style="font-size: 1.0625rem;">Dropdown Menu</h3>
            <span class="badge badge-secondary">Menu</span>
        </div>
        <div class="card-body">
            <div style="display:flex; align-items:center; justify-content: space-between; gap: 1rem; margin-bottom: 1rem;">
                <div style="font-size: 0.9375rem; color: var(--muted-foreground);">Reusable dropdown using existing styles.</div>
                <div class="user-dropdown" data-td-dropdown>
                    <button type="button" class="user-dropdown-btn" data-td-dropdown-btn>
                        <div class="user-avatar" style="width: 28px; height: 28px; font-size: 0.75rem;">A</div>
                        <div class="user-info" style="line-height: 1.2;">
                            <div class="user-name" style="font-size: 0.875rem;">Actions</div>
                            <div class="user-role">Quick menu</div>
                        </div>
                        <svg class="user-dropdown-arrow" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div class="user-dropdown-menu">
                        <a href="#" class="dropdown-item" onclick="return false;">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 5v14m7-7H5"/></svg>
                            Create item
                        </a>
                        <a href="#" class="dropdown-item" onclick="return false;">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/></svg>
                            View list
                        </a>
                        <div class="dropdown-divider"></div>
                        <a href="#" class="dropdown-item dropdown-item-danger" onclick="return false;">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>
                            Destructive action
                        </a>
                    </div>
                </div>
            </div>

            <div style="border: 1px solid var(--border); border-radius: 10px; padding: 1rem; background: var(--muted);">
                <div style="font-size: 0.9375rem; font-weight: 600; margin-bottom: 0.5rem;">Common uses</div>
                <div class="badge-list">
                    <span class="badge badge-primary">Row actions</span>
                    <span class="badge badge-primary">Bulk actions</span>
                    <span class="badge badge-primary">Context menus</span>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="grid-2" style="margin-bottom: 1.5rem;">
    {{-- Form components (including switch) --}}
    <div class="card">
        <div class="card-header">
            <h3 class="card-title" style="font-size: 1.0625rem;">Form Components</h3>
            <span class="badge badge-secondary">Inputs</span>
        </div>
        <div class="card-body">
            <div class="form-group">
                <label class="form-label">Email <span class="form-label-optional">(required)</span></label>
                <input class="form-input" type="email" placeholder="name@example.com" value="name@example.com" />
                <div class="form-hint">Used for notifications and sign-in.</div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Plan</label>
                    <select class="form-select">
                        <option>Starter</option>
                        <option selected>Pro</option>
                        <option>Enterprise</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">API Key</label>
                    <input class="form-input is-invalid" type="text" value="sk_live_••••" />
                    <div class="form-error">Invalid key format.</div>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Notes</label>
                <textarea class="form-textarea" placeholder="Add a short note…">Keep this short. Link to details.</textarea>
            </div>

            <div class="form-group" style="margin-bottom: 1rem;">
                <label class="form-label">Preferences</label>
                <div style="display:flex; flex-direction: column; gap: 0.75rem;">
                    <label class="toggle-label">
                        <input class="toggle-input" type="checkbox" checked>
                        <span class="toggle-slider"></span>
                        <span class="toggle-text">Enable notifications</span>
                    </label>
                    <label class="toggle-label">
                        <input class="toggle-input" type="checkbox">
                        <span class="toggle-slider"></span>
                        <span class="toggle-text">Maintenance mode</span>
                    </label>
                </div>
            </div>

            <div class="form-row">
                <div>
                    <div class="form-label" style="margin-bottom: 0.5rem;">Checkboxes</div>
                    <div class="checkbox-list">
                        <label class="checkbox-item">
                            <input type="checkbox" class="checkbox-input" checked>
                            <div>
                                <div style="font-size: 0.9375rem; font-weight: 600;">Read</div>
                                <div style="font-size: 0.8125rem; color: var(--muted-foreground);">Can view resources</div>
                            </div>
                        </label>
                        <label class="checkbox-item">
                            <input type="checkbox" class="checkbox-input">
                            <div>
                                <div style="font-size: 0.9375rem; font-weight: 600;">Write</div>
                                <div style="font-size: 0.8125rem; color: var(--muted-foreground);">Can edit resources</div>
                            </div>
                        </label>
                    </div>
                </div>
                <div>
                    <div class="form-label" style="margin-bottom: 0.5rem;">Radio</div>
                    <div style="display:flex; flex-direction: column; gap: 0.75rem;">
                        <label style="display:flex; align-items:center; gap: 0.625rem;">
                            <input type="radio" name="demo_radio" class="radio-input" checked>
                            <span style="font-size: 0.9375rem;">Daily summary</span>
                        </label>
                        <label style="display:flex; align-items:center; gap: 0.625rem;">
                            <input type="radio" name="demo_radio" class="radio-input">
                            <span style="font-size: 0.9375rem;">Weekly summary</span>
                        </label>
                    </div>
                </div>
            </div>

            <div style="display:flex; gap: 0.5rem; margin-top: 1rem; flex-wrap: wrap;">
                <a href="#" class="btn btn-primary btn-sm" onclick="return false;">Submit</a>
                <a href="#" class="btn btn-secondary btn-sm" onclick="return false;">Secondary</a>
                <a href="#" class="btn btn-ghost btn-sm" onclick="return false;">Ghost</a>
            </div>
        </div>
    </div>

    {{-- Rich text (Quill) + sparklines --}}
    <div class="card">
        <div class="card-header">
            <h3 class="card-title" style="font-size: 1.0625rem;">Rich Text + Sparklines</h3>
            <span class="badge badge-secondary">Content</span>
        </div>
        <div class="card-body">
            <div style="border: 1px solid var(--border); border-radius: 10px; padding: 1rem; background: var(--background); margin-bottom: 1rem;">
                <div style="display:flex; align-items:center; justify-content: space-between; gap: 1rem; margin-bottom: 0.75rem;">
                    <div style="font-size: 0.875rem; color: var(--muted-foreground);">Rich text editor</div>
                    <span class="badge badge-primary">Quill.js</span>
                </div>
                <div id="td-quill-editor" style="min-height: 160px; background: var(--background);"></div>
                <script type="application/json" id="td-quill-initial">@json($quillInitialHtml)</script>
                <div style="margin-top: 0.75rem; padding: 0.75rem 1rem; border-radius: 8px; background: var(--muted); border: 1px solid var(--border);">
                    <div style="font-size: 0.8125rem; color: var(--muted-foreground); margin-bottom: 0.5rem;">Generated HTML</div>
                    <textarea id="td-quill-html" class="form-textarea" readonly style="min-height: 120px; font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, 'Liberation Mono', 'Courier New', monospace;"></textarea>
                </div>
            </div>

            <div style="display:flex; flex-direction: column; gap: 0.75rem;">
                @foreach($charts['sparklines'] as $s)
                    <div style="display:flex; align-items:center; justify-content: space-between; gap: 1rem; padding: 0.75rem 1rem; border: 1px solid var(--border); border-radius: 10px; background: var(--background);">
                        <div style="min-width:0;">
                            <div style="display:flex; align-items:center; gap: 0.5rem; flex-wrap: wrap;">
                                <div style="font-weight: 700;">{{ $s['label'] }}</div>
                                <span class="badge {{ $s['badge_class'] }}">{{ $s['badge_text'] }}</span>
                            </div>
                            <div style="font-size: 0.9375rem; color: var(--muted-foreground);">{{ $s['value'] }}</div>
                        </div>
                        <svg viewBox="0 0 162 24" width="162" height="24" preserveAspectRatio="none" style="display:block; color: var(--foreground); opacity: 0.9;">
                            <path d="{{ $s['path'] }}" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                        </svg>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

{{-- Tables --}}
<div class="card">
    <div class="card-header">
        <h3 class="card-title" style="font-size: 1.0625rem;">Recent Activity</h3>
        <span class="badge badge-secondary">Table + badges</span>
    </div>
    <div class="card-body" style="padding: 0;">
        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th>Event</th>
                        <th>Actor</th>
                        <th>Status</th>
                        <th style="text-align:right;">When</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($activity as $row)
                        <tr>
                            <td>
                                <div style="font-weight: 600;">{{ $row['title'] }}</div>
                                <div style="font-size: 0.8125rem; color: var(--muted-foreground);">{{ $row['subtitle'] }}</div>
                            </td>
                            <td>
                                <div class="user-cell" style="gap: 0.625rem; text-decoration: none;">
                                    <div class="user-cell-avatar">{{ strtoupper(substr($row['actor'], 0, 1)) }}</div>
                                    <div class="user-cell-info">
                                        <div class="user-cell-name" style="font-size: 0.9375rem;">{{ $row['actor'] }}</div>
                                        <div class="user-cell-email" style="font-size: 0.8125rem;">{{ $row['actor_meta'] }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge {{ $row['status_badge_class'] }}">{{ $row['status'] }}</span>
                            </td>
                            <td style="text-align:right; color: var(--muted-foreground);">{{ $row['when'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/quill@1.3.7/dist/quill.min.js"></script>
<script>
    (function () {
        function initQuill() {
            var editorEl = document.getElementById('td-quill-editor');
            if (!editorEl || typeof window.Quill === 'undefined') return;

            var quill = new window.Quill(editorEl, {
                theme: 'snow',
                modules: {
                    toolbar: [
                        ['bold', 'italic', 'underline'],
                        [{ 'list': 'ordered' }, { 'list': 'bullet' }],
                        ['link'],
                        ['clean']
                    ]
                }
            });

            // Seed initial content
            try {
                var initialNode = document.getElementById('td-quill-initial');
                var initialHtml = '';
                if (initialNode && initialNode.textContent) {
                    initialHtml = JSON.parse(initialNode.textContent);
                }
                if (initialHtml) {
                    quill.clipboard.dangerouslyPasteHTML(initialHtml);
                }
            } catch (e) {
                // no-op
            }

            var output = document.getElementById('td-quill-html');
            function sync() {
                if (!output) return;
                output.value = quill.root.innerHTML;
            }
            quill.on('text-change', sync);
            sync();
        }

        function initTabs() {
            document.querySelectorAll('[data-td-tabset]').forEach(function (tabset) {
                var links = tabset.querySelectorAll('[data-td-tab]');
                var panels = tabset.querySelectorAll('[data-td-tab-panel]');

                function activate(name) {
                    links.forEach(function (link) {
                        link.classList.toggle('active', link.getAttribute('data-td-tab') === name);
                    });
                    panels.forEach(function (panel) {
                        panel.style.display = panel.getAttribute('data-td-tab-panel') === name ? '' : 'none';
                    });
                }

                links.forEach(function (link) {
                    link.addEventListener('click', function (e) {
                        e.preventDefault();
                        activate(link.getAttribute('data-td-tab'));
                    });
                });

                var initial = (links[0] && links[0].getAttribute('data-td-tab')) || 'overview';
                activate(initial);
            });
        }

        function initDropdowns() {
            document.querySelectorAll('[data-td-dropdown]').forEach(function (dropdown) {
                var btn = dropdown.querySelector('[data-td-dropdown-btn]');
                if (!btn) return;

                btn.addEventListener('click', function (e) {
                    e.preventDefault();
                    e.stopPropagation();

                    document.querySelectorAll('[data-td-dropdown].active').forEach(function (open) {
                        if (open !== dropdown) open.classList.remove('active');
                    });
                    dropdown.classList.toggle('active');
                });
            });

            document.addEventListener('click', function (event) {
                document.querySelectorAll('[data-td-dropdown].active').forEach(function (open) {
                    if (!open.contains(event.target)) {
                        open.classList.remove('active');
                    }
                });
            });
        }

        document.addEventListener('DOMContentLoaded', function () {
            initTabs();
            initDropdowns();
            initQuill();
        });
    })();
</script>
@endpush
@endsection
