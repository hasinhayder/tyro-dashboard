@extends($isAdmin ? 'tyro-dashboard::layouts.admin' : 'tyro-dashboard::layouts.user')

@section('title', 'Example Support')

@section('breadcrumb')
<a href="{{ route('tyro-dashboard.index') }}">Dashboard</a>
<span class="breadcrumb-separator">/</span>
<span>Examples</span>
<span class="breadcrumb-separator">/</span>
<span>Support</span>
@endsection

@section('content')
@php
    $tickets = [
        ['id' => '#1932', 'subject' => 'Unable to reset password', 'priority' => 'High', 'priority_badge' => 'badge-danger', 'status' => 'Open', 'status_badge' => 'badge-warning', 'assignee' => 'Jane Doe', 'updated' => '8m ago'],
        ['id' => '#1928', 'subject' => 'Billing invoice mismatch', 'priority' => 'Medium', 'priority_badge' => 'badge-warning', 'status' => 'In Progress', 'status_badge' => 'badge-primary', 'assignee' => 'Admin', 'updated' => '1h ago'],
        ['id' => '#1919', 'subject' => 'Feature request: exports', 'priority' => 'Low', 'priority_badge' => 'badge-secondary', 'status' => 'Triaged', 'status_badge' => 'badge-primary', 'assignee' => 'System', 'updated' => 'Yesterday'],
        ['id' => '#1907', 'subject' => 'Webhook failing intermittently', 'priority' => 'High', 'priority_badge' => 'badge-danger', 'status' => 'Waiting', 'status_badge' => 'badge-secondary', 'assignee' => 'John Smith', 'updated' => '2d ago'],
    ];
@endphp

<div class="page-header">
    <div class="page-header-row">
        <div>
            <h1 class="page-title">Example Support</h1>
            <p class="page-description" style="font-size: 1rem;">A ticketing layout with filters, status badges, and table actions.</p>
        </div>
        <div style="display:flex; gap: 0.5rem; flex-wrap: wrap;">
            <a href="#" class="btn btn-primary btn-sm" onclick="return false;">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 5v14m7-7H5"/></svg>
                New ticket
            </a>
            <a href="#" class="btn btn-secondary btn-sm" onclick="return false;">Assign</a>
        </div>
    </div>
</div>

<div class="grid-2" style="margin-bottom: 1.5rem;">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title" style="font-size: 1.0625rem;">Filters</h3>
            <span class="badge badge-secondary">Form</span>
        </div>
        <div class="card-body">
            <div class="form-row">
                <div class="form-group" style="margin-bottom: 0;">
                    <label class="form-label">Status</label>
                    <select class="form-select">
                        <option selected>All</option>
                        <option>Open</option>
                        <option>In Progress</option>
                        <option>Waiting</option>
                        <option>Closed</option>
                    </select>
                </div>
                <div class="form-group" style="margin-bottom: 0;">
                    <label class="form-label">Priority</label>
                    <select class="form-select">
                        <option selected>All</option>
                        <option>Low</option>
                        <option>Medium</option>
                        <option>High</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title" style="font-size: 1.0625rem;">SLA</h3>
            <span class="badge badge-secondary">Progress</span>
        </div>
        <div class="card-body">
            <div style="display:flex; justify-content: space-between; margin-bottom: 0.5rem;">
                <span style="color: var(--muted-foreground); font-size: 0.9375rem;">On-time resolution</span>
                <strong>92%</strong>
            </div>
            <div style="height: 10px; width: 100%; background: var(--muted); border-radius: 9999px; overflow:hidden; border: 1px solid var(--border);">
                <div style="height: 100%; width: 92%; background: var(--success);"></div>
            </div>
            <div style="margin-top: 0.75rem; color: var(--muted-foreground); font-size: 0.9375rem;">Keep response times consistent to maintain SLA.</div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h3 class="card-title" style="font-size: 1.0625rem;">Tickets</h3>
        <span class="badge badge-secondary">Table</span>
    </div>
    <div class="card-body" style="padding: 0;">
        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Subject</th>
                        <th>Priority</th>
                        <th>Status</th>
                        <th>Assignee</th>
                        <th style="text-align:right;">Updated</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($tickets as $t)
                        <tr>
                            <td style="font-weight: 600;">{{ $t['id'] }}</td>
                            <td>
                                <div style="font-weight: 600;">{{ $t['subject'] }}</div>
                                <div style="font-size: 0.8125rem; color: var(--muted-foreground);">Customer-facing issue</div>
                            </td>
                            <td><span class="badge {{ $t['priority_badge'] }}">{{ $t['priority'] }}</span></td>
                            <td><span class="badge {{ $t['status_badge'] }}">{{ $t['status'] }}</span></td>
                            <td>{{ $t['assignee'] }}</td>
                            <td style="text-align:right; color: var(--muted-foreground);">{{ $t['updated'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
