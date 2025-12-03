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

    <!-- OTP Settings -->
    <div class="card" style="margin-bottom: 1.5rem;">
        <div class="card-header">
            <h3 class="card-title">OTP (One-Time Password)</h3>
        </div>
        <div class="card-body">
            <div class="form-group">
                <label class="toggle-label">
                    <input type="checkbox" name="otp[enabled]" class="toggle-input" value="1" {{ old('otp.enabled', $config['otp']['enabled'] ?? false) ? 'checked' : '' }}>
                    <span class="toggle-slider"></span>
                    <span class="toggle-text">Enable OTP Verification</span>
                </label>
                <span class="form-hint" style="margin-top: 0.5rem;">Require OTP code via email after login credentials are verified.</span>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="otp_length" class="form-label">OTP Length</label>
                    <input type="number" id="otp_length" name="otp[length]" class="form-input @error('otp.length') is-invalid @enderror" value="{{ old('otp.length', $config['otp']['length'] ?? 4) }}" min="4" max="8">
                    <span class="form-hint">Number of digits (4-8).</span>
                    @error('otp.length')
                        <span class="form-error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="otp_expire" class="form-label">OTP Expiration (minutes)</label>
                    <input type="number" id="otp_expire" name="otp[expire]" class="form-input @error('otp.expire') is-invalid @enderror" value="{{ old('otp.expire', $config['otp']['expire'] ?? 5) }}" min="1" max="30">
                    <span class="form-hint">Time until OTP expires.</span>
                    @error('otp.expire')
                        <span class="form-error">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="otp_max_resend" class="form-label">Max Resend Attempts</label>
                    <input type="number" id="otp_max_resend" name="otp[max_resend]" class="form-input @error('otp.max_resend') is-invalid @enderror" value="{{ old('otp.max_resend', $config['otp']['max_resend'] ?? 3) }}" min="1" max="10">
                    @error('otp.max_resend')
                        <span class="form-error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="otp_resend_cooldown" class="form-label">Resend Cooldown (seconds)</label>
                    <input type="number" id="otp_resend_cooldown" name="otp[resend_cooldown]" class="form-input @error('otp.resend_cooldown') is-invalid @enderror" value="{{ old('otp.resend_cooldown', $config['otp']['resend_cooldown'] ?? 60) }}" min="30" max="300">
                    @error('otp.resend_cooldown')
                        <span class="form-error">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>
    </div>

    <!-- Math Captcha Settings -->
    <div class="card" style="margin-bottom: 1.5rem;">
        <div class="card-header">
            <h3 class="card-title">Math Captcha</h3>
        </div>
        <div class="card-body">
            <div class="alert alert-info" style="margin-bottom: 1rem;">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <div class="alert-content">
                    <p class="alert-message">Simple math captcha (addition/subtraction) to prevent automated submissions without requiring external services.</p>
                </div>
            </div>

            <div class="feature-grid">
                <div class="form-group">
                    <label class="toggle-label">
                        <input type="checkbox" name="captcha[enabled_login]" class="toggle-input" value="1" {{ old('captcha.enabled_login', $config['captcha']['enabled_login'] ?? false) ? 'checked' : '' }}>
                        <span class="toggle-slider"></span>
                        <span class="toggle-text">Enable on Login</span>
                    </label>
                    <span class="form-hint">Show captcha on login form.</span>
                </div>

                <div class="form-group">
                    <label class="toggle-label">
                        <input type="checkbox" name="captcha[enabled_register]" class="toggle-input" value="1" {{ old('captcha.enabled_register', $config['captcha']['enabled_register'] ?? false) ? 'checked' : '' }}>
                        <span class="toggle-slider"></span>
                        <span class="toggle-text">Enable on Registration</span>
                    </label>
                    <span class="form-hint">Show captcha on registration form.</span>
                </div>
            </div>

            <div class="form-row" style="margin-top: 1rem;">
                <div class="form-group">
                    <label for="captcha_min" class="form-label">Minimum Number</label>
                    <input type="number" id="captcha_min" name="captcha[min_number]" class="form-input @error('captcha.min_number') is-invalid @enderror" value="{{ old('captcha.min_number', $config['captcha']['min_number'] ?? 1) }}" min="1" max="50">
                    @error('captcha.min_number')
                        <span class="form-error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="captcha_max" class="form-label">Maximum Number</label>
                    <input type="number" id="captcha_max" name="captcha[max_number]" class="form-input @error('captcha.max_number') is-invalid @enderror" value="{{ old('captcha.max_number', $config['captcha']['max_number'] ?? 10) }}" min="5" max="100">
                    @error('captcha.max_number')
                        <span class="form-error">{{ $message }}</span>
                    @enderror
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

    <!-- Lockout Protection -->
    <div class="card" style="margin-bottom: 1.5rem;">
        <div class="card-header">
            <h3 class="card-title">Brute Force Lockout Protection</h3>
        </div>
        <div class="card-body">
            <div class="alert alert-warning" style="margin-bottom: 1rem;">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
                <div class="alert-content">
                    <p class="alert-message">Automatically locks out users after too many failed login attempts to prevent brute-force attacks.</p>
                </div>
            </div>

            <div class="form-group">
                <label class="toggle-label">
                    <input type="checkbox" name="lockout[enabled]" class="toggle-input" value="1" {{ old('lockout.enabled', $config['lockout']['enabled'] ?? true) ? 'checked' : '' }}>
                    <span class="toggle-slider"></span>
                    <span class="toggle-text">Enable Lockout Protection</span>
                </label>
                <span class="form-hint" style="margin-top: 0.5rem;">Protect against brute force attacks.</span>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="lockout_max_attempts" class="form-label">Max Attempts</label>
                    <input type="number" id="lockout_max_attempts" name="lockout[max_attempts]" class="form-input @error('lockout.max_attempts') is-invalid @enderror" value="{{ old('lockout.max_attempts', $config['lockout']['max_attempts'] ?? 5) }}" min="1" max="20">
                    <span class="form-hint">Login attempts before lockout.</span>
                    @error('lockout.max_attempts')
                        <span class="form-error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="lockout_duration" class="form-label">Lockout Duration (minutes)</label>
                    <input type="number" id="lockout_duration" name="lockout[lockout_duration]" class="form-input @error('lockout.lockout_duration') is-invalid @enderror" value="{{ old('lockout.lockout_duration', $config['lockout']['lockout_duration'] ?? 15) }}" min="1" max="60">
                    <span class="form-hint">Time until lockout expires.</span>
                    @error('lockout.lockout_duration')
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
                    <p class="alert-message">OAuth credentials should be configured in your <code>.env</code> file for security. Enable providers here after configuring.</p>
                </div>
            </div>

            <div class="social-providers-grid">
                <!-- Google -->
                <div class="social-provider-item">
                    <label class="toggle-label">
                        <input type="checkbox" name="social[providers][google]" class="toggle-input" value="1" {{ old('social.providers.google', $config['social']['providers']['google'] ?? false) ? 'checked' : '' }}>
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
                        <input type="checkbox" name="social[providers][github]" class="toggle-input" value="1" {{ old('social.providers.github', $config['social']['providers']['github'] ?? false) ? 'checked' : '' }}>
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
                        <input type="checkbox" name="social[providers][facebook]" class="toggle-input" value="1" {{ old('social.providers.facebook', $config['social']['providers']['facebook'] ?? false) ? 'checked' : '' }}>
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
                        <input type="checkbox" name="social[providers][twitter]" class="toggle-input" value="1" {{ old('social.providers.twitter', $config['social']['providers']['twitter'] ?? false) ? 'checked' : '' }}>
                        <span class="toggle-slider"></span>
                        <div class="social-provider-info">
                            <svg class="social-icon social-icon-twitter" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/>
                            </svg>
                            <span class="toggle-text">Twitter / X</span>
                        </div>
                    </label>
                </div>

                <!-- LinkedIn -->
                <div class="social-provider-item">
                    <label class="toggle-label">
                        <input type="checkbox" name="social[providers][linkedin]" class="toggle-input" value="1" {{ old('social.providers.linkedin', $config['social']['providers']['linkedin'] ?? false) ? 'checked' : '' }}>
                        <span class="toggle-slider"></span>
                        <div class="social-provider-info">
                            <svg class="social-icon social-icon-linkedin" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                            </svg>
                            <span class="toggle-text">LinkedIn</span>
                        </div>
                    </label>
                </div>

                <!-- Bitbucket -->
                <div class="social-provider-item">
                    <label class="toggle-label">
                        <input type="checkbox" name="social[providers][bitbucket]" class="toggle-input" value="1" {{ old('social.providers.bitbucket', $config['social']['providers']['bitbucket'] ?? false) ? 'checked' : '' }}>
                        <span class="toggle-slider"></span>
                        <div class="social-provider-info">
                            <svg class="social-icon social-icon-bitbucket" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M.778 1.213a.768.768 0 00-.768.892l3.263 19.81c.084.5.515.868 1.022.873H19.95a.772.772 0 00.77-.646l3.27-20.03a.768.768 0 00-.768-.891zM14.52 15.53H9.522L8.17 8.466h7.561z"/>
                            </svg>
                            <span class="toggle-text">Bitbucket</span>
                        </div>
                    </label>
                </div>

                <!-- GitLab -->
                <div class="social-provider-item">
                    <label class="toggle-label">
                        <input type="checkbox" name="social[providers][gitlab]" class="toggle-input" value="1" {{ old('social.providers.gitlab', $config['social']['providers']['gitlab'] ?? false) ? 'checked' : '' }}>
                        <span class="toggle-slider"></span>
                        <div class="social-provider-info">
                            <svg class="social-icon social-icon-gitlab" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M23.955 13.587l-1.342-4.135-2.664-8.189a.455.455 0 00-.867 0L16.418 9.45H7.582L4.918 1.263a.455.455 0 00-.867 0L1.386 9.45.045 13.587a.924.924 0 00.331 1.023L12 23.054l11.624-8.443a.92.92 0 00.331-1.024"/>
                            </svg>
                            <span class="toggle-text">GitLab</span>
                        </div>
                    </label>
                </div>

                <!-- Slack -->
                <div class="social-provider-item">
                    <label class="toggle-label">
                        <input type="checkbox" name="social[providers][slack]" class="toggle-input" value="1" {{ old('social.providers.slack', $config['social']['providers']['slack'] ?? false) ? 'checked' : '' }}>
                        <span class="toggle-slider"></span>
                        <div class="social-provider-info">
                            <svg class="social-icon social-icon-slack" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M5.042 15.165a2.528 2.528 0 0 1-2.52 2.523A2.528 2.528 0 0 1 0 15.165a2.527 2.527 0 0 1 2.522-2.52h2.52v2.52zM6.313 15.165a2.527 2.527 0 0 1 2.521-2.52 2.527 2.527 0 0 1 2.521 2.52v6.313A2.528 2.528 0 0 1 8.834 24a2.528 2.528 0 0 1-2.521-2.522v-6.313zM8.834 5.042a2.528 2.528 0 0 1-2.521-2.52A2.528 2.528 0 0 1 8.834 0a2.528 2.528 0 0 1 2.521 2.522v2.52H8.834zM8.834 6.313a2.528 2.528 0 0 1 2.521 2.521 2.528 2.528 0 0 1-2.521 2.521H2.522A2.528 2.528 0 0 1 0 8.834a2.528 2.528 0 0 1 2.522-2.521h6.312zM18.956 8.834a2.528 2.528 0 0 1 2.522-2.521A2.528 2.528 0 0 1 24 8.834a2.528 2.528 0 0 1-2.522 2.521h-2.522V8.834zM17.688 8.834a2.528 2.528 0 0 1-2.523 2.521 2.527 2.527 0 0 1-2.52-2.521V2.522A2.527 2.527 0 0 1 15.165 0a2.528 2.528 0 0 1 2.523 2.522v6.312zM15.165 18.956a2.528 2.528 0 0 1 2.523 2.522A2.528 2.528 0 0 1 15.165 24a2.527 2.527 0 0 1-2.52-2.522v-2.522h2.52zM15.165 17.688a2.527 2.527 0 0 1-2.52-2.523 2.526 2.526 0 0 1 2.52-2.52h6.313A2.527 2.527 0 0 1 24 15.165a2.528 2.528 0 0 1-2.522 2.523h-6.313z"/>
                            </svg>
                            <span class="toggle-text">Slack</span>
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
    .social-providers-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
        gap: 1rem;
    }
    .social-provider-item {
        padding: 0.75rem;
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
        gap: 0.5rem;
    }
    .social-icon {
        width: 18px;
        height: 18px;
    }
    .social-icon-google { color: #4285f4; }
    .social-icon-github { color: var(--text-primary); }
    .social-icon-facebook { color: #1877f2; }
    .social-icon-twitter { color: var(--text-primary); }
    .social-icon-linkedin { color: #0a66c2; }
    .social-icon-bitbucket { color: #0052cc; }
    .social-icon-gitlab { color: #fc6d26; }
    .social-icon-slack { color: #4a154b; }
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
