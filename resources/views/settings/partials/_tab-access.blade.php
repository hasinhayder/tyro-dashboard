{{-- Roles & API --}}
            <div class="vtabs-panel" id="vtab-access">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Roles &amp; API</h3>
                    </div>
                    <div class="card-body">
                        <p class="sys-settings-section-description" style="margin-bottom:1rem;">Default role, API access and tokens.</p>

                        <div class="sys-settings-grid">
                            <div class="sys-settings-surface">
                                <h4 class="sys-settings-surface-title">Default role</h4>
                                <p class="sys-settings-surface-description">The role slug assigned to new users.</p>

                                <div class="form-group" style="margin-bottom:0;">
                                    <label for="DEFAULT_ROLE_SLUG" class="form-label">Default role slug <span class="env-key">DEFAULT_ROLE_SLUG</span></label>
                                    <input type="text" name="DEFAULT_ROLE_SLUG" id="DEFAULT_ROLE_SLUG"
                                           class="form-input" maxlength="100"
                                           value="{{ old('DEFAULT_ROLE_SLUG', $settings['DEFAULT_ROLE_SLUG']) }}">
                                    <p class="form-hint">Writes <code>DEFAULT_ROLE_SLUG</code> in <code>.env</code>.</p>
                                </div>
                            </div>

                            <div class="sys-settings-surface">
                                <h4 class="sys-settings-surface-title">API &amp; tokens</h4>
                                <p class="sys-settings-surface-description">Control API availability and token behavior.</p>

                                <div class="sys-settings-toggles" style="margin-bottom:0;">
                                    <div class="sys-settings-toggle">
                                        <div class="sys-settings-toggle-top">
                                            <div>
                                                <p class="sys-settings-toggle-title">Disable Tyro API <span class="env-key">TYRO_DISABLE_API</span></p>
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
                                                <p class="sys-settings-toggle-title">Disable Tyro commands <span class="env-key">TYRO_DISABLE_COMMANDS</span></p>
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
                                                <p class="sys-settings-toggle-title">Delete previous tokens on login <span class="env-key">DELETE_PREVIOUS_ACCESS_TOKENS_ON_LOGIN</span></p>
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
                                <h4 class="sys-settings-surface-title">API routing</h4>
                                <p class="sys-settings-surface-description">Set the API route prefix.</p>

                                <div class="form-group" style="margin-bottom:0;">
                                    <label for="TYRO_ROUTE_PREFIX" class="form-label">Route prefix <span class="env-key">TYRO_ROUTE_PREFIX</span></label>
                                    <input type="text" name="TYRO_ROUTE_PREFIX" id="TYRO_ROUTE_PREFIX"
                                           class="form-input" maxlength="50"
                                           value="{{ old('TYRO_ROUTE_PREFIX', $settings['TYRO_ROUTE_PREFIX']) }}">
                                    <p class="form-hint">API route prefix. Writes <code>TYRO_ROUTE_PREFIX</code>.</p>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
