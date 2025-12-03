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
                    <label for="app_name" class="form-label">Application Name</label>
                    <input type="text" id="app_name" name="branding[app_name]" class="form-input @error('branding.app_name') is-invalid @enderror" value="{{ old('branding.app_name', $config['branding']['app_name'] ?? config('app.name')) }}">
                    <span class="form-hint">Displayed on login pages.</span>
                    @error('branding.app_name')
                        <span class="form-error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="logo_url" class="form-label">Logo URL</label>
                    <input type="text" id="logo_url" name="branding[logo_url]" class="form-input @error('branding.logo_url') is-invalid @enderror" value="{{ old('branding.logo_url', $config['branding']['logo_url'] ?? '') }}" placeholder="/images/logo.svg">
                    <span class="form-hint">Leave empty to show app name as text.</span>
                    @error('branding.logo_url')
                        <span class="form-error">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="form-group">
                <label for="favicon_url" class="form-label">Favicon URL</label>
                <input type="text" id="favicon_url" name="branding[favicon_url]" class="form-input @error('branding.favicon_url') is-invalid @enderror" value="{{ old('branding.favicon_url', $config['branding']['favicon_url'] ?? '') }}" placeholder="/favicon.ico">
                @error('branding.favicon_url')
                    <span class="form-error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="primary_color" class="form-label">Primary Color</label>
                <div class="color-picker-wrapper">
                    <input type="color" id="primary_color_picker" value="{{ old('branding.primary_color', $config['branding']['primary_color'] ?? '#6366f1') }}" style="width: 48px; height: 36px; border: none; cursor: pointer;">
                    <input type="text" id="primary_color" name="branding[primary_color]" class="form-input @error('branding.primary_color') is-invalid @enderror" value="{{ old('branding.primary_color', $config['branding']['primary_color'] ?? '#6366f1') }}" style="flex: 1;">
                </div>
                <span class="form-hint">Used for buttons, links, and accents.</span>
                @error('branding.primary_color')
                    <span class="form-error">{{ $message }}</span>
                @enderror
            </div>
        </div>
    </div>

    <!-- Route Settings -->
    <div class="card" style="margin-bottom: 1.5rem;">
        <div class="card-header">
            <h3 class="card-title">Routes</h3>
        </div>
        <div class="card-body">
            <div class="form-group">
                <label for="route_prefix" class="form-label">Route Prefix</label>
                <input type="text" id="route_prefix" name="routes[prefix]" class="form-input @error('routes.prefix') is-invalid @enderror" value="{{ old('routes.prefix', $config['routes']['prefix'] ?? '') }}" placeholder="auth">
                <span class="form-hint">E.g., <code>auth</code> results in <code>/auth/login</code></span>
                @error('routes.prefix')
                    <span class="form-error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="home_path" class="form-label">Home Path (After Login)</label>
                    <input type="text" id="home_path" name="routes[home]" class="form-input @error('routes.home') is-invalid @enderror" value="{{ old('routes.home', $config['routes']['home'] ?? '/dashboard') }}">
                    @error('routes.home')
                        <span class="form-error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="logout_redirect" class="form-label">Logout Redirect Path</label>
                    <input type="text" id="logout_redirect" name="routes[logout_redirect]" class="form-input @error('routes.logout_redirect') is-invalid @enderror" value="{{ old('routes.logout_redirect', $config['routes']['logout_redirect'] ?? '/') }}">
                    @error('routes.logout_redirect')
                        <span class="form-error">{{ $message }}</span>
                    @enderror
                </div>
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
                        <input type="checkbox" name="features[registration]" class="toggle-input" value="1" {{ old('features.registration', $config['features']['registration'] ?? true) ? 'checked' : '' }}>
                        <span class="toggle-slider"></span>
                        <span class="toggle-text">Allow Registration</span>
                    </label>
                    <span class="form-hint">Enable new user sign-ups.</span>
                </div>

                <div class="form-group">
                    <label class="toggle-label">
                        <input type="checkbox" name="features[password_reset]" class="toggle-input" value="1" {{ old('features.password_reset', $config['features']['password_reset'] ?? true) ? 'checked' : '' }}>
                        <span class="toggle-slider"></span>
                        <span class="toggle-text">Password Reset</span>
                    </label>
                    <span class="form-hint">Allow users to reset passwords.</span>
                </div>

                <div class="form-group">
                    <label class="toggle-label">
                        <input type="checkbox" name="features[email_verification]" class="toggle-input" value="1" {{ old('features.email_verification', $config['features']['email_verification'] ?? false) ? 'checked' : '' }}>
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
                    <input type="number" id="password_max_length" name="password[max_length]" class="form-input @error('password.max_length') is-invalid @enderror" value="{{ old('password.max_length', $config['password']['max_length'] ?? 255) }}" min="8" max="255">
                    @error('password.max_length')
                        <span class="form-error">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="checkbox-grid">
                <label class="checkbox-label">
                    <input type="checkbox" name="password[require_uppercase]" class="checkbox-input" value="1" {{ old('password.require_uppercase', $config['password']['require_uppercase'] ?? false) ? 'checked' : '' }}>
                    <span>Require uppercase letter</span>
                </label>
                <label class="checkbox-label">
                    <input type="checkbox" name="password[require_lowercase]" class="checkbox-input" value="1" {{ old('password.require_lowercase', $config['password']['require_lowercase'] ?? false) ? 'checked' : '' }}>
                    <span>Require lowercase letter</span>
                </label>
                <label class="checkbox-label">
                    <input type="checkbox" name="password[require_number]" class="checkbox-input" value="1" {{ old('password.require_number', $config['password']['require_number'] ?? false) ? 'checked' : '' }}>
                    <span>Require number</span>
                </label>
                <label class="checkbox-label">
                    <input type="checkbox" name="password[require_symbol]" class="checkbox-input" value="1" {{ old('password.require_symbol', $config['password']['require_symbol'] ?? false) ? 'checked' : '' }}>
                    <span>Require symbol</span>
                </label>
            </div>
        </div>
    </div>

    <!-- Rate Limiting -->
    <div class="card" style="margin-bottom: 1.5rem;">
        <div class="card-header">
            <h3 class="card-title">Rate Limiting</h3>
        </div>
        <div class="card-body">
            <div class="form-group">
                <label class="toggle-label">
                    <input type="checkbox" name="rate_limiting[enabled]" class="toggle-input" value="1" {{ old('rate_limiting.enabled', $config['rate_limiting']['enabled'] ?? true) ? 'checked' : '' }}>
                    <span class="toggle-slider"></span>
                    <span class="toggle-text">Enable Rate Limiting</span>
                </label>
                <span class="form-hint" style="margin-top: 0.5rem;">Protect against brute force attacks.</span>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="rate_max_attempts" class="form-label">Max Attempts</label>
                    <input type="number" id="rate_max_attempts" name="rate_limiting[max_attempts]" class="form-input @error('rate_limiting.max_attempts') is-invalid @enderror" value="{{ old('rate_limiting.max_attempts', $config['rate_limiting']['max_attempts'] ?? 5) }}" min="1" max="100">
                    <span class="form-hint">Login attempts before lockout.</span>
                    @error('rate_limiting.max_attempts')
                        <span class="form-error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="rate_decay_minutes" class="form-label">Lockout Duration (minutes)</label>
                    <input type="number" id="rate_decay_minutes" name="rate_limiting[decay_minutes]" class="form-input @error('rate_limiting.decay_minutes') is-invalid @enderror" value="{{ old('rate_limiting.decay_minutes', $config['rate_limiting']['decay_minutes'] ?? 1) }}" min="1" max="60">
                    <span class="form-hint">Time until attempts reset.</span>
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
            <div class="alert alert-info" style="margin-bottom: 1rem;">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <div class="alert-content">
                    <p class="alert-message">OAuth credentials should be configured in your <code>.env</code> file for security. Enable providers here after configuring.</p>
                </div>
            </div>

            <div class="feature-grid">
                <div class="form-group">
                    <label class="toggle-label">
                        <input type="checkbox" name="social[google]" class="toggle-input" value="1" {{ old('social.google', $config['social']['google'] ?? false) ? 'checked' : '' }}>
                        <span class="toggle-slider"></span>
                        <span class="toggle-text">Google</span>
                    </label>
                </div>

                <div class="form-group">
                    <label class="toggle-label">
                        <input type="checkbox" name="social[github]" class="toggle-input" value="1" {{ old('social.github', $config['social']['github'] ?? false) ? 'checked' : '' }}>
                        <span class="toggle-slider"></span>
                        <span class="toggle-text">GitHub</span>
                    </label>
                </div>

                <div class="form-group">
                    <label class="toggle-label">
                        <input type="checkbox" name="social[facebook]" class="toggle-input" value="1" {{ old('social.facebook', $config['social']['facebook'] ?? false) ? 'checked' : '' }}>
                        <span class="toggle-slider"></span>
                        <span class="toggle-text">Facebook</span>
                    </label>
                </div>

                <div class="form-group">
                    <label class="toggle-label">
                        <input type="checkbox" name="social[twitter]" class="toggle-input" value="1" {{ old('social.twitter', $config['social']['twitter'] ?? false) ? 'checked' : '' }}>
                        <span class="toggle-slider"></span>
                        <span class="toggle-text">Twitter / X</span>
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
    .color-picker-wrapper {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    .color-picker-wrapper input[type="color"] {
        border-radius: 0.375rem;
        overflow: hidden;
    }
    .checkbox-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 0.75rem;
    }
    .checkbox-label {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        cursor: pointer;
        font-size: 0.875rem;
        color: var(--text-primary);
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const colorPicker = document.getElementById('primary_color_picker');
        const colorInput = document.getElementById('primary_color');
        
        colorPicker.addEventListener('input', function() {
            colorInput.value = this.value;
        });
        
        colorInput.addEventListener('input', function() {
            if (/^#[0-9A-Fa-f]{6}$/.test(this.value)) {
                colorPicker.value = this.value;
            }
        });
    });
</script>
@endsection
