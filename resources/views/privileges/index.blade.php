@extends('tyro-dashboard::layouts.admin')

@section('title', 'Privileges')

@section('breadcrumb')
<a href="{{ route('tyro-dashboard.index') }}">Dashboard</a>
<span class="breadcrumb-separator">/</span>
<span>Privileges</span>
@endsection

@section('content')
<div class="page-header">
    <div class="page-header-row">
        <div>
            <h1 class="page-title">Privileges</h1>
            <p class="page-description">Manage granular permissions that can be assigned to roles.</p>
        </div>
        <a href="{{ route('tyro-dashboard.privileges.create') }}" class="btn btn-primary">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
            </svg>
            Add Privilege
        </a>
    </div>
</div>

<!-- Filters -->
<div class="card" style="margin-bottom: 1rem;">
    <div class="card-body">
        <form action="{{ route('tyro-dashboard.privileges.index') }}" method="GET">
            <div class="filters-bar">
                <div class="search-box">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    <input type="text" name="search" class="form-input" placeholder="Search privileges..." value="{{ $filters['search'] ?? '' }}">
                </div>
                <button type="submit" class="btn btn-secondary">Search</button>
                @if(!empty($filters['search']))
                    <a href="{{ route('tyro-dashboard.privileges.index') }}" class="btn btn-ghost">Clear</a>
                @endif
            </div>
        </form>
    </div>
</div>

<!-- Privileges Table -->
<div class="card">
    @if($privileges->count())
    <div class="table-container">
        <table class="table">
            <thead>
                <tr>
                    <th>Privilege</th>
                    <th>Slug</th>
                    <th>Description</th>
                    <th>Roles</th>
                    <th style="text-align: right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($privileges as $privilege)
                <tr>
                    <td>
                        <a href="{{ route('tyro-dashboard.privileges.show', $privilege->id) }}" style="display: flex; align-items: center; gap: 0.75rem; text-decoration: none;">
                            <div style="width: 36px; height: 36px; border-radius: 0.5rem; background: linear-gradient(135deg, #22c55e 0%, #16a34a 100%); display: flex; align-items: center; justify-content: center;">
                                <svg style="width: 18px; height: 18px; color: white;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                                </svg>
                            </div>
                            <span style="font-weight: 500; color: var(--foreground);">{{ $privilege->name }}</span>
                        </a>
                    </td>
                    <td>
                        <code style="padding: 0.25rem 0.5rem; background-color: var(--muted); border-radius: 0.25rem; font-size: 0.8125rem;">{{ $privilege->slug }}</code>
                    </td>
                    <td>
                        <span style="font-size: 0.875rem; color: var(--muted-foreground);">{{ Str::limit($privilege->description, 50) ?: '-' }}</span>
                    </td>
                    <td>
                        <span class="badge badge-secondary">{{ $privilege->roles_count }} roles</span>
                    </td>
                    <td>
                        <div class="action-buttons" style="justify-content: flex-end;">
                            <a href="{{ route('tyro-dashboard.privileges.show', $privilege->id) }}" class="action-btn" title="View">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </a>
                            <a href="{{ route('tyro-dashboard.privileges.edit', $privilege->id) }}" class="action-btn" title="Edit">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                            </a>
                            <form action="{{ route('tyro-dashboard.privileges.destroy', $privilege->id) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="action-btn action-btn-danger" title="Delete" onclick="return confirm('Are you sure you want to delete this privilege? It will be removed from all roles.')">
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

    @if($privileges->hasPages())
    <div class="pagination">
        {{ $privileges->links() }}
    </div>
    @endif
    @else
    <div class="empty-state">
        <svg class="empty-state-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
        </svg>
        <h3 class="empty-state-title">No privileges found</h3>
        <p class="empty-state-description">Get started by creating a new privilege.</p>
        <a href="{{ route('tyro-dashboard.privileges.create') }}" class="btn btn-primary">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
            </svg>
            Add Privilege
        </a>
    </div>
    @endif
</div>

@push('scripts')
<script>
    // Auto-focus search input and move cursor to end if search is present
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.querySelector('input[name="search"]');
        if (searchInput && searchInput.value) {
            searchInput.focus();
            searchInput.setSelectionRange(searchInput.value.length, searchInput.value.length);
        }
    });
</script>
@endpush
@endsection
