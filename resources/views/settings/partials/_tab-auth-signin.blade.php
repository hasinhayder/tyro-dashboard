{{-- Login & registration --}}
            <div class="vtabs-panel" id="vtab-auth-signin">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Login &amp; registration</h3>
                    </div>
                    <div class="card-body">
                        <p class="sys-settings-section-description" style="margin-bottom:1rem;">How people sign up and sign in, where they land afterwards, and password requirements.</p>

                        <div class="sys-settings-grid">
                            <div class="sys-settings-surface">
                                <h4 class="sys-settings-surface-title">Registration &amp; login</h4>
                                <p class="sys-settings-surface-description">Basic registration and login form preferences.</p>

                                <div class="sys-settings-toggles" style="margin-bottom:0.85rem;">
                                    <div class="sys-settings-toggle">
                                        <div class="sys-settings-toggle-top">
                                            <div>
                                                <p class="sys-settings-toggle-title">Enable registration <span class="env-key">TYRO_LOGIN_REGISTRATION_ENABLED</span></p>
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
                                                <p class="sys-settings-toggle-title">Require email verification <span class="env-key">TYRO_LOGIN_REQUIRE_EMAIL_VERIFICATION</span></p>
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
                                                <p class="sys-settings-toggle-title">Show "Remember Me" <span class="env-key">TYRO_LOGIN_REMEMBER_ME</span></p>
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
                                                <p class="sys-settings-toggle-title">Show "Forgot Password" <span class="env-key">TYRO_LOGIN_FORGOT_PASSWORD</span></p>
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
                                    <label for="TYRO_LOGIN_FIELD" class="form-label">Login field <span class="env-key">TYRO_LOGIN_FIELD</span></label>
                                    <select name="TYRO_LOGIN_FIELD" id="TYRO_LOGIN_FIELD" class="form-select">
                                        <option value="email" {{ old('TYRO_LOGIN_FIELD', $settings['TYRO_LOGIN_FIELD']) === 'email' ? 'selected' : '' }}>Email</option>
                                        <option value="username" {{ old('TYRO_LOGIN_FIELD', $settings['TYRO_LOGIN_FIELD']) === 'username' ? 'selected' : '' }}>Username</option>
                                        <option value="both" {{ old('TYRO_LOGIN_FIELD', $settings['TYRO_LOGIN_FIELD']) === 'both' ? 'selected' : '' }}>Both</option>
                                    </select>
                                </div>
                            </div>

                            <div class="sys-settings-surface">
                                <h4 class="sys-settings-surface-title">Registration options</h4>
                                <p class="sys-settings-surface-description">Additional registration and login behaviour.</p>

                                <div class="sys-settings-toggles" style="margin-bottom:0;">
                                    <div class="sys-settings-toggle">
                                        <div class="sys-settings-toggle-top">
                                            <div>
                                                <p class="sys-settings-toggle-title">Auto-login after registration <span class="env-key">TYRO_LOGIN_REGISTRATION_AUTO_LOGIN</span></p>
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
                                                <p class="sys-settings-toggle-title">Disable password login <span class="env-key">TYRO_LOGIN_DISABLE_PASSWORD</span></p>
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

                            <div class="sys-settings-surface">
                                <h4 class="sys-settings-surface-title">Redirects</h4>
                                <p class="sys-settings-surface-description">Where users are sent after login/logout.</p>

                                <div class="form-group" style="margin-bottom:0.85rem;">
                                    <label for="TYRO_LOGIN_REDIRECT_AFTER_LOGIN" class="form-label">After login <span class="env-key">TYRO_LOGIN_REDIRECT_AFTER_LOGIN</span></label>
                                    <input type="text" name="TYRO_LOGIN_REDIRECT_AFTER_LOGIN" id="TYRO_LOGIN_REDIRECT_AFTER_LOGIN"
                                           class="form-input" maxlength="255"
                                           value="{{ old('TYRO_LOGIN_REDIRECT_AFTER_LOGIN', $settings['TYRO_LOGIN_REDIRECT_AFTER_LOGIN']) }}">
                                </div>

                                <div class="form-group" style="margin-bottom:0.85rem;">
                                    <label for="TYRO_LOGIN_REDIRECT_AFTER_LOGOUT" class="form-label">After logout <span class="env-key">TYRO_LOGIN_REDIRECT_AFTER_LOGOUT</span></label>
                                    <input type="text" name="TYRO_LOGIN_REDIRECT_AFTER_LOGOUT" id="TYRO_LOGIN_REDIRECT_AFTER_LOGOUT"
                                           class="form-input" maxlength="255"
                                           value="{{ old('TYRO_LOGIN_REDIRECT_AFTER_LOGOUT', $settings['TYRO_LOGIN_REDIRECT_AFTER_LOGOUT']) }}">
                                </div>

                                <div class="form-group" style="margin-bottom:0.85rem;">
                                    <label for="TYRO_LOGIN_REDIRECT_AFTER_REGISTER" class="form-label">After register <span class="env-key">TYRO_LOGIN_REDIRECT_AFTER_REGISTER</span></label>
                                    <input type="text" name="TYRO_LOGIN_REDIRECT_AFTER_REGISTER" id="TYRO_LOGIN_REDIRECT_AFTER_REGISTER"
                                           class="form-input" maxlength="255"
                                           value="{{ old('TYRO_LOGIN_REDIRECT_AFTER_REGISTER', $settings['TYRO_LOGIN_REDIRECT_AFTER_REGISTER']) }}">
                                </div>

                                <div class="form-group" style="margin-bottom:0;">
                                    <label for="TYRO_LOGIN_REDIRECT_AFTER_EMAIL_VERIFICATION" class="form-label">After email verification <span class="env-key">TYRO_LOGIN_REDIRECT_AFTER_EMAIL_VERIFICATION</span></label>
                                    <input type="text" name="TYRO_LOGIN_REDIRECT_AFTER_EMAIL_VERIFICATION" id="TYRO_LOGIN_REDIRECT_AFTER_EMAIL_VERIFICATION"
                                           class="form-input" maxlength="255"
                                           value="{{ old('TYRO_LOGIN_REDIRECT_AFTER_EMAIL_VERIFICATION', $settings['TYRO_LOGIN_REDIRECT_AFTER_EMAIL_VERIFICATION']) }}">
                                </div>
                            </div>

                            <div class="sys-settings-surface">
                                <h4 class="sys-settings-surface-title">Password rules</h4>
                                <p class="sys-settings-surface-description">Control password requirements for registration and updates.</p>

                                <div class="form-group" style="margin-bottom:0.85rem;">
                                    <label for="TYRO_LOGIN_PASSWORD_MIN_LENGTH" class="form-label">Minimum password length <span class="env-key">TYRO_LOGIN_PASSWORD_MIN_LENGTH</span></label>
                                    <input type="number" name="TYRO_LOGIN_PASSWORD_MIN_LENGTH" id="TYRO_LOGIN_PASSWORD_MIN_LENGTH"
                                           class="form-input" min="4" max="100"
                                           value="{{ old('TYRO_LOGIN_PASSWORD_MIN_LENGTH', $settings['TYRO_LOGIN_PASSWORD_MIN_LENGTH']) }}">
                                </div>

                                <div class="sys-settings-toggles" style="margin-bottom:0;">
                                    <div class="sys-settings-toggle">
                                        <div class="sys-settings-toggle-top">
                                            <div>
                                                <p class="sys-settings-toggle-title">Require uppercase <span class="env-key">TYRO_LOGIN_PASSWORD_REQUIRE_UPPERCASE</span></p>
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
                                                <p class="sys-settings-toggle-title">Require lowercase <span class="env-key">TYRO_LOGIN_PASSWORD_REQUIRE_LOWERCASE</span></p>
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
                                                <p class="sys-settings-toggle-title">Require numbers <span class="env-key">TYRO_LOGIN_PASSWORD_REQUIRE_NUMBERS</span></p>
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
                                                <p class="sys-settings-toggle-title">Require special characters <span class="env-key">TYRO_LOGIN_PASSWORD_REQUIRE_SPECIAL_CHARS</span></p>
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
                                                <p class="sys-settings-toggle-title">Block common passwords <span class="env-key">TYRO_LOGIN_PASSWORD_CHECK_COMMON</span></p>
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
                                                <p class="sys-settings-toggle-title">Require password confirmation <span class="env-key">TYRO_LOGIN_PASSWORD_REQUIRE_CONFIRMATION</span></p>
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
                                                <p class="sys-settings-toggle-title">Disallow user info in password <span class="env-key">TYRO_LOGIN_PASSWORD_DISALLOW_USER_INFO</span></p>
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
                                    <label for="TYRO_LOGIN_PASSWORD_MAX_LENGTH" class="form-label">Maximum password length <span class="env-key">TYRO_LOGIN_PASSWORD_MAX_LENGTH</span></label>
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
