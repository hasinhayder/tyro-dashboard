        {{-- Sidebar Colors Tab --}}
        @php
            $sbBg   = old('TYRO_DASHBOARD_SIDEBAR_BG', $settings['TYRO_DASHBOARD_SIDEBAR_BG'] ?? '#0e0e0e');
            $sbText = old('TYRO_DASHBOARD_SIDEBAR_TEXT', $settings['TYRO_DASHBOARD_SIDEBAR_TEXT'] ?? '#f8fafc');
            $sbPrimary = old('TYRO_DASHBOARD_SIDEBAR_PRIMARY', $settings['TYRO_DASHBOARD_SIDEBAR_PRIMARY'] ?? '#333333');
            $sbAccent = old('TYRO_DASHBOARD_SIDEBAR_ACCENT', $settings['TYRO_DASHBOARD_SIDEBAR_ACCENT'] ?? '#f5f5f5');
            $sbAccentFg = old('TYRO_DASHBOARD_SIDEBAR_ACCENT_FOREGROUND', $settings['TYRO_DASHBOARD_SIDEBAR_ACCENT_FOREGROUND'] ?? '#171717');
            $sbHeaderBorder = old('TYRO_DASHBOARD_SIDEBAR_HEADER_BORDER', $settings['TYRO_DASHBOARD_SIDEBAR_HEADER_BORDER'] ?? '#333c56');
            $sbAccordionCompact = filter_var(old('TYRO_DASHBOARD_SIDEBAR_ACCORDION_COMPACT', $settings['TYRO_DASHBOARD_SIDEBAR_ACCORDION_COMPACT'] ?? false), FILTER_VALIDATE_BOOLEAN);
            $sbOpenSections = old('TYRO_DASHBOARD_SIDEBAR_ACCORDION_OPEN_SECTIONS', $settings['TYRO_DASHBOARD_SIDEBAR_ACCORDION_OPEN_SECTIONS'] ?? 1);
            $sbLogo = old('TYRO_DASHBOARD_SIDEBAR_LOGO', $settings['TYRO_DASHBOARD_SIDEBAR_LOGO'] ?? null);
            $sbLogoShape = old('TYRO_DASHBOARD_SIDEBAR_LOGO_SHAPE', $settings['TYRO_DASHBOARD_SIDEBAR_LOGO_SHAPE'] ?? 'circle');
            $sbLogoShape = $sbLogoShape === 'rectangle' ? 'rectangle' : 'circle';
            $sbLogoRadius = old('TYRO_DASHBOARD_SIDEBAR_LOGO_BORDER_RADIUS', $settings['TYRO_DASHBOARD_SIDEBAR_LOGO_BORDER_RADIUS'] ?? '0');
            $sbLogoRadius = is_string($sbLogoRadius) && trim($sbLogoRadius) !== '' ? trim($sbLogoRadius) : '0';
            $sbLogoUrl = null;
            if ($sbLogo) {
                $sbLogoUrl = str_starts_with($sbLogo, 'http://') || str_starts_with($sbLogo, 'https://')
                    ? $sbLogo
                    : \Illuminate\Support\Facades\Storage::url($sbLogo);
            }
        @endphp
        <div class="vtabs-panel" id="vtab-sidebar-colors">
            <div class="card">
                <div class="card-header" style="display:flex;align-items:center;justify-content:space-between;gap:1rem;">
                    <h3 class="card-title">Sidebar Options</h3>
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
                                        <p class="sys-settings-toggle-title">Accordion style sidebar sections <span style="font-weight:normal">(<code>TYRO_DASHBOARD_SIDEBAR_ACCORDION_COMPACT</code>)</span></p>
                                        <p class="sys-settings-toggle-description">When enabled, sidebar sections become collapsible accordions. The Home &amp; Essentials section stays open while other sections can be collapsed.</p>
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

                        <div class="form-group" style="margin-top:1rem;">
                            <label for="sb_open_sections" class="form-label">Default open sections (TYRO_DASHBOARD_SIDEBAR_ACCORDION_OPEN_SECTIONS)</label>
                            <input type="number" name="TYRO_DASHBOARD_SIDEBAR_ACCORDION_OPEN_SECTIONS" id="sb_open_sections"
                                   class="form-input" min="0" max="50"
                                   value="{{ old('TYRO_DASHBOARD_SIDEBAR_ACCORDION_OPEN_SECTIONS', $sbOpenSections) }}"
                                   style="max-width:120px;">
                            <p class="form-hint">Number of sidebar sections that stay open by default (0 = all collapsed). The section with an active link always stays open.</p>
                        </div>
                    </div>

                    <div class="sys-settings-surface" style="margin-bottom:1.25rem;">
                        <h4 class="sys-settings-surface-title">Sidebar Logo</h4>
                        <p class="sys-settings-surface-description">Custom logo image shown in the sidebar header. When not set, the default icon and app name are shown.</p>

                        <div class="form-group" style="margin-bottom:0;">
                            <div id="sb-logo-picker-wrap" class="{{ $sbLogoShape === 'rectangle' ? 'sb-logo-rectangle' : '' }}">
                                <x-media-picker
                                    name="TYRO_DASHBOARD_SIDEBAR_LOGO"
                                    :value="$sbLogo"
                                    preview="true"
                                    preview-position="left"
                                    preview-width="70px"
                                    preview-height="70px"
                                    :circle="$sbLogoShape === 'circle'"
                                    :radius="$sbLogoShape === 'rectangle' ? $sbLogoRadius : null"
                                    button="primary"
                                    button-text="Select Logo"
                                    width="100%"
                                    size="medium"
                                />
                            </div>
                        </div>

                        <div class="sys-settings-metrics" style="margin-top:1rem;">
                            <div class="form-group sys-settings-metric" style="margin-bottom:0;">
                                <label for="TYRO_DASHBOARD_SIDEBAR_LOGO_SHAPE" class="form-label">Logo shape (TYRO_DASHBOARD_SIDEBAR_LOGO_SHAPE)</label>
                                <select name="TYRO_DASHBOARD_SIDEBAR_LOGO_SHAPE" id="TYRO_DASHBOARD_SIDEBAR_LOGO_SHAPE" class="form-select" onchange="updateSbLogoShape()">
                                    <option value="circle" {{ $sbLogoShape === 'circle' ? 'selected' : '' }}>Circle</option>
                                    <option value="rectangle" {{ $sbLogoShape === 'rectangle' ? 'selected' : '' }}>Rectangle</option>
                                </select>
                                <p class="form-hint">Circle crops the logo to a 36&times;36 round icon. Rectangle keeps the logo's natural aspect ratio.</p>
                            </div>
                            <div class="form-group sys-settings-metric" id="sb-logo-radius-group" style="margin-bottom:0;{{ $sbLogoShape === 'rectangle' ? '' : 'display:none;' }}">
                                <label for="TYRO_DASHBOARD_SIDEBAR_LOGO_BORDER_RADIUS" class="form-label">Corner radius (TYRO_DASHBOARD_SIDEBAR_LOGO_BORDER_RADIUS)</label>
                                <input type="text" name="TYRO_DASHBOARD_SIDEBAR_LOGO_BORDER_RADIUS" id="TYRO_DASHBOARD_SIDEBAR_LOGO_BORDER_RADIUS"
                                       class="form-input" maxlength="20"
                                       value="{{ $sbLogoRadius }}" oninput="updateSbLogoShape()">
                                <p class="form-hint">CSS value e.g. <code>8px</code>, <code>50%</code>. Use <code>0</code> for sharp corners.</p>
                            </div>
                        </div>
                    </div>

                    <div class="sys-settings-surface">
                        <h4 class="sys-settings-surface-title">Sidebar Colors</h4>
                        <p class="sys-settings-surface-description">Customize the sidebar color scheme.</p>

                        <div style="display:flex; gap:2rem; align-items:flex-start;">
                            <div style="flex:1; min-width:0;">
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
                            </div>

                            <div style="flex-shrink:0; position:sticky; top:1rem; align-self:flex-start;">
                                <h4 class="branding-surface-title" style="margin:0 0 0.75rem;">Sidebar Preview</h4>
                                <div id="sidebarPreview" style="border-radius:0.75rem;overflow:hidden;border:1px solid var(--border);width:25rem;">
                                    <div data-sb-preview-header style="padding:1rem 1.25rem;display:flex;align-items:center;gap:0.75rem;background:{{ $sbBg }};border-bottom:1px solid {{ $sbHeaderBorder }};">
                                        <img data-sb-preview-logo alt="" @if($sbLogoUrl) src="{{ $sbLogoUrl }}" @endif
                                             style="width:24px;height:24px;object-fit:{{ $sbLogoShape === 'rectangle' ? 'contain' : 'cover' }};border-radius:{{ $sbLogoShape === 'rectangle' ? $sbLogoRadius : '50%' }};{{ $sbLogoUrl ? '' : 'display:none;' }}">
                                        <div data-sb-preview-icon style="width:24px;height:24px;border-radius:6px;background:{{ $sbText }};opacity:0.85;display:{{ $sbLogoUrl ? 'none' : 'flex' }};align-items:center;justify-content:center;">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="{{ $sbBg }}" stroke-width="2" style="width:14px;height:14px;"><rect x="3" y="3" width="18" height="18" rx="2"/></svg>
                                        </div>
                                        <span data-sb-preview-title style="font-size:0.94rem;font-weight:600;color:{{ $sbText }};">Dashboard</span>
                                    </div>
                                    <div data-sb-preview-body style="padding:0.5rem 0.75rem 0.75rem;display:flex;flex-direction:column;gap:4px;background:{{ $sbBg }};">
                                        <div data-sb-preview-item style="padding:0.4rem 0.6rem;border-radius:6px;font-size:0.82rem;font-weight:500;color:{{ $sbText }};opacity:0.7;">Users</div>
                                        <div data-sb-preview-item style="padding:0.4rem 0.6rem;border-radius:6px;font-size:0.82rem;font-weight:500;background:{{ $sbPrimary }};color:{{ $sbText }};">Settings</div>
                                        <div data-sb-preview-item style="padding:0.4rem 0.6rem;border-radius:6px;font-size:0.82rem;font-weight:500;background:{{ $sbAccent }};color:{{ $sbAccentFg }};">System</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
