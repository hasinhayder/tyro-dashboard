@extends($isAdmin ? 'tyro-dashboard::layouts.admin' : 'tyro-dashboard::layouts.user')

@section('title', 'Example CRM')

@section('breadcrumb')
<a href="{{ route('tyro-dashboard.index') }}">Dashboard</a>
<span class="breadcrumb-separator">/</span>
<span>Examples</span>
<span class="breadcrumb-separator">/</span>
<span>CRM</span>
@endsection

@section('content')
@php
    $pipeline = [
        ['stage' => 'New', 'count' => 34, 'badge' => 'badge-primary'],
        ['stage' => 'Qualified', 'count' => 18, 'badge' => 'badge-success'],
        ['stage' => 'Proposal', 'count' => 9, 'badge' => 'badge-warning'],
        ['stage' => 'Closed', 'count' => 6, 'badge' => 'badge-secondary'],
    ];

    $leads = [
        ['name' => 'Acme Corp', 'owner' => 'Jane Doe', 'status' => 'Qualified', 'status_badge' => 'badge-success', 'value' => '$12,500', 'updated' => '10m ago'],
        ['name' => 'Northwind', 'owner' => 'Admin', 'status' => 'Proposal', 'status_badge' => 'badge-warning', 'value' => '$8,300', 'updated' => '1h ago'],
        ['name' => 'Globex', 'owner' => 'John Smith', 'status' => 'New', 'status_badge' => 'badge-primary', 'value' => '$3,200', 'updated' => '3h ago'],
        ['name' => 'Initech', 'owner' => 'System', 'status' => 'Closed', 'status_badge' => 'badge-secondary', 'value' => '$22,900', 'updated' => 'Yesterday'],
    ];
@endphp

<div class="page-header">
    <div class="page-header-row">
        <div>
            <h1 class="page-title">Example CRM</h1>
            <p class="page-description" style="font-size: 1rem;">A simple CRM dashboard layout: pipeline summary + deals table.</p>
        </div>
        <div style="display:flex; gap: 0.5rem; flex-wrap: wrap;">
            <a href="#" class="btn btn-primary btn-sm" onclick="return false;">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 5v14m7-7H5"/></svg>
                Add lead
            </a>
            <a href="#" class="btn btn-secondary btn-sm" onclick="return false;">Import</a>
        </div>
    </div>
</div>

<div class="stats-grid">
    @foreach($pipeline as $p)
        <div class="stat-card">
            <div class="stat-icon stat-icon-primary">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 4h16v6H4z"/><path stroke-linecap="round" stroke-linejoin="round" d="M4 14h10v6H4z"/><path stroke-linecap="round" stroke-linejoin="round" d="M16 14h4v6h-4z"/></svg>
            </div>
            <div>
                <div class="stat-label" style="font-size: 0.9375rem;">{{ $p['stage'] }}</div>
                <div class="stat-value">{{ $p['count'] }}</div>
                <div style="margin-top: 0.5rem;">
                    <span class="badge {{ $p['badge'] }}">Stage</span>
                </div>
            </div>
        </div>
    @endforeach
</div>

<div class="grid-2" style="margin-bottom: 1.5rem;">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title" style="font-size: 1.0625rem;">Win Rate</h3>
            <span class="badge badge-secondary">Progress</span>
        </div>
        <div class="card-body">
            <div style="display:flex; justify-content: space-between; margin-bottom: 0.5rem;">
                <span style="color: var(--muted-foreground); font-size: 0.9375rem;">This month</span>
                <strong>38%</strong>
            </div>
            <div style="height: 10px; width: 100%; background: var(--muted); border-radius: 9999px; overflow:hidden; border: 1px solid var(--border);">
                <div style="height: 100%; width: 38%; background: var(--success);"></div>
            </div>
            <div style="margin-top: 0.75rem; color: var(--muted-foreground); font-size: 0.9375rem;">Improve win rate by prioritizing qualified leads.</div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title" style="font-size: 1.0625rem;">Quick Actions</h3>
            <span class="badge badge-secondary">Buttons</span>
        </div>
        <div class="card-body">
            <div style="display:flex; gap: 0.5rem; flex-wrap: wrap;">
                <a href="#" class="btn btn-secondary btn-sm" onclick="return false;">Assign owner</a>
                <a href="#" class="btn btn-secondary btn-sm" onclick="return false;">Schedule call</a>
                <a href="#" class="btn btn-secondary btn-sm" onclick="return false;">Create task</a>
                <a href="#" class="btn btn-ghost btn-sm" onclick="return false;">View settings</a>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h3 class="card-title" style="font-size: 1.0625rem;">Deals</h3>
        <span class="badge badge-secondary">Table</span>
    </div>
    <div class="card-body" style="padding: 0;">
        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th>Account</th>
                        <th>Owner</th>
                        <th>Status</th>
                        <th style="text-align:right;">Value</th>
                        <th style="text-align:right;">Updated</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($leads as $lead)
                        <tr>
                            <td style="font-weight: 600;">{{ $lead['name'] }}</td>
                            <td>{{ $lead['owner'] }}</td>
                            <td><span class="badge {{ $lead['status_badge'] }}">{{ $lead['status'] }}</span></td>
                            <td style="text-align:right; font-weight: 600;">{{ $lead['value'] }}</td>
                            <td style="text-align:right; color: var(--muted-foreground);">{{ $lead['updated'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
