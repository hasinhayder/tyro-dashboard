        {{-- Passkeys Tab --}}
        {{-- Only rendered when passkeys are enabled AND the laravel/passkeys package is installed. --}}
        <div class="vtabs-panel" id="vtab-passkeys">
            <div class="card">
                <div class="card-header" style="display:flex;align-items:center;justify-content:space-between;gap:1rem;">
                    <h3 class="card-title">Passkeys</h3>
                    <button type="submit" form="systemSettingsForm" class="btn btn-primary btn-sm section-save-button">Save</button>
                </div>
                <div class="card-body">
                    <div class="sys-settings-section-intro">
                        <div class="sys-settings-section-copy">
                            <h4 class="sys-settings-section-heading">Passwordless WebAuthn sign-in</h4>
                            <p class="sys-settings-section-description">Manage the Tyro Login passkeys feature: enable/disable it and customize the labels, page titles, and routes shown across the login, setup, and management screens. All values are written to the <code>.env</code> file under the <code>TYRO_LOGIN_PASSKEYS_*</code> keys.</p>
                        </div>
                        <span class="sys-settings-section-badge">.env</span>
                    </div>

                    <div class="sys-settings-grid">
                        <div class="sys-settings-surface">
                            <h4 class="sys-settings-surface-title">Feature Toggle</h4>
                            <p class="sys-settings-surface-description">Enable or disable the passkeys feature for the whole application.</p>

                            <div class="form-group" style="margin-bottom:0;">
                                <div class="sys-settings-toggle">
                                    <div class="sys-settings-toggle-top">
                                        <div>
                                            <p class="sys-settings-toggle-title">Enable passkeys <span style="font-weight:normal">(<code>TYRO_LOGIN_PASSKEYS_ENABLED</code>)</span></p>
                                            <p class="sys-settings-toggle-description">Turns on passwordless passkey sign-in. Requires the <code>laravel/passkeys</code> composer package. Disabling this also hides the Passkeys tab and the profile passkeys section.</p>
                                        </div>
                                        <div>
                                            <input type="hidden" name="TYRO_LOGIN_PASSKEYS_ENABLED" value="0">
                                            <label class="toggle-label">
                                                <input type="checkbox" name="TYRO_LOGIN_PASSKEYS_ENABLED" id="TYRO_LOGIN_PASSKEYS_ENABLED" value="1" class="toggle-input" {{ old('TYRO_LOGIN_PASSKEYS_ENABLED', $settings['TYRO_LOGIN_PASSKEYS_ENABLED']) ? 'checked' : '' }}>
                                                <span class="toggle-slider"></span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div id="passkeys-details-surface" class="sys-settings-grid">
                        <div class="sys-settings-surface">
                            <h4 class="sys-settings-surface-title">Login Page</h4>
                            <p class="sys-settings-surface-description">Labels shown on the passkey login button and the divider beneath it.</p>

                            <div class="form-group" style="margin-bottom:0.85rem;">
                                <label for="TYRO_LOGIN_PASSKEYS_LOGIN_BUTTON" class="form-label">Login button text (TYRO_LOGIN_PASSKEYS_LOGIN_BUTTON)</label>
                                <input type="text" name="TYRO_LOGIN_PASSKEYS_LOGIN_BUTTON" id="TYRO_LOGIN_PASSKEYS_LOGIN_BUTTON"
                                       class="form-input" maxlength="255"
                                       value="{{ old('TYRO_LOGIN_PASSKEYS_LOGIN_BUTTON', $settings['TYRO_LOGIN_PASSKEYS_LOGIN_BUTTON']) }}">
                            </div>

                            <div class="form-group" style="margin-bottom:0;">
                                <label for="TYRO_LOGIN_PASSKEYS_DIVIDER" class="form-label">Divider text (TYRO_LOGIN_PASSKEYS_DIVIDER)</label>
                                <input type="text" name="TYRO_LOGIN_PASSKEYS_DIVIDER" id="TYRO_LOGIN_PASSKEYS_DIVIDER"
                                       class="form-input" maxlength="255"
                                       value="{{ old('TYRO_LOGIN_PASSKEYS_DIVIDER', $settings['TYRO_LOGIN_PASSKEYS_DIVIDER']) }}">
                            </div>
                        </div>

                        <div class="sys-settings-surface">
                            <h4 class="sys-settings-surface-title">Setup Page</h4>
                            <p class="sys-settings-surface-description">Shown on the passkey registration (<code>/passkeys-setup</code>) page.</p>

                            <div class="form-group" style="margin-bottom:0.85rem;">
                                <label for="TYRO_LOGIN_PASSKEYS_SETUP_TITLE" class="form-label">Title (TYRO_LOGIN_PASSKEYS_SETUP_TITLE)</label>
                                <input type="text" name="TYRO_LOGIN_PASSKEYS_SETUP_TITLE" id="TYRO_LOGIN_PASSKEYS_SETUP_TITLE"
                                       class="form-input" maxlength="255"
                                       value="{{ old('TYRO_LOGIN_PASSKEYS_SETUP_TITLE', $settings['TYRO_LOGIN_PASSKEYS_SETUP_TITLE']) }}">
                            </div>

                            <div class="form-group" style="margin-bottom:0.85rem;">
                                <label for="TYRO_LOGIN_PASSKEYS_SETUP_SUBTITLE" class="form-label">Subtitle (TYRO_LOGIN_PASSKEYS_SETUP_SUBTITLE)</label>
                                <input type="text" name="TYRO_LOGIN_PASSKEYS_SETUP_SUBTITLE" id="TYRO_LOGIN_PASSKEYS_SETUP_SUBTITLE"
                                       class="form-input" maxlength="500"
                                       value="{{ old('TYRO_LOGIN_PASSKEYS_SETUP_SUBTITLE', $settings['TYRO_LOGIN_PASSKEYS_SETUP_SUBTITLE']) }}">
                            </div>

                            <div class="form-group" style="margin-bottom:0;">
                                <label for="TYRO_LOGIN_PASSKEYS_SETUP_BUTTON" class="form-label">Button text (TYRO_LOGIN_PASSKEYS_SETUP_BUTTON)</label>
                                <input type="text" name="TYRO_LOGIN_PASSKEYS_SETUP_BUTTON" id="TYRO_LOGIN_PASSKEYS_SETUP_BUTTON"
                                       class="form-input" maxlength="100"
                                       value="{{ old('TYRO_LOGIN_PASSKEYS_SETUP_BUTTON', $settings['TYRO_LOGIN_PASSKEYS_SETUP_BUTTON']) }}">
                            </div>
                        </div>

                        <div class="sys-settings-surface">
                            <h4 class="sys-settings-surface-title">Management Page</h4>
                            <p class="sys-settings-surface-description">Shown on the passkey list &amp; remove (<code>/remove-passkeys</code>) page.</p>

                            <div class="form-group" style="margin-bottom:0.85rem;">
                                <label for="TYRO_LOGIN_PASSKEYS_REMOVE_TITLE" class="form-label">Title (TYRO_LOGIN_PASSKEYS_REMOVE_TITLE)</label>
                                <input type="text" name="TYRO_LOGIN_PASSKEYS_REMOVE_TITLE" id="TYRO_LOGIN_PASSKEYS_REMOVE_TITLE"
                                       class="form-input" maxlength="255"
                                       value="{{ old('TYRO_LOGIN_PASSKEYS_REMOVE_TITLE', $settings['TYRO_LOGIN_PASSKEYS_REMOVE_TITLE']) }}">
                            </div>

                            <div class="form-group" style="margin-bottom:0.85rem;">
                                <label for="TYRO_LOGIN_PASSKEYS_REMOVE_SUBTITLE" class="form-label">Subtitle (TYRO_LOGIN_PASSKEYS_REMOVE_SUBTITLE)</label>
                                <input type="text" name="TYRO_LOGIN_PASSKEYS_REMOVE_SUBTITLE" id="TYRO_LOGIN_PASSKEYS_REMOVE_SUBTITLE"
                                       class="form-input" maxlength="500"
                                       value="{{ old('TYRO_LOGIN_PASSKEYS_REMOVE_SUBTITLE', $settings['TYRO_LOGIN_PASSKEYS_REMOVE_SUBTITLE']) }}">
                            </div>

                            <div class="form-group" style="margin-bottom:0.85rem;">
                                <label for="TYRO_LOGIN_PASSKEYS_REMOVE_BUTTON" class="form-label">Remove button text (TYRO_LOGIN_PASSKEYS_REMOVE_BUTTON)</label>
                                <input type="text" name="TYRO_LOGIN_PASSKEYS_REMOVE_BUTTON" id="TYRO_LOGIN_PASSKEYS_REMOVE_BUTTON"
                                       class="form-input" maxlength="100"
                                       value="{{ old('TYRO_LOGIN_PASSKEYS_REMOVE_BUTTON', $settings['TYRO_LOGIN_PASSKEYS_REMOVE_BUTTON']) }}">
                            </div>

                            <div class="form-group" style="margin-bottom:0;">
                                <label for="TYRO_LOGIN_PASSKEYS_EMPTY_TEXT" class="form-label">Empty state text (TYRO_LOGIN_PASSKEYS_EMPTY_TEXT)</label>
                                <input type="text" name="TYRO_LOGIN_PASSKEYS_EMPTY_TEXT" id="TYRO_LOGIN_PASSKEYS_EMPTY_TEXT"
                                       class="form-input" maxlength="500"
                                       value="{{ old('TYRO_LOGIN_PASSKEYS_EMPTY_TEXT', $settings['TYRO_LOGIN_PASSKEYS_EMPTY_TEXT']) }}">
                            </div>
                        </div>

                        <div class="sys-settings-surface">
                            <h4 class="sys-settings-surface-title">Routes &amp; Client</h4>
                            <p class="sys-settings-surface-description">URL paths for the setup/management pages and the browser client bundle.</p>

                            <div class="form-group" style="margin-bottom:0.85rem;">
                                <label for="TYRO_LOGIN_PASSKEYS_ROUTE" class="form-label">Setup route path (TYRO_LOGIN_PASSKEYS_ROUTE)</label>
                                <input type="text" name="TYRO_LOGIN_PASSKEYS_ROUTE" id="TYRO_LOGIN_PASSKEYS_ROUTE"
                                       class="form-input" maxlength="100"
                                       value="{{ old('TYRO_LOGIN_PASSKEYS_ROUTE', $settings['TYRO_LOGIN_PASSKEYS_ROUTE']) }}">
                                <p class="form-hint">Path relative to the app root, e.g. <code>passkeys-setup</code>.</p>
                            </div>

                            <div class="form-group" style="margin-bottom:0.85rem;">
                                <label for="TYRO_LOGIN_PASSKEYS_REMOVE_ROUTE" class="form-label">Management route path (TYRO_LOGIN_PASSKEYS_REMOVE_ROUTE)</label>
                                <input type="text" name="TYRO_LOGIN_PASSKEYS_REMOVE_ROUTE" id="TYRO_LOGIN_PASSKEYS_REMOVE_ROUTE"
                                       class="form-input" maxlength="100"
                                       value="{{ old('TYRO_LOGIN_PASSKEYS_REMOVE_ROUTE', $settings['TYRO_LOGIN_PASSKEYS_REMOVE_ROUTE']) }}">
                                <p class="form-hint">Path relative to the app root, e.g. <code>remove-passkeys</code>.</p>
                            </div>

                            <div class="form-group" style="margin-bottom:0;">
                                <label for="TYRO_LOGIN_PASSKEYS_CDN" class="form-label">Browser client URL (TYRO_LOGIN_PASSKEYS_CDN)</label>
                                <input type="text" name="TYRO_LOGIN_PASSKEYS_CDN" id="TYRO_LOGIN_PASSKEYS_CDN"
                                       class="form-input" maxlength="500"
                                       value="{{ old('TYRO_LOGIN_PASSKEYS_CDN', $settings['TYRO_LOGIN_PASSKEYS_CDN']) }}">
                                <p class="form-hint">ESM URL for the <code>@laravel/passkeys</code> client. Override to self-host.</p>
                            </div>
                        </div>
                        </div><!-- /passkeys-details-surface -->
                    </div>
                </div>
            </div>
        </div><!-- /vtab-passkeys -->
