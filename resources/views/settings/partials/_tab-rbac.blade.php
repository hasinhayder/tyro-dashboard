            {{-- Authorization Tab --}}
            <div class="vtabs-panel" id="vtab-rbac">
                <div class="card">
                    <div class="card-header" style="display:flex;align-items:center;justify-content:space-between;gap:1rem;">
                        <h3 class="card-title">Authorization</h3>
                        <button type="submit" form="systemSettingsForm" class="btn btn-primary btn-sm section-save-button">Save</button>
                    </div>
                    <div class="card-body">
                        <div class="sys-settings-section-intro">
                            <div class="sys-settings-section-copy">
                                <h4 class="sys-settings-section-heading">Manage Tyro RBAC environment configuration</h4>
                                <p class="sys-settings-section-description">Control audit logs, API availability, default roles, and tokens. All values are written to the <code>.env</code> file.</p>
                            </div>
                            <span class="sys-settings-section-badge">.env</span>
                        </div>

                        <div class="sys-settings-grid">
                            <div class="sys-settings-surface">
                                <h4 class="sys-settings-surface-title">Audit Logs</h4>
                                <p class="sys-settings-surface-description">Control audit log behaviour and retention.</p>

                                <div class="sys-settings-toggles" style="margin-bottom:0.85rem;">
                                    <div class="sys-settings-toggle">
                                        <div class="sys-settings-toggle-top">
                                            <div>
                                                <p class="sys-settings-toggle-title">Enable audit logs <span style="font-weight:normal">(<code>TYRO_AUDIT_ENABLED</code>)</span></p>
                                                <p class="sys-settings-toggle-description">Logs RBAC-related events such as role assignments, permission changes, and user management actions for auditing purposes.</p>
                                            </div>
                                            <div>
                                                <input type="hidden" name="TYRO_AUDIT_ENABLED" value="0">
                                                <label class="toggle-label">
                                                    <input type="checkbox" name="TYRO_AUDIT_ENABLED" value="1" class="toggle-input" id="tyro_audit_enabled" {{ old('TYRO_AUDIT_ENABLED', $settings['TYRO_AUDIT_ENABLED']) !== false ? 'checked' : '' }}>
                                                    <span class="toggle-slider"></span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group" style="margin-bottom:0;" id="tyro_audit_retention_group">
                                    <label for="TYRO_AUDIT_RETENTION_DAYS" class="form-label">Retention days (TYRO_AUDIT_RETENTION_DAYS)</label>
                                    <input type="number" name="TYRO_AUDIT_RETENTION_DAYS" id="TYRO_AUDIT_RETENTION_DAYS"
                                           class="form-input" min="1" max="3650"
                                           value="{{ old('TYRO_AUDIT_RETENTION_DAYS', $settings['TYRO_AUDIT_RETENTION_DAYS']) }}">
                                    <p class="form-hint">Writes <code>TYRO_AUDIT_RETENTION_DAYS</code>.</p>
                                </div>
                            </div>

                            <div class="sys-settings-surface">
                                <h4 class="sys-settings-surface-title">API &amp; Tokens</h4>
                                <p class="sys-settings-surface-description">Control API availability and token behavior.</p>

                                <div class="sys-settings-toggles" style="margin-bottom:0;">
                                    <div class="sys-settings-toggle">
                                        <div class="sys-settings-toggle-top">
                                            <div>
                                                <p class="sys-settings-toggle-title">Disable Tyro API <span style="font-weight:normal">(<code>TYRO_DISABLE_API</code>)</span></p>
                                                <p class="sys-settings-toggle-description">Disables all Tyro API routes and endpoints when enabled, useful for maintenance or security lockdown.</p>
                                            </div>
                                            <div>
                                                <input type="hidden" name="TYRO_DISABLE_API" value="0">
                                                <label class="toggle-label">
                                                    <input type="checkbox" name="TYRO_DISABLE_API" value="1" class="toggle-input" {{ old('TYRO_DISABLE_API', $settings['TYRO_DISABLE_API']) ? 'checked' : '' }}>
                                                    <span class="toggle-slider"></span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="sys-settings-toggle">
                                        <div class="sys-settings-toggle-top">
                                            <div>
                                                <p class="sys-settings-toggle-title">Disable Tyro commands <span style="font-weight:normal">(<code>TYRO_DISABLE_COMMANDS</code>)</span></p>
                                                <p class="sys-settings-toggle-description">Prevents Tyro Artisan commands from being registered, keeping the CLI clean in production environments.</p>
                                            </div>
                                            <div>
                                                <input type="hidden" name="TYRO_DISABLE_COMMANDS" value="0">
                                                <label class="toggle-label">
                                                    <input type="checkbox" name="TYRO_DISABLE_COMMANDS" value="1" class="toggle-input" {{ old('TYRO_DISABLE_COMMANDS', $settings['TYRO_DISABLE_COMMANDS']) ? 'checked' : '' }}>
                                                    <span class="toggle-slider"></span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="sys-settings-toggle">
                                        <div class="sys-settings-toggle-top">
                                            <div>
                                                <p class="sys-settings-toggle-title">Delete previous tokens on login <span style="font-weight:normal">(<code>DELETE_PREVIOUS_ACCESS_TOKENS_ON_LOGIN</code>)</span></p>
                                                <p class="sys-settings-toggle-description">Revokes all existing API tokens when a user logs in, forcing new token generation to improve security.</p>
                                            </div>
                                            <div>
                                                <input type="hidden" name="DELETE_PREVIOUS_ACCESS_TOKENS_ON_LOGIN" value="0">
                                                <label class="toggle-label">
                                                    <input type="checkbox" name="DELETE_PREVIOUS_ACCESS_TOKENS_ON_LOGIN" value="1" class="toggle-input" {{ old('DELETE_PREVIOUS_ACCESS_TOKENS_ON_LOGIN', $settings['DELETE_PREVIOUS_ACCESS_TOKENS_ON_LOGIN']) ? 'checked' : '' }}>
                                                    <span class="toggle-slider"></span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="sys-settings-surface">
                                <h4 class="sys-settings-surface-title">Default Role</h4>
                                <p class="sys-settings-surface-description">The role slug assigned to new users.</p>

                                <div class="form-group" style="margin-bottom:0;">
                                    <label for="DEFAULT_ROLE_SLUG" class="form-label">Default role slug (DEFAULT_ROLE_SLUG)</label>
                                    <input type="text" name="DEFAULT_ROLE_SLUG" id="DEFAULT_ROLE_SLUG"
                                           class="form-input" maxlength="100"
                                           value="{{ old('DEFAULT_ROLE_SLUG', $settings['DEFAULT_ROLE_SLUG']) }}">
                                    <p class="form-hint">Writes <code>DEFAULT_ROLE_SLUG</code> in <code>.env</code>.</p>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
