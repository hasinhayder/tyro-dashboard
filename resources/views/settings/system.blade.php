@extends('tyro-dashboard::layouts.admin')

@section('title', 'System Settings')

@section('breadcrumb')
<a href="{{ route($dashboardRoute::name('index')) }}">Dashboard</a>
<span class="breadcrumb-separator">/</span>
<span>System Settings</span>
@endsection

@push('styles')
<style>
.sys-settings-intro {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 1rem;
    margin-bottom: 1.5rem;
}
.sys-settings-copy { max-width: 36rem; }
.sys-settings-kicker {
    display: inline-flex;
    align-items: center;
    gap: 0.45rem;
    padding: 0.35rem 0.7rem;
    border-radius: 999px;
    background: color-mix(in srgb, var(--primary) 10%, var(--card));
    color: var(--primary);
    font-size: 0.75rem;
    font-weight: 700;
    letter-spacing: 0.08em;
    text-transform: uppercase;
    margin-bottom: 0.85rem;
}
.sys-settings-heading {
    margin: 0;
    color: var(--foreground);
    font-size: 1.25rem;
    line-height: 1.2;
}
.sys-settings-description {
    margin: 0.55rem 0 0;
    color: var(--muted-foreground);
    font-size: 0.9375rem;
    line-height: 1.7;
}
.sys-settings-surface {
    padding: 1rem 1.05rem;
    border: 1px solid var(--border);
    border-radius: 1rem;
    background: var(--muted);
}
.sys-settings-surface-title {
    margin: 0 0 0.25rem;
    color: var(--foreground);
    font-size: 0.94rem;
    font-weight: 700;
}
.sys-settings-surface-description {
    margin: 0 0 0.9rem;
    color: var(--muted-foreground);
    font-size: 0.84rem;
    line-height: 1.6;
}
.sys-settings-section-intro {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 1rem;
    margin-bottom: 1.25rem;
}
.sys-settings-section-copy { max-width: 38rem; }
.sys-settings-section-heading {
    margin: 0;
    color: var(--foreground);
    font-size: 1.05rem;
    font-weight: 700;
}
.sys-settings-section-description {
    margin: 0.45rem 0 0;
    color: var(--muted-foreground);
    font-size: 0.875rem;
    line-height: 1.65;
}
.sys-settings-section-badge {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 3rem;
    padding: 0.45rem 0.75rem;
    border-radius: 999px;
    border: 1px solid var(--border);
    background: var(--muted);
    color: var(--foreground);
    font-size: 0.8rem;
    font-weight: 700;
    white-space: nowrap;
}
.sys-settings-grid {
    display: grid;
    gap: 1rem;
}
.sys-settings-toggles {
    display: flex;
    flex-direction: column;
    gap: 0.85rem;
}
.sys-settings-toggle {
    padding: 1rem 1.05rem;
    border: 1px solid var(--border);
    border-radius: 1rem;
    background: var(--card);
}
.sys-settings-toggle-top {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 1rem;
}
.sys-settings-toggle-title {
    margin: 0;
    color: var(--foreground);
    font-size: 0.95rem;
    font-weight: 700;
}
.sys-settings-toggle-description {
    margin: 0.35rem 0 0;
    color: var(--muted-foreground);
    font-size: 0.85rem;
    line-height: 1.55;
}
.sys-settings-metrics {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 0.9rem;
}
.sys-settings-metric .form-label { margin-bottom: 0.45rem; }
.sys-settings-metric .form-input,
.sys-settings-metric .form-select { max-width: none !important; }
.sys-settings-save-row {
    display: flex;
    justify-content: flex-end;
    margin-top: 2rem;
    padding-top: 1.5rem;
    border-top: 1px solid var(--border);
}
@media (max-width: 640px) {
    .sys-settings-metrics { grid-template-columns: 1fr; }
    .sys-settings-toggle-top { align-items: flex-start; }
}
.sys-settings-copy { max-width: 36rem; }
.sys-settings-kicker {
    display: inline-flex;
    align-items: center;
    gap: 0.45rem;
    padding: 0.35rem 0.7rem;
    border-radius: 999px;
    background: color-mix(in srgb, var(--primary) 10%, var(--card));
    color: var(--primary);
    font-size: 0.75rem;
    font-weight: 700;
    letter-spacing: 0.08em;
    text-transform: uppercase;
    margin-bottom: 0.85rem;
}
.sys-settings-heading {
    margin: 0;
    color: var(--foreground);
    font-size: 1.25rem;
    line-height: 1.2;
}
.sys-settings-description {
    margin: 0.55rem 0 0;
    color: var(--muted-foreground);
    font-size: 0.9375rem;
    line-height: 1.7;
}
.sys-settings-surface {
    padding: 1rem 1.05rem;
    border: 1px solid var(--border);
    border-radius: 1rem;
    background: var(--muted);
}
.sys-settings-surface-title {
    margin: 0 0 0.25rem;
    color: var(--foreground);
    font-size: 0.94rem;
    font-weight: 700;
}
.sys-settings-surface-description {
    margin: 0 0 0.9rem;
    color: var(--muted-foreground);
    font-size: 0.84rem;
    line-height: 1.6;
}
.sys-settings-section-intro {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 1rem;
    margin-bottom: 1.25rem;
}
.sys-settings-section-copy { max-width: 38rem; }
.sys-settings-section-heading {
    margin: 0;
    color: var(--foreground);
    font-size: 1.05rem;
    font-weight: 700;
}
.sys-settings-section-description {
    margin: 0.45rem 0 0;
    color: var(--muted-foreground);
    font-size: 0.875rem;
    line-height: 1.65;
}
.sys-settings-section-badge {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 3rem;
    padding: 0.45rem 0.75rem;
    border-radius: 999px;
    border: 1px solid var(--border);
    background: var(--muted);
    color: var(--foreground);
    font-size: 0.8rem;
    font-weight: 700;
    white-space: nowrap;
}
.sys-settings-grid {
    display: grid;
    gap: 1rem;
}
.sys-settings-toggles {
    display: flex;
    flex-direction: column;
    gap: 0.85rem;
}
.sys-settings-toggle {
    padding: 1rem 1.05rem;
    border: 1px solid var(--border);
    border-radius: 1rem;
    background: var(--card);
}
.sys-settings-toggle-top {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 1rem;
}
.sys-settings-toggle-title {
    margin: 0;
    color: var(--foreground);
    font-size: 0.95rem;
    font-weight: 700;
}
.sys-settings-toggle-description {
    margin: 0.35rem 0 0;
    color: var(--muted-foreground);
    font-size: 0.85rem;
    line-height: 1.55;
}
.sys-settings-metrics {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 0.9rem;
}
.sys-settings-metric .form-label { margin-bottom: 0.45rem; }
.sys-settings-metric .form-input,
.sys-settings-metric .form-select { max-width: none !important; }
.sys-settings-save-row {
    display: flex;
    justify-content: flex-end;
    margin-top: 2rem;
    padding-top: 1.5rem;
    border-top: 1px solid var(--border);
}
@media (max-width: 1024px) {
    .sys-settings-section-intro { flex-direction: column; }
}
@media (max-width: 640px) {
    .sys-settings-metrics { grid-template-columns: 1fr; }
    .sys-settings-toggle-top { align-items: flex-start; }
}

.branding-theme-grid {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 0.85rem;
}
.branding-theme-color {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 0.75rem;
    border: 1px solid var(--border);
    border-radius: 0.75rem;
    background: var(--card);
}
.branding-theme-color-meta {
    flex: 1;
    min-width: 0;
}
.branding-theme-color-name {
    font-size: 0.84rem;
    font-weight: 600;
    color: var(--foreground);
}
.branding-theme-color-var {
    font-size: 0.72rem;
    color: var(--muted-foreground);
    font-family: monospace;
    margin-top: 0.05rem;
}
.branding-theme-color-text {
    width: 80px;
    padding: 0.4rem 0.5rem;
    border: 1px solid var(--border);
    border-radius: 0.45rem;
    background: var(--muted);
    color: var(--foreground);
    font-size: 0.8rem;
    font-family: monospace;
    text-align: center;
    transition: border-color 0.15s;
    flex-shrink: 0;
}
.branding-theme-color-text:focus {
    border-color: var(--primary);
    outline: none;
}
.branding-surface-title {
    margin: 0 0 0.25rem;
    color: var(--foreground);
    font-size: 0.94rem;
    font-weight: 700;
}
.branding-color-reset {
    padding:4px;
    border:none;
    background:none;
    cursor:pointer;
    color:var(--muted-foreground);
    flex-shrink:0;
    border-radius:4px;
    display:flex;
    align-items:center;
    justify-content:center;
    transition: color 0.15s ease, background 0.15s ease;
}
.branding-color-reset:hover {
    color: var(--destructive);
    background: color-mix(in srgb, var(--destructive), transparent 90%);
}
</style>
@endpush

@section('content')
<div class="page-header">
    <div class="page-header-row">
        <div>
            <h1 class="page-title">System Settings</h1>
            <p class="page-description">Manage environment-level configuration for the dashboard, RBAC, and authentication systems.</p>
        </div>
        <div>
            <button type="submit" form="systemSettingsForm" class="btn btn-primary" id="systemSettingsSaveButton">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width:18px;height:18px;"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path><polyline points="17 21 17 13 7 13 7 21"></polyline><polyline points="7 3 7 8 15 8"></polyline></svg>
                Save Settings
            </button>
        </div>
    </div>
</div>

<form id="systemSettingsForm" method="POST" action="{{ route($dashboardRoute::name('settings.system.update')) }}">
    @csrf

    <div class="vtabs-layout">
        <nav class="vtabs-sidebar">
            <button class="vtabs-item active" data-vtab="dashboard" type="button">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="4" rx="1"/><rect x="14" y="10" width="7" height="11" rx="1"/><rect x="3" y="13" width="7" height="8" rx="1"/></svg>
                Dashboard
            </button>
            <button class="vtabs-item" data-vtab="rbac" type="button">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0 1 12 2.944a11.955 11.955 0 0 1-8.618 3.04A12.02 12.02 0 0 0 3 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                RBAC
            </button>
            <button class="vtabs-item" data-vtab="login-auth" type="button">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16"><path stroke-linecap="round" stroke-linejoin="round" d="M12 2l7 4v6c0 5-3.5 9.5-7 10-3.5-.5-7-5-7-10V6l7-4z"/><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4"/></svg>
                Login &amp; Auth
            </button>
            <button class="vtabs-item" data-vtab="rbac-advanced" type="button">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16"><path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                RBAC Advanced
            </button>
            <button class="vtabs-item" data-vtab="login-auth-advanced" type="button">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16"><path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                Login &amp; Auth Advanced
            </button>
            <button class="vtabs-item" data-vtab="sidebar-colors" type="button">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"/><line x1="9" y1="3" x2="9" y2="21"/></svg>
                Sidebar Colors
            </button>
            <button class="vtabs-item" data-vtab="admin-bar-colors" type="button">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16"><rect x="2" y="2" width="20" height="8" rx="2"/><rect x="2" y="14" width="20" height="8" rx="2"/></svg>
                Admin Bar
            </button>
            <button class="vtabs-item" data-vtab="dashboard-colors" type="button">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16"><path stroke-linecap="round" stroke-linejoin="round" d="M7 21a4 4 0 0 1-4-4V5a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v12a4 4 0 0 1-4 4zm0 0h12a2 2 0 0 0 2-2v-4a2 2 0 0 0-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 0 1 2.828 0l2.829 2.829a2 2 0 0 1 0 2.828l-8.486 8.485"/></svg>
                Dashboard Colors
            </button>
        <div class="vtabs-save-bar">
            <button type="submit" form="systemSettingsForm" class="btn btn-primary btn-sm" style="width:100%;">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width:16px;height:16px;"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path><polyline points="17 21 17 13 7 13 7 21"></polyline><polyline points="7 3 7 8 15 8"></polyline></svg>
                Save Settings
            </button>
        </div>
        </nav>

        <div class="vtabs-content">
            {{-- Dashboard Tab --}}
            <div class="vtabs-panel active" id="vtab-dashboard">
                <div class="card">
                    <div class="card-header" style="display:flex;align-items:center;justify-content:space-between;gap:1rem;">
                        <h3 class="card-title">Dashboard</h3>
                        <button type="submit" form="systemSettingsForm" class="btn btn-primary btn-sm section-save-button">Save</button>
                    </div>
                    <div class="card-body">
                        <div class="sys-settings-section-intro">
                            <div class="sys-settings-section-copy">
                                <h4 class="sys-settings-section-heading">Manage tyro-dashboard environment configuration</h4>
                                <p class="sys-settings-section-description">Control branding, sidebar appearance, admin bar, notification style, and feature flags. All values are written to the <code>.env</code> file.</p>
                            </div>
                            <span class="sys-settings-section-badge">.env</span>
                        </div>

                        <div class="sys-settings-grid">
                            <div class="sys-settings-surface">
                                <h4 class="sys-settings-surface-title">Branding</h4>
                                <p class="sys-settings-surface-description">Customize the dashboard app name and sidebar colors.</p>

                                <div class="form-group">
                                    <label for="TYRO_DASHBOARD_APP_NAME" class="form-label">App name</label>
                                    <input type="text" name="TYRO_DASHBOARD_APP_NAME" id="TYRO_DASHBOARD_APP_NAME"
                                           class="form-input" maxlength="255"
                                           value="{{ old('TYRO_DASHBOARD_APP_NAME', $settings['TYRO_DASHBOARD_APP_NAME']) }}">
                                    <p class="form-hint">Displayed in the sidebar logo and page titles.</p>
                                </div>

                                <div class="form-group">
                                    <label for="TYRO_DASHBOARD_LOGO_HEIGHT" class="form-label">Logo height</label>
                                    <input type="text" name="TYRO_DASHBOARD_LOGO_HEIGHT" id="TYRO_DASHBOARD_LOGO_HEIGHT"
                                           class="form-input" maxlength="20"
                                           value="{{ old('TYRO_DASHBOARD_LOGO_HEIGHT', $settings['TYRO_DASHBOARD_LOGO_HEIGHT']) }}">
                                    <p class="form-hint">CSS height value e.g. <code>32px</code>, <code>3rem</code>.</p>
                                </div>

                            </div>

                            <div class="sys-settings-surface">
                                <h4 class="sys-settings-surface-title">Collapsible Sidebar</h4>
                                <p class="sys-settings-surface-description">Toggle collapsible sidebar, compact accordion mode, and disable example sections.</p>

                                <div class="sys-settings-toggles" style="margin-bottom:0;">
                                    <div class="sys-settings-toggle">
                                        <div class="sys-settings-toggle-top">
                                            <div>
                                                <p class="sys-settings-toggle-title">Collapsible sidebar</p>
                                                <p class="sys-settings-toggle-description">Writes <code>TYRO_DASHBOARD_COLLAPSIBLE_SIDEBAR</code>.</p>
                                            </div>
                                            <div>
                                                <input type="hidden" name="TYRO_DASHBOARD_COLLAPSIBLE_SIDEBAR" value="0">
                                                <label class="toggle-label">
                                                    <input type="checkbox" name="TYRO_DASHBOARD_COLLAPSIBLE_SIDEBAR" value="1" class="toggle-input" {{ old('TYRO_DASHBOARD_COLLAPSIBLE_SIDEBAR', $settings['TYRO_DASHBOARD_COLLAPSIBLE_SIDEBAR']) ? 'checked' : '' }}>
                                                    <span class="toggle-slider"></span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="sys-settings-toggle">
                                        <div class="sys-settings-toggle-top">
                                            <div>
                                                <p class="sys-settings-toggle-title">Compact accordion mode</p>
                                                <p class="sys-settings-toggle-description">Writes <code>TYRO_DASHBOARD_SIDEBAR_ACCORDION_COMPACT</code>.</p>
                                            </div>
                                            <div>
                                                <input type="hidden" name="TYRO_DASHBOARD_SIDEBAR_ACCORDION_COMPACT" value="0">
                                                <label class="toggle-label">
                                                    <input type="checkbox" name="TYRO_DASHBOARD_SIDEBAR_ACCORDION_COMPACT" value="1" class="toggle-input" {{ old('TYRO_DASHBOARD_SIDEBAR_ACCORDION_COMPACT', $settings['TYRO_DASHBOARD_SIDEBAR_ACCORDION_COMPACT']) ? 'checked' : '' }}>
                                                    <span class="toggle-slider"></span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="sys-settings-toggle">
                                        <div class="sys-settings-toggle-top">
                                            <div>
                                                <p class="sys-settings-toggle-title">Disable examples</p>
                                                <p class="sys-settings-toggle-description">Writes <code>TYRO_DASHBOARD_DISABLE_EXAMPLES</code>.</p>
                                            </div>
                                            <div>
                                                <input type="hidden" name="TYRO_DASHBOARD_DISABLE_EXAMPLES" value="0">
                                                <label class="toggle-label">
                                                    <input type="checkbox" name="TYRO_DASHBOARD_DISABLE_EXAMPLES" value="1" class="toggle-input" {{ old('TYRO_DASHBOARD_DISABLE_EXAMPLES', $settings['TYRO_DASHBOARD_DISABLE_EXAMPLES']) ? 'checked' : '' }}>
                                                    <span class="toggle-slider"></span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="sys-settings-surface">
                                <h4 class="sys-settings-surface-title">Feature Flags</h4>
                                <p class="sys-settings-surface-description">Enable or disable dashboard features.</p>

                                <div class="sys-settings-toggles" style="margin-bottom:0;">
                                    <div class="sys-settings-toggle">
                                        <div class="sys-settings-toggle-top">
                                            <div>
                                                <p class="sys-settings-toggle-title">Invitation system</p>
                                                <p class="sys-settings-toggle-description">Writes <code>TYRO_DASHBOARD_ENABLE_INVITATION</code>.</p>
                                            </div>
                                            <div>
                                                <input type="hidden" name="TYRO_DASHBOARD_ENABLE_INVITATION" value="0">
                                                <label class="toggle-label">
                                                    <input type="checkbox" name="TYRO_DASHBOARD_ENABLE_INVITATION" value="1" class="toggle-input" {{ old('TYRO_DASHBOARD_ENABLE_INVITATION', $settings['TYRO_DASHBOARD_ENABLE_INVITATION']) ? 'checked' : '' }}>
                                                    <span class="toggle-slider"></span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="sys-settings-toggle">
                                        <div class="sys-settings-toggle-top">
                                            <div>
                                                <p class="sys-settings-toggle-title">Audit logs</p>
                                                <p class="sys-settings-toggle-description">Writes <code>TYRO_DASHBOARD_ENABLE_AUDIT_LOGS</code>.</p>
                                            </div>
                                            <div>
                                                <input type="hidden" name="TYRO_DASHBOARD_ENABLE_AUDIT_LOGS" value="0">
                                                <label class="toggle-label">
                                                    <input type="checkbox" name="TYRO_DASHBOARD_ENABLE_AUDIT_LOGS" value="1" class="toggle-input" {{ old('TYRO_DASHBOARD_ENABLE_AUDIT_LOGS', $settings['TYRO_DASHBOARD_ENABLE_AUDIT_LOGS']) ? 'checked' : '' }}>
                                                    <span class="toggle-slider"></span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="sys-settings-surface">
                                <h4 class="sys-settings-surface-title">Notifications</h4>
                                <p class="sys-settings-surface-description">Choose between legacy alerts or toast-style notifications.</p>

                                <div class="form-group" style="margin-bottom:0.85rem;">
                                    <label for="TYRO_DASHBOARD_NOTIFICATION_STYLE" class="form-label">Notification style</label>
                                    <select name="TYRO_DASHBOARD_NOTIFICATION_STYLE" id="TYRO_DASHBOARD_NOTIFICATION_STYLE" class="form-select">
                                        <option value="legacy" {{ old('TYRO_DASHBOARD_NOTIFICATION_STYLE', $settings['TYRO_DASHBOARD_NOTIFICATION_STYLE']) === 'legacy' ? 'selected' : '' }}>Legacy</option>
                                        <option value="toast" {{ old('TYRO_DASHBOARD_NOTIFICATION_STYLE', $settings['TYRO_DASHBOARD_NOTIFICATION_STYLE']) === 'toast' ? 'selected' : '' }}>Toast</option>
                                    </select>
                                </div>

                                <div class="form-group" style="margin-bottom:0;">
                                    <label for="TYRO_DASHBOARD_TOAST_POSITION" class="form-label">Toast position</label>
                                    <select name="TYRO_DASHBOARD_TOAST_POSITION" id="TYRO_DASHBOARD_TOAST_POSITION" class="form-select">
                                        <option value="top-right" {{ old('TYRO_DASHBOARD_TOAST_POSITION', $settings['TYRO_DASHBOARD_TOAST_POSITION']) === 'top-right' ? 'selected' : '' }}>Top right</option>
                                        <option value="bottom-right" {{ old('TYRO_DASHBOARD_TOAST_POSITION', $settings['TYRO_DASHBOARD_TOAST_POSITION']) === 'bottom-right' ? 'selected' : '' }}>Bottom right</option>
                                    </select>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

            {{-- RBAC Tab --}}
            <div class="vtabs-panel" id="vtab-rbac">
                <div class="card">
                    <div class="card-header" style="display:flex;align-items:center;justify-content:space-between;gap:1rem;">
                        <h3 class="card-title">RBAC</h3>
                        <button type="submit" form="systemSettingsForm" class="btn btn-primary btn-sm section-save-button">Save</button>
                    </div>
                    <div class="card-body">
                        <div class="sys-settings-section-intro">
                            <div class="sys-settings-section-copy">
                                <h4 class="sys-settings-section-heading">Manage Tyro RBAC environment configuration</h4>
                                <p class="sys-settings-section-description">Control caching, default roles, password rules, and API availability. All values are written to the <code>.env</code> file.</p>
                            </div>
                            <span class="sys-settings-section-badge">.env</span>
                        </div>

                        <div class="sys-settings-grid">
                            <div class="sys-settings-surface">
                                <h4 class="sys-settings-surface-title">Caching</h4>
                                <p class="sys-settings-surface-description">Control RBAC cache behavior.</p>

                                <div class="sys-settings-toggles" style="margin-bottom:0.85rem;">
                                    <div class="sys-settings-toggle">
                                        <div class="sys-settings-toggle-top">
                                            <div>
                                                <p class="sys-settings-toggle-title">Enable RBAC cache</p>
                                                <p class="sys-settings-toggle-description">Writes <code>TYRO_CACHE_ENABLED</code>.</p>
                                            </div>
                                            <div>
                                                <input type="hidden" name="TYRO_CACHE_ENABLED" value="0">
                                                <label class="toggle-label">
                                                    <input type="checkbox" name="TYRO_CACHE_ENABLED" value="1" class="toggle-input" {{ old('TYRO_CACHE_ENABLED', $settings['TYRO_CACHE_ENABLED']) !== false ? 'checked' : '' }}>
                                                    <span class="toggle-slider"></span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group" style="margin-bottom:0;">
                                    <label for="TYRO_CACHE_TTL" class="form-label">Cache TTL (seconds)</label>
                                    <input type="number" name="TYRO_CACHE_TTL" id="TYRO_CACHE_TTL"
                                           class="form-input" min="0" max="86400"
                                           value="{{ old('TYRO_CACHE_TTL', $settings['TYRO_CACHE_TTL']) }}">
                                    <p class="form-hint" style="margin-top:0.35rem;">Default is 300 (5 minutes).</p>
                                </div>
                            </div>

                            <div class="sys-settings-surface">
                                <h4 class="sys-settings-surface-title">API &amp; Tokens</h4>
                                <p class="sys-settings-surface-description">Control API availability and token behavior.</p>

                                <div class="sys-settings-toggles" style="margin-bottom:0;">
                                    <div class="sys-settings-toggle">
                                        <div class="sys-settings-toggle-top">
                                            <div>
                                                <p class="sys-settings-toggle-title">Disable Tyro API</p>
                                                <p class="sys-settings-toggle-description">Writes <code>TYRO_DISABLE_API</code>.</p>
                                            </div>
                                            <div>
                                                <input type="hidden" name="TYRO_DISABLE_API" value="0">
                                                <label class="toggle-label">
                                                    <input type="checkbox" name="TYRO_DISABLE_API" value="1" class="toggle-input" {{ old('TYRO_DISABLE_API', $settings['TYRO_DISABLE_API']) ? 'checked' : '' }}>
                                                    <span class="toggle-slider"></span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="sys-settings-toggle">
                                        <div class="sys-settings-toggle-top">
                                            <div>
                                                <p class="sys-settings-toggle-title">Disable Tyro commands</p>
                                                <p class="sys-settings-toggle-description">Writes <code>TYRO_DISABLE_COMMANDS</code>.</p>
                                            </div>
                                            <div>
                                                <input type="hidden" name="TYRO_DISABLE_COMMANDS" value="0">
                                                <label class="toggle-label">
                                                    <input type="checkbox" name="TYRO_DISABLE_COMMANDS" value="1" class="toggle-input" {{ old('TYRO_DISABLE_COMMANDS', $settings['TYRO_DISABLE_COMMANDS']) ? 'checked' : '' }}>
                                                    <span class="toggle-slider"></span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="sys-settings-toggle">
                                        <div class="sys-settings-toggle-top">
                                            <div>
                                                <p class="sys-settings-toggle-title">Delete previous tokens on login</p>
                                                <p class="sys-settings-toggle-description">Writes <code>DELETE_PREVIOUS_ACCESS_TOKENS_ON_LOGIN</code>.</p>
                                            </div>
                                            <div>
                                                <input type="hidden" name="DELETE_PREVIOUS_ACCESS_TOKENS_ON_LOGIN" value="0">
                                                <label class="toggle-label">
                                                    <input type="checkbox" name="DELETE_PREVIOUS_ACCESS_TOKENS_ON_LOGIN" value="1" class="toggle-input" {{ old('DELETE_PREVIOUS_ACCESS_TOKENS_ON_LOGIN', $settings['DELETE_PREVIOUS_ACCESS_TOKENS_ON_LOGIN']) ? 'checked' : '' }}>
                                                    <span class="toggle-slider"></span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="sys-settings-surface">
                                <h4 class="sys-settings-surface-title">Default Role</h4>
                                <p class="sys-settings-surface-description">The role slug assigned to new users.</p>

                                <div class="form-group" style="margin-bottom:0;">
                                    <label for="DEFAULT_ROLE_SLUG" class="form-label">Default role slug</label>
                                    <input type="text" name="DEFAULT_ROLE_SLUG" id="DEFAULT_ROLE_SLUG"
                                           class="form-input" maxlength="100"
                                           value="{{ old('DEFAULT_ROLE_SLUG', $settings['DEFAULT_ROLE_SLUG']) }}">
                                    <p class="form-hint">Writes <code>DEFAULT_ROLE_SLUG</code> in <code>.env</code>.</p>
                                </div>
                            </div>

                            <div class="sys-settings-surface">
                                <h4 class="sys-settings-surface-title">Password Rules</h4>
                                <p class="sys-settings-surface-description">Control password requirements for registration and updates.</p>

                                <div class="form-group" style="margin-bottom:0.85rem;">
                                    <label for="TYRO_PASSWORD_MIN_LENGTH" class="form-label">Minimum password length</label>
                                    <input type="number" name="TYRO_PASSWORD_MIN_LENGTH" id="TYRO_PASSWORD_MIN_LENGTH"
                                           class="form-input" min="4" max="100"
                                           value="{{ old('TYRO_PASSWORD_MIN_LENGTH', $settings['TYRO_PASSWORD_MIN_LENGTH']) }}">
                                </div>

                                <div class="sys-settings-toggles" style="margin-bottom:0;">
                                    <div class="sys-settings-toggle">
                                        <div class="sys-settings-toggle-top">
                                            <div>
                                                <p class="sys-settings-toggle-title">Require uppercase</p>
                                                <p class="sys-settings-toggle-description">Writes <code>TYRO_PASSWORD_REQUIRE_UPPERCASE</code>.</p>
                                            </div>
                                            <div>
                                                <input type="hidden" name="TYRO_PASSWORD_REQUIRE_UPPERCASE" value="0">
                                                <label class="toggle-label">
                                                    <input type="checkbox" name="TYRO_PASSWORD_REQUIRE_UPPERCASE" value="1" class="toggle-input" {{ old('TYRO_PASSWORD_REQUIRE_UPPERCASE', $settings['TYRO_PASSWORD_REQUIRE_UPPERCASE']) ? 'checked' : '' }}>
                                                    <span class="toggle-slider"></span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="sys-settings-toggle">
                                        <div class="sys-settings-toggle-top">
                                            <div>
                                                <p class="sys-settings-toggle-title">Require lowercase</p>
                                                <p class="sys-settings-toggle-description">Writes <code>TYRO_PASSWORD_REQUIRE_LOWERCASE</code>.</p>
                                            </div>
                                            <div>
                                                <input type="hidden" name="TYRO_PASSWORD_REQUIRE_LOWERCASE" value="0">
                                                <label class="toggle-label">
                                                    <input type="checkbox" name="TYRO_PASSWORD_REQUIRE_LOWERCASE" value="1" class="toggle-input" {{ old('TYRO_PASSWORD_REQUIRE_LOWERCASE', $settings['TYRO_PASSWORD_REQUIRE_LOWERCASE']) ? 'checked' : '' }}>
                                                    <span class="toggle-slider"></span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="sys-settings-toggle">
                                        <div class="sys-settings-toggle-top">
                                            <div>
                                                <p class="sys-settings-toggle-title">Require numbers</p>
                                                <p class="sys-settings-toggle-description">Writes <code>TYRO_PASSWORD_REQUIRE_NUMBERS</code>.</p>
                                            </div>
                                            <div>
                                                <input type="hidden" name="TYRO_PASSWORD_REQUIRE_NUMBERS" value="0">
                                                <label class="toggle-label">
                                                    <input type="checkbox" name="TYRO_PASSWORD_REQUIRE_NUMBERS" value="1" class="toggle-input" {{ old('TYRO_PASSWORD_REQUIRE_NUMBERS', $settings['TYRO_PASSWORD_REQUIRE_NUMBERS']) ? 'checked' : '' }}>
                                                    <span class="toggle-slider"></span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="sys-settings-toggle">
                                        <div class="sys-settings-toggle-top">
                                            <div>
                                                <p class="sys-settings-toggle-title">Require special characters</p>
                                                <p class="sys-settings-toggle-description">Writes <code>TYRO_PASSWORD_REQUIRE_SPECIAL_CHARS</code>.</p>
                                            </div>
                                            <div>
                                                <input type="hidden" name="TYRO_PASSWORD_REQUIRE_SPECIAL_CHARS" value="0">
                                                <label class="toggle-label">
                                                    <input type="checkbox" name="TYRO_PASSWORD_REQUIRE_SPECIAL_CHARS" value="1" class="toggle-input" {{ old('TYRO_PASSWORD_REQUIRE_SPECIAL_CHARS', $settings['TYRO_PASSWORD_REQUIRE_SPECIAL_CHARS']) ? 'checked' : '' }}>
                                                    <span class="toggle-slider"></span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="sys-settings-toggle">
                                        <div class="sys-settings-toggle-top">
                                            <div>
                                                <p class="sys-settings-toggle-title">Block common passwords</p>
                                                <p class="sys-settings-toggle-description">Writes <code>TYRO_PASSWORD_CHECK_COMMON</code>.</p>
                                            </div>
                                            <div>
                                                <input type="hidden" name="TYRO_PASSWORD_CHECK_COMMON" value="0">
                                                <label class="toggle-label">
                                                    <input type="checkbox" name="TYRO_PASSWORD_CHECK_COMMON" value="1" class="toggle-input" {{ old('TYRO_PASSWORD_CHECK_COMMON', $settings['TYRO_PASSWORD_CHECK_COMMON']) ? 'checked' : '' }}>
                                                    <span class="toggle-slider"></span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- RBAC Advanced Tab --}}
            <div class="vtabs-panel" id="vtab-rbac-advanced">
                <div class="card">
                    <div class="card-header" style="display:flex;align-items:center;justify-content:space-between;gap:1rem;">
                        <h3 class="card-title">RBAC Advanced</h3>
                        <button type="submit" form="systemSettingsForm" class="btn btn-primary btn-sm section-save-button">Save</button>
                    </div>
                    <div class="card-body">
                        <div class="sys-settings-section-intro">
                            <div class="sys-settings-section-copy">
                                <h4 class="sys-settings-section-heading">Advanced RBAC environment configuration</h4>
                                <p class="sys-settings-section-description">Fine-tune cache driver, audit logs, route prefix, and extended password rules.</p>
                            </div>
                            <span class="sys-settings-section-badge">Advanced</span>
                        </div>

                        <div class="sys-settings-grid">
                            <div class="sys-settings-surface">
                                <h4 class="sys-settings-surface-title">Cache Driver</h4>
                                <p class="sys-settings-surface-description">Configure the cache store backend.</p>

                                <div class="form-group" style="margin-bottom:0;">
                                    <label for="TYRO_CACHE_STORE" class="form-label">Cache store</label>
                                    <input type="text" name="TYRO_CACHE_STORE" id="TYRO_CACHE_STORE"
                                           class="form-input" maxlength="50"
                                           value="{{ old('TYRO_CACHE_STORE', $settings['TYRO_CACHE_STORE']) }}">
                                    <p class="form-hint">e.g. <code>redis</code>, <code>file</code>, <code>memcached</code>. Writes <code>TYRO_CACHE_STORE</code>.</p>
                                </div>
                            </div>

                            <div class="sys-settings-surface">
                                <h4 class="sys-settings-surface-title">Audit Logs</h4>
                                <p class="sys-settings-surface-description">Control audit log behaviour and retention.</p>

                                <div class="sys-settings-toggles" style="margin-bottom:0.85rem;">
                                    <div class="sys-settings-toggle">
                                        <div class="sys-settings-toggle-top">
                                            <div>
                                                <p class="sys-settings-toggle-title">Enable audit logs</p>
                                                <p class="sys-settings-toggle-description">Writes <code>TYRO_AUDIT_ENABLED</code>.</p>
                                            </div>
                                            <div>
                                                <input type="hidden" name="TYRO_AUDIT_ENABLED" value="0">
                                                <label class="toggle-label">
                                                    <input type="checkbox" name="TYRO_AUDIT_ENABLED" value="1" class="toggle-input" id="tyro_audit_enabled" {{ old('TYRO_AUDIT_ENABLED', $settings['TYRO_AUDIT_ENABLED']) !== false ? 'checked' : '' }}>
                                                    <span class="toggle-slider"></span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group" style="margin-bottom:0;" id="tyro_audit_retention_group">
                                    <label for="TYRO_AUDIT_RETENTION_DAYS" class="form-label">Retention days</label>
                                    <input type="number" name="TYRO_AUDIT_RETENTION_DAYS" id="TYRO_AUDIT_RETENTION_DAYS"
                                           class="form-input" min="1" max="3650"
                                           value="{{ old('TYRO_AUDIT_RETENTION_DAYS', $settings['TYRO_AUDIT_RETENTION_DAYS']) }}">
                                    <p class="form-hint">Writes <code>TYRO_AUDIT_RETENTION_DAYS</code>.</p>
                                </div>
                            </div>

                            <div class="sys-settings-surface">
                                <h4 class="sys-settings-surface-title">API Routing</h4>
                                <p class="sys-settings-surface-description">Set the API route prefix.</p>

                                <div class="form-group" style="margin-bottom:0;">
                                    <label for="TYRO_ROUTE_PREFIX" class="form-label">Route prefix</label>
                                    <input type="text" name="TYRO_ROUTE_PREFIX" id="TYRO_ROUTE_PREFIX"
                                           class="form-input" maxlength="50"
                                           value="{{ old('TYRO_ROUTE_PREFIX', $settings['TYRO_ROUTE_PREFIX']) }}">
                                    <p class="form-hint">API route prefix. Writes <code>TYRO_ROUTE_PREFIX</code>.</p>
                                </div>
                            </div>

                            <div class="sys-settings-surface">
                                <h4 class="sys-settings-surface-title">Password Rules</h4>
                                <p class="sys-settings-surface-description">Extended password validation rules.</p>

                                <div class="sys-settings-toggles" style="margin-bottom:0;">
                                    <div class="sys-settings-toggle">
                                        <div class="sys-settings-toggle-top">
                                            <div>
                                                <p class="sys-settings-toggle-title">Require password confirmation</p>
                                                <p class="sys-settings-toggle-description">Writes <code>TYRO_PASSWORD_REQUIRE_CONFIRMATION</code>.</p>
                                            </div>
                                            <div>
                                                <input type="hidden" name="TYRO_PASSWORD_REQUIRE_CONFIRMATION" value="0">
                                                <label class="toggle-label">
                                                    <input type="checkbox" name="TYRO_PASSWORD_REQUIRE_CONFIRMATION" value="1" class="toggle-input" {{ old('TYRO_PASSWORD_REQUIRE_CONFIRMATION', $settings['TYRO_PASSWORD_REQUIRE_CONFIRMATION']) ? 'checked' : '' }}>
                                                    <span class="toggle-slider"></span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="sys-settings-toggle">
                                        <div class="sys-settings-toggle-top">
                                            <div>
                                                <p class="sys-settings-toggle-title">Disallow user info in password</p>
                                                <p class="sys-settings-toggle-description">Prevents passwords containing email/name parts. Writes <code>TYRO_PASSWORD_DISALLOW_USER_INFO</code>.</p>
                                            </div>
                                            <div>
                                                <input type="hidden" name="TYRO_PASSWORD_DISALLOW_USER_INFO" value="0">
                                                <label class="toggle-label">
                                                    <input type="checkbox" name="TYRO_PASSWORD_DISALLOW_USER_INFO" value="1" class="toggle-input" {{ old('TYRO_PASSWORD_DISALLOW_USER_INFO', $settings['TYRO_PASSWORD_DISALLOW_USER_INFO']) ? 'checked' : '' }}>
                                                    <span class="toggle-slider"></span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group" style="margin-top:0.85rem; margin-bottom:0;">
                                    <label for="TYRO_PASSWORD_MAX_LENGTH" class="form-label">Maximum password length</label>
                                    <input type="number" name="TYRO_PASSWORD_MAX_LENGTH" id="TYRO_PASSWORD_MAX_LENGTH"
                                           class="form-input" min="0" max="500"
                                           value="{{ old('TYRO_PASSWORD_MAX_LENGTH', $settings['TYRO_PASSWORD_MAX_LENGTH']) }}">
                                    <p class="form-hint">Leave 0 for no limit. Writes <code>TYRO_PASSWORD_MAX_LENGTH</code>.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Login & Auth Tab --}}
            <div class="vtabs-panel" id="vtab-login-auth">
                <div class="card">
                    <div class="card-header" style="display:flex;align-items:center;justify-content:space-between;gap:1rem;">
                        <h3 class="card-title">Login &amp; Auth</h3>
                        <button type="submit" form="systemSettingsForm" class="btn btn-primary btn-sm section-save-button">Save</button>
                    </div>
                    <div class="card-body">
                        <div class="sys-settings-section-intro">
                            <div class="sys-settings-section-copy">
                                <h4 class="sys-settings-section-heading">Manage Tyro Login environment configuration</h4>
                                <p class="sys-settings-section-description">Control layout, registration, OTP, 2FA, captcha, social login, email settings, and lockout protection.</p>
                            </div>
                            <span class="sys-settings-section-badge">.env</span>
                        </div>

                        <div class="sys-settings-grid">
                            <div class="sys-settings-surface">
                                <h4 class="sys-settings-surface-title">Layout &amp; Branding</h4>
                                <p class="sys-settings-surface-description">Customize the appearance of auth pages.</p>

                                <div class="form-group" style="margin-bottom:0.85rem;">
                                    <label for="TYRO_LOGIN_LAYOUT" class="form-label">Auth page layout</label>
                                    <select name="TYRO_LOGIN_LAYOUT" id="TYRO_LOGIN_LAYOUT" class="form-select">
                                        <option value="centered" {{ old('TYRO_LOGIN_LAYOUT', $settings['TYRO_LOGIN_LAYOUT']) === 'centered' ? 'selected' : '' }}>Centered</option>
                                        <option value="split-left" {{ old('TYRO_LOGIN_LAYOUT', $settings['TYRO_LOGIN_LAYOUT']) === 'split-left' ? 'selected' : '' }}>Split left</option>
                                        <option value="split-right" {{ old('TYRO_LOGIN_LAYOUT', $settings['TYRO_LOGIN_LAYOUT']) === 'split-right' ? 'selected' : '' }}>Split right</option>
                                        <option value="fullscreen" {{ old('TYRO_LOGIN_LAYOUT', $settings['TYRO_LOGIN_LAYOUT']) === 'fullscreen' ? 'selected' : '' }}>Fullscreen</option>
                                        <option value="card" {{ old('TYRO_LOGIN_LAYOUT', $settings['TYRO_LOGIN_LAYOUT']) === 'card' ? 'selected' : '' }}>Card</option>
                                    </select>
                                </div>

                                <div class="form-group" style="margin-bottom:0.85rem;">
                                    <label for="TYRO_LOGIN_APP_NAME" class="form-label">Auth app name</label>
                                    <input type="text" name="TYRO_LOGIN_APP_NAME" id="TYRO_LOGIN_APP_NAME"
                                           class="form-input" maxlength="255"
                                           value="{{ old('TYRO_LOGIN_APP_NAME', $settings['TYRO_LOGIN_APP_NAME']) }}">
                                </div>

                                <div class="form-group" style="margin-bottom:0;">
                                    <label for="TYRO_LOGIN_BACKGROUND_IMAGE" class="form-label">Background image URL</label>
                                    <input type="url" name="TYRO_LOGIN_BACKGROUND_IMAGE" id="TYRO_LOGIN_BACKGROUND_IMAGE"
                                           class="form-input" maxlength="500"
                                           value="{{ old('TYRO_LOGIN_BACKGROUND_IMAGE', $settings['TYRO_LOGIN_BACKGROUND_IMAGE']) }}">
                                    <p class="form-hint">Used for split and fullscreen layouts.</p>
                                </div>
                            </div>

                            <div class="sys-settings-surface">
                                <h4 class="sys-settings-surface-title">Redirects</h4>
                                <p class="sys-settings-surface-description">Where users are sent after login/logout.</p>

                                <div class="form-group" style="margin-bottom:0.85rem;">
                                    <label for="TYRO_LOGIN_REDIRECT_AFTER_LOGIN" class="form-label">After login</label>
                                    <input type="text" name="TYRO_LOGIN_REDIRECT_AFTER_LOGIN" id="TYRO_LOGIN_REDIRECT_AFTER_LOGIN"
                                           class="form-input" maxlength="255"
                                           value="{{ old('TYRO_LOGIN_REDIRECT_AFTER_LOGIN', $settings['TYRO_LOGIN_REDIRECT_AFTER_LOGIN']) }}">
                                </div>

                                <div class="form-group" style="margin-bottom:0.85rem;">
                                    <label for="TYRO_LOGIN_REDIRECT_AFTER_LOGOUT" class="form-label">After logout</label>
                                    <input type="text" name="TYRO_LOGIN_REDIRECT_AFTER_LOGOUT" id="TYRO_LOGIN_REDIRECT_AFTER_LOGOUT"
                                           class="form-input" maxlength="255"
                                           value="{{ old('TYRO_LOGIN_REDIRECT_AFTER_LOGOUT', $settings['TYRO_LOGIN_REDIRECT_AFTER_LOGOUT']) }}">
                                </div>

                                <div class="form-group" style="margin-bottom:0.85rem;">
                                    <label for="TYRO_LOGIN_REDIRECT_AFTER_REGISTER" class="form-label">After register</label>
                                    <input type="text" name="TYRO_LOGIN_REDIRECT_AFTER_REGISTER" id="TYRO_LOGIN_REDIRECT_AFTER_REGISTER"
                                           class="form-input" maxlength="255"
                                           value="{{ old('TYRO_LOGIN_REDIRECT_AFTER_REGISTER', $settings['TYRO_LOGIN_REDIRECT_AFTER_REGISTER']) }}">
                                </div>

                                <div class="form-group" style="margin-bottom:0;">
                                    <label for="TYRO_LOGIN_REDIRECT_AFTER_EMAIL_VERIFICATION" class="form-label">After email verification</label>
                                    <input type="text" name="TYRO_LOGIN_REDIRECT_AFTER_EMAIL_VERIFICATION" id="TYRO_LOGIN_REDIRECT_AFTER_EMAIL_VERIFICATION"
                                           class="form-input" maxlength="255"
                                           value="{{ old('TYRO_LOGIN_REDIRECT_AFTER_EMAIL_VERIFICATION', $settings['TYRO_LOGIN_REDIRECT_AFTER_EMAIL_VERIFICATION']) }}">
                                </div>
                            </div>

                            <div class="sys-settings-surface">
                                <h4 class="sys-settings-surface-title">Registration &amp; Login</h4>
                                <p class="sys-settings-surface-description">Basic registration and login form preferences.</p>

                                <div class="sys-settings-toggles" style="margin-bottom:0.85rem;">
                                    <div class="sys-settings-toggle">
                                        <div class="sys-settings-toggle-top">
                                            <div>
                                                <p class="sys-settings-toggle-title">Enable registration</p>
                                                <p class="sys-settings-toggle-description">Writes <code>TYRO_LOGIN_REGISTRATION_ENABLED</code>.</p>
                                            </div>
                                            <div>
                                                <input type="hidden" name="TYRO_LOGIN_REGISTRATION_ENABLED" value="0">
                                                <label class="toggle-label">
                                                    <input type="checkbox" name="TYRO_LOGIN_REGISTRATION_ENABLED" value="1" class="toggle-input" {{ old('TYRO_LOGIN_REGISTRATION_ENABLED', $settings['TYRO_LOGIN_REGISTRATION_ENABLED']) ? 'checked' : '' }}>
                                                    <span class="toggle-slider"></span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="sys-settings-toggle">
                                        <div class="sys-settings-toggle-top">
                                            <div>
                                                <p class="sys-settings-toggle-title">Require email verification</p>
                                                <p class="sys-settings-toggle-description">Writes <code>TYRO_LOGIN_REQUIRE_EMAIL_VERIFICATION</code>.</p>
                                            </div>
                                            <div>
                                                <input type="hidden" name="TYRO_LOGIN_REQUIRE_EMAIL_VERIFICATION" value="0">
                                                <label class="toggle-label">
                                                    <input type="checkbox" name="TYRO_LOGIN_REQUIRE_EMAIL_VERIFICATION" value="1" class="toggle-input" {{ old('TYRO_LOGIN_REQUIRE_EMAIL_VERIFICATION', $settings['TYRO_LOGIN_REQUIRE_EMAIL_VERIFICATION']) ? 'checked' : '' }}>
                                                    <span class="toggle-slider"></span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="sys-settings-toggle">
                                        <div class="sys-settings-toggle-top">
                                            <div>
                                                <p class="sys-settings-toggle-title">Show "Remember Me"</p>
                                                <p class="sys-settings-toggle-description">Writes <code>TYRO_LOGIN_REMEMBER_ME</code>.</p>
                                            </div>
                                            <div>
                                                <input type="hidden" name="TYRO_LOGIN_REMEMBER_ME" value="0">
                                                <label class="toggle-label">
                                                    <input type="checkbox" name="TYRO_LOGIN_REMEMBER_ME" value="1" class="toggle-input" {{ old('TYRO_LOGIN_REMEMBER_ME', $settings['TYRO_LOGIN_REMEMBER_ME']) ? 'checked' : '' }}>
                                                    <span class="toggle-slider"></span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="sys-settings-toggle">
                                        <div class="sys-settings-toggle-top">
                                            <div>
                                                <p class="sys-settings-toggle-title">Show "Forgot Password"</p>
                                                <p class="sys-settings-toggle-description">Writes <code>TYRO_LOGIN_FORGOT_PASSWORD</code>.</p>
                                            </div>
                                            <div>
                                                <input type="hidden" name="TYRO_LOGIN_FORGOT_PASSWORD" value="0">
                                                <label class="toggle-label">
                                                    <input type="checkbox" name="TYRO_LOGIN_FORGOT_PASSWORD" value="1" class="toggle-input" {{ old('TYRO_LOGIN_FORGOT_PASSWORD', $settings['TYRO_LOGIN_FORGOT_PASSWORD']) ? 'checked' : '' }}>
                                                    <span class="toggle-slider"></span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group" style="margin-bottom:0;">
                                    <label for="TYRO_LOGIN_FIELD" class="form-label">Login field</label>
                                    <select name="TYRO_LOGIN_FIELD" id="TYRO_LOGIN_FIELD" class="form-select">
                                        <option value="email" {{ old('TYRO_LOGIN_FIELD', $settings['TYRO_LOGIN_FIELD']) === 'email' ? 'selected' : '' }}>Email</option>
                                        <option value="username" {{ old('TYRO_LOGIN_FIELD', $settings['TYRO_LOGIN_FIELD']) === 'username' ? 'selected' : '' }}>Username</option>
                                        <option value="both" {{ old('TYRO_LOGIN_FIELD', $settings['TYRO_LOGIN_FIELD']) === 'both' ? 'selected' : '' }}>Both</option>
                                    </select>
                                </div>
                            </div>

                            <div class="sys-settings-surface">
                                <h4 class="sys-settings-surface-title">Security Features</h4>
                                <p class="sys-settings-surface-description">Captcha, OTP, 2FA, and magic link settings.</p>

                                <div class="sys-settings-toggles" style="margin-bottom:0.85rem;">
                                    <div class="sys-settings-toggle">
                                        <div class="sys-settings-toggle-top">
                                            <div>
                                                <p class="sys-settings-toggle-title">Captcha on login</p>
                                                <p class="sys-settings-toggle-description">Writes <code>TYRO_LOGIN_CAPTCHA_LOGIN</code>.</p>
                                            </div>
                                            <div>
                                                <input type="hidden" name="TYRO_LOGIN_CAPTCHA_LOGIN" value="0">
                                                <label class="toggle-label">
                                                    <input type="checkbox" name="TYRO_LOGIN_CAPTCHA_LOGIN" value="1" class="toggle-input" {{ old('TYRO_LOGIN_CAPTCHA_LOGIN', $settings['TYRO_LOGIN_CAPTCHA_LOGIN']) ? 'checked' : '' }}>
                                                    <span class="toggle-slider"></span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="sys-settings-toggle">
                                        <div class="sys-settings-toggle-top">
                                            <div>
                                                <p class="sys-settings-toggle-title">Captcha on registration</p>
                                                <p class="sys-settings-toggle-description">Writes <code>TYRO_LOGIN_CAPTCHA_REGISTER</code>.</p>
                                            </div>
                                            <div>
                                                <input type="hidden" name="TYRO_LOGIN_CAPTCHA_REGISTER" value="0">
                                                <label class="toggle-label">
                                                    <input type="checkbox" name="TYRO_LOGIN_CAPTCHA_REGISTER" value="1" class="toggle-input" {{ old('TYRO_LOGIN_CAPTCHA_REGISTER', $settings['TYRO_LOGIN_CAPTCHA_REGISTER']) ? 'checked' : '' }}>
                                                    <span class="toggle-slider"></span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="sys-settings-toggle">
                                        <div class="sys-settings-toggle-top">
                                            <div>
                                                <p class="sys-settings-toggle-title">Enable OTP</p>
                                                <p class="sys-settings-toggle-description">Writes <code>TYRO_LOGIN_OTP_ENABLED</code>.</p>
                                            </div>
                                            <div>
                                                <input type="hidden" name="TYRO_LOGIN_OTP_ENABLED" value="0">
                                                <label class="toggle-label">
                                                    <input type="checkbox" name="TYRO_LOGIN_OTP_ENABLED" value="1" class="toggle-input" {{ old('TYRO_LOGIN_OTP_ENABLED', $settings['TYRO_LOGIN_OTP_ENABLED']) ? 'checked' : '' }}>
                                                    <span class="toggle-slider"></span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="sys-settings-toggle">
                                        <div class="sys-settings-toggle-top">
                                            <div>
                                                <p class="sys-settings-toggle-title">Enable 2FA</p>
                                                <p class="sys-settings-toggle-description">Writes <code>TYRO_LOGIN_2FA_ENABLED</code>.</p>
                                            </div>
                                            <div>
                                                <input type="hidden" name="TYRO_LOGIN_2FA_ENABLED" value="0">
                                                <label class="toggle-label">
                                                    <input type="checkbox" name="TYRO_LOGIN_2FA_ENABLED" value="1" class="toggle-input" {{ old('TYRO_LOGIN_2FA_ENABLED', $settings['TYRO_LOGIN_2FA_ENABLED']) ? 'checked' : '' }}>
                                                    <span class="toggle-slider"></span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="sys-settings-toggle">
                                        <div class="sys-settings-toggle-top">
                                            <div>
                                                <p class="sys-settings-toggle-title">Enable magic links</p>
                                                <p class="sys-settings-toggle-description">Writes <code>TYRO_LOGIN_ENABLE_MAGIC_LINKS</code>.</p>
                                            </div>
                                            <div>
                                                <input type="hidden" name="TYRO_LOGIN_ENABLE_MAGIC_LINKS" value="0">
                                                <label class="toggle-label">
                                                    <input type="checkbox" name="TYRO_LOGIN_ENABLE_MAGIC_LINKS" value="1" class="toggle-input" {{ old('TYRO_LOGIN_ENABLE_MAGIC_LINKS', $settings['TYRO_LOGIN_ENABLE_MAGIC_LINKS']) ? 'checked' : '' }}>
                                                    <span class="toggle-slider"></span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>

                            <div class="sys-settings-surface">
                                <h4 class="sys-settings-surface-title">Social Login</h4>
                                <p class="sys-settings-surface-description">Enable/disable social authentication.</p>

                                <div class="sys-settings-toggles" style="margin-bottom:0;">
                                    <div class="sys-settings-toggle">
                                        <div class="sys-settings-toggle-top">
                                            <div>
                                                <p class="sys-settings-toggle-title">Enable social login</p>
                                                <p class="sys-settings-toggle-description">Writes <code>TYRO_LOGIN_SOCIAL_ENABLED</code>.</p>
                                            </div>
                                            <div>
                                                <input type="hidden" name="TYRO_LOGIN_SOCIAL_ENABLED" value="0">
                                                <label class="toggle-label">
                                                    <input type="checkbox" name="TYRO_LOGIN_SOCIAL_ENABLED" value="1" class="toggle-input" {{ old('TYRO_LOGIN_SOCIAL_ENABLED', $settings['TYRO_LOGIN_SOCIAL_ENABLED']) ? 'checked' : '' }}>
                                                    <span class="toggle-slider"></span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="sys-settings-toggle">
                                        <div class="sys-settings-toggle-top">
                                            <div>
                                                <p class="sys-settings-toggle-title">Auto-register social users</p>
                                                <p class="sys-settings-toggle-description">Writes <code>TYRO_LOGIN_SOCIAL_AUTO_REGISTER</code>.</p>
                                            </div>
                                            <div>
                                                <input type="hidden" name="TYRO_LOGIN_SOCIAL_AUTO_REGISTER" value="0">
                                                <label class="toggle-label">
                                                    <input type="checkbox" name="TYRO_LOGIN_SOCIAL_AUTO_REGISTER" value="1" class="toggle-input" {{ old('TYRO_LOGIN_SOCIAL_AUTO_REGISTER', $settings['TYRO_LOGIN_SOCIAL_AUTO_REGISTER']) ? 'checked' : '' }}>
                                                    <span class="toggle-slider"></span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>

                            <div class="sys-settings-surface">
                                <h4 class="sys-settings-surface-title">Lockout Protection</h4>
                                <p class="sys-settings-surface-description">Brute-force protection settings.</p>

                                <div class="sys-settings-toggles" style="margin-bottom:0.85rem;">
                                    <div class="sys-settings-toggle">
                                        <div class="sys-settings-toggle-top">
                                            <div>
                                                <p class="sys-settings-toggle-title">Enable lockout</p>
                                                <p class="sys-settings-toggle-description">Writes <code>TYRO_LOGIN_LOCKOUT_ENABLED</code>.</p>
                                            </div>
                                            <div>
                                                <input type="hidden" name="TYRO_LOGIN_LOCKOUT_ENABLED" value="0">
                                                <label class="toggle-label">
                                                    <input type="checkbox" name="TYRO_LOGIN_LOCKOUT_ENABLED" value="1" class="toggle-input" {{ old('TYRO_LOGIN_LOCKOUT_ENABLED', $settings['TYRO_LOGIN_LOCKOUT_ENABLED']) ? 'checked' : '' }}>
                                                    <span class="toggle-slider"></span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="sys-settings-metrics" style="margin-bottom:0.85rem;">
                                    <div class="form-group sys-settings-metric" style="margin-bottom:0;">
                                        <label for="TYRO_LOGIN_LOCKOUT_MAX_ATTEMPTS" class="form-label">Max attempts</label>
                                        <input type="number" name="TYRO_LOGIN_LOCKOUT_MAX_ATTEMPTS" id="TYRO_LOGIN_LOCKOUT_MAX_ATTEMPTS"
                                               class="form-input" min="1" max="50"
                                               value="{{ old('TYRO_LOGIN_LOCKOUT_MAX_ATTEMPTS', $settings['TYRO_LOGIN_LOCKOUT_MAX_ATTEMPTS']) }}">
                                    </div>
                                    <div class="form-group sys-settings-metric" style="margin-bottom:0;">
                                        <label for="TYRO_LOGIN_LOCKOUT_DURATION" class="form-label">Duration (min)</label>
                                        <input type="number" name="TYRO_LOGIN_LOCKOUT_DURATION" id="TYRO_LOGIN_LOCKOUT_DURATION"
                                               class="form-input" min="1" max="1440"
                                               value="{{ old('TYRO_LOGIN_LOCKOUT_DURATION', $settings['TYRO_LOGIN_LOCKOUT_DURATION']) }}">
                                    </div>
                                </div>

                                <div class="sys-settings-toggle" style="margin-bottom:0;">
                                    <div class="sys-settings-toggle-top">
                                        <div>
                                            <p class="sys-settings-toggle-title">Show remaining attempts</p>
                                            <p class="sys-settings-toggle-description">Writes <code>TYRO_LOGIN_SHOW_ATTEMPTS_LEFT</code>.</p>
                                        </div>
                                        <div>
                                            <input type="hidden" name="TYRO_LOGIN_SHOW_ATTEMPTS_LEFT" value="0">
                                            <label class="toggle-label">
                                                <input type="checkbox" name="TYRO_LOGIN_SHOW_ATTEMPTS_LEFT" value="1" class="toggle-input" {{ old('TYRO_LOGIN_SHOW_ATTEMPTS_LEFT', $settings['TYRO_LOGIN_SHOW_ATTEMPTS_LEFT']) ? 'checked' : '' }}>
                                                <span class="toggle-slider"></span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="sys-settings-surface">
                                <h4 class="sys-settings-surface-title">Token Expiration</h4>
                                <p class="sys-settings-surface-description">Verification and password reset token expiry.</p>

                                <div class="sys-settings-metrics" style="margin-bottom:0;">
                                    <div class="form-group sys-settings-metric" style="margin-bottom:0;">
                                        <label for="TYRO_LOGIN_VERIFICATION_EXPIRE" class="form-label">Verification expire (min)</label>
                                        <input type="number" name="TYRO_LOGIN_VERIFICATION_EXPIRE" id="TYRO_LOGIN_VERIFICATION_EXPIRE"
                                               class="form-input" min="1" max="1440"
                                               value="{{ old('TYRO_LOGIN_VERIFICATION_EXPIRE', $settings['TYRO_LOGIN_VERIFICATION_EXPIRE']) }}">
                                    </div>
                                    <div class="form-group sys-settings-metric" style="margin-bottom:0;">
                                        <label for="TYRO_LOGIN_PASSWORD_RESET_EXPIRE" class="form-label">Password reset expire (min)</label>
                                        <input type="number" name="TYRO_LOGIN_PASSWORD_RESET_EXPIRE" id="TYRO_LOGIN_PASSWORD_RESET_EXPIRE"
                                               class="form-input" min="1" max="1440"
                                               value="{{ old('TYRO_LOGIN_PASSWORD_RESET_EXPIRE', $settings['TYRO_LOGIN_PASSWORD_RESET_EXPIRE']) }}">
                                    </div>
                                </div>
                            </div>

                            <div class="sys-settings-surface">
                                <h4 class="sys-settings-surface-title">Email Notifications</h4>
                                <p class="sys-settings-surface-description">Enable/disable individual auth emails.</p>

                                <div class="sys-settings-toggles" style="margin-bottom:0;">
                                    <div class="sys-settings-toggle">
                                        <div class="sys-settings-toggle-top">
                                            <div>
                                                <p class="sys-settings-toggle-title">OTP email</p>
                                                <p class="sys-settings-toggle-description">Writes <code>TYRO_LOGIN_EMAIL_OTP</code>.</p>
                                            </div>
                                            <div>
                                                <input type="hidden" name="TYRO_LOGIN_EMAIL_OTP" value="0">
                                                <label class="toggle-label">
                                                    <input type="checkbox" name="TYRO_LOGIN_EMAIL_OTP" value="1" class="toggle-input" {{ old('TYRO_LOGIN_EMAIL_OTP', $settings['TYRO_LOGIN_EMAIL_OTP']) ? 'checked' : '' }}>
                                                    <span class="toggle-slider"></span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="sys-settings-toggle">
                                        <div class="sys-settings-toggle-top">
                                            <div>
                                                <p class="sys-settings-toggle-title">Password reset email</p>
                                                <p class="sys-settings-toggle-description">Writes <code>TYRO_LOGIN_EMAIL_PASSWORD_RESET</code>.</p>
                                            </div>
                                            <div>
                                                <input type="hidden" name="TYRO_LOGIN_EMAIL_PASSWORD_RESET" value="0">
                                                <label class="toggle-label">
                                                    <input type="checkbox" name="TYRO_LOGIN_EMAIL_PASSWORD_RESET" value="1" class="toggle-input" {{ old('TYRO_LOGIN_EMAIL_PASSWORD_RESET', $settings['TYRO_LOGIN_EMAIL_PASSWORD_RESET']) ? 'checked' : '' }}>
                                                    <span class="toggle-slider"></span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="sys-settings-toggle">
                                        <div class="sys-settings-toggle-top">
                                            <div>
                                                <p class="sys-settings-toggle-title">Email verification email</p>
                                                <p class="sys-settings-toggle-description">Writes <code>TYRO_LOGIN_EMAIL_VERIFY</code>.</p>
                                            </div>
                                            <div>
                                                <input type="hidden" name="TYRO_LOGIN_EMAIL_VERIFY" value="0">
                                                <label class="toggle-label">
                                                    <input type="checkbox" name="TYRO_LOGIN_EMAIL_VERIFY" value="1" class="toggle-input" {{ old('TYRO_LOGIN_EMAIL_VERIFY', $settings['TYRO_LOGIN_EMAIL_VERIFY']) ? 'checked' : '' }}>
                                                    <span class="toggle-slider"></span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="sys-settings-toggle">
                                        <div class="sys-settings-toggle-top">
                                            <div>
                                                <p class="sys-settings-toggle-title">Welcome email</p>
                                                <p class="sys-settings-toggle-description">Writes <code>TYRO_LOGIN_EMAIL_WELCOME</code>.</p>
                                            </div>
                                            <div>
                                                <input type="hidden" name="TYRO_LOGIN_EMAIL_WELCOME" value="0">
                                                <label class="toggle-label">
                                                    <input type="checkbox" name="TYRO_LOGIN_EMAIL_WELCOME" value="1" class="toggle-input" {{ old('TYRO_LOGIN_EMAIL_WELCOME', $settings['TYRO_LOGIN_EMAIL_WELCOME']) ? 'checked' : '' }}>
                                                    <span class="toggle-slider"></span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="sys-settings-toggle">
                                        <div class="sys-settings-toggle-top">
                                            <div>
                                                <p class="sys-settings-toggle-title">Magic link email</p>
                                                <p class="sys-settings-toggle-description">Writes <code>TYRO_LOGIN_EMAIL_MAGIC_LINK</code>.</p>
                                            </div>
                                            <div>
                                                <input type="hidden" name="TYRO_LOGIN_EMAIL_MAGIC_LINK" value="0">
                                                <label class="toggle-label">
                                                    <input type="checkbox" name="TYRO_LOGIN_EMAIL_MAGIC_LINK" value="1" class="toggle-input" {{ old('TYRO_LOGIN_EMAIL_MAGIC_LINK', $settings['TYRO_LOGIN_EMAIL_MAGIC_LINK']) ? 'checked' : '' }}>
                                                    <span class="toggle-slider"></span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>




                        </div>
                    </div>
                </div>
            </div>

            {{-- Login & Auth Advanced Tab --}}
            <div class="vtabs-panel" id="vtab-login-auth-advanced">
                <div class="card">
                    <div class="card-header" style="display:flex;align-items:center;justify-content:space-between;gap:1rem;">
                        <h3 class="card-title">Login &amp; Auth Advanced</h3>
                        <button type="submit" form="systemSettingsForm" class="btn btn-primary btn-sm section-save-button">Save</button>
                    </div>
                    <div class="card-body">
                        <div class="sys-settings-section-intro">
                            <div class="sys-settings-section-copy">
                                <h4 class="sys-settings-section-heading">Advanced Login &amp; Auth environment configuration</h4>
                                <p class="sys-settings-section-description">Fine-tune branding, captcha, OTP, 2FA, social providers, lockout, email subjects, and page content.</p>
                            </div>
                            <span class="sys-settings-section-badge">Advanced</span>
                        </div>

                        <div class="sys-settings-grid">
                            <div class="sys-settings-surface">
                                <h4 class="sys-settings-surface-title">Branding Details</h4>
                                <p class="sys-settings-surface-description">Custom logo and app branding for auth pages.</p>

                                <div class="form-group" style="margin-bottom:0.85rem;">
                                    <label for="TYRO_LOGIN_LOGO" class="form-label">Logo URL</label>
                                    <input type="url" name="TYRO_LOGIN_LOGO" id="TYRO_LOGIN_LOGO" class="form-input" maxlength="500" value="{{ old('TYRO_LOGIN_LOGO', $settings['TYRO_LOGIN_LOGO']) }}">
                                    <p class="form-hint">Custom logo shown on auth pages.</p>
                                </div>
                                <div class="form-group" style="margin-bottom:0.85rem;">
                                    <label for="TYRO_LOGIN_LOGO_DARK" class="form-label">Logo URL (dark mode)</label>
                                    <input type="url" name="TYRO_LOGIN_LOGO_DARK" id="TYRO_LOGIN_LOGO_DARK" class="form-input" maxlength="500" value="{{ old('TYRO_LOGIN_LOGO_DARK', $settings['TYRO_LOGIN_LOGO_DARK']) }}">
                                    <p class="form-hint">Falls back to the light logo when not set.</p>
                                </div>
                                <div class="form-group" style="margin-bottom:0;">
                                    <label for="TYRO_LOGIN_LOGO_HEIGHT" class="form-label">Logo height</label>
                                    <input type="text" name="TYRO_LOGIN_LOGO_HEIGHT" id="TYRO_LOGIN_LOGO_HEIGHT" class="form-input" maxlength="20" value="{{ old('TYRO_LOGIN_LOGO_HEIGHT', $settings['TYRO_LOGIN_LOGO_HEIGHT']) }}">
                                    <p class="form-hint">CSS value e.g. <code>32px</code>, <code>3rem</code>.</p>
                                </div>
                            </div>

                            <div class="sys-settings-surface">
                                <h4 class="sys-settings-surface-title">Registration Details</h4>
                                <p class="sys-settings-surface-description">Additional registration and login behaviour.</p>

                                <div class="sys-settings-toggles" style="margin-bottom:0;">
                                    <div class="sys-settings-toggle">
                                        <div class="sys-settings-toggle-top">
                                            <div>
                                                <p class="sys-settings-toggle-title">Auto-login after registration</p>
                                                <p class="sys-settings-toggle-description">Writes <code>TYRO_LOGIN_REGISTRATION_AUTO_LOGIN</code>.</p>
                                            </div>
                                            <div>
                                                <input type="hidden" name="TYRO_LOGIN_REGISTRATION_AUTO_LOGIN" value="0">
                                                <label class="toggle-label">
                                                    <input type="checkbox" name="TYRO_LOGIN_REGISTRATION_AUTO_LOGIN" value="1" class="toggle-input" {{ old('TYRO_LOGIN_REGISTRATION_AUTO_LOGIN', $settings['TYRO_LOGIN_REGISTRATION_AUTO_LOGIN']) ? 'checked' : '' }}>
                                                    <span class="toggle-slider"></span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="sys-settings-toggle">
                                        <div class="sys-settings-toggle-top">
                                            <div>
                                                <p class="sys-settings-toggle-title">Disable password login</p>
                                                <p class="sys-settings-toggle-description">Hides password field, remember me, and forgot password link. Writes <code>TYRO_LOGIN_DISABLE_PASSWORD</code>.</p>
                                            </div>
                                            <div>
                                                <input type="hidden" name="TYRO_LOGIN_DISABLE_PASSWORD" value="0">
                                                <label class="toggle-label">
                                                    <input type="checkbox" name="TYRO_LOGIN_DISABLE_PASSWORD" value="1" class="toggle-input" {{ old('TYRO_LOGIN_DISABLE_PASSWORD', $settings['TYRO_LOGIN_DISABLE_PASSWORD']) ? 'checked' : '' }}>
                                                    <span class="toggle-slider"></span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="sys-settings-surface" id="captcha-details-surface">
                                <h4 class="sys-settings-surface-title">Captcha Details</h4>
                                <p class="sys-settings-surface-description">Captcha text labels and number range. Visible when captcha is enabled.</p>

                                <div class="form-group" style="margin-bottom:0.85rem;">
                                    <label for="TYRO_LOGIN_CAPTCHA_LABEL" class="form-label">Label</label>
                                    <input type="text" name="TYRO_LOGIN_CAPTCHA_LABEL" id="TYRO_LOGIN_CAPTCHA_LABEL" class="form-input" maxlength="255" value="{{ old('TYRO_LOGIN_CAPTCHA_LABEL', $settings['TYRO_LOGIN_CAPTCHA_LABEL']) }}">
                                </div>
                                <div class="form-group" style="margin-bottom:0.85rem;">
                                    <label for="TYRO_LOGIN_CAPTCHA_PLACEHOLDER" class="form-label">Placeholder</label>
                                    <input type="text" name="TYRO_LOGIN_CAPTCHA_PLACEHOLDER" id="TYRO_LOGIN_CAPTCHA_PLACEHOLDER" class="form-input" maxlength="255" value="{{ old('TYRO_LOGIN_CAPTCHA_PLACEHOLDER', $settings['TYRO_LOGIN_CAPTCHA_PLACEHOLDER']) }}">
                                </div>
                                <div class="form-group" style="margin-bottom:0.85rem;">
                                    <label for="TYRO_LOGIN_CAPTCHA_ERROR" class="form-label">Error message</label>
                                    <input type="text" name="TYRO_LOGIN_CAPTCHA_ERROR" id="TYRO_LOGIN_CAPTCHA_ERROR" class="form-input" maxlength="255" value="{{ old('TYRO_LOGIN_CAPTCHA_ERROR', $settings['TYRO_LOGIN_CAPTCHA_ERROR']) }}">
                                </div>
                                <div class="sys-settings-metrics" style="margin-bottom:0;">
                                    <div class="form-group sys-settings-metric" style="margin-bottom:0;">
                                        <label for="TYRO_LOGIN_CAPTCHA_MIN" class="form-label">Min number</label>
                                        <input type="number" name="TYRO_LOGIN_CAPTCHA_MIN" id="TYRO_LOGIN_CAPTCHA_MIN" class="form-input" min="0" max="100" value="{{ old('TYRO_LOGIN_CAPTCHA_MIN', $settings['TYRO_LOGIN_CAPTCHA_MIN']) }}">
                                    </div>
                                    <div class="form-group sys-settings-metric" style="margin-bottom:0;">
                                        <label for="TYRO_LOGIN_CAPTCHA_MAX" class="form-label">Max number</label>
                                        <input type="number" name="TYRO_LOGIN_CAPTCHA_MAX" id="TYRO_LOGIN_CAPTCHA_MAX" class="form-input" min="1" max="1000" value="{{ old('TYRO_LOGIN_CAPTCHA_MAX', $settings['TYRO_LOGIN_CAPTCHA_MAX']) }}">
                                    </div>
                                </div>
                            </div>

                            <div class="sys-settings-surface" id="otp-details-surface">
                                <h4 class="sys-settings-surface-title">OTP Details</h4>
                                <p class="sys-settings-surface-description">OTP length, expiry, resend limits, and page text. Visible when OTP is enabled.</p>

                                <div class="sys-settings-metrics" style="margin-bottom:0.85rem;">
                                    <div class="form-group sys-settings-metric" style="margin-bottom:0;">
                                        <label for="TYRO_LOGIN_OTP_LENGTH" class="form-label">OTP length</label>
                                        <input type="number" name="TYRO_LOGIN_OTP_LENGTH" id="TYRO_LOGIN_OTP_LENGTH" class="form-input" min="4" max="8" value="{{ old('TYRO_LOGIN_OTP_LENGTH', $settings['TYRO_LOGIN_OTP_LENGTH']) }}">
                                    </div>
                                    <div class="form-group sys-settings-metric" style="margin-bottom:0;">
                                        <label for="TYRO_LOGIN_OTP_EXPIRE" class="form-label">OTP expire (min)</label>
                                        <input type="number" name="TYRO_LOGIN_OTP_EXPIRE" id="TYRO_LOGIN_OTP_EXPIRE" class="form-input" min="1" max="60" value="{{ old('TYRO_LOGIN_OTP_EXPIRE', $settings['TYRO_LOGIN_OTP_EXPIRE']) }}">
                                    </div>
                                </div>
                                <div class="sys-settings-metrics" style="margin-bottom:0.85rem;">
                                    <div class="form-group sys-settings-metric" style="margin-bottom:0;">
                                        <label for="TYRO_LOGIN_OTP_MAX_RESEND" class="form-label">Max resend</label>
                                        <input type="number" name="TYRO_LOGIN_OTP_MAX_RESEND" id="TYRO_LOGIN_OTP_MAX_RESEND" class="form-input" min="1" max="20" value="{{ old('TYRO_LOGIN_OTP_MAX_RESEND', $settings['TYRO_LOGIN_OTP_MAX_RESEND']) }}">
                                    </div>
                                    <div class="form-group sys-settings-metric" style="margin-bottom:0;">
                                        <label for="TYRO_LOGIN_OTP_RESEND_COOLDOWN" class="form-label">Resend cooldown (sec)</label>
                                        <input type="number" name="TYRO_LOGIN_OTP_RESEND_COOLDOWN" id="TYRO_LOGIN_OTP_RESEND_COOLDOWN" class="form-input" min="10" max="600" value="{{ old('TYRO_LOGIN_OTP_RESEND_COOLDOWN', $settings['TYRO_LOGIN_OTP_RESEND_COOLDOWN']) }}">
                                    </div>
                                </div>
                                <div class="form-group" style="margin-bottom:0.85rem;">
                                    <label for="TYRO_LOGIN_OTP_TITLE" class="form-label">Page title</label>
                                    <input type="text" name="TYRO_LOGIN_OTP_TITLE" id="TYRO_LOGIN_OTP_TITLE" class="form-input" maxlength="255" value="{{ old('TYRO_LOGIN_OTP_TITLE', $settings['TYRO_LOGIN_OTP_TITLE']) }}">
                                </div>
                                <div class="form-group" style="margin-bottom:0.85rem;">
                                    <label for="TYRO_LOGIN_OTP_SUBTITLE" class="form-label">Page subtitle</label>
                                    <input type="text" name="TYRO_LOGIN_OTP_SUBTITLE" id="TYRO_LOGIN_OTP_SUBTITLE" class="form-input" maxlength="500" value="{{ old('TYRO_LOGIN_OTP_SUBTITLE', $settings['TYRO_LOGIN_OTP_SUBTITLE']) }}">
                                    <p class="form-hint">Supports <code>:length</code> and <code>:email</code> placeholders.</p>
                                </div>
                                <div class="form-group" style="margin-bottom:0.85rem;">
                                    <label for="TYRO_LOGIN_OTP_LABEL" class="form-label">Input label</label>
                                    <input type="text" name="TYRO_LOGIN_OTP_LABEL" id="TYRO_LOGIN_OTP_LABEL" class="form-input" maxlength="255" value="{{ old('TYRO_LOGIN_OTP_LABEL', $settings['TYRO_LOGIN_OTP_LABEL']) }}">
                                </div>
                                <div class="form-group" style="margin-bottom:0.85rem;">
                                    <label for="TYRO_LOGIN_OTP_PLACEHOLDER" class="form-label">Input placeholder</label>
                                    <input type="text" name="TYRO_LOGIN_OTP_PLACEHOLDER" id="TYRO_LOGIN_OTP_PLACEHOLDER" class="form-input" maxlength="255" value="{{ old('TYRO_LOGIN_OTP_PLACEHOLDER', $settings['TYRO_LOGIN_OTP_PLACEHOLDER']) }}">
                                </div>
                                <div class="sys-settings-metrics" style="margin-bottom:0.85rem;">
                                    <div class="form-group sys-settings-metric" style="margin-bottom:0;">
                                        <label for="TYRO_LOGIN_OTP_SUBMIT_BUTTON" class="form-label">Submit button</label>
                                        <input type="text" name="TYRO_LOGIN_OTP_SUBMIT_BUTTON" id="TYRO_LOGIN_OTP_SUBMIT_BUTTON" class="form-input" maxlength="100" value="{{ old('TYRO_LOGIN_OTP_SUBMIT_BUTTON', $settings['TYRO_LOGIN_OTP_SUBMIT_BUTTON']) }}">
                                    </div>
                                    <div class="form-group sys-settings-metric" style="margin-bottom:0;">
                                        <label for="TYRO_LOGIN_OTP_RESEND_BUTTON" class="form-label">Resend button</label>
                                        <input type="text" name="TYRO_LOGIN_OTP_RESEND_BUTTON" id="TYRO_LOGIN_OTP_RESEND_BUTTON" class="form-input" maxlength="100" value="{{ old('TYRO_LOGIN_OTP_RESEND_BUTTON', $settings['TYRO_LOGIN_OTP_RESEND_BUTTON']) }}">
                                    </div>
                                </div>
                                <div class="form-group" style="margin-bottom:0.85rem;">
                                    <label for="TYRO_LOGIN_OTP_ERROR" class="form-label">Error message</label>
                                    <input type="text" name="TYRO_LOGIN_OTP_ERROR" id="TYRO_LOGIN_OTP_ERROR" class="form-input" maxlength="500" value="{{ old('TYRO_LOGIN_OTP_ERROR', $settings['TYRO_LOGIN_OTP_ERROR']) }}">
                                </div>
                                <div class="form-group" style="margin-bottom:0.85rem;">
                                    <label for="TYRO_LOGIN_OTP_RESEND_SUCCESS" class="form-label">Resend success message</label>
                                    <input type="text" name="TYRO_LOGIN_OTP_RESEND_SUCCESS" id="TYRO_LOGIN_OTP_RESEND_SUCCESS" class="form-input" maxlength="500" value="{{ old('TYRO_LOGIN_OTP_RESEND_SUCCESS', $settings['TYRO_LOGIN_OTP_RESEND_SUCCESS']) }}">
                                </div>
                                <div class="form-group" style="margin-bottom:0.85rem;">
                                    <label for="TYRO_LOGIN_OTP_MAX_RESEND_ERROR" class="form-label">Max resend error</label>
                                    <input type="text" name="TYRO_LOGIN_OTP_MAX_RESEND_ERROR" id="TYRO_LOGIN_OTP_MAX_RESEND_ERROR" class="form-input" maxlength="500" value="{{ old('TYRO_LOGIN_OTP_MAX_RESEND_ERROR', $settings['TYRO_LOGIN_OTP_MAX_RESEND_ERROR']) }}">
                                </div>
                                <div class="form-group" style="margin-bottom:0.85rem;">
                                    <label for="TYRO_LOGIN_OTP_BG_TITLE" class="form-label">Background title</label>
                                    <input type="text" name="TYRO_LOGIN_OTP_BG_TITLE" id="TYRO_LOGIN_OTP_BG_TITLE" class="form-input" maxlength="255" value="{{ old('TYRO_LOGIN_OTP_BG_TITLE', $settings['TYRO_LOGIN_OTP_BG_TITLE']) }}">
                                </div>
                                <div class="form-group" style="margin-bottom:0;">
                                    <label for="TYRO_LOGIN_OTP_BG_DESCRIPTION" class="form-label">Background description</label>
                                    <input type="text" name="TYRO_LOGIN_OTP_BG_DESCRIPTION" id="TYRO_LOGIN_OTP_BG_DESCRIPTION" class="form-input" maxlength="500" value="{{ old('TYRO_LOGIN_OTP_BG_DESCRIPTION', $settings['TYRO_LOGIN_OTP_BG_DESCRIPTION']) }}">
                                </div>
                            </div>

                            <div class="sys-settings-surface" id="twofa-details-surface">
                                <h4 class="sys-settings-surface-title">2FA Details</h4>
                                <p class="sys-settings-surface-description">2FA page text, cookie settings, and forced roles. Visible when 2FA is enabled.</p>

                                <div class="form-group" style="margin-bottom:0.85rem;">
                                    <label for="TYRO_LOGIN_2FA_SETUP_TITLE" class="form-label">Setup page title</label>
                                    <input type="text" name="TYRO_LOGIN_2FA_SETUP_TITLE" id="TYRO_LOGIN_2FA_SETUP_TITLE" class="form-input" maxlength="255" value="{{ old('TYRO_LOGIN_2FA_SETUP_TITLE', $settings['TYRO_LOGIN_2FA_SETUP_TITLE']) }}">
                                </div>
                                <div class="form-group" style="margin-bottom:0.85rem;">
                                    <label for="TYRO_LOGIN_2FA_SETUP_SUBTITLE" class="form-label">Setup page subtitle</label>
                                    <input type="text" name="TYRO_LOGIN_2FA_SETUP_SUBTITLE" id="TYRO_LOGIN_2FA_SETUP_SUBTITLE" class="form-input" maxlength="500" value="{{ old('TYRO_LOGIN_2FA_SETUP_SUBTITLE', $settings['TYRO_LOGIN_2FA_SETUP_SUBTITLE']) }}">
                                </div>
                                <div class="form-group" style="margin-bottom:0.85rem;">
                                    <label for="TYRO_LOGIN_2FA_CHALLENGE_TITLE" class="form-label">Challenge page title</label>
                                    <input type="text" name="TYRO_LOGIN_2FA_CHALLENGE_TITLE" id="TYRO_LOGIN_2FA_CHALLENGE_TITLE" class="form-input" maxlength="255" value="{{ old('TYRO_LOGIN_2FA_CHALLENGE_TITLE', $settings['TYRO_LOGIN_2FA_CHALLENGE_TITLE']) }}">
                                </div>
                                <div class="form-group" style="margin-bottom:0.85rem;">
                                    <label for="TYRO_LOGIN_2FA_CHALLENGE_SUBTITLE" class="form-label">Challenge page subtitle</label>
                                    <input type="text" name="TYRO_LOGIN_2FA_CHALLENGE_SUBTITLE" id="TYRO_LOGIN_2FA_CHALLENGE_SUBTITLE" class="form-input" maxlength="500" value="{{ old('TYRO_LOGIN_2FA_CHALLENGE_SUBTITLE', $settings['TYRO_LOGIN_2FA_CHALLENGE_SUBTITLE']) }}">
                                </div>
                                <div class="sys-settings-toggle" style="margin-bottom:0.85rem;">
                                    <div class="sys-settings-toggle-top">
                                        <div>
                                            <p class="sys-settings-toggle-title">Allow users to skip 2FA setup</p>
                                            <p class="sys-settings-toggle-description">Writes <code>TYRO_LOGIN_2FA_ALLOW_SKIP</code>.</p>
                                        </div>
                                        <div>
                                            <input type="hidden" name="TYRO_LOGIN_2FA_ALLOW_SKIP" value="0">
                                            <label class="toggle-label">
                                                <input type="checkbox" name="TYRO_LOGIN_2FA_ALLOW_SKIP" value="1" class="toggle-input" {{ old('TYRO_LOGIN_2FA_ALLOW_SKIP', $settings['TYRO_LOGIN_2FA_ALLOW_SKIP']) ? 'checked' : '' }}>
                                                <span class="toggle-slider"></span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group" style="margin-bottom:0.85rem;">
                                    <label for="TYRO_LOGIN_2FA_IGNORE_COOKIE_DAYS" class="form-label">Skip cookie days</label>
                                    <input type="number" name="TYRO_LOGIN_2FA_IGNORE_COOKIE_DAYS" id="TYRO_LOGIN_2FA_IGNORE_COOKIE_DAYS" class="form-input" min="1" max="365" value="{{ old('TYRO_LOGIN_2FA_IGNORE_COOKIE_DAYS', $settings['TYRO_LOGIN_2FA_IGNORE_COOKIE_DAYS']) }}">
                                </div>
                                <div class="form-group" style="margin-bottom:0;">
                                    <label for="TYRO_LOGIN_2FA_FORCED_ROLES" class="form-label">Forced roles</label>
                                    <input type="text" name="TYRO_LOGIN_2FA_FORCED_ROLES" id="TYRO_LOGIN_2FA_FORCED_ROLES" class="form-input" maxlength="255" value="{{ old('TYRO_LOGIN_2FA_FORCED_ROLES', $settings['TYRO_LOGIN_2FA_FORCED_ROLES']) }}">
                                    <p class="form-hint">Comma-separated role slugs that must use 2FA.</p>
                                </div>
                            </div>

                            <div class="sys-settings-surface" id="social-details-surface">
                                <h4 class="sys-settings-surface-title">Social Provider Details</h4>
                                <p class="sys-settings-surface-description">Enable individual social providers. Visible when social login is enabled.</p>

                                <div class="sys-settings-toggles" style="margin-bottom:0.85rem;">
                                    <div class="sys-settings-toggle">
                                        <div class="sys-settings-toggle-top">
                                            <div>
                                                <p class="sys-settings-toggle-title">Google</p>
                                                <p class="sys-settings-toggle-description">Writes <code>TYRO_LOGIN_SOCIAL_GOOGLE</code>.</p>
                                            </div>
                                            <div>
                                                <input type="hidden" name="TYRO_LOGIN_SOCIAL_GOOGLE" value="0">
                                                <label class="toggle-label">
                                                    <input type="checkbox" name="TYRO_LOGIN_SOCIAL_GOOGLE" value="1" class="toggle-input" {{ old('TYRO_LOGIN_SOCIAL_GOOGLE', $settings['TYRO_LOGIN_SOCIAL_GOOGLE']) ? 'checked' : '' }}>
                                                    <span class="toggle-slider"></span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="sys-settings-toggle">
                                        <div class="sys-settings-toggle-top">
                                            <div>
                                                <p class="sys-settings-toggle-title">Facebook</p>
                                                <p class="sys-settings-toggle-description">Writes <code>TYRO_LOGIN_SOCIAL_FACEBOOK</code>.</p>
                                            </div>
                                            <div>
                                                <input type="hidden" name="TYRO_LOGIN_SOCIAL_FACEBOOK" value="0">
                                                <label class="toggle-label">
                                                    <input type="checkbox" name="TYRO_LOGIN_SOCIAL_FACEBOOK" value="1" class="toggle-input" {{ old('TYRO_LOGIN_SOCIAL_FACEBOOK', $settings['TYRO_LOGIN_SOCIAL_FACEBOOK']) ? 'checked' : '' }}>
                                                    <span class="toggle-slider"></span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="sys-settings-toggle">
                                        <div class="sys-settings-toggle-top">
                                            <div>
                                                <p class="sys-settings-toggle-title">GitHub</p>
                                                <p class="sys-settings-toggle-description">Writes <code>TYRO_LOGIN_SOCIAL_GITHUB</code>.</p>
                                            </div>
                                            <div>
                                                <input type="hidden" name="TYRO_LOGIN_SOCIAL_GITHUB" value="0">
                                                <label class="toggle-label">
                                                    <input type="checkbox" name="TYRO_LOGIN_SOCIAL_GITHUB" value="1" class="toggle-input" {{ old('TYRO_LOGIN_SOCIAL_GITHUB', $settings['TYRO_LOGIN_SOCIAL_GITHUB']) ? 'checked' : '' }}>
                                                    <span class="toggle-slider"></span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="sys-settings-toggle">
                                        <div class="sys-settings-toggle-top">
                                            <div>
                                                <p class="sys-settings-toggle-title">X (Twitter)</p>
                                                <p class="sys-settings-toggle-description">Writes <code>TYRO_LOGIN_SOCIAL_TWITTER</code>.</p>
                                            </div>
                                            <div>
                                                <input type="hidden" name="TYRO_LOGIN_SOCIAL_TWITTER" value="0">
                                                <label class="toggle-label">
                                                    <input type="checkbox" name="TYRO_LOGIN_SOCIAL_TWITTER" value="1" class="toggle-input" {{ old('TYRO_LOGIN_SOCIAL_TWITTER', $settings['TYRO_LOGIN_SOCIAL_TWITTER']) ? 'checked' : '' }}>
                                                    <span class="toggle-slider"></span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="sys-settings-toggle">
                                        <div class="sys-settings-toggle-top">
                                            <div>
                                                <p class="sys-settings-toggle-title">LinkedIn</p>
                                                <p class="sys-settings-toggle-description">Writes <code>TYRO_LOGIN_SOCIAL_LINKEDIN</code>.</p>
                                            </div>
                                            <div>
                                                <input type="hidden" name="TYRO_LOGIN_SOCIAL_LINKEDIN" value="0">
                                                <label class="toggle-label">
                                                    <input type="checkbox" name="TYRO_LOGIN_SOCIAL_LINKEDIN" value="1" class="toggle-input" {{ old('TYRO_LOGIN_SOCIAL_LINKEDIN', $settings['TYRO_LOGIN_SOCIAL_LINKEDIN']) ? 'checked' : '' }}>
                                                    <span class="toggle-slider"></span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="sys-settings-toggle">
                                        <div class="sys-settings-toggle-top">
                                            <div>
                                                <p class="sys-settings-toggle-title">Bitbucket</p>
                                                <p class="sys-settings-toggle-description">Writes <code>TYRO_LOGIN_SOCIAL_BITBUCKET</code>.</p>
                                            </div>
                                            <div>
                                                <input type="hidden" name="TYRO_LOGIN_SOCIAL_BITBUCKET" value="0">
                                                <label class="toggle-label">
                                                    <input type="checkbox" name="TYRO_LOGIN_SOCIAL_BITBUCKET" value="1" class="toggle-input" {{ old('TYRO_LOGIN_SOCIAL_BITBUCKET', $settings['TYRO_LOGIN_SOCIAL_BITBUCKET']) ? 'checked' : '' }}>
                                                    <span class="toggle-slider"></span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="sys-settings-toggle">
                                        <div class="sys-settings-toggle-top">
                                            <div>
                                                <p class="sys-settings-toggle-title">GitLab</p>
                                                <p class="sys-settings-toggle-description">Writes <code>TYRO_LOGIN_SOCIAL_GITLAB</code>.</p>
                                            </div>
                                            <div>
                                                <input type="hidden" name="TYRO_LOGIN_SOCIAL_GITLAB" value="0">
                                                <label class="toggle-label">
                                                    <input type="checkbox" name="TYRO_LOGIN_SOCIAL_GITLAB" value="1" class="toggle-input" {{ old('TYRO_LOGIN_SOCIAL_GITLAB', $settings['TYRO_LOGIN_SOCIAL_GITLAB']) ? 'checked' : '' }}>
                                                    <span class="toggle-slider"></span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="sys-settings-toggle">
                                        <div class="sys-settings-toggle-top">
                                            <div>
                                                <p class="sys-settings-toggle-title">Slack</p>
                                                <p class="sys-settings-toggle-description">Writes <code>TYRO_LOGIN_SOCIAL_SLACK</code>.</p>
                                            </div>
                                            <div>
                                                <input type="hidden" name="TYRO_LOGIN_SOCIAL_SLACK" value="0">
                                                <label class="toggle-label">
                                                    <input type="checkbox" name="TYRO_LOGIN_SOCIAL_SLACK" value="1" class="toggle-input" {{ old('TYRO_LOGIN_SOCIAL_SLACK', $settings['TYRO_LOGIN_SOCIAL_SLACK']) ? 'checked' : '' }}>
                                                    <span class="toggle-slider"></span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group" style="margin-bottom:0.85rem;">
                                    <label for="TYRO_LOGIN_SOCIAL_LINK_EXISTING" class="form-label">Link existing accounts by email</label>
                                    <div style="margin-top:0.35rem;">
                                        <input type="hidden" name="TYRO_LOGIN_SOCIAL_LINK_EXISTING" value="0">
                                        <label class="toggle-label">
                                            <input type="checkbox" name="TYRO_LOGIN_SOCIAL_LINK_EXISTING" value="1" class="toggle-input" {{ old('TYRO_LOGIN_SOCIAL_LINK_EXISTING', $settings['TYRO_LOGIN_SOCIAL_LINK_EXISTING']) ? 'checked' : '' }}>
                                            <span class="toggle-slider"></span>
                                        </label>
                                    </div>
                                </div>
                                <div class="form-group" style="margin-bottom:0.85rem;">
                                    <label for="TYRO_LOGIN_SOCIAL_AUTO_VERIFY_EMAIL" class="form-label">Auto-verify email after social login</label>
                                    <div style="margin-top:0.35rem;">
                                        <input type="hidden" name="TYRO_LOGIN_SOCIAL_AUTO_VERIFY_EMAIL" value="0">
                                        <label class="toggle-label">
                                            <input type="checkbox" name="TYRO_LOGIN_SOCIAL_AUTO_VERIFY_EMAIL" value="1" class="toggle-input" {{ old('TYRO_LOGIN_SOCIAL_AUTO_VERIFY_EMAIL', $settings['TYRO_LOGIN_SOCIAL_AUTO_VERIFY_EMAIL']) ? 'checked' : '' }}>
                                            <span class="toggle-slider"></span>
                                        </label>
                                    </div>
                                </div>
                                <div class="form-group" style="margin-bottom:0;">
                                    <label for="TYRO_LOGIN_SOCIAL_DIVIDER" class="form-label">Divider text</label>
                                    <input type="text" name="TYRO_LOGIN_SOCIAL_DIVIDER" id="TYRO_LOGIN_SOCIAL_DIVIDER" class="form-input" maxlength="255" value="{{ old('TYRO_LOGIN_SOCIAL_DIVIDER', $settings['TYRO_LOGIN_SOCIAL_DIVIDER']) }}">
                                </div>
                            </div>

                            <div class="sys-settings-surface" id="lockout-details-surface">
                                <h4 class="sys-settings-surface-title">Lockout Details</h4>
                                <p class="sys-settings-surface-description">Lockout page text and auto-redirect behaviour. Visible when lockout is enabled.</p>

                                <div class="sys-settings-toggles" style="margin-bottom:0.85rem;">
                                    <div class="sys-settings-toggle">
                                        <div class="sys-settings-toggle-top">
                                            <div>
                                                <p class="sys-settings-toggle-title">Auto-redirect after lockout expires</p>
                                                <p class="sys-settings-toggle-description">Writes <code>TYRO_LOGIN_LOCKOUT_AUTO_REDIRECT</code>.</p>
                                            </div>
                                            <div>
                                                <input type="hidden" name="TYRO_LOGIN_LOCKOUT_AUTO_REDIRECT" value="0">
                                                <label class="toggle-label">
                                                    <input type="checkbox" name="TYRO_LOGIN_LOCKOUT_AUTO_REDIRECT" value="1" class="toggle-input" {{ old('TYRO_LOGIN_LOCKOUT_AUTO_REDIRECT', $settings['TYRO_LOGIN_LOCKOUT_AUTO_REDIRECT']) ? 'checked' : '' }}>
                                                    <span class="toggle-slider"></span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group" style="margin-bottom:0.85rem;">
                                    <label for="TYRO_LOGIN_LOCKOUT_MESSAGE" class="form-label">Lockout message</label>
                                    <input type="text" name="TYRO_LOGIN_LOCKOUT_MESSAGE" id="TYRO_LOGIN_LOCKOUT_MESSAGE" class="form-input" maxlength="500" value="{{ old('TYRO_LOGIN_LOCKOUT_MESSAGE', $settings['TYRO_LOGIN_LOCKOUT_MESSAGE']) }}">
                                    <p class="form-hint">Supports <code>:minutes</code> placeholder.</p>
                                </div>
                                <div class="form-group" style="margin-bottom:0.85rem;">
                                    <label for="TYRO_LOGIN_LOCKOUT_TITLE" class="form-label">Lockout page title</label>
                                    <input type="text" name="TYRO_LOGIN_LOCKOUT_TITLE" id="TYRO_LOGIN_LOCKOUT_TITLE" class="form-input" maxlength="255" value="{{ old('TYRO_LOGIN_LOCKOUT_TITLE', $settings['TYRO_LOGIN_LOCKOUT_TITLE']) }}">
                                </div>
                                <div class="form-group" style="margin-bottom:0;">
                                    <label for="TYRO_LOGIN_LOCKOUT_SUBTITLE" class="form-label">Lockout page subtitle</label>
                                    <input type="text" name="TYRO_LOGIN_LOCKOUT_SUBTITLE" id="TYRO_LOGIN_LOCKOUT_SUBTITLE" class="form-input" maxlength="500" value="{{ old('TYRO_LOGIN_LOCKOUT_SUBTITLE', $settings['TYRO_LOGIN_LOCKOUT_SUBTITLE']) }}">
                                </div>
                            </div>

                            <div class="sys-settings-surface">
                                <h4 class="sys-settings-surface-title">Email Subjects</h4>
                                <p class="sys-settings-surface-description">Custom email subject lines for auth emails.</p>

                                <div class="form-group" style="margin-bottom:0.85rem;">
                                    <label for="TYRO_LOGIN_EMAIL_OTP_SUBJECT" class="form-label">OTP email subject</label>
                                    <input type="text" name="TYRO_LOGIN_EMAIL_OTP_SUBJECT" id="TYRO_LOGIN_EMAIL_OTP_SUBJECT" class="form-input" maxlength="255" value="{{ old('TYRO_LOGIN_EMAIL_OTP_SUBJECT', $settings['TYRO_LOGIN_EMAIL_OTP_SUBJECT']) }}">
                                </div>
                                <div class="form-group" style="margin-bottom:0.85rem;">
                                    <label for="TYRO_LOGIN_EMAIL_PASSWORD_RESET_SUBJECT" class="form-label">Password reset subject</label>
                                    <input type="text" name="TYRO_LOGIN_EMAIL_PASSWORD_RESET_SUBJECT" id="TYRO_LOGIN_EMAIL_PASSWORD_RESET_SUBJECT" class="form-input" maxlength="255" value="{{ old('TYRO_LOGIN_EMAIL_PASSWORD_RESET_SUBJECT', $settings['TYRO_LOGIN_EMAIL_PASSWORD_RESET_SUBJECT']) }}">
                                </div>
                                <div class="form-group" style="margin-bottom:0.85rem;">
                                    <label for="TYRO_LOGIN_EMAIL_VERIFY_SUBJECT" class="form-label">Email verification subject</label>
                                    <input type="text" name="TYRO_LOGIN_EMAIL_VERIFY_SUBJECT" id="TYRO_LOGIN_EMAIL_VERIFY_SUBJECT" class="form-input" maxlength="255" value="{{ old('TYRO_LOGIN_EMAIL_VERIFY_SUBJECT', $settings['TYRO_LOGIN_EMAIL_VERIFY_SUBJECT']) }}">
                                </div>
                                <div class="form-group" style="margin-bottom:0.85rem;">
                                    <label for="TYRO_LOGIN_EMAIL_WELCOME_SUBJECT" class="form-label">Welcome email subject</label>
                                    <input type="text" name="TYRO_LOGIN_EMAIL_WELCOME_SUBJECT" id="TYRO_LOGIN_EMAIL_WELCOME_SUBJECT" class="form-input" maxlength="255" value="{{ old('TYRO_LOGIN_EMAIL_WELCOME_SUBJECT', $settings['TYRO_LOGIN_EMAIL_WELCOME_SUBJECT']) }}">
                                </div>
                                <div class="form-group" style="margin-bottom:0;">
                                    <label for="TYRO_LOGIN_EMAIL_MAGIC_LINK_SUBJECT" class="form-label">Magic link subject</label>
                                    <input type="text" name="TYRO_LOGIN_EMAIL_MAGIC_LINK_SUBJECT" id="TYRO_LOGIN_EMAIL_MAGIC_LINK_SUBJECT" class="form-input" maxlength="255" value="{{ old('TYRO_LOGIN_EMAIL_MAGIC_LINK_SUBJECT', $settings['TYRO_LOGIN_EMAIL_MAGIC_LINK_SUBJECT']) }}">
                                </div>
                            </div>

                            <div class="sys-settings-surface">
                                <h4 class="sys-settings-surface-title">Page Content</h4>
                                <p class="sys-settings-surface-description">Customise titles, subtitles, and background text for auth pages.</p>

                                <h5 style="margin:0 0 0.6rem;font-size:0.88rem;font-weight:700;color:var(--foreground);">Login Page</h5>
                                <div class="form-group" style="margin-bottom:0.85rem;">
                                    <label for="TYRO_LOGIN_BG_TITLE" class="form-label">Background title</label>
                                    <input type="text" name="TYRO_LOGIN_BG_TITLE" id="TYRO_LOGIN_BG_TITLE" class="form-input" maxlength="255" value="{{ old('TYRO_LOGIN_BG_TITLE', $settings['TYRO_LOGIN_BG_TITLE']) }}">
                                </div>
                                <div class="form-group" style="margin-bottom:0.85rem;">
                                    <label for="TYRO_LOGIN_BG_DESCRIPTION" class="form-label">Background description</label>
                                    <input type="text" name="TYRO_LOGIN_BG_DESCRIPTION" id="TYRO_LOGIN_BG_DESCRIPTION" class="form-input" maxlength="500" value="{{ old('TYRO_LOGIN_BG_DESCRIPTION', $settings['TYRO_LOGIN_BG_DESCRIPTION']) }}">
                                </div>

                                <h5 style="margin:0 0 0.6rem;font-size:0.88rem;font-weight:700;color:var(--foreground);">Register Page</h5>
                                <div class="form-group" style="margin-bottom:0.85rem;">
                                    <label for="TYRO_LOGIN_REGISTER_BG_TITLE" class="form-label">Background title</label>
                                    <input type="text" name="TYRO_LOGIN_REGISTER_BG_TITLE" id="TYRO_LOGIN_REGISTER_BG_TITLE" class="form-input" maxlength="255" value="{{ old('TYRO_LOGIN_REGISTER_BG_TITLE', $settings['TYRO_LOGIN_REGISTER_BG_TITLE']) }}">
                                </div>
                                <div class="form-group" style="margin-bottom:0.85rem;">
                                    <label for="TYRO_LOGIN_REGISTER_BG_DESCRIPTION" class="form-label">Background description</label>
                                    <input type="text" name="TYRO_LOGIN_REGISTER_BG_DESCRIPTION" id="TYRO_LOGIN_REGISTER_BG_DESCRIPTION" class="form-input" maxlength="500" value="{{ old('TYRO_LOGIN_REGISTER_BG_DESCRIPTION', $settings['TYRO_LOGIN_REGISTER_BG_DESCRIPTION']) }}">
                                </div>

                                <h5 style="margin:0 0 0.6rem;font-size:0.88rem;font-weight:700;color:var(--foreground);">Verify Email Page</h5>
                                <div class="form-group" style="margin-bottom:0.85rem;">
                                    <label for="TYRO_LOGIN_VERIFY_EMAIL_TITLE" class="form-label">Page title</label>
                                    <input type="text" name="TYRO_LOGIN_VERIFY_EMAIL_TITLE" id="TYRO_LOGIN_VERIFY_EMAIL_TITLE" class="form-input" maxlength="255" value="{{ old('TYRO_LOGIN_VERIFY_EMAIL_TITLE', $settings['TYRO_LOGIN_VERIFY_EMAIL_TITLE']) }}">
                                </div>
                                <div class="form-group" style="margin-bottom:0.85rem;">
                                    <label for="TYRO_LOGIN_VERIFY_EMAIL_SUBTITLE" class="form-label">Page subtitle</label>
                                    <input type="text" name="TYRO_LOGIN_VERIFY_EMAIL_SUBTITLE" id="TYRO_LOGIN_VERIFY_EMAIL_SUBTITLE" class="form-input" maxlength="500" value="{{ old('TYRO_LOGIN_VERIFY_EMAIL_SUBTITLE', $settings['TYRO_LOGIN_VERIFY_EMAIL_SUBTITLE']) }}">
                                </div>
                                <div class="form-group" style="margin-bottom:0.85rem;">
                                    <label for="TYRO_LOGIN_VERIFY_EMAIL_BG_TITLE" class="form-label">Background title</label>
                                    <input type="text" name="TYRO_LOGIN_VERIFY_EMAIL_BG_TITLE" id="TYRO_LOGIN_VERIFY_EMAIL_BG_TITLE" class="form-input" maxlength="255" value="{{ old('TYRO_LOGIN_VERIFY_EMAIL_BG_TITLE', $settings['TYRO_LOGIN_VERIFY_EMAIL_BG_TITLE']) }}">
                                </div>
                                <div class="form-group" style="margin-bottom:0.85rem;">
                                    <label for="TYRO_LOGIN_VERIFY_EMAIL_BG_DESCRIPTION" class="form-label">Background description</label>
                                    <input type="text" name="TYRO_LOGIN_VERIFY_EMAIL_BG_DESCRIPTION" id="TYRO_LOGIN_VERIFY_EMAIL_BG_DESCRIPTION" class="form-input" maxlength="500" value="{{ old('TYRO_LOGIN_VERIFY_EMAIL_BG_DESCRIPTION', $settings['TYRO_LOGIN_VERIFY_EMAIL_BG_DESCRIPTION']) }}">
                                </div>

                                <h5 style="margin:0 0 0.6rem;font-size:0.88rem;font-weight:700;color:var(--foreground);">Forgot Password Page</h5>
                                <div class="form-group" style="margin-bottom:0.85rem;">
                                    <label for="TYRO_LOGIN_FORGOT_PASSWORD_TITLE" class="form-label">Page title</label>
                                    <input type="text" name="TYRO_LOGIN_FORGOT_PASSWORD_TITLE" id="TYRO_LOGIN_FORGOT_PASSWORD_TITLE" class="form-input" maxlength="255" value="{{ old('TYRO_LOGIN_FORGOT_PASSWORD_TITLE', $settings['TYRO_LOGIN_FORGOT_PASSWORD_TITLE']) }}">
                                </div>
                                <div class="form-group" style="margin-bottom:0.85rem;">
                                    <label for="TYRO_LOGIN_FORGOT_PASSWORD_SUBTITLE" class="form-label">Page subtitle</label>
                                    <input type="text" name="TYRO_LOGIN_FORGOT_PASSWORD_SUBTITLE" id="TYRO_LOGIN_FORGOT_PASSWORD_SUBTITLE" class="form-input" maxlength="500" value="{{ old('TYRO_LOGIN_FORGOT_PASSWORD_SUBTITLE', $settings['TYRO_LOGIN_FORGOT_PASSWORD_SUBTITLE']) }}">
                                </div>
                                <div class="form-group" style="margin-bottom:0.85rem;">
                                    <label for="TYRO_LOGIN_FORGOT_PASSWORD_BG_TITLE" class="form-label">Background title</label>
                                    <input type="text" name="TYRO_LOGIN_FORGOT_PASSWORD_BG_TITLE" id="TYRO_LOGIN_FORGOT_PASSWORD_BG_TITLE" class="form-input" maxlength="255" value="{{ old('TYRO_LOGIN_FORGOT_PASSWORD_BG_TITLE', $settings['TYRO_LOGIN_FORGOT_PASSWORD_BG_TITLE']) }}">
                                </div>
                                <div class="form-group" style="margin-bottom:0.85rem;">
                                    <label for="TYRO_LOGIN_FORGOT_PASSWORD_BG_DESCRIPTION" class="form-label">Background description</label>
                                    <input type="text" name="TYRO_LOGIN_FORGOT_PASSWORD_BG_DESCRIPTION" id="TYRO_LOGIN_FORGOT_PASSWORD_BG_DESCRIPTION" class="form-input" maxlength="500" value="{{ old('TYRO_LOGIN_FORGOT_PASSWORD_BG_DESCRIPTION', $settings['TYRO_LOGIN_FORGOT_PASSWORD_BG_DESCRIPTION']) }}">
                                </div>

                                <h5 style="margin:0 0 0.6rem;font-size:0.88rem;font-weight:700;color:var(--foreground);">Reset Password Page</h5>
                                <div class="form-group" style="margin-bottom:0.85rem;">
                                    <label for="TYRO_LOGIN_RESET_PASSWORD_TITLE" class="form-label">Page title</label>
                                    <input type="text" name="TYRO_LOGIN_RESET_PASSWORD_TITLE" id="TYRO_LOGIN_RESET_PASSWORD_TITLE" class="form-input" maxlength="255" value="{{ old('TYRO_LOGIN_RESET_PASSWORD_TITLE', $settings['TYRO_LOGIN_RESET_PASSWORD_TITLE']) }}">
                                </div>
                                <div class="form-group" style="margin-bottom:0.85rem;">
                                    <label for="TYRO_LOGIN_RESET_PASSWORD_SUBTITLE" class="form-label">Page subtitle</label>
                                    <input type="text" name="TYRO_LOGIN_RESET_PASSWORD_SUBTITLE" id="TYRO_LOGIN_RESET_PASSWORD_SUBTITLE" class="form-input" maxlength="500" value="{{ old('TYRO_LOGIN_RESET_PASSWORD_SUBTITLE', $settings['TYRO_LOGIN_RESET_PASSWORD_SUBTITLE']) }}">
                                </div>
                                <div class="form-group" style="margin-bottom:0.85rem;">
                                    <label for="TYRO_LOGIN_RESET_PASSWORD_BG_TITLE" class="form-label">Background title</label>
                                    <input type="text" name="TYRO_LOGIN_RESET_PASSWORD_BG_TITLE" id="TYRO_LOGIN_RESET_PASSWORD_BG_TITLE" class="form-input" maxlength="255" value="{{ old('TYRO_LOGIN_RESET_PASSWORD_BG_TITLE', $settings['TYRO_LOGIN_RESET_PASSWORD_BG_TITLE']) }}">
                                </div>
                                <div class="form-group" style="margin-bottom:0;">
                                    <label for="TYRO_LOGIN_RESET_PASSWORD_BG_DESCRIPTION" class="form-label">Background description</label>
                                    <input type="text" name="TYRO_LOGIN_RESET_PASSWORD_BG_DESCRIPTION" id="TYRO_LOGIN_RESET_PASSWORD_BG_DESCRIPTION" class="form-input" maxlength="500" value="{{ old('TYRO_LOGIN_RESET_PASSWORD_BG_DESCRIPTION', $settings['TYRO_LOGIN_RESET_PASSWORD_BG_DESCRIPTION']) }}">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        {{-- Sidebar Colors Tab --}}
        @php
            $sbBg   = old('TYRO_DASHBOARD_SIDEBAR_BG', $settings['TYRO_DASHBOARD_SIDEBAR_BG'] ?? '#0e0e0e');
            $sbText = old('TYRO_DASHBOARD_SIDEBAR_TEXT', $settings['TYRO_DASHBOARD_SIDEBAR_TEXT'] ?? '#f8fafc');
            $sbPrimary = old('TYRO_DASHBOARD_SIDEBAR_PRIMARY', $settings['TYRO_DASHBOARD_SIDEBAR_PRIMARY'] ?? '#333333');
            $sbAccent = old('TYRO_DASHBOARD_SIDEBAR_ACCENT', $settings['TYRO_DASHBOARD_SIDEBAR_ACCENT'] ?? '#f5f5f5');
            $sbAccentFg = old('TYRO_DASHBOARD_SIDEBAR_ACCENT_FOREGROUND', $settings['TYRO_DASHBOARD_SIDEBAR_ACCENT_FOREGROUND'] ?? '#171717');
            $sbHeaderBorder = old('TYRO_DASHBOARD_SIDEBAR_HEADER_BORDER', $settings['TYRO_DASHBOARD_SIDEBAR_HEADER_BORDER'] ?? '#333c56');
            $sbAccordionCompact = filter_var(old('TYRO_DASHBOARD_SIDEBAR_ACCORDION_COMPACT', $settings['TYRO_DASHBOARD_SIDEBAR_ACCORDION_COMPACT'] ?? false), FILTER_VALIDATE_BOOLEAN);
        @endphp
        <div class="vtabs-panel" id="vtab-sidebar-colors">
            <div class="card">
                <div class="card-header" style="display:flex;align-items:center;justify-content:space-between;gap:1rem;">
                    <h3 class="card-title">Sidebar Colors</h3>
                    <div style="display:flex;align-items:center;gap:0.5rem;">
                        <button type="button" onclick="confirmResetSbColors()" class="btn btn-secondary btn-sm">Reset to Default</button>
                        <button type="submit" form="systemSettingsForm" class="btn btn-primary btn-sm section-save-button">Save</button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="sys-settings-section-intro">
                        <div class="sys-settings-section-copy">
                            <h4 class="sys-settings-section-heading">Customize the admin sidebar appearance</h4>
                            <p class="sys-settings-section-description">Choose the background, text, highlight, hover, and separator colors for the dashboard sidebar. These values are stored in your <code>.env</code> file and take effect immediately after saving.</p>
                        </div>
                        <span class="sys-settings-section-badge">Sidebar</span>
                    </div>

                    <div class="sys-settings-surface" style="margin-bottom:1.25rem;">
                        <div class="sys-settings-toggles" style="margin-bottom:1rem;">
                            <div class="sys-settings-toggle">
                                <div class="sys-settings-toggle-top">
                                    <div>
                                        <p class="sys-settings-toggle-title">Accordion style sidebar sections</p>
                                        <p class="sys-settings-toggle-description">When enabled, sidebar sections are collapsible. Home &amp; Essentials section stays open while other sections can be collapsed. Writes <code>TYRO_DASHBOARD_SIDEBAR_ACCORDION_COMPACT</code>.</p>
                                    </div>
                                    <div>
                                        <input type="hidden" name="TYRO_DASHBOARD_SIDEBAR_ACCORDION_COMPACT" value="0">
                                        <label class="toggle-label">
                                            <input type="checkbox" name="TYRO_DASHBOARD_SIDEBAR_ACCORDION_COMPACT" value="1" class="toggle-input" id="sb_accordion_compact" {{ $sbAccordionCompact ? 'checked' : '' }}>
                                            <span class="toggle-slider"></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="branding-theme-grid">
                            <div class="branding-theme-color">
                                <input type="color"
                                    name="TYRO_DASHBOARD_SIDEBAR_BG"
                                    id="sb_bg_picker"
                                    value="{{ $sbBg }}"
                                    data-default="#0e0e0e"
                                    style="width:36px;height:36px;padding:2px;border:1px solid var(--border);border-radius:6px;cursor:pointer;background:var(--background);flex-shrink:0;"
                                    oninput="document.getElementById('sb_bg_text').value=this.value;updateSbPreview()">
                                <div class="branding-theme-color-meta">
                                    <div class="branding-theme-color-name">Background</div>
                                    <div class="branding-theme-color-var">--sidebar</div>
                                </div>
                                <input type="text"
                                    id="sb_bg_text"
                                    value="{{ $sbBg }}"
                                    maxlength="7"
                                    class="branding-theme-color-text"
                                    oninput="syncSbPicker(this,'sb_bg_picker');updateSbPreview()">
                                <button type="button" onclick="resetSingleSbColor(this)" class="branding-color-reset" title="Reset to default">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:14px;height:14px;"><path stroke-linecap="round" stroke-linejoin="round" d="M1 4v6h6M23 20v-6h-6"/><path stroke-linecap="round" stroke-linejoin="round" d="M20.49 9A9 9 0 0 0 5.64 5.64L1 10m22 4l-4.64 4.36A9 9 0 0 1 3.51 15"/></svg>
                                </button>
                            </div>
                            <div class="branding-theme-color">
                                <input type="color"
                                    name="TYRO_DASHBOARD_SIDEBAR_TEXT"
                                    id="sb_text_picker"
                                    value="{{ $sbText }}"
                                    data-default="#f8fafc"
                                    style="width:36px;height:36px;padding:2px;border:1px solid var(--border);border-radius:6px;cursor:pointer;background:var(--background);flex-shrink:0;"
                                    oninput="document.getElementById('sb_text_text').value=this.value;updateSbPreview()">
                                <div class="branding-theme-color-meta">
                                    <div class="branding-theme-color-name">Text</div>
                                    <div class="branding-theme-color-var">--sidebar-foreground</div>
                                </div>
                                <input type="text"
                                    id="sb_text_text"
                                    value="{{ $sbText }}"
                                    maxlength="7"
                                    class="branding-theme-color-text"
                                    oninput="syncSbPicker(this,'sb_text_picker');updateSbPreview()">
                                <button type="button" onclick="resetSingleSbColor(this)" class="branding-color-reset" title="Reset to default">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:14px;height:14px;"><path stroke-linecap="round" stroke-linejoin="round" d="M1 4v6h6M23 20v-6h-6"/><path stroke-linecap="round" stroke-linejoin="round" d="M20.49 9A9 9 0 0 0 5.64 5.64L1 10m22 4l-4.64 4.36A9 9 0 0 1 3.51 15"/></svg>
                                </button>
                            </div>
                            <div class="branding-theme-color">
                                <input type="color"
                                    name="TYRO_DASHBOARD_SIDEBAR_PRIMARY"
                                    id="sb_primary_picker"
                                    value="{{ $sbPrimary }}"
                                    data-default="#333333"
                                    style="width:36px;height:36px;padding:2px;border:1px solid var(--border);border-radius:6px;cursor:pointer;background:var(--background);flex-shrink:0;"
                                    oninput="document.getElementById('sb_primary_text').value=this.value;updateSbPreview()">
                                <div class="branding-theme-color-meta">
                                    <div class="branding-theme-color-name">Highlight</div>
                                    <div class="branding-theme-color-var">--sidebar-primary</div>
                                </div>
                                <input type="text"
                                    id="sb_primary_text"
                                    value="{{ $sbPrimary }}"
                                    maxlength="7"
                                    class="branding-theme-color-text"
                                    oninput="syncSbPicker(this,'sb_primary_picker');updateSbPreview()">
                                <button type="button" onclick="resetSingleSbColor(this)" class="branding-color-reset" title="Reset to default">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:14px;height:14px;"><path stroke-linecap="round" stroke-linejoin="round" d="M1 4v6h6M23 20v-6h-6"/><path stroke-linecap="round" stroke-linejoin="round" d="M20.49 9A9 9 0 0 0 5.64 5.64L1 10m22 4l-4.64 4.36A9 9 0 0 1 3.51 15"/></svg>
                                </button>
                            </div>
                            <div class="branding-theme-color">
                                <input type="color"
                                    name="TYRO_DASHBOARD_SIDEBAR_ACCENT"
                                    id="sb_accent_picker"
                                    value="{{ $sbAccent }}"
                                    data-default="#f5f5f5"
                                    style="width:36px;height:36px;padding:2px;border:1px solid var(--border);border-radius:6px;cursor:pointer;background:var(--background);flex-shrink:0;"
                                    oninput="document.getElementById('sb_accent_text').value=this.value;updateSbPreview()">
                                <div class="branding-theme-color-meta">
                                    <div class="branding-theme-color-name">Hover</div>
                                    <div class="branding-theme-color-var">--sidebar-accent</div>
                                </div>
                                <input type="text"
                                    id="sb_accent_text"
                                    value="{{ $sbAccent }}"
                                    maxlength="7"
                                    class="branding-theme-color-text"
                                    oninput="syncSbPicker(this,'sb_accent_picker');updateSbPreview()">
                                <button type="button" onclick="resetSingleSbColor(this)" class="branding-color-reset" title="Reset to default">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:14px;height:14px;"><path stroke-linecap="round" stroke-linejoin="round" d="M1 4v6h6M23 20v-6h-6"/><path stroke-linecap="round" stroke-linejoin="round" d="M20.49 9A9 9 0 0 0 5.64 5.64L1 10m22 4l-4.64 4.36A9 9 0 0 1 3.51 15"/></svg>
                                </button>
                            </div>
                            <div class="branding-theme-color">
                                <input type="color"
                                    name="TYRO_DASHBOARD_SIDEBAR_ACCENT_FOREGROUND"
                                    id="sb_accent_fg_picker"
                                    value="{{ $sbAccentFg }}"
                                    data-default="#171717"
                                    style="width:36px;height:36px;padding:2px;border:1px solid var(--border);border-radius:6px;cursor:pointer;background:var(--background);flex-shrink:0;"
                                    oninput="document.getElementById('sb_accent_fg_text').value=this.value;updateSbPreview()">
                                <div class="branding-theme-color-meta">
                                    <div class="branding-theme-color-name">Hover Text</div>
                                    <div class="branding-theme-color-var">--sidebar-accent-foreground</div>
                                </div>
                                <input type="text"
                                    id="sb_accent_fg_text"
                                    value="{{ $sbAccentFg }}"
                                    maxlength="7"
                                    class="branding-theme-color-text"
                                    oninput="syncSbPicker(this,'sb_accent_fg_picker');updateSbPreview()">
                                <button type="button" onclick="resetSingleSbColor(this)" class="branding-color-reset" title="Reset to default">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:14px;height:14px;"><path stroke-linecap="round" stroke-linejoin="round" d="M1 4v6h6M23 20v-6h-6"/><path stroke-linecap="round" stroke-linejoin="round" d="M20.49 9A9 9 0 0 0 5.64 5.64L1 10m22 4l-4.64 4.36A9 9 0 0 1 3.51 15"/></svg>
                                </button>
                            </div>
                            <div class="branding-theme-color">
                                <input type="color"
                                    name="TYRO_DASHBOARD_SIDEBAR_HEADER_BORDER"
                                    id="sb_header_border_picker"
                                    value="{{ $sbHeaderBorder }}"
                                    data-default="#333c56"
                                    style="width:36px;height:36px;padding:2px;border:1px solid var(--border);border-radius:6px;cursor:pointer;background:var(--background);flex-shrink:0;"
                                    oninput="document.getElementById('sb_header_border_text').value=this.value;updateSbPreview()">
                                <div class="branding-theme-color-meta">
                                    <div class="branding-theme-color-name">Separator</div>
                                    <div class="branding-theme-color-var">--sidebar-header-border</div>
                                </div>
                                <input type="text"
                                    id="sb_header_border_text"
                                    value="{{ $sbHeaderBorder }}"
                                    maxlength="7"
                                    class="branding-theme-color-text"
                                    oninput="syncSbPicker(this,'sb_header_border_picker');updateSbPreview()">
                                <button type="button" onclick="resetSingleSbColor(this)" class="branding-color-reset" title="Reset to default">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:14px;height:14px;"><path stroke-linecap="round" stroke-linejoin="round" d="M1 4v6h6M23 20v-6h-6"/><path stroke-linecap="round" stroke-linejoin="round" d="M20.49 9A9 9 0 0 0 5.64 5.64L1 10m22 4l-4.64 4.36A9 9 0 0 1 3.51 15"/></svg>
                                </button>
                            </div>
                        </div>

                        {{-- Sidebar live preview --}}
                        <h4 class="branding-surface-title" style="margin:1.25rem 0 0.75rem;">Sidebar Preview</h4>
                        <div id="sidebarPreview" style="border-radius:0.75rem;overflow:hidden;border:1px solid var(--border);width:25rem;">
                            <div style="padding:1rem 1.25rem;display:flex;align-items:center;gap:0.75rem;background:{{ $sbBg }};border-bottom:1px solid {{ $sbHeaderBorder }};">
                                <div style="width:24px;height:24px;border-radius:6px;background:{{ $sbText }};opacity:0.85;display:flex;align-items:center;justify-content:center;">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="{{ $sbBg }}" stroke-width="2" style="width:14px;height:14px;"><rect x="3" y="3" width="18" height="18" rx="2"/></svg>
                                </div>
                                <span style="font-size:0.94rem;font-weight:600;color:{{ $sbText }};">Dashboard</span>
                            </div>
                            <div style="padding:0.5rem 0.75rem 0.75rem;display:flex;flex-direction:column;gap:4px;background:{{ $sbBg }};">
                                <div style="padding:0.4rem 0.6rem;border-radius:6px;font-size:0.82rem;font-weight:500;color:{{ $sbText }};opacity:0.7;">Users</div>
                                <div style="padding:0.4rem 0.6rem;border-radius:6px;font-size:0.82rem;font-weight:500;background:{{ $sbPrimary }};color:{{ $sbText }};">Settings</div>
                                <div style="padding:0.4rem 0.6rem;border-radius:6px;font-size:0.82rem;font-weight:500;background:{{ $sbAccent }};color:{{ $sbAccentFg }};">System</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Admin Bar Colors Tab --}}
        @php
            $abBg = old('TYRO_DASHBOARD_ADMIN_BAR_BG_COLOR', $settings['TYRO_DASHBOARD_ADMIN_BAR_BG_COLOR'] ?? '#000000');
            $abText = old('TYRO_DASHBOARD_ADMIN_BAR_TEXT_COLOR', $settings['TYRO_DASHBOARD_ADMIN_BAR_TEXT_COLOR'] ?? '#ffffff');
            $abMessage = old('TYRO_DASHBOARD_ADMIN_BAR_MESSAGE', $settings['TYRO_DASHBOARD_ADMIN_BAR_MESSAGE'] ?? '');
            $abAlign = old('TYRO_DASHBOARD_ADMIN_BAR_ALIGN', $settings['TYRO_DASHBOARD_ADMIN_BAR_ALIGN'] ?? 'left');
            $abHeight = old('TYRO_DASHBOARD_ADMIN_BAR_HEIGHT', $settings['TYRO_DASHBOARD_ADMIN_BAR_HEIGHT'] ?? '40px');
        @endphp
        <div class="vtabs-panel" id="vtab-admin-bar-colors">
            <div class="card">
                <div class="card-header" style="display:flex;align-items:center;justify-content:space-between;gap:1rem;">
                    <h3 class="card-title">Admin Bar</h3>
                    <div style="display:flex;align-items:center;gap:0.5rem;">
                        <button type="button" onclick="confirmResetAbColors()" class="btn btn-secondary btn-sm">Reset to Default</button>
                        <button type="submit" form="systemSettingsForm" class="btn btn-primary btn-sm section-save-button">Save</button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="sys-settings-section-intro">
                        <div class="sys-settings-section-copy">
                            <h4 class="sys-settings-section-heading">Configure the admin notice bar</h4>
                            <p class="sys-settings-section-description">Control the visibility, colors, message, alignment, and height of the admin notice bar at the top of the dashboard. Values are stored in <code>.env</code>.</p>
                        </div>
                        <span class="sys-settings-section-badge">Admin Bar</span>
                    </div>

                    <div class="sys-settings-surface">
                        <div class="sys-settings-toggles" style="margin-bottom:1rem;">
                            <div class="sys-settings-toggle">
                                <div class="sys-settings-toggle-top">
                                    <div>
                                        <p class="sys-settings-toggle-title">Enable admin bar</p>
                                        <p class="sys-settings-toggle-description">Writes <code>TYRO_DASHBOARD_ADMIN_BAR_ENABLED</code>.</p>
                                    </div>
                                    <div>
                                        <input type="hidden" name="TYRO_DASHBOARD_ADMIN_BAR_ENABLED" value="0">
                                        <label class="toggle-label">
                                            <input type="checkbox" name="TYRO_DASHBOARD_ADMIN_BAR_ENABLED" value="1" class="toggle-input" id="ab_enabled" {{ old('TYRO_DASHBOARD_ADMIN_BAR_ENABLED', $settings['TYRO_DASHBOARD_ADMIN_BAR_ENABLED']) ? 'checked' : '' }}>
                                            <span class="toggle-slider"></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group" style="margin-bottom:0.85rem;">
                            <label for="TYRO_DASHBOARD_ADMIN_BAR_MESSAGE" class="form-label">Bar message</label>
                            <input type="text" name="TYRO_DASHBOARD_ADMIN_BAR_MESSAGE" id="TYRO_DASHBOARD_ADMIN_BAR_MESSAGE"
                                   class="form-input" maxlength="500"
                                   value="{{ $abMessage }}">
                        </div>

                        <div class="branding-theme-grid" style="margin-bottom:1rem;">
                            <div class="branding-theme-color">
                                <input type="color"
                                    name="TYRO_DASHBOARD_ADMIN_BAR_BG_COLOR"
                                    id="ab_bg_picker"
                                    value="{{ $abBg }}"
                                    data-default="#000000"
                                    style="width:36px;height:36px;padding:2px;border:1px solid var(--border);border-radius:6px;cursor:pointer;background:var(--background);flex-shrink:0;"
                                    oninput="document.getElementById('ab_bg_text').value=this.value;updateAbPreview()">
                                <div class="branding-theme-color-meta">
                                    <div class="branding-theme-color-name">Bar Background</div>
                                    <div class="branding-theme-color-var">--admin-bar-bg</div>
                                </div>
                                <input type="text"
                                    id="ab_bg_text"
                                    value="{{ $abBg }}"
                                    maxlength="7"
                                    class="branding-theme-color-text"
                                    oninput="syncSbPicker(this,'ab_bg_picker');updateAbPreview()">
                                <button type="button" onclick="resetSingleAbColor(this)" class="branding-color-reset" title="Reset to default">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:14px;height:14px;"><path stroke-linecap="round" stroke-linejoin="round" d="M1 4v6h6M23 20v-6h-6"/><path stroke-linecap="round" stroke-linejoin="round" d="M20.49 9A9 9 0 0 0 5.64 5.64L1 10m22 4l-4.64 4.36A9 9 0 0 1 3.51 15"/></svg>
                                </button>
                            </div>
                            <div class="branding-theme-color">
                                <input type="color"
                                    name="TYRO_DASHBOARD_ADMIN_BAR_TEXT_COLOR"
                                    id="ab_text_picker"
                                    value="{{ $abText }}"
                                    data-default="#ffffff"
                                    style="width:36px;height:36px;padding:2px;border:1px solid var(--border);border-radius:6px;cursor:pointer;background:var(--background);flex-shrink:0;"
                                    oninput="document.getElementById('ab_text_text').value=this.value;updateAbPreview()">
                                <div class="branding-theme-color-meta">
                                    <div class="branding-theme-color-name">Bar Text</div>
                                    <div class="branding-theme-color-var">--admin-bar-text</div>
                                </div>
                                <input type="text"
                                    id="ab_text_text"
                                    value="{{ $abText }}"
                                    maxlength="7"
                                    class="branding-theme-color-text"
                                    oninput="syncSbPicker(this,'ab_text_picker');updateAbPreview()">
                                <button type="button" onclick="resetSingleAbColor(this)" class="branding-color-reset" title="Reset to default">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:14px;height:14px;"><path stroke-linecap="round" stroke-linejoin="round" d="M1 4v6h6M23 20v-6h-6"/><path stroke-linecap="round" stroke-linejoin="round" d="M20.49 9A9 9 0 0 0 5.64 5.64L1 10m22 4l-4.64 4.36A9 9 0 0 1 3.51 15"/></svg>
                                </button>
                            </div>
                        </div>

                        <div class="sys-settings-metrics" style="margin-bottom:0;">
                            <div class="form-group sys-settings-metric" style="margin-bottom:0;">
                                <label for="TYRO_DASHBOARD_ADMIN_BAR_ALIGN" class="form-label">Text alignment</label>
                                <select name="TYRO_DASHBOARD_ADMIN_BAR_ALIGN" id="ab_align" class="form-select" onchange="updateAbPreview()">
                                    <option value="left" {{ $abAlign === 'left' ? 'selected' : '' }}>Left</option>
                                    <option value="center" {{ $abAlign === 'center' ? 'selected' : '' }}>Center</option>
                                    <option value="right" {{ $abAlign === 'right' ? 'selected' : '' }}>Right</option>
                                </select>
                            </div>
                            <div class="form-group sys-settings-metric" style="margin-bottom:0;">
                                <label for="TYRO_DASHBOARD_ADMIN_BAR_HEIGHT" class="form-label">Bar height</label>
                                <input type="text" name="TYRO_DASHBOARD_ADMIN_BAR_HEIGHT" id="ab_height"
                                       class="form-input" maxlength="20"
                                       value="{{ $abHeight }}" onchange="updateAbPreview()">
                            </div>
                        </div>

                        {{-- Admin bar live preview --}}
                        <h4 class="branding-surface-title" style="margin:1.25rem 0 0.75rem;">Admin Bar Preview</h4>
                        <div id="adminBarPreview" style="border-radius:0.75rem;overflow:hidden;border:1px solid var(--border);">
                            <div id="ab_preview_bar" style="padding:0.5rem 1rem;background:{{ $abBg }};color:{{ $abText }};text-align:{{ $abAlign }};font-size:0.85rem;font-weight:500;height:{{ $abHeight }};display:flex;align-items:center;justify-content:{{ $abAlign === 'center' ? 'center' : ($abAlign === 'right' ? 'flex-end' : 'flex-start') }};">
                                <span id="ab_preview_text">{{ $abMessage ?: 'Admin notice bar message' }}</span>
                            </div>
                            <div style="padding:1rem;background:var(--card);">
                                <div style="height:12px;width:60%;background:var(--muted);border-radius:4px;margin-bottom:8px;"></div>
                                <div style="height:12px;width:40%;background:var(--muted);border-radius:4px;"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div><!-- /vtab-admin-bar-colors -->

        {{-- Dashboard Colors Tab --}}
        @php
            $dcColors = \HasinHayder\TyroDashboard\Support\DashboardColors::form();
            $dcDefaults = \HasinHayder\TyroDashboard\Support\DashboardColors::defaults();
        @endphp
        <div class="vtabs-panel" id="vtab-dashboard-colors">
            <div class="card">
                <div class="card-header" style="display:flex;align-items:center;justify-content:space-between;gap:1rem;">
                    <h3 class="card-title" style="margin:0;">Dashboard Colors</h3>
                    <div style="display:flex;align-items:center;gap:0.5rem;">
                        <button type="button" onclick="confirmResetAllDcColors()" class="btn btn-secondary btn-sm">Reset All To Default</button>
                        <button type="submit" form="systemSettingsForm" class="btn btn-primary btn-sm section-save-button">Save</button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="sys-settings-section-intro" style="margin-bottom:1rem;">
                        <div class="sys-settings-section-copy">
                            <h4 class="sys-settings-section-heading">Customise the shadcn UI palette</h4>
                            <p class="sys-settings-section-description">Pick a hex colour and opacity for each shadcn UI variable. Light and dark mode palettes are stored independently in <code>storage/app/dashboard-colors.json</code>.</p>
                        </div>
                        <span class="sys-settings-section-badge">SHADCN</span>
                    </div>

                    <div id="dcTabBar" style="display:flex;gap:0.5rem;margin-bottom:1.5rem;border-bottom:1px solid var(--border);padding-bottom:0;">
                        <button type="button" id="dcTabLight"
                            onclick="switchDcTab('light')"
                            style="padding:0.5rem 1.1rem;font-size:0.85rem;font-weight:600;border:none;border-bottom:2px solid var(--primary);background:none;cursor:pointer;color:var(--foreground);margin-bottom:-1px;">
                            Light Mode
                        </button>
                        <button type="button" id="dcTabDark"
                            onclick="switchDcTab('dark')"
                            style="padding:0.5rem 1.1rem;font-size:0.85rem;font-weight:600;border:none;border-bottom:2px solid transparent;background:none;cursor:pointer;color:var(--muted-foreground);margin-bottom:-1px;">
                            Dark Mode
                        </button>
                    </div>

                    @foreach(['light', 'dark'] as $dcMode)
                        <div id="dcPanel{{ ucfirst($dcMode) }}" data-dc-panel="{{ $dcMode }}" style="{{ $dcMode === 'light' ? '' : 'display:none;' }}">
                            <div class="branding-theme-grid" style="grid-template-columns:repeat(3,1fr);">
                                @foreach($dcColors[$dcMode] ?? [] as $dcVar => $dcConfig)
                                    @php
                                        $dcUid = $dcMode.'_'.preg_replace('/[^a-z0-9-]/', '', $dcVar);
                                        $dcDefHex = $dcDefaults[$dcMode][$dcVar]['hex'] ?? $dcConfig['hex'];
                                        $dcDefAlpha = $dcDefaults[$dcMode][$dcVar]['alpha'] ?? $dcConfig['alpha'];
                                    @endphp
                                    <div class="branding-theme-color" style="flex-direction:column;align-items:stretch;gap:0.5rem;">
                                        <div style="display:flex;align-items:center;gap:0.75rem;">
                                            <input type="color"
                                                name="dashboard_colors[{{ $dcMode }}][{{ $dcVar }}][hex]"
                                                value="{{ $dcConfig['hex'] }}"
                                                data-default-hex="{{ $dcDefHex }}"
                                                style="width:36px;height:36px;padding:2px;border:1px solid var(--border);border-radius:6px;cursor:pointer;background:var(--background);flex-shrink:0;"
                                                oninput="dcSyncPicker(this, '{{ $dcUid }}')">
                                            <div class="branding-theme-color-meta">
                                                <div class="branding-theme-color-name">{{ $dcConfig['label'] }}</div>
                                                <div class="branding-theme-color-var">{{ $dcVar }}</div>
                                            </div>
                                            <button type="button" onclick="resetSingleDcColor(this)" class="btn btn-sm" style="padding:4px;border:none;background:none;cursor:pointer;color:var(--muted-foreground);flex-shrink:0;border-radius:4px;display:flex;align-items:center;justify-content:center;" title="Reset to default">
                                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:14px;height:14px;"><path stroke-linecap="round" stroke-linejoin="round" d="M1 4v6h6M23 20v-6h-6"/><path stroke-linecap="round" stroke-linejoin="round" d="M20.49 9A9 9 0 0 0 5.64 5.64L1 10m22 4l-4.64 4.36A9 9 0 0 1 3.51 15"/></svg>
                                            </button>
                                            <input type="text"
                                                id="dcHex_{{ $dcUid }}"
                                                value="{{ $dcConfig['hex'] }}"
                                                data-default-hex="{{ $dcDefHex }}"
                                                maxlength="7"
                                                class="branding-theme-color-text"
                                                oninput="dcSyncHex(this, '{{ $dcMode }}', '{{ preg_replace("/[^a-z0-9-]/", '', $dcVar) }}')">
                                        </div>
                                        <div style="display:flex;align-items:center;gap:8px;">
                                            <span style="font-size:0.7rem;font-weight:600;color:var(--muted-foreground);white-space:nowrap;">Alpha</span>
                                            <input type="range"
                                                name="dashboard_colors[{{ $dcMode }}][{{ $dcVar }}][alpha]"
                                                value="{{ $dcConfig['alpha'] }}"
                                                data-default-alpha="{{ $dcDefAlpha }}"
                                                min="0" max="100"
                                                style="flex:1;height:4px;cursor:pointer;accent-color:var(--primary);"
                                                oninput="dcSyncAlpha(this, '{{ $dcMode }}', '{{ preg_replace("/[^a-z0-9-]/", '', $dcVar) }}')">
                                            <input type="number"
                                                id="dcAlpha_{{ $dcUid }}"
                                                value="{{ $dcConfig['alpha'] }}"
                                                data-default-alpha="{{ $dcDefAlpha }}"
                                                min="0" max="100"
                                                style="width:56px;padding:2px 6px;font-size:0.75rem;text-align:center;border:1px solid var(--border);border-radius:4px;background:var(--background);color:var(--foreground);font-family:monospace;">
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div><!-- /vtab-dashboard-colors -->

    </div>
</div>

    <div class="sys-settings-save-row">
        <button type="submit" class="btn btn-primary">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width:18px;height:18px;"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path><polyline points="17 21 17 13 7 13 7 21"></polyline><polyline points="7 3 7 8 15 8"></polyline></svg>
            Save Settings
        </button>
    </div>
</form>
@endsection

@push('scripts')
<script>
function syncSbPicker(textInput, pickerId) {
    var picker = document.getElementById(pickerId);
    if (picker) picker.value = textInput.value;
}

function updateSbPreview() {
    var bg = document.getElementById('sb_bg_picker').value;
    var text = document.getElementById('sb_text_picker').value;
    var primary = document.getElementById('sb_primary_picker').value;
    var accent = document.getElementById('sb_accent_picker').value;
    var accentFg = document.getElementById('sb_accent_fg_picker').value;
    var headerBorder = document.getElementById('sb_header_border_picker').value;

    var preview = document.getElementById('sidebarPreview');
    if (!preview) return;

    var header = preview.querySelector('div:first-child');
    if (header) {
        header.style.background = bg;
        header.style.borderBottomColor = headerBorder;
        var iconBox = header.querySelector('div:first-child');
        if (iconBox) {
            iconBox.style.background = text;
            var svg = iconBox.querySelector('svg');
            if (svg) svg.setAttribute('stroke', bg);
        }
        var title = header.querySelector('span');
        if (title) title.style.color = text;
    }

    var body = preview.querySelector('div:last-child');
    if (body) {
        body.style.background = bg;
        var items = body.querySelectorAll('div');
        if (items.length >= 3) {
            items[0].style.color = text;
            items[1].style.background = primary;
            items[1].style.color = text;
            items[2].style.background = accent;
            items[2].style.color = accentFg;
        }
    }
}

function updateAbPreview() {
    var bg = document.getElementById('ab_bg_picker').value;
    var text = document.getElementById('ab_text_picker').value;
    var align = document.getElementById('ab_align').value;
    var height = document.getElementById('ab_height').value;
    var msg = document.getElementById('TYRO_DASHBOARD_ADMIN_BAR_MESSAGE').value;

    var bar = document.getElementById('ab_preview_bar');
    if (!bar) return;

    bar.style.background = bg;
    bar.style.color = text;
    bar.style.height = height;
    bar.style.justifyContent = align === 'center' ? 'center' : (align === 'right' ? 'flex-end' : 'flex-start');

    var textEl = document.getElementById('ab_preview_text');
    if (textEl) textEl.textContent = msg || 'Admin notice bar message';
}

// Sticky sidebar save button visibility
(function() {
    var topBtn = document.getElementById('systemSettingsSaveButton');
    var sideBar = document.querySelector('.vtabs-save-bar');
    if (!topBtn || !sideBar) return;

    var ticking = false;

    function updateSideSaveBtn() {
        var rect = topBtn.getBoundingClientRect();
        var isVisible = rect.top < window.innerHeight && rect.bottom > 0;
        sideBar.classList.toggle('visible', !isVisible);
        ticking = false;
    }

    window.addEventListener('scroll', function() {
        if (!ticking) {
            requestAnimationFrame(updateSideSaveBtn);
            ticking = true;
        }
    });
    updateSideSaveBtn();
})();

// ── Sidebar Colors Reset ──────────────────────────────────────
function resetSingleSbColor(btn) {
    var card = btn.closest('.branding-theme-color');
    if (!card) return;
    var colorIn = card.querySelector('input[type="color"]');
    var textIn = card.querySelector('input[type="text"]');
    var def = colorIn ? colorIn.dataset.default : null;
    if (def && colorIn) colorIn.value = def;
    if (def && textIn) textIn.value = def;
    updateSbPreview();
}

function resetSbColors() {
    var defaults = {
        sb_bg_picker: '#0e0e0e',
        sb_text_picker: '#f8fafc',
        sb_primary_picker: '#333333',
        sb_accent_picker: '#f5f5f5',
        sb_accent_fg_picker: '#171717',
        sb_header_border_picker: '#333c56'
    };
    Object.keys(defaults).forEach(function(id) {
        var picker = document.getElementById(id);
        if (!picker) return;
        var def = picker.dataset.default || defaults[id];
        picker.value = def;
        var textId = id.replace('_picker', '_text');
        var textInput = document.getElementById(textId);
        if (textInput) textInput.value = def;
    });
    updateSbPreview();
}

function saveForm() {
    var f = document.getElementById('systemSettingsForm');
    if (f) f.requestSubmit();
}

function confirmResetSbColors() {
    showDanger(
        'Reset sidebar colours?',
        'This will revert the sidebar to its default colours.',
        { confirmText: 'Reset to Default' }
    ).then(function(confirmed) {
        if (confirmed) { resetSbColors(); saveForm(); }
    });
}

// ── Admin Bar Colors Reset ────────────────────────────────────
function resetSingleAbColor(btn) {
    var card = btn.closest('.branding-theme-color');
    if (!card) return;
    var colorIn = card.querySelector('input[type="color"]');
    var textIn = card.querySelector('input[type="text"]');
    var def = colorIn ? colorIn.dataset.default : null;
    if (def && colorIn) colorIn.value = def;
    if (def && textIn) textIn.value = def;
    updateAbPreview();
}

function resetAbColors() {
    var defaults = {
        ab_bg_picker: '#000000',
        ab_text_picker: '#ffffff'
    };
    Object.keys(defaults).forEach(function(id) {
        var picker = document.getElementById(id);
        if (!picker) return;
        var def = picker.dataset.default || defaults[id];
        picker.value = def;
        var textId = id.replace('_picker', '_text');
        var textInput = document.getElementById(textId);
        if (textInput) textInput.value = def;
    });
    updateAbPreview();
}

function confirmResetAbColors() {
    showDanger(
        'Reset admin bar colours?',
        'This will revert the admin bar to its default colours.',
        { confirmText: 'Reset to Default' }
    ).then(function(confirmed) {
        if (confirmed) { resetAbColors(); saveForm(); }
    });
}

// ── Dashboard Colors ─────────────────────────────────────────
function switchDcTab(mode) {
    var lightTab = document.getElementById('dcTabLight');
    var darkTab = document.getElementById('dcTabDark');
    var lightPanel = document.getElementById('dcPanelLight');
    var darkPanel = document.getElementById('dcPanelDark');
    if (!lightTab || !darkTab || !lightPanel || !darkPanel) return;

    if (mode === 'light') {
        lightTab.style.borderBottomColor = 'var(--primary)';
        lightTab.style.color = 'var(--foreground)';
        darkTab.style.borderBottomColor = 'transparent';
        darkTab.style.color = 'var(--muted-foreground)';
        lightPanel.style.display = '';
        darkPanel.style.display = 'none';
    } else {
        darkTab.style.borderBottomColor = 'var(--primary)';
        darkTab.style.color = 'var(--foreground)';
        lightTab.style.borderBottomColor = 'transparent';
        lightTab.style.color = 'var(--muted-foreground)';
        darkPanel.style.display = '';
        lightPanel.style.display = 'none';
    }
}

function dcSyncPicker(colorInput, uid) {
    var textInput = document.getElementById('dcHex_' + uid);
    if (textInput) textInput.value = colorInput.value;
}

function dcSyncHex(textInput, mode, varName) {
    if (!textInput.value.match(/^#[0-9a-fA-F]{6}$/)) return;
    var card = textInput.closest('.branding-theme-color');
    if (!card) return;
    var colorPicker = card.querySelector('input[type="color"]');
    if (colorPicker) colorPicker.value = textInput.value;
}

function dcSyncAlpha(rangeInput, mode, varName) {
    var card = rangeInput.closest('.branding-theme-color');
    if (!card) return;
    var numInput = card.querySelector('input[type="number"]');
    if (numInput) numInput.value = rangeInput.value;
}

function resetSingleDcColor(btn) {
    var card = btn.closest('.branding-theme-color');
    if (!card) return;
    var colorPicker = card.querySelector('input[type="color"]');
    var hexText = card.querySelector('input[type="text"]');
    var rangeInput = card.querySelector('input[type="range"]');
    var numInput = card.querySelector('input[type="number"]');

    if (colorPicker && colorPicker.dataset.defaultHex) colorPicker.value = colorPicker.dataset.defaultHex;
    if (hexText && hexText.dataset.defaultHex) hexText.value = hexText.dataset.defaultHex;
    if (rangeInput && rangeInput.dataset.defaultAlpha) rangeInput.value = rangeInput.dataset.defaultAlpha;
    if (numInput && numInput.dataset.defaultAlpha) numInput.value = numInput.dataset.defaultAlpha;
}

function resetAllDcColors() {
    document.querySelectorAll('#vtab-dashboard-colors .branding-theme-color').forEach(function(card) {
        var colorPicker = card.querySelector('input[type="color"]');
        var hexText = card.querySelector('input[type="text"]');
        var rangeInput = card.querySelector('input[type="range"]');
        var numInput = card.querySelector('input[type="number"]');

        if (colorPicker && colorPicker.dataset.defaultHex) colorPicker.value = colorPicker.dataset.defaultHex;
        if (hexText && hexText.dataset.defaultHex) hexText.value = hexText.dataset.defaultHex;
        if (rangeInput && rangeInput.dataset.defaultAlpha) rangeInput.value = rangeInput.dataset.defaultAlpha;
        if (numInput && numInput.dataset.defaultAlpha) numInput.value = numInput.dataset.defaultAlpha;
    });
}

function confirmResetAllDcColors() {
    showDanger(
        'Reset dashboard colours?',
        'This will revert all shadcn UI colour variables to their defaults.',
        { confirmText: 'Reset to Default' }
    ).then(function(confirmed) {
        if (confirmed) { resetAllDcColors(); saveForm(); }
    });
}

(function() {
    var form = document.getElementById('systemSettingsForm');
    if (!form) return;

    var submitting = false;
    var mainBtn = document.getElementById('systemSettingsSaveButton');
    var sectionBtns = form.querySelectorAll('button[type="submit"]');

    form.addEventListener('submit', function(e) {
        if (submitting) {
            e.preventDefault();
            return;
        }
        e.preventDefault();
        submitting = true;

        sectionBtns.forEach(function(b) { b.disabled = true; });
        var origHTML = mainBtn ? mainBtn.innerHTML : '';
        if (mainBtn) mainBtn.innerHTML = 'Saving...';

        fetch(form.getAttribute('action'), {
            method: 'POST',
            headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
            body: new FormData(form)
        }).then(function(r) {
            if (r.ok) {
                return r.json().then(function(d) {
                    showToast(d.message, 'success');
                    setTimeout(function() {
                        fetch('{{ route($dashboardRoute::name('settings.system.clear-config-cache')) }}', {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                                'Content-Type': 'application/json',
                                'Accept': 'application/json'
                            }
                        }).catch(function() {});
                    }, 500);
                });
            }
            if (r.status === 422) {
                return r.json().then(function(d) {
                    var errs = Object.values(d.errors || {});
                    showToast(errs.length ? errs[0][0] : 'Validation failed.', 'error');
                });
            }
            return r.json().then(function(d) {
                showToast(d.message || 'Server error.', 'error');
            });
        }).catch(function() {
            showToast('Network error. Please try again.', 'error');
        }).finally(function() {
            submitting = false;
            sectionBtns.forEach(function(b) { b.disabled = false; });
            if (mainBtn) mainBtn.innerHTML = origHTML;
        });
    });
})();

// ── Conditional Field Visibility ──────────────────────────────
(function() {
    var pairs = [
        { toggle: 'TYRO_LOGIN_OTP_ENABLED', target: 'otp-details-surface' },
        { toggle: 'TYRO_LOGIN_2FA_ENABLED', target: 'twofa-details-surface' },
        { toggle: 'TYRO_LOGIN_SOCIAL_ENABLED', target: 'social-details-surface' },
        { toggle: 'TYRO_LOGIN_LOCKOUT_ENABLED', target: 'lockout-details-surface' },
        { toggle: 'TYRO_AUDIT_ENABLED', target: 'tyro_audit_retention_group' },
    ];

    function updateConditionalVisibility() {
        pairs.forEach(function(p) {
            var checkbox = document.getElementById(p.toggle);
            var target = document.getElementById(p.target);
            if (!checkbox || !target) return;
            target.style.display = checkbox.checked ? '' : 'none';
        });
    }

    pairs.forEach(function(p) {
        var checkbox = document.getElementById(p.toggle);
        if (checkbox) {
            checkbox.addEventListener('change', updateConditionalVisibility);
        }
    });

    updateConditionalVisibility();

    // Captcha details: show if either captcha login or captcha register is enabled
    var captchaSurface = document.getElementById('captcha-details-surface');
    if (captchaSurface) {
        var cl = document.getElementById('TYRO_LOGIN_CAPTCHA_LOGIN');
        var cr = document.getElementById('TYRO_LOGIN_CAPTCHA_REGISTER');
        function updateCaptcha() {
            captchaSurface.style.display = (cl && cl.checked) || (cr && cr.checked) ? '' : 'none';
        }
        if (cl) cl.addEventListener('change', updateCaptcha);
        if (cr) cr.addEventListener('change', updateCaptcha);
        updateCaptcha();
    }
})();
</script>
@endpush
