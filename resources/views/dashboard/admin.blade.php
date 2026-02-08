@extends('tyro-dashboard::layouts.admin')

@section('title', 'Admin Dashboard')

@section('breadcrumb')
<span>Dashboard</span>
@endsection

@section('content')
<div class="page-header">
    <div class="page-header-row">
        <div>
            <h1 class="page-title">Welcome back, {{ $user->name ?? 'User' }}!</h1>
            <p class="page-description" style="font-size: 1rem;">Here's what's happening with your application today.</p>
        </div>
    </div>
</div>

<!-- Stats Grid -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon stat-icon-primary">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
            </svg>
        </div>
        <div class="stat-content">
            <div class="stat-label" style="font-size: 0.9375rem;">Total Users</div>
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
            <div class="stat-label" style="font-size: 0.9375rem;">Total Roles</div>
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
            <div class="stat-label" style="font-size: 0.9375rem;">Total Privileges</div>
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
            <div class="stat-label" style="font-size: 0.9375rem;">Suspended Users</div>
            <div class="stat-value">{{ number_format($stats['suspended_users'] ?? 0) }}</div>
        </div>
    </div>

    @if(config('tyro-dashboard.features.invitation_system', true))
    <div class="stat-card">
        <div class="stat-icon stat-icon-info">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
            </svg>
        </div>
        <div class="stat-content">
            <div class="stat-label" style="font-size: 0.9375rem;">Invitation Links</div>
            <div class="stat-value">{{ number_format($stats['total_invitations'] ?? 0) }}</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon stat-icon-success">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
            </svg>
        </div>
        <div class="stat-content">
            <div class="stat-label" style="font-size: 0.9375rem;">Total Referrals</div>
            <div class="stat-value">{{ number_format($stats['total_referrals'] ?? 0) }}</div>
        </div>
    </div>
    @endif
</div>

<div class="grid-2">
    <!-- Recent Users -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title" style="font-size: 1.0625rem;">Recent Users</h3>
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
                                <a href="{{ route('tyro-dashboard.users.edit', $recentUser->id) }}" class="user-cell" style="text-decoration: none;">
                                    <div class="user-cell-avatar" style="{{ ($recentUser->profile_photo_path || $recentUser->use_gravatar) ? 'background: none; padding: 0;' : '' }}">
                                        @if($recentUser->profile_photo_path || ($recentUser->use_gravatar && $recentUser->email))
                                            <img src="{{ $recentUser->profile_photo_url }}" alt="{{ $recentUser->name }}" style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;">
                                        @else
                                            {{ strtoupper(substr($recentUser->name, 0, 1)) }}
                                        @endif
                                    </div>
                                    <div class="user-cell-info">
                                        <div class="user-cell-name" style="font-size: 0.9375rem;">{{ $recentUser->name }}</div>
                                        <div class="user-cell-email" style="font-size: 0.8125rem;">{{ $recentUser->email }}</div>
                                    </div>
                                </a>
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
                <p class="empty-state-description" style="font-size: 0.9375rem;">No users found.</p>
            </div>
            @endif
        </div>
    </div>

    <!-- Role Distribution -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title" style="font-size: 1.0625rem;">Role Distribution</h3>
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
                                <a href="{{ route('tyro-dashboard.roles.show', $roleStat['id']) }}" style="text-decoration: none;">
                                    <span class="badge badge-primary" style="font-size: 0.875rem;">{{ $roleStat['name'] }}</span>
                                </a>
                            </td>
                            <td style="text-align: right;">
                                <strong style="font-size: 0.9375rem;">{{ $roleStat['count'] }}</strong> <span style="font-size: 0.9375rem;">users</span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="empty-state">
                <p class="empty-state-description" style="font-size: 0.9375rem;">No roles found.</p>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
