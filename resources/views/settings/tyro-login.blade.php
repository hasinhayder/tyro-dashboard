@extends('tyro-dashboard::layouts.app')

@section('title', 'Tyro Login Settings')

@section('breadcrumb')
<a href="{{ route('tyro-dashboard.index') }}">Dashboard</a>
<span class="breadcrumb-separator">/</span>
<span>Settings</span>
<span class="breadcrumb-separator">/</span>
<span>Tyro Login</span>
@endsection

@section('content')
<div class="page-header">
    <div class="page-header-row">
        <div>
            <h1 class="page-title">Tyro Login Settings</h1>
            <p class="page-description">Configure authentication and login UI settings.</p>
        </div>
        <div>
            <span class="badge badge-primary">v{{ config('tyro-login.version', '1.0.0') }}</span>
        </div>
    </div>
</div>

<div class="settings-nav">
    <a href="{{ route('tyro-dashboard.settings.tyro') }}" class="settings-nav-item">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
        </svg>
        Tyro RBAC
    </a>
    <a href="{{ route('tyro-dashboard.settings.tyro-login') }}" class="settings-nav-item active">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
        </svg>
        Tyro Login
    </a>
</div>

<form action="{{ route('tyro-dashboard.settings.tyro-login.update') }}" method="POST">
    @csrf
    @method('PUT')
    
    <!-- Branding Settings -->
    <div class="card" style="margin-bottom: 1.5rem;">
        <div class="card-header">
            <h3 class="card-title">Branding</h3>
        </div>
        <div class="card-body">
            <div class="form-row">
                <div class="form-group">
                    <label for="branding_app_name" class="form-label">Application Name</label>
                    <input type="text" id="branding_app_name" name="branding[app_name]" class="form-input @error('branding.app_name') is-invalid @enderror" value="{{ old('branding.app_name', $config['branding']['app_name'] ?? config('app.name')) }}">
                    <span class="form-hint">Displayed on login pages.</span>
                    @error('branding.app_name')
                        <span class="form-error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="branding_logo_url" class="form-label">Logo URL</label>
                    <input type="text" id="branding_logo_url" name="branding[logo_url]" class="form-input @error('branding.logo_url') is-invalid @enderror" value="{{ old('branding.logo_url', $config['branding']['logo_url'] ?? '') }}" placeholder="/images/logo.svg">
                    <span class="form-hint">Leave empty to show app name as text.</span>
                    @error('branding.logo_url')
                        <span class="form-error">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="branding_favicon_url" class="form-label">Favicon URL</label>
                    <input type="text" id="branding_favicon_url" name="branding[favicon_url]" class="form-input @error('branding.favicon_url') is-invalid @enderror" value="{{ old('branding.favicon_url', $config['branding']['favicon_url'] ?? '') }}" placeholder="/favicon.ico">
                    @error('branding.favicon_url')
                        <span class="form-error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="branding_primary_color" class="form-label">Primary Color</label>
                    <div style="display: flex; gap: 0.5rem; align-items: center;">
                        <input type="color" id="branding_primary_color_picker" value="{{ old('branding.primary_color', $config['branding']['primary_color'] ?? '#6366f1') }}" style="width: 50px; height: 38px; padding: 0; border: 1px solid var(--border-color); border-radius: 0.375rem; cursor: pointer;">
                        <input type="text" id="branding_primary_color" name="branding[primary_color]" class="form-input @error('branding.primary_color') is-invalid @enderror" value="{{ old('branding.primary_color', $config['branding']['primary_color'] ?? '#6366f1') }}" style="flex: 1;">
                    </div>
                    @error('branding.primary_color')
                        <span class="form-error">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>
    </div>

    <!-- Route Settings -->
    <div class="card" style="margin-bottom: 1.5rem;">
        <div class="card-header">
            <h3 class="card-title">Route Settings</h3>
        </div>
        <div class="card-body">
            <div class="form-row">
                <div class="form-group">
                    <label for="routes_prefix" class="form-label">Route Prefix <span class="form-label-optional">(optional)</span></label>
                    <input type="text" id="routes_prefix" name="routes[prefix]" class="form-input @error('routes.prefix') is-invalid @enderror" value="{{ old('routes.prefix', $config['routes']['prefix'] ?? '') }}" placeholder="auth">
                    <span class="form-hint">E.g., <code>auth</code> results in <code>/auth/login</code>.</span>
                    @error('routes.prefix')
                        <span class="form-error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="routes_home" class="form-label">Home Redirect Path</label>
                    <input type="text" id="routes_home" name="routes[home]" class="form-input @error('routes.home') is-invalid @enderror" value="{{ old('routes.home', $config['routes']['home'] ?? $config['redirects']['after_login'] ?? '/') }}">
                    <span class="form-hint">Where to redirect after successful login.</span>
                    @error('routes.home')
                        <span class="form-error">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="form-group">
                <label for="routes_logout_redirect" class="form-label">Logout Redirect Path</label>
                <input type="text" id="routes_logout_redirect" name="routes[logout_redirect]" class="form-input @error('routes.logout_redirect') is-invalid @enderror" value="{{ old('routes.logout_redirect', $config['routes']['logout_redirect'] ?? $config['redirects']['after_logout'] ?? '/login') }}">
                <span class="form-hint">Where to redirect after logout.</span>
                @error('routes.logout_redirect')
                    <span class="form-error">{{ $message }}</span>
                @enderror
            </div>
        </div>
    </div>

    <!-- Features -->
    <div class="card" style="margin-bottom: 1.5rem;">
        <div class="card-header">
            <h3 class="card-title">Features</h3>
        </div>
        <div class="card-body">
            <div class="feature-grid">
                <div class="form-group">
                    <label class="toggle-label">
                        <input type="checkbox" name="features[registration]" class="toggle-input" value="1" {{ old('features.registration', $config['features']['registration'] ?? $config['registration']['enabled'] ?? true) ? 'checked' : '' }}>
                        <span class="toggle-slider"></span>
                        <span class="toggle-text">Enable Registration</span>
                    </label>
                    <span class="form-hint">Allow new user sign-ups.</span>
                </div>

                <div class="form-group">
                    <label class="toggle-label">
                        <input type="checkbox" name="features[password_reset]" class="toggle-input" value="1" {{ old('features.password_reset', $config['features']['password_reset'] ?? $config['features']['forgot_password'] ?? true) ? 'checked' : '' }}>
                        <span class="toggle-slider"></span>
                        <span class="toggle-text">Password Reset</span>
                    </label>
                    <span class="form-hint">Allow password recovery.</span>
                </div>

                <div class="form-group">
                    <label class="toggle-label">
                        <input type="checkbox" name="features[email_verification]" class="toggle-input" value="1" {{ old('features.email_verification', $config['features']['email_verification'] ?? $config['registration']['require_email_verification'] ?? false) ? 'checked' : '' }}>
                        <span class="toggle-slider"></span>
                        <span class="toggle-text">Email Verification</span>
                    </label>
                    <span class="form-hint">Require email verification.</span>
                </div>

                <div class="form-group">
                    <label class="toggle-label">
                        <input type="checkbox" name="features[remember_me]" class="toggle-input" value="1" {{ old('features.remember_me', $config['features']['remember_me'] ?? true) ? 'checked' : '' }}>
                        <span class="toggle-slider"></span>
                        <span class="toggle-text">Remember Me</span>
                    </label>
                    <span class="form-hint">Show "Remember me" checkbox.</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Password Rules -->
    <div class="card" style="margin-bottom: 1.5rem;">
        <div class="card-header">
            <h3 class="card-title">Password Rules</h3>
        </div>
        <div class="card-body">
            <div class="form-row">
                <div class="form-group">
                    <label for="password_min_length" class="form-label">Minimum Length</label>
                    <input type="number" id="password_min_length" name="password[min_length]" class="form-input @error('password.min_length') is-invalid @enderror" value="{{ old('password.min_length', $config['password']['min_length'] ?? 8) }}" min="6" max="50">
                    @error('password.min_length')
                        <span class="form-error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password_max_length" class="form-label">Maximum Length</label>
                    <input type="number" id="password_max_length" name="password[max_length]" class="form-input @error('password.max_length') is-invalid @enderror" value="{{ old('password.max_length', $config['password']['max_length'] ?? 128) }}" min="8" max="255">
                    @error('password.max_length')
                        <span class="form-error">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="feature-grid" style="margin-top: 1rem;">
                <div class="form-group">
                    <label class="toggle-label">
                        <input type="checkbox" name="password[require_uppercase]" class="toggle-input" value="1" {{ old('password.require_uppercase', $config['password']['require_uppercase'] ?? false) ? 'checked' : '' }}>
                        <span class="toggle-slider"></span>
                        <span class="toggle-text">Require Uppercase</span>
                    </label>
                </div>

                <div class="form-group">
                    <label class="toggle-label">
                        <input type="checkbox" name="password[require_lowercase]" class="toggle-input" value="1" {{ old('password.require_lowercase', $config['password']['require_lowercase'] ?? false) ? 'checked' : '' }}>
                        <span class="toggle-slider"></span>
                        <span class="toggle-text">Require Lowercase</span>
                    </label>
                </div>

                <div class="form-group">
                    <label class="toggle-label">
                        <input type="checkbox" name="password[require_number]" class="toggle-input" value="1" {{ old('password.require_number', $config['password']['require_number'] ?? false) ? 'checked' : '' }}>
                        <span class="toggle-slider"></span>
                        <span class="toggle-text">Require Number</span>
                    </label>
                </div>

                <div class="form-group">
                    <label class="toggle-label">
                        <input type="checkbox" name="password[require_symbol]" class="toggle-input" value="1" {{ old('password.require_symbol', $config['password']['require_symbol'] ?? false) ? 'checked' : '' }}>
                        <span class="toggle-slider"></span>
                        <span class="toggle-text">Require Symbol</span>
                    </label>
                </div>
            </div>
        </div>
    </div>

    <!-- Rate Limiting -->
    <div class="card" style="margin-bottom: 1.5rem;">
        <div class="card-header">
            <h3 class="card-title">Rate Limiting / Lockout Protection</h3>
        </div>
        <div class="card-body">
            <div class="alert alert-warning" style="margin-bottom: 1rem;">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
                <div class="alert-content">
                    <p class="alert-message">Protects against brute force attacks by limiting login attempts.</p>
                </div>
            </div>

            <div class="form-group">
                <label class="toggle-label">
                    <input type="checkbox" name="rate_limiting[enabled]" class="toggle-input" value="1" {{ old('rate_limiting.enabled', $config['rate_limiting']['enabled'] ?? $config['lockout']['enabled'] ?? true) ? 'checked' : '' }}>
                    <span class="toggle-slider"></span>
                    <span class="toggle-text">Enable Rate Limiting</span>
                </label>
            </div>

            <div class="form-row" style="margin-top: 1rem;">
                <div class="form-group">
                    <label for="rate_limiting_max_attempts" class="form-label">Max Attempts</label>
                    <input type="number" id="rate_limiting_max_attempts" name="rate_limiting[max_attempts]" class="form-input @error('rate_limiting.max_attempts') is-invalid @enderror" value="{{ old('rate_limiting.max_attempts', $config['rate_limiting']['max_attempts'] ?? $config['lockout']['max_attempts'] ?? 5) }}" min="1" max="100">
                    <span class="form-hint">Login attempts before lockout.</span>
                    @error('rate_limiting.max_attempts')
                        <span class="form-error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="rate_limiting_decay_minutes" class="form-label">Decay Minutes</label>
                    <input type="number" id="rate_limiting_decay_minutes" name="rate_limiting[decay_minutes]" class="form-input @error('rate_limiting.decay_minutes') is-invalid @enderror" value="{{ old('rate_limiting.decay_minutes', $config['rate_limiting']['decay_minutes'] ?? $config['lockout']['duration_minutes'] ?? 15) }}" min="1" max="60">
                    <span class="form-hint">Minutes until attempts reset.</span>
                    @error('rate_limiting.decay_minutes')
                        <span class="form-error">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>
    </div>

    <!-- Social Login -->
    <div class="card" style="margin-bottom: 1.5rem;">
        <div class="card-header">
            <h3 class="card-title">Social Login (OAuth)</h3>
        </div>
        <div class="card-body">
            <div class="form-group" style="margin-bottom: 1.5rem;">
                <label class="toggle-label">
                    <input type="checkbox" name="social[enabled]" class="toggle-input" value="1" {{ old('social.enabled', $config['social']['enabled'] ?? false) ? 'checked' : '' }}>
                    <span class="toggle-slider"></span>
                    <span class="toggle-text">Enable Social Login</span>
                </label>
                <span class="form-hint" style="margin-top: 0.5rem;">Allow users to sign in with social accounts.</span>
            </div>

            <div class="alert alert-info" style="margin-bottom: 1rem;">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <div class="alert-content">
                    <p class="alert-message">OAuth credentials should be configured in your <code>.env</code> file. Enable providers here after configuring.</p>
                </div>
            </div>

            <h4 style="font-size: 0.9375rem; font-weight: 600; margin-bottom: 1rem; color: var(--text-primary);">OAuth Providers</h4>
            
            <div class="social-providers-grid">
                <!-- Google -->
                <div class="social-provider-item">
                    <label class="toggle-label">
                        <input type="checkbox" name="social[google]" class="toggle-input" value="1" {{ old('social.google', $config['social']['providers']['google']['enabled'] ?? false) ? 'checked' : '' }}>
                        <span class="toggle-slider"></span>
                        <div class="social-provider-info">
                            <svg class="social-icon social-icon-google" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                                <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                                <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                                <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                            </svg>
                            <span class="toggle-text">Google</span>
                        </div>
                    </label>
                </div>

                <!-- GitHub -->
                <div class="social-provider-item">
                    <label class="toggle-label">
                        <input type="checkbox" name="social[github]" class="toggle-input" value="1" {{ old('social.github', $config['social']['providers']['github']['enabled'] ?? false) ? 'checked' : '' }}>
                        <span class="toggle-slider"></span>
                        <div class="social-provider-info">
                            <svg class="social-icon social-icon-github" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"/>
                            </svg>
                            <span class="toggle-text">GitHub</span>
                        </div>
                    </label>
                </div>

                <!-- Facebook -->
                <div class="social-provider-item">
                    <label class="toggle-label">
                        <input type="checkbox" name="social[facebook]" class="toggle-input" value="1" {{ old('social.facebook', $config['social']['providers']['facebook']['enabled'] ?? false) ? 'checked' : '' }}>
                        <span class="toggle-slider"></span>
                        <div class="social-provider-info">
                            <svg class="social-icon social-icon-facebook" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                            </svg>
                            <span class="toggle-text">Facebook</span>
                        </div>
                    </label>
                </div>

                <!-- Twitter/X -->
                <div class="social-provider-item">
                    <label class="toggle-label">
                        <input type="checkbox" name="social[twitter]" class="toggle-input" value="1" {{ old('social.twitter', $config['social']['providers']['twitter']['enabled'] ?? false) ? 'checked' : '' }}>
                        <span class="toggle-slider"></span>
                        <div class="social-provider-info">
                            <svg class="social-icon social-icon-twitter" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/>
                            </svg>
                            <span class="toggle-text">Twitter / X</span>
                        </div>
                    </label>
                </div>
            </div>
        </div>
    </div>

    <div class="form-actions">
        <button type="submit" class="btn btn-primary">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
            </svg>
            Save Settings
        </button>
        <button type="reset" class="btn btn-secondary">Reset</button>
    </div>
</form>

<style>
    .social-providers-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        gap: 1rem;
    }
    .social-provider-item {
        padding: 1rem;
        background-color: var(--bg-secondary);
        border-radius: 0.5rem;
        transition: background-color 0.15s ease;
    }
    .social-provider-item:hover {
        background-color: var(--bg-tertiary);
    }
    .social-provider-info {
        display: flex;
        align-items: center;
        gap: 0.625rem;
    }
    .social-icon {
        width: 20px;
        height: 20px;
    }
    .social-icon-google { color: #4285f4; }
    .social-icon-github { color: var(--text-primary); }
    .social-icon-facebook { color: #1877f2; }
    .social-icon-twitter { color: var(--text-primary); }
</style>

<script>
    document.getElementById('branding_primary_color_picker').addEventListener('input', function() {
        document.getElementById('branding_primary_color').value = this.value;
    });
    document.getElementById('branding_primary_color').addEventListener('input', function() {
        if (/^#[0-9A-Fa-f]{6}$/.test(this.value)) {
            document.getElementById('branding_primary_color_picker').value = this.value;
        }
    });
</script>
@endsection
