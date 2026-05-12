            {{-- Dashboard Tab --}}
            <div class="vtabs-panel active" id="vtab-dashboard">
                <div class="card">
                    <div class="card-header" style="display:flex;align-items:center;justify-content:space-between;gap:1rem;">
                        <h3 class="card-title">Dashboard</h3>
                        <button type="submit" form="systemSettingsForm" class="btn btn-primary btn-sm section-save-button">Save</button>
                    </div>
                    <div class="card-body">
                        <div class="sys-settings-section-intro">
                            <div class="sys-settings-section-copy">
                                <h4 class="sys-settings-section-heading">Manage tyro-dashboard environment configuration</h4>
                                <p class="sys-settings-section-description">Control branding, sidebar appearance, admin bar, notification style, and feature flags. All values are written to the <code>.env</code> file.</p>
                            </div>
                            <span class="sys-settings-section-badge">.env</span>
                        </div>

                        <div class="sys-settings-grid">
                            <div class="sys-settings-surface">
                                <h4 class="sys-settings-surface-title">Branding</h4>
                                <p class="sys-settings-surface-description">Customize the dashboard app name and sidebar colors.</p>

                                <div class="form-group">
                                    <label for="TYRO_DASHBOARD_APP_NAME" class="form-label">App name (TYRO_DASHBOARD_APP_NAME)</label>
                                    <input type="text" name="TYRO_DASHBOARD_APP_NAME" id="TYRO_DASHBOARD_APP_NAME"
                                           class="form-input" maxlength="255"
                                           value="{{ old('TYRO_DASHBOARD_APP_NAME', $settings['TYRO_DASHBOARD_APP_NAME']) }}">
                                    <p class="form-hint">Displayed in the sidebar logo and page titles.</p>
                                </div>

                                <div class="form-group">
                                    <label for="TYRO_DASHBOARD_LOGO_HEIGHT" class="form-label">Logo height (TYRO_DASHBOARD_LOGO_HEIGHT)</label>
                                    <input type="text" name="TYRO_DASHBOARD_LOGO_HEIGHT" id="TYRO_DASHBOARD_LOGO_HEIGHT"
                                           class="form-input" maxlength="20"
                                           value="{{ old('TYRO_DASHBOARD_LOGO_HEIGHT', $settings['TYRO_DASHBOARD_LOGO_HEIGHT']) }}">
                                    <p class="form-hint">CSS height value e.g. <code>32px</code>, <code>3rem</code>.</p>
                                </div>

                            </div>

                            <div class="sys-settings-surface">
                                <h4 class="sys-settings-surface-title">Collapsible Sidebar</h4>
                                <p class="sys-settings-surface-description">Toggle collapsible sidebar and disable example sections.</p>

                                <div class="sys-settings-toggles" style="margin-bottom:0;">
                                    <div class="sys-settings-toggle">
                                        <div class="sys-settings-toggle-top">
                                            <div>
                                                <p class="sys-settings-toggle-title">Collapsible sidebar <span style="font-weight:normal">(<code>TYRO_DASHBOARD_COLLAPSIBLE_SIDEBAR</code>)</span></p>
                                                <p class="sys-settings-toggle-description">When enabled, the sidebar can be collapsed to a narrow icon-only state, giving more horizontal space for page content.</p>
                                            </div>
                                            <div>
                                                <input type="hidden" name="TYRO_DASHBOARD_COLLAPSIBLE_SIDEBAR" value="0">
                                                <label class="toggle-label">
                                                    <input type="checkbox" name="TYRO_DASHBOARD_COLLAPSIBLE_SIDEBAR" value="1" class="toggle-input" {{ old('TYRO_DASHBOARD_COLLAPSIBLE_SIDEBAR', $settings['TYRO_DASHBOARD_COLLAPSIBLE_SIDEBAR']) ? 'checked' : '' }}>
                                                    <span class="toggle-slider"></span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="sys-settings-toggle">
                                        <div class="sys-settings-toggle-top">
                                            <div>
                                                <p class="sys-settings-toggle-title">Disable examples <span style="font-weight:normal">(<code>TYRO_DASHBOARD_DISABLE_EXAMPLES</code>)</span></p>
                                                <p class="sys-settings-toggle-description">Hides example pages, sample data, and demo content from the dashboard sidebar and navigation.</p>
                                            </div>
                                            <div>
                                                <input type="hidden" name="TYRO_DASHBOARD_DISABLE_EXAMPLES" value="0">
                                                <label class="toggle-label">
                                                    <input type="checkbox" name="TYRO_DASHBOARD_DISABLE_EXAMPLES" value="1" class="toggle-input" {{ old('TYRO_DASHBOARD_DISABLE_EXAMPLES', $settings['TYRO_DASHBOARD_DISABLE_EXAMPLES']) ? 'checked' : '' }}>
                                                    <span class="toggle-slider"></span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="sys-settings-surface">
                                <h4 class="sys-settings-surface-title">Feature Flags</h4>
                                <p class="sys-settings-surface-description">Enable or disable dashboard features.</p>

                                <div class="sys-settings-toggles" style="margin-bottom:0;">
                                    <div class="sys-settings-toggle">
                                        <div class="sys-settings-toggle-top">
                                            <div>
                                                <p class="sys-settings-toggle-title">Roles menu <span style="font-weight:normal">(<code>TYRO_DASHBOARD_SHOW_ROLES_MENU</code>)</span></p>
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
                                                <p class="sys-settings-toggle-title">Privileges menu <span style="font-weight:normal">(<code>TYRO_DASHBOARD_SHOW_PRIVILEGES_MENU</code>)</span></p>
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
                                                <p class="sys-settings-toggle-title">Resources menu <span style="font-weight:normal">(<code>TYRO_DASHBOARD_SHOW_RESOURCES_MENU</code>)</span></p>
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
                                                <p class="sys-settings-toggle-title">Invitation system <span style="font-weight:normal">(<code>TYRO_DASHBOARD_ENABLE_INVITATION</code>)</span></p>
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
                                                <p class="sys-settings-toggle-title">Audit logs <span style="font-weight:normal">(<code>TYRO_DASHBOARD_ENABLE_AUDIT_LOGS</code>)</span></p>
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
                                                <p class="sys-settings-toggle-title">Profile photo upload <span style="font-weight:normal">(<code>TYRO_DASHBOARD_ENABLE_PROFILE_PHOTO</code>)</span></p>
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
                                                <p class="sys-settings-toggle-title">Gravatar <span style="font-weight:normal">(<code>TYRO_DASHBOARD_ENABLE_GRAVATAR</code>)</span></p>
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

                            <div class="sys-settings-surface">
                                <h4 class="sys-settings-surface-title">Notifications</h4>
                                <p class="sys-settings-surface-description">Choose between legacy alerts or toast-style notifications.</p>

                                <div class="form-group" style="margin-bottom:0.85rem;">
                                    <label for="TYRO_DASHBOARD_NOTIFICATION_STYLE" class="form-label">Notification style (TYRO_DASHBOARD_NOTIFICATION_STYLE)</label>
                                    <select name="TYRO_DASHBOARD_NOTIFICATION_STYLE" id="TYRO_DASHBOARD_NOTIFICATION_STYLE" class="form-select">
                                        <option value="legacy" {{ old('TYRO_DASHBOARD_NOTIFICATION_STYLE', $settings['TYRO_DASHBOARD_NOTIFICATION_STYLE']) === 'legacy' ? 'selected' : '' }}>Legacy</option>
                                        <option value="toast" {{ old('TYRO_DASHBOARD_NOTIFICATION_STYLE', $settings['TYRO_DASHBOARD_NOTIFICATION_STYLE']) === 'toast' ? 'selected' : '' }}>Toast</option>
                                    </select>
                                </div>

                                <div class="form-group" style="margin-bottom:0;">
                                    <label for="TYRO_DASHBOARD_TOAST_POSITION" class="form-label">Toast position (TYRO_DASHBOARD_TOAST_POSITION)</label>
                                    <select name="TYRO_DASHBOARD_TOAST_POSITION" id="TYRO_DASHBOARD_TOAST_POSITION" class="form-select">
                                        <option value="top-right" {{ old('TYRO_DASHBOARD_TOAST_POSITION', $settings['TYRO_DASHBOARD_TOAST_POSITION']) === 'top-right' ? 'selected' : '' }}>Top right</option>
                                        <option value="bottom-right" {{ old('TYRO_DASHBOARD_TOAST_POSITION', $settings['TYRO_DASHBOARD_TOAST_POSITION']) === 'bottom-right' ? 'selected' : '' }}>Bottom right</option>
                                    </select>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
