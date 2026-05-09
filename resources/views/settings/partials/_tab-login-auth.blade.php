            {{-- Authentication Tab --}}
            <div class="vtabs-panel" id="vtab-login-auth">
                <div class="card">
                    <div class="card-header" style="display:flex;align-items:center;justify-content:space-between;gap:1rem;">
                        <h3 class="card-title">Authentication</h3>
                        <button type="submit" form="systemSettingsForm" class="btn btn-primary btn-sm section-save-button">Save</button>
                    </div>
                    <div class="card-body">
                        <div class="sys-settings-section-intro">
                            <div class="sys-settings-section-copy">
                                <h4 class="sys-settings-section-heading">Manage Tyro Login environment configuration</h4>
                                <p class="sys-settings-section-description">Control layout, registration, OTP, 2FA, captcha, social login, password rules, email settings, and lockout protection.</p>
                            </div>
                            <span class="sys-settings-section-badge">.env</span>
                        </div>

                        <div class="sys-settings-grid">
                            <div class="sys-settings-surface">
                                <h4 class="sys-settings-surface-title">Layout &amp; Branding</h4>
                                <p class="sys-settings-surface-description">Customize the appearance of auth pages.</p>

                                <div class="form-group" style="margin-bottom:0.85rem;">
                                    <label for="TYRO_LOGIN_LAYOUT" class="form-label">Auth page layout (TYRO_LOGIN_LAYOUT)</label>
                                    <select name="TYRO_LOGIN_LAYOUT" id="TYRO_LOGIN_LAYOUT" class="form-select">
                                        <option value="centered" {{ old('TYRO_LOGIN_LAYOUT', $settings['TYRO_LOGIN_LAYOUT']) === 'centered' ? 'selected' : '' }}>Centered</option>
                                        <option value="split-left" {{ old('TYRO_LOGIN_LAYOUT', $settings['TYRO_LOGIN_LAYOUT']) === 'split-left' ? 'selected' : '' }}>Split left</option>
                                        <option value="split-right" {{ old('TYRO_LOGIN_LAYOUT', $settings['TYRO_LOGIN_LAYOUT']) === 'split-right' ? 'selected' : '' }}>Split right</option>
                                        <option value="fullscreen" {{ old('TYRO_LOGIN_LAYOUT', $settings['TYRO_LOGIN_LAYOUT']) === 'fullscreen' ? 'selected' : '' }}>Fullscreen</option>
                                        <option value="card" {{ old('TYRO_LOGIN_LAYOUT', $settings['TYRO_LOGIN_LAYOUT']) === 'card' ? 'selected' : '' }}>Card</option>
                                    </select>
                                </div>

                                <div class="form-group" style="margin-bottom:0.85rem;">
                                    <label for="TYRO_LOGIN_APP_NAME" class="form-label">Auth app name (TYRO_LOGIN_APP_NAME)</label>
                                    <input type="text" name="TYRO_LOGIN_APP_NAME" id="TYRO_LOGIN_APP_NAME"
                                           class="form-input" maxlength="255"
                                           value="{{ old('TYRO_LOGIN_APP_NAME', $settings['TYRO_LOGIN_APP_NAME']) }}">
                                </div>

                                <div class="form-group" style="margin-bottom:0;">
                                    <label for="TYRO_LOGIN_BACKGROUND_IMAGE" class="form-label">Background image URL (TYRO_LOGIN_BACKGROUND_IMAGE)</label>
                                    <input type="url" name="TYRO_LOGIN_BACKGROUND_IMAGE" id="TYRO_LOGIN_BACKGROUND_IMAGE"
                                           class="form-input" maxlength="500"
                                           value="{{ old('TYRO_LOGIN_BACKGROUND_IMAGE', $settings['TYRO_LOGIN_BACKGROUND_IMAGE']) }}">
                                    <p class="form-hint">Used for split and fullscreen layouts.</p>
                                </div>
                            </div>

                            <div class="sys-settings-surface">
                                <h4 class="sys-settings-surface-title">Branding Details</h4>
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
                                <div class="form-group" style="margin-bottom:0;">
                                    <label for="TYRO_LOGIN_LOGO_HEIGHT" class="form-label">Logo height (TYRO_LOGIN_LOGO_HEIGHT)</label>
                                    <input type="text" name="TYRO_LOGIN_LOGO_HEIGHT" id="TYRO_LOGIN_LOGO_HEIGHT" class="form-input" maxlength="20" value="{{ old('TYRO_LOGIN_LOGO_HEIGHT', $settings['TYRO_LOGIN_LOGO_HEIGHT']) }}">
                                    <p class="form-hint">CSS value e.g. <code>32px</code>, <code>3rem</code>.</p>
                                </div>
                            </div>

                            <div class="sys-settings-surface">
                                <h4 class="sys-settings-surface-title">Redirects</h4>
                                <p class="sys-settings-surface-description">Where users are sent after login/logout.</p>

                                <div class="form-group" style="margin-bottom:0.85rem;">
                                    <label for="TYRO_LOGIN_REDIRECT_AFTER_LOGIN" class="form-label">After login (TYRO_LOGIN_REDIRECT_AFTER_LOGIN)</label>
                                    <input type="text" name="TYRO_LOGIN_REDIRECT_AFTER_LOGIN" id="TYRO_LOGIN_REDIRECT_AFTER_LOGIN"
                                           class="form-input" maxlength="255"
                                           value="{{ old('TYRO_LOGIN_REDIRECT_AFTER_LOGIN', $settings['TYRO_LOGIN_REDIRECT_AFTER_LOGIN']) }}">
                                </div>

                                <div class="form-group" style="margin-bottom:0.85rem;">
                                    <label for="TYRO_LOGIN_REDIRECT_AFTER_LOGOUT" class="form-label">After logout (TYRO_LOGIN_REDIRECT_AFTER_LOGOUT)</label>
                                    <input type="text" name="TYRO_LOGIN_REDIRECT_AFTER_LOGOUT" id="TYRO_LOGIN_REDIRECT_AFTER_LOGOUT"
                                           class="form-input" maxlength="255"
                                           value="{{ old('TYRO_LOGIN_REDIRECT_AFTER_LOGOUT', $settings['TYRO_LOGIN_REDIRECT_AFTER_LOGOUT']) }}">
                                </div>

                                <div class="form-group" style="margin-bottom:0.85rem;">
                                    <label for="TYRO_LOGIN_REDIRECT_AFTER_REGISTER" class="form-label">After register (TYRO_LOGIN_REDIRECT_AFTER_REGISTER)</label>
                                    <input type="text" name="TYRO_LOGIN_REDIRECT_AFTER_REGISTER" id="TYRO_LOGIN_REDIRECT_AFTER_REGISTER"
                                           class="form-input" maxlength="255"
                                           value="{{ old('TYRO_LOGIN_REDIRECT_AFTER_REGISTER', $settings['TYRO_LOGIN_REDIRECT_AFTER_REGISTER']) }}">
                                </div>

                                <div class="form-group" style="margin-bottom:0;">
                                    <label for="TYRO_LOGIN_REDIRECT_AFTER_EMAIL_VERIFICATION" class="form-label">After email verification (TYRO_LOGIN_REDIRECT_AFTER_EMAIL_VERIFICATION)</label>
                                    <input type="text" name="TYRO_LOGIN_REDIRECT_AFTER_EMAIL_VERIFICATION" id="TYRO_LOGIN_REDIRECT_AFTER_EMAIL_VERIFICATION"
                                           class="form-input" maxlength="255"
                                           value="{{ old('TYRO_LOGIN_REDIRECT_AFTER_EMAIL_VERIFICATION', $settings['TYRO_LOGIN_REDIRECT_AFTER_EMAIL_VERIFICATION']) }}">
                                </div>
                            </div>

                            <div class="sys-settings-surface">
                                <h4 class="sys-settings-surface-title">Registration &amp; Login</h4>
                                <p class="sys-settings-surface-description">Basic registration and login form preferences.</p>

                                <div class="sys-settings-toggles" style="margin-bottom:0.85rem;">
                                    <div class="sys-settings-toggle">
                                        <div class="sys-settings-toggle-top">
                                            <div>
                                                <p class="sys-settings-toggle-title">Enable registration <span style="font-weight:normal">(<code>TYRO_LOGIN_REGISTRATION_ENABLED</code>)</span></p>
                                                <p class="sys-settings-toggle-description">Allows new users to create accounts. When disabled, the registration page is hidden.</p>
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
                                                <p class="sys-settings-toggle-title">Require email verification <span style="font-weight:normal">(<code>TYRO_LOGIN_REQUIRE_EMAIL_VERIFICATION</code>)</span></p>
                                                <p class="sys-settings-toggle-description">Requires users to verify their email address via a confirmation link before they can access the dashboard.</p>
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
                                                <p class="sys-settings-toggle-title">Show "Remember Me" <span style="font-weight:normal">(<code>TYRO_LOGIN_REMEMBER_ME</code>)</span></p>
                                                <p class="sys-settings-toggle-description">Displays the "Remember Me" checkbox on the login form, allowing users to stay signed in across sessions.</p>
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
                                                <p class="sys-settings-toggle-title">Show "Forgot Password" <span style="font-weight:normal">(<code>TYRO_LOGIN_FORGOT_PASSWORD</code>)</span></p>
                                                <p class="sys-settings-toggle-description">Shows the "Forgot Password" link on the login form for password reset requests.</p>
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
                                    <label for="TYRO_LOGIN_FIELD" class="form-label">Login field (TYRO_LOGIN_FIELD)</label>
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
                                                <p class="sys-settings-toggle-title">Captcha on login <span style="font-weight:normal">(<code>TYRO_LOGIN_CAPTCHA_LOGIN</code>)</span></p>
                                                <p class="sys-settings-toggle-description">Shows a CAPTCHA challenge on the login form to prevent automated brute-force attacks.</p>
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
                                                <p class="sys-settings-toggle-title">Captcha on registration <span style="font-weight:normal">(<code>TYRO_LOGIN_CAPTCHA_REGISTER</code>)</span></p>
                                                <p class="sys-settings-toggle-description">Shows a CAPTCHA challenge on the registration form to prevent automated spam sign-ups.</p>
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
                                                <p class="sys-settings-toggle-title">Enable OTP <span style="font-weight:normal">(<code>TYRO_LOGIN_OTP_ENABLED</code>)</span></p>
                                                <p class="sys-settings-toggle-description">Sends a one-time passcode to the user's email for passwordless login verification.</p>
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
                                                <p class="sys-settings-toggle-title">Enable 2FA <span style="font-weight:normal">(<code>TYRO_LOGIN_2FA_ENABLED</code>)</span></p>
                                                <p class="sys-settings-toggle-description">Requires users to set up two-factor authentication for an extra layer of account security.</p>
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
                                                <p class="sys-settings-toggle-title">Enable magic links <span style="font-weight:normal">(<code>TYRO_LOGIN_ENABLE_MAGIC_LINKS</code>)</span></p>
                                                <p class="sys-settings-toggle-description">Sends a magic link to the user's email that logs them in without requiring a password.</p>
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

                            </div>

                            <div class="sys-settings-surface">
                                <h4 class="sys-settings-surface-title">Social Login</h4>
                                <p class="sys-settings-surface-description">Enable/disable social authentication.</p>

                                <div class="sys-settings-toggles" style="margin-bottom:0;">
                                    <div class="sys-settings-toggle">
                                        <div class="sys-settings-toggle-top">
                                            <div>
                                                <p class="sys-settings-toggle-title">Enable social login <span style="font-weight:normal">(<code>TYRO_LOGIN_SOCIAL_ENABLED</code>)</span></p>
                                                <p class="sys-settings-toggle-description">Displays social login buttons on the auth pages for providers like Google, Facebook, and GitHub.</p>
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
                                                <p class="sys-settings-toggle-title">Auto-register social users <span style="font-weight:normal">(<code>TYRO_LOGIN_SOCIAL_AUTO_REGISTER</code>)</span></p>
                                                <p class="sys-settings-toggle-description">Automatically creates an account for first-time users who log in via a social provider, bypassing the registration form.</p>
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
                                                <p class="sys-settings-toggle-title">Enable lockout <span style="font-weight:normal">(<code>TYRO_LOGIN_LOCKOUT_ENABLED</code>)</span></p>
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
                                        <label for="TYRO_LOGIN_LOCKOUT_MAX_ATTEMPTS" class="form-label">Max attempts (TYRO_LOGIN_LOCKOUT_MAX_ATTEMPTS)</label>
                                        <input type="number" name="TYRO_LOGIN_LOCKOUT_MAX_ATTEMPTS" id="TYRO_LOGIN_LOCKOUT_MAX_ATTEMPTS"
                                               class="form-input" min="1" max="50"
                                               value="{{ old('TYRO_LOGIN_LOCKOUT_MAX_ATTEMPTS', $settings['TYRO_LOGIN_LOCKOUT_MAX_ATTEMPTS']) }}">
                                    </div>
                                    <div class="form-group sys-settings-metric" style="margin-bottom:0;">
                                        <label for="TYRO_LOGIN_LOCKOUT_DURATION" class="form-label">Duration (min) (TYRO_LOGIN_LOCKOUT_DURATION)</label>
                                        <input type="number" name="TYRO_LOGIN_LOCKOUT_DURATION" id="TYRO_LOGIN_LOCKOUT_DURATION"
                                               class="form-input" min="1" max="1440"
                                               value="{{ old('TYRO_LOGIN_LOCKOUT_DURATION', $settings['TYRO_LOGIN_LOCKOUT_DURATION']) }}">
                                    </div>
                                </div>

                                <div class="sys-settings-toggle" style="margin-bottom:0;">
                                    <div class="sys-settings-toggle-top">
                                        <div>
                                            <p class="sys-settings-toggle-title">Show remaining attempts <span style="font-weight:normal">(<code>TYRO_LOGIN_SHOW_ATTEMPTS_LEFT</code>)</span></p>
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

                            <div class="sys-settings-surface">
                                <h4 class="sys-settings-surface-title">Token Expiration</h4>
                                <p class="sys-settings-surface-description">Verification and password reset token expiry.</p>

                                <div class="sys-settings-metrics" style="margin-bottom:0;">
                                    <div class="form-group sys-settings-metric" style="margin-bottom:0;">
                                        <label for="TYRO_LOGIN_VERIFICATION_EXPIRE" class="form-label">Verification expire (min) (TYRO_LOGIN_VERIFICATION_EXPIRE)</label>
                                        <input type="number" name="TYRO_LOGIN_VERIFICATION_EXPIRE" id="TYRO_LOGIN_VERIFICATION_EXPIRE"
                                               class="form-input" min="1" max="1440"
                                               value="{{ old('TYRO_LOGIN_VERIFICATION_EXPIRE', $settings['TYRO_LOGIN_VERIFICATION_EXPIRE']) }}">
                                    </div>
                                    <div class="form-group sys-settings-metric" style="margin-bottom:0;">
                                        <label for="TYRO_LOGIN_PASSWORD_RESET_EXPIRE" class="form-label">Password reset expire (min) (TYRO_LOGIN_PASSWORD_RESET_EXPIRE)</label>
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
                                                <p class="sys-settings-toggle-title">OTP email <span style="font-weight:normal">(<code>TYRO_LOGIN_EMAIL_OTP</code>)</span></p>
                                                <p class="sys-settings-toggle-description">Sends OTP codes via email to users when they choose the OTP login method.</p>
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
                                                <p class="sys-settings-toggle-title">Password reset email <span style="font-weight:normal">(<code>TYRO_LOGIN_EMAIL_PASSWORD_RESET</code>)</span></p>
                                                <p class="sys-settings-toggle-description">Sends password reset links via email when users request a password change.</p>
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
                                                <p class="sys-settings-toggle-title">Email verification email <span style="font-weight:normal">(<code>TYRO_LOGIN_EMAIL_VERIFY</code>)</span></p>
                                                <p class="sys-settings-toggle-description">Sends email verification links to new users to confirm their email address ownership.</p>
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
                                                <p class="sys-settings-toggle-title">Welcome email <span style="font-weight:normal">(<code>TYRO_LOGIN_EMAIL_WELCOME</code>)</span></p>
                                                <p class="sys-settings-toggle-description">Sends a welcome email to new users after they successfully complete registration.</p>
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
                                                <p class="sys-settings-toggle-title">Magic link email <span style="font-weight:normal">(<code>TYRO_LOGIN_EMAIL_MAGIC_LINK</code>)</span></p>
                                                <p class="sys-settings-toggle-description">Sends magic login links via email for passwordless authentication.</p>
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

                            <div class="sys-settings-surface">
                                <h4 class="sys-settings-surface-title">Password Rules</h4>
                                <p class="sys-settings-surface-description">Control password requirements for registration and updates.</p>

                                <div class="form-group" style="margin-bottom:0.85rem;">
                                    <label for="TYRO_LOGIN_PASSWORD_MIN_LENGTH" class="form-label">Minimum password length (TYRO_LOGIN_PASSWORD_MIN_LENGTH)</label>
                                    <input type="number" name="TYRO_LOGIN_PASSWORD_MIN_LENGTH" id="TYRO_LOGIN_PASSWORD_MIN_LENGTH"
                                           class="form-input" min="4" max="100"
                                           value="{{ old('TYRO_LOGIN_PASSWORD_MIN_LENGTH', $settings['TYRO_LOGIN_PASSWORD_MIN_LENGTH']) }}">
                                </div>

                                <div class="sys-settings-toggles" style="margin-bottom:0;">
                                    <div class="sys-settings-toggle">
                                        <div class="sys-settings-toggle-top">
                                            <div>
                                                <p class="sys-settings-toggle-title">Require uppercase <span style="font-weight:normal">(<code>TYRO_LOGIN_PASSWORD_REQUIRE_UPPERCASE</code>)</span></p>
                                                <p class="sys-settings-toggle-description">Requires at least one uppercase letter (A-Z) in user passwords.</p>
                                            </div>
                                            <div>
                                                <input type="hidden" name="TYRO_LOGIN_PASSWORD_REQUIRE_UPPERCASE" value="0">
                                                <label class="toggle-label">
                                                    <input type="checkbox" name="TYRO_LOGIN_PASSWORD_REQUIRE_UPPERCASE" value="1" class="toggle-input" {{ old('TYRO_LOGIN_PASSWORD_REQUIRE_UPPERCASE', $settings['TYRO_LOGIN_PASSWORD_REQUIRE_UPPERCASE']) ? 'checked' : '' }}>
                                                    <span class="toggle-slider"></span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="sys-settings-toggle">
                                        <div class="sys-settings-toggle-top">
                                            <div>
                                                <p class="sys-settings-toggle-title">Require lowercase <span style="font-weight:normal">(<code>TYRO_LOGIN_PASSWORD_REQUIRE_LOWERCASE</code>)</span></p>
                                                <p class="sys-settings-toggle-description">Requires at least one lowercase letter (a-z) in user passwords.</p>
                                            </div>
                                            <div>
                                                <input type="hidden" name="TYRO_LOGIN_PASSWORD_REQUIRE_LOWERCASE" value="0">
                                                <label class="toggle-label">
                                                    <input type="checkbox" name="TYRO_LOGIN_PASSWORD_REQUIRE_LOWERCASE" value="1" class="toggle-input" {{ old('TYRO_LOGIN_PASSWORD_REQUIRE_LOWERCASE', $settings['TYRO_LOGIN_PASSWORD_REQUIRE_LOWERCASE']) ? 'checked' : '' }}>
                                                    <span class="toggle-slider"></span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="sys-settings-toggle">
                                        <div class="sys-settings-toggle-top">
                                            <div>
                                                <p class="sys-settings-toggle-title">Require numbers <span style="font-weight:normal">(<code>TYRO_LOGIN_PASSWORD_REQUIRE_NUMBERS</code>)</span></p>
                                                <p class="sys-settings-toggle-description">Requires at least one numeric digit (0-9) in user passwords.</p>
                                            </div>
                                            <div>
                                                <input type="hidden" name="TYRO_LOGIN_PASSWORD_REQUIRE_NUMBERS" value="0">
                                                <label class="toggle-label">
                                                    <input type="checkbox" name="TYRO_LOGIN_PASSWORD_REQUIRE_NUMBERS" value="1" class="toggle-input" {{ old('TYRO_LOGIN_PASSWORD_REQUIRE_NUMBERS', $settings['TYRO_LOGIN_PASSWORD_REQUIRE_NUMBERS']) ? 'checked' : '' }}>
                                                    <span class="toggle-slider"></span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="sys-settings-toggle">
                                        <div class="sys-settings-toggle-top">
                                            <div>
                                                <p class="sys-settings-toggle-title">Require special characters <span style="font-weight:normal">(<code>TYRO_LOGIN_PASSWORD_REQUIRE_SPECIAL_CHARS</code>)</span></p>
                                                <p class="sys-settings-toggle-description">Requires at least one special character (e.g. !@#$%) in user passwords.</p>
                                            </div>
                                            <div>
                                                <input type="hidden" name="TYRO_LOGIN_PASSWORD_REQUIRE_SPECIAL_CHARS" value="0">
                                                <label class="toggle-label">
                                                    <input type="checkbox" name="TYRO_LOGIN_PASSWORD_REQUIRE_SPECIAL_CHARS" value="1" class="toggle-input" {{ old('TYRO_LOGIN_PASSWORD_REQUIRE_SPECIAL_CHARS', $settings['TYRO_LOGIN_PASSWORD_REQUIRE_SPECIAL_CHARS']) ? 'checked' : '' }}>
                                                    <span class="toggle-slider"></span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="sys-settings-toggle">
                                        <div class="sys-settings-toggle-top">
                                            <div>
                                                <p class="sys-settings-toggle-title">Block common passwords <span style="font-weight:normal">(<code>TYRO_LOGIN_PASSWORD_CHECK_COMMON</code>)</span></p>
                                                <p class="sys-settings-toggle-description">Blocks commonly used and easily guessable passwords from being used, improving account security.</p>
                                            </div>
                                            <div>
                                                <input type="hidden" name="TYRO_LOGIN_PASSWORD_CHECK_COMMON" value="0">
                                                <label class="toggle-label">
                                                    <input type="checkbox" name="TYRO_LOGIN_PASSWORD_CHECK_COMMON" value="1" class="toggle-input" {{ old('TYRO_LOGIN_PASSWORD_CHECK_COMMON', $settings['TYRO_LOGIN_PASSWORD_CHECK_COMMON']) ? 'checked' : '' }}>
                                                    <span class="toggle-slider"></span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="sys-settings-toggle">
                                        <div class="sys-settings-toggle-top">
                                            <div>
                                                <p class="sys-settings-toggle-title">Require password confirmation <span style="font-weight:normal">(<code>TYRO_LOGIN_PASSWORD_REQUIRE_CONFIRMATION</code>)</span></p>
                                                <p class="sys-settings-toggle-description">Requires users to type their password twice during registration to prevent typos.</p>
                                            </div>
                                            <div>
                                                <input type="hidden" name="TYRO_LOGIN_PASSWORD_REQUIRE_CONFIRMATION" value="0">
                                                <label class="toggle-label">
                                                    <input type="checkbox" name="TYRO_LOGIN_PASSWORD_REQUIRE_CONFIRMATION" value="1" class="toggle-input" {{ old('TYRO_LOGIN_PASSWORD_REQUIRE_CONFIRMATION', $settings['TYRO_LOGIN_PASSWORD_REQUIRE_CONFIRMATION']) ? 'checked' : '' }}>
                                                    <span class="toggle-slider"></span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="sys-settings-toggle">
                                        <div class="sys-settings-toggle-top">
                                            <div>
                                                <p class="sys-settings-toggle-title">Disallow user info in password <span style="font-weight:normal">(<code>TYRO_LOGIN_PASSWORD_DISALLOW_USER_INFO</code>)</span></p>
                                                <p class="sys-settings-toggle-description">Prevents users from including their email address, username, or name parts in their password.</p>
                                            </div>
                                            <div>
                                                <input type="hidden" name="TYRO_LOGIN_PASSWORD_DISALLOW_USER_INFO" value="0">
                                                <label class="toggle-label">
                                                    <input type="checkbox" name="TYRO_LOGIN_PASSWORD_DISALLOW_USER_INFO" value="1" class="toggle-input" {{ old('TYRO_LOGIN_PASSWORD_DISALLOW_USER_INFO', $settings['TYRO_LOGIN_PASSWORD_DISALLOW_USER_INFO']) ? 'checked' : '' }}>
                                                    <span class="toggle-slider"></span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group" style="margin-top:0.85rem; margin-bottom:0;">
                                    <label for="TYRO_LOGIN_PASSWORD_MAX_LENGTH" class="form-label">Maximum password length (TYRO_LOGIN_PASSWORD_MAX_LENGTH)</label>
                                    <input type="number" name="TYRO_LOGIN_PASSWORD_MAX_LENGTH" id="TYRO_LOGIN_PASSWORD_MAX_LENGTH"
                                           class="form-input" min="0" max="500"
                                           value="{{ old('TYRO_LOGIN_PASSWORD_MAX_LENGTH', $settings['TYRO_LOGIN_PASSWORD_MAX_LENGTH']) }}">
                                    <p class="form-hint">Leave 0 for no limit. Writes <code>TYRO_LOGIN_PASSWORD_MAX_LENGTH</code>.</p>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
