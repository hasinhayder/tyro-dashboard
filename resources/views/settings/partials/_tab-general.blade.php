{{-- General --}}
            <div class="vtabs-panel active" id="vtab-general">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">General</h3>
                    </div>
                    <div class="card-body">
                        <p class="sys-settings-section-description" style="margin-bottom:1rem;">Dashboard branding, sidebar behaviour, notifications and demo content.</p>

                        <div class="sys-settings-grid">
                            <div class="sys-settings-surface">
                                <h4 class="sys-settings-surface-title">Branding</h4>
                                <p class="sys-settings-surface-description">Customize the dashboard app name, logo sizing, and favicon.</p>

                                <div class="form-group">
                                    <label for="TYRO_DASHBOARD_APP_NAME" class="form-label">App name <span class="env-key">TYRO_DASHBOARD_APP_NAME</span></label>
                                    <input type="text" name="TYRO_DASHBOARD_APP_NAME" id="TYRO_DASHBOARD_APP_NAME"
                                           class="form-input" maxlength="255"
                                           value="{{ old('TYRO_DASHBOARD_APP_NAME', $settings['TYRO_DASHBOARD_APP_NAME']) }}">
                                    <p class="form-hint">Displayed in the sidebar logo and page titles.</p>
                                </div>

                                <div class="form-group">
                                    <label for="TYRO_DASHBOARD_LOGO_HEIGHT" class="form-label">Logo height <span class="env-key">TYRO_DASHBOARD_LOGO_HEIGHT</span></label>
                                    <input type="text" name="TYRO_DASHBOARD_LOGO_HEIGHT" id="TYRO_DASHBOARD_LOGO_HEIGHT"
                                           class="form-input" maxlength="20"
                                           value="{{ old('TYRO_DASHBOARD_LOGO_HEIGHT', $settings['TYRO_DASHBOARD_LOGO_HEIGHT']) }}">
                                    <p class="form-hint">CSS height value e.g. <code>32px</code>, <code>3rem</code>.</p>
                                </div>

                                <div class="form-group" style="margin-bottom:0;">
                                    <label class="form-label">Favicon <span class="env-key">TYRO_DASHBOARD_FAVICON</span></label>
                                    <x-media-picker
                                        name="TYRO_DASHBOARD_FAVICON"
                                        :value="old('TYRO_DASHBOARD_FAVICON', $settings['TYRO_DASHBOARD_FAVICON'] ?? null)"
                                        output="thumb"
                                        preview="true"
                                        preview-position="left"
                                        preview-width="48px"
                                        preview-height="48px"
                                        button="primary"
                                        button-text="Select Favicon"
                                        width="100%"
                                        size="medium"
                                    />
                                    <p class="form-hint" style="margin-top:0.35rem;">Used as the shortcut icon / favicon in dashboard browser tabs.</p>
                                </div>
                            </div>

                            <div class="sys-settings-surface">
                                <h4 class="sys-settings-surface-title">Collapsible sidebar</h4>
                                <p class="sys-settings-surface-description">Toggle collapsible sidebar behavior.</p>

                                <div class="sys-settings-toggles" style="margin-bottom:0;">
                                    <div class="sys-settings-toggle">
                                        <div class="sys-settings-toggle-top">
                                            <div>
                                                <p class="sys-settings-toggle-title">Collapsible sidebar <span class="env-key">TYRO_DASHBOARD_COLLAPSIBLE_SIDEBAR</span></p>
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
                                </div>
                            </div>

                            <div class="sys-settings-surface">
                                <h4 class="sys-settings-surface-title">Notifications</h4>
                                <p class="sys-settings-surface-description">Choose between legacy alerts or toast-style notifications.</p>

                                <div class="form-group" style="margin-bottom:0.85rem;">
                                    <label for="TYRO_DASHBOARD_NOTIFICATION_STYLE" class="form-label">Notification style <span class="env-key">TYRO_DASHBOARD_NOTIFICATION_STYLE</span></label>
                                    <select name="TYRO_DASHBOARD_NOTIFICATION_STYLE" id="TYRO_DASHBOARD_NOTIFICATION_STYLE" class="form-select">
                                        <option value="legacy" {{ old('TYRO_DASHBOARD_NOTIFICATION_STYLE', $settings['TYRO_DASHBOARD_NOTIFICATION_STYLE']) === 'legacy' ? 'selected' : '' }}>Legacy</option>
                                        <option value="toast" {{ old('TYRO_DASHBOARD_NOTIFICATION_STYLE', $settings['TYRO_DASHBOARD_NOTIFICATION_STYLE']) === 'toast' ? 'selected' : '' }}>Toast</option>
                                    </select>
                                </div>

                                <div class="form-group" style="margin-bottom:0;">
                                    <label for="TYRO_DASHBOARD_TOAST_POSITION" class="form-label">Toast position <span class="env-key">TYRO_DASHBOARD_TOAST_POSITION</span></label>
                                    <select name="TYRO_DASHBOARD_TOAST_POSITION" id="TYRO_DASHBOARD_TOAST_POSITION" class="form-select">
                                        <option value="top-right" {{ old('TYRO_DASHBOARD_TOAST_POSITION', $settings['TYRO_DASHBOARD_TOAST_POSITION']) === 'top-right' ? 'selected' : '' }}>Top right</option>
                                        <option value="bottom-right" {{ old('TYRO_DASHBOARD_TOAST_POSITION', $settings['TYRO_DASHBOARD_TOAST_POSITION']) === 'bottom-right' ? 'selected' : '' }}>Bottom right</option>
                                    </select>
                                </div>
                            </div>

                            <div class="sys-settings-surface">
                                <h4 class="sys-settings-surface-title">Examples &amp; demo content</h4>
                                <p class="sys-settings-surface-description">Control the visibility of sample data and example pages.</p>

                                <div class="sys-settings-toggles" style="margin-bottom:0;">
                                    <div class="sys-settings-toggle">
                                        <div class="sys-settings-toggle-top">
                                            <div>
                                                <p class="sys-settings-toggle-title">Disable examples <span class="env-key">TYRO_DASHBOARD_DISABLE_EXAMPLES</span></p>
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

                        </div>
                    </div>
                </div>
            </div>
