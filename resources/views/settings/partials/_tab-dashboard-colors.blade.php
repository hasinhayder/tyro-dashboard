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
