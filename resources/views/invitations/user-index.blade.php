@extends('tyro-dashboard::layouts.user')

@section('title', 'My Invitation Link')

@section('breadcrumb')
<a href="{{ route('tyro-dashboard.index') }}">Dashboard</a>
<span class="breadcrumb-separator">/</span>
<span>My Invitation Link</span>
@endsection

@section('content')
<div class="page-header">
    <div class="page-header-row">
        <div>
            <h1 class="page-title">My Invitation Link</h1>
            <p class="page-description">Share your invitation link and track referrals.</p>
        </div>
    </div>
</div>

<div class="grid-2">
    <!-- Invitation Link Card -->
    <div>
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Your Invitation Link</h3>
            </div>
            <div class="card-body">
                @if($invitationLink)
                <div style="margin-bottom: 1.5rem;">
                    <label class="form-label">Invitation URL</label>
                    <div style="display: flex; gap: 0.5rem;">
                        <input type="text" class="form-input" id="invitation-url" value="{{ url('/register?invite=' . $invitationLink->hash) }}" readonly style="font-family: monospace; font-size: 0.875rem;">
                        <button onclick="copyInvitationLink()" class="btn btn-secondary" title="Copy to clipboard">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                            </svg>
                        </button>
                    </div>
                    <small class="form-hint">Share this link with others to invite them to register.</small>
                </div>

                <div style="margin-bottom: 1rem;">
                    <label class="form-label">Invitation Hash</label>
                    <code style="display: block; padding: 0.75rem; background: var(--muted); border-radius: 0.5rem; font-size: 0.875rem;">{{ $invitationLink->hash }}</code>
                </div>

                <div>
                    <label class="form-label">Statistics</label>
                    <div class="stats-grid" style="grid-template-columns: repeat(2, 1fr);">
                        <div class="stat-card">
                            <div class="stat-content">
                                <div class="stat-label">Total Referrals</div>
                                <div class="stat-value">{{ $referrals->count() }}</div>
                            </div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-content">
                                <div class="stat-label">Created</div>
                                <div class="stat-value" style="font-size: 1.25rem;">{{ $invitationLink->created_at->format('M d, Y') }}</div>
                            </div>
                        </div>
                    </div>
                </div>
                @else
                <div class="empty-state" style="padding: 2rem 0;">
                    <div class="empty-state-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
                        </svg>
                    </div>
                    <h3 class="empty-state-title">No Invitation Link Yet</h3>
                    <p class="empty-state-description">Create your unique invitation link to start inviting others.</p>
                    <form action="{{ route('tyro-dashboard.invitations.create') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-primary">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                            Create My Invitation Link
                        </button>
                    </form>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Referrals List -->
    <div>
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">My Referrals</h3>
            </div>
            <div class="card-body" style="padding: 0;">
                @if($referrals->count())
                <div class="table-container">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>User</th>
                                <th>Email</th>
                                <th>Joined</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($referrals as $referral)
                            <tr>
                                <td>
                                    <div class="user-cell">
                                            <div class="user-cell-avatar" style="{{ ($referral->referredUser->profile_photo_path || $referral->referredUser->use_gravatar) ? 'background: none; padding: 0;' : '' }}">
                                                @if($referral->referredUser->profile_photo_path || ($referral->referredUser->use_gravatar && $referral->referredUser->email))
                                                    <img src="{{ $referral->referredUser->profile_photo_url }}" alt="{{ $referral->referredUser->name }}" style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;">
                                                @else
                                                    {{ strtoupper(substr($referral->referredUser->name ?? 'U', 0, 1)) }}
                                                @endif
                                            </div>
                                        <div class="user-cell-info">
                                            <div class="user-cell-name">{{ $referral->referredUser->name ?? 'Unknown' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $referral->referredUser->email ?? 'N/A' }}</td>
                                <td>
                                    <span style="font-size: 0.875rem;">{{ $referral->created_at->format('M d, Y') }}</span>
                                    <br>
                                    <span style="font-size: 0.75rem; color: var(--muted-foreground);">{{ $referral->created_at->diffForHumans() }}</span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="empty-state">
                    <div class="empty-state-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                    <h3 class="empty-state-title">No Referrals Yet</h3>
                    <p class="empty-state-description">Share your invitation link to see referrals here.</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
function copyInvitationLink() {
    const input = document.getElementById('invitation-url');
    input.select();
    navigator.clipboard.writeText(input.value).then(() => {
        showSuccess('Invitation link copied to clipboard!');
    }).catch(err => {
        console.error('Failed to copy:', err);
    });
}
</script>
@endsection
