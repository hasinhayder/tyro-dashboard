<div class="grid-2" style="margin-bottom: 1.5rem;">
    {{-- Form components (including switch) --}}
    <div class="card">
        <div class="card-header">
            <h3 class="card-title" style="font-size: 1.0625rem;">Form Components</h3>
            <span class="badge badge-secondary">Inputs</span>
        </div>
        <div class="card-body">
            <div class="form-group">
                <label class="form-label">Email <span class="form-label-optional">(required)</span></label>
                <input class="form-input" type="email" placeholder="name@example.com" value="name@example.com" />
                <div class="form-hint">Used for notifications and sign-in.</div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Plan</label>
                    <select class="form-select">
                        <option>Starter</option>
                        <option selected>Pro</option>
                        <option>Enterprise</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">API Key</label>
                    <input class="form-input is-invalid" type="text" value="sk_live_••••" />
                    <div class="form-error">Invalid key format.</div>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Notes</label>
                <textarea class="form-textarea" placeholder="Add a short note…">Keep this short. Link to details.</textarea>
            </div>

            <div class="form-group" style="margin-bottom: 1rem;">
                <label class="form-label">Preferences</label>
                <div style="display:flex; flex-direction: column; gap: 0.75rem;">
                    <label class="toggle-label">
                        <input class="toggle-input" type="checkbox" checked>
                        <span class="toggle-slider"></span>
                        <span class="toggle-text">Enable notifications</span>
                    </label>
                    <label class="toggle-label">
                        <input class="toggle-input" type="checkbox">
                        <span class="toggle-slider"></span>
                        <span class="toggle-text">Maintenance mode</span>
                    </label>
                </div>
            </div>

            <div class="form-row">
                <div>
                    <div class="form-label" style="margin-bottom: 0.5rem;">Checkboxes</div>
                    <div class="checkbox-list">
                        <label class="checkbox-item">
                            <input type="checkbox" class="checkbox-input" checked>
                            <div>
                                <div style="font-size: 0.9375rem; font-weight: 600;">Read</div>
                                <div style="font-size: 0.8125rem; color: var(--muted-foreground);">Can view resources</div>
                            </div>
                        </label>
                        <label class="checkbox-item">
                            <input type="checkbox" class="checkbox-input">
                            <div>
                                <div style="font-size: 0.9375rem; font-weight: 600;">Write</div>
                                <div style="font-size: 0.8125rem; color: var(--muted-foreground);">Can edit resources</div>
                            </div>
                        </label>
                    </div>
                </div>
                <div>
                    <div class="form-label" style="margin-bottom: 0.5rem;">Radio</div>
                    <div style="display:flex; flex-direction: column; gap: 0.75rem;">
                        <label style="display:flex; align-items:center; gap: 0.625rem;">
                            <input type="radio" name="demo_radio" class="radio-input" checked>
                            <span style="font-size: 0.9375rem;">Daily summary</span>
                        </label>
                        <label style="display:flex; align-items:center; gap: 0.625rem;">
                            <input type="radio" name="demo_radio" class="radio-input">
                            <span style="font-size: 0.9375rem;">Weekly summary</span>
                        </label>
                    </div>
                </div>
            </div>

            <div style="display:flex; gap: 0.5rem; margin-top: 1rem; flex-wrap: wrap;">
                <a href="#" class="btn btn-primary btn-sm" onclick="return false;">Submit</a>
                <a href="#" class="btn btn-secondary btn-sm" onclick="return false;">Secondary</a>
                <a href="#" class="btn btn-ghost btn-sm" onclick="return false;">Ghost</a>
            </div>
        </div>
    </div>

    {{-- Rich text (Quill) + sparklines --}}
    @php($quillInitialHtml = '<h3>Announcement</h3><p>Use <strong>Quill</strong> for rich text content in forms.</p><ul><li>Bold / Italic</li><li>Lists</li><li>Links</li></ul><p><em>Tip:</em> copy the generated HTML below.</p>')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title" style="font-size: 1.0625rem;">Rich Text + Sparklines</h3>
            <span class="badge badge-secondary">Content</span>
        </div>
        <div class="card-body">
            <div style="border: 1px solid var(--border); border-radius: 10px; padding: 1rem; background: var(--background); margin-bottom: 1rem;">
                <div style="display:flex; align-items:center; justify-content: space-between; gap: 1rem; margin-bottom: 0.75rem;">
                    <div style="font-size: 0.875rem; color: var(--muted-foreground);">Rich text editor</div>
                    <span class="badge badge-primary">Quill.js</span>
                </div>
                <div id="td-quill-editor" style="min-height: 160px; background: var(--background);"></div>
                <script type="application/json" id="td-quill-initial">@json($quillInitialHtml)</script>
                <div style="margin-top: 0.75rem; padding: 0.75rem 1rem; border-radius: 8px; background: var(--muted); border: 1px solid var(--border);">
                    <div style="font-size: 0.8125rem; color: var(--muted-foreground); margin-bottom: 0.5rem;">Generated HTML</div>
                    <textarea id="td-quill-html" class="form-textarea" readonly style="min-height: 120px; font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, 'Liberation Mono', 'Courier New', monospace;"></textarea>
                </div>
            </div>

            <div style="display:flex; flex-direction: column; gap: 0.75rem;">
                @foreach($charts['sparklines'] as $s)
                    <div style="display:flex; align-items:center; justify-content: space-between; gap: 1rem; padding: 0.75rem 1rem; border: 1px solid var(--border); border-radius: 10px; background: var(--background);">
                        <div style="min-width:0;">
                            <div style="display:flex; align-items:center; gap: 0.5rem; flex-wrap: wrap;">
                                <div style="font-weight: 700;">{{ $s['label'] }}</div>
                                <span class="badge {{ $s['badge_class'] }}">{{ $s['badge_text'] }}</span>
                            </div>
                            <div style="font-size: 0.9375rem; color: var(--muted-foreground);">{{ $s['value'] }}</div>
                        </div>
                        <svg viewBox="0 0 162 24" width="162" height="24" preserveAspectRatio="none" style="display:block; color: var(--foreground); opacity: 0.9;">
                            <path d="{{ $s['path'] }}" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                        </svg>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
