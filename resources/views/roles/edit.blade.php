@extends('tyro-dashboard::layouts.admin')

@section('title', 'Edit Role')

@push('styles')
<style>
    .privilege-table-wrap {
        border: 1px solid var(--border);
        border-radius: var(--radius);
        overflow-x: auto;
        background: var(--card);
    }

    .privilege-table {
        width: 100%;
        min-width: 640px;
        border-collapse: collapse;
    }

    .privilege-table th,
    .privilege-table td {
        padding: 1.125rem 1rem;
        border-bottom: 1px solid var(--border);
        text-align: left;
        vertical-align: middle;
    }

    .privilege-table th {
        color: var(--muted-foreground);
        font-size: 0.75rem;
        font-weight: 600;
        letter-spacing: 0;
        text-transform: uppercase;
        background: var(--muted);
    }

    .privilege-table tbody tr:last-child td {
        border-bottom: 0;
    }

    .privilege-table tbody tr:hover {
        background: var(--accent);
    }

    .privilege-table tbody tr:has(.privilege-check:checked) {
        background: color-mix(in srgb, var(--primary) 10%, transparent);
    }

    .privilege-select-cell {
        width: 3.25rem;
        text-align: center;
        padding-right: 0.25rem;
    }

    .privilege-toggle {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        color: var(--foreground);
        font-size: 0.875rem;
        font-weight: 500;
        cursor: pointer;
        user-select: none;
    }

    .privilege-check {
        width: 1rem;
        height: 1rem;
        margin: 0;
        accent-color: var(--primary);
    }

    .privilege-name {
        font-weight: 600;
        color: var(--foreground);
    }

    .privilege-assignment-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
        margin-bottom: 0.875rem;
    }

    .privilege-assignment-section {
        margin-top: 1rem;
    }

    .privilege-assignment-header .form-label {
        margin-bottom: 0;
    }

    .privilege-search {
        width: min(100%, 22rem);
    }

    .privilege-slug {
        display: inline-block;
        max-width: 100%;
        padding: 0.2rem 0.45rem;
        border-radius: calc(var(--radius) - 2px);
        background: var(--muted);
        color: var(--muted-foreground);
        font-size: 0.8125rem;
        overflow-wrap: anywhere;
    }

    .privilege-sr-only {
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

    .privilege-empty-row[hidden],
    .privilege-row[hidden] {
        display: none;
    }

    .privilege-empty-cell {
        color: var(--muted-foreground);
        text-align: center;
    }

    @media (max-width: 720px) {
        .privilege-table {
            min-width: 0;
        }

        .privilege-assignment-header {
            align-items: stretch;
            flex-direction: column;
            gap: 0.625rem;
        }

        .privilege-search {
            width: 100%;
        }

        .privilege-table thead {
            display: none;
        }

        .privilege-table,
        .privilege-table tbody,
        .privilege-table tr,
        .privilege-table td {
            display: block;
            width: 100%;
        }

        .privilege-table tr {
            border-bottom: 1px solid var(--border);
        }

        .privilege-table tbody tr:last-child {
            border-bottom: 0;
        }

        .privilege-table td {
            border-bottom: 0;
            padding: 0.9rem 1.15rem;
        }

        .privilege-select-cell {
            width: 100%;
            padding-bottom: 0.25rem;
        }

        .privilege-name-cell {
            padding-top: 0.25rem;
            padding-bottom: 0.35rem;
        }

        .privilege-slug-cell {
            padding-top: 0.35rem;
        }
    }
</style>
@endpush

@section('breadcrumb')
<a href="{{ route($dashboardRoute::name('index')) }}">Dashboard</a>
<span class="breadcrumb-separator">/</span>
<a href="{{ route($dashboardRoute::name('roles.index')) }}">Roles</a>
<span class="breadcrumb-separator">/</span>
<span>Edit</span>
@endsection

@section('content')
<div class="page-header">
    <div class="page-header-row">
        <div>
            <h1 class="page-title">Edit Role</h1>
            <p class="page-description">Update role information and privileges.</p>
        </div>
        <div style="display: flex; gap: 10px;">
            <a href="{{ route($dashboardRoute::name('roles.index')) }}" class="btn btn-secondary">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Back to Roles
            </a>
            @if(!in_array($role->slug, $protectedRoles))
            <form action="{{ route($dashboardRoute::name('roles.destroy'), $role->id) }}" method="POST" style="display: inline;" id="delete-role-edit-form">
                @csrf
                @method('DELETE')
                <button type="button" class="btn btn-destructive" onclick="event.preventDefault(); showDanger('Delete Role', 'Are you sure you want to delete this role? This action cannot be undone.').then(confirmed => { if(confirmed) document.getElementById('delete-role-edit-form').submit(); })">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                    Delete Role
                </button>
            </form>
            @endif
        </div>
    </div>
</div>

<form action="{{ route($dashboardRoute::name('roles.update'), $role->id) }}" method="POST">
    @csrf
    @method('PUT')
    <div class="card">
        <div class="card-body">
            <div class="form-row">
                <div class="form-group">
                    <label for="name" class="form-label">Role Name</label>
                    <input type="text" id="name" name="name" class="form-input @error('name') is-invalid @enderror" value="{{ old('name', $role->name) }}" required>
                    @error('name')
                        <span class="form-error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="slug" class="form-label">Slug</label>
                    <input type="text" id="slug" name="slug" class="form-input @error('slug') is-invalid @enderror" value="{{ old('slug', $role->slug) }}">
                    <span class="form-hint">Used for programmatic access. Must be unique.</span>
                    @error('slug')
                        <span class="form-error">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>
    </div>

    <div class="card privilege-assignment-section">
        <div class="card-body">
            <div class="form-group">
                @if($privileges->count())
                @php
                    $selectedPrivileges = old('privileges', $role->privileges->pluck('id')->toArray());
                    $selectedPrivileges = array_map('intval', $selectedPrivileges);
                @endphp
                <div class="privilege-assignment-header">
                    <label class="form-label">Assign Privileges</label>
                    <label for="privilegeSearch" class="privilege-sr-only">Search privileges</label>
                    <input type="search" id="privilegeSearch" class="form-input privilege-search" placeholder="Search privileges..." autocomplete="off" data-assignment-search data-target-table="privilegeAssignmentTable">
                </div>
                <div class="privilege-table-wrap">
                    <table class="privilege-table" id="privilegeAssignmentTable">
                        <thead>
                            <tr>
                                <th scope="col"><span class="privilege-sr-only">Assigned</span></th>
                                <th scope="col">Privilege</th>
                                <th scope="col">Slug</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($privileges as $privilege)
                            <tr class="privilege-row" data-search-text="{{ strtolower($privilege->name.' '.$privilege->slug) }}">
                                <td class="privilege-select-cell">
                                    <label class="privilege-toggle">
                                        <input type="checkbox" name="privileges[]" value="{{ $privilege->id }}" class="privilege-check" aria-label="Assign {{ $privilege->name }} privilege" {{ in_array((int) $privilege->id, $selectedPrivileges, true) ? 'checked' : '' }}>
                                    </label>
                                </td>
                                <td class="privilege-name-cell">
                                    <div class="privilege-name">{{ $privilege->name }}</div>
                                </td>
                                <td class="privilege-slug-cell">
                                    <code class="privilege-slug">{{ $privilege->slug }}</code>
                                </td>
                            </tr>
                            @endforeach
                            <tr class="privilege-empty-row" hidden>
                                <td colspan="3" class="privilege-empty-cell">No privileges match your search.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                @else
                <label class="form-label">Assign Privileges</label>
                <div class="alert alert-info">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <div class="alert-content">
                        <p class="alert-message">No privileges available. <a href="{{ route($dashboardRoute::name('privileges.create')) }}">Create one</a> first.</p>
                    </div>
                </div>
                @endif
                @error('privileges')
                    <span class="form-error">{{ $message }}</span>
                @enderror
            </div>
        </div>
        <div class="card-footer" style="display: flex; gap: 0.75rem;">
            <button type="submit" class="btn btn-primary">Save Changes</button>
            <a href="{{ route($dashboardRoute::name('roles.index')) }}" class="btn btn-secondary">Cancel</a>
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
