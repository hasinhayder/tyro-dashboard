@extends('tyro-dashboard::layouts.admin')

@section('title', $role->name)

@section('breadcrumb')
<a href="{{ route('tyro-dashboard.index') }}">Dashboard</a>
<span class="breadcrumb-separator">/</span>
<a href="{{ route('tyro-dashboard.roles.index') }}">Roles</a>
<span class="breadcrumb-separator">/</span>
<span>{{ $role->name }}</span>
@endsection

@section('content')
<div class="page-header">
    <div class="page-header-row">
        <div>
            <h1 class="page-title">{{ $role->name }}</h1>
            <p class="page-description">
                <code style="padding: 0.375rem 0.625rem; background-color: var(--muted); border-radius: 0.375rem; font-size: 0.9375rem;">{{ $role->slug }}</code>
            </p>
        </div>
        <div class="btn-group">
            <a href="{{ route('tyro-dashboard.roles.edit', $role->id) }}" class="btn btn-primary">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
                Edit Role
            </a>
            <a href="{{ route('tyro-dashboard.roles.index') }}" class="btn btn-secondary">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Back to Roles
            </a>
        </div>
    </div>
</div>

<div class="grid-2">
    <!-- Privileges -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title" style="font-size: 1.0625rem;">Privileges ({{ $role->privileges->count() }})</h3>
        </div>
        <div class="card-body" style="padding: 0;">
            @if($role->privileges->count())
            <div class="table-container">
                <table class="table">
                    <thead>
                        <tr>
                            <th style="font-size: 0.9375rem;">Privilege</th>
                            <th style="font-size: 0.9375rem;">Slug</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($role->privileges as $privilege)
                        <tr>
                            <td>
                                <a href="{{ route('tyro-dashboard.privileges.show', $privilege->id) }}" style="font-weight: 500; font-size: 0.9375rem; color: var(--foreground); text-decoration: none;">
                                    {{ $privilege->name }}
                                </a>
                            </td>
                            <td>
                                <code style="padding: 0.375rem 0.625rem; background-color: var(--muted); border-radius: 0.375rem; font-size: 0.875rem;">{{ $privilege->slug }}</code>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="empty-state">
                <p class="empty-state-description" style="font-size: 0.9375rem;">No privileges assigned to this role.</p>
                <a href="{{ route('tyro-dashboard.roles.edit', $role->id) }}" class="btn btn-sm btn-secondary">Assign Privileges</a>
            </div>
            @endif
        </div>
    </div>

    <!-- Users -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title" style="font-size: 1.0625rem;">Users with this Role ({{ $role->users->count() }})</h3>
        </div>
        <div class="card-body" style="padding: 0;">
            @if($role->users->count())
            <div class="table-container">
                <table class="table">
                    <thead>
                        <tr>
                            <th style="font-size: 0.9375rem;">User</th>
                            <th style="text-align: right; font-size: 0.9375rem;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($role->users->take(10) as $roleUser)
                        <tr>
                            <td>
                                <a href="{{ route('tyro-dashboard.users.edit', $roleUser->id) }}" class="user-cell" style="text-decoration: none;">
                                    <div class="user-cell-avatar">
                                        {{ strtoupper(substr($roleUser->name, 0, 1)) }}
                                    </div>
                                    <div class="user-cell-info">
                                        <div class="user-cell-name" style="font-size: 0.9375rem;">{{ $roleUser->name }}</div>
                                        <div class="user-cell-email" style="font-size: 0.8125rem;">{{ $roleUser->email }}</div>
                                    </div>
                                </a>
                            </td>
                            <td style="text-align: right;">
                                <div class="action-buttons" style="justify-content: flex-end;">
                                    <a href="{{ route('tyro-dashboard.users.edit', $roleUser->id) }}" class="action-btn" title="Edit User">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </a>
                                    <form action="{{ route('tyro-dashboard.roles.remove-user', ['id' => $role->id, 'userId' => $roleUser->id]) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="action-btn action-btn-danger" title="Remove from Role" onclick="return confirm('Are you sure you want to remove this user from the {{ $role->name }} role?')">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M13 7a4 4 0 11-8 0 4 4 0 018 0zM9 14a6 6 0 00-6 6v1h12v-1a6 6 0 00-6-6zM21 12h-6" />
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
            @if($role->users->count() > 10)
            <div style="padding: 1rem; text-align: center; border-top: 1px solid var(--border);">
                <a href="{{ route('tyro-dashboard.users.index', ['role' => $role->slug]) }}" class="btn btn-sm btn-secondary">View All {{ $role->users->count() }} Users</a>
            </div>
            @endif
            @else
            <div class="empty-state">
                <p class="empty-state-description" style="font-size: 0.9375rem;">No users have this role.</p>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
