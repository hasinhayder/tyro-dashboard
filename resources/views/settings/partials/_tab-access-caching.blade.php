{{-- Caching & audit --}}
            <div class="vtabs-panel" id="vtab-access-caching">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Caching &amp; audit</h3>
                    </div>
                    <div class="card-body">
                        <p class="sys-settings-section-description" style="margin-bottom:1rem;">RBAC cache and audit logging.</p>

                        <div class="sys-settings-grid">
                            <div class="sys-settings-surface">
                                <h4 class="sys-settings-surface-title">Cache driver</h4>
                                <p class="sys-settings-surface-description">Configure the cache store backend.</p>

                                <div class="form-group" style="margin-bottom:0;">
                                    <label for="TYRO_CACHE_STORE" class="form-label">Cache store <span class="env-key">TYRO_CACHE_STORE</span></label>
                                    <input type="text" name="TYRO_CACHE_STORE" id="TYRO_CACHE_STORE"
                                           class="form-input" maxlength="50"
                                           value="{{ old('TYRO_CACHE_STORE', $settings['TYRO_CACHE_STORE']) }}">
                                    <p class="form-hint">e.g. <code>redis</code>, <code>file</code>, <code>memcached</code>. Writes <code>TYRO_CACHE_STORE</code>.</p>
                                </div>
                            </div>

                            <div class="sys-settings-surface">
                                <h4 class="sys-settings-surface-title">Caching</h4>
                                <p class="sys-settings-surface-description">Control RBAC cache behavior.</p>

                                <div class="sys-settings-toggles" style="margin-bottom:0.85rem;">
                                    <div class="sys-settings-toggle">
                                        <div class="sys-settings-toggle-top">
                                            <div>
                                                <p class="sys-settings-toggle-title">Enable RBAC cache <span class="env-key">TYRO_CACHE_ENABLED</span></p>
                                                <p class="sys-settings-toggle-description">Caches RBAC permissions and roles to reduce database queries and improve performance.</p>
                                            </div>
                                            <div>
                                                <input type="hidden" name="TYRO_CACHE_ENABLED" value="0">
                                                <label class="toggle-label">
                                                    <input type="checkbox" name="TYRO_CACHE_ENABLED" value="1" class="toggle-input" {{ old('TYRO_CACHE_ENABLED', $settings['TYRO_CACHE_ENABLED']) !== false ? 'checked' : '' }}>
                                                    <span class="toggle-slider"></span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group" style="margin-bottom:0;">
                                    <label for="TYRO_CACHE_TTL" class="form-label">Cache TTL (seconds) <span class="env-key">TYRO_CACHE_TTL</span></label>
                                    <input type="number" name="TYRO_CACHE_TTL" id="TYRO_CACHE_TTL"
                                           class="form-input" min="0" max="86400"
                                           value="{{ old('TYRO_CACHE_TTL', $settings['TYRO_CACHE_TTL']) }}">
                                    <p class="form-hint" style="margin-top:0.35rem;">Default is 300 (5 minutes).</p>
                                </div>
                            </div>

                            <div class="sys-settings-surface">
                                <h4 class="sys-settings-surface-title">Audit logs</h4>
                                <p class="sys-settings-surface-description">Control audit log behaviour and retention.</p>

                                <div class="sys-settings-toggles" style="margin-bottom:0.85rem;">
                                    <div class="sys-settings-toggle">
                                        <div class="sys-settings-toggle-top">
                                            <div>
                                                <p class="sys-settings-toggle-title">Enable audit logs <span class="env-key">TYRO_AUDIT_ENABLED</span></p>
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
                                    <label for="TYRO_AUDIT_RETENTION_DAYS" class="form-label">Retention days <span class="env-key">TYRO_AUDIT_RETENTION_DAYS</span></label>
                                    <input type="number" name="TYRO_AUDIT_RETENTION_DAYS" id="TYRO_AUDIT_RETENTION_DAYS"
                                           class="form-input" min="1" max="3650"
                                           value="{{ old('TYRO_AUDIT_RETENTION_DAYS', $settings['TYRO_AUDIT_RETENTION_DAYS']) }}">
                                    <p class="form-hint">Writes <code>TYRO_AUDIT_RETENTION_DAYS</code>.</p>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
