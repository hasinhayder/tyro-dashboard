{{-- Social login --}}
            <div class="vtabs-panel" id="vtab-auth-social">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Social login</h3>
                    </div>
                    <div class="card-body">
                        <p class="sys-settings-section-description" style="margin-bottom:1rem;">Sign in with third-party providers.</p>

                        <div class="sys-settings-grid">
                            <div class="sys-settings-surface">
                                <h4 class="sys-settings-surface-title">Social login</h4>
                                <p class="sys-settings-surface-description">Enable/disable social authentication.</p>

                                <div class="sys-settings-toggles" style="margin-bottom:0;">
                                    <div class="sys-settings-toggle">
                                        <div class="sys-settings-toggle-top">
                                            <div>
                                                <p class="sys-settings-toggle-title">Enable social login <span class="env-key">TYRO_LOGIN_SOCIAL_ENABLED</span></p>
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
                                                <p class="sys-settings-toggle-title">Auto-register social users <span class="env-key">TYRO_LOGIN_SOCIAL_AUTO_REGISTER</span></p>
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

                            <div class="sys-settings-surface" id="social-details-surface">
                                <h4 class="sys-settings-surface-title">Social providers</h4>
                                <p class="sys-settings-surface-description">Enable individual social providers. Visible when social login is enabled.</p>

                                <div class="sys-settings-toggles" style="margin-bottom:0.85rem;">
                                    <div class="sys-settings-toggle">
                                        <div class="sys-settings-toggle-top">
                                            <div>
                                                <p class="sys-settings-toggle-title">Google <span class="env-key">TYRO_LOGIN_SOCIAL_GOOGLE</span></p>
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
                                                <p class="sys-settings-toggle-title">Facebook <span class="env-key">TYRO_LOGIN_SOCIAL_FACEBOOK</span></p>
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
                                                <p class="sys-settings-toggle-title">GitHub <span class="env-key">TYRO_LOGIN_SOCIAL_GITHUB</span></p>
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
                                                <p class="sys-settings-toggle-title">X (Twitter) <span class="env-key">TYRO_LOGIN_SOCIAL_TWITTER</span></p>
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
                                                <p class="sys-settings-toggle-title">LinkedIn <span class="env-key">TYRO_LOGIN_SOCIAL_LINKEDIN</span></p>
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
                                                <p class="sys-settings-toggle-title">Bitbucket <span class="env-key">TYRO_LOGIN_SOCIAL_BITBUCKET</span></p>
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
                                                <p class="sys-settings-toggle-title">GitLab <span class="env-key">TYRO_LOGIN_SOCIAL_GITLAB</span></p>
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
                                                <p class="sys-settings-toggle-title">Slack <span class="env-key">TYRO_LOGIN_SOCIAL_SLACK</span></p>
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
                                    <div class="sys-settings-toggle">
                                        <div class="sys-settings-toggle-top">
                                            <div>
                                                <p class="sys-settings-toggle-title">Link existing accounts by email <span class="env-key">TYRO_LOGIN_SOCIAL_LINK_EXISTING</span></p>
                                                <p class="sys-settings-toggle-description">Links social sign-ins to an existing account with the same email address instead of creating a duplicate.</p>
                                            </div>
                                            <div>
                                                <input type="hidden" name="TYRO_LOGIN_SOCIAL_LINK_EXISTING" value="0">
                                                <label class="toggle-label">
                                                    <input type="checkbox" name="TYRO_LOGIN_SOCIAL_LINK_EXISTING" value="1" class="toggle-input" {{ old('TYRO_LOGIN_SOCIAL_LINK_EXISTING', $settings['TYRO_LOGIN_SOCIAL_LINK_EXISTING']) ? 'checked' : '' }}>
                                                    <span class="toggle-slider"></span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="sys-settings-toggle">
                                        <div class="sys-settings-toggle-top">
                                            <div>
                                                <p class="sys-settings-toggle-title">Auto-verify email after social login <span class="env-key">TYRO_LOGIN_SOCIAL_AUTO_VERIFY_EMAIL</span></p>
                                                <p class="sys-settings-toggle-description">Marks the user's email as verified automatically after a social sign-in, since social providers confirm email ownership.</p>
                                            </div>
                                            <div>
                                                <input type="hidden" name="TYRO_LOGIN_SOCIAL_AUTO_VERIFY_EMAIL" value="0">
                                                <label class="toggle-label">
                                                    <input type="checkbox" name="TYRO_LOGIN_SOCIAL_AUTO_VERIFY_EMAIL" value="1" class="toggle-input" {{ old('TYRO_LOGIN_SOCIAL_AUTO_VERIFY_EMAIL', $settings['TYRO_LOGIN_SOCIAL_AUTO_VERIFY_EMAIL']) ? 'checked' : '' }}>
                                                    <span class="toggle-slider"></span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group" style="margin-bottom:0;">
                                    <label for="TYRO_LOGIN_SOCIAL_DIVIDER" class="form-label">Divider text <span class="env-key">TYRO_LOGIN_SOCIAL_DIVIDER</span></label>
                                    <input type="text" name="TYRO_LOGIN_SOCIAL_DIVIDER" id="TYRO_LOGIN_SOCIAL_DIVIDER" class="form-input" maxlength="255" value="{{ old('TYRO_LOGIN_SOCIAL_DIVIDER', $settings['TYRO_LOGIN_SOCIAL_DIVIDER']) }}">
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
