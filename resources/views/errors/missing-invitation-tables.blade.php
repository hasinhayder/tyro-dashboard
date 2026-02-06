@php
    $isAdmin = false;
    if (auth()->user() && method_exists(auth()->user(), 'hasRole')) {
        $adminRoles = config('tyro-dashboard.admin_roles', ['admin', 'super-admin']);
        foreach ($adminRoles as $role) {
            if (auth()->user()->hasRole($role)) {
                $isAdmin = true;
                break;
            }
        }
    }
    $layoutType = $isAdmin ? 'admin' : 'user';
@endphp

@extends('tyro-dashboard::layouts.' . $layoutType)

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
    <div class="card-body" style="padding: 2.5rem;">
        <div style="margin-bottom: 2rem;">
            <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1rem;">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 40px; height: 40px; color: var(--warning); flex-shrink: 0;">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
                <div>
                    <h2 style="font-size: 1.5rem; font-weight: 600; margin: 0; color: var(--foreground);">Database Migration Required</h2>
                    <p style="font-size: 0.9375rem; color: var(--muted-foreground); margin: 0.25rem 0 0 0;">The invitation system requires database setup</p>
                </div>
            </div>
        </div>

        <div style="background: var(--muted); border-radius: 0.5rem; padding: 1.25rem; margin-bottom: 2rem;">
            <h3 style="font-size: 1rem; font-weight: 600; margin: 0 0 0.75rem 0; color: var(--foreground);">Missing Tables:</h3>
            <ul style="list-style: none; padding: 0; margin: 0; display: flex; flex-direction: column; gap: 0.5rem;">
                <li style="display: flex; align-items: center; gap: 0.5rem;">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 18px; height: 18px; color: var(--danger); flex-shrink: 0;">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                    <code style="font-size: 0.875rem; padding: 0.25rem 0.5rem; background: var(--background); border-radius: 0.25rem;">invitation_links</code>
                </li>
                <li style="display: flex; align-items: center; gap: 0.5rem;">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 18px; height: 18px; color: var(--danger); flex-shrink: 0;">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                    <code style="font-size: 0.875rem; padding: 0.25rem 0.5rem; background: var(--background); border-radius: 0.25rem;">invitation_referrals</code>
                </li>
            </ul>
        </div>

        <div style="margin-bottom: 2rem;">
            <h3 style="font-size: 1rem; font-weight: 600; margin: 0 0 0.75rem 0; color: var(--foreground);">How to Fix:</h3>
            <p style="font-size: 0.9375rem; color: var(--muted-foreground); margin: 0 0 1rem 0;">Run one of the following commands in your terminal:</p>
            
            <div style="margin-bottom: 1rem;">
                <div style="background: #1e1e1e; color: #d4d4d4; padding: 1rem; border-radius: 0.5rem; font-family: 'Monaco', 'Menlo', 'Courier New', monospace; font-size: 0.875rem; position: relative;">
                    <div style="color: #6a9955; margin-bottom: 0.5rem;"># Option 1: Run all pending migrations</div>
                    <code style="color: #ce9178;">php artisan migrate</code>
                    <button onclick="copyToClipboard('php artisan migrate', this)" style="position: absolute; top: 0.5rem; right: 0.5rem; background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.2); color: #d4d4d4; padding: 0.25rem 0.5rem; border-radius: 0.25rem; cursor: pointer; font-size: 0.75rem;" onmouseover="this.style.background='rgba(255,255,255,0.2)'" onmouseout="this.style.background='rgba(255,255,255,0.1)'">Copy</button>
                </div>
            </div>

            <div style="text-align: center; margin: 1rem 0; color: var(--muted-foreground); font-size: 0.875rem;">OR</div>

            <div style="margin-bottom: 1rem;">
                <div style="background: #1e1e1e; color: #d4d4d4; padding: 1rem; border-radius: 0.5rem; font-family: 'Monaco', 'Menlo', 'Courier New', monospace; font-size: 0.875rem; position: relative;">
                    <div style="color: #6a9955; margin-bottom: 0.5rem;"># Option 2: Run only Tyro Login migrations</div>
                    <code style="color: #ce9178;">php artisan migrate --path=vendor/hasinhayder/tyro-login/database/migrations</code>
                    <button onclick="copyToClipboard('php artisan migrate --path=vendor/hasinhayder/tyro-login/database/migrations', this)" style="position: absolute; top: 0.5rem; right: 0.5rem; background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.2); color: #d4d4d4; padding: 0.25rem 0.5rem; border-radius: 0.25rem; cursor: pointer; font-size: 0.75rem;" onmouseover="this.style.background='rgba(255,255,255,0.2)'" onmouseout="this.style.background='rgba(255,255,255,0.1)'">Copy</button>
                </div>
            </div>
        </div>

        <div style="background: #fffbeb; border: 1px solid #fbbf24; border-radius: 0.5rem; padding: 1rem; margin-bottom: 1.5rem;">
            <div style="display: flex; gap: 0.75rem; align-items: flex-start;">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 20px; height: 20px; color: #f59e0b; flex-shrink: 0; margin-top: 0.125rem;">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <div>
                    <strong style="color: #92400e; font-size: 0.9375rem; font-weight: 600; display: block; margin-bottom: 0.25rem;">Note:</strong>
                    <p style="color: #78350f; font-size: 0.875rem; font-weight: 400; margin: 0; line-height: 1.5;">After running the migrations, refresh this page to access the invitation system.</p>
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
function copyToClipboard(text, button) {
    navigator.clipboard.writeText(text).then(() => {
        const originalText = button.textContent;
        button.textContent = 'Copied';
        setTimeout(() => {
            button.textContent = originalText;
        }, 2000);
    }).catch(err => {
        console.error('Failed to copy:', err);
    });
}
</script>
@endsection
