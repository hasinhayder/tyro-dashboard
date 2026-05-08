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
                                        <p class="sys-settings-toggle-title">Enable admin bar <span style="font-weight:normal">(<code>TYRO_DASHBOARD_ADMIN_BAR_ENABLED</code>)</span></p>
                                                <p class="sys-settings-toggle-description">Shows a configurable notice bar at the top of the dashboard for announcements and alerts.</p>
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
                            <label for="TYRO_DASHBOARD_ADMIN_BAR_MESSAGE" class="form-label">Bar message (TYRO_DASHBOARD_ADMIN_BAR_MESSAGE)</label>
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
                                <label for="TYRO_DASHBOARD_ADMIN_BAR_ALIGN" class="form-label">Text alignment (TYRO_DASHBOARD_ADMIN_BAR_ALIGN)</label>
                                <select name="TYRO_DASHBOARD_ADMIN_BAR_ALIGN" id="ab_align" class="form-select" onchange="updateAbPreview()">
                                    <option value="left" {{ $abAlign === 'left' ? 'selected' : '' }}>Left</option>
                                    <option value="center" {{ $abAlign === 'center' ? 'selected' : '' }}>Center</option>
                                    <option value="right" {{ $abAlign === 'right' ? 'selected' : '' }}>Right</option>
                                </select>
                            </div>
                            <div class="form-group sys-settings-metric" style="margin-bottom:0;">
                                <label for="TYRO_DASHBOARD_ADMIN_BAR_HEIGHT" class="form-label">Bar height (TYRO_DASHBOARD_ADMIN_BAR_HEIGHT)</label>
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
