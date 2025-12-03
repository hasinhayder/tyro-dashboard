@extends('tyro-dashboard::layouts.app')

@section('title', 'Tyro Settings')

@section('breadcrumb')
<a href="{{ route('tyro-dashboard.index') }}">Dashboard</a>
<span class="breadcrumb-separator">/</span>
<span>Settings</span>
<span class="breadcrumb-separator">/</span>
<span>Tyro</span>
@endsection

@section('content')
<div class="page-header">
    <div class="page-header-row">
        <div>
            <h1 class="page-title">Tyro Settings</h1>
            <p class="page-description">Configure the core Tyro RBAC package settings.</p>
        </div>
        <div>
            <span class="badge badge-primary">v{{ config('tyro.version', '1.0.0') }}</span>
        </div>
    </div>
</div>

<div class="settings-nav">
    <a href="{{ route('tyro-dashboard.settings.tyro') }}" class="settings-nav-item active">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
        </svg>
        Tyro RBAC
    </a>
    <a href="{{ route('tyro-dashboard.settings.tyro-login') }}" class="settings-nav-item">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
        </svg>
        Tyro Login
    </a>
</div>

<form action="{{ route('tyro-dashboard.settings.tyro.update') }}" method="POST">
    @csrf
    @method('PUT')
    
    <!-- User Model Configuration -->
    <div class="card" style="margin-bottom: 1.5rem;">
        <div class="card-header">
            <h3 class="card-title">User Model Configuration</h3>
        </div>
        <div class="card-body">
            <div class="form-group">
                <label for="user_model" class="form-label">User Model Class</label>
                <input type="text" id="user_model" name="user_model" class="form-input @error('user_model') is-invalid @enderror" value="{{ old('user_model', $config['models']['user'] ?? 'App\\Models\\User') }}">
                <span class="form-hint">The fully qualified class name of your User model.</span>
                @error('user_model')
                    <span class="form-error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="user_primary_key" class="form-label">User Primary Key</label>
                    <input type="text" id="user_primary_key" name="user_primary_key" class="form-input @error('user_primary_key') is-invalid @enderror" value="{{ old('user_primary_key', $config['user_primary_key'] ?? 'id') }}">
                    <span class="form-hint">Primary key column name on users table.</span>
                    @error('user_primary_key')
                        <span class="form-error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="user_foreign_key" class="form-label">User Foreign Key</label>
                    <input type="text" id="user_foreign_key" name="user_foreign_key" class="form-input @error('user_foreign_key') is-invalid @enderror" value="{{ old('user_foreign_key', $config['user_foreign_key'] ?? 'user_id') }}">
                    <span class="form-hint">Foreign key column name referencing users.</span>
                    @error('user_foreign_key')
                        <span class="form-error">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>
    </div>

    <!-- Database Tables Configuration -->
    <div class="card" style="margin-bottom: 1.5rem;">
        <div class="card-header">
            <h3 class="card-title">Database Tables</h3>
        </div>
        <div class="card-body">
            <div class="form-row">
                <div class="form-group">
                    <label for="tables_roles" class="form-label">Roles Table</label>
                    <input type="text" id="tables_roles" name="tables[roles]" class="form-input @error('tables.roles') is-invalid @enderror" value="{{ old('tables.roles', $config['tables']['roles'] ?? 'roles') }}">
                    @error('tables.roles')
                        <span class="form-error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="tables_privileges" class="form-label">Privileges Table</label>
                    <input type="text" id="tables_privileges" name="tables[privileges]" class="form-input @error('tables.privileges') is-invalid @enderror" value="{{ old('tables.privileges', $config['tables']['privileges'] ?? 'privileges') }}">
                    @error('tables.privileges')
                        <span class="form-error">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="tables_role_user" class="form-label">Role-User Pivot Table</label>
                    <input type="text" id="tables_role_user" name="tables[role_user]" class="form-input @error('tables.role_user') is-invalid @enderror" value="{{ old('tables.role_user', $config['tables']['role_user'] ?? 'role_user') }}">
                    @error('tables.role_user')
                        <span class="form-error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="tables_role_privilege" class="form-label">Role-Privilege Pivot Table</label>
                    <input type="text" id="tables_role_privilege" name="tables[role_privilege]" class="form-input @error('tables.role_privilege') is-invalid @enderror" value="{{ old('tables.role_privilege', $config['tables']['role_privilege'] ?? 'role_privilege') }}">
                    @error('tables.role_privilege')
                        <span class="form-error">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>
    </div>

    <!-- Cache Configuration -->
    <div class="card" style="margin-bottom: 1.5rem;">
        <div class="card-header">
            <h3 class="card-title">Cache Configuration</h3>
        </div>
        <div class="card-body">
            <div class="form-group">
                <label class="toggle-label">
                    <input type="checkbox" name="cache_enabled" class="toggle-input" value="1" {{ old('cache_enabled', $config['cache']['enabled'] ?? true) ? 'checked' : '' }}>
                    <span class="toggle-slider"></span>
                    <span class="toggle-text">Enable Cache</span>
                </label>
                <span class="form-hint" style="margin-top: 0.5rem;">Cache roles and privileges for better performance.</span>
            </div>

            <div class="form-row" style="margin-top: 1rem;">
                <div class="form-group">
                    <label for="cache_duration" class="form-label">Cache Duration (seconds)</label>
                    <input type="number" id="cache_duration" name="cache_duration" class="form-input @error('cache_duration') is-invalid @enderror" value="{{ old('cache_duration', $config['cache']['ttl'] ?? 3600) }}" min="0">
                    <span class="form-hint">Time-to-live for cached data.</span>
                    @error('cache_duration')
                        <span class="form-error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="cache_prefix" class="form-label">Cache Prefix</label>
                    <input type="text" id="cache_prefix" name="cache_prefix" class="form-input @error('cache_prefix') is-invalid @enderror" value="{{ old('cache_prefix', $config['cache']['prefix'] ?? 'tyro_') }}">
                    <span class="form-hint">Prefix for cache keys.</span>
                    @error('cache_prefix')
                        <span class="form-error">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>
    </div>

    <!-- Middleware Configuration -->
    <div class="card" style="margin-bottom: 1.5rem;">
        <div class="card-header">
            <h3 class="card-title">Middleware Configuration</h3>
        </div>
        <div class="card-body">
            <div class="alert alert-info" style="margin-bottom: 1rem;">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <div class="alert-content">
                    <p class="alert-message">Configure the middleware alias names used in route definitions.</p>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="middleware_role" class="form-label">Role Middleware Name</label>
                    <input type="text" id="middleware_role" name="middleware[role]" class="form-input @error('middleware.role') is-invalid @enderror" value="{{ old('middleware.role', $config['middleware']['role'] ?? 'role') }}">
                    <span class="form-hint">Middleware alias for role checks (e.g., <code>role:admin</code>).</span>
                    @error('middleware.role')
                        <span class="form-error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="middleware_privilege" class="form-label">Privilege Middleware Name</label>
                    <input type="text" id="middleware_privilege" name="middleware[privilege]" class="form-input @error('middleware.privilege') is-invalid @enderror" value="{{ old('middleware.privilege', $config['middleware']['privilege'] ?? 'privilege') }}">
                    <span class="form-hint">Middleware alias for privilege checks (e.g., <code>privilege:edit-posts</code>).</span>
                    @error('middleware.privilege')
                        <span class="form-error">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>
    </div>

    <!-- User Suspension -->
    <div class="card" style="margin-bottom: 1.5rem;">
        <div class="card-header">
            <h3 class="card-title">User Suspension</h3>
        </div>
        <div class="card-body">
            <div class="form-group">
                <label class="toggle-label">
                    <input type="checkbox" name="suspension_enabled" class="toggle-input" value="1" {{ old('suspension_enabled', $config['suspension']['enabled'] ?? false) ? 'checked' : '' }}>
                    <span class="toggle-slider"></span>
                    <span class="toggle-text">Enable Suspension Feature</span>
                </label>
                <span class="form-hint" style="margin-top: 0.5rem;">Allow suspending user accounts.</span>
            </div>

            <div class="form-group" style="margin-top: 1rem;">
                <label for="suspension_column" class="form-label">Suspension Column Name</label>
                <input type="text" id="suspension_column" name="suspension_column" class="form-input @error('suspension_column') is-invalid @enderror" value="{{ old('suspension_column', $config['suspension']['column'] ?? 'is_suspended') }}">
                <span class="form-hint">Column name in users table for suspension status.</span>
                @error('suspension_column')
                    <span class="form-error">{{ $message }}</span>
                @enderror
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
@endsection
