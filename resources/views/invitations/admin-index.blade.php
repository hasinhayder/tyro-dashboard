@extends('tyro-dashboard::layouts.admin')

@section('title', 'Invitation Links')

@section('breadcrumb')
<a href="{{ route('tyro-dashboard.index') }}">Dashboard</a>
<span class="breadcrumb-separator">/</span>
<span>Invitation Links</span>
@endsection

@section('content')
<div class="page-header">
    <div class="page-header-row">
        <div>
            <h1 class="page-title">Invitation Links</h1>
            <p class="page-description">Manage user invitation and referral links.</p>
        </div>
        <a href="{{ route('tyro-dashboard.invitations.admin.create') }}" class="btn btn-primary">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
            </svg>
            Create Invitation Link
        </a>
    </div>
</div>

<!-- Search -->
<div class="card" style="margin-bottom: 1rem;">
    <div class="card-body">
        <form action="{{ route('tyro-dashboard.invitations.admin.index') }}" method="GET">
            <div class="filters-bar">
                <div class="search-box">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    <input type="text" name="search" class="form-input" placeholder="Search by user name, email, or hash..." value="{{ request('search') }}">
                </div>
                <button type="submit" class="btn btn-secondary">Search</button>
                @if(request('search'))
                    <a href="{{ route('tyro-dashboard.invitations.admin.index') }}" class="btn btn-ghost">Clear</a>
                @endif
            </div>
        </form>
    </div>
</div>

<!-- Invitation Links Table -->
<div class="card">
    <div class="card-body" style="padding: 0;">
        @if($links->count())
        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th style="width: 25%;">User</th>
                        <th style="width: 20%;">Email</th>
                        <th style="width: 15%; text-align: center;">Referrals</th>
                        <th style="width: 20%;">Created</th>
                        <th style="width: 20%; text-align: right;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($links as $link)
                    <tr>
                        <td>
                            <div class="user-cell">
                                <div class="user-cell-avatar" style="{{ ($link->user->profile_photo_path || $link->user->use_gravatar) ? 'background: none; padding: 0;' : '' }}">
                                    @if($link->user->profile_photo_path || ($link->user->use_gravatar && $link->user->email))
                                        <img src="{{ $link->user->profile_photo_url }}" alt="{{ $link->user->name }}" style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;">
                                    @else
                                        {{ strtoupper(substr($link->user->name ?? 'U', 0, 1)) }}
                                    @endif
                                </div>
                                <div class="user-cell-info">
                                    <div class="user-cell-name">{{ $link->user->name ?? 'Unknown' }}</div>
                                </div>
                            </div>
                        </td>
                        <td>{{ $link->user->email ?? 'N/A' }}</td>
                        <td style="text-align: center;">
                            <span class="badge badge-primary">{{ $link->referrals->count() }}</span>
                        </td>
                        <td>
                            <span style="font-size: 0.875rem;">{{ $link->created_at->format('M d, Y') }}</span>
                            <br>
                            <span style="font-size: 0.75rem; color: var(--muted-foreground);">{{ $link->created_at->format('h:i A') }}</span>
                        </td>
                        <td style="text-align: right;">
                            <div class="btn-group">
                                <button onclick="copyToClipboard('{{ url('/register?invite=' . $link->hash) }}')" class="btn btn-sm btn-secondary" title="Copy invitation URL">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                    </svg>
                                </button>
                                <form action="{{ route('tyro-dashboard.invitations.admin.destroy', $link->id) }}" method="POST" style="display: inline;" id="delete-invitation-form-{{ $link->id }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btn btn-sm btn-danger" title="Delete invitation link" onclick="event.preventDefault(); showDanger('Delete Invitation Link', 'Are you sure you want to delete this invitation link?{{ $link->referrals->count() > 0 ? ' This will also remove ' . $link->referrals->count() . ' referral record(s).' : '' }}').then(confirmed => { if(confirmed) document.getElementById('delete-invitation-form-{{ $link->id }}').submit(); })">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
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

        <!-- Pagination -->
        @if($links->hasPages())
        <div class="pagination-container">
            {{ $links->links() }}
        </div>
        @endif

        @else
        <div class="empty-state">
            <div class="empty-state-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                </svg>
            </div>
            <h3 class="empty-state-title">No invitation links found</h3>
            <p class="empty-state-description">Create an invitation link to get started with referrals.</p>
            <a href="{{ route('tyro-dashboard.invitations.admin.create') }}" class="btn btn-primary">Create Invitation Link</a>
        </div>
        @endif
    </div>
</div>

<script>
function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(() => {
        showSuccess('Invitation link copied to clipboard!');
    }).catch(err => {
        console.error('Failed to copy:', err);
    });
}
</script>
@endsection
