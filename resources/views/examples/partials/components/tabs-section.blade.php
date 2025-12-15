{{-- Tabs + Dropdown + Forms + Rich text --}}
<div class="grid-2" style="margin-bottom: 1.5rem;">
    {{-- Tabbed section --}}
    <div class="card">
        <div class="card-header">
            <h3 class="card-title" style="font-size: 1.0625rem;">Tabbed Section</h3>
            <span class="badge badge-secondary">Tabs</span>
        </div>
        <div class="card-body">
            <div data-td-tabset>
                <div class="tabs">
                    <a href="#" class="tab-link active" data-td-tab="overview" onclick="return false;">Overview</a>
                    <a href="#" class="tab-link" data-td-tab="forms" onclick="return false;">Forms</a>
                    <a href="#" class="tab-link" data-td-tab="notes" onclick="return false;">Notes</a>
                </div>

                <div data-td-tab-panel="overview">
                    <div style="display:flex; flex-direction: column; gap: 0.75rem;">
                        <div style="font-size: 0.9375rem; color: var(--muted-foreground);">
                            Use tabs for settings pages, profile sections, or dashboards with multiple views.
                        </div>
                        <div class="badge-list">
                            <span class="badge badge-primary">Copy-ready</span>
                            <span class="badge badge-success">No extra CSS needed</span>
                            <span class="badge badge-secondary">Small JS snippet</span>
                        </div>
                        <div style="border: 1px solid var(--border); border-radius: 10px; padding: 1rem; background: var(--muted);">
                            <div style="display:flex; justify-content: space-between; gap: 1rem;">
                                <div>
                                    <div style="font-size: 0.875rem; color: var(--muted-foreground);">Deployments</div>
                                    <div style="font-size: 1.5rem; font-weight: 700;">12</div>
                                </div>
                                <div>
                                    <div style="font-size: 0.875rem; color: var(--muted-foreground);">Incidents</div>
                                    <div style="font-size: 1.5rem; font-weight: 700;">0</div>
                                </div>
                                <div>
                                    <div style="font-size: 0.875rem; color: var(--muted-foreground);">SLA</div>
                                    <div style="font-size: 1.5rem; font-weight: 700;">99.9%</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div data-td-tab-panel="forms" style="display:none;">
                    <div style="display:flex; flex-direction: column; gap: 0.75rem;">
                        <div style="font-size: 0.9375rem; color: var(--muted-foreground);">Quick form layout inside a tab.</div>
                        <div class="form-row">
                            <div class="form-group" style="margin-bottom: 0;">
                                <label class="form-label">Project name</label>
                                <input class="form-input" type="text" placeholder="Acme App" value="Acme App" />
                            </div>
                            <div class="form-group" style="margin-bottom: 0;">
                                <label class="form-label">Environment</label>
                                <select class="form-select">
                                    <option>Production</option>
                                    <option>Staging</option>
                                    <option>Development</option>
                                </select>
                            </div>
                        </div>
                        <div style="display:flex; gap: 0.5rem;">
                            <a href="#" class="btn btn-primary btn-sm" onclick="return false;">Save</a>
                            <a href="#" class="btn btn-ghost btn-sm" onclick="return false;">Cancel</a>
                        </div>
                    </div>
                </div>

                <div data-td-tab-panel="notes" style="display:none;">
                    <div style="font-size: 0.9375rem; color: var(--muted-foreground); margin-bottom: 0.75rem;">
                        Rich text preview example (useful for announcements / release notes).
                    </div>
                    <div style="border: 1px solid var(--border); border-radius: 10px; padding: 1rem; background: var(--background);">
                        <div style="font-weight: 700; font-size: 1.0625rem; margin-bottom: 0.5rem;">Release notes</div>
                        <div style="color: var(--muted-foreground); font-size: 0.9375rem; margin-bottom: 0.75rem;">Today we shipped a few improvements:</div>
                        <ul style="padding-left: 1.25rem; margin-bottom: 0.75rem; color: var(--foreground);">
                            <li><strong>Faster</strong> dashboard load time</li>
                            <li>Improved role management UX</li>
                            <li>Better error messages</li>
                        </ul>
                        <blockquote style="margin: 0; padding: 0.75rem 1rem; border-left: 3px solid var(--border); background: var(--muted); border-radius: 8px; color: var(--muted-foreground);">
                            Tip: keep announcements short and link to details.
                        </blockquote>
                        <div style="margin-top: 0.75rem; font-size: 0.9375rem;">
                            <a href="#" onclick="return false;" style="color: var(--foreground); text-decoration: underline;">View full changelog</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Dropdown menu --}}
    <div class="card">
        <div class="card-header">
            <h3 class="card-title" style="font-size: 1.0625rem;">Dropdown Menu</h3>
            <span class="badge badge-secondary">Menu</span>
        </div>
        <div class="card-body">
            <div style="display:flex; align-items:center; justify-content: space-between; gap: 1rem; margin-bottom: 1rem;">
                <div style="font-size: 0.9375rem; color: var(--muted-foreground);">Reusable dropdown using existing styles.</div>
                <div class="user-dropdown" data-td-dropdown>
                    <button type="button" class="user-dropdown-btn" data-td-dropdown-btn>
                        <div class="user-avatar" style="width: 28px; height: 28px; font-size: 0.75rem;">A</div>
                        <div class="user-info" style="line-height: 1.2;">
                            <div class="user-name" style="font-size: 0.875rem;">Actions</div>
                            <div class="user-role">Quick menu</div>
                        </div>
                        <svg class="user-dropdown-arrow" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div class="user-dropdown-menu">
                        <a href="#" class="dropdown-item" onclick="return false;">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 5v14m7-7H5"/></svg>
                            Create item
                        </a>
                        <a href="#" class="dropdown-item" onclick="return false;">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/></svg>
                            View list
                        </a>
                        <div class="dropdown-divider"></div>
                        <a href="#" class="dropdown-item dropdown-item-danger" onclick="return false;">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>
                            Destructive action
                        </a>
                    </div>
                </div>
            </div>

            <div style="border: 1px solid var(--border); border-radius: 10px; padding: 1rem; background: var(--muted);">
                <div style="font-size: 0.9375rem; font-weight: 600; margin-bottom: 0.5rem;">Common uses</div>
                <div class="badge-list">
                    <span class="badge badge-primary">Row actions</span>
                    <span class="badge badge-primary">Bulk actions</span>
                    <span class="badge badge-primary">Context menus</span>
                </div>
            </div>
        </div>
    </div>
</div>
