@extends('tyro-dashboard::layouts.user')

@section('title', 'Maintenance Mode')

@section('breadcrumb')
<a href="{{ route('tyro-dashboard.index') }}">Dashboard</a>
<span class="breadcrumb-separator">/</span>
<span>Invitation System</span>
@endsection

@section('content')
<div class="page-header">
    <div class="page-header-row">
        <div>
            <h1 class="page-title">Invitation System</h1>
            <p class="page-description">Temporarily unavailable</p>
        </div>
    </div>
</div>

<div class="card" style="max-width: 700px; margin: 0 auto;">
    <div class="card-body" style="padding: 2.5rem;">
        <div style="margin-bottom: 2rem;">
            <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1rem;">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 40px; height: 40px; color: var(--warning); flex-shrink: 0;">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    <circle cx="12" cy="12" r="10" />
                </svg>
                <div>
                    <h2 style="font-size: 1.5rem; font-weight: 600; margin: 0; color: var(--foreground);">Under Maintenance</h2>
                    <p style="font-size: 0.9375rem; color: var(--muted-foreground); margin: 0.25rem 0 0 0;">The invitation system is temporarily unavailable</p>
                </div>
            </div>
            <p style="font-size: 1rem; color: var(--muted-foreground); font-weight: 400; line-height: 1.6; margin: 0;">
                We're currently updating the invitation system to serve you better. This feature will be available shortly.
            </p>
        </div>

        <div style="background: var(--muted); border-radius: 0.5rem; padding: 1.25rem; margin-bottom: 2rem;">
            <div style="display: flex; align-items: flex-start; gap: 0.75rem;">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 20px; height: 20px; color: var(--primary); flex-shrink: 0; margin-top: 0.125rem;">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <p style="color: var(--muted-foreground); font-size: 0.9375rem; font-weight: 400; margin: 0; line-height: 1.5;">
                    Our team is working to restore this feature. Thank you for your patience!
                </p>
            </div>
        </div>

        <div style="margin-top: 2rem; display: flex; gap: 0.5rem;">
            <a href="{{ route('tyro-dashboard.index') }}" class="btn btn-primary">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Back to Dashboard
            </a>
        </div>
    </div>
</div>
@endsection
