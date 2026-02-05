@extends('tyro-dashboard::layouts.admin')

@section('title', 'Create Invitation Link')

@section('breadcrumb')
<a href="{{ route('tyro-dashboard.index') }}">Dashboard</a>
<span class="breadcrumb-separator">/</span>
<a href="{{ route('tyro-dashboard.invitations.admin.index') }}">Invitation Links</a>
<span class="breadcrumb-separator">/</span>
<span>Create</span>
@endsection

@section('content')
<div class="page-header">
    <div class="page-header-row">
        <div>
            <h1 class="page-title">Create Invitation Link</h1>
            <p class="page-description">Generate an invitation link for a user.</p>
        </div>
    </div>
</div>

<div class="grid-2">
    <div>
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Invitation Details</h3>
            </div>
            <div class="card-body">
                <form action="{{ route('tyro-dashboard.invitations.admin.store') }}" method="POST">
                    @csrf

                    <div class="form-group">
                        <label for="user_id" class="form-label">Select User</label>
                        <select name="user_id" id="user_id" class="form-select @error('user_id') is-invalid @enderror" required>
                            <option value="">Choose a user...</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }} ({{ $user->email }})
                                </option>
                            @endforeach
                        </select>
                        @error('user_id')
                            <span class="form-error">{{ $message }}</span>
                        @enderror
                        <small class="form-hint">Select the user who will receive this invitation link.</small>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                            </svg>
                            Create Invitation Link
                        </button>
                        <a href="{{ route('tyro-dashboard.invitations.admin.index') }}" class="btn btn-ghost">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div>
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">About Invitation Links</h3>
            </div>
            <div class="card-body">
                <div class="info-list" style="display: flex; flex-direction: column; gap: 1.5rem;">
                    <div style="display: flex; gap: 1rem; align-items: flex-start;">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 24px; height: 24px; flex-shrink: 0; color: var(--primary); margin-top: 0.125rem;">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <div>
                            <strong style="font-size: 0.9375rem; display: block; margin-bottom: 0.25rem;">One Link Per User</strong>
                            <p style="font-size: 0.875rem; color: var(--muted-foreground); margin: 0;">Each user can have only one invitation link. If a user already has a link, you cannot create another one.</p>
                        </div>
                    </div>
                    <div style="display: flex; gap: 1rem; align-items: flex-start;">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 24px; height: 24px; flex-shrink: 0; color: var(--primary); margin-top: 0.125rem;">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <div>
                            <strong style="font-size: 0.9375rem; display: block; margin-bottom: 0.25rem;">Automatic Tracking</strong>
                            <p style="font-size: 0.875rem; color: var(--muted-foreground); margin: 0;">All signups through this invitation link will be automatically tracked.</p>
                        </div>
                    </div>
                    <div style="display: flex; gap: 1rem; align-items: flex-start;">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 24px; height: 24px; flex-shrink: 0; color: var(--primary); margin-top: 0.125rem;">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                        <div>
                            <strong style="font-size: 0.9375rem; display: block; margin-bottom: 0.25rem;">Unique Hash</strong>
                            <p style="font-size: 0.875rem; color: var(--muted-foreground); margin: 0;">Each invitation link has a unique 32-character hash that cannot be changed.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
