            <div class="vtabs-panel" id="vtab-rbac-advanced">
                <div class="card">
                    <div class="card-header" style="display:flex;align-items:center;justify-content:space-between;gap:1rem;">
                        <h3 class="card-title">Authorization Advanced</h3>
                        <button type="submit" form="systemSettingsForm" class="btn btn-primary btn-sm section-save-button">Save</button>
                    </div>
                    <div class="card-body">
                        <div class="sys-settings-section-intro">
                            <div class="sys-settings-section-copy">
                                <h4 class="sys-settings-section-heading">Advanced RBAC environment configuration</h4>
                                <p class="sys-settings-section-description">Fine-tune cache driver, cache store, and route prefix.</p>
                            </div>
                            <span class="sys-settings-section-badge">Advanced</span>
                        </div>

                        <div class="sys-settings-grid">
                            <div class="sys-settings-surface">
                                <h4 class="sys-settings-surface-title">Cache Driver</h4>
                                <p class="sys-settings-surface-description">Configure the cache store backend.</p>

                                <div class="form-group" style="margin-bottom:0;">
                                    <label for="TYRO_CACHE_STORE" class="form-label">Cache store (TYRO_CACHE_STORE)</label>
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
                                                <p class="sys-settings-toggle-title">Enable RBAC cache <span style="font-weight:normal">(<code>TYRO_CACHE_ENABLED</code>)</span></p>
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
                                    <label for="TYRO_CACHE_TTL" class="form-label">Cache TTL (seconds) (TYRO_CACHE_TTL)</label>
                                    <input type="number" name="TYRO_CACHE_TTL" id="TYRO_CACHE_TTL"
                                           class="form-input" min="0" max="86400"
                                           value="{{ old('TYRO_CACHE_TTL', $settings['TYRO_CACHE_TTL']) }}">
                                    <p class="form-hint" style="margin-top:0.35rem;">Default is 300 (5 minutes).</p>
                                </div>
                            </div>

                            <div class="sys-settings-surface">
                                <h4 class="sys-settings-surface-title">API Routing</h4>
                                <p class="sys-settings-surface-description">Set the API route prefix.</p>

                                <div class="form-group" style="margin-bottom:0;">
                                    <label for="TYRO_ROUTE_PREFIX" class="form-label">Route prefix (TYRO_ROUTE_PREFIX)</label>
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
