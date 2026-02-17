@extends('tyro-dashboard::layouts.admin')

@section('title', 'Audit Logs')

@section('breadcrumb')
<a href="{{ route('tyro-dashboard.index') }}">Dashboard</a>
<span class="breadcrumb-separator">/</span>
<span>Audit Logs</span>
@endsection

@section('content')
<div class="page-header">
    <div class="page-header-row">
        <div>
            <h1 class="page-title">Audit Logs</h1>
            <p class="page-description">Track system activity, security events, and administrative changes.</p>
        </div>
        <form action="{{ route('tyro-dashboard.audits.flush', request()->query()) }}" method="POST" id="flush-audits-form">
            @csrf
            @method('DELETE')
            <button type="button" class="btn btn-danger" onclick="event.preventDefault(); showDanger('Flush Audit Logs', 'Are you sure you want to permanently delete all audit log entries?').then(confirmed => { if (confirmed) document.getElementById('flush-audits-form').submit(); });">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 6h18M8 6V4a1 1 0 011-1h6a1 1 0 011 1v2m1 0v14a2 2 0 01-2 2H9a2 2 0 01-2-2V6h10z" />
                </svg>
                Flush All Logs
            </button>
        </form>
    </div>
</div>

<div class="card" style="margin-bottom: 1rem;">
    <div class="card-body">
        <form action="{{ route('tyro-dashboard.audits.index') }}" method="GET">
            <div class="filters-bar" style="flex-wrap: wrap; gap: 0.75rem;">
                <div class="search-box" style="min-width: 260px; flex: 1;">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    <input type="text" name="search" class="form-input" placeholder="Search event, actor, target..." value="{{ $filters['search'] ?? '' }}">
                </div>

                <div class="filter-group">
                    <label class="filter-label">Event:</label>
                    <select name="event" class="form-select" style="min-width: 180px;">
                        <option value="">All Events</option>
                        @foreach($eventOptions as $eventName)
                            <option value="{{ $eventName }}" {{ ($filters['event'] ?? '') === $eventName ? 'selected' : '' }}>{{ $eventName }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="filter-group">
                    <label class="filter-label">Actor:</label>
                    <input type="text" name="actor" class="form-input" style="min-width: 180px;" placeholder="system, id, name, email" value="{{ $filters['actor'] ?? '' }}">
                </div>

                <div class="filter-group">
                    <label class="filter-label">From:</label>
                    <input type="datetime-local" name="from" class="form-input" value="{{ $filters['from'] ?? '' }}">
                </div>

                <div class="filter-group">
                    <label class="filter-label">To:</label>
                    <input type="datetime-local" name="to" class="form-input" value="{{ $filters['to'] ?? '' }}">
                </div>

                <div class="filter-group">
                <button type="submit" class="btn btn-secondary">Filter</button>
                @if(!empty($filters['search']) || !empty($filters['event']) || !empty($filters['actor']) || !empty($filters['from']) || !empty($filters['to']))
                    <a href="{{ route('tyro-dashboard.audits.index') }}" class="btn btn-ghost">Clear</a>
                @endif
            </div>
        </form>
    </div>
</div>

<div class="card">
    @if($logs->count())
        <form action="{{ route('tyro-dashboard.audits.bulk-destroy', request()->query()) }}" method="POST" id="bulk-delete-form">
            @csrf
            <div class="card-body" style="border-bottom: 1px solid var(--border); display: flex; align-items: center; gap: 0.75rem; justify-content: space-between;">
                <div style="display: flex; align-items: center; gap: 0.75rem;">
                    <label style="display: inline-flex; align-items: center; gap: 0.5rem; font-size: 0.875rem; color: var(--muted-foreground);">
                        <input type="checkbox" id="select-all-logs">
                        Select All on page
                    </label>
                </div>
                <div style="display: flex; align-items: center; gap: 0.75rem;">
                    <label class="filter-label" style="margin: 0; white-space: nowrap;">Per Page:</label>
                    <select id="per-page-select" name="per_page" class="form-select" style="min-width: 100px;" onchange="updatePerPage(this.value)">
                        @foreach([20, 50, 100] as $size)
                            <option value="{{ $size }}" {{ (int)($filters['per_page'] ?? 20) === $size ? 'selected' : '' }}>{{ $size }}</option>
                        @endforeach
                    </select>
                    <button type="button" class="btn btn-danger" id="bulk-delete-btn" disabled onclick="event.preventDefault(); submitBulkDelete();">Delete Selected</button>
                </div>
            </div>

            <div class="table-container">
                <table class="table">
                    <thead>
                        <tr>
                            <th style="width: 42px;"></th>
                            <th>Time</th>
                            <th>Event</th>
                            <th>Actor</th>
                            <th>Target</th>
                            <th>Summary</th>
                            <th style="text-align: right;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($logs as $log)
                            <tr>
                                <td>
                                    <input type="checkbox" class="row-checkbox" name="selected_ids[]" value="{{ $log->id }}">
                                </td>
                                <td>
                                    <span style="font-size: 0.875rem; color: var(--foreground);">{{ optional($log->created_at)->format('Y-m-d H:i:s') }}</span>
                                </td>
                                <td>
                                    <code style="padding: 0.25rem 0.5rem; background-color: var(--muted); border-radius: 0.25rem; font-size: 0.8125rem;">{{ $log->event }}</code>
                                </td>
                                <td>
                                    @if($log->user)
                                        <div style="font-size: 0.875rem;">
                                            <a href="{{ route('tyro-dashboard.users.edit', $log->user->id) }}" style="font-weight: 500; color: var(--primary); text-decoration: none;">
                                                {{ $log->user->name }}
                                            </a>
                                        </div>
                                    @else
                                        <span class="badge badge-secondary">System</span>
                                    @endif
                                </td>
                                <td>
                                    @php
                                        $targetType = class_basename($log->auditable_type ?? 'System');
                                        $targetId = $log->auditable_id;
                                        $targetRoute = null;

                                        if ($targetId) {
                                            if ($targetType === 'User') {
                                                $targetRoute = route('tyro-dashboard.users.edit', $targetId);
                                            } elseif ($targetType === 'Role') {
                                                $targetRoute = route('tyro-dashboard.roles.show', $targetId);
                                            } elseif ($targetType === 'Privilege') {
                                                $targetRoute = route('tyro-dashboard.privileges.show', $targetId);
                                            }
                                        }
                                    @endphp

                                    @if($targetRoute)
                                        <a href="{{ $targetRoute }}" style="font-size: 0.875rem; color: var(--primary); text-decoration: none;">
                                            {{ $targetType }} #{{ $targetId }}
                                        </a>
                                    @else
                                        <span style="font-size: 0.875rem; color: var(--muted-foreground);">
                                            {{ $targetType }}
                                            @if($targetId)
                                                #{{ $targetId }}
                                            @endif
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <span style="font-size: 0.875rem; color: var(--foreground);">{{ \Illuminate\Support\Str::limit($log->summary, 100) }}</span>
                                </td>
                                <td>
                                    <div class="action-buttons" style="justify-content: flex-end;">
                                        <form action="{{ route('tyro-dashboard.audits.destroy', array_merge(request()->query(), ['id' => $log->id])) }}" method="POST" id="delete-log-form-{{ $log->id }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="action-btn action-btn-danger" title="Delete" onclick="event.preventDefault(); showDanger('Delete Audit Log', 'Delete this audit log entry permanently?').then(confirmed => { if(confirmed) document.getElementById('delete-log-form-{{ $log->id }}').submit(); })">
                                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </form>

        @if($logs->hasPages())
            <div class="pagination">
                {{ $logs->links() }}
            </div>
        @endif
    @else
        <div class="empty-state">
            <svg class="empty-state-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
            <h3 class="empty-state-title">No audit logs found</h3>
            <p class="empty-state-description">No entries match your current filters.</p>
        </div>
    @endif
</div>

@push('scripts')
<script>
    function updatePerPage(value) {
        const url = new URL(window.location.href);
        url.searchParams.set('per_page', value);
        url.searchParams.delete('page');
        window.location.href = url.toString();
    }

    function updateBulkDeleteButtonState() {
        const checkedCount = document.querySelectorAll('.row-checkbox:checked').length;
        const bulkDeleteBtn = document.getElementById('bulk-delete-btn');
        if (bulkDeleteBtn) {
            bulkDeleteBtn.disabled = checkedCount === 0;
        }
    }

    function submitBulkDelete() {
        const checkedCount = document.querySelectorAll('.row-checkbox:checked').length;
        if (checkedCount === 0) {
            return;
        }

        showDanger('Delete Selected Audit Logs', `Delete ${checkedCount} selected audit log entr${checkedCount > 1 ? 'ies' : 'y'} permanently?`)
            .then(confirmed => {
                if (confirmed) {
                    document.getElementById('bulk-delete-form').submit();
                }
            });
    }

    document.addEventListener('DOMContentLoaded', function () {
        const selectAll = document.getElementById('select-all-logs');
        const rowCheckboxes = document.querySelectorAll('.row-checkbox');

        if (selectAll) {
            selectAll.addEventListener('change', function () {
                rowCheckboxes.forEach((checkbox) => {
                    checkbox.checked = selectAll.checked;
                });
                updateBulkDeleteButtonState();
            });
        }

        rowCheckboxes.forEach((checkbox) => {
            checkbox.addEventListener('change', function () {
                const allChecked = rowCheckboxes.length > 0 && Array.from(rowCheckboxes).every((input) => input.checked);
                if (selectAll) {
                    selectAll.checked = allChecked;
                }
                updateBulkDeleteButtonState();
            });
        });

        updateBulkDeleteButtonState();
    });
</script>
@endpush
@endsection
