@extends($isAdmin ? 'tyro-dashboard::layouts.admin' : 'tyro-dashboard::layouts.user')

@section('title', 'My Profile')

@section('breadcrumb')
<a href="{{ route($dashboardRoute::name('index')) }}">Dashboard</a>
<span class="breadcrumb-separator">/</span>
<span>My Profile</span>
@endsection

@section('content')
<div class="page-header">
    <div class="page-header-row">
        <div>
            <h1 class="page-title">My Profile</h1>
            <p class="page-description">Manage your account settings and preferences.</p>
        </div>
    </div>
</div>

<div class="grid-2">
    <!-- Profile Information -->
    <div class="card" style="display: flex; flex-direction: column;">
        <div class="card-header">
            <h3 class="card-title">Profile Information</h3>
        </div>
        <form action="{{ route($dashboardRoute::name('profile.update')) }}" method="POST" style="flex: 1; display: flex; flex-direction: column;">
            @csrf
            @method('PUT')
            <div class="card-body" style="flex: 1;">
                @if((config('tyro-dashboard.features.profile_photo_upload') && method_exists($user, 'hasProfilePhotoColumn') && $user->hasProfilePhotoColumn()) || (config('tyro-dashboard.features.gravatar') && method_exists($user, 'hasGravatarColumn') && $user->hasGravatarColumn()))
                <div class="form-group">
                    @if(config('tyro-dashboard.features.profile_photo_upload') && method_exists($user, 'hasProfilePhotoColumn') && $user->hasProfilePhotoColumn())
                        <x-media-picker
                            name="profile_photo_url"
                            :value="$user->profile_photo_path ? $user->profile_photo_url : null"
                            preview="true"
                            preview-position="left"
                            preview-width="64px"
                            preview-height="64px"
                            button-text="Choose Photo"
                            output="webp"
                            circle="true"
                            label="Profile Photo"
                            width="100%"
                        />
                    @endif

                    @if(config('tyro-dashboard.features.gravatar') && method_exists($user, 'hasGravatarColumn') && $user->hasGravatarColumn())
                    <div class="form-check" style="display: flex; align-items: center; gap: 0.5rem; margin-top: 0.5rem;">
                        <input type="checkbox" id="use_gravatar" name="use_gravatar" value="1" {{ old('use_gravatar', $user->use_gravatar) ? 'checked' : '' }}>
                        <label for="use_gravatar" style="margin-bottom: 0;">Use Gravatar</label>
                    </div>
                    @endif
                </div>
                @endif
                <div class="form-group">
                    <label for="name" class="form-label">Name</label>
                    <input type="text" id="name" name="name" class="form-input @error('name') is-invalid @enderror" value="{{ old('name', $user->name) }}" required>
                    @error('name')
                        <span class="form-error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" id="email" name="email" class="form-input @error('email') is-invalid @enderror" value="{{ old('email', $user->email) }}" required>
                    @error('email')
                        <span class="form-error">{{ $message }}</span>
                    @enderror
                    {{-- 
                    @if($user->email_verified_at)
                        <span class="form-hint" style="color: var(--success);">
                            <svg style="width: 14px; height: 14px; display: inline-block; vertical-align: middle; margin-right: 4px;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Email verified on {{ $user->email_verified_at->format('M d, Y') }}
                        </span>
                    @else
                        <span class="form-hint" style="color: var(--warning);">Email not verified</span>
                    @endif
                    --}}
                </div>
            </div>
            <div class="card-footer" style="margin-top: auto;">
                <button type="submit" class="btn btn-primary">Save Changes</button>
            </div>
        </form>
    </div>

    <!-- Update Password -->
    <div class="card" style="display: flex; flex-direction: column;">
        <div class="card-header">
            <h3 class="card-title">Update Password</h3>
        </div>
        <form action="{{ route($dashboardRoute::name('profile.password')) }}" method="POST" style="flex: 1; display: flex; flex-direction: column;">
            @csrf
            @method('PUT')
            <div class="card-body" style="flex: 1;">
                <div class="form-group">
                    <label for="current_password" class="form-label">Current Password</label>
                    <input type="password" id="current_password" name="current_password" class="form-input @error('current_password') is-invalid @enderror" required>
                    @error('current_password')
                        <span class="form-error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password" class="form-label">New Password</label>
                    <input type="password" id="password" name="password" class="form-input @error('password') is-invalid @enderror" required>
                    @error('password')
                        <span class="form-error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password_confirmation" class="form-label">Confirm New Password</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" class="form-input" required>
                </div>
            </div>
            <div class="card-footer" style="margin-top: auto;">
                <button type="submit" class="btn btn-primary">Update Password</button>
            </div>
        </form>
    </div>
</div>

    <!-- Two-Factor Authentication -->
@if(config('tyro-login.two_factor.enabled'))
    <div class="card" style="margin-top: 1.5rem;">
        <div class="card-header">
            <h3 class="card-title">Two-Factor Authentication (2FA)</h3>
        </div>
        <div class="card-body">
            @if($user->two_factor_secret)
                <p style="margin-bottom: 1rem; color: var(--muted-foreground);">
                    Two-factor authentication is currently <strong>enabled</strong> for your account.
                </p>
                <form action="{{ route($dashboardRoute::name('profile.2fa.setup')) }}" method="POST" id="setup-profile-2fa-form">
                    @csrf
                    <button type="button" class="btn btn-warning" onclick="event.preventDefault(); showConfirm('Reset 2FA', 'Are you sure you want to reset your 2FA? You will need to set it up again.').then(confirmed => { if(confirmed) document.getElementById('setup-profile-2fa-form').submit(); })">
                        Reset 2FA Configuration
                    </button>
                </form>
            @else
                <p style="margin-bottom: 1rem; color: var(--muted-foreground);">
                    Two-factor authentication is currently <strong>disabled</strong> for your account.
                </p>
                <form method="POST" action="{{ route($dashboardRoute::name('profile.2fa.setup')) }}">
                    @csrf
                    <button type="submit" class="btn btn-primary">Setup 2FA</button>
                </form>
            @endif
        </div>
    </div>
    @endif

@if($passkeysAvailable ?? false)
<!-- Passkeys -->
<div class="card" style="margin-top: 1.5rem;">
    <div class="card-header" style="display:flex;align-items:center;justify-content:space-between;gap:1rem;flex-wrap:wrap;">
        <h3 class="card-title">Passkeys</h3>
        <button type="button" class="btn btn-primary btn-sm" id="add-passkey-btn">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:16px;height:16px;margin-right:0.35rem;vertical-align:middle;"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
            Add passkey
        </button>
    </div>
    <div class="card-body">
        <p style="margin-bottom: 1rem; color: var(--muted-foreground);">
            Passkeys let you sign in securely without a password, using your device's Face ID, Touch ID, or screen lock.
        </p>

        @forelse($passkeys as $pk)
            <div class="profile-passkey-row" style="display:flex;align-items:center;justify-content:space-between;gap:1rem;flex-wrap:wrap;padding:0.85rem 0;border-top:1px solid var(--border);">
                <div style="display:flex;align-items:center;gap:0.75rem;min-width:0;">
                    <span style="display:inline-flex;align-items:center;justify-content:center;width:38px;height:38px;flex-shrink:0;border-radius:50%;background:var(--muted);color:var(--foreground);">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:18px;height:18px;"><path stroke-linecap="round" stroke-linejoin="round" d="M21 2l-2 2m-7.61 7.61a5.5 5.5 0 11-7.778 7.778 5.5 5.5 0 017.777-7.777zm0 0L15.5 7.5m0 0l3 3L22 7l-3-3m-3.5 3.5L19 4"/></svg>
                    </span>
                    <div style="min-width:0;">
                        <p style="font-weight:600;margin-bottom:0.15rem;word-break:break-word;">
                            {{ $pk->name ?: 'Unnamed passkey' }}
                            @if($pk->authenticator)
                                <span class="badge badge-secondary" style="margin-left:0.35rem;">{{ $pk->authenticator }}</span>
                            @endif
                        </p>
                        <p style="font-size:0.8rem;color:var(--muted-foreground);margin:0;">
                            Added {{ $pk->created_at?->format('M j, Y') }}
                            · {{ $pk->last_used_at ? 'Last used '.$pk->last_used_at->format('M j, Y') : 'Never used' }}
                        </p>
                    </div>
                </div>
                <div style="display:flex;align-items:center;gap:0.5rem;flex-wrap:wrap;">
                    <button type="button" class="btn btn-secondary btn-sm" onclick="openRenamePasskeyModal('{{ $pk->getKey() }}', {{ json_encode($pk->name ?: 'Unnamed passkey') }})">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:16px;height:16px;margin-right:0.35rem;vertical-align:middle;">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        Rename
                    </button>
                    <form method="POST" action="{{ route($dashboardRoute::name('profile.passkeys.destroy'), ['id' => $pk->getKey()]) }}" id="remove-passkey-form-{{ $pk->getKey() }}">
                        @csrf
                        @method('DELETE')
                        <button type="button" class="btn btn-destructive" onclick="event.preventDefault(); showDanger('Remove Passkey', 'Are you sure you want to remove this passkey? You may be locked out if this is your only sign-in method.').then(confirmed => { if(confirmed) document.getElementById('remove-passkey-form-{{ $pk->getKey() }}').submit(); })">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                            Remove
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <p id="profile-passkeys-empty" style="padding:0.85rem 0;color:var(--muted-foreground);">You don&rsquo;t have any passkeys yet.</p>
        @endforelse
    </div>
</div>

<!-- Rename Passkey Modal -->
<div class="modal-overlay" id="renamePasskeyModal">
    <div class="modal">
        <div class="modal-header">
            <h3 class="modal-title">Rename Passkey</h3>
            <button type="button" class="modal-close" onclick="closeModal('renamePasskeyModal')">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        <form id="renamePasskeyForm" method="POST">
            @csrf
            @method('PUT')
            <div class="modal-body">
                <p style="margin-bottom: 1rem; color: var(--muted-foreground);">
                    Enter a new name for <strong id="renamePasskeyCurrentName"></strong>.
                </p>
                <div class="form-group">
                    <label for="renamePasskeyName" class="form-label">Passkey Name</label>
                    <input type="text" id="renamePasskeyName" name="name" class="form-input" placeholder="Passkey name" maxlength="255" required>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeModal('renamePasskeyModal')">Cancel</button>
                <button type="submit" class="btn btn-primary">Save</button>
            </div>
        </form>
    </div>
</div>
@endif

<!-- Account Information -->
<div class="card" style="margin-top: 1.5rem;">
    <div class="card-header">
        <h3 class="card-title">Account Information</h3>
    </div>
    <div class="card-body">
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.5rem;">
            <div>
                <label class="form-label" style="margin-bottom: 0.25rem;">Account ID</label>
                <p style="font-size: 0.875rem; color: var(--muted-foreground);">#{{ $user->id }}</p>
            </div>
            <div>
                <label class="form-label" style="margin-bottom: 0.25rem;">Member Since</label>
                <p style="font-size: 0.875rem; color: var(--muted-foreground);">{{ $user->created_at->format('F d, Y') }}</p>
            </div>
            @if(method_exists($user, 'roles') && $user->roles->count())
            <div>
                <label class="form-label" style="margin-bottom: 0.25rem;">Roles</label>
                <div class="badge-list">
                    @foreach($user->roles as $role)
                        <span class="badge badge-primary">{{ $role->name }}</span>
                    @endforeach
                </div>
            </div>
            @endif
            <div>
                <label class="form-label" style="margin-bottom: 0.25rem;">Status</label>
                <p>
                    @if(method_exists($user, 'isSuspended') && $user->isSuspended())
                        <span class="badge badge-danger">Suspended</span>
                    @else
                        <span class="badge badge-success">Active</span>
                    @endif
                </p>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
@if($passkeysAvailable ?? false)
<script>
    function openRenamePasskeyModal(passkeyId, currentName) {
        var form = document.getElementById('renamePasskeyForm');
        var actionTemplate = '{{ route($dashboardRoute::name('profile.passkeys.rename'), ['id' => '__ID__']) }}';
        form.action = actionTemplate.replace('__ID__', passkeyId);
        document.getElementById('renamePasskeyCurrentName').textContent = currentName || 'this passkey';
        var input = document.getElementById('renamePasskeyName');
        input.value = currentName && currentName !== 'Unnamed passkey' ? currentName : '';
        openModal('renamePasskeyModal');
        setTimeout(function () { input.focus(); input.select(); }, 50);
    }
</script>
<script type="module">
    (function () {
        var addBtn = document.getElementById('add-passkey-btn');
        if (!addBtn) return;

        var cdnUrl = @json($passkeyCdnUrl);
        var PasskeysLib = null;

        addBtn.addEventListener('click', async function () {
            var original = addBtn.innerHTML;
            try {
                if (!PasskeysLib) {
                    var mod = await import(cdnUrl);
                    PasskeysLib = mod.Passkeys || mod.default;
                }
                if (!PasskeysLib || typeof PasskeysLib.isSupported !== 'function' || !PasskeysLib.isSupported()) {
                    window.showToast && window.showToast('Passkeys are not supported in this browser.', 'error');
                    return;
                }
                addBtn.disabled = true;
                addBtn.textContent = 'Waiting…';
                var label = (navigator.userAgentData && navigator.userAgentData.platform) || navigator.platform || 'This device';
                await PasskeysLib.register({ name: label });
                window.showToast && window.showToast('Passkey added successfully.', 'success');
                setTimeout(function () { window.location.reload(); }, 700);
            } catch (e) {
                var name = (e && (e.name || (e.constructor && e.constructor.name))) || '';
                if (name === 'UserCancelledError' || name === 'AbortError') {
                    window.showToast && window.showToast('Passkey creation was cancelled.', 'error');
                } else if (name === 'PasskeyExistsError' || name === 'InvalidStateError') {
                    window.showToast && window.showToast('A passkey for this device already exists.', 'error');
                } else if (name === 'NotSupportedError' || name === 'InvalidDomainError' || name === 'SecurityError') {
                    window.showToast && window.showToast('Passkeys cannot be used here (requires HTTPS and a supported browser).', 'error');
                } else {
                    window.showToast && window.showToast((e && e.message) || 'Could not create the passkey. Please try again.', 'error');
                }
            } finally {
                addBtn.disabled = false;
                addBtn.innerHTML = original;
            }
        });
    })();
</script>
@endif
@endpush
