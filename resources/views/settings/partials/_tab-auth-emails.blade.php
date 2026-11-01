{{-- Auth emails --}}
            <div class="vtabs-panel" id="vtab-auth-emails">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Auth emails</h3>
                    </div>
                    <div class="card-body">
                        <p class="sys-settings-section-description" style="margin-bottom:1rem;">Notification emails sent by the authentication system.</p>

                        <div class="sys-settings-grid">
                            <div class="sys-settings-surface">
                                <h4 class="sys-settings-surface-title">Email notifications</h4>
                                <p class="sys-settings-surface-description">Enable/disable individual auth emails.</p>

                                <div class="sys-settings-toggles" style="margin-bottom:0;">
                                    <div class="sys-settings-toggle">
                                        <div class="sys-settings-toggle-top">
                                            <div>
                                                <p class="sys-settings-toggle-title">OTP email <span class="env-key">TYRO_LOGIN_EMAIL_OTP</span></p>
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
                                                <p class="sys-settings-toggle-title">Password reset email <span class="env-key">TYRO_LOGIN_EMAIL_PASSWORD_RESET</span></p>
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
                                                <p class="sys-settings-toggle-title">Email verification email <span class="env-key">TYRO_LOGIN_EMAIL_VERIFY</span></p>
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
                                                <p class="sys-settings-toggle-title">Welcome email <span class="env-key">TYRO_LOGIN_EMAIL_WELCOME</span></p>
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
                                                <p class="sys-settings-toggle-title">Magic link email <span class="env-key">TYRO_LOGIN_EMAIL_MAGIC_LINK</span></p>
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
                                <h4 class="sys-settings-surface-title">Email subjects</h4>
                                <p class="sys-settings-surface-description">Custom email subject lines for auth emails.</p>

                                <div class="form-group" style="margin-bottom:0.85rem;">
                                    <label for="TYRO_LOGIN_EMAIL_OTP_SUBJECT" class="form-label">OTP email subject <span class="env-key">TYRO_LOGIN_EMAIL_OTP_SUBJECT</span></label>
                                    <input type="text" name="TYRO_LOGIN_EMAIL_OTP_SUBJECT" id="TYRO_LOGIN_EMAIL_OTP_SUBJECT" class="form-input" maxlength="255" value="{{ old('TYRO_LOGIN_EMAIL_OTP_SUBJECT', $settings['TYRO_LOGIN_EMAIL_OTP_SUBJECT']) }}">
                                </div>
                                <div class="form-group" style="margin-bottom:0.85rem;">
                                    <label for="TYRO_LOGIN_EMAIL_PASSWORD_RESET_SUBJECT" class="form-label">Password reset subject <span class="env-key">TYRO_LOGIN_EMAIL_PASSWORD_RESET_SUBJECT</span></label>
                                    <input type="text" name="TYRO_LOGIN_EMAIL_PASSWORD_RESET_SUBJECT" id="TYRO_LOGIN_EMAIL_PASSWORD_RESET_SUBJECT" class="form-input" maxlength="255" value="{{ old('TYRO_LOGIN_EMAIL_PASSWORD_RESET_SUBJECT', $settings['TYRO_LOGIN_EMAIL_PASSWORD_RESET_SUBJECT']) }}">
                                </div>
                                <div class="form-group" style="margin-bottom:0.85rem;">
                                    <label for="TYRO_LOGIN_EMAIL_VERIFY_SUBJECT" class="form-label">Email verification subject <span class="env-key">TYRO_LOGIN_EMAIL_VERIFY_SUBJECT</span></label>
                                    <input type="text" name="TYRO_LOGIN_EMAIL_VERIFY_SUBJECT" id="TYRO_LOGIN_EMAIL_VERIFY_SUBJECT" class="form-input" maxlength="255" value="{{ old('TYRO_LOGIN_EMAIL_VERIFY_SUBJECT', $settings['TYRO_LOGIN_EMAIL_VERIFY_SUBJECT']) }}">
                                </div>
                                <div class="form-group" style="margin-bottom:0.85rem;">
                                    <label for="TYRO_LOGIN_EMAIL_WELCOME_SUBJECT" class="form-label">Welcome email subject <span class="env-key">TYRO_LOGIN_EMAIL_WELCOME_SUBJECT</span></label>
                                    <input type="text" name="TYRO_LOGIN_EMAIL_WELCOME_SUBJECT" id="TYRO_LOGIN_EMAIL_WELCOME_SUBJECT" class="form-input" maxlength="255" value="{{ old('TYRO_LOGIN_EMAIL_WELCOME_SUBJECT', $settings['TYRO_LOGIN_EMAIL_WELCOME_SUBJECT']) }}">
                                </div>
                                <div class="form-group" style="margin-bottom:0;">
                                    <label for="TYRO_LOGIN_EMAIL_MAGIC_LINK_SUBJECT" class="form-label">Magic link subject <span class="env-key">TYRO_LOGIN_EMAIL_MAGIC_LINK_SUBJECT</span></label>
                                    <input type="text" name="TYRO_LOGIN_EMAIL_MAGIC_LINK_SUBJECT" id="TYRO_LOGIN_EMAIL_MAGIC_LINK_SUBJECT" class="form-input" maxlength="255" value="{{ old('TYRO_LOGIN_EMAIL_MAGIC_LINK_SUBJECT', $settings['TYRO_LOGIN_EMAIL_MAGIC_LINK_SUBJECT']) }}">
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
