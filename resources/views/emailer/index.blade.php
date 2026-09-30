@extends('tyro-dashboard::layouts.admin')

@section('title', 'Emailer')

@section('breadcrumb')
<a href="{{ route($dashboardRoute::name('index')) }}">Dashboard</a>
<span class="breadcrumb-separator">/</span>
<span>Emailer</span>
@endsection

@push('styles')
    @once
        @include('tyro-dashboard::emailer._styles')
    @endonce
@endpush

@section('content')
<div class="page-header">
    <div class="page-header-row">
        <div>
            <h1 class="page-title">Emailer</h1>
            <p class="page-description">Quickly compose and dispatch styled emails using pre-designed responsive templates. Outgoing jobs are dispatched to your application queue.</p>
        </div>
        <div style="display:flex;gap:0.5rem;flex-wrap:wrap;">
            @if(config('tyro-dashboard.features.smtp_settings', true))
            <a href="{{ route($dashboardRoute::name('settings.smtp.index')) }}" class="btn btn-secondary">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:16px;height:16px;">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                </svg>
                SMTP Settings
            </a>
            @endif
            <button type="button" class="btn btn-primary" onclick="openPresetModal()">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:16px;height:16px;">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z" />
                </svg>
                Change Design
            </button>
        </div>
    </div>
</div>

<div class="emailer-container">
    <!-- Main Composer Form -->
    <div class="emailer-card">
        <div class="emailer-card-header">
            <h3 class="emailer-card-title">Compose Message</h3>
        </div>
        <div class="emailer-card-body">
            <!-- Hidden design input -->
            <input type="hidden" id="emailDesignInput" value="modern">
            <input type="hidden" id="emailBody" value="">

            <!-- Recipient To -->
            <div class="emailer-field-group">
                <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:0.375rem;">
                    <label class="emailer-field-label" style="margin-bottom:0;" for="emailTo">
                        Recipient (To) <span class="req">*</span>
                    </label>
                    <button type="button" class="emailer-carbon-toggle" id="emailerCarbonToggleBtn" onclick="toggleCarbonFields()">
                        + Add CC &amp; BCC
                    </button>
                </div>
                <input type="text" id="emailTo" class="emailer-field-input" placeholder="recipient@example.com (comma-separated for multiple)">
            </div>

            <!-- Optional CC & BCC -->
            <div id="emailerCarbonFields" class="emailer-carbon-fields" style="display:none;margin-bottom:1.25rem;">
                <div>
                    <label class="emailer-field-label" for="emailCc">CC</label>
                    <input type="text" id="emailCc" class="emailer-field-input" placeholder="colleague@example.com">
                </div>
                <div>
                    <label class="emailer-field-label" for="emailBcc">BCC</label>
                    <input type="text" id="emailBcc" class="emailer-field-input" placeholder="archive@example.com">
                </div>
            </div>

            <!-- Subject -->
            <div class="emailer-field-group">
                <label class="emailer-field-label" for="emailSubject">
                    Subject <span class="req">*</span>
                </label>
                <input type="text" id="emailSubject" class="emailer-field-input" placeholder="Enter your email subject line...">
            </div>

            <!-- Rich Text Body -->
            <div class="emailer-field-group">
                <label class="emailer-field-label">
                    Message Body <span class="req">*</span>
                </label>
                <div class="emailer-quill-wrapper">
                    <div id="emailerEditor"></div>
                </div>
            </div>

            <!-- Bottom Actions -->
            <div style="margin-top: 1.5rem; pt-4; display: flex; align-items: center; justify-content: flex-end; gap: 0.75rem; border-top: 1px solid var(--border); padding-top: 1.25rem;">
                <button type="button" class="btn btn-secondary" onclick="openEmailPreview()">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:16px;height:16px;">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                    Preview Email
                </button>
                <button type="button" class="btn btn-primary" id="emailerBottomSendBtn" onclick="sendEmail()">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:16px;height:16px;">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 12L3.269 3.126A59.768 59.768 0 0121.485 12 59.77 59.77 0 013.27 20.876L5.999 12zm0 0h7.5" />
                    </svg>
                    Queue &amp; Send
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal: Choose Email Design Preset -->
<div class="modal-overlay" id="emailerPresetModal">
    <div class="modal">
        <div class="modal-header">
            <div>
                <h3 class="modal-title">Select Email Design Preset</h3>
                <p style="font-size:0.8125rem;color:var(--muted-foreground);margin:0.25rem 0 0 0;">
                    Pick an email design layout. Your selection is saved automatically for all upcoming emails.
                </p>
            </div>
            <button type="button" class="modal-close" onclick="closePresetModal()">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <div class="modal-body">
            <div class="emailer-modal-presets-grid">
                @foreach($presets as $key => $preset)
                    <div class="emailer-preset-item-card {{ $key === 'modern' ? 'is-selected' : '' }}" data-design-key="{{ $key }}" onclick="selectPresetFromModal('{{ $key }}')">
                        <div class="emailer-preset-badge-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" style="width:12px;height:12px;"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                        </div>
                        <div style="display:flex;align-items:center;gap:0.5rem;margin-bottom:0.5rem;">
                            <span style="width:10px;height:10px;border-radius:50%;background:{{ $preset['preview_accent'] }};display:inline-block;"></span>
                            <span class="emailer-preset-tag">{{ $preset['badge'] }}</span>
                        </div>
                        <h4 style="font-size:0.9375rem;font-weight:700;color:var(--foreground);margin:0 0 0.35rem 0;">{{ $preset['name'] }}</h4>
                        <p style="font-size:0.8125rem;color:var(--muted-foreground);line-height:1.45;margin:0 0 0.75rem 0;">{{ $preset['description'] }}</p>
                        <div style="display:flex;gap:0.5rem;margin-top:auto;">
                            <button type="button" class="btn btn-sm btn-secondary" style="flex:1;justify-content:center;font-size:0.75rem;" onclick="event.stopPropagation(); previewPresetFromModal('{{ $key }}')">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:13px;height:13px;">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                Preview
                            </button>
                            <button type="button" class="btn btn-sm btn-primary" style="flex:1;justify-content:center;font-size:0.75rem;" onclick="event.stopPropagation(); selectPresetFromModal('{{ $key }}')">
                                Select This Design
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" onclick="closePresetModal()">Close</button>
        </div>
    </div>
</div>

<!-- Modal: Live Email Preview -->
<div class="modal-overlay" id="emailerPreviewModal">
    <div class="modal">
        <div class="modal-header">
            <h3 class="modal-title">Live Email Preview</h3>
            <button type="button" class="modal-close" onclick="closeEmailPreview()">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <div class="modal-body" style="padding:1rem;">
            <iframe id="emailerPreviewIframe" sandbox="allow-same-origin"></iframe>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" onclick="closeEmailPreview()">Close Preview</button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    @once
        @include('tyro-dashboard::emailer._scripts')
    @endonce
@endpush
