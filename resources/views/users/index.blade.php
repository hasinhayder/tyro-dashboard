@extends('tyro-dashboard::layouts.admin')

@section('title', 'Users')

@section('breadcrumb')
<a href="{{ route('tyro-dashboard.index') }}">Dashboard</a>
<span class="breadcrumb-separator">/</span>
<span>Users</span>
@endsection

@section('content')
<div class="page-header">
    <div class="page-header-row">
        <div>
            <h1 class="page-title">Users</h1>
            <p class="page-description">Manage user accounts, roles, and permissions.</p>
        </div>
        <a href="{{ route('tyro-dashboard.users.create') }}" class="btn btn-primary">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
            </svg>
            Add User
        </a>
    </div>
</div>

<!-- Filters -->
<div class="card" style="margin-bottom: 1rem;">
    <div class="card-body">
        <form action="{{ route('tyro-dashboard.users.index') }}" method="GET">
            <div class="filters-bar">
                <div class="search-box">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    <input type="text" name="search" class="form-input" placeholder="Search users..." value="{{ $filters['search'] ?? '' }}">
                </div>
                <div class="filter-group">
                    <label class="filter-label">Role:</label>
                    <select name="role" class="form-select" style="min-width: 150px;">
                        <option value="">All Roles</option>
                        @foreach($roles as $role)
                            <option value="{{ $role->slug }}" {{ ($filters['role'] ?? '') === $role->slug ? 'selected' : '' }}>{{ $role->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="filter-group">
                    <label class="filter-label">Status:</label>
                    <select name="status" class="form-select" style="min-width: 130px;">
                        <option value="">All Status</option>
                        <option value="active" {{ ($filters['status'] ?? '') === 'active' ? 'selected' : '' }}>Active</option>
                        <option value="suspended" {{ ($filters['status'] ?? '') === 'suspended' ? 'selected' : '' }}>Suspended</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-secondary">Filter</button>
                @if(!empty($filters['search']) || !empty($filters['role']) || !empty($filters['status']))
                    <a href="{{ route('tyro-dashboard.users.index') }}" class="btn btn-ghost">Clear</a>
                @endif
            </div>
        </form>
    </div>
</div>

<!-- Users Table -->
<div class="card">
    @if($users->count())
    <div class="table-container">
        <table class="table">
            <thead>
                <tr>
                    <th>User</th>
                    <th>Roles</th>
                    <th>Status</th>
                    <th>Joined</th>
                    <th style="text-align: right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $listUser)
                <tr>
                    <td>
                        <a href="{{ route('tyro-dashboard.users.edit', $listUser->id) }}" class="user-cell" style="text-decoration: none;">
                            <div class="user-cell-avatar">
                                {{ strtoupper(substr($listUser->name, 0, 1)) }}
                            </div>
                            <div class="user-cell-info">
                                <div class="user-cell-name">{{ $listUser->name }}</div>
                                <div class="user-cell-email">{{ $listUser->email }}</div>
                            </div>
                        </a>
                    </td>
                    <td>
                        <div class="badge-list">
                            @forelse($listUser->roles as $role)
                                <span class="badge badge-primary">{{ $role->name }}</span>
                            @empty
                                <span class="badge badge-secondary">No roles</span>
                            @endforelse
                        </div>
                    </td>
                    <td>
                        @if(method_exists($listUser, 'isSuspended') && $listUser->isSuspended())
                            <span class="badge badge-danger">Suspended</span>
                        @else
                            <span class="badge badge-success">Active</span>
                        @endif
                    </td>
                    <td>{{ $listUser->created_at->format('M d, Y') }}</td>
                    <td>
                        <div class="action-buttons" style="justify-content: flex-end;">
                            <a href="{{ route('tyro-dashboard.users.edit', $listUser->id) }}" class="action-btn" title="Edit">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                            </a>
                            @if(method_exists($listUser, 'isSuspended') && $listUser->isSuspended())
                                <form action="{{ route('tyro-dashboard.users.unsuspend', $listUser->id) }}" method="POST" style="display: inline;">
                                    @csrf
                                    <button type="submit" class="action-btn" title="Unsuspend" onclick="return confirm('Are you sure you want to unsuspend this user?')">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </button>
                                </form>
                            @elseif(method_exists($listUser, 'suspend'))
                                <button type="button" class="action-btn action-btn-danger" title="Suspend" onclick="openSuspendModal({{ $listUser->id }}, '{{ addslashes($listUser->name) }}')">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                                    </svg>
                                </button>
                            @endif
                            @if($listUser->id !== $user->id)
                                <form action="{{ route('tyro-dashboard.users.destroy', $listUser->id) }}" method="POST" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="action-btn action-btn-danger" title="Delete" onclick="return confirm('Are you sure you want to delete this user? This action cannot be undone.')">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    @if($users->hasPages())
    <div class="pagination">
        {{ $users->links() }}
    </div>
    @endif
    @else
    <div class="empty-state">
        <svg class="empty-state-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
        </svg>
        <h3 class="empty-state-title">No users found</h3>
        <p class="empty-state-description">Get started by creating a new user.</p>
        <a href="{{ route('tyro-dashboard.users.create') }}" class="btn btn-primary">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
            </svg>
            Add User
        </a>
    </div>
    @endif
</div>

<!-- Suspend Modal -->
<div class="modal-overlay" id="suspendModal">
    <div class="modal">
        <div class="modal-header">
            <h3 class="modal-title">Suspend User</h3>
            <button type="button" class="modal-close" onclick="closeModal('suspendModal')">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        <form id="suspendForm" method="POST">
            @csrf
            <div class="modal-body">
                <p style="margin-bottom: 1rem; color: var(--muted-foreground);">
                    You are about to suspend <strong id="suspendUserName"></strong>. This will revoke all their active sessions.
                </p>
                <div class="form-group">
                    <label for="reason" class="form-label">
                        Reason for suspension <span class="form-label-optional">(optional)</span>
                    </label>
                    <textarea id="reason" name="reason" class="form-textarea" rows="3" placeholder="Enter a reason for suspension..."></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeModal('suspendModal')">Cancel</button>
                <button type="submit" class="btn btn-danger">Suspend User</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    function openSuspendModal(userId, userName) {
        document.getElementById('suspendForm').action = '{{ url(config('tyro-dashboard.route_prefix', 'dashboard')) }}/users/' + userId + '/suspend';
        document.getElementById('suspendUserName').textContent = userName;
        document.getElementById('reason').value = '';
        openModal('suspendModal');
    }

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
