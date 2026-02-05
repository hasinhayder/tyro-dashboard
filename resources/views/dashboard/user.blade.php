@extends('tyro-dashboard::layouts.user')

@section('title', 'Dashboard')

@section('breadcrumb')
<span>Dashboard</span>
@endsection

@section('content')
<div class="page-header">
    <div class="page-header-row">
        <div>
            <h1 class="page-title">Welcome back, {{ $user->name ?? 'User' }}!</h1>
            <p class="page-description" style="font-size: 1rem;">Here's what's happening with your account today.</p>
        </div>
    </div>
</div>

<!-- User Dashboard -->
<div class="card">
    <div class="card-header">
        <h3 class="card-title" style="font-size: 1.0625rem;">Your Account</h3>
    </div>
    <div class="card-body">
        <div class="user-cell" style="margin-bottom: 1.5rem;">
            <div class="user-cell-avatar" style="width: 64px; height: 64px; font-size: 1.5rem;">
                {{ strtoupper(substr($user->name, 0, 1)) }}
            </div>
            <div class="user-cell-info">
                <div class="user-cell-name" style="font-size: 1.375rem;">{{ $user->name }}</div>
                <div class="user-cell-email" style="font-size: 0.9375rem;">{{ $user->email }}</div>
            </div>
        </div>

        @if(method_exists($user, 'roles') && $user->roles && $user->roles->count())
        <div style="margin-bottom: 1.5rem;">
            <strong style="font-size: 0.9375rem; color: var(--muted-foreground);">Your Roles:</strong>
            <div class="badge-list" style="margin-top: 0.5rem;">
                @foreach($user->roles as $role)
                    <span class="badge badge-primary" style="font-size: 0.875rem;">{{ $role->name }}</span>
                @endforeach
            </div>
        </div>
        @endif

        @if(config('tyro-dashboard.features.invitation_system', true))
            @if(isset($stats['has_invitation_link']) && $stats['has_invitation_link'])
            <div style="margin-bottom: 1.5rem; padding: 1rem; background: var(--muted); border-radius: 0.5rem;">
                <div style="display: flex; align-items: center; justify-content: space-between;">
                    <div>
                        <strong style="font-size: 0.9375rem;">My Referrals</strong>
                        <p style="font-size: 0.875rem; color: var(--muted-foreground); margin-top: 0.25rem;">You've referred {{ $stats['my_referrals'] ?? 0 }} user(s)</p>
                    </div>
                    <a href="{{ route('tyro-dashboard.invitations.index') }}" class="btn btn-sm btn-secondary">View Details</a>
                </div>
            </div>
            @else
            <div style="margin-bottom: 1.5rem; padding: 1rem; background: var(--muted); border-radius: 0.5rem;">
                <strong style="font-size: 0.9375rem;">Invitation Link</strong>
                <p style="font-size: 0.875rem; color: var(--muted-foreground); margin-top: 0.25rem;">Create your invitation link to start referring users.</p>
                <a href="{{ route('tyro-dashboard.invitations.index') }}" class="btn btn-sm btn-primary" style="margin-top: 0.5rem;">Create Invitation Link</a>
            </div>
            @endif
        @endif

        <a href="{{ route('tyro-dashboard.profile') }}" class="btn btn-primary">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
            </svg>
            Edit Profile
        </a>
    </div>
</div>
@endsection
