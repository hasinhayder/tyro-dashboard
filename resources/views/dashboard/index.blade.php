@extends('tyro-dashboard::layouts.app')

@section('title', 'Dashboard')

@section('breadcrumb')
<span>Dashboard</span>
@endsection

@section('content')
<div class="page-header">
    <div class="page-header-row">
        <div>
            <h1 class="page-title">Welcome back, {{ $user->name ?? 'User' }}!</h1>
            <p class="page-description">Here's what's happening with your account today.</p>
        </div>
    </div>
</div>

@if($isAdmin ?? false)
<!-- Stats Grid -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon stat-icon-primary">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
            </svg>
        </div>
        <div class="stat-content">
            <div class="stat-label">Total Users</div>
            <div class="stat-value">{{ number_format($stats['total_users'] ?? 0) }}</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon stat-icon-success">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
            </svg>
        </div>
        <div class="stat-content">
            <div class="stat-label">Total Roles</div>
            <div class="stat-value">{{ number_format($stats['total_roles'] ?? 0) }}</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon stat-icon-info">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
            </svg>
        </div>
        <div class="stat-content">
            <div class="stat-label">Total Privileges</div>
            <div class="stat-value">{{ number_format($stats['total_privileges'] ?? 0) }}</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon stat-icon-danger">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
            </svg>
        </div>
        <div class="stat-content">
            <div class="stat-label">Suspended Users</div>
            <div class="stat-value">{{ number_format($stats['suspended_users'] ?? 0) }}</div>
        </div>
    </div>
</div>

<div class="grid-2">
    <!-- Recent Users -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Recent Users</h3>
            <a href="{{ route('tyro-dashboard.users.index') }}" class="btn btn-sm btn-ghost">View All</a>
        </div>
        <div class="card-body" style="padding: 0;">
            @if(isset($stats['recent_users']) && $stats['recent_users']->count())
            <div class="table-container">
                <table class="table">
                    <tbody>
                        @foreach($stats['recent_users'] as $recentUser)
                        <tr>
                            <td>
                                <div class="user-cell">
                                    <div class="user-cell-avatar">
                                        {{ strtoupper(substr($recentUser->name, 0, 1)) }}
                                    </div>
                                    <div class="user-cell-info">
                                        <div class="user-cell-name">{{ $recentUser->name }}</div>
                                        <div class="user-cell-email">{{ $recentUser->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td style="text-align: right;">
                                @if(method_exists($recentUser, 'isSuspended') && $recentUser->isSuspended())
                                    <span class="badge badge-danger">Suspended</span>
                                @else
                                    <span class="badge badge-success">Active</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="empty-state">
                <p class="empty-state-description">No users found.</p>
            </div>
            @endif
        </div>
    </div>

    <!-- Role Distribution -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Role Distribution</h3>
            <a href="{{ route('tyro-dashboard.roles.index') }}" class="btn btn-sm btn-ghost">Manage Roles</a>
        </div>
        <div class="card-body" style="padding: 0;">
            @if(isset($stats['role_distribution']) && $stats['role_distribution']->count())
            <div class="table-container">
                <table class="table">
                    <tbody>
                        @foreach($stats['role_distribution'] as $roleStat)
                        <tr>
                            <td>
                                <span class="badge badge-primary">{{ $roleStat['name'] }}</span>
                            </td>
                            <td style="text-align: right;">
                                <strong>{{ $roleStat['count'] }}</strong> users
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="empty-state">
                <p class="empty-state-description">No roles found.</p>
            </div>
            @endif
        </div>
    </div>
</div>

@else
<!-- User Dashboard (Non-Admin) -->
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Your Account</h3>
    </div>
    <div class="card-body">
        <div class="user-cell" style="margin-bottom: 1.5rem;">
            <div class="user-cell-avatar" style="width: 64px; height: 64px; font-size: 1.5rem;">
                {{ strtoupper(substr($user->name, 0, 1)) }}
            </div>
            <div class="user-cell-info">
                <div class="user-cell-name" style="font-size: 1.25rem;">{{ $user->name }}</div>
                <div class="user-cell-email">{{ $user->email }}</div>
            </div>
        </div>

        @if(method_exists($user, 'roles') && $user->roles && $user->roles->count())
        <div style="margin-bottom: 1rem;">
            <strong style="font-size: 0.875rem; color: var(--muted-foreground);">Your Roles:</strong>
            <div class="badge-list" style="margin-top: 0.5rem;">
                @foreach($user->roles as $role)
                    <span class="badge badge-primary">{{ $role->name }}</span>
                @endforeach
            </div>
        </div>
        @endif

        <a href="{{ route('tyro-dashboard.profile') }}" class="btn btn-primary">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
            </svg>
            Edit Profile
        </a>
    </div>
</div>
@endif
@endsection
