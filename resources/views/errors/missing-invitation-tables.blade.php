@extends('tyro-dashboard::layouts.' . (auth()->user() && method_exists(auth()->user(), 'hasRole') && (auth()->user()->hasRole(config('tyro-dashboard.admin_roles', ['admin', 'super-admin']))) ? 'admin' : 'user'))

@section('title', 'Migration Required')

@section('breadcrumb')
<a href="{{ route('tyro-dashboard.index') }}">Dashboard</a>
<span class="breadcrumb-separator">/</span>
<span>Migration Required</span>
@endsection

@section('content')
<div class="page-header">
    <div class="page-header-row">
        <div>
            <h1 class="page-title">Invitation System - Migration Required</h1>
            <p class="page-description">Database tables are missing</p>
        </div>
    </div>
</div>

<div class="card" style="max-width: 900px; margin: 0 auto;">
    <div class="card-body" style="padding: 3rem;">
        <div style="text-align: center; margin-bottom: 2rem;">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 64px; height: 64px; color: var(--warning); margin: 0 auto 1rem;">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
            <h2 style="font-size: 1.5rem; font-weight: 600; margin-bottom: 0.5rem;">Database Tables Missing</h2>
            <p style="font-size: 1rem; color: var(--muted-foreground);">The invitation system requires database migrations to be run first.</p>
        </div>

        <div style="background: var(--muted); border-radius: 0.5rem; padding: 1.5rem; margin-bottom: 2rem;">
            <h3 style="font-size: 1.125rem; font-weight: 600; margin-bottom: 1rem;">Required Tables:</h3>
            <ul style="list-style: none; padding: 0; margin: 0;">
                <li style="padding: 0.5rem 0; display: flex; align-items: center; gap: 0.5rem;">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 20px; height: 20px; color: var(--danger);">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                    <code style="font-size: 0.875rem; padding: 0.25rem 0.5rem; background: var(--background); border-radius: 0.25rem;">invitation_links</code>
                </li>
                <li style="padding: 0.5rem 0; display: flex; align-items: center; gap: 0.5rem;">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 20px; height: 20px; color: var(--danger);">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                    <code style="font-size: 0.875rem; padding: 0.25rem 0.5rem; background: var(--background); border-radius: 0.25rem;">invitation_referrals</code>
                </li>
            </ul>
        </div>

        <div style="margin-bottom: 2rem;">
            <h3 style="font-size: 1.125rem; font-weight: 600; margin-bottom: 1rem;">How to Fix:</h3>
            <p style="font-size: 0.9375rem; color: var(--muted-foreground); margin-bottom: 1rem;">Run one of the following commands in your terminal:</p>
            
            <div style="margin-bottom: 1rem;">
                <div style="background: #1e1e1e; color: #d4d4d4; padding: 1rem; border-radius: 0.5rem; font-family: 'Monaco', 'Menlo', 'Courier New', monospace; font-size: 0.875rem; position: relative;">
                    <div style="color: #6a9955; margin-bottom: 0.5rem;"># Option 1: Run all pending migrations</div>
                    <code style="color: #ce9178;">php artisan migrate</code>
                    <button onclick="copyToClipboard('php artisan migrate')" style="position: absolute; top: 0.5rem; right: 0.5rem; background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.2); color: #d4d4d4; padding: 0.25rem 0.5rem; border-radius: 0.25rem; cursor: pointer; font-size: 0.75rem;" onmouseover="this.style.background='rgba(255,255,255,0.2)'" onmouseout="this.style.background='rgba(255,255,255,0.1)'">Copy</button>
                </div>
            </div>

            <div style="text-align: center; margin: 1rem 0; color: var(--muted-foreground); font-size: 0.875rem;">OR</div>

            <div style="margin-bottom: 1rem;">
                <div style="background: #1e1e1e; color: #d4d4d4; padding: 1rem; border-radius: 0.5rem; font-family: 'Monaco', 'Menlo', 'Courier New', monospace; font-size: 0.875rem; position: relative;">
                    <div style="color: #6a9955; margin-bottom: 0.5rem;"># Option 2: Run only Tyro Login migrations</div>
                    <code style="color: #ce9178;">php artisan migrate --path=vendor/hasinhayder/tyro-login/database/migrations</code>
                    <button onclick="copyToClipboard('php artisan migrate --path=vendor/hasinhayder/tyro-login/database/migrations')" style="position: absolute; top: 0.5rem; right: 0.5rem; background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.2); color: #d4d4d4; padding: 0.25rem 0.5rem; border-radius: 0.25rem; cursor: pointer; font-size: 0.75rem;" onmouseover="this.style.background='rgba(255,255,255,0.2)'" onmouseout="this.style.background='rgba(255,255,255,0.1)'">Copy</button>
                </div>
            </div>
        </div>

        <div style="background: #fffbeb; border: 1px solid #fbbf24; border-radius: 0.5rem; padding: 1rem; margin-bottom: 2rem;">
            <div style="display: flex; gap: 0.75rem;">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 24px; height: 24px; color: #f59e0b; flex-shrink: 0;">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <div>
                    <strong style="color: #92400e; font-size: 0.9375rem; display: block; margin-bottom: 0.25rem;">Note:</strong>
                    <p style="color: #78350f; font-size: 0.875rem; margin: 0;">After running the migrations, refresh this page to access the invitation system.</p>
                </div>
            </div>
        </div>

        <div style="text-align: center;">
            <a href="{{ route('tyro-dashboard.index') }}" class="btn btn-secondary">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Back to Dashboard
            </a>
            <button onclick="location.reload()" class="btn btn-primary" style="margin-left: 0.5rem;">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                </svg>
                Refresh Page
            </button>
        </div>
    </div>
</div>

<script>
function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(() => {
        alert('Command copied to clipboard!');
    }).catch(err => {
        console.error('Failed to copy:', err);
    });
}
</script>
@endsection
