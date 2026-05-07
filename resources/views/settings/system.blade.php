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
            <button class="vtabs-item" data-vtab="sidebar-admin-bar" type="button">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"/><line x1="9" y1="3" x2="9" y2="21"/></svg>
                Sidebar &amp; Admin Bar
            </button>
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

                                <div class="sys-settings-metrics">
                                    <div class="form-group sys-settings-metric" style="margin-bottom:0;">
                                        <label for="TYRO_DASHBOARD_SIDEBAR_BG" class="form-label">Sidebar background</label>
                                        <input type="text" name="TYRO_DASHBOARD_SIDEBAR_BG" id="TYRO_DASHBOARD_SIDEBAR_BG"
                                               class="form-input" maxlength="50"
                                               value="{{ old('TYRO_DASHBOARD_SIDEBAR_BG', $settings['TYRO_DASHBOARD_SIDEBAR_BG']) }}">
                                    </div>
                                    <div class="form-group sys-settings-metric" style="margin-bottom:0;">
                                        <label for="TYRO_DASHBOARD_SIDEBAR_TEXT" class="form-label">Sidebar text</label>
                                        <input type="text" name="TYRO_DASHBOARD_SIDEBAR_TEXT" id="TYRO_DASHBOARD_SIDEBAR_TEXT"
                                               class="form-input" maxlength="50"
                                               value="{{ old('TYRO_DASHBOARD_SIDEBAR_TEXT', $settings['TYRO_DASHBOARD_SIDEBAR_TEXT']) }}">
                                    </div>
                                </div>

                                <div class="sys-settings-metrics" style="margin-top:0.85rem;">
                                    <div class="form-group sys-settings-metric" style="margin-bottom:0;">
                                        <label for="TYRO_DASHBOARD_SIDEBAR_PRIMARY" class="form-label">Sidebar primary</label>
                                        <input type="text" name="TYRO_DASHBOARD_SIDEBAR_PRIMARY" id="TYRO_DASHBOARD_SIDEBAR_PRIMARY"
                                               class="form-input" maxlength="50"
                                               value="{{ old('TYRO_DASHBOARD_SIDEBAR_PRIMARY', $settings['TYRO_DASHBOARD_SIDEBAR_PRIMARY']) }}">
                                    </div>
                                    <div class="form-group sys-settings-metric" style="margin-bottom:0;">
                                        <label for="TYRO_DASHBOARD_SIDEBAR_ACCENT" class="form-label">Sidebar accent</label>
                                        <input type="text" name="TYRO_DASHBOARD_SIDEBAR_ACCENT" id="TYRO_DASHBOARD_SIDEBAR_ACCENT"
                                               class="form-input" maxlength="50"
                                               value="{{ old('TYRO_DASHBOARD_SIDEBAR_ACCENT', $settings['TYRO_DASHBOARD_SIDEBAR_ACCENT']) }}">
                                    </div>
                                </div>
                            </div>

                            <div class="sys-settings-surface">
                                <h4 class="sys-settings-surface-title">Sidebar Behavior</h4>
                                <p class="sys-settings-surface-description">Toggle sidebar collapse, accordion mode, and example sections.</p>

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
                                                <p class="sys-settings-toggle-title">Compact accordion</p>
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
                                    <p class="form-hint">How long RBAC data is cached. Default is 300 (5 minutes).</p>
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

                                <div class="form-group" style="margin-bottom:0;">
                                    <label for="TYRO_LOGIN_REDIRECT_AFTER_LOGOUT" class="form-label">After logout</label>
                                    <input type="text" name="TYRO_LOGIN_REDIRECT_AFTER_LOGOUT" id="TYRO_LOGIN_REDIRECT_AFTER_LOGOUT"
                                           class="form-input" maxlength="255"
                                           value="{{ old('TYRO_LOGIN_REDIRECT_AFTER_LOGOUT', $settings['TYRO_LOGIN_REDIRECT_AFTER_LOGOUT']) }}">
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
                                                <p class="sys-settings-toggle-title">Allow 2FA skip</p>
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

                                <div class="sys-settings-metrics" style="margin-top:0.85rem;">
                                    <div class="form-group sys-settings-metric" style="margin-bottom:0;">
                                        <label for="TYRO_LOGIN_OTP_LENGTH" class="form-label">OTP length</label>
                                        <input type="number" name="TYRO_LOGIN_OTP_LENGTH" id="TYRO_LOGIN_OTP_LENGTH"
                                               class="form-input" min="4" max="8"
                                               value="{{ old('TYRO_LOGIN_OTP_LENGTH', $settings['TYRO_LOGIN_OTP_LENGTH']) }}">
                                    </div>
                                    <div class="form-group sys-settings-metric" style="margin-bottom:0;">
                                        <label for="TYRO_LOGIN_OTP_EXPIRE" class="form-label">OTP expire (min)</label>
                                        <input type="number" name="TYRO_LOGIN_OTP_EXPIRE" id="TYRO_LOGIN_OTP_EXPIRE"
                                               class="form-input" min="1" max="60"
                                               value="{{ old('TYRO_LOGIN_OTP_EXPIRE', $settings['TYRO_LOGIN_OTP_EXPIRE']) }}">
                                    </div>
                                </div>

                                <div class="sys-settings-metrics" style="margin-top:0.85rem;">
                                    <div class="form-group sys-settings-metric" style="margin-bottom:0;">
                                        <label for="TYRO_LOGIN_OTP_MAX_RESEND" class="form-label">OTP max resend</label>
                                        <input type="number" name="TYRO_LOGIN_OTP_MAX_RESEND" id="TYRO_LOGIN_OTP_MAX_RESEND"
                                               class="form-input" min="1" max="20"
                                               value="{{ old('TYRO_LOGIN_OTP_MAX_RESEND', $settings['TYRO_LOGIN_OTP_MAX_RESEND']) }}">
                                    </div>
                                    <div class="form-group sys-settings-metric" style="margin-bottom:0;">
                                        <label for="TYRO_LOGIN_MAGIC_LINK_EXPIRE" class="form-label">Magic link expire (min)</label>
                                        <input type="number" name="TYRO_LOGIN_MAGIC_LINK_EXPIRE" id="TYRO_LOGIN_MAGIC_LINK_EXPIRE"
                                               class="form-input" min="1" max="60"
                                               value="{{ old('TYRO_LOGIN_MAGIC_LINK_EXPIRE', $settings['TYRO_LOGIN_MAGIC_LINK_EXPIRE']) }}">
                                    </div>
                                </div>

                                <div class="form-group" style="margin-top:0.85rem; margin-bottom:0;">
                                    <label for="TYRO_LOGIN_EMAIL_MAGIC_LINK_SUBJECT" class="form-label">Magic link email subject</label>
                                    <input type="text" name="TYRO_LOGIN_EMAIL_MAGIC_LINK_SUBJECT" id="TYRO_LOGIN_EMAIL_MAGIC_LINK_SUBJECT"
                                           class="form-input" maxlength="255"
                                           value="{{ old('TYRO_LOGIN_EMAIL_MAGIC_LINK_SUBJECT', $settings['TYRO_LOGIN_EMAIL_MAGIC_LINK_SUBJECT']) }}">
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

                                <div class="sys-settings-metrics" style="margin-bottom:0;">
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

        {{-- Sidebar & Admin Bar Tab --}}
        @php
            $sbBg   = old('TYRO_DASHBOARD_SIDEBAR_BG', $settings['TYRO_DASHBOARD_SIDEBAR_BG'] ?: '#0e0e0e');
            $sbText = old('TYRO_DASHBOARD_SIDEBAR_TEXT', $settings['TYRO_DASHBOARD_SIDEBAR_TEXT'] ?: '#f8fafc');
            $sbPrimary = old('TYRO_DASHBOARD_SIDEBAR_PRIMARY', $settings['TYRO_DASHBOARD_SIDEBAR_PRIMARY'] ?: '#333333');
            $sbAccent = old('TYRO_DASHBOARD_SIDEBAR_ACCENT', $settings['TYRO_DASHBOARD_SIDEBAR_ACCENT'] ?: '#f5f5f5');
            $sbAccentFg = old('TYRO_DASHBOARD_SIDEBAR_ACCENT_FOREGROUND', $settings['TYRO_DASHBOARD_SIDEBAR_ACCENT_FOREGROUND'] ?: '#171717');
            $sbHeaderBorder = old('TYRO_DASHBOARD_SIDEBAR_HEADER_BORDER', $settings['TYRO_DASHBOARD_SIDEBAR_HEADER_BORDER'] ?: '#333c56');
            $sbAccordionCompact = filter_var(old('TYRO_DASHBOARD_SIDEBAR_ACCORDION_COMPACT', $settings['TYRO_DASHBOARD_SIDEBAR_ACCORDION_COMPACT'] ?? false), FILTER_VALIDATE_BOOLEAN);
            $abBg = old('TYRO_DASHBOARD_ADMIN_BAR_BG_COLOR', $settings['TYRO_DASHBOARD_ADMIN_BAR_BG_COLOR'] ?: '#000000');
            $abText = old('TYRO_DASHBOARD_ADMIN_BAR_TEXT_COLOR', $settings['TYRO_DASHBOARD_ADMIN_BAR_TEXT_COLOR'] ?: '#ffffff');
            $abMessage = old('TYRO_DASHBOARD_ADMIN_BAR_MESSAGE', $settings['TYRO_DASHBOARD_ADMIN_BAR_MESSAGE'] ?: '');
            $abAlign = old('TYRO_DASHBOARD_ADMIN_BAR_ALIGN', $settings['TYRO_DASHBOARD_ADMIN_BAR_ALIGN'] ?: 'left');
            $abHeight = old('TYRO_DASHBOARD_ADMIN_BAR_HEIGHT', $settings['TYRO_DASHBOARD_ADMIN_BAR_HEIGHT'] ?: '40px');
        @endphp
        <div class="vtabs-panel" id="vtab-sidebar-admin-bar">
            <div class="card">
                <div class="card-header" style="display:flex;align-items:center;justify-content:space-between;gap:1rem;">
                    <h3 class="card-title">Sidebar &amp; Admin Bar</h3>
                    <button type="submit" form="systemSettingsForm" class="btn btn-primary btn-sm section-save-button">Save</button>
                </div>
                <div class="card-body">
                    <div class="sys-settings-section-intro">
                        <div class="sys-settings-section-copy">
                            <h4 class="sys-settings-section-heading">Customize the sidebar and admin bar appearance</h4>
                            <p class="sys-settings-section-description">Pick colors for the dashboard sidebar and configure the admin notice bar. Values are stored in <code>.env</code> and take effect after saving.</p>
                        </div>
                        <span class="sys-settings-section-badge">Colors</span>
                    </div>

                    {{-- Sidebar Colors --}}
                    <div class="sys-settings-surface" style="margin-bottom:1.25rem;">
                        <div style="display:flex;align-items:center;justify-content:space-between;gap:0.75rem;margin-bottom:0.5rem;">
                            <h4 class="sys-settings-surface-title" style="margin:0;">Sidebar Colors</h4>
                            <button type="button" onclick="confirmResetSbColors()" class="btn btn-secondary btn-sm">Reset to Default</button>
                        </div>
                        <p class="sys-settings-surface-description">Background, text, highlight, hover, and separator colors for the dashboard sidebar.</p>

                        <div class="sys-settings-toggles" style="margin-bottom:1rem;">
                            <div class="sys-settings-toggle">
                                <div class="sys-settings-toggle-top">
                                    <div>
                                        <p class="sys-settings-toggle-title">Accordion style sidebar sections</p>
                                        <p class="sys-settings-toggle-description">When enabled, sidebar sections are collapsible. Writes <code>TYRO_DASHBOARD_SIDEBAR_ACCORDION_COMPACT</code>.</p>
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

                    {{-- Admin Bar Colors --}}
                    <div class="sys-settings-surface">
                        <div style="display:flex;align-items:center;justify-content:space-between;gap:0.75rem;margin-bottom:0.5rem;">
                            <h4 class="sys-settings-surface-title" style="margin:0;">Admin Bar Colors</h4>
                            <button type="button" onclick="confirmResetAbColors()" class="btn btn-secondary btn-sm">Reset to Default</button>
                        </div>
                        <p class="sys-settings-surface-description">The admin notice bar sits at the top of the dashboard. Control its visibility, colors, message, alignment, and height.</p>

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

function confirmResetSbColors() {
    showConfirm(
        'Reset sidebar colours?',
        'This will revert the sidebar to its default colours.',
        { confirmText: 'Reset to Default' }
    ).then(function(confirmed) {
        if (confirmed) resetSbColors();
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
    showConfirm(
        'Reset admin bar colours?',
        'This will revert the admin bar to its default colours.',
        { confirmText: 'Reset to Default' }
    ).then(function(confirmed) {
        if (confirmed) resetAbColors();
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
                return r.json().then(function(d) { showToast(d.message, 'success'); });
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
</script>
@endpush
