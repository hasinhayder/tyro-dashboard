@extends('tyro-dashboard::layouts.admin')

@section('title', 'SMTP Settings')

@section('breadcrumb')
<a href="{{ route($dashboardRoute::name('index')) }}">Dashboard</a>
<span class="breadcrumb-separator">/</span>
<span>SMTP Settings</span>
@endsection

@push('styles')
<style>
    .smtp-stats-grid .stat-card {
        display: flex !important;
        flex-direction: row !important;
        align-items: center !important;
        justify-content: space-between !important;
        gap: 1rem !important;
        padding: 1.25rem !important;
    }
    .smtp-stats-grid .stat-card-left {
        display: flex !important;
        flex-direction: row !important;
        align-items: center !important;
        gap: 1rem !important;
        flex: 1 1 auto !important;
        min-width: 0 !important;
    }
    .smtp-stats-grid .stat-icon { width: 48px; height: 48px; flex-shrink: 0; margin-bottom: 0; }
    .smtp-stats-grid .stat-icon svg { width: 24px; height: 24px; }
    .smtp-stats-grid .stat-value { font-size: 1.1rem; word-break: break-all; }
    .smtp-form-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1rem;
    }
    @media (max-width: 640px) { .smtp-form-grid { grid-template-columns: 1fr; } }
    .smtp-preset-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 1rem;
    }
    .smtp-preset-card {
        border: 1px solid var(--border);
        border-radius: 10px;
        background: var(--card);
        overflow: hidden;
        display: flex;
        flex-direction: column;
    }
    .smtp-preset-head {
        padding: 1rem 1.25rem;
        border-bottom: 1px solid var(--border);
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 0.75rem;
    }
    .smtp-preset-name { font-weight: 600; font-size: 0.9375rem; color: var(--foreground); }
    .smtp-preset-meta {
        padding: 1rem 1.25rem;
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
        flex: 1;
    }
    .smtp-preset-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 0.75rem;
        font-size: 0.8125rem;
    }
    .smtp-preset-row span:first-child { color: var(--muted-foreground); }
    .smtp-preset-row span:last-child { color: var(--foreground); font-weight: 500; word-break: break-all; text-align: right; }
    .smtp-preset-actions {
        padding: 0.75rem 1.25rem;
        border-top: 1px solid var(--border);
        display: flex;
        align-items: center;
        gap: 0.5rem;
        flex-wrap: wrap;
    }
    .smtp-preset-actions .btn { flex: 1; }
    .smtp-preset-empty {
        text-align: center;
        padding: 2.5rem 1.5rem;
        border: 1px dashed var(--border);
        border-radius: 10px;
        background: var(--muted);
    }
    .smtp-preset-empty p { color: var(--muted-foreground); font-size: 0.875rem; margin-top: 0.5rem; }
    .smtp-modal-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1rem;
    }
    @media (max-width: 640px) { .smtp-modal-grid { grid-template-columns: 1fr; } }
    .smtp-test-row {
        display: flex;
        align-items: end;
        gap: 0.75rem;
        flex-wrap: wrap;
    }
    .smtp-test-row .form-group { flex: 1; min-width: 200px; margin-bottom: 0; }
    #smtpUsePresetModal .modal { max-width: 760px; width: 96%; }
    #smtpUsePresetModal .modal-body { max-height: 60vh; overflow-y: auto; }
    .smtp-btn-save-preset {
        background-color: color-mix(in srgb, var(--info), transparent 88%);
        color: var(--info);
        border: 1px solid color-mix(in srgb, var(--info), transparent 70%);
    }
    .smtp-btn-save-preset:hover {
        background-color: color-mix(in srgb, var(--info), transparent 82%);
        border-color: color-mix(in srgb, var(--info), transparent 55%);
        color: var(--info);
    }
</style>
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
                    <option value="tls" {{ ($current['MAIL_SCHEME'] ?? '') === 'tls' ? 'selected' : '' }}>TLS</option>
                    <option value="ssl" {{ ($current['MAIL_SCHEME'] ?? '') === 'ssl' ? 'selected' : '' }}>SSL</option>
                </select>
            </div>
            <div class="form-group">
                <label class="form-label" for="MAIL_USERNAME">Username</label>
                <input type="text" id="MAIL_USERNAME" class="form-input" placeholder="smtp username" value="{{ $current['MAIL_USERNAME'] }}">
            </div>
            <div class="form-group">
                <label class="form-label" for="MAIL_PASSWORD">Password</label>
                <input type="password" id="MAIL_PASSWORD" class="form-input" placeholder="••••••••" value="{{ $current['MAIL_PASSWORD'] }}">
                <p class="form-hint">Leave blank to keep existing value when updating via presets.</p>
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
                    <input type="email" id="smtpTestTo" class="form-input" placeholder="you@example.com" value="{{ auth()->user()->email ?? '' }}">
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
                            <option value="tls">TLS</option>
                            <option value="ssl">SSL</option>
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
<script>
(function(){
    var csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
    var routes = {
        update: @json(route($dashboardRoute::name('settings.smtp.update'))),
        clearCache: @json(route($dashboardRoute::name('settings.smtp.clear-config-cache'))),
        test: @json(route($dashboardRoute::name('settings.smtp.test'))),
        presetStore: @json(route($dashboardRoute::name('settings.smtp.presets.store'))),
        presetUpdate: function(id){ return @json(route($dashboardRoute::name('settings.smtp.presets.update'), ['id' => ':id'])).replace(':id', id); },
        presetDestroy: function(id){ return @json(route($dashboardRoute::name('settings.smtp.presets.destroy'), ['id' => ':id'])).replace(':id', id); },
        presetApply: function(id){ return @json(route($dashboardRoute::name('settings.smtp.presets.apply'), ['id' => ':id'])).replace(':id', id); },
    };
    var presetsData = @json($presets->keyBy('id'));

    function headers(){ return { 'X-CSRF-TOKEN': csrf, 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json', 'Content-Type': 'application/json' }; }
    function setBusy(btn,busy){ if(!btn) return; btn.disabled=busy; btn.style.opacity=busy?'0.6':''; btn.style.cursor=busy?'wait':''; }

    window.saveSmtp = function(){
        var btn=document.getElementById('smtpSaveBtn'); setBusy(btn,true);
        fetch(routes.update,{method:'POST',headers:headers(),body:JSON.stringify({
            MAIL_MAILER: document.getElementById('MAIL_MAILER').value,
            MAIL_HOST: document.getElementById('MAIL_HOST').value,
            MAIL_PORT: document.getElementById('MAIL_PORT').value ? parseInt(document.getElementById('MAIL_PORT').value,10) : null,
            MAIL_SCHEME: document.getElementById('MAIL_SCHEME').value || null,
            MAIL_USERNAME: document.getElementById('MAIL_USERNAME').value || null,
            MAIL_PASSWORD: document.getElementById('MAIL_PASSWORD').value || null,
            MAIL_FROM_ADDRESS: document.getElementById('MAIL_FROM_ADDRESS').value || null,
            MAIL_FROM_NAME: document.getElementById('MAIL_FROM_NAME').value || null
        })})
        .then(function(r){return r.json().then(function(d){return {ok:r.ok,d:d};});})
        .then(function(res){
            if(res.ok && res.d.success){ showToast(res.d.message,'success'); setTimeout(function(){location.reload();},800); }
            else {
                var msg=res.d.message||'Failed to save.';
                if(res.d.errors){ msg=Object.values(res.d.errors).flat().join(' '); }
                showToast(msg,'error');
            }
        }).catch(function(){showToast('Network error.','error');}).finally(function(){setBusy(btn,false);});
    };

    window.clearSmtpCache = function(){
        showDanger('Clear Config Cache','Clear the config cache? This runs config:clear and reloads settings from .env.',{confirmText:'Clear Cache'})
        .then(function(ok){
            if(!ok) return;
            fetch(routes.clearCache,{method:'POST',headers:headers()})
            .then(function(r){return r.json();}).then(function(d){showToast(d.message,d.success?'success':'warning');})
            .catch(function(){showToast('Network error.','error');});
        });
    };

    window.sendTestEmail = function(){
        var btn=document.getElementById('smtpTestBtn');
        var to=document.getElementById('smtpTestTo').value.trim();
        if(!to){ showToast('Enter a recipient email.','warning'); return; }
        setBusy(btn,true);
        fetch(routes.test,{method:'POST',headers:headers(),body:JSON.stringify({to:to})})
        .then(function(r){return r.json().then(function(d){return {ok:r.ok,d:d};});})
        .then(function(res){
            if(res.ok && res.d.success) showToast(res.d.message,'success');
            else {
                var msg=res.d.message||'Failed to send.';
                if(res.d.errors) msg=Object.values(res.d.errors).flat().join(' ');
                showToast(msg,'error');
            }
        }).catch(function(){showToast('Network error.','error');}).finally(function(){setBusy(btn,false);});
    };

    window.openPresetModal = function(prefill){
        document.getElementById('presetId').value='';
        document.getElementById('presetModalTitle').textContent='Add Preset';
        document.getElementById('presetPasswordHint').textContent='Stored encrypted.';
        if(prefill){
            document.getElementById('preset_name').value='';
            document.getElementById('preset_mailer').value=document.getElementById('MAIL_MAILER').value;
            document.getElementById('preset_host').value=document.getElementById('MAIL_HOST').value;
            document.getElementById('preset_port').value=document.getElementById('MAIL_PORT').value;
            document.getElementById('preset_encryption').value=document.getElementById('MAIL_SCHEME').value;
            document.getElementById('preset_username').value=document.getElementById('MAIL_USERNAME').value;
            document.getElementById('preset_password').value='';
            document.getElementById('preset_from_address').value=document.getElementById('MAIL_FROM_ADDRESS').value;
            document.getElementById('preset_from_name').value=document.getElementById('MAIL_FROM_NAME').value;
        } else {
            document.getElementById('presetForm').reset();
            document.getElementById('preset_port').value='587';
            document.getElementById('preset_mailer').value='smtp';
        }
        openModal('smtpPresetModal');
    };

    window.saveCurrentAsPreset = function(){
        document.getElementById('presetId').value='';
        document.getElementById('presetModalTitle').textContent='Save current settings as preset';
        document.getElementById('presetPasswordHint').textContent='Stored encrypted. Leave blank if no password.';
        document.getElementById('preset_name').value='';
        document.getElementById('preset_mailer').value=document.getElementById('MAIL_MAILER').value;
        document.getElementById('preset_host').value=document.getElementById('MAIL_HOST').value;
        document.getElementById('preset_port').value=document.getElementById('MAIL_PORT').value;
        document.getElementById('preset_encryption').value=document.getElementById('MAIL_SCHEME').value;
        document.getElementById('preset_username').value=document.getElementById('MAIL_USERNAME').value;
        document.getElementById('preset_password').value=document.getElementById('MAIL_PASSWORD').value;
        document.getElementById('preset_from_address').value=document.getElementById('MAIL_FROM_ADDRESS').value;
        document.getElementById('preset_from_name').value=document.getElementById('MAIL_FROM_NAME').value;
        openModal('smtpPresetModal');
        setTimeout(function(){ var el=document.getElementById('preset_name'); if(el) el.focus(); }, 120);
    };

    window.closePresetModal = function(){ closeModal('smtpPresetModal'); };
    window.openUsePresetModal = function(){ openModal('smtpUsePresetModal'); };
    window.closeUsePresetModal = function(){ closeModal('smtpUsePresetModal'); };

    window.editPreset = function(id){
        var p=presetsData[id];
        if(!p){ showToast('Preset not found.','error'); return; }
        closeUsePresetModal();
        document.getElementById('presetId').value=p.id;
        document.getElementById('presetModalTitle').textContent='Edit Preset';
        document.getElementById('preset_name').value=p.name;
        document.getElementById('preset_mailer').value=p.mailer;
        document.getElementById('preset_host').value=p.host;
        document.getElementById('preset_port').value=p.port||'';
        document.getElementById('preset_encryption').value=p.encryption||'';
        document.getElementById('preset_username').value=p.username||'';
        document.getElementById('preset_password').value='';
        document.getElementById('presetPasswordHint').textContent='Leave blank to keep existing password.';
        document.getElementById('preset_from_address').value=p.from_address||'';
        document.getElementById('preset_from_name').value=p.from_name||'';
        setTimeout(function(){ openModal('smtpPresetModal'); }, 180);
    };

    window.savePreset = function(){
        var btn=document.getElementById('presetSaveBtn'); setBusy(btn,true);
        var id=document.getElementById('presetId').value;
        var payload={
            name: document.getElementById('preset_name').value.trim(),
            mailer: document.getElementById('preset_mailer').value,
            host: document.getElementById('preset_host').value.trim(),
            port: document.getElementById('preset_port').value ? parseInt(document.getElementById('preset_port').value,10) : null,
            encryption: document.getElementById('preset_encryption').value || null,
            username: document.getElementById('preset_username').value.trim() || null,
            password: document.getElementById('preset_password').value || null,
            from_address: document.getElementById('preset_from_address').value.trim() || null,
            from_name: document.getElementById('preset_from_name').value.trim() || null
        };
        var isEdit=!!id;
        if(!isEdit && !payload.password) payload.password=null;
        if(isEdit && !payload.password) delete payload.password;
        var url=isEdit ? routes.presetUpdate(id) : routes.presetStore;
        var method=isEdit ? 'PUT' : 'POST';
        fetch(url,{method:method,headers:headers(),body:JSON.stringify(payload)})
        .then(function(r){return r.json().then(function(d){return {ok:r.ok,d:d,status:r.status};});})
        .then(function(res){
            if(res.ok && res.d.success){ showToast(res.d.message,'success'); closePresetModal(); setTimeout(function(){location.reload();},700); }
            else {
                var msg=res.d.message||'Failed to save preset.';
                if(res.d.errors) msg=Object.values(res.d.errors).flat().join(' ');
                showToast(msg,'error');
            }
        }).catch(function(){showToast('Network error.','error');}).finally(function(){setBusy(btn,false);});
    };

    window.applyPreset = function(id,name){
        showConfirm('Use Preset','Apply preset "'+name+'"? This will overwrite the current SMTP settings in .env.',{confirmText:'Apply'})
        .then(function(ok){
            if(!ok) return;
            fetch(routes.presetApply(id),{method:'POST',headers:headers(),body:JSON.stringify({})})
            .then(function(r){return r.json().then(function(d){return {ok:r.ok,d:d};});})
            .then(function(res){
                if(res.ok && res.d.success){ showToast(res.d.message,'success'); closeUsePresetModal(); setTimeout(function(){location.reload();},700); }
                else {
                    var msg=res.d.message||'Failed to apply.';
                    if(res.d.errors) msg=Object.values(res.d.errors).flat().join(' ');
                    showToast(msg,'error');
                }
            }).catch(function(){showToast('Network error.','error');});
        });
    };

    window.deletePreset = function(id,name){
        showDanger('Delete Preset','Delete preset "'+name+'"? This cannot be undone.',{confirmText:'Delete'})
        .then(function(ok){
            if(!ok) return;
            fetch(routes.presetDestroy(id),{method:'DELETE',headers:headers()})
            .then(function(r){return r.json().then(function(d){return {ok:r.ok,d:d};});})
            .then(function(res){
                if(res.ok && res.d.success){ showToast(res.d.message,'success'); var el=document.querySelector('#smtpUsePresetModal [data-preset-id="'+id+'"]'); if(el) el.remove(); if(!document.querySelector('#smtpUsePresetModal [data-preset-id]')) setTimeout(function(){location.reload();},500); }
                else showToast(res.d.message||'Failed to delete.','error');
            }).catch(function(){showToast('Network error.','error');});
        });
    };

    document.addEventListener('keydown', function(e){
        if(e.key !== 'Escape') return;
        var useModal=document.getElementById('smtpUsePresetModal');
        var addModal=document.getElementById('smtpPresetModal');
        if(useModal && useModal.classList.contains('active')){ closeUsePresetModal(); e.stopPropagation(); }
        else if(addModal && addModal.classList.contains('active')){ closePresetModal(); e.stopPropagation(); }
    });
})();
</script>
@endpush
