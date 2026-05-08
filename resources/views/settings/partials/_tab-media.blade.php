        {{-- Media Tab --}}
        <div class="vtabs-panel" id="vtab-media">
            <div class="card">
                <div class="card-header" style="display:flex;align-items:center;justify-content:space-between;gap:1rem;">
                    <h3 class="card-title">Media</h3>
                    <button type="submit" form="systemSettingsForm" class="btn btn-primary btn-sm section-save-button">Save</button>
                </div>
                <div class="card-body">
                    <div class="sys-settings-section-intro">
                        <div class="sys-settings-section-copy">
                            <h4 class="sys-settings-section-heading">Media Library — Image Import API Keys</h4>
                            <p class="sys-settings-section-description">Configure API keys for external image providers (Freepik, Pexels, Unsplash, Pixabay). These keys are used by the Image Importer in the Media Library to search and import stock photos. All values are written to the <code>.env</code> file.</p>
                        </div>
                        <span class="sys-settings-section-badge">.env</span>
                    </div>

                    <div class="sys-settings-grid">
                        <div class="sys-settings-surface">
                            <h4 class="sys-settings-surface-title">Upload Settings</h4>
                            <p class="sys-settings-surface-description">Maximum file size for media uploads in kilobytes.</p>

                            <div class="form-group" style="margin-bottom:0;">
                                <label for="TYRO_DASHBOARD_MEDIA_MAX_SIZE" class="form-label">Max upload size (KB) (TYRO_DASHBOARD_MEDIA_MAX_SIZE)</label>
                                <input type="number" name="TYRO_DASHBOARD_MEDIA_MAX_SIZE" id="TYRO_DASHBOARD_MEDIA_MAX_SIZE"
                                       class="form-input" min="1" max="1048576"
                                       value="{{ old('TYRO_DASHBOARD_MEDIA_MAX_SIZE', $settings['TYRO_DASHBOARD_MEDIA_MAX_SIZE']) }}">
                                <p class="form-hint">Default is 10240 (10MB). Writes <code>TYRO_DASHBOARD_MEDIA_MAX_SIZE</code> in <code>.env</code>.</p>
                            </div>
                        </div>

                        <div class="sys-settings-surface">
                            <h4 class="sys-settings-surface-title">Freepik</h4>
                            <p class="sys-settings-surface-description">API key for searching and importing Freepik images.</p>

                            <div class="form-group" style="margin-bottom:0;">
                                <label for="TYRO_DASHBOARD_FREEPIK_KEY" class="form-label">Freepik API Key (TYRO_DASHBOARD_FREEPIK_KEY)</label>
                                 <input type="password" name="TYRO_DASHBOARD_FREEPIK_KEY" id="TYRO_DASHBOARD_FREEPIK_KEY"
                                       class="form-input" maxlength="255"
                                       value="{{ old('TYRO_DASHBOARD_FREEPIK_KEY', $settings['TYRO_DASHBOARD_FREEPIK_KEY']) }}">
                                <p class="form-hint">Writes <code>TYRO_DASHBOARD_FREEPIK_KEY</code> in <code>.env</code>. Get yours at <a href="https://www.freepik.com/api" target="_blank" rel="noopener">freepik.com/api</a>.</p>
                            </div>
                        </div>

                        <div class="sys-settings-surface">
                            <h4 class="sys-settings-surface-title">Pexels</h4>
                            <p class="sys-settings-surface-description">API key for searching and importing Pexels images.</p>

                            <div class="form-group" style="margin-bottom:0;">
                                <label for="TYRO_DASHBOARD_PEXELS_KEY" class="form-label">Pexels API Key (TYRO_DASHBOARD_PEXELS_KEY)</label>
                                 <input type="password" name="TYRO_DASHBOARD_PEXELS_KEY" id="TYRO_DASHBOARD_PEXELS_KEY"
                                       class="form-input" maxlength="255"
                                       value="{{ old('TYRO_DASHBOARD_PEXELS_KEY', $settings['TYRO_DASHBOARD_PEXELS_KEY']) }}">
                                <p class="form-hint">Writes <code>TYRO_DASHBOARD_PEXELS_KEY</code> in <code>.env</code>. Get yours at <a href="https://www.pexels.com/api/" target="_blank" rel="noopener">pexels.com/api</a>.</p>
                            </div>
                        </div>

                        <div class="sys-settings-surface">
                            <h4 class="sys-settings-surface-title">Unsplash</h4>
                            <p class="sys-settings-surface-description">Access key for searching and importing Unsplash images.</p>

                            <div class="form-group" style="margin-bottom:0;">
                                <label for="TYRO_DASHBOARD_UNSPLASH_ACCESS_KEY" class="form-label">Unsplash Access Key (TYRO_DASHBOARD_UNSPLASH_ACCESS_KEY)</label>
                                 <input type="password" name="TYRO_DASHBOARD_UNSPLASH_ACCESS_KEY" id="TYRO_DASHBOARD_UNSPLASH_ACCESS_KEY"
                                       class="form-input" maxlength="255"
                                       value="{{ old('TYRO_DASHBOARD_UNSPLASH_ACCESS_KEY', $settings['TYRO_DASHBOARD_UNSPLASH_ACCESS_KEY']) }}">
                                <p class="form-hint">Writes <code>TYRO_DASHBOARD_UNSPLASH_ACCESS_KEY</code> in <code>.env</code>. Get yours at <a href="https://unsplash.com/developers" target="_blank" rel="noopener">unsplash.com/developers</a>.</p>
                            </div>
                        </div>

                        <div class="sys-settings-surface">
                            <h4 class="sys-settings-surface-title">Pixabay</h4>
                            <p class="sys-settings-surface-description">API key for searching and importing Pixabay images.</p>

                            <div class="form-group" style="margin-bottom:0;">
                                <label for="TYRO_DASHBOARD_PIXABAY_KEY" class="form-label">Pixabay API Key (TYRO_DASHBOARD_PIXABAY_KEY)</label>
                                 <input type="password" name="TYRO_DASHBOARD_PIXABAY_KEY" id="TYRO_DASHBOARD_PIXABAY_KEY"
                                       class="form-input" maxlength="255"
                                       value="{{ old('TYRO_DASHBOARD_PIXABAY_KEY', $settings['TYRO_DASHBOARD_PIXABAY_KEY']) }}">
                                <p class="form-hint">Writes <code>TYRO_DASHBOARD_PIXABAY_KEY</code> in <code>.env</code>. Get yours at <a href="https://pixabay.com/api/docs/" target="_blank" rel="noopener">pixabay.com/api/docs</a>.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div><!-- /vtab-media -->
