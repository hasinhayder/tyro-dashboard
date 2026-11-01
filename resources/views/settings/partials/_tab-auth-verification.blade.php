{{-- Verification & captcha --}}
            <div class="vtabs-panel" id="vtab-auth-verification">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Verification &amp; captcha</h3>
                    </div>
                    <div class="card-body">
                        <p class="sys-settings-section-description" style="margin-bottom:1rem;">Captcha, one-time passcodes and two-factor authentication.</p>

                        <div class="sys-settings-grid">
                            <div class="sys-settings-surface">
                                <h4 class="sys-settings-surface-title">Security features</h4>
                                <p class="sys-settings-surface-description">Captcha, OTP, 2FA, and magic link settings.</p>

                                <div class="sys-settings-toggles" style="margin-bottom:0.85rem;">
                                    <div class="sys-settings-toggle">
                                        <div class="sys-settings-toggle-top">
                                            <div>
                                                <p class="sys-settings-toggle-title">Captcha on login <span class="env-key">TYRO_LOGIN_CAPTCHA_LOGIN</span></p>
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
                                                <p class="sys-settings-toggle-title">Captcha on registration <span class="env-key">TYRO_LOGIN_CAPTCHA_REGISTER</span></p>
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
                                                <p class="sys-settings-toggle-title">Enable OTP <span class="env-key">TYRO_LOGIN_OTP_ENABLED</span></p>
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
                                                <p class="sys-settings-toggle-title">Enable 2FA <span class="env-key">TYRO_LOGIN_2FA_ENABLED</span></p>
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
                                                <p class="sys-settings-toggle-title">Enable magic links <span class="env-key">TYRO_LOGIN_ENABLE_MAGIC_LINKS</span></p>
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

                            <div class="sys-settings-surface" id="captcha-details-surface">
                                <h4 class="sys-settings-surface-title">Captcha</h4>
                                <p class="sys-settings-surface-description">Captcha text labels and number range. Visible when captcha is enabled.</p>

                                <div class="form-group" style="margin-bottom:0.85rem;">
                                    <label for="TYRO_LOGIN_CAPTCHA_LABEL" class="form-label">Label <span class="env-key">TYRO_LOGIN_CAPTCHA_LABEL</span></label>
                                    <input type="text" name="TYRO_LOGIN_CAPTCHA_LABEL" id="TYRO_LOGIN_CAPTCHA_LABEL" class="form-input" maxlength="255" value="{{ old('TYRO_LOGIN_CAPTCHA_LABEL', $settings['TYRO_LOGIN_CAPTCHA_LABEL']) }}">
                                </div>
                                <div class="form-group" style="margin-bottom:0.85rem;">
                                    <label for="TYRO_LOGIN_CAPTCHA_PLACEHOLDER" class="form-label">Placeholder <span class="env-key">TYRO_LOGIN_CAPTCHA_PLACEHOLDER</span></label>
                                    <input type="text" name="TYRO_LOGIN_CAPTCHA_PLACEHOLDER" id="TYRO_LOGIN_CAPTCHA_PLACEHOLDER" class="form-input" maxlength="255" value="{{ old('TYRO_LOGIN_CAPTCHA_PLACEHOLDER', $settings['TYRO_LOGIN_CAPTCHA_PLACEHOLDER']) }}">
                                </div>
                                <div class="form-group" style="margin-bottom:0.85rem;">
                                    <label for="TYRO_LOGIN_CAPTCHA_ERROR" class="form-label">Error message <span class="env-key">TYRO_LOGIN_CAPTCHA_ERROR</span></label>
                                    <input type="text" name="TYRO_LOGIN_CAPTCHA_ERROR" id="TYRO_LOGIN_CAPTCHA_ERROR" class="form-input" maxlength="255" value="{{ old('TYRO_LOGIN_CAPTCHA_ERROR', $settings['TYRO_LOGIN_CAPTCHA_ERROR']) }}">
                                </div>
                                <div class="sys-settings-metrics" style="margin-bottom:0;">
                                    <div class="form-group sys-settings-metric" style="margin-bottom:0;">
                                        <label for="TYRO_LOGIN_CAPTCHA_MIN" class="form-label">Min number <span class="env-key">TYRO_LOGIN_CAPTCHA_MIN</span></label>
                                        <input type="number" name="TYRO_LOGIN_CAPTCHA_MIN" id="TYRO_LOGIN_CAPTCHA_MIN" class="form-input" min="0" max="100" value="{{ old('TYRO_LOGIN_CAPTCHA_MIN', $settings['TYRO_LOGIN_CAPTCHA_MIN']) }}">
                                    </div>
                                    <div class="form-group sys-settings-metric" style="margin-bottom:0;">
                                        <label for="TYRO_LOGIN_CAPTCHA_MAX" class="form-label">Max number <span class="env-key">TYRO_LOGIN_CAPTCHA_MAX</span></label>
                                        <input type="number" name="TYRO_LOGIN_CAPTCHA_MAX" id="TYRO_LOGIN_CAPTCHA_MAX" class="form-input" min="1" max="1000" value="{{ old('TYRO_LOGIN_CAPTCHA_MAX', $settings['TYRO_LOGIN_CAPTCHA_MAX']) }}">
                                    </div>
                                </div>
                            </div>

                            <div class="sys-settings-surface" id="otp-details-surface">
                                <h4 class="sys-settings-surface-title">One-time passcode (OTP)</h4>
                                <p class="sys-settings-surface-description">OTP length, expiry, resend limits, and page text. Visible when OTP is enabled.</p>

                                <div class="sys-settings-metrics" style="margin-bottom:0.85rem;">
                                    <div class="form-group sys-settings-metric" style="margin-bottom:0;">
                                        <label for="TYRO_LOGIN_OTP_LENGTH" class="form-label">OTP length <span class="env-key">TYRO_LOGIN_OTP_LENGTH</span></label>
                                        <input type="number" name="TYRO_LOGIN_OTP_LENGTH" id="TYRO_LOGIN_OTP_LENGTH" class="form-input" min="4" max="8" value="{{ old('TYRO_LOGIN_OTP_LENGTH', $settings['TYRO_LOGIN_OTP_LENGTH']) }}">
                                    </div>
                                    <div class="form-group sys-settings-metric" style="margin-bottom:0;">
                                        <label for="TYRO_LOGIN_OTP_EXPIRE" class="form-label">OTP expire (min) <span class="env-key">TYRO_LOGIN_OTP_EXPIRE</span></label>
                                        <input type="number" name="TYRO_LOGIN_OTP_EXPIRE" id="TYRO_LOGIN_OTP_EXPIRE" class="form-input" min="1" max="60" value="{{ old('TYRO_LOGIN_OTP_EXPIRE', $settings['TYRO_LOGIN_OTP_EXPIRE']) }}">
                                    </div>
                                </div>
                                <div class="sys-settings-metrics" style="margin-bottom:0.85rem;">
                                    <div class="form-group sys-settings-metric" style="margin-bottom:0;">
                                        <label for="TYRO_LOGIN_OTP_MAX_RESEND" class="form-label">Max resend <span class="env-key">TYRO_LOGIN_OTP_MAX_RESEND</span></label>
                                        <input type="number" name="TYRO_LOGIN_OTP_MAX_RESEND" id="TYRO_LOGIN_OTP_MAX_RESEND" class="form-input" min="1" max="20" value="{{ old('TYRO_LOGIN_OTP_MAX_RESEND', $settings['TYRO_LOGIN_OTP_MAX_RESEND']) }}">
                                    </div>
                                    <div class="form-group sys-settings-metric" style="margin-bottom:0;">
                                        <label for="TYRO_LOGIN_OTP_RESEND_COOLDOWN" class="form-label">Resend cooldown (sec) <span class="env-key">TYRO_LOGIN_OTP_RESEND_COOLDOWN</span></label>
                                        <input type="number" name="TYRO_LOGIN_OTP_RESEND_COOLDOWN" id="TYRO_LOGIN_OTP_RESEND_COOLDOWN" class="form-input" min="10" max="600" value="{{ old('TYRO_LOGIN_OTP_RESEND_COOLDOWN', $settings['TYRO_LOGIN_OTP_RESEND_COOLDOWN']) }}">
                                    </div>
                                </div>
                                <div class="form-group" style="margin-bottom:0.85rem;">
                                    <label for="TYRO_LOGIN_OTP_TITLE" class="form-label">Page title <span class="env-key">TYRO_LOGIN_OTP_TITLE</span></label>
                                    <input type="text" name="TYRO_LOGIN_OTP_TITLE" id="TYRO_LOGIN_OTP_TITLE" class="form-input" maxlength="255" value="{{ old('TYRO_LOGIN_OTP_TITLE', $settings['TYRO_LOGIN_OTP_TITLE']) }}">
                                </div>
                                <div class="form-group" style="margin-bottom:0.85rem;">
                                    <label for="TYRO_LOGIN_OTP_SUBTITLE" class="form-label">Page subtitle <span class="env-key">TYRO_LOGIN_OTP_SUBTITLE</span></label>
                                    <input type="text" name="TYRO_LOGIN_OTP_SUBTITLE" id="TYRO_LOGIN_OTP_SUBTITLE" class="form-input" maxlength="500" value="{{ old('TYRO_LOGIN_OTP_SUBTITLE', $settings['TYRO_LOGIN_OTP_SUBTITLE']) }}">
                                    <p class="form-hint">Supports <code>:length</code> and <code>:email</code> placeholders.</p>
                                </div>
                                <div class="form-group" style="margin-bottom:0.85rem;">
                                    <label for="TYRO_LOGIN_OTP_LABEL" class="form-label">Input label <span class="env-key">TYRO_LOGIN_OTP_LABEL</span></label>
                                    <input type="text" name="TYRO_LOGIN_OTP_LABEL" id="TYRO_LOGIN_OTP_LABEL" class="form-input" maxlength="255" value="{{ old('TYRO_LOGIN_OTP_LABEL', $settings['TYRO_LOGIN_OTP_LABEL']) }}">
                                </div>
                                <div class="form-group" style="margin-bottom:0.85rem;">
                                    <label for="TYRO_LOGIN_OTP_PLACEHOLDER" class="form-label">Input placeholder <span class="env-key">TYRO_LOGIN_OTP_PLACEHOLDER</span></label>
                                    <input type="text" name="TYRO_LOGIN_OTP_PLACEHOLDER" id="TYRO_LOGIN_OTP_PLACEHOLDER" class="form-input" maxlength="255" value="{{ old('TYRO_LOGIN_OTP_PLACEHOLDER', $settings['TYRO_LOGIN_OTP_PLACEHOLDER']) }}">
                                </div>
                                <div class="sys-settings-metrics" style="margin-bottom:0.85rem;">
                                    <div class="form-group sys-settings-metric" style="margin-bottom:0;">
                                        <label for="TYRO_LOGIN_OTP_SUBMIT_BUTTON" class="form-label">Submit button <span class="env-key">TYRO_LOGIN_OTP_SUBMIT_BUTTON</span></label>
                                        <input type="text" name="TYRO_LOGIN_OTP_SUBMIT_BUTTON" id="TYRO_LOGIN_OTP_SUBMIT_BUTTON" class="form-input" maxlength="100" value="{{ old('TYRO_LOGIN_OTP_SUBMIT_BUTTON', $settings['TYRO_LOGIN_OTP_SUBMIT_BUTTON']) }}">
                                    </div>
                                    <div class="form-group sys-settings-metric" style="margin-bottom:0;">
                                        <label for="TYRO_LOGIN_OTP_RESEND_BUTTON" class="form-label">Resend button <span class="env-key">TYRO_LOGIN_OTP_RESEND_BUTTON</span></label>
                                        <input type="text" name="TYRO_LOGIN_OTP_RESEND_BUTTON" id="TYRO_LOGIN_OTP_RESEND_BUTTON" class="form-input" maxlength="100" value="{{ old('TYRO_LOGIN_OTP_RESEND_BUTTON', $settings['TYRO_LOGIN_OTP_RESEND_BUTTON']) }}">
                                    </div>
                                </div>
                                <div class="form-group" style="margin-bottom:0.85rem;">
                                    <label for="TYRO_LOGIN_OTP_ERROR" class="form-label">Error message <span class="env-key">TYRO_LOGIN_OTP_ERROR</span></label>
                                    <input type="text" name="TYRO_LOGIN_OTP_ERROR" id="TYRO_LOGIN_OTP_ERROR" class="form-input" maxlength="500" value="{{ old('TYRO_LOGIN_OTP_ERROR', $settings['TYRO_LOGIN_OTP_ERROR']) }}">
                                </div>
                                <div class="form-group" style="margin-bottom:0.85rem;">
                                    <label for="TYRO_LOGIN_OTP_RESEND_SUCCESS" class="form-label">Resend success message <span class="env-key">TYRO_LOGIN_OTP_RESEND_SUCCESS</span></label>
                                    <input type="text" name="TYRO_LOGIN_OTP_RESEND_SUCCESS" id="TYRO_LOGIN_OTP_RESEND_SUCCESS" class="form-input" maxlength="500" value="{{ old('TYRO_LOGIN_OTP_RESEND_SUCCESS', $settings['TYRO_LOGIN_OTP_RESEND_SUCCESS']) }}">
                                </div>
                                <div class="form-group" style="margin-bottom:0.85rem;">
                                    <label for="TYRO_LOGIN_OTP_MAX_RESEND_ERROR" class="form-label">Max resend error <span class="env-key">TYRO_LOGIN_OTP_MAX_RESEND_ERROR</span></label>
                                    <input type="text" name="TYRO_LOGIN_OTP_MAX_RESEND_ERROR" id="TYRO_LOGIN_OTP_MAX_RESEND_ERROR" class="form-input" maxlength="500" value="{{ old('TYRO_LOGIN_OTP_MAX_RESEND_ERROR', $settings['TYRO_LOGIN_OTP_MAX_RESEND_ERROR']) }}">
                                </div>
                                <div class="form-group" style="margin-bottom:0.85rem;">
                                    <label for="TYRO_LOGIN_OTP_BG_TITLE" class="form-label">Background title <span class="env-key">TYRO_LOGIN_OTP_BG_TITLE</span></label>
                                    <input type="text" name="TYRO_LOGIN_OTP_BG_TITLE" id="TYRO_LOGIN_OTP_BG_TITLE" class="form-input" maxlength="255" value="{{ old('TYRO_LOGIN_OTP_BG_TITLE', $settings['TYRO_LOGIN_OTP_BG_TITLE']) }}">
                                </div>
                                <div class="form-group" style="margin-bottom:0;">
                                    <label for="TYRO_LOGIN_OTP_BG_DESCRIPTION" class="form-label">Background description <span class="env-key">TYRO_LOGIN_OTP_BG_DESCRIPTION</span></label>
                                    <input type="text" name="TYRO_LOGIN_OTP_BG_DESCRIPTION" id="TYRO_LOGIN_OTP_BG_DESCRIPTION" class="form-input" maxlength="500" value="{{ old('TYRO_LOGIN_OTP_BG_DESCRIPTION', $settings['TYRO_LOGIN_OTP_BG_DESCRIPTION']) }}">
                                </div>
                            </div>

                            <div class="sys-settings-surface" id="twofa-details-surface">
                                <h4 class="sys-settings-surface-title">Two-factor authentication (2FA)</h4>
                                <p class="sys-settings-surface-description">2FA page text, cookie settings, and forced/skip roles. Visible when 2FA is enabled.</p>

                                <div class="form-group" style="margin-bottom:0.85rem;">
                                    <label for="TYRO_LOGIN_2FA_SETUP_TITLE" class="form-label">Setup page title <span class="env-key">TYRO_LOGIN_2FA_SETUP_TITLE</span></label>
                                    <input type="text" name="TYRO_LOGIN_2FA_SETUP_TITLE" id="TYRO_LOGIN_2FA_SETUP_TITLE" class="form-input" maxlength="255" value="{{ old('TYRO_LOGIN_2FA_SETUP_TITLE', $settings['TYRO_LOGIN_2FA_SETUP_TITLE']) }}">
                                </div>
                                <div class="form-group" style="margin-bottom:0.85rem;">
                                    <label for="TYRO_LOGIN_2FA_SETUP_SUBTITLE" class="form-label">Setup page subtitle <span class="env-key">TYRO_LOGIN_2FA_SETUP_SUBTITLE</span></label>
                                    <input type="text" name="TYRO_LOGIN_2FA_SETUP_SUBTITLE" id="TYRO_LOGIN_2FA_SETUP_SUBTITLE" class="form-input" maxlength="500" value="{{ old('TYRO_LOGIN_2FA_SETUP_SUBTITLE', $settings['TYRO_LOGIN_2FA_SETUP_SUBTITLE']) }}">
                                </div>
                                <div class="form-group" style="margin-bottom:0.85rem;">
                                    <label for="TYRO_LOGIN_2FA_CHALLENGE_TITLE" class="form-label">Challenge page title <span class="env-key">TYRO_LOGIN_2FA_CHALLENGE_TITLE</span></label>
                                    <input type="text" name="TYRO_LOGIN_2FA_CHALLENGE_TITLE" id="TYRO_LOGIN_2FA_CHALLENGE_TITLE" class="form-input" maxlength="255" value="{{ old('TYRO_LOGIN_2FA_CHALLENGE_TITLE', $settings['TYRO_LOGIN_2FA_CHALLENGE_TITLE']) }}">
                                </div>
                                <div class="form-group" style="margin-bottom:0.85rem;">
                                    <label for="TYRO_LOGIN_2FA_CHALLENGE_SUBTITLE" class="form-label">Challenge page subtitle <span class="env-key">TYRO_LOGIN_2FA_CHALLENGE_SUBTITLE</span></label>
                                    <input type="text" name="TYRO_LOGIN_2FA_CHALLENGE_SUBTITLE" id="TYRO_LOGIN_2FA_CHALLENGE_SUBTITLE" class="form-input" maxlength="500" value="{{ old('TYRO_LOGIN_2FA_CHALLENGE_SUBTITLE', $settings['TYRO_LOGIN_2FA_CHALLENGE_SUBTITLE']) }}">
                                </div>
                                <div class="sys-settings-toggle" style="margin-bottom:0.85rem;">
                                    <div class="sys-settings-toggle-top">
                                        <div>
                                            <p class="sys-settings-toggle-title">Allow users to skip 2FA setup <span class="env-key">TYRO_LOGIN_2FA_ALLOW_SKIP</span></p>
                                                <p class="sys-settings-toggle-description">Lets users postpone or skip the two-factor authentication setup process if they prefer not to set it up.</p>
                                        </div>
                                        <div>
                                            <input type="hidden" name="TYRO_LOGIN_2FA_ALLOW_SKIP" value="0">
                                            <label class="toggle-label">
                                                <input type="checkbox" name="TYRO_LOGIN_2FA_ALLOW_SKIP" value="1" class="toggle-input" {{ old('TYRO_LOGIN_2FA_ALLOW_SKIP', $settings['TYRO_LOGIN_2FA_ALLOW_SKIP']) ? 'checked' : '' }}>
                                                <span class="toggle-slider"></span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group" style="margin-bottom:0.85rem;">
                                    <label for="TYRO_LOGIN_2FA_IGNORE_COOKIE_DAYS" class="form-label">Skip cookie days <span class="env-key">TYRO_LOGIN_2FA_IGNORE_COOKIE_DAYS</span></label>
                                    <input type="number" name="TYRO_LOGIN_2FA_IGNORE_COOKIE_DAYS" id="TYRO_LOGIN_2FA_IGNORE_COOKIE_DAYS" class="form-input" min="1" max="365" value="{{ old('TYRO_LOGIN_2FA_IGNORE_COOKIE_DAYS', $settings['TYRO_LOGIN_2FA_IGNORE_COOKIE_DAYS']) }}">
                                </div>
                                @php
                                    $roleOptions = $roleOptions ?? [];
                                    $forcedRoleList = array_map('strval', (array) old('TYRO_LOGIN_2FA_FORCED_ROLES', array_filter(array_map('trim', explode(',', (string) $settings['TYRO_LOGIN_2FA_FORCED_ROLES'])))));
                                    $skipRoleList = array_map('strval', (array) old('TYRO_LOGIN_2FA_SKIP_ROLES', array_filter(array_map('trim', explode(',', (string) $settings['TYRO_LOGIN_2FA_SKIP_ROLES'])))));
                                @endphp
                                <div class="form-group" style="margin-bottom:0.85rem;">
                                    <label class="form-label">Forced roles <span class="env-key">TYRO_LOGIN_2FA_FORCED_ROLES</span></label>
                                    <input type="hidden" name="TYRO_LOGIN_2FA_FORCED_ROLES[]" value="">
                                    <div class="sys-settings-checkbox-grid" style="display:flex;flex-wrap:wrap;gap:0.5rem 1.25rem;margin:0.35rem 0 0.25rem;">
                                        @forelse($roleOptions as $roleSlug => $roleLabel)
                                        <label class="form-check" style="display:inline-flex;align-items:center;gap:0.4rem;margin:0;font-weight:normal;">
                                            <input type="checkbox" name="TYRO_LOGIN_2FA_FORCED_ROLES[]" value="{{ $roleSlug }}" {{ in_array((string) $roleSlug, $forcedRoleList, true) ? 'checked' : '' }}>
                                            <span>{{ $roleLabel === $roleSlug ? $roleSlug : $roleLabel.' ('.$roleSlug.')' }}</span>
                                        </label>
                                        @empty
                                        <p class="form-hint" style="margin:0;">No roles found. Create roles before configuring forced roles.</p>
                                        @endforelse
                                    </div>
                                    <p class="form-hint">Roles required to use 2FA. Forced roles take precedence over skip roles.</p>
                                </div>
                                <div class="form-group" style="margin-bottom:0;">
                                    <label class="form-label">Skip roles <span class="env-key">TYRO_LOGIN_2FA_SKIP_ROLES</span></label>
                                    <input type="hidden" name="TYRO_LOGIN_2FA_SKIP_ROLES[]" value="">
                                    <div class="sys-settings-checkbox-grid" style="display:flex;flex-wrap:wrap;gap:0.5rem 1.25rem;margin:0.35rem 0 0.25rem;">
                                        @forelse($roleOptions as $roleSlug => $roleLabel)
                                        <label class="form-check" style="display:inline-flex;align-items:center;gap:0.4rem;margin:0;font-weight:normal;">
                                            <input type="checkbox" name="TYRO_LOGIN_2FA_SKIP_ROLES[]" value="{{ $roleSlug }}" {{ in_array((string) $roleSlug, $skipRoleList, true) ? 'checked' : '' }}>
                                            <span>{{ $roleLabel === $roleSlug ? $roleSlug : $roleLabel.' ('.$roleSlug.')' }}</span>
                                        </label>
                                        @empty
                                        <p class="form-hint" style="margin:0;">No roles found. Create roles before configuring skip roles.</p>
                                        @endforelse
                                    </div>
                                    <p class="form-hint">Roles never prompted to set up 2FA (comma-separated in <code>.env</code>). Forced roles always take precedence.</p>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
