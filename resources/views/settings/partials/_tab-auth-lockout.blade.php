{{-- Lockout & tokens --}}
            <div class="vtabs-panel" id="vtab-auth-lockout">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Lockout &amp; tokens</h3>
                    </div>
                    <div class="card-body">
                        <p class="sys-settings-section-description" style="margin-bottom:1rem;">Brute-force protection and token expiry.</p>

                        <div class="sys-settings-grid">
                            <div class="sys-settings-surface">
                                <h4 class="sys-settings-surface-title">Lockout protection</h4>
                                <p class="sys-settings-surface-description">Brute-force protection settings.</p>

                                <div class="sys-settings-toggles" style="margin-bottom:0.85rem;">
                                    <div class="sys-settings-toggle">
                                        <div class="sys-settings-toggle-top">
                                            <div>
                                                <p class="sys-settings-toggle-title">Enable lockout <span class="env-key">TYRO_LOGIN_LOCKOUT_ENABLED</span></p>
                                                <p class="sys-settings-toggle-description">Locks users out after too many failed login attempts to prevent brute-force password guessing.</p>
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

                                <div class="sys-settings-metrics" style="margin-bottom:0.85rem;">
                                    <div class="form-group sys-settings-metric" style="margin-bottom:0;">
                                        <label for="TYRO_LOGIN_LOCKOUT_MAX_ATTEMPTS" class="form-label">Max attempts <span class="env-key">TYRO_LOGIN_LOCKOUT_MAX_ATTEMPTS</span></label>
                                        <input type="number" name="TYRO_LOGIN_LOCKOUT_MAX_ATTEMPTS" id="TYRO_LOGIN_LOCKOUT_MAX_ATTEMPTS"
                                               class="form-input" min="1" max="50"
                                               value="{{ old('TYRO_LOGIN_LOCKOUT_MAX_ATTEMPTS', $settings['TYRO_LOGIN_LOCKOUT_MAX_ATTEMPTS']) }}">
                                    </div>
                                    <div class="form-group sys-settings-metric" style="margin-bottom:0;">
                                        <label for="TYRO_LOGIN_LOCKOUT_DURATION" class="form-label">Duration (min) <span class="env-key">TYRO_LOGIN_LOCKOUT_DURATION</span></label>
                                        <input type="number" name="TYRO_LOGIN_LOCKOUT_DURATION" id="TYRO_LOGIN_LOCKOUT_DURATION"
                                               class="form-input" min="1" max="1440"
                                               value="{{ old('TYRO_LOGIN_LOCKOUT_DURATION', $settings['TYRO_LOGIN_LOCKOUT_DURATION']) }}">
                                    </div>
                                </div>

                                <div class="sys-settings-toggle" style="margin-bottom:0;">
                                    <div class="sys-settings-toggle-top">
                                        <div>
                                            <p class="sys-settings-toggle-title">Show remaining attempts <span class="env-key">TYRO_LOGIN_SHOW_ATTEMPTS_LEFT</span></p>
                                                <p class="sys-settings-toggle-description">Displays the number of remaining login attempts before the user is locked out, helping them avoid accidental lockout.</p>
                                        </div>
                                        <div>
                                            <input type="hidden" name="TYRO_LOGIN_SHOW_ATTEMPTS_LEFT" value="0">
                                            <label class="toggle-label">
                                                <input type="checkbox" name="TYRO_LOGIN_SHOW_ATTEMPTS_LEFT" value="1" class="toggle-input" {{ old('TYRO_LOGIN_SHOW_ATTEMPTS_LEFT', $settings['TYRO_LOGIN_SHOW_ATTEMPTS_LEFT']) ? 'checked' : '' }}>
                                                <span class="toggle-slider"></span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="sys-settings-surface" id="lockout-details-surface">
                                <h4 class="sys-settings-surface-title">Lockout settings</h4>
                                <p class="sys-settings-surface-description">Lockout page text and auto-redirect behaviour. Visible when lockout is enabled.</p>

                                <div class="sys-settings-toggles" style="margin-bottom:0.85rem;">
                                    <div class="sys-settings-toggle">
                                        <div class="sys-settings-toggle-top">
                                            <div>
                                                <p class="sys-settings-toggle-title">Auto-redirect after lockout expires <span class="env-key">TYRO_LOGIN_LOCKOUT_AUTO_REDIRECT</span></p>
                                                <p class="sys-settings-toggle-description">Automatically redirects users back to the login page once the lockout period expires, instead of requiring a manual page refresh.</p>
                                            </div>
                                            <div>
                                                <input type="hidden" name="TYRO_LOGIN_LOCKOUT_AUTO_REDIRECT" value="0">
                                                <label class="toggle-label">
                                                    <input type="checkbox" name="TYRO_LOGIN_LOCKOUT_AUTO_REDIRECT" value="1" class="toggle-input" {{ old('TYRO_LOGIN_LOCKOUT_AUTO_REDIRECT', $settings['TYRO_LOGIN_LOCKOUT_AUTO_REDIRECT']) ? 'checked' : '' }}>
                                                    <span class="toggle-slider"></span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group" style="margin-bottom:0.85rem;">
                                    <label for="TYRO_LOGIN_LOCKOUT_MESSAGE" class="form-label">Lockout message <span class="env-key">TYRO_LOGIN_LOCKOUT_MESSAGE</span></label>
                                    <input type="text" name="TYRO_LOGIN_LOCKOUT_MESSAGE" id="TYRO_LOGIN_LOCKOUT_MESSAGE" class="form-input" maxlength="500" value="{{ old('TYRO_LOGIN_LOCKOUT_MESSAGE', $settings['TYRO_LOGIN_LOCKOUT_MESSAGE']) }}">
                                    <p class="form-hint">Supports <code>:minutes</code> placeholder.</p>
                                </div>
                                <div class="form-group" style="margin-bottom:0.85rem;">
                                    <label for="TYRO_LOGIN_LOCKOUT_TITLE" class="form-label">Lockout page title <span class="env-key">TYRO_LOGIN_LOCKOUT_TITLE</span></label>
                                    <input type="text" name="TYRO_LOGIN_LOCKOUT_TITLE" id="TYRO_LOGIN_LOCKOUT_TITLE" class="form-input" maxlength="255" value="{{ old('TYRO_LOGIN_LOCKOUT_TITLE', $settings['TYRO_LOGIN_LOCKOUT_TITLE']) }}">
                                </div>
                                <div class="form-group" style="margin-bottom:0;">
                                    <label for="TYRO_LOGIN_LOCKOUT_SUBTITLE" class="form-label">Lockout page subtitle <span class="env-key">TYRO_LOGIN_LOCKOUT_SUBTITLE</span></label>
                                    <input type="text" name="TYRO_LOGIN_LOCKOUT_SUBTITLE" id="TYRO_LOGIN_LOCKOUT_SUBTITLE" class="form-input" maxlength="500" value="{{ old('TYRO_LOGIN_LOCKOUT_SUBTITLE', $settings['TYRO_LOGIN_LOCKOUT_SUBTITLE']) }}">
                                </div>
                            </div>

                            <div class="sys-settings-surface">
                                <h4 class="sys-settings-surface-title">Token expiration</h4>
                                <p class="sys-settings-surface-description">Verification and password reset token expiry.</p>

                                <div class="sys-settings-metrics" style="margin-bottom:0;">
                                    <div class="form-group sys-settings-metric" style="margin-bottom:0;">
                                        <label for="TYRO_LOGIN_VERIFICATION_EXPIRE" class="form-label">Verification expire (min) <span class="env-key">TYRO_LOGIN_VERIFICATION_EXPIRE</span></label>
                                        <input type="number" name="TYRO_LOGIN_VERIFICATION_EXPIRE" id="TYRO_LOGIN_VERIFICATION_EXPIRE"
                                               class="form-input" min="1" max="1440"
                                               value="{{ old('TYRO_LOGIN_VERIFICATION_EXPIRE', $settings['TYRO_LOGIN_VERIFICATION_EXPIRE']) }}">
                                    </div>
                                    <div class="form-group sys-settings-metric" style="margin-bottom:0;">
                                        <label for="TYRO_LOGIN_PASSWORD_RESET_EXPIRE" class="form-label">Password reset expire (min) <span class="env-key">TYRO_LOGIN_PASSWORD_RESET_EXPIRE</span></label>
                                        <input type="number" name="TYRO_LOGIN_PASSWORD_RESET_EXPIRE" id="TYRO_LOGIN_PASSWORD_RESET_EXPIRE"
                                               class="form-input" min="1" max="1440"
                                               value="{{ old('TYRO_LOGIN_PASSWORD_RESET_EXPIRE', $settings['TYRO_LOGIN_PASSWORD_RESET_EXPIRE']) }}">
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
