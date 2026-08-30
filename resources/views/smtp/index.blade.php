@extends('tyro-dashboard::layouts.admin')

@section('title', 'SMTP Settings')

@section('breadcrumb')
<a href="{{ route($dashboardRoute::name('index')) }}">Dashboard</a>
<span class="breadcrumb-separator">/</span>
<span>SMTP Settings</span>
@endsection

@push('styles')
    @once
        @include('tyro-dashboard::smtp._styles')
    @endonce
@endpush

@section('content')
<div class="page-header">
    <div class="page-header-row">
        <div>
            <h1 class="page-title">SMTP Settings</h1>
            <p class="page-description">Configure outgoing mail and manage reusable SMTP presets. Changes are written to <code>.env</code>.</p>
        </div>
        <div style="display:flex;gap:0.5rem;flex-wrap:wrap;">
            <button type="button" class="btn btn-secondary" onclick="openUsePresetModal()">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                Use Presets
            </button>
            <button type="button" class="btn btn-secondary" onclick="openPresetModal()">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                Add Preset
            </button>
            <button type="button" class="btn btn-primary" id="smtpSaveBtn" onclick="saveSmtp()">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 21H5a2 2 0 01-2-2V5a2 2 0 012-2h11l5 5v11a2 2 0 01-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                Save SMTP
            </button>
        </div>
    </div>
</div>

<div class="stats-grid smtp-stats-grid" style="margin-bottom:1rem;">
    <div class="stat-card">
        <div class="stat-card-left">
            <div class="stat-icon stat-icon-primary">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
            </div>
            <div>
                <div class="stat-label">Active Mailer</div>
                <div class="stat-value">{{ $current['MAIL_MAILER'] ?: '—' }}</div>
            </div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-card-left">
            <div class="stat-icon stat-icon-info">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14M5 12a2 2 0 01-2-2V7a2 2 0 012-2h14a2 2 0 012 2v3a2 2 0 01-2 2M5 12a2 2 0 00-2 2v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 00-2-2"/></svg>
            </div>
            <div>
                <div class="stat-label">Host</div>
                <div class="stat-value" style="font-size:0.9rem;">{{ $current['MAIL_HOST'] ?: '—' }}{{ $current['MAIL_PORT'] ? ':'.$current['MAIL_PORT'] : '' }}</div>
            </div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-card-left">
            <div class="stat-icon stat-icon-success">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
            </div>
            <div>
                <div class="stat-label">Presets</div>
                <div class="stat-value">{{ $presets->count() }}</div>
            </div>
        </div>
    </div>
</div>

<div class="card" style="margin-bottom:1rem;">
    <div class="card-header">
        <h3 class="card-title">Current SMTP Configuration</h3>
        <span class="badge badge-secondary">.env</span>
    </div>
    <div class="card-body">
        <div class="smtp-form-grid">
            <div class="form-group">
                <label class="form-label" for="MAIL_MAILER">Mailer</label>
                <select id="MAIL_MAILER" class="form-select">
                    <option value="smtp" {{ ($current['MAIL_MAILER'] ?? '') === 'smtp' ? 'selected' : '' }}>smtp</option>
                    <option value="sendmail" {{ ($current['MAIL_MAILER'] ?? '') === 'sendmail' ? 'selected' : '' }}>sendmail</option>
                    <option value="log" {{ ($current['MAIL_MAILER'] ?? '') === 'log' ? 'selected' : '' }}>log</option>
                    <option value="array" {{ ($current['MAIL_MAILER'] ?? '') === 'array' ? 'selected' : '' }}>array</option>
                    <option value="ses" {{ ($current['MAIL_MAILER'] ?? '') === 'ses' ? 'selected' : '' }}>ses</option>
                    <option value="mailgun" {{ ($current['MAIL_MAILER'] ?? '') === 'mailgun' ? 'selected' : '' }}>mailgun</option>
                    <option value="postmark" {{ ($current['MAIL_MAILER'] ?? '') === 'postmark' ? 'selected' : '' }}>postmark</option>
                    <option value="resend" {{ ($current['MAIL_MAILER'] ?? '') === 'resend' ? 'selected' : '' }}>resend</option>
                </select>
            </div>
            <div class="form-group">
                <label class="form-label" for="MAIL_HOST">Host</label>
                <input type="text" id="MAIL_HOST" class="form-input" placeholder="smtp.mailtrap.io" value="{{ $current['MAIL_HOST'] }}">
            </div>
            <div class="form-group">
                <label class="form-label" for="MAIL_PORT">Port</label>
                <input type="number" id="MAIL_PORT" class="form-input" placeholder="587" min="1" max="65535" value="{{ $current['MAIL_PORT'] }}">
            </div>
            <div class="form-group">
                <label class="form-label" for="MAIL_SCHEME">Encryption</label>
                <select id="MAIL_SCHEME" class="form-select">
                    <option value="" {{ empty($current['MAIL_SCHEME']) ? 'selected' : '' }}>None</option>
                    <option value="smtp" {{ ($current['MAIL_SCHEME'] ?? '') === 'smtp' ? 'selected' : '' }}>TLS</option>
                    <option value="smtps" {{ ($current['MAIL_SCHEME'] ?? '') === 'smtps' ? 'selected' : '' }}>SSL</option>
                </select>
            </div>
            <div class="form-group">
                <label class="form-label" for="MAIL_USERNAME">Username</label>
                <input type="text" id="MAIL_USERNAME" class="form-input" placeholder="smtp username" value="{{ $current['MAIL_USERNAME'] }}">
            </div>
            <div class="form-group">
                <label class="form-label" for="MAIL_PASSWORD">Password</label>
                <input type="password" id="MAIL_PASSWORD" class="form-input" placeholder="••••••••" autocomplete="new-password">
                <p class="form-hint">Leave blank to keep the existing value.</p>
            </div>
            <div class="form-group">
                <label class="form-label" for="MAIL_FROM_ADDRESS">From Address</label>
                <input type="email" id="MAIL_FROM_ADDRESS" class="form-input" placeholder="hello@example.com" value="{{ $current['MAIL_FROM_ADDRESS'] }}">
            </div>
            <div class="form-group">
                <label class="form-label" for="MAIL_FROM_NAME">From Name</label>
                <input type="text" id="MAIL_FROM_NAME" class="form-input" placeholder="Example" value="{{ $current['MAIL_FROM_NAME'] }}">
            </div>
        </div>

        <div style="margin-top:1.25rem;padding-top:1rem;border-top:1px solid var(--border);">
            <h4 style="font-size:0.875rem;font-weight:600;margin-bottom:0.75rem;">Test Email</h4>
            <div class="smtp-test-row">
                <div class="form-group">
                    <label class="form-label" for="smtpTestTo">Send test to</label>
                    <input type="email" id="smtpTestTo" class="form-input" placeholder="you@example.com" value="{{ auth()->user()->email ?? '' }}" data-default="{{ auth()->user()->email ?? '' }}">
                </div>
                <button type="button" class="btn btn-secondary" id="smtpTestBtn" onclick="sendTestEmail()">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M22 2L11 13M22 2l-7 20-4-9-9-4 20-7z"/></svg>
                    Send Test
                </button>
            </div>
            <p class="form-hint">Saves are written to <code>.env</code> and config is cleared automatically. Run a test after saving to verify delivery.</p>
        </div>
    </div>
    <div class="card-footer" style="display:flex;justify-content:space-between;gap:0.75rem;flex-wrap:wrap;">
        <button type="button" class="btn btn-destructive btn-sm" onclick="clearSmtpCache()">Clear Config Cache</button>
        <div style="display:flex;gap:0.5rem;flex-wrap:wrap;">
            <button type="button" class="btn btn-sm smtp-btn-save-preset" onclick="saveCurrentAsPreset()">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-6-3-6 3V5z"/></svg>
                Save current settings as preset
            </button>
            <button type="button" class="btn btn-primary btn-sm" onclick="saveSmtp()">Save SMTP</button>
        </div>
    </div>
</div>

<div class="modal-overlay" id="smtpPresetModal">
    <div class="modal" style="max-width:640px;">
        <div class="modal-header">
            <h3 class="modal-title" id="presetModalTitle">Add Preset</h3>
            <button type="button" class="modal-close" onclick="closePresetModal()">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <form id="presetForm" onsubmit="return false;">
            <input type="hidden" id="presetId" value="">
            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label" for="preset_name">Preset Name *</label>
                    <input type="text" id="preset_name" class="form-input" maxlength="100" placeholder="e.g. Production Mailgun">
                </div>
                <div class="smtp-modal-grid">
                    <div class="form-group">
                        <label class="form-label" for="preset_mailer">Mailer *</label>
                        <select id="preset_mailer" class="form-select">
                            <option value="smtp">smtp</option>
                            <option value="sendmail">sendmail</option>
                            <option value="log">log</option>
                            <option value="ses">ses</option>
                            <option value="mailgun">mailgun</option>
                            <option value="postmark">postmark</option>
                            <option value="resend">resend</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="preset_host">Host *</label>
                        <input type="text" id="preset_host" class="form-input" placeholder="smtp.example.com">
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="preset_port">Port</label>
                        <input type="number" id="preset_port" class="form-input" placeholder="587" min="1" max="65535">
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="preset_encryption">Encryption</label>
                        <select id="preset_encryption" class="form-select">
                            <option value="">None</option>
                            <option value="smtp">TLS</option>
                            <option value="smtps">SSL</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="preset_username">Username</label>
                        <input type="text" id="preset_username" class="form-input" placeholder="smtp username">
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="preset_password">Password</label>
                        <input type="password" id="preset_password" class="form-input" placeholder="••••••••">
                        <p class="form-hint" id="presetPasswordHint">Stored encrypted.</p>
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="preset_from_address">From Address</label>
                        <input type="email" id="preset_from_address" class="form-input" placeholder="hello@example.com">
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="preset_from_name">From Name</label>
                        <input type="text" id="preset_from_name" class="form-input" placeholder="Example">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closePresetModal()">Cancel</button>
                <button type="button" class="btn btn-primary" id="presetSaveBtn" onclick="savePreset()">Save Preset</button>
            </div>
        </form>
    </div>
</div>

<div class="modal-overlay" id="smtpUsePresetModal">
    <div class="modal">
        <div class="modal-header">
            <h3 class="modal-title">Use Preset</h3>
            <button type="button" class="modal-close" onclick="closeUsePresetModal()">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <div class="modal-body" style="padding:0;">
            @if($presets->isEmpty())
                <div class="smtp-preset-empty" style="margin:1.25rem;">
                    <svg style="width:40px;height:40px;color:var(--muted-foreground);margin:0 auto;display:block;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                    <p>No presets yet. Save your current SMTP as a preset to switch quickly later.</p>
                    <button type="button" class="btn btn-primary btn-sm" style="margin-top:1rem;" onclick="closeUsePresetModal(); openPresetModal();">Add Preset</button>
                </div>
            @else
                <div class="table-container">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Preset</th>
                                <th>Host</th>
                                <th style="text-align:right;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($presets as $preset)
                                <tr data-preset-id="{{ $preset->id }}">
                                    <td style="font-weight:600;white-space:nowrap;">{{ $preset->name }}</td>
                                    <td style="font-size:0.8125rem;white-space:nowrap;">{{ $preset->host }}@if($preset->port)<span style="color:var(--muted-foreground);">:{{ $preset->port }}</span>@endif</td>
                                    <td style="text-align:right;white-space:nowrap;">
                                        <div style="display:inline-flex;gap:0.375rem;">
                                            <button type="button" class="btn btn-primary btn-sm" onclick="applyPreset({{ $preset->id }}, '{{ addslashes($preset->name) }}')">Use</button>
                                            <button type="button" class="btn btn-secondary btn-sm" onclick="editPreset({{ $preset->id }})">Edit</button>
                                            <button type="button" class="btn btn-destructive btn-sm" onclick="deletePreset({{ $preset->id }}, '{{ addslashes($preset->name) }}')">Delete</button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" onclick="closeUsePresetModal()">Close</button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    @once
        @include('tyro-dashboard::smtp._scripts')
    @endonce
@endpush
