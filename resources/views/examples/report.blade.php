@extends($isAdmin ? 'tyro-dashboard::layouts.admin' : 'tyro-dashboard::layouts.user')

@section('title', 'Example Report')

@section('breadcrumb')
<a href="{{ route('tyro-dashboard.index') }}">Dashboard</a>
<span class="breadcrumb-separator">/</span>
<span>Examples</span>
<span class="breadcrumb-separator">/</span>
<span>Report</span>
@endsection

@section('content')
@php
    $summary = [
        ['label' => 'Sessions', 'value' => '128,430', 'icon_class' => 'stat-icon-primary'],
        ['label' => 'Conversions', 'value' => '3.8%', 'icon_class' => 'stat-icon-success'],
        ['label' => 'Refunds', 'value' => '0.6%', 'icon_class' => 'stat-icon-warning'],
        ['label' => 'Errors', 'value' => '0.18%', 'icon_class' => 'stat-icon-danger'],
    ];

    $rows = [
        ['metric' => 'Checkout started', 'count' => '4,210', 'delta' => '+4.1%', 'badge' => 'badge-success'],
        ['metric' => 'Payment failed', 'count' => '183', 'delta' => '+0.6%', 'badge' => 'badge-warning'],
        ['metric' => 'Subscription created', 'count' => '512', 'delta' => '+2.2%', 'badge' => 'badge-success'],
        ['metric' => 'Support contacted', 'count' => '74', 'delta' => '-1.8%', 'badge' => 'badge-primary'],
    ];
@endphp

<div class="page-header">
    <div class="page-header-row">
        <div>
            <h1 class="page-title">Example Report</h1>
            <p class="page-description" style="font-size: 1rem;">A report-style page with filters, KPI row, and a chart block.</p>
        </div>
        <div style="display:flex; gap: 0.5rem; flex-wrap: wrap;">
            <a href="#" class="btn btn-secondary btn-sm" onclick="return false;">Export CSV</a>
            <a href="#" class="btn btn-primary btn-sm" onclick="return false;">Share</a>
        </div>
    </div>
</div>

<div class="card" style="margin-bottom: 1.5rem;">
    <div class="card-header">
        <h3 class="card-title" style="font-size: 1.0625rem;">Filters</h3>
        <span class="badge badge-secondary">Form</span>
    </div>
    <div class="card-body">
        <div class="form-row">
            <div class="form-group" style="margin-bottom: 0;">
                <label class="form-label">Range</label>
                <select class="form-select">
                    <option>Last 7 days</option>
                    <option selected>Last 30 days</option>
                    <option>Last 90 days</option>
                </select>
            </div>
            <div class="form-group" style="margin-bottom: 0;">
                <label class="form-label">Segment</label>
                <select class="form-select">
                    <option selected>All users</option>
                    <option>New users</option>
                    <option>Returning users</option>
                </select>
            </div>
            <div class="form-group" style="margin-bottom: 0;">
                <label class="form-label">Group by</label>
                <select class="form-select">
                    <option selected>Day</option>
                    <option>Week</option>
                    <option>Month</option>
                </select>
            </div>
        </div>
    </div>
</div>

<div class="stats-grid">
    @foreach($summary as $s)
        <div class="stat-card">
            <div class="stat-icon {{ $s['icon_class'] }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 19V5"/><path stroke-linecap="round" stroke-linejoin="round" d="M8 19V9"/><path stroke-linecap="round" stroke-linejoin="round" d="M12 19V13"/><path stroke-linecap="round" stroke-linejoin="round" d="M16 19V7"/><path stroke-linecap="round" stroke-linejoin="round" d="M20 19V11"/></svg>
            </div>
            <div>
                <div class="stat-label" style="font-size: 0.9375rem;">{{ $s['label'] }}</div>
                <div class="stat-value">{{ $s['value'] }}</div>
            </div>
        </div>
    @endforeach
</div>

<div class="grid-2" style="margin-bottom: 1.5rem;">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title" style="font-size: 1.0625rem;">Trend</h3>
            <span class="badge badge-secondary">SVG chart</span>
        </div>
        <div class="card-body">
            <div style="border: 1px solid var(--border); border-radius: 10px; padding: 1rem; background: var(--muted);">
                <svg viewBox="0 0 600 180" width="100%" height="180" preserveAspectRatio="none" style="display:block; color: var(--foreground);">
                    <g opacity="0.35" stroke="currentColor" style="color: var(--muted-foreground);">
                        <path d="M0 150 H600" />
                        <path d="M0 110 H600" />
                        <path d="M0 70 H600" />
                        <path d="M0 30 H600" />
                    </g>
                    <path d="M 0 130 C 80 125, 120 95, 180 100 C 240 105, 280 80, 340 72 C 400 64, 460 78, 520 52 C 560 35, 580 36, 600 30" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"></path>
                </svg>
            </div>
            <div style="display:flex; justify-content: space-between; margin-top: 0.5rem; font-size: 0.8125rem; color: var(--muted-foreground);">
                <span>Start</span>
                <span>End</span>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title" style="font-size: 1.0625rem;">Highlights</h3>
            <span class="badge badge-secondary">Table</span>
        </div>
        <div class="card-body" style="padding: 0;">
            <div class="table-container">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Metric</th>
                            <th style="text-align:right;">Count</th>
                            <th style="text-align:right;">Delta</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($rows as $r)
                            <tr>
                                <td style="font-weight: 600;">{{ $r['metric'] }}</td>
                                <td style="text-align:right;">{{ $r['count'] }}</td>
                                <td style="text-align:right;"><span class="badge {{ $r['badge'] }}">{{ $r['delta'] }}</span></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
