@extends('tyro-dashboard::layouts.admin')

@section('title', 'Edit User')

@section('breadcrumb')
<a href="{{ route('tyro-dashboard.index') }}">Dashboard</a>
<span class="breadcrumb-separator">/</span>
<a href="{{ route('tyro-dashboard.users.index') }}">Users</a>
<span class="breadcrumb-separator">/</span>
<span>Edit</span>
@endsection

@section('content')
<div class="page-header">
    <div class="page-header-row">
        <div>
            <h1 class="page-title">Edit User</h1>
            <p class="page-description">Update user information and roles.</p>
        </div>
        <a href="{{ route('tyro-dashboard.users.index') }}" class="btn btn-secondary">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Back to Users
        </a>
    </div>
</div>

<div class="grid-2">
    <!-- User Information -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">User Information</h3>
        </div>
        <form action="{{ route('tyro-dashboard.users.update', $editUser->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="card-body">
                <div class="form-group">
                    <label for="name" class="form-label">Name</label>
                    <input type="text" id="name" name="name" class="form-input @error('name') is-invalid @enderror" value="{{ old('name', $editUser->name) }}" required>
                    @error('name')
                        <span class="form-error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" id="email" name="email" class="form-input @error('email') is-invalid @enderror" value="{{ old('email', $editUser->email) }}" required>
                    @error('email')
                        <span class="form-error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password" class="form-label">
                        New Password <span class="form-label-optional">(leave blank to keep current)</span>
                    </label>
                    <input type="password" id="password" name="password" class="form-input @error('password') is-invalid @enderror" placeholder="••••••••">
                    @error('password')
                        <span class="form-error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password_confirmation" class="form-label">Confirm New Password</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" class="form-input" placeholder="••••••••">
                </div>

                <div class="form-group">
                    <label class="form-label">Assign Roles</label>
                    <div class="checkbox-list">
                        @foreach($roles as $role)
                        <label class="checkbox-item">
                            <input type="checkbox" name="roles[]" value="{{ $role->id }}" class="checkbox-input" {{ in_array($role->id, old('roles', $editUser->roles->pluck('id')->toArray())) ? 'checked' : '' }}>
                            <div class="checkbox-item-content">
                                <div class="checkbox-item-title">{{ $role->name }}</div>
                                <div class="checkbox-item-description">{{ $role->slug }}</div>
                            </div>
                        </label>
                        @endforeach
                    </div>
                    @error('roles')
                        <span class="form-error">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            <div class="card-footer" style="display: flex; gap: 0.75rem;">
                <button type="submit" class="btn btn-primary">Save Changes</button>
                <a href="{{ route('tyro-dashboard.users.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>

    <!-- Account Status -->
    <div>
        <div class="card" style="margin-bottom: 1rem;">
            <div class="card-header">
                <h3 class="card-title">Account Status</h3>
            </div>
            <div class="card-body">
                <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1rem;">
                    <div class="user-cell-avatar" style="width: 48px; height: 48px; font-size: 1.25rem;">
                        {{ strtoupper(substr($editUser->name, 0, 1)) }}
                    </div>
                    <div>
                        <div style="font-weight: 600; color: var(--foreground);">{{ $editUser->name }}</div>
                        <div style="font-size: 0.875rem; color: var(--muted-foreground);">Member since {{ $editUser->created_at->format('M d, Y') }}</div>
                    </div>
                </div>

                <div style="padding: 1rem; background-color: var(--muted); border-radius: 0.5rem;">
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <span style="font-size: 0.875rem; color: var(--muted-foreground);">Status</span>
                        @if(method_exists($editUser, 'isSuspended') && $editUser->isSuspended())
                            <span class="badge badge-danger">Suspended</span>
                        @else
                            <span class="badge badge-success">Active</span>
                        @endif
                    </div>
                    @if(method_exists($editUser, 'isSuspended') && $editUser->isSuspended() && method_exists($editUser, 'getSuspensionReason') && $editUser->getSuspensionReason())
                        <div style="margin-top: 0.75rem; padding-top: 0.75rem; border-top: 1px solid var(--border);">
                            <span style="font-size: 0.75rem; color: var(--muted-foreground);">Suspension Reason:</span>
                            <p style="font-size: 0.875rem; color: var(--muted-foreground); margin-top: 0.25rem;">{{ $editUser->getSuspensionReason() }}</p>
                        </div>
                    @endif
                </div>

                <!-- 2FA Status & Reset -->
                @if(config('tyro-login.two_factor.enabled'))
                <div style="margin-top: 1rem; padding-top: 1rem; border-top: 1px solid var(--border);">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.75rem;">
                        <span style="font-size: 0.875rem; color: var(--muted-foreground);">Two-Factor Authentication</span>
                        @if($editUser->two_factor_secret)
                            <span class="badge badge-success">Enabled</span>
                        @else
                            <span class="badge badge-secondary">Disabled</span>
                        @endif
                    </div>
                    @if($editUser->two_factor_secret)
                        <form action="{{ route('tyro-dashboard.users.2fa.reset', $editUser->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-warning" style="width: 100%;" onclick="return confirm('Are you sure you want to reset 2FA for this user?')">
                                Reset 2FA
                            </button>
                        </form>
                    @endif
                </div>
                @endif

                <div style="margin-top: 1rem; padding-top: 1rem; border-top: 1px solid var(--border);">
                    @if(method_exists($editUser, 'isSuspended') && $editUser->isSuspended())
                        <form action="{{ route('tyro-dashboard.users.unsuspend', $editUser->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-success" style="width: 100%;">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Unsuspend User
                            </button>
                        </form>
                    @elseif($editUser->id !== $user->id)
                        <button type="button" class="btn btn-warning" style="width: 100%;" onclick="openSuspendModal()">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                            </svg>
                            Suspend User
                        </button>
                    @endif
                </div>
            </div>
        </div>

        @if($editUser->id !== $user->id)
        <div class="card" style="border-color: var(--destructive);">
            <div class="card-header" style="background-color: color-mix(in srgb, var(--destructive), transparent 90%);">
                <h3 class="card-title" style="color: var(--destructive);">Danger Zone</h3>
            </div>
            <div class="card-body">
                <p style="font-size: 0.875rem; color: var(--muted-foreground); margin-bottom: 1rem;">
                    Once you delete a user, there is no going back. Please be certain.
                </p>
                <form action="{{ route('tyro-dashboard.users.destroy', $editUser->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger" style="width: 100%;" onclick="return confirm('Are you sure you want to delete this user? This action cannot be undone.')">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                        Delete User
                    </button>
                </form>
            </div>
        </div>
        @endif
    </div>
</div>

<!-- Suspend Modal -->
<div class="modal-overlay" id="suspendModal">
    <div class="modal">
        <div class="modal-header">
            <h3 class="modal-title">Suspend User</h3>
            <button type="button" class="modal-close" onclick="closeModal('suspendModal')">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        <form action="{{ route('tyro-dashboard.users.suspend', $editUser->id) }}" method="POST">
            @csrf
            <div class="modal-body">
                <p style="margin-bottom: 1rem; color: var(--muted-foreground);">
                    You are about to suspend <strong>{{ $editUser->name }}</strong>. This will revoke all their active sessions.
                </p>
                <div class="form-group">
                    <label for="reason" class="form-label">
                        Reason for suspension <span class="form-label-optional">(optional)</span>
                    </label>
                    <textarea id="reason" name="reason" class="form-textarea" rows="3" placeholder="Enter a reason for suspension..."></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeModal('suspendModal')">Cancel</button>
                <button type="submit" class="btn btn-danger">Suspend User</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    function openSuspendModal() {
        openModal('suspendModal');
    }
</script>
@endpush
@endsection
