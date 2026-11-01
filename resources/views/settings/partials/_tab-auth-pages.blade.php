{{-- Auth pages --}}
            <div class="vtabs-panel" id="vtab-auth-pages">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Auth pages</h3>
                    </div>
                    <div class="card-body">
                        <p class="sys-settings-section-description" style="margin-bottom:1rem;">Layout, branding and copy of the login and registration pages.</p>

                        <div class="sys-settings-grid">
                            <div class="sys-settings-surface">
                                <h4 class="sys-settings-surface-title">Layout &amp; background</h4>
                                <p class="sys-settings-surface-description">Customize the appearance of auth pages.</p>

                                <div class="form-group" style="margin-bottom:0.85rem;">
                                    <label for="TYRO_LOGIN_LAYOUT" class="form-label">Auth page layout <span class="env-key">TYRO_LOGIN_LAYOUT</span></label>
                                    <select name="TYRO_LOGIN_LAYOUT" id="TYRO_LOGIN_LAYOUT" class="form-select">
                                        <option value="centered" {{ old('TYRO_LOGIN_LAYOUT', $settings['TYRO_LOGIN_LAYOUT']) === 'centered' ? 'selected' : '' }}>Centered</option>
                                        <option value="split-left" {{ old('TYRO_LOGIN_LAYOUT', $settings['TYRO_LOGIN_LAYOUT']) === 'split-left' ? 'selected' : '' }}>Split left</option>
                                        <option value="split-right" {{ old('TYRO_LOGIN_LAYOUT', $settings['TYRO_LOGIN_LAYOUT']) === 'split-right' ? 'selected' : '' }}>Split right</option>
                                        <option value="fullscreen" {{ old('TYRO_LOGIN_LAYOUT', $settings['TYRO_LOGIN_LAYOUT']) === 'fullscreen' ? 'selected' : '' }}>Fullscreen</option>
                                        <option value="card" {{ old('TYRO_LOGIN_LAYOUT', $settings['TYRO_LOGIN_LAYOUT']) === 'card' ? 'selected' : '' }}>Card</option>
                                        <option value="youtube-video" {{ old('TYRO_LOGIN_LAYOUT', $settings['TYRO_LOGIN_LAYOUT']) === 'youtube-video' ? 'selected' : '' }}>YouTube video</option>
                                        <option value="tidal" {{ old('TYRO_LOGIN_LAYOUT', $settings['TYRO_LOGIN_LAYOUT']) === 'tidal' ? 'selected' : '' }}>Tidal</option>
                                        <option value="animated-birds" {{ old('TYRO_LOGIN_LAYOUT', $settings['TYRO_LOGIN_LAYOUT']) === 'animated-birds' ? 'selected' : '' }}>Animated birds</option>
                                        <option value="particle-network" {{ old('TYRO_LOGIN_LAYOUT', $settings['TYRO_LOGIN_LAYOUT']) === 'particle-network' ? 'selected' : '' }}>Particle network</option>
                                        <option value="aurora-waves" {{ old('TYRO_LOGIN_LAYOUT', $settings['TYRO_LOGIN_LAYOUT']) === 'aurora-waves' ? 'selected' : '' }}>Aurora waves</option>
                                    </select>
                                </div>

                                <div class="form-group" style="margin-bottom:0.85rem;">
                                    <label for="TYRO_LOGIN_APP_NAME" class="form-label">Auth app name <span class="env-key">TYRO_LOGIN_APP_NAME</span></label>
                                    <input type="text" name="TYRO_LOGIN_APP_NAME" id="TYRO_LOGIN_APP_NAME"
                                           class="form-input" maxlength="255"
                                           value="{{ old('TYRO_LOGIN_APP_NAME', $settings['TYRO_LOGIN_APP_NAME']) }}">
                                </div>

                                <div class="form-group" id="background-image-field" style="margin-bottom:0;">
                                    <label for="TYRO_LOGIN_BACKGROUND_IMAGE" class="form-label">Background image URL <span class="env-key">TYRO_LOGIN_BACKGROUND_IMAGE</span></label>
                                    <input type="url" name="TYRO_LOGIN_BACKGROUND_IMAGE" id="TYRO_LOGIN_BACKGROUND_IMAGE"
                                           class="form-input" maxlength="500"
                                           value="{{ old('TYRO_LOGIN_BACKGROUND_IMAGE', $settings['TYRO_LOGIN_BACKGROUND_IMAGE']) }}">
                                    <p class="form-hint">Used for split and fullscreen layouts.</p>
                                </div>

                                <div id="youtube-video-details-surface">
                                    <div class="form-group" style="margin-bottom:0.85rem;margin-top:0.85rem;">
                                        <label for="TYRO_LOGIN_VIDEO_URL" class="form-label">YouTube video URL or ID <span class="env-key">TYRO_LOGIN_VIDEO_URL</span></label>
                                        <input type="text" name="TYRO_LOGIN_VIDEO_URL" id="TYRO_LOGIN_VIDEO_URL"
                                               class="form-input" maxlength="500"
                                               value="{{ old('TYRO_LOGIN_VIDEO_URL', $settings['TYRO_LOGIN_VIDEO_URL']) }}">
                                        <p class="form-hint">Full YouTube URL or 11-character video ID.</p>
                                    </div>

                                    <div class="form-group" style="margin-bottom:0.85rem;">
                                        <label for="TYRO_LOGIN_VIDEO_BLUR" class="form-label">Video blur <span class="env-key">TYRO_LOGIN_VIDEO_BLUR</span></label>
                                        <input type="text" name="TYRO_LOGIN_VIDEO_BLUR" id="TYRO_LOGIN_VIDEO_BLUR"
                                               class="form-input" maxlength="20"
                                               value="{{ old('TYRO_LOGIN_VIDEO_BLUR', $settings['TYRO_LOGIN_VIDEO_BLUR']) }}">
                                        <p class="form-hint">CSS blur value applied to the background video (e.g. 4px).</p>
                                    </div>

                                    <div class="form-group" style="margin-bottom:0.85rem;">
                                        <label for="TYRO_LOGIN_VIDEO_OVERLAY_COLOR" class="form-label">Overlay color <span class="env-key">TYRO_LOGIN_VIDEO_OVERLAY_COLOR</span></label>
                                        <div style="display:flex;align-items:center;gap:0.5rem;">
                                            <input type="color" name="TYRO_LOGIN_VIDEO_OVERLAY_COLOR" id="TYRO_LOGIN_VIDEO_OVERLAY_COLOR"
                                                   value="{{ old('TYRO_LOGIN_VIDEO_OVERLAY_COLOR', $settings['TYRO_LOGIN_VIDEO_OVERLAY_COLOR']) }}"
                                                   style="width:45px;height:45px;padding:2px;border:1px solid var(--input);border-radius:6px;cursor:pointer;background:var(--background);flex-shrink:0;"
                                                    oninput="document.getElementById('TYRO_LOGIN_VIDEO_OVERLAY_COLOR_TEXT').value=this.value;toggleTyroColorReset('TYRO_LOGIN_VIDEO_OVERLAY_COLOR')">
                                            <input type="text" id="TYRO_LOGIN_VIDEO_OVERLAY_COLOR_TEXT"
                                                   value="{{ old('TYRO_LOGIN_VIDEO_OVERLAY_COLOR', $settings['TYRO_LOGIN_VIDEO_OVERLAY_COLOR']) }}"
                                                   class="form-input" maxlength="30" style="max-width:140px;height:45px;"
                                                   oninput="syncTyroColorPicker('TYRO_LOGIN_VIDEO_OVERLAY_COLOR')">
                                            <button type="button" id="TYRO_LOGIN_VIDEO_OVERLAY_COLOR_RESET" data-default="#111827"
                                                    class="branding-color-reset" title="Reset to default (#111827)"
                                                    style="display:none;flex-shrink:0;"
                                                    onclick="resetTyroColor('TYRO_LOGIN_VIDEO_OVERLAY_COLOR')">
                                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:14px;height:14px;"><path stroke-linecap="round" stroke-linejoin="round" d="M1 4v6h6M23 20v-6h-6"/><path stroke-linecap="round" stroke-linejoin="round" d="M20.49 9A9 9 0 0 0 5.64 5.64L1 10m22 4l-4.64 4.36A9 9 0 0 1 3.51 15"/></svg>
                                            </button>
                                        </div>
                                        <p class="form-hint">Hex color drawn over the video (default #111827).</p>
                                    </div>

                                    <div class="form-group" style="margin-bottom:0.85rem;">
                                        <label for="TYRO_LOGIN_VIDEO_OVERLAY_OPACITY" class="form-label">Overlay opacity <span class="env-key">TYRO_LOGIN_VIDEO_OVERLAY_OPACITY</span></label>
                                        <input type="number" step="0.1" min="0" max="1" name="TYRO_LOGIN_VIDEO_OVERLAY_OPACITY" id="TYRO_LOGIN_VIDEO_OVERLAY_OPACITY"
                                               class="form-input"
                                               value="{{ old('TYRO_LOGIN_VIDEO_OVERLAY_OPACITY', $settings['TYRO_LOGIN_VIDEO_OVERLAY_OPACITY']) }}">
                                        <p class="form-hint">Value between 0.0 (fully transparent) and 1.0 (fully opaque).</p>
                                    </div>

                                    <div class="form-group" style="margin-bottom:0;">
                                        <div class="sys-settings-toggle">
                                            <div class="sys-settings-toggle-top">
                                                <div>
                                                    <p class="sys-settings-toggle-title">Enable video sound <span class="env-key">TYRO_LOGIN_VIDEO_SOUND</span></p>
                                                    <p class="sys-settings-toggle-description">Plays the YouTube video with sound when enabled.</p>
                                                </div>
                                                <div>
                                                    <input type="hidden" name="TYRO_LOGIN_VIDEO_SOUND" value="0">
                                                    <label class="toggle-label">
                                                        <input type="checkbox" name="TYRO_LOGIN_VIDEO_SOUND" value="1" class="toggle-input" {{ old('TYRO_LOGIN_VIDEO_SOUND', $settings['TYRO_LOGIN_VIDEO_SOUND']) ? 'checked' : '' }}>
                                                        <span class="toggle-slider"></span>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div id="tidal-details-surface">
                                    <div class="form-group" style="margin-bottom:0.85rem;margin-top:0.85rem;">
                                        <label for="TYRO_LOGIN_TIDAL_COLOR" class="form-label">Primary water color <span class="env-key">TYRO_LOGIN_TIDAL_COLOR</span></label>
                                        <div style="display:flex;align-items:center;gap:0.5rem;">
                                            <input type="color" name="TYRO_LOGIN_TIDAL_COLOR" id="TYRO_LOGIN_TIDAL_COLOR"
                                                   value="{{ old('TYRO_LOGIN_TIDAL_COLOR', $settings['TYRO_LOGIN_TIDAL_COLOR']) }}"
                                                   style="width:45px;height:45px;padding:2px;border:1px solid var(--input);border-radius:6px;cursor:pointer;background:var(--background);flex-shrink:0;"
                                                   oninput="document.getElementById('TYRO_LOGIN_TIDAL_COLOR_TEXT').value=this.value;toggleTyroColorReset('TYRO_LOGIN_TIDAL_COLOR')">
                                            <input type="text" id="TYRO_LOGIN_TIDAL_COLOR_TEXT"
                                                   value="{{ old('TYRO_LOGIN_TIDAL_COLOR', $settings['TYRO_LOGIN_TIDAL_COLOR']) }}"
                                                   class="form-input" maxlength="30" style="max-width:140px;height:45px;"
                                                   oninput="syncTyroColorPicker('TYRO_LOGIN_TIDAL_COLOR')">
                                            <button type="button" id="TYRO_LOGIN_TIDAL_COLOR_RESET" data-default="#1f7a8c"
                                                    class="branding-color-reset" title="Reset to default (#1f7a8c)"
                                                    style="display:none;flex-shrink:0;"
                                                    onclick="resetTyroColor('TYRO_LOGIN_TIDAL_COLOR')">
                                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:14px;height:14px;"><path stroke-linecap="round" stroke-linejoin="round" d="M1 4v6h6M23 20v-6h-6"/><path stroke-linecap="round" stroke-linejoin="round" d="M20.49 9A9 9 0 0 0 5.64 5.64L1 10m22 4l-4.64 4.36A9 9 0 0 1 3.51 15"/></svg>
                                            </button>
                                        </div>
                                        <p class="form-hint">Drives the water and sky/bubble tint (default #1f7a8c).</p>
                                    </div>

                                    <div class="form-group" style="margin-bottom:0.85rem;">
                                        <label for="TYRO_LOGIN_TIDAL_SPEED" class="form-label">Wave speed <span class="env-key">TYRO_LOGIN_TIDAL_SPEED</span></label>
                                        <input type="number" step="0.1" min="0" name="TYRO_LOGIN_TIDAL_SPEED" id="TYRO_LOGIN_TIDAL_SPEED"
                                               class="form-input"
                                               value="{{ old('TYRO_LOGIN_TIDAL_SPEED', $settings['TYRO_LOGIN_TIDAL_SPEED']) }}">
                                        <p class="form-hint">Wave animation speed multiplier (default 1).</p>
                                    </div>

                                    <div class="form-group" style="margin-bottom:0;">
                                        <div class="sys-settings-toggle">
                                            <div class="sys-settings-toggle-top">
                                                <div>
                                                    <p class="sys-settings-toggle-title">Show rising bubbles <span class="env-key">TYRO_LOGIN_TIDAL_BUBBLES</span></p>
                                                    <p class="sys-settings-toggle-description">Renders animated bubbles rising from the water.</p>
                                                </div>
                                                <div>
                                                    <input type="hidden" name="TYRO_LOGIN_TIDAL_BUBBLES" value="0">
                                                    <label class="toggle-label">
                                                        <input type="checkbox" name="TYRO_LOGIN_TIDAL_BUBBLES" value="1" class="toggle-input" {{ old('TYRO_LOGIN_TIDAL_BUBBLES', $settings['TYRO_LOGIN_TIDAL_BUBBLES']) ? 'checked' : '' }}>
                                                        <span class="toggle-slider"></span>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div id="animated-birds-details-surface">
                                    <div class="form-group" style="margin-bottom:0;margin-top:0.85rem;">
                                        <label for="TYRO_LOGIN_BIRDS_COLOR" class="form-label">Sky color <span class="env-key">TYRO_LOGIN_BIRDS_COLOR</span></label>
                                        <div style="display:flex;align-items:center;gap:0.5rem;">
                                            <input type="color" name="TYRO_LOGIN_BIRDS_COLOR" id="TYRO_LOGIN_BIRDS_COLOR"
                                                   value="{{ old('TYRO_LOGIN_BIRDS_COLOR', $settings['TYRO_LOGIN_BIRDS_COLOR']) }}"
                                                   style="width:45px;height:45px;padding:2px;border:1px solid var(--input);border-radius:6px;cursor:pointer;background:var(--background);flex-shrink:0;"
                                                   oninput="document.getElementById('TYRO_LOGIN_BIRDS_COLOR_TEXT').value=this.value;toggleTyroColorReset('TYRO_LOGIN_BIRDS_COLOR')">
                                            <input type="text" id="TYRO_LOGIN_BIRDS_COLOR_TEXT"
                                                   value="{{ old('TYRO_LOGIN_BIRDS_COLOR', $settings['TYRO_LOGIN_BIRDS_COLOR']) }}"
                                                   class="form-input" maxlength="30" style="max-width:140px;height:45px;"
                                                   oninput="syncTyroColorPicker('TYRO_LOGIN_BIRDS_COLOR')">
                                            <button type="button" id="TYRO_LOGIN_BIRDS_COLOR_RESET" data-default="#f7f2ec"
                                                    class="branding-color-reset" title="Reset to default (#f7f2ec)"
                                                    style="display:none;flex-shrink:0;"
                                                    onclick="resetTyroColor('TYRO_LOGIN_BIRDS_COLOR')">
                                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:14px;height:14px;"><path stroke-linecap="round" stroke-linejoin="round" d="M1 4v6h6M23 20v-6h-6"/><path stroke-linecap="round" stroke-linejoin="round" d="M20.49 9A9 9 0 0 0 5.64 5.64L1 10m22 4l-4.64 4.36A9 9 0 0 1 3.51 15"/></svg>
                                            </button>
                                        </div>
                                        <p class="form-hint">Drives the sky background and frosted form tint (default #f7f2ec).</p>
                                    </div>
                                </div>

                                <div id="aurora-waves-details-surface">
                                    <div class="form-group" style="margin-bottom:0.85rem;margin-top:0.85rem;">
                                        <label for="TYRO_LOGIN_AURORA_COLOR" class="form-label">Base color <span class="env-key">TYRO_LOGIN_AURORA_COLOR</span></label>
                                        <div style="display:flex;align-items:center;gap:0.5rem;">
                                            <input type="color" name="TYRO_LOGIN_AURORA_COLOR" id="TYRO_LOGIN_AURORA_COLOR"
                                                   value="{{ old('TYRO_LOGIN_AURORA_COLOR', $settings['TYRO_LOGIN_AURORA_COLOR']) }}"
                                                   style="width:45px;height:45px;padding:2px;border:1px solid var(--input);border-radius:6px;cursor:pointer;background:var(--background);flex-shrink:0;"
                                                   oninput="document.getElementById('TYRO_LOGIN_AURORA_COLOR_TEXT').value=this.value;toggleTyroColorReset('TYRO_LOGIN_AURORA_COLOR')">
                                            <input type="text" id="TYRO_LOGIN_AURORA_COLOR_TEXT"
                                                   value="{{ old('TYRO_LOGIN_AURORA_COLOR', $settings['TYRO_LOGIN_AURORA_COLOR']) }}"
                                                   class="form-input" maxlength="30" style="max-width:140px;height:45px;"
                                                   oninput="syncTyroColorPicker('TYRO_LOGIN_AURORA_COLOR')">
                                            <button type="button" id="TYRO_LOGIN_AURORA_COLOR_RESET" data-default="#0b1020"
                                                    class="branding-color-reset" title="Reset to default (#0b1020)"
                                                    style="display:none;flex-shrink:0;"
                                                    onclick="resetTyroColor('TYRO_LOGIN_AURORA_COLOR')">
                                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:14px;height:14px;"><path stroke-linecap="round" stroke-linejoin="round" d="M1 4v6h6M23 20v-6h-6"/><path stroke-linecap="round" stroke-linejoin="round" d="M20.49 9A9 9 0 0 0 5.64 5.64L1 10m22 4l-4.64 4.36A9 9 0 0 1 3.51 15"/></svg>
                                            </button>
                                        </div>
                                        <p class="form-hint">Dark recommended - drives background and form tint (default #0b1020).</p>
                                    </div>

                                    <div class="form-group" style="margin-bottom:0.85rem;">
                                        <label for="TYRO_LOGIN_AURORA_SPEED" class="form-label">Animation speed <span class="env-key">TYRO_LOGIN_AURORA_SPEED</span></label>
                                        <input type="number" step="0.1" min="0" name="TYRO_LOGIN_AURORA_SPEED" id="TYRO_LOGIN_AURORA_SPEED"
                                               class="form-input"
                                               value="{{ old('TYRO_LOGIN_AURORA_SPEED', $settings['TYRO_LOGIN_AURORA_SPEED']) }}">
                                        <p class="form-hint">Animation speed multiplier (default 1).</p>
                                    </div>

                                    <div class="form-group" style="margin-bottom:0;">
                                        <label for="TYRO_LOGIN_AURORA_INTENSITY" class="form-label">Ribbon intensity <span class="env-key">TYRO_LOGIN_AURORA_INTENSITY</span></label>
                                        <input type="number" step="0.1" min="0" max="1" name="TYRO_LOGIN_AURORA_INTENSITY" id="TYRO_LOGIN_AURORA_INTENSITY"
                                               class="form-input"
                                               value="{{ old('TYRO_LOGIN_AURORA_INTENSITY', $settings['TYRO_LOGIN_AURORA_INTENSITY']) }}">
                                        <p class="form-hint">Ribbon intensity from 0 (faint) to 1 (vivid) (default 0.5).</p>
                                    </div>
                                </div>

                                <div id="particle-network-details-surface">
                                    <div class="form-group" style="margin-bottom:0.85rem;margin-top:0.85rem;">
                                        <label for="TYRO_LOGIN_PARTICLE_COLOR" class="form-label">Base color <span class="env-key">TYRO_LOGIN_PARTICLE_COLOR</span></label>
                                        <div style="display:flex;align-items:center;gap:0.5rem;">
                                            <input type="color" name="TYRO_LOGIN_PARTICLE_COLOR" id="TYRO_LOGIN_PARTICLE_COLOR"
                                                   value="{{ old('TYRO_LOGIN_PARTICLE_COLOR', $settings['TYRO_LOGIN_PARTICLE_COLOR']) }}"
                                                   style="width:45px;height:45px;padding:2px;border:1px solid var(--input);border-radius:6px;cursor:pointer;background:var(--background);flex-shrink:0;"
                                                   oninput="document.getElementById('TYRO_LOGIN_PARTICLE_COLOR_TEXT').value=this.value;toggleTyroColorReset('TYRO_LOGIN_PARTICLE_COLOR')">
                                            <input type="text" id="TYRO_LOGIN_PARTICLE_COLOR_TEXT"
                                                   value="{{ old('TYRO_LOGIN_PARTICLE_COLOR', $settings['TYRO_LOGIN_PARTICLE_COLOR']) }}"
                                                   class="form-input" maxlength="30" style="max-width:140px;height:45px;"
                                                   oninput="syncTyroColorPicker('TYRO_LOGIN_PARTICLE_COLOR')">
                                            <button type="button" id="TYRO_LOGIN_PARTICLE_COLOR_RESET" data-default="#0f172a"
                                                    class="branding-color-reset" title="Reset to default (#0f172a)"
                                                    style="display:none;flex-shrink:0;"
                                                    onclick="resetTyroColor('TYRO_LOGIN_PARTICLE_COLOR')">
                                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:14px;height:14px;"><path stroke-linecap="round" stroke-linejoin="round" d="M1 4v6h6M23 20v-6h-6"/><path stroke-linecap="round" stroke-linejoin="round" d="M20.49 9A9 9 0 0 0 5.64 5.64L1 10m22 4l-4.64 4.36A9 9 0 0 1 3.51 15"/></svg>
                                            </button>
                                        </div>
                                        <p class="form-hint">Base color for dark mode (default #0f172a).</p>
                                    </div>

                                    <div class="form-group" style="margin-bottom:0.85rem;">
                                        <label for="TYRO_LOGIN_PARTICLE_DENSITY" class="form-label">Particle density <span class="env-key">TYRO_LOGIN_PARTICLE_DENSITY</span></label>
                                        <input type="number" step="1" min="0" name="TYRO_LOGIN_PARTICLE_DENSITY" id="TYRO_LOGIN_PARTICLE_DENSITY"
                                               class="form-input"
                                               value="{{ old('TYRO_LOGIN_PARTICLE_DENSITY', $settings['TYRO_LOGIN_PARTICLE_DENSITY']) }}">
                                        <p class="form-hint">Nodes on a 1280x720 viewport, scales with area (default 80).</p>
                                    </div>

                                    <div class="form-group" style="margin-bottom:0.85rem;">
                                        <label for="TYRO_LOGIN_PARTICLE_LINK_DISTANCE" class="form-label">Link distance <span class="env-key">TYRO_LOGIN_PARTICLE_LINK_DISTANCE</span></label>
                                        <input type="number" step="1" min="0" name="TYRO_LOGIN_PARTICLE_LINK_DISTANCE" id="TYRO_LOGIN_PARTICLE_LINK_DISTANCE"
                                               class="form-input"
                                               value="{{ old('TYRO_LOGIN_PARTICLE_LINK_DISTANCE', $settings['TYRO_LOGIN_PARTICLE_LINK_DISTANCE']) }}">
                                        <p class="form-hint">Max distance in pixels linking two nodes (default 130).</p>
                                    </div>

                                    <div class="form-group" style="margin-bottom:0;">
                                        <div class="sys-settings-toggle">
                                            <div class="sys-settings-toggle-top">
                                                <div>
                                                    <p class="sys-settings-toggle-title">React to mouse cursor <span class="env-key">TYRO_LOGIN_PARTICLE_INTERACTIVE</span></p>
                                                    <p class="sys-settings-toggle-description">Nodes repel and link to the cursor when enabled.</p>
                                                </div>
                                                <div>
                                                    <input type="hidden" name="TYRO_LOGIN_PARTICLE_INTERACTIVE" value="0">
                                                    <label class="toggle-label">
                                                        <input type="checkbox" name="TYRO_LOGIN_PARTICLE_INTERACTIVE" value="1" class="toggle-input" {{ old('TYRO_LOGIN_PARTICLE_INTERACTIVE', $settings['TYRO_LOGIN_PARTICLE_INTERACTIVE']) ? 'checked' : '' }}>
                                                        <span class="toggle-slider"></span>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="sys-settings-surface">
                                <h4 class="sys-settings-surface-title">Logo &amp; app name</h4>
                                <p class="sys-settings-surface-description">Custom logo and app branding for auth pages.</p>

                                <x-media-picker
                                    name="TYRO_LOGIN_LOGO"
                                    :value="old('TYRO_LOGIN_LOGO', $settings['TYRO_LOGIN_LOGO'])"
                                    preview="true"
                                    preview-position="left"
                                    preview-width="75px"
                                    preview-height="75px"
                                    circle="true"
                                    button="primary"
                                    button-text="Choose Logo"
                                    label="Logo URL"
                                    width="100%"
                                    full-url="true"
                                />
                                <p class="form-hint" style="margin-top:-0.5rem;margin-bottom:0.85rem;">Custom logo shown on auth pages.</p>

                                <x-media-picker
                                    name="TYRO_LOGIN_LOGO_DARK"
                                    :value="old('TYRO_LOGIN_LOGO_DARK', $settings['TYRO_LOGIN_LOGO_DARK'])"
                                    preview="true"
                                    preview-position="left"
                                    preview-width="75px"
                                    preview-height="75px"
                                    circle="true"
                                    button="primary"
                                    button-text="Choose Logo"
                                    label="Logo URL (dark mode)"
                                    width="100%"
                                    full-url="true"
                                />
                                <!-- <p class="form-hint" style="margin-top:-0.5rem;margin-bottom:0.85rem;">Falls back to the light logo when not set.</p> -->
                                <div class="form-group" style="margin-bottom:0.85rem;">
                                    <label for="TYRO_LOGIN_LOGO_HEIGHT" class="form-label">Logo height <span class="env-key">TYRO_LOGIN_LOGO_HEIGHT</span></label>
                                    <input type="text" name="TYRO_LOGIN_LOGO_HEIGHT" id="TYRO_LOGIN_LOGO_HEIGHT" class="form-input" maxlength="20" value="{{ old('TYRO_LOGIN_LOGO_HEIGHT', $settings['TYRO_LOGIN_LOGO_HEIGHT']) }}">
                                    <p class="form-hint">CSS value e.g. <code>32px</code>, <code>3rem</code>.</p>
                                </div>

                                <div class="form-group" style="margin-bottom:0;">
                                    <label for="TYRO_LOGIN_LOGO_BORDER_RADIUS" class="form-label">Logo border radius <span class="env-key">TYRO_LOGIN_LOGO_BORDER_RADIUS</span></label>
                                    <input type="text" name="TYRO_LOGIN_LOGO_BORDER_RADIUS" id="TYRO_LOGIN_LOGO_BORDER_RADIUS" class="form-input" maxlength="20" value="{{ old('TYRO_LOGIN_LOGO_BORDER_RADIUS', $settings['TYRO_LOGIN_LOGO_BORDER_RADIUS']) }}">
                                    <p class="form-hint">CSS value e.g. <code>8px</code>, <code>50%</code>. Use <code>0</code> for square.</p>
                                </div>
                            </div>

                            <div class="sys-settings-surface">
                                <h4 class="sys-settings-surface-title">Page content</h4>
                                <p class="sys-settings-surface-description">Customise titles, subtitles, and background text for auth pages.</p>

                                <h5 style="margin:0 0 0.6rem;font-size:0.88rem;font-weight:700;color:var(--foreground);">Login Page</h5>
                                <div class="form-group" style="margin-bottom:0.85rem;">
                                    <label for="TYRO_LOGIN_BG_TITLE" class="form-label">Background title <span class="env-key">TYRO_LOGIN_BG_TITLE</span></label>
                                    <input type="text" name="TYRO_LOGIN_BG_TITLE" id="TYRO_LOGIN_BG_TITLE" class="form-input" maxlength="255" value="{{ old('TYRO_LOGIN_BG_TITLE', $settings['TYRO_LOGIN_BG_TITLE']) }}">
                                </div>
                                <div class="form-group" style="margin-bottom:0.85rem;">
                                    <label for="TYRO_LOGIN_BG_DESCRIPTION" class="form-label">Background description <span class="env-key">TYRO_LOGIN_BG_DESCRIPTION</span></label>
                                    <input type="text" name="TYRO_LOGIN_BG_DESCRIPTION" id="TYRO_LOGIN_BG_DESCRIPTION" class="form-input" maxlength="500" value="{{ old('TYRO_LOGIN_BG_DESCRIPTION', $settings['TYRO_LOGIN_BG_DESCRIPTION']) }}">
                                </div>

                                <h5 style="margin:0 0 0.6rem;font-size:0.88rem;font-weight:700;color:var(--foreground);">Register Page</h5>
                                <div class="form-group" style="margin-bottom:0.85rem;">
                                    <label for="TYRO_LOGIN_REGISTER_BG_TITLE" class="form-label">Background title <span class="env-key">TYRO_LOGIN_REGISTER_BG_TITLE</span></label>
                                    <input type="text" name="TYRO_LOGIN_REGISTER_BG_TITLE" id="TYRO_LOGIN_REGISTER_BG_TITLE" class="form-input" maxlength="255" value="{{ old('TYRO_LOGIN_REGISTER_BG_TITLE', $settings['TYRO_LOGIN_REGISTER_BG_TITLE']) }}">
                                </div>
                                <div class="form-group" style="margin-bottom:0.85rem;">
                                    <label for="TYRO_LOGIN_REGISTER_BG_DESCRIPTION" class="form-label">Background description <span class="env-key">TYRO_LOGIN_REGISTER_BG_DESCRIPTION</span></label>
                                    <input type="text" name="TYRO_LOGIN_REGISTER_BG_DESCRIPTION" id="TYRO_LOGIN_REGISTER_BG_DESCRIPTION" class="form-input" maxlength="500" value="{{ old('TYRO_LOGIN_REGISTER_BG_DESCRIPTION', $settings['TYRO_LOGIN_REGISTER_BG_DESCRIPTION']) }}">
                                </div>

                                <h5 style="margin:0 0 0.6rem;font-size:0.88rem;font-weight:700;color:var(--foreground);">Verify Email Page</h5>
                                <div class="form-group" style="margin-bottom:0.85rem;">
                                    <label for="TYRO_LOGIN_VERIFY_EMAIL_TITLE" class="form-label">Page title <span class="env-key">TYRO_LOGIN_VERIFY_EMAIL_TITLE</span></label>
                                    <input type="text" name="TYRO_LOGIN_VERIFY_EMAIL_TITLE" id="TYRO_LOGIN_VERIFY_EMAIL_TITLE" class="form-input" maxlength="255" value="{{ old('TYRO_LOGIN_VERIFY_EMAIL_TITLE', $settings['TYRO_LOGIN_VERIFY_EMAIL_TITLE']) }}">
                                </div>
                                <div class="form-group" style="margin-bottom:0.85rem;">
                                    <label for="TYRO_LOGIN_VERIFY_EMAIL_SUBTITLE" class="form-label">Page subtitle <span class="env-key">TYRO_LOGIN_VERIFY_EMAIL_SUBTITLE</span></label>
                                    <input type="text" name="TYRO_LOGIN_VERIFY_EMAIL_SUBTITLE" id="TYRO_LOGIN_VERIFY_EMAIL_SUBTITLE" class="form-input" maxlength="500" value="{{ old('TYRO_LOGIN_VERIFY_EMAIL_SUBTITLE', $settings['TYRO_LOGIN_VERIFY_EMAIL_SUBTITLE']) }}">
                                </div>
                                <div class="form-group" style="margin-bottom:0.85rem;">
                                    <label for="TYRO_LOGIN_VERIFY_EMAIL_BG_TITLE" class="form-label">Background title <span class="env-key">TYRO_LOGIN_VERIFY_EMAIL_BG_TITLE</span></label>
                                    <input type="text" name="TYRO_LOGIN_VERIFY_EMAIL_BG_TITLE" id="TYRO_LOGIN_VERIFY_EMAIL_BG_TITLE" class="form-input" maxlength="255" value="{{ old('TYRO_LOGIN_VERIFY_EMAIL_BG_TITLE', $settings['TYRO_LOGIN_VERIFY_EMAIL_BG_TITLE']) }}">
                                </div>
                                <div class="form-group" style="margin-bottom:0.85rem;">
                                    <label for="TYRO_LOGIN_VERIFY_EMAIL_BG_DESCRIPTION" class="form-label">Background description <span class="env-key">TYRO_LOGIN_VERIFY_EMAIL_BG_DESCRIPTION</span></label>
                                    <input type="text" name="TYRO_LOGIN_VERIFY_EMAIL_BG_DESCRIPTION" id="TYRO_LOGIN_VERIFY_EMAIL_BG_DESCRIPTION" class="form-input" maxlength="500" value="{{ old('TYRO_LOGIN_VERIFY_EMAIL_BG_DESCRIPTION', $settings['TYRO_LOGIN_VERIFY_EMAIL_BG_DESCRIPTION']) }}">
                                </div>

                                <h5 style="margin:0 0 0.6rem;font-size:0.88rem;font-weight:700;color:var(--foreground);">Forgot Password Page</h5>
                                <div class="form-group" style="margin-bottom:0.85rem;">
                                    <label for="TYRO_LOGIN_FORGOT_PASSWORD_TITLE" class="form-label">Page title <span class="env-key">TYRO_LOGIN_FORGOT_PASSWORD_TITLE</span></label>
                                    <input type="text" name="TYRO_LOGIN_FORGOT_PASSWORD_TITLE" id="TYRO_LOGIN_FORGOT_PASSWORD_TITLE" class="form-input" maxlength="255" value="{{ old('TYRO_LOGIN_FORGOT_PASSWORD_TITLE', $settings['TYRO_LOGIN_FORGOT_PASSWORD_TITLE']) }}">
                                </div>
                                <div class="form-group" style="margin-bottom:0.85rem;">
                                    <label for="TYRO_LOGIN_FORGOT_PASSWORD_SUBTITLE" class="form-label">Page subtitle <span class="env-key">TYRO_LOGIN_FORGOT_PASSWORD_SUBTITLE</span></label>
                                    <input type="text" name="TYRO_LOGIN_FORGOT_PASSWORD_SUBTITLE" id="TYRO_LOGIN_FORGOT_PASSWORD_SUBTITLE" class="form-input" maxlength="500" value="{{ old('TYRO_LOGIN_FORGOT_PASSWORD_SUBTITLE', $settings['TYRO_LOGIN_FORGOT_PASSWORD_SUBTITLE']) }}">
                                </div>
                                <div class="form-group" style="margin-bottom:0.85rem;">
                                    <label for="TYRO_LOGIN_FORGOT_PASSWORD_BG_TITLE" class="form-label">Background title <span class="env-key">TYRO_LOGIN_FORGOT_PASSWORD_BG_TITLE</span></label>
                                    <input type="text" name="TYRO_LOGIN_FORGOT_PASSWORD_BG_TITLE" id="TYRO_LOGIN_FORGOT_PASSWORD_BG_TITLE" class="form-input" maxlength="255" value="{{ old('TYRO_LOGIN_FORGOT_PASSWORD_BG_TITLE', $settings['TYRO_LOGIN_FORGOT_PASSWORD_BG_TITLE']) }}">
                                </div>
                                <div class="form-group" style="margin-bottom:0.85rem;">
                                    <label for="TYRO_LOGIN_FORGOT_PASSWORD_BG_DESCRIPTION" class="form-label">Background description <span class="env-key">TYRO_LOGIN_FORGOT_PASSWORD_BG_DESCRIPTION</span></label>
                                    <input type="text" name="TYRO_LOGIN_FORGOT_PASSWORD_BG_DESCRIPTION" id="TYRO_LOGIN_FORGOT_PASSWORD_BG_DESCRIPTION" class="form-input" maxlength="500" value="{{ old('TYRO_LOGIN_FORGOT_PASSWORD_BG_DESCRIPTION', $settings['TYRO_LOGIN_FORGOT_PASSWORD_BG_DESCRIPTION']) }}">
                                </div>

                                <h5 style="margin:0 0 0.6rem;font-size:0.88rem;font-weight:700;color:var(--foreground);">Reset Password Page</h5>
                                <div class="form-group" style="margin-bottom:0.85rem;">
                                    <label for="TYRO_LOGIN_RESET_PASSWORD_TITLE" class="form-label">Page title <span class="env-key">TYRO_LOGIN_RESET_PASSWORD_TITLE</span></label>
                                    <input type="text" name="TYRO_LOGIN_RESET_PASSWORD_TITLE" id="TYRO_LOGIN_RESET_PASSWORD_TITLE" class="form-input" maxlength="255" value="{{ old('TYRO_LOGIN_RESET_PASSWORD_TITLE', $settings['TYRO_LOGIN_RESET_PASSWORD_TITLE']) }}">
                                </div>
                                <div class="form-group" style="margin-bottom:0.85rem;">
                                    <label for="TYRO_LOGIN_RESET_PASSWORD_SUBTITLE" class="form-label">Page subtitle <span class="env-key">TYRO_LOGIN_RESET_PASSWORD_SUBTITLE</span></label>
                                    <input type="text" name="TYRO_LOGIN_RESET_PASSWORD_SUBTITLE" id="TYRO_LOGIN_RESET_PASSWORD_SUBTITLE" class="form-input" maxlength="500" value="{{ old('TYRO_LOGIN_RESET_PASSWORD_SUBTITLE', $settings['TYRO_LOGIN_RESET_PASSWORD_SUBTITLE']) }}">
                                </div>
                                <div class="form-group" style="margin-bottom:0.85rem;">
                                    <label for="TYRO_LOGIN_RESET_PASSWORD_BG_TITLE" class="form-label">Background title <span class="env-key">TYRO_LOGIN_RESET_PASSWORD_BG_TITLE</span></label>
                                    <input type="text" name="TYRO_LOGIN_RESET_PASSWORD_BG_TITLE" id="TYRO_LOGIN_RESET_PASSWORD_BG_TITLE" class="form-input" maxlength="255" value="{{ old('TYRO_LOGIN_RESET_PASSWORD_BG_TITLE', $settings['TYRO_LOGIN_RESET_PASSWORD_BG_TITLE']) }}">
                                </div>
                                <div class="form-group" style="margin-bottom:0;">
                                    <label for="TYRO_LOGIN_RESET_PASSWORD_BG_DESCRIPTION" class="form-label">Background description <span class="env-key">TYRO_LOGIN_RESET_PASSWORD_BG_DESCRIPTION</span></label>
                                    <input type="text" name="TYRO_LOGIN_RESET_PASSWORD_BG_DESCRIPTION" id="TYRO_LOGIN_RESET_PASSWORD_BG_DESCRIPTION" class="form-input" maxlength="500" value="{{ old('TYRO_LOGIN_RESET_PASSWORD_BG_DESCRIPTION', $settings['TYRO_LOGIN_RESET_PASSWORD_BG_DESCRIPTION']) }}">
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
