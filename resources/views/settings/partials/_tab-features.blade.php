{{-- Features --}}
            <div class="vtabs-panel" id="vtab-features">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Features</h3>
                    </div>
                    <div class="card-body">
                        <p class="sys-settings-section-description" style="margin-bottom:1rem;">Turn dashboard modules and menu items on or off.</p>

                        <div class="sys-settings-grid">
                            <div class="sys-settings-surface">
                                <h4 class="sys-settings-surface-title">Feature flags</h4>
                                <p class="sys-settings-surface-description">Enable or disable dashboard features.</p>

                                <div class="sys-settings-toggles" style="margin-bottom:0;">
                                    <div class="sys-settings-toggle">
                                        <div class="sys-settings-toggle-top">
                                            <div>
                                                <p class="sys-settings-toggle-title">Roles menu <span class="env-key">TYRO_DASHBOARD_SHOW_ROLES_MENU</span></p>
                                                <p class="sys-settings-toggle-description">Displays the roles management menu in the administration sidebar.</p>
                                            </div>
                                            <div>
                                                <input type="hidden" name="TYRO_DASHBOARD_SHOW_ROLES_MENU" value="0">
                                                <label class="toggle-label">
                                                    <input type="checkbox" name="TYRO_DASHBOARD_SHOW_ROLES_MENU" value="1" class="toggle-input" {{ old('TYRO_DASHBOARD_SHOW_ROLES_MENU', $settings['TYRO_DASHBOARD_SHOW_ROLES_MENU']) ? 'checked' : '' }}>
                                                    <span class="toggle-slider"></span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="sys-settings-toggle">
                                        <div class="sys-settings-toggle-top">
                                            <div>
                                                <p class="sys-settings-toggle-title">Privileges menu <span class="env-key">TYRO_DASHBOARD_SHOW_PRIVILEGES_MENU</span></p>
                                                <p class="sys-settings-toggle-description">Displays the privileges management menu in the administration sidebar.</p>
                                            </div>
                                            <div>
                                                <input type="hidden" name="TYRO_DASHBOARD_SHOW_PRIVILEGES_MENU" value="0">
                                                <label class="toggle-label">
                                                    <input type="checkbox" name="TYRO_DASHBOARD_SHOW_PRIVILEGES_MENU" value="1" class="toggle-input" {{ old('TYRO_DASHBOARD_SHOW_PRIVILEGES_MENU', $settings['TYRO_DASHBOARD_SHOW_PRIVILEGES_MENU']) ? 'checked' : '' }}>
                                                    <span class="toggle-slider"></span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="sys-settings-toggle">
                                        <div class="sys-settings-toggle-top">
                                            <div>
                                                <p class="sys-settings-toggle-title">Resources menu <span class="env-key">TYRO_DASHBOARD_SHOW_RESOURCES_MENU</span></p>
                                                <p class="sys-settings-toggle-description">Displays the dynamic resources management menu in the administration sidebar.</p>
                                            </div>
                                            <div>
                                                <input type="hidden" name="TYRO_DASHBOARD_SHOW_RESOURCES_MENU" value="0">
                                                <label class="toggle-label">
                                                    <input type="checkbox" name="TYRO_DASHBOARD_SHOW_RESOURCES_MENU" value="1" class="toggle-input" {{ old('TYRO_DASHBOARD_SHOW_RESOURCES_MENU', $settings['TYRO_DASHBOARD_SHOW_RESOURCES_MENU']) ? 'checked' : '' }}>
                                                    <span class="toggle-slider"></span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="sys-settings-toggle">
                                        <div class="sys-settings-toggle-top">
                                            <div>
                                                <p class="sys-settings-toggle-title">Invitation system <span class="env-key">TYRO_DASHBOARD_ENABLE_INVITATION</span></p>
                                                <p class="sys-settings-toggle-description">Allows admins to invite new users by email. Disabling this hides the invitation UI and prevents new invitations from being sent.</p>
                                            </div>
                                            <div>
                                                <input type="hidden" name="TYRO_DASHBOARD_ENABLE_INVITATION" value="0">
                                                <label class="toggle-label">
                                                    <input type="checkbox" name="TYRO_DASHBOARD_ENABLE_INVITATION" value="1" class="toggle-input" {{ old('TYRO_DASHBOARD_ENABLE_INVITATION', $settings['TYRO_DASHBOARD_ENABLE_INVITATION']) ? 'checked' : '' }}>
                                                    <span class="toggle-slider"></span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="sys-settings-toggle">
                                        <div class="sys-settings-toggle-top">
                                            <div>
                                                <p class="sys-settings-toggle-title">Emailer <span class="env-key">TYRO_DASHBOARD_ENABLE_EMAILER</span></p>
                                                <p class="sys-settings-toggle-description">Enables the admin email composer and template system. Disabling this hides the emailer menu item and disables its routes.</p>
                                            </div>
                                            <div>
                                                <input type="hidden" name="TYRO_DASHBOARD_ENABLE_EMAILER" value="0">
                                                <label class="toggle-label">
                                                    <input type="checkbox" name="TYRO_DASHBOARD_ENABLE_EMAILER" value="1" class="toggle-input" {{ old('TYRO_DASHBOARD_ENABLE_EMAILER', $settings['TYRO_DASHBOARD_ENABLE_EMAILER']) ? 'checked' : '' }}>
                                                    <span class="toggle-slider"></span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="sys-settings-toggle">
                                        <div class="sys-settings-toggle-top">
                                            <div>
                                                <p class="sys-settings-toggle-title">Audit logs <span class="env-key">TYRO_DASHBOARD_ENABLE_AUDIT_LOGS</span></p>
                                                <p class="sys-settings-toggle-description">Logs user activities such as logins, profile updates, and admin actions for auditing and compliance purposes.</p>
                                            </div>
                                            <div>
                                                <input type="hidden" name="TYRO_DASHBOARD_ENABLE_AUDIT_LOGS" value="0">
                                                <label class="toggle-label">
                                                    <input type="checkbox" name="TYRO_DASHBOARD_ENABLE_AUDIT_LOGS" value="1" class="toggle-input" {{ old('TYRO_DASHBOARD_ENABLE_AUDIT_LOGS', $settings['TYRO_DASHBOARD_ENABLE_AUDIT_LOGS']) ? 'checked' : '' }}>
                                                    <span class="toggle-slider"></span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="sys-settings-toggle">
                                        <div class="sys-settings-toggle-top">
                                            <div>
                                                <p class="sys-settings-toggle-title">Profile photo upload <span class="env-key">TYRO_DASHBOARD_ENABLE_PROFILE_PHOTO</span></p>
                                                <p class="sys-settings-toggle-description">Lets users upload a custom profile photo. When disabled, only Gravatar or initials are shown.</p>
                                            </div>
                                            <div>
                                                <input type="hidden" name="TYRO_DASHBOARD_ENABLE_PROFILE_PHOTO" value="0">
                                                <label class="toggle-label">
                                                    <input type="checkbox" name="TYRO_DASHBOARD_ENABLE_PROFILE_PHOTO" value="1" class="toggle-input" {{ old('TYRO_DASHBOARD_ENABLE_PROFILE_PHOTO', $settings['TYRO_DASHBOARD_ENABLE_PROFILE_PHOTO']) ? 'checked' : '' }}>
                                                    <span class="toggle-slider"></span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="sys-settings-toggle">
                                        <div class="sys-settings-toggle-top">
                                            <div>
                                                <p class="sys-settings-toggle-title">Gravatar <span class="env-key">TYRO_DASHBOARD_ENABLE_GRAVATAR</span></p>
                                                <p class="sys-settings-toggle-description">Falls back to Gravatar images for users who haven't uploaded a custom profile photo.</p>
                                            </div>
                                            <div>
                                                <input type="hidden" name="TYRO_DASHBOARD_ENABLE_GRAVATAR" value="0">
                                                <label class="toggle-label">
                                                    <input type="checkbox" name="TYRO_DASHBOARD_ENABLE_GRAVATAR" value="1" class="toggle-input" {{ old('TYRO_DASHBOARD_ENABLE_GRAVATAR', $settings['TYRO_DASHBOARD_ENABLE_GRAVATAR']) ? 'checked' : '' }}>
                                                    <span class="toggle-slider"></span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
