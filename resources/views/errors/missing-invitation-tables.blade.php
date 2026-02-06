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
            <h1 class="page-title">Database Migration Required</h1>
            <p class="page-description">Setup required for invitation system</p>
        </div>
    </div>
</div>

<div class="card" style="max-width: 900px; margin: 0 auto;">
    <div class="card-body" style="padding: 2.5rem;">
        <!-- Header Section -->
        <div style="margin-bottom: 2rem;">
            <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1rem;">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 48px; height: 48px; color: var(--warning); flex-shrink: 0;">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z" />
                </svg>
                <div>
                    <h2 style="font-size: 1.5rem; font-weight: 600; margin: 0; color: var(--foreground); line-height: 1.3;">Database Tables Missing</h2>
                    <p style="font-size: 0.9375rem; color: var(--muted-foreground); margin: 0.375rem 0 0 0;">Please run migrations to set up the invitation system</p>
                </div>
            </div>
        </div>

        <!-- Missing Tables Section -->
        <div style="background: var(--muted); border-radius: 0.5rem; padding: 1.25rem; margin-bottom: 2rem; border: 1px solid var(--border);">
            <h3 style="font-size: 0.875rem; font-weight: 600; margin: 0 0 1rem 0; color: var(--foreground); text-transform: uppercase; letter-spacing: 0.05em;">Required Tables</h3>
            <div style="display: flex; flex-direction: column; gap: 0.625rem;">
                <div style="display: flex; align-items: center; gap: 0.625rem; padding: 0.5rem; background: var(--background); border-radius: 0.375rem;">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 16px; height: 16px; color: var(--destructive); flex-shrink: 0;">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                    <code style="font-size: 0.875rem; font-weight: 500; color: var(--foreground); font-family: 'Monaco', 'Menlo', 'Courier New', monospace;">invitation_links</code>
                </div>
                <div style="display: flex; align-items: center; gap: 0.625rem; padding: 0.5rem; background: var(--background); border-radius: 0.375rem;">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 16px; height: 16px; color: var(--destructive); flex-shrink: 0;">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                    <code style="font-size: 0.875rem; font-weight: 500; color: var(--foreground); font-family: 'Monaco', 'Menlo', 'Courier New', monospace;">invitation_referrals</code>
                </div>
            </div>
        </div>

        <!-- Migration Instructions -->
        <div style="margin-bottom: 2rem;">
            <h3 style="font-size: 0.875rem; font-weight: 600; margin: 0 0 1rem 0; color: var(--foreground); text-transform: uppercase; letter-spacing: 0.05em;">Migration Options</h3>
            <p style="font-size: 0.9375rem; color: var(--muted-foreground); margin: 0 0 1.25rem 0; line-height: 1.6;">Choose one of the following commands to create the required database tables:</p>
            
            <!-- Option 1 -->
            <div style="margin-bottom: 1.25rem;">
                <div style="background: var(--muted); padding: 0.75rem 1rem; border-radius: 0.5rem 0.5rem 0 0; border: 1px solid var(--border); border-bottom: none;">
                    <div style="display: flex; align-items: center; gap: 0.5rem;">
                        <span style="display: inline-flex; align-items: center; justify-content: center; width: 20px; height: 20px; background: var(--primary); color: white; border-radius: 50%; font-size: 0.75rem; font-weight: 600;">1</span>
                        <span style="font-size: 0.875rem; font-weight: 500; color: var(--foreground);">Run all pending migrations</span>
                    </div>
                </div>
                <div style="background: #1e1e1e; color: #d4d4d4; padding: 1rem 1.25rem; border-radius: 0 0 0.5rem 0.5rem; font-family: 'Monaco', 'Menlo', 'Courier New', monospace; font-size: 0.875rem; position: relative; border: 1px solid var(--border); border-top: none;">
                    <code style="color: #4ec9b0; display: block; line-height: 1.6;">php artisan migrate</code>
                    <button onclick="copyToClipboard('php artisan migrate', this)" class="copy-btn">Copy</button>
                </div>
            </div>

            <div style="text-align: center; margin: 1.25rem 0; position: relative;">
                <span style="background: var(--background); padding: 0 1rem; color: var(--muted-foreground); font-size: 0.8125rem; font-weight: 500; position: relative; z-index: 1;">OR</span>
                <div style="position: absolute; top: 50%; left: 0; right: 0; height: 1px; background: var(--border); z-index: 0;"></div>
            </div>

            <!-- Option 2 -->
            <div style="margin-bottom: 1.25rem;">
                <div style="background: var(--muted); padding: 0.75rem 1rem; border-radius: 0.5rem 0.5rem 0 0; border: 1px solid var(--border); border-bottom: none;">
                    <div style="display: flex; align-items: center; gap: 0.5rem;">
                        <span style="display: inline-flex; align-items: center; justify-content: center; width: 20px; height: 20px; background: var(--primary); color: white; border-radius: 50%; font-size: 0.75rem; font-weight: 600;">2</span>
                        <span style="font-size: 0.875rem; font-weight: 500; color: var(--foreground);">Run only Tyro Login migrations</span>
                    </div>
                </div>
                <div style="background: #1e1e1e; color: #d4d4d4; padding: 1rem 1.25rem; border-radius: 0 0 0.5rem 0.5rem; font-family: 'Monaco', 'Menlo', 'Courier New', monospace; font-size: 0.875rem; position: relative; border: 1px solid var(--border); border-top: none;">
                    <code style="color: #4ec9b0; display: block; line-height: 1.6; word-break: break-all;">php artisan migrate --path=vendor/hasinhayder/tyro-login/database/migrations</code>
                    <button onclick="copyToClipboard('php artisan migrate --path=vendor/hasinhayder/tyro-login/database/migrations', this)" class="copy-btn">Copy</button>
                </div>
            </div>
        </div>

        <!-- Info Box -->
        <div style="background: var(--muted); border-radius: 0.5rem; padding: 1.25rem; margin-bottom: 2rem; border: 1px solid var(--border);">
            <div style="display: flex; gap: 0.75rem; align-items: flex-start;">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 20px; height: 20px; color: var(--primary); flex-shrink: 0; margin-top: 0.125rem;">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <p style="color: var(--muted-foreground); font-size: 0.9375rem; font-weight: 400; margin: 0; line-height: 1.6;">
                    After running the migrations successfully, refresh this page to access the invitation system.
                </p>
            </div>
        </div>

        <!-- Action Buttons -->
        <div style="display: flex; gap: 0.75rem; justify-content: center; flex-wrap: wrap;">
            <a href="{{ route('tyro-dashboard.index') }}" class="btn btn-secondary">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Back to Dashboard
            </a>
            <button onclick="location.reload()" class="btn btn-primary">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                </svg>
                Refresh Page
            </button>
        </div>
    </div>
</div>

<style>
    .copy-btn {
        position: absolute;
        top: 0.75rem;
        right: 1rem;
        background: rgba(255, 255, 255, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.2);
        color: #d4d4d4;
        padding: 0.375rem 0.75rem;
        border-radius: 0.375rem;
        cursor: pointer;
        font-size: 0.8125rem;
        font-weight: 500;
        transition: all 0.2s ease;
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
    }

    .copy-btn:hover {
        background: rgba(255, 255, 255, 0.2);
        border-color: rgba(255, 255, 255, 0.3);
    }

    .copy-btn:active {
        background: rgba(255, 255, 255, 0.15);
        transform: scale(0.98);
    }

    .copy-btn.copied {
        background: rgba(34, 197, 94, 0.2);
        border-color: rgba(34, 197, 94, 0.4);
        color: #4ade80;
    }

    @media (max-width: 768px) {
        .card {
            max-width: 100% !important;
        }
        
        .card-body {
            padding: 1.5rem !important;
        }

        .copy-btn {
            position: static;
            display: block;
            width: 100%;
            margin-top: 0.75rem;
        }

        code {
            word-break: break-all;
        }
    }
</style>

<script>
function copyToClipboard(text, button) {
    navigator.clipboard.writeText(text).then(() => {
        const originalText = button.textContent;
        button.textContent = 'Copied';
        button.classList.add('copied');
        
        setTimeout(() => {
            button.textContent = originalText;
            button.classList.remove('copied');
        }, 2000);
    }).catch(err => {
        console.error('Failed to copy:', err);
        button.textContent = 'Failed';
        setTimeout(() => {
            button.textContent = 'Copy';
        }, 2000);
    });
}
</script>
@endsection
