@extends('tyro-dashboard::layouts.admin')

@section('title', 'Edit Privilege')

@push('styles')
<style>
    .role-assignment-table-wrap {
        border: 1px solid var(--border);
        border-radius: var(--radius);
        overflow-x: auto;
        background: var(--card);
    }

    .role-assignment-table {
        width: 100%;
        min-width: 640px;
        border-collapse: collapse;
    }

    .role-assignment-table th,
    .role-assignment-table td {
        padding: 1.125rem 1rem;
        border-bottom: 1px solid var(--border);
        text-align: left;
        vertical-align: middle;
    }

    .role-assignment-table th {
        color: var(--muted-foreground);
        font-size: 0.75rem;
        font-weight: 600;
        letter-spacing: 0;
        text-transform: uppercase;
        background: var(--muted);
    }

    .role-assignment-table tbody tr:last-child td {
        border-bottom: 0;
    }

    .role-assignment-table tbody tr:hover {
        background: var(--accent);
    }

    .role-assignment-table tbody tr:has(.role-assignment-check:checked) {
        background: color-mix(in srgb, var(--primary) 10%, transparent);
    }

    .role-assignment-select-cell {
        width: 3.25rem;
        text-align: center;
        padding-right: 0.25rem;
    }

    .role-assignment-toggle {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        color: var(--foreground);
        font-size: 0.875rem;
        font-weight: 500;
        cursor: pointer;
        user-select: none;
    }

    .role-assignment-check {
        width: 1rem;
        height: 1rem;
        margin: 0;
        accent-color: var(--primary);
    }

    .role-assignment-name {
        font-weight: 600;
        color: var(--foreground);
    }

    .role-assignment-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
        margin-bottom: 0.875rem;
    }

    .role-assignment-section {
        margin-top: 1rem;
    }

    .role-assignment-header .form-label {
        margin-bottom: 0;
    }

    .role-assignment-search {
        width: min(100%, 22rem);
    }

    .role-assignment-slug {
        display: inline-block;
        max-width: 100%;
        padding: 0.2rem 0.45rem;
        border-radius: calc(var(--radius) - 2px);
        background: var(--muted);
        color: var(--muted-foreground);
        font-size: 0.8125rem;
        overflow-wrap: anywhere;
    }

    .role-assignment-sr-only {
        position: absolute;
        width: 1px;
        height: 1px;
        padding: 0;
        margin: -1px;
        overflow: hidden;
        clip: rect(0, 0, 0, 0);
        white-space: nowrap;
        border: 0;
    }

    .role-assignment-empty-row[hidden],
    .role-assignment-row[hidden] {
        display: none;
    }

    .role-assignment-empty-cell {
        color: var(--muted-foreground);
        text-align: center;
    }

    @media (max-width: 720px) {
        .role-assignment-table {
            min-width: 0;
        }

        .role-assignment-header {
            align-items: stretch;
            flex-direction: column;
            gap: 0.625rem;
        }

        .role-assignment-search {
            width: 100%;
        }

        .role-assignment-table thead {
            display: none;
        }

        .role-assignment-table,
        .role-assignment-table tbody,
        .role-assignment-table tr,
        .role-assignment-table td {
            display: block;
            width: 100%;
        }

        .role-assignment-table tr {
            border-bottom: 1px solid var(--border);
        }

        .role-assignment-table tbody tr:last-child {
            border-bottom: 0;
        }

        .role-assignment-table td {
            border-bottom: 0;
            padding: 0.9rem 1.15rem;
        }

        .role-assignment-select-cell {
            width: 100%;
            padding-bottom: 0.25rem;
        }

        .role-assignment-name-cell {
            padding-top: 0.25rem;
            padding-bottom: 0.35rem;
        }

        .role-assignment-slug-cell {
            padding-top: 0.35rem;
        }
    }
</style>
@endpush

@section('breadcrumb')
<a href="{{ route($dashboardRoute::name('index')) }}">Dashboard</a>
<span class="breadcrumb-separator">/</span>
<a href="{{ route($dashboardRoute::name('privileges.index')) }}">Privileges</a>
<span class="breadcrumb-separator">/</span>
<span>Edit</span>
@endsection

@section('content')
<div class="page-header">
    <div class="page-header-row">
        <div>
            <h1 class="page-title">Edit Privilege</h1>
            <p class="page-description">Update privilege information and role assignments.</p>
        </div>
        <div style="display: flex; gap: 10px;">
            <a href="{{ route($dashboardRoute::name('privileges.index')) }}" class="btn btn-secondary">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Back to Privileges
            </a>
            <form action="{{ route($dashboardRoute::name('privileges.destroy'), $privilege->id) }}" method="POST" style="display: inline;" id="delete-privilege-edit-form">
                @csrf
                @method('DELETE')
                <button type="button" class="btn btn-destructive" onclick="event.preventDefault(); showDanger('Delete Privilege', 'Are you sure you want to delete this privilege? This action cannot be undone.').then(confirmed => { if(confirmed) document.getElementById('delete-privilege-edit-form').submit(); })">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                    Delete Privilege
                </button>
            </form>
        </div>
    </div>
</div>

<form action="{{ route($dashboardRoute::name('privileges.update'), $privilege->id) }}" method="POST">
    @csrf
    @method('PUT')
    <div class="card">
        <div class="card-body">
            <div class="form-row">
                <div class="form-group">
                    <label for="name" class="form-label">Privilege Name</label>
                    <input type="text" id="name" name="name" class="form-input @error('name') is-invalid @enderror" value="{{ old('name', $privilege->name) }}" required>
                    @error('name')
                        <span class="form-error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="slug" class="form-label">Slug</label>
                    <input type="text" id="slug" name="slug" class="form-input @error('slug') is-invalid @enderror" value="{{ old('slug', $privilege->slug) }}">
                    <span class="form-hint">Used for programmatic access. Must be unique.</span>
                    @error('slug')
                        <span class="form-error">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="form-group">
                <label for="description" class="form-label">
                    Description <span class="form-label-optional">(optional)</span>
                </label>
                <textarea id="description" name="description" class="form-textarea @error('description') is-invalid @enderror" rows="3">{{ old('description', $privilege->description) }}</textarea>
                @error('description')
                    <span class="form-error">{{ $message }}</span>
                @enderror
            </div>
        </div>
    </div>

    <div class="card role-assignment-section">
        <div class="card-body">
            <div class="form-group">
                @if($roles->count())
                @php
                    $selectedRoles = old('roles', $privilege->roles->pluck('id')->toArray());
                    $selectedRoles = array_map('intval', $selectedRoles);
                @endphp
                <div class="role-assignment-header">
                    <label class="form-label">Assign to Roles</label>
                    <label for="roleAssignmentSearch" class="role-assignment-sr-only">Search roles</label>
                    <input type="search" id="roleAssignmentSearch" class="form-input role-assignment-search" placeholder="Search roles..." autocomplete="off" data-assignment-search data-target-table="roleAssignmentTable">
                </div>
                <div class="role-assignment-table-wrap">
                    <table class="role-assignment-table" id="roleAssignmentTable">
                        <thead>
                            <tr>
                                <th scope="col"><span class="role-assignment-sr-only">Assigned</span></th>
                                <th scope="col">Role</th>
                                <th scope="col">Slug</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($roles as $role)
                            <tr class="role-assignment-row" data-search-text="{{ strtolower($role->name.' '.$role->slug) }}">
                                <td class="role-assignment-select-cell">
                                    <label class="role-assignment-toggle">
                                        <input type="checkbox" name="roles[]" value="{{ $role->id }}" class="role-assignment-check" aria-label="Assign to {{ $role->name }} role" {{ in_array((int) $role->id, $selectedRoles, true) ? 'checked' : '' }}>
                                    </label>
                                </td>
                                <td class="role-assignment-name-cell">
                                    <div class="role-assignment-name">{{ $role->name }}</div>
                                </td>
                                <td class="role-assignment-slug-cell">
                                    <code class="role-assignment-slug">{{ $role->slug }}</code>
                                </td>
                            </tr>
                            @endforeach
                            <tr class="role-assignment-empty-row" hidden>
                                <td colspan="3" class="role-assignment-empty-cell">No roles match your search.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                @else
                <label class="form-label">Assign to Roles</label>
                <div class="alert alert-info">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <div class="alert-content">
                        <p class="alert-message">No roles available. <a href="{{ route($dashboardRoute::name('roles.create')) }}">Create one</a> first.</p>
                    </div>
                </div>
                @endif
                @error('roles')
                    <span class="form-error">{{ $message }}</span>
                @enderror
            </div>
        </div>
        <div class="card-footer" style="display: flex; gap: 0.75rem;">
            <button type="submit" class="btn btn-primary">Save Changes</button>
            <a href="{{ route($dashboardRoute::name('privileges.index')) }}" class="btn btn-secondary">Cancel</a>
        </div>
    </div>
</form>
@endsection

@push('scripts')
<script>
    document.querySelectorAll('[data-assignment-search]').forEach((input) => {
        const table = document.getElementById(input.dataset.targetTable);
        if (! table) {
            return;
        }

        const rows = Array.from(table.querySelectorAll('tbody tr[data-search-text]'));
        const emptyRow = table.querySelector('tbody tr:not([data-search-text])');

        input.addEventListener('input', () => {
            const query = input.value.trim().toLowerCase();
            let visibleCount = 0;

            rows.forEach((row) => {
                const visible = row.dataset.searchText.includes(query);
                row.hidden = ! visible;
                if (visible) {
                    visibleCount++;
                }
            });

            if (emptyRow) {
                emptyRow.hidden = visibleCount > 0;
            }
        });
    });
</script>
@endpush
