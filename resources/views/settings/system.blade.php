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

                            <div class="sys-settings-surface">
                                <h4 class="sys-settings-surface-title">Admin Bar</h4>
                                <p class="sys-settings-surface-description">A notice bar at the top of the dashboard.</p>

                                <div class="sys-settings-toggles" style="margin-bottom:0.85rem;">
                                    <div class="sys-settings-toggle">
                                        <div class="sys-settings-toggle-top">
                                            <div>
                                                <p class="sys-settings-toggle-title">Enable admin bar</p>
                                                <p class="sys-settings-toggle-description">Writes <code>TYRO_DASHBOARD_ADMIN_BAR_ENABLED</code>.</p>
                                            </div>
                                            <div>
                                                <input type="hidden" name="TYRO_DASHBOARD_ADMIN_BAR_ENABLED" value="0">
                                                <label class="toggle-label">
                                                    <input type="checkbox" name="TYRO_DASHBOARD_ADMIN_BAR_ENABLED" value="1" class="toggle-input" {{ old('TYRO_DASHBOARD_ADMIN_BAR_ENABLED', $settings['TYRO_DASHBOARD_ADMIN_BAR_ENABLED']) ? 'checked' : '' }}>
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
                                           value="{{ old('TYRO_DASHBOARD_ADMIN_BAR_MESSAGE', $settings['TYRO_DASHBOARD_ADMIN_BAR_MESSAGE']) }}">
                                </div>

                                <div class="sys-settings-metrics">
                                    <div class="form-group sys-settings-metric" style="margin-bottom:0;">
                                        <label for="TYRO_DASHBOARD_ADMIN_BAR_BG_COLOR" class="form-label">Background color</label>
                                        <input type="text" name="TYRO_DASHBOARD_ADMIN_BAR_BG_COLOR" id="TYRO_DASHBOARD_ADMIN_BAR_BG_COLOR"
                                               class="form-input" maxlength="50"
                                               value="{{ old('TYRO_DASHBOARD_ADMIN_BAR_BG_COLOR', $settings['TYRO_DASHBOARD_ADMIN_BAR_BG_COLOR']) }}">
                                    </div>
                                    <div class="form-group sys-settings-metric" style="margin-bottom:0;">
                                        <label for="TYRO_DASHBOARD_ADMIN_BAR_TEXT_COLOR" class="form-label">Text color</label>
                                        <input type="text" name="TYRO_DASHBOARD_ADMIN_BAR_TEXT_COLOR" id="TYRO_DASHBOARD_ADMIN_BAR_TEXT_COLOR"
                                               class="form-input" maxlength="50"
                                               value="{{ old('TYRO_DASHBOARD_ADMIN_BAR_TEXT_COLOR', $settings['TYRO_DASHBOARD_ADMIN_BAR_TEXT_COLOR']) }}">
                                    </div>
                                </div>

                                <div class="sys-settings-metrics" style="margin-top:0.85rem;">
                                    <div class="form-group sys-settings-metric" style="margin-bottom:0;">
                                        <label for="TYRO_DASHBOARD_ADMIN_BAR_ALIGN" class="form-label">Alignment</label>
                                        <select name="TYRO_DASHBOARD_ADMIN_BAR_ALIGN" id="TYRO_DASHBOARD_ADMIN_BAR_ALIGN" class="form-select">
                                            <option value="left" {{ old('TYRO_DASHBOARD_ADMIN_BAR_ALIGN', $settings['TYRO_DASHBOARD_ADMIN_BAR_ALIGN']) === 'left' ? 'selected' : '' }}>Left</option>
                                            <option value="center" {{ old('TYRO_DASHBOARD_ADMIN_BAR_ALIGN', $settings['TYRO_DASHBOARD_ADMIN_BAR_ALIGN']) === 'center' ? 'selected' : '' }}>Center</option>
                                            <option value="right" {{ old('TYRO_DASHBOARD_ADMIN_BAR_ALIGN', $settings['TYRO_DASHBOARD_ADMIN_BAR_ALIGN']) === 'right' ? 'selected' : '' }}>Right</option>
                                        </select>
                                    </div>
                                    <div class="form-group sys-settings-metric" style="margin-bottom:0;">
                                        <label for="TYRO_DASHBOARD_ADMIN_BAR_HEIGHT" class="form-label">Bar height</label>
                                        <input type="text" name="TYRO_DASHBOARD_ADMIN_BAR_HEIGHT" id="TYRO_DASHBOARD_ADMIN_BAR_HEIGHT"
                                               class="form-input" maxlength="20"
                                               value="{{ old('TYRO_DASHBOARD_ADMIN_BAR_HEIGHT', $settings['TYRO_DASHBOARD_ADMIN_BAR_HEIGHT']) }}">
                                    </div>
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
