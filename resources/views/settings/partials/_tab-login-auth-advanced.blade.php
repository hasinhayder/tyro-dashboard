            {{-- Login & Auth Advanced Tab --}}
            <div class="vtabs-panel" id="vtab-login-auth-advanced">
                <div class="card">
                    <div class="card-header" style="display:flex;align-items:center;justify-content:space-between;gap:1rem;">
                        <h3 class="card-title">Login &amp; Auth Advanced</h3>
                        <button type="submit" form="systemSettingsForm" class="btn btn-primary btn-sm section-save-button">Save</button>
                    </div>
                    <div class="card-body">
                        <div class="sys-settings-section-intro">
                            <div class="sys-settings-section-copy">
                                <h4 class="sys-settings-section-heading">Advanced Login &amp; Auth environment configuration</h4>
                                <p class="sys-settings-section-description">Fine-tune branding, captcha, OTP, 2FA, social providers, lockout, email subjects, and page content.</p>
                            </div>
                            <span class="sys-settings-section-badge">Advanced</span>
                        </div>

                        <div class="sys-settings-grid">
                            <div class="sys-settings-surface">
                                <h4 class="sys-settings-surface-title">Registration Details</h4>
                                <p class="sys-settings-surface-description">Additional registration and login behaviour.</p>

                                <div class="sys-settings-toggles" style="margin-bottom:0;">
                                    <div class="sys-settings-toggle">
                                        <div class="sys-settings-toggle-top">
                                            <div>
                                                <p class="sys-settings-toggle-title">Auto-login after registration <span style="font-weight:normal">(<code>TYRO_LOGIN_REGISTRATION_AUTO_LOGIN</code>)</span></p>
                                                <p class="sys-settings-toggle-description">Automatically signs in users right after they complete the registration process.</p>
                                            </div>
                                            <div>
                                                <input type="hidden" name="TYRO_LOGIN_REGISTRATION_AUTO_LOGIN" value="0">
                                                <label class="toggle-label">
                                                    <input type="checkbox" name="TYRO_LOGIN_REGISTRATION_AUTO_LOGIN" value="1" class="toggle-input" {{ old('TYRO_LOGIN_REGISTRATION_AUTO_LOGIN', $settings['TYRO_LOGIN_REGISTRATION_AUTO_LOGIN']) ? 'checked' : '' }}>
                                                    <span class="toggle-slider"></span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="sys-settings-toggle">
                                        <div class="sys-settings-toggle-top">
                                            <div>
                                                <p class="sys-settings-toggle-title">Disable password login <span style="font-weight:normal">(<code>TYRO_LOGIN_DISABLE_PASSWORD</code>)</span></p>
                                                <p class="sys-settings-toggle-description">Hides the password field, remember me checkbox, and forgot password link on the login form for passwordless setups.</p>
                                            </div>
                                            <div>
                                                <input type="hidden" name="TYRO_LOGIN_DISABLE_PASSWORD" value="0">
                                                <label class="toggle-label">
                                                    <input type="checkbox" name="TYRO_LOGIN_DISABLE_PASSWORD" value="1" class="toggle-input" {{ old('TYRO_LOGIN_DISABLE_PASSWORD', $settings['TYRO_LOGIN_DISABLE_PASSWORD']) ? 'checked' : '' }}>
                                                    <span class="toggle-slider"></span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="sys-settings-surface" id="captcha-details-surface">
                                <h4 class="sys-settings-surface-title">Captcha Details</h4>
                                <p class="sys-settings-surface-description">Captcha text labels and number range. Visible when captcha is enabled.</p>

                                <div class="form-group" style="margin-bottom:0.85rem;">
                                    <label for="TYRO_LOGIN_CAPTCHA_LABEL" class="form-label">Label (TYRO_LOGIN_CAPTCHA_LABEL)</label>
                                    <input type="text" name="TYRO_LOGIN_CAPTCHA_LABEL" id="TYRO_LOGIN_CAPTCHA_LABEL" class="form-input" maxlength="255" value="{{ old('TYRO_LOGIN_CAPTCHA_LABEL', $settings['TYRO_LOGIN_CAPTCHA_LABEL']) }}">
                                </div>
                                <div class="form-group" style="margin-bottom:0.85rem;">
                                    <label for="TYRO_LOGIN_CAPTCHA_PLACEHOLDER" class="form-label">Placeholder (TYRO_LOGIN_CAPTCHA_PLACEHOLDER)</label>
                                    <input type="text" name="TYRO_LOGIN_CAPTCHA_PLACEHOLDER" id="TYRO_LOGIN_CAPTCHA_PLACEHOLDER" class="form-input" maxlength="255" value="{{ old('TYRO_LOGIN_CAPTCHA_PLACEHOLDER', $settings['TYRO_LOGIN_CAPTCHA_PLACEHOLDER']) }}">
                                </div>
                                <div class="form-group" style="margin-bottom:0.85rem;">
                                    <label for="TYRO_LOGIN_CAPTCHA_ERROR" class="form-label">Error message (TYRO_LOGIN_CAPTCHA_ERROR)</label>
                                    <input type="text" name="TYRO_LOGIN_CAPTCHA_ERROR" id="TYRO_LOGIN_CAPTCHA_ERROR" class="form-input" maxlength="255" value="{{ old('TYRO_LOGIN_CAPTCHA_ERROR', $settings['TYRO_LOGIN_CAPTCHA_ERROR']) }}">
                                </div>
                                <div class="sys-settings-metrics" style="margin-bottom:0;">
                                    <div class="form-group sys-settings-metric" style="margin-bottom:0;">
                                        <label for="TYRO_LOGIN_CAPTCHA_MIN" class="form-label">Min number (TYRO_LOGIN_CAPTCHA_MIN)</label>
                                        <input type="number" name="TYRO_LOGIN_CAPTCHA_MIN" id="TYRO_LOGIN_CAPTCHA_MIN" class="form-input" min="0" max="100" value="{{ old('TYRO_LOGIN_CAPTCHA_MIN', $settings['TYRO_LOGIN_CAPTCHA_MIN']) }}">
                                    </div>
                                    <div class="form-group sys-settings-metric" style="margin-bottom:0;">
                                        <label for="TYRO_LOGIN_CAPTCHA_MAX" class="form-label">Max number (TYRO_LOGIN_CAPTCHA_MAX)</label>
                                        <input type="number" name="TYRO_LOGIN_CAPTCHA_MAX" id="TYRO_LOGIN_CAPTCHA_MAX" class="form-input" min="1" max="1000" value="{{ old('TYRO_LOGIN_CAPTCHA_MAX', $settings['TYRO_LOGIN_CAPTCHA_MAX']) }}">
                                    </div>
                                </div>
                            </div>

                            <div class="sys-settings-surface" id="otp-details-surface">
                                <h4 class="sys-settings-surface-title">OTP Details</h4>
                                <p class="sys-settings-surface-description">OTP length, expiry, resend limits, and page text. Visible when OTP is enabled.</p>

                                <div class="sys-settings-metrics" style="margin-bottom:0.85rem;">
                                    <div class="form-group sys-settings-metric" style="margin-bottom:0;">
                                        <label for="TYRO_LOGIN_OTP_LENGTH" class="form-label">OTP length (TYRO_LOGIN_OTP_LENGTH)</label>
                                        <input type="number" name="TYRO_LOGIN_OTP_LENGTH" id="TYRO_LOGIN_OTP_LENGTH" class="form-input" min="4" max="8" value="{{ old('TYRO_LOGIN_OTP_LENGTH', $settings['TYRO_LOGIN_OTP_LENGTH']) }}">
                                    </div>
                                    <div class="form-group sys-settings-metric" style="margin-bottom:0;">
                                        <label for="TYRO_LOGIN_OTP_EXPIRE" class="form-label">OTP expire (min) (TYRO_LOGIN_OTP_EXPIRE)</label>
                                        <input type="number" name="TYRO_LOGIN_OTP_EXPIRE" id="TYRO_LOGIN_OTP_EXPIRE" class="form-input" min="1" max="60" value="{{ old('TYRO_LOGIN_OTP_EXPIRE', $settings['TYRO_LOGIN_OTP_EXPIRE']) }}">
                                    </div>
                                </div>
                                <div class="sys-settings-metrics" style="margin-bottom:0.85rem;">
                                    <div class="form-group sys-settings-metric" style="margin-bottom:0;">
                                        <label for="TYRO_LOGIN_OTP_MAX_RESEND" class="form-label">Max resend (TYRO_LOGIN_OTP_MAX_RESEND)</label>
                                        <input type="number" name="TYRO_LOGIN_OTP_MAX_RESEND" id="TYRO_LOGIN_OTP_MAX_RESEND" class="form-input" min="1" max="20" value="{{ old('TYRO_LOGIN_OTP_MAX_RESEND', $settings['TYRO_LOGIN_OTP_MAX_RESEND']) }}">
                                    </div>
                                    <div class="form-group sys-settings-metric" style="margin-bottom:0;">
                                        <label for="TYRO_LOGIN_OTP_RESEND_COOLDOWN" class="form-label">Resend cooldown (sec) (TYRO_LOGIN_OTP_RESEND_COOLDOWN)</label>
                                        <input type="number" name="TYRO_LOGIN_OTP_RESEND_COOLDOWN" id="TYRO_LOGIN_OTP_RESEND_COOLDOWN" class="form-input" min="10" max="600" value="{{ old('TYRO_LOGIN_OTP_RESEND_COOLDOWN', $settings['TYRO_LOGIN_OTP_RESEND_COOLDOWN']) }}">
                                    </div>
                                </div>
                                <div class="form-group" style="margin-bottom:0.85rem;">
                                    <label for="TYRO_LOGIN_OTP_TITLE" class="form-label">Page title (TYRO_LOGIN_OTP_TITLE)</label>
                                    <input type="text" name="TYRO_LOGIN_OTP_TITLE" id="TYRO_LOGIN_OTP_TITLE" class="form-input" maxlength="255" value="{{ old('TYRO_LOGIN_OTP_TITLE', $settings['TYRO_LOGIN_OTP_TITLE']) }}">
                                </div>
                                <div class="form-group" style="margin-bottom:0.85rem;">
                                    <label for="TYRO_LOGIN_OTP_SUBTITLE" class="form-label">Page subtitle (TYRO_LOGIN_OTP_SUBTITLE)</label>
                                    <input type="text" name="TYRO_LOGIN_OTP_SUBTITLE" id="TYRO_LOGIN_OTP_SUBTITLE" class="form-input" maxlength="500" value="{{ old('TYRO_LOGIN_OTP_SUBTITLE', $settings['TYRO_LOGIN_OTP_SUBTITLE']) }}">
                                    <p class="form-hint">Supports <code>:length</code> and <code>:email</code> placeholders.</p>
                                </div>
                                <div class="form-group" style="margin-bottom:0.85rem;">
                                    <label for="TYRO_LOGIN_OTP_LABEL" class="form-label">Input label (TYRO_LOGIN_OTP_LABEL)</label>
                                    <input type="text" name="TYRO_LOGIN_OTP_LABEL" id="TYRO_LOGIN_OTP_LABEL" class="form-input" maxlength="255" value="{{ old('TYRO_LOGIN_OTP_LABEL', $settings['TYRO_LOGIN_OTP_LABEL']) }}">
                                </div>
                                <div class="form-group" style="margin-bottom:0.85rem;">
                                    <label for="TYRO_LOGIN_OTP_PLACEHOLDER" class="form-label">Input placeholder (TYRO_LOGIN_OTP_PLACEHOLDER)</label>
                                    <input type="text" name="TYRO_LOGIN_OTP_PLACEHOLDER" id="TYRO_LOGIN_OTP_PLACEHOLDER" class="form-input" maxlength="255" value="{{ old('TYRO_LOGIN_OTP_PLACEHOLDER', $settings['TYRO_LOGIN_OTP_PLACEHOLDER']) }}">
                                </div>
                                <div class="sys-settings-metrics" style="margin-bottom:0.85rem;">
                                    <div class="form-group sys-settings-metric" style="margin-bottom:0;">
                                        <label for="TYRO_LOGIN_OTP_SUBMIT_BUTTON" class="form-label">Submit button (TYRO_LOGIN_OTP_SUBMIT_BUTTON)</label>
                                        <input type="text" name="TYRO_LOGIN_OTP_SUBMIT_BUTTON" id="TYRO_LOGIN_OTP_SUBMIT_BUTTON" class="form-input" maxlength="100" value="{{ old('TYRO_LOGIN_OTP_SUBMIT_BUTTON', $settings['TYRO_LOGIN_OTP_SUBMIT_BUTTON']) }}">
                                    </div>
                                    <div class="form-group sys-settings-metric" style="margin-bottom:0;">
                                        <label for="TYRO_LOGIN_OTP_RESEND_BUTTON" class="form-label">Resend button (TYRO_LOGIN_OTP_RESEND_BUTTON)</label>
                                        <input type="text" name="TYRO_LOGIN_OTP_RESEND_BUTTON" id="TYRO_LOGIN_OTP_RESEND_BUTTON" class="form-input" maxlength="100" value="{{ old('TYRO_LOGIN_OTP_RESEND_BUTTON', $settings['TYRO_LOGIN_OTP_RESEND_BUTTON']) }}">
                                    </div>
                                </div>
                                <div class="form-group" style="margin-bottom:0.85rem;">
                                    <label for="TYRO_LOGIN_OTP_ERROR" class="form-label">Error message (TYRO_LOGIN_OTP_ERROR)</label>
                                    <input type="text" name="TYRO_LOGIN_OTP_ERROR" id="TYRO_LOGIN_OTP_ERROR" class="form-input" maxlength="500" value="{{ old('TYRO_LOGIN_OTP_ERROR', $settings['TYRO_LOGIN_OTP_ERROR']) }}">
                                </div>
                                <div class="form-group" style="margin-bottom:0.85rem;">
                                    <label for="TYRO_LOGIN_OTP_RESEND_SUCCESS" class="form-label">Resend success message (TYRO_LOGIN_OTP_RESEND_SUCCESS)</label>
                                    <input type="text" name="TYRO_LOGIN_OTP_RESEND_SUCCESS" id="TYRO_LOGIN_OTP_RESEND_SUCCESS" class="form-input" maxlength="500" value="{{ old('TYRO_LOGIN_OTP_RESEND_SUCCESS', $settings['TYRO_LOGIN_OTP_RESEND_SUCCESS']) }}">
                                </div>
                                <div class="form-group" style="margin-bottom:0.85rem;">
                                    <label for="TYRO_LOGIN_OTP_MAX_RESEND_ERROR" class="form-label">Max resend error (TYRO_LOGIN_OTP_MAX_RESEND_ERROR)</label>
                                    <input type="text" name="TYRO_LOGIN_OTP_MAX_RESEND_ERROR" id="TYRO_LOGIN_OTP_MAX_RESEND_ERROR" class="form-input" maxlength="500" value="{{ old('TYRO_LOGIN_OTP_MAX_RESEND_ERROR', $settings['TYRO_LOGIN_OTP_MAX_RESEND_ERROR']) }}">
                                </div>
                                <div class="form-group" style="margin-bottom:0.85rem;">
                                    <label for="TYRO_LOGIN_OTP_BG_TITLE" class="form-label">Background title (TYRO_LOGIN_OTP_BG_TITLE)</label>
                                    <input type="text" name="TYRO_LOGIN_OTP_BG_TITLE" id="TYRO_LOGIN_OTP_BG_TITLE" class="form-input" maxlength="255" value="{{ old('TYRO_LOGIN_OTP_BG_TITLE', $settings['TYRO_LOGIN_OTP_BG_TITLE']) }}">
                                </div>
                                <div class="form-group" style="margin-bottom:0;">
                                    <label for="TYRO_LOGIN_OTP_BG_DESCRIPTION" class="form-label">Background description (TYRO_LOGIN_OTP_BG_DESCRIPTION)</label>
                                    <input type="text" name="TYRO_LOGIN_OTP_BG_DESCRIPTION" id="TYRO_LOGIN_OTP_BG_DESCRIPTION" class="form-input" maxlength="500" value="{{ old('TYRO_LOGIN_OTP_BG_DESCRIPTION', $settings['TYRO_LOGIN_OTP_BG_DESCRIPTION']) }}">
                                </div>
                            </div>

                            <div class="sys-settings-surface" id="twofa-details-surface">
                                <h4 class="sys-settings-surface-title">2FA Details</h4>
                                <p class="sys-settings-surface-description">2FA page text, cookie settings, and forced roles. Visible when 2FA is enabled.</p>

                                <div class="form-group" style="margin-bottom:0.85rem;">
                                    <label for="TYRO_LOGIN_2FA_SETUP_TITLE" class="form-label">Setup page title (TYRO_LOGIN_2FA_SETUP_TITLE)</label>
                                    <input type="text" name="TYRO_LOGIN_2FA_SETUP_TITLE" id="TYRO_LOGIN_2FA_SETUP_TITLE" class="form-input" maxlength="255" value="{{ old('TYRO_LOGIN_2FA_SETUP_TITLE', $settings['TYRO_LOGIN_2FA_SETUP_TITLE']) }}">
                                </div>
                                <div class="form-group" style="margin-bottom:0.85rem;">
                                    <label for="TYRO_LOGIN_2FA_SETUP_SUBTITLE" class="form-label">Setup page subtitle (TYRO_LOGIN_2FA_SETUP_SUBTITLE)</label>
                                    <input type="text" name="TYRO_LOGIN_2FA_SETUP_SUBTITLE" id="TYRO_LOGIN_2FA_SETUP_SUBTITLE" class="form-input" maxlength="500" value="{{ old('TYRO_LOGIN_2FA_SETUP_SUBTITLE', $settings['TYRO_LOGIN_2FA_SETUP_SUBTITLE']) }}">
                                </div>
                                <div class="form-group" style="margin-bottom:0.85rem;">
                                    <label for="TYRO_LOGIN_2FA_CHALLENGE_TITLE" class="form-label">Challenge page title (TYRO_LOGIN_2FA_CHALLENGE_TITLE)</label>
                                    <input type="text" name="TYRO_LOGIN_2FA_CHALLENGE_TITLE" id="TYRO_LOGIN_2FA_CHALLENGE_TITLE" class="form-input" maxlength="255" value="{{ old('TYRO_LOGIN_2FA_CHALLENGE_TITLE', $settings['TYRO_LOGIN_2FA_CHALLENGE_TITLE']) }}">
                                </div>
                                <div class="form-group" style="margin-bottom:0.85rem;">
                                    <label for="TYRO_LOGIN_2FA_CHALLENGE_SUBTITLE" class="form-label">Challenge page subtitle (TYRO_LOGIN_2FA_CHALLENGE_SUBTITLE)</label>
                                    <input type="text" name="TYRO_LOGIN_2FA_CHALLENGE_SUBTITLE" id="TYRO_LOGIN_2FA_CHALLENGE_SUBTITLE" class="form-input" maxlength="500" value="{{ old('TYRO_LOGIN_2FA_CHALLENGE_SUBTITLE', $settings['TYRO_LOGIN_2FA_CHALLENGE_SUBTITLE']) }}">
                                </div>
                                <div class="sys-settings-toggle" style="margin-bottom:0.85rem;">
                                    <div class="sys-settings-toggle-top">
                                        <div>
                                            <p class="sys-settings-toggle-title">Allow users to skip 2FA setup <span style="font-weight:normal">(<code>TYRO_LOGIN_2FA_ALLOW_SKIP</code>)</span></p>
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
                                    <label for="TYRO_LOGIN_2FA_IGNORE_COOKIE_DAYS" class="form-label">Skip cookie days (TYRO_LOGIN_2FA_IGNORE_COOKIE_DAYS)</label>
                                    <input type="number" name="TYRO_LOGIN_2FA_IGNORE_COOKIE_DAYS" id="TYRO_LOGIN_2FA_IGNORE_COOKIE_DAYS" class="form-input" min="1" max="365" value="{{ old('TYRO_LOGIN_2FA_IGNORE_COOKIE_DAYS', $settings['TYRO_LOGIN_2FA_IGNORE_COOKIE_DAYS']) }}">
                                </div>
                                <div class="form-group" style="margin-bottom:0;">
                                    <label for="TYRO_LOGIN_2FA_FORCED_ROLES" class="form-label">Forced roles (TYRO_LOGIN_2FA_FORCED_ROLES)</label>
                                    <input type="text" name="TYRO_LOGIN_2FA_FORCED_ROLES" id="TYRO_LOGIN_2FA_FORCED_ROLES" class="form-input" maxlength="255" value="{{ old('TYRO_LOGIN_2FA_FORCED_ROLES', $settings['TYRO_LOGIN_2FA_FORCED_ROLES']) }}">
                                    <p class="form-hint">Comma-separated role slugs that must use 2FA.</p>
                                </div>
                            </div>

                            <div class="sys-settings-surface" id="social-details-surface">
                                <h4 class="sys-settings-surface-title">Social Provider Details</h4>
                                <p class="sys-settings-surface-description">Enable individual social providers. Visible when social login is enabled.</p>

                                <div class="sys-settings-toggles" style="margin-bottom:0.85rem;">
                                    <div class="sys-settings-toggle">
                                        <div class="sys-settings-toggle-top">
                                            <div>
                                                <p class="sys-settings-toggle-title">Google <span style="font-weight:normal">(<code>TYRO_LOGIN_SOCIAL_GOOGLE</code>)</span></p>
                                                <p class="sys-settings-toggle-description">Allows users to sign in using their Google account.</p>
                                            </div>
                                            <div>
                                                <input type="hidden" name="TYRO_LOGIN_SOCIAL_GOOGLE" value="0">
                                                <label class="toggle-label">
                                                    <input type="checkbox" name="TYRO_LOGIN_SOCIAL_GOOGLE" value="1" class="toggle-input" {{ old('TYRO_LOGIN_SOCIAL_GOOGLE', $settings['TYRO_LOGIN_SOCIAL_GOOGLE']) ? 'checked' : '' }}>
                                                    <span class="toggle-slider"></span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="sys-settings-toggle">
                                        <div class="sys-settings-toggle-top">
                                            <div>
                                                <p class="sys-settings-toggle-title">Facebook <span style="font-weight:normal">(<code>TYRO_LOGIN_SOCIAL_FACEBOOK</code>)</span></p>
                                                <p class="sys-settings-toggle-description">Allows users to sign in using their Facebook account.</p>
                                            </div>
                                            <div>
                                                <input type="hidden" name="TYRO_LOGIN_SOCIAL_FACEBOOK" value="0">
                                                <label class="toggle-label">
                                                    <input type="checkbox" name="TYRO_LOGIN_SOCIAL_FACEBOOK" value="1" class="toggle-input" {{ old('TYRO_LOGIN_SOCIAL_FACEBOOK', $settings['TYRO_LOGIN_SOCIAL_FACEBOOK']) ? 'checked' : '' }}>
                                                    <span class="toggle-slider"></span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="sys-settings-toggle">
                                        <div class="sys-settings-toggle-top">
                                            <div>
                                                <p class="sys-settings-toggle-title">GitHub <span style="font-weight:normal">(<code>TYRO_LOGIN_SOCIAL_GITHUB</code>)</span></p>
                                                <p class="sys-settings-toggle-description">Allows users to sign in using their GitHub account.</p>
                                            </div>
                                            <div>
                                                <input type="hidden" name="TYRO_LOGIN_SOCIAL_GITHUB" value="0">
                                                <label class="toggle-label">
                                                    <input type="checkbox" name="TYRO_LOGIN_SOCIAL_GITHUB" value="1" class="toggle-input" {{ old('TYRO_LOGIN_SOCIAL_GITHUB', $settings['TYRO_LOGIN_SOCIAL_GITHUB']) ? 'checked' : '' }}>
                                                    <span class="toggle-slider"></span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="sys-settings-toggle">
                                        <div class="sys-settings-toggle-top">
                                            <div>
                                                <p class="sys-settings-toggle-title">X (Twitter) <span style="font-weight:normal">(<code>TYRO_LOGIN_SOCIAL_TWITTER</code>)</span></p>
                                                <p class="sys-settings-toggle-description">Allows users to sign in using their X (Twitter) account.</p>
                                            </div>
                                            <div>
                                                <input type="hidden" name="TYRO_LOGIN_SOCIAL_TWITTER" value="0">
                                                <label class="toggle-label">
                                                    <input type="checkbox" name="TYRO_LOGIN_SOCIAL_TWITTER" value="1" class="toggle-input" {{ old('TYRO_LOGIN_SOCIAL_TWITTER', $settings['TYRO_LOGIN_SOCIAL_TWITTER']) ? 'checked' : '' }}>
                                                    <span class="toggle-slider"></span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="sys-settings-toggle">
                                        <div class="sys-settings-toggle-top">
                                            <div>
                                                <p class="sys-settings-toggle-title">LinkedIn <span style="font-weight:normal">(<code>TYRO_LOGIN_SOCIAL_LINKEDIN</code>)</span></p>
                                                <p class="sys-settings-toggle-description">Allows users to sign in using their LinkedIn account.</p>
                                            </div>
                                            <div>
                                                <input type="hidden" name="TYRO_LOGIN_SOCIAL_LINKEDIN" value="0">
                                                <label class="toggle-label">
                                                    <input type="checkbox" name="TYRO_LOGIN_SOCIAL_LINKEDIN" value="1" class="toggle-input" {{ old('TYRO_LOGIN_SOCIAL_LINKEDIN', $settings['TYRO_LOGIN_SOCIAL_LINKEDIN']) ? 'checked' : '' }}>
                                                    <span class="toggle-slider"></span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="sys-settings-toggle">
                                        <div class="sys-settings-toggle-top">
                                            <div>
                                                <p class="sys-settings-toggle-title">Bitbucket <span style="font-weight:normal">(<code>TYRO_LOGIN_SOCIAL_BITBUCKET</code>)</span></p>
                                                <p class="sys-settings-toggle-description">Allows users to sign in using their Bitbucket account.</p>
                                            </div>
                                            <div>
                                                <input type="hidden" name="TYRO_LOGIN_SOCIAL_BITBUCKET" value="0">
                                                <label class="toggle-label">
                                                    <input type="checkbox" name="TYRO_LOGIN_SOCIAL_BITBUCKET" value="1" class="toggle-input" {{ old('TYRO_LOGIN_SOCIAL_BITBUCKET', $settings['TYRO_LOGIN_SOCIAL_BITBUCKET']) ? 'checked' : '' }}>
                                                    <span class="toggle-slider"></span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="sys-settings-toggle">
                                        <div class="sys-settings-toggle-top">
                                            <div>
                                                <p class="sys-settings-toggle-title">GitLab <span style="font-weight:normal">(<code>TYRO_LOGIN_SOCIAL_GITLAB</code>)</span></p>
                                                <p class="sys-settings-toggle-description">Allows users to sign in using their GitLab account.</p>
                                            </div>
                                            <div>
                                                <input type="hidden" name="TYRO_LOGIN_SOCIAL_GITLAB" value="0">
                                                <label class="toggle-label">
                                                    <input type="checkbox" name="TYRO_LOGIN_SOCIAL_GITLAB" value="1" class="toggle-input" {{ old('TYRO_LOGIN_SOCIAL_GITLAB', $settings['TYRO_LOGIN_SOCIAL_GITLAB']) ? 'checked' : '' }}>
                                                    <span class="toggle-slider"></span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="sys-settings-toggle">
                                        <div class="sys-settings-toggle-top">
                                            <div>
                                                <p class="sys-settings-toggle-title">Slack <span style="font-weight:normal">(<code>TYRO_LOGIN_SOCIAL_SLACK</code>)</span></p>
                                                <p class="sys-settings-toggle-description">Allows users to sign in using their Slack account.</p>
                                            </div>
                                            <div>
                                                <input type="hidden" name="TYRO_LOGIN_SOCIAL_SLACK" value="0">
                                                <label class="toggle-label">
                                                    <input type="checkbox" name="TYRO_LOGIN_SOCIAL_SLACK" value="1" class="toggle-input" {{ old('TYRO_LOGIN_SOCIAL_SLACK', $settings['TYRO_LOGIN_SOCIAL_SLACK']) ? 'checked' : '' }}>
                                                    <span class="toggle-slider"></span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group" style="margin-bottom:0.85rem;">
                                    <label for="TYRO_LOGIN_SOCIAL_LINK_EXISTING" class="form-label">Link existing accounts by email (TYRO_LOGIN_SOCIAL_LINK_EXISTING)</label>
                                    <div style="margin-top:0.35rem;">
                                        <input type="hidden" name="TYRO_LOGIN_SOCIAL_LINK_EXISTING" value="0">
                                        <label class="toggle-label">
                                            <input type="checkbox" name="TYRO_LOGIN_SOCIAL_LINK_EXISTING" value="1" class="toggle-input" {{ old('TYRO_LOGIN_SOCIAL_LINK_EXISTING', $settings['TYRO_LOGIN_SOCIAL_LINK_EXISTING']) ? 'checked' : '' }}>
                                            <span class="toggle-slider"></span>
                                        </label>
                                    </div>
                                </div>
                                <div class="form-group" style="margin-bottom:0.85rem;">
                                    <label for="TYRO_LOGIN_SOCIAL_AUTO_VERIFY_EMAIL" class="form-label">Auto-verify email after social login (TYRO_LOGIN_SOCIAL_AUTO_VERIFY_EMAIL)</label>
                                    <div style="margin-top:0.35rem;">
                                        <input type="hidden" name="TYRO_LOGIN_SOCIAL_AUTO_VERIFY_EMAIL" value="0">
                                        <label class="toggle-label">
                                            <input type="checkbox" name="TYRO_LOGIN_SOCIAL_AUTO_VERIFY_EMAIL" value="1" class="toggle-input" {{ old('TYRO_LOGIN_SOCIAL_AUTO_VERIFY_EMAIL', $settings['TYRO_LOGIN_SOCIAL_AUTO_VERIFY_EMAIL']) ? 'checked' : '' }}>
                                            <span class="toggle-slider"></span>
                                        </label>
                                    </div>
                                </div>
                                <div class="form-group" style="margin-bottom:0;">
                                    <label for="TYRO_LOGIN_SOCIAL_DIVIDER" class="form-label">Divider text (TYRO_LOGIN_SOCIAL_DIVIDER)</label>
                                    <input type="text" name="TYRO_LOGIN_SOCIAL_DIVIDER" id="TYRO_LOGIN_SOCIAL_DIVIDER" class="form-input" maxlength="255" value="{{ old('TYRO_LOGIN_SOCIAL_DIVIDER', $settings['TYRO_LOGIN_SOCIAL_DIVIDER']) }}">
                                </div>
                            </div>

                            <div class="sys-settings-surface" id="lockout-details-surface">
                                <h4 class="sys-settings-surface-title">Lockout Details</h4>
                                <p class="sys-settings-surface-description">Lockout page text and auto-redirect behaviour. Visible when lockout is enabled.</p>

                                <div class="sys-settings-toggles" style="margin-bottom:0.85rem;">
                                    <div class="sys-settings-toggle">
                                        <div class="sys-settings-toggle-top">
                                            <div>
                                                <p class="sys-settings-toggle-title">Auto-redirect after lockout expires <span style="font-weight:normal">(<code>TYRO_LOGIN_LOCKOUT_AUTO_REDIRECT</code>)</span></p>
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
                                    <label for="TYRO_LOGIN_LOCKOUT_MESSAGE" class="form-label">Lockout message (TYRO_LOGIN_LOCKOUT_MESSAGE)</label>
                                    <input type="text" name="TYRO_LOGIN_LOCKOUT_MESSAGE" id="TYRO_LOGIN_LOCKOUT_MESSAGE" class="form-input" maxlength="500" value="{{ old('TYRO_LOGIN_LOCKOUT_MESSAGE', $settings['TYRO_LOGIN_LOCKOUT_MESSAGE']) }}">
                                    <p class="form-hint">Supports <code>:minutes</code> placeholder.</p>
                                </div>
                                <div class="form-group" style="margin-bottom:0.85rem;">
                                    <label for="TYRO_LOGIN_LOCKOUT_TITLE" class="form-label">Lockout page title (TYRO_LOGIN_LOCKOUT_TITLE)</label>
                                    <input type="text" name="TYRO_LOGIN_LOCKOUT_TITLE" id="TYRO_LOGIN_LOCKOUT_TITLE" class="form-input" maxlength="255" value="{{ old('TYRO_LOGIN_LOCKOUT_TITLE', $settings['TYRO_LOGIN_LOCKOUT_TITLE']) }}">
                                </div>
                                <div class="form-group" style="margin-bottom:0;">
                                    <label for="TYRO_LOGIN_LOCKOUT_SUBTITLE" class="form-label">Lockout page subtitle (TYRO_LOGIN_LOCKOUT_SUBTITLE)</label>
                                    <input type="text" name="TYRO_LOGIN_LOCKOUT_SUBTITLE" id="TYRO_LOGIN_LOCKOUT_SUBTITLE" class="form-input" maxlength="500" value="{{ old('TYRO_LOGIN_LOCKOUT_SUBTITLE', $settings['TYRO_LOGIN_LOCKOUT_SUBTITLE']) }}">
                                </div>
                            </div>

                            <div class="sys-settings-surface">
                                <h4 class="sys-settings-surface-title">Email Subjects</h4>
                                <p class="sys-settings-surface-description">Custom email subject lines for auth emails.</p>

                                <div class="form-group" style="margin-bottom:0.85rem;">
                                    <label for="TYRO_LOGIN_EMAIL_OTP_SUBJECT" class="form-label">OTP email subject (TYRO_LOGIN_EMAIL_OTP_SUBJECT)</label>
                                    <input type="text" name="TYRO_LOGIN_EMAIL_OTP_SUBJECT" id="TYRO_LOGIN_EMAIL_OTP_SUBJECT" class="form-input" maxlength="255" value="{{ old('TYRO_LOGIN_EMAIL_OTP_SUBJECT', $settings['TYRO_LOGIN_EMAIL_OTP_SUBJECT']) }}">
                                </div>
                                <div class="form-group" style="margin-bottom:0.85rem;">
                                    <label for="TYRO_LOGIN_EMAIL_PASSWORD_RESET_SUBJECT" class="form-label">Password reset subject (TYRO_LOGIN_EMAIL_PASSWORD_RESET_SUBJECT)</label>
                                    <input type="text" name="TYRO_LOGIN_EMAIL_PASSWORD_RESET_SUBJECT" id="TYRO_LOGIN_EMAIL_PASSWORD_RESET_SUBJECT" class="form-input" maxlength="255" value="{{ old('TYRO_LOGIN_EMAIL_PASSWORD_RESET_SUBJECT', $settings['TYRO_LOGIN_EMAIL_PASSWORD_RESET_SUBJECT']) }}">
                                </div>
                                <div class="form-group" style="margin-bottom:0.85rem;">
                                    <label for="TYRO_LOGIN_EMAIL_VERIFY_SUBJECT" class="form-label">Email verification subject (TYRO_LOGIN_EMAIL_VERIFY_SUBJECT)</label>
                                    <input type="text" name="TYRO_LOGIN_EMAIL_VERIFY_SUBJECT" id="TYRO_LOGIN_EMAIL_VERIFY_SUBJECT" class="form-input" maxlength="255" value="{{ old('TYRO_LOGIN_EMAIL_VERIFY_SUBJECT', $settings['TYRO_LOGIN_EMAIL_VERIFY_SUBJECT']) }}">
                                </div>
                                <div class="form-group" style="margin-bottom:0.85rem;">
                                    <label for="TYRO_LOGIN_EMAIL_WELCOME_SUBJECT" class="form-label">Welcome email subject (TYRO_LOGIN_EMAIL_WELCOME_SUBJECT)</label>
                                    <input type="text" name="TYRO_LOGIN_EMAIL_WELCOME_SUBJECT" id="TYRO_LOGIN_EMAIL_WELCOME_SUBJECT" class="form-input" maxlength="255" value="{{ old('TYRO_LOGIN_EMAIL_WELCOME_SUBJECT', $settings['TYRO_LOGIN_EMAIL_WELCOME_SUBJECT']) }}">
                                </div>
                                <div class="form-group" style="margin-bottom:0;">
                                    <label for="TYRO_LOGIN_EMAIL_MAGIC_LINK_SUBJECT" class="form-label">Magic link subject (TYRO_LOGIN_EMAIL_MAGIC_LINK_SUBJECT)</label>
                                    <input type="text" name="TYRO_LOGIN_EMAIL_MAGIC_LINK_SUBJECT" id="TYRO_LOGIN_EMAIL_MAGIC_LINK_SUBJECT" class="form-input" maxlength="255" value="{{ old('TYRO_LOGIN_EMAIL_MAGIC_LINK_SUBJECT', $settings['TYRO_LOGIN_EMAIL_MAGIC_LINK_SUBJECT']) }}">
                                </div>
                            </div>

                            <div class="sys-settings-surface">
                                <h4 class="sys-settings-surface-title">Page Content</h4>
                                <p class="sys-settings-surface-description">Customise titles, subtitles, and background text for auth pages.</p>

                                <h5 style="margin:0 0 0.6rem;font-size:0.88rem;font-weight:700;color:var(--foreground);">Login Page</h5>
                                <div class="form-group" style="margin-bottom:0.85rem;">
                                    <label for="TYRO_LOGIN_BG_TITLE" class="form-label">Background title (TYRO_LOGIN_BG_TITLE)</label>
                                    <input type="text" name="TYRO_LOGIN_BG_TITLE" id="TYRO_LOGIN_BG_TITLE" class="form-input" maxlength="255" value="{{ old('TYRO_LOGIN_BG_TITLE', $settings['TYRO_LOGIN_BG_TITLE']) }}">
                                </div>
                                <div class="form-group" style="margin-bottom:0.85rem;">
                                    <label for="TYRO_LOGIN_BG_DESCRIPTION" class="form-label">Background description (TYRO_LOGIN_BG_DESCRIPTION)</label>
                                    <input type="text" name="TYRO_LOGIN_BG_DESCRIPTION" id="TYRO_LOGIN_BG_DESCRIPTION" class="form-input" maxlength="500" value="{{ old('TYRO_LOGIN_BG_DESCRIPTION', $settings['TYRO_LOGIN_BG_DESCRIPTION']) }}">
                                </div>

                                <h5 style="margin:0 0 0.6rem;font-size:0.88rem;font-weight:700;color:var(--foreground);">Register Page</h5>
                                <div class="form-group" style="margin-bottom:0.85rem;">
                                    <label for="TYRO_LOGIN_REGISTER_BG_TITLE" class="form-label">Background title (TYRO_LOGIN_REGISTER_BG_TITLE)</label>
                                    <input type="text" name="TYRO_LOGIN_REGISTER_BG_TITLE" id="TYRO_LOGIN_REGISTER_BG_TITLE" class="form-input" maxlength="255" value="{{ old('TYRO_LOGIN_REGISTER_BG_TITLE', $settings['TYRO_LOGIN_REGISTER_BG_TITLE']) }}">
                                </div>
                                <div class="form-group" style="margin-bottom:0.85rem;">
                                    <label for="TYRO_LOGIN_REGISTER_BG_DESCRIPTION" class="form-label">Background description (TYRO_LOGIN_REGISTER_BG_DESCRIPTION)</label>
                                    <input type="text" name="TYRO_LOGIN_REGISTER_BG_DESCRIPTION" id="TYRO_LOGIN_REGISTER_BG_DESCRIPTION" class="form-input" maxlength="500" value="{{ old('TYRO_LOGIN_REGISTER_BG_DESCRIPTION', $settings['TYRO_LOGIN_REGISTER_BG_DESCRIPTION']) }}">
                                </div>

                                <h5 style="margin:0 0 0.6rem;font-size:0.88rem;font-weight:700;color:var(--foreground);">Verify Email Page</h5>
                                <div class="form-group" style="margin-bottom:0.85rem;">
                                    <label for="TYRO_LOGIN_VERIFY_EMAIL_TITLE" class="form-label">Page title (TYRO_LOGIN_VERIFY_EMAIL_TITLE)</label>
                                    <input type="text" name="TYRO_LOGIN_VERIFY_EMAIL_TITLE" id="TYRO_LOGIN_VERIFY_EMAIL_TITLE" class="form-input" maxlength="255" value="{{ old('TYRO_LOGIN_VERIFY_EMAIL_TITLE', $settings['TYRO_LOGIN_VERIFY_EMAIL_TITLE']) }}">
                                </div>
                                <div class="form-group" style="margin-bottom:0.85rem;">
                                    <label for="TYRO_LOGIN_VERIFY_EMAIL_SUBTITLE" class="form-label">Page subtitle (TYRO_LOGIN_VERIFY_EMAIL_SUBTITLE)</label>
                                    <input type="text" name="TYRO_LOGIN_VERIFY_EMAIL_SUBTITLE" id="TYRO_LOGIN_VERIFY_EMAIL_SUBTITLE" class="form-input" maxlength="500" value="{{ old('TYRO_LOGIN_VERIFY_EMAIL_SUBTITLE', $settings['TYRO_LOGIN_VERIFY_EMAIL_SUBTITLE']) }}">
                                </div>
                                <div class="form-group" style="margin-bottom:0.85rem;">
                                    <label for="TYRO_LOGIN_VERIFY_EMAIL_BG_TITLE" class="form-label">Background title (TYRO_LOGIN_VERIFY_EMAIL_BG_TITLE)</label>
                                    <input type="text" name="TYRO_LOGIN_VERIFY_EMAIL_BG_TITLE" id="TYRO_LOGIN_VERIFY_EMAIL_BG_TITLE" class="form-input" maxlength="255" value="{{ old('TYRO_LOGIN_VERIFY_EMAIL_BG_TITLE', $settings['TYRO_LOGIN_VERIFY_EMAIL_BG_TITLE']) }}">
                                </div>
                                <div class="form-group" style="margin-bottom:0.85rem;">
                                    <label for="TYRO_LOGIN_VERIFY_EMAIL_BG_DESCRIPTION" class="form-label">Background description (TYRO_LOGIN_VERIFY_EMAIL_BG_DESCRIPTION)</label>
                                    <input type="text" name="TYRO_LOGIN_VERIFY_EMAIL_BG_DESCRIPTION" id="TYRO_LOGIN_VERIFY_EMAIL_BG_DESCRIPTION" class="form-input" maxlength="500" value="{{ old('TYRO_LOGIN_VERIFY_EMAIL_BG_DESCRIPTION', $settings['TYRO_LOGIN_VERIFY_EMAIL_BG_DESCRIPTION']) }}">
                                </div>

                                <h5 style="margin:0 0 0.6rem;font-size:0.88rem;font-weight:700;color:var(--foreground);">Forgot Password Page</h5>
                                <div class="form-group" style="margin-bottom:0.85rem;">
                                    <label for="TYRO_LOGIN_FORGOT_PASSWORD_TITLE" class="form-label">Page title (TYRO_LOGIN_FORGOT_PASSWORD_TITLE)</label>
                                    <input type="text" name="TYRO_LOGIN_FORGOT_PASSWORD_TITLE" id="TYRO_LOGIN_FORGOT_PASSWORD_TITLE" class="form-input" maxlength="255" value="{{ old('TYRO_LOGIN_FORGOT_PASSWORD_TITLE', $settings['TYRO_LOGIN_FORGOT_PASSWORD_TITLE']) }}">
                                </div>
                                <div class="form-group" style="margin-bottom:0.85rem;">
                                    <label for="TYRO_LOGIN_FORGOT_PASSWORD_SUBTITLE" class="form-label">Page subtitle (TYRO_LOGIN_FORGOT_PASSWORD_SUBTITLE)</label>
                                    <input type="text" name="TYRO_LOGIN_FORGOT_PASSWORD_SUBTITLE" id="TYRO_LOGIN_FORGOT_PASSWORD_SUBTITLE" class="form-input" maxlength="500" value="{{ old('TYRO_LOGIN_FORGOT_PASSWORD_SUBTITLE', $settings['TYRO_LOGIN_FORGOT_PASSWORD_SUBTITLE']) }}">
                                </div>
                                <div class="form-group" style="margin-bottom:0.85rem;">
                                    <label for="TYRO_LOGIN_FORGOT_PASSWORD_BG_TITLE" class="form-label">Background title (TYRO_LOGIN_FORGOT_PASSWORD_BG_TITLE)</label>
                                    <input type="text" name="TYRO_LOGIN_FORGOT_PASSWORD_BG_TITLE" id="TYRO_LOGIN_FORGOT_PASSWORD_BG_TITLE" class="form-input" maxlength="255" value="{{ old('TYRO_LOGIN_FORGOT_PASSWORD_BG_TITLE', $settings['TYRO_LOGIN_FORGOT_PASSWORD_BG_TITLE']) }}">
                                </div>
                                <div class="form-group" style="margin-bottom:0.85rem;">
                                    <label for="TYRO_LOGIN_FORGOT_PASSWORD_BG_DESCRIPTION" class="form-label">Background description (TYRO_LOGIN_FORGOT_PASSWORD_BG_DESCRIPTION)</label>
                                    <input type="text" name="TYRO_LOGIN_FORGOT_PASSWORD_BG_DESCRIPTION" id="TYRO_LOGIN_FORGOT_PASSWORD_BG_DESCRIPTION" class="form-input" maxlength="500" value="{{ old('TYRO_LOGIN_FORGOT_PASSWORD_BG_DESCRIPTION', $settings['TYRO_LOGIN_FORGOT_PASSWORD_BG_DESCRIPTION']) }}">
                                </div>

                                <h5 style="margin:0 0 0.6rem;font-size:0.88rem;font-weight:700;color:var(--foreground);">Reset Password Page</h5>
                                <div class="form-group" style="margin-bottom:0.85rem;">
                                    <label for="TYRO_LOGIN_RESET_PASSWORD_TITLE" class="form-label">Page title (TYRO_LOGIN_RESET_PASSWORD_TITLE)</label>
                                    <input type="text" name="TYRO_LOGIN_RESET_PASSWORD_TITLE" id="TYRO_LOGIN_RESET_PASSWORD_TITLE" class="form-input" maxlength="255" value="{{ old('TYRO_LOGIN_RESET_PASSWORD_TITLE', $settings['TYRO_LOGIN_RESET_PASSWORD_TITLE']) }}">
                                </div>
                                <div class="form-group" style="margin-bottom:0.85rem;">
                                    <label for="TYRO_LOGIN_RESET_PASSWORD_SUBTITLE" class="form-label">Page subtitle (TYRO_LOGIN_RESET_PASSWORD_SUBTITLE)</label>
                                    <input type="text" name="TYRO_LOGIN_RESET_PASSWORD_SUBTITLE" id="TYRO_LOGIN_RESET_PASSWORD_SUBTITLE" class="form-input" maxlength="500" value="{{ old('TYRO_LOGIN_RESET_PASSWORD_SUBTITLE', $settings['TYRO_LOGIN_RESET_PASSWORD_SUBTITLE']) }}">
                                </div>
                                <div class="form-group" style="margin-bottom:0.85rem;">
                                    <label for="TYRO_LOGIN_RESET_PASSWORD_BG_TITLE" class="form-label">Background title (TYRO_LOGIN_RESET_PASSWORD_BG_TITLE)</label>
                                    <input type="text" name="TYRO_LOGIN_RESET_PASSWORD_BG_TITLE" id="TYRO_LOGIN_RESET_PASSWORD_BG_TITLE" class="form-input" maxlength="255" value="{{ old('TYRO_LOGIN_RESET_PASSWORD_BG_TITLE', $settings['TYRO_LOGIN_RESET_PASSWORD_BG_TITLE']) }}">
                                </div>
                                <div class="form-group" style="margin-bottom:0;">
                                    <label for="TYRO_LOGIN_RESET_PASSWORD_BG_DESCRIPTION" class="form-label">Background description (TYRO_LOGIN_RESET_PASSWORD_BG_DESCRIPTION)</label>
                                    <input type="text" name="TYRO_LOGIN_RESET_PASSWORD_BG_DESCRIPTION" id="TYRO_LOGIN_RESET_PASSWORD_BG_DESCRIPTION" class="form-input" maxlength="500" value="{{ old('TYRO_LOGIN_RESET_PASSWORD_BG_DESCRIPTION', $settings['TYRO_LOGIN_RESET_PASSWORD_BG_DESCRIPTION']) }}">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
