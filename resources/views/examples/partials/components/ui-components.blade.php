{{-- Reusable UI components showcase: <x-tyro-dashboard::*> and <x-tyro-dashboard-media> --}}
@php
    try {
        $demoMedia = \HasinHayder\TyroDashboard\Models\Media::whereNotNull('path')->orderBy('id')->first();
    } catch (\Throwable $e) {
        $demoMedia = null;
    }
    $demoMediaId = $demoMedia?->id;
@endphp

<x-tyro-dashboard::card title="Reusable Components" class="card-outer-section" style="margin-top: 1.25rem; margin-bottom: 1.5rem;">
    <x-slot:description>Drop-in Blade components. Each block below shows the tag you would use to assemble dashboard pages.</x-slot:description>
    <x-slot:actions>
        <x-tyro-dashboard::badge variant="secondary">Additive</x-tyro-dashboard::badge>
        <x-tyro-dashboard::badge variant="success">No new CSS</x-tyro-dashboard::badge>
    </x-slot:actions>

    <div class="stats-grid" style="margin-bottom: 1.5rem;">
        <x-tyro-dashboard::stat label="Revenue" value="$48,230" variant="success" change="+12.4% vs last month" trend="up" />
        <x-tyro-dashboard::stat label="New Signups" value="1,284" variant="primary" change="+6.1% this week" trend="up" />
        <x-tyro-dashboard::stat label="Open Tickets" value="42" variant="warning" change="-3 since yesterday" trend="down" />
        <x-tyro-dashboard::stat label="Error Rate" value="0.18%" variant="danger" change="within target" trend="none" />
        <x-tyro-dashboard::stat label="Avg. Session" value="4m 12s" variant="info" />
    </div>

    <div class="grid-2" style="margin-bottom: 1.5rem;">
        <x-tyro-dashboard::card title="Card with everything">
            <x-slot:description>A header, body, and footer — all optional.</x-slot:description>
            <x-slot:actions>
                <x-tyro-dashboard::badge variant="success">+12%</x-tyro-dashboard::badge>
                <a href="#" class="btn btn-secondary btn-sm" onclick="return false;">Settings</a>
            </x-slot:actions>
            This is the card body. Pass any content here — text, tables, charts, or forms.
            <x-slot:footer>Updated 5 minutes ago</x-slot:footer>
        </x-tyro-dashboard::card>

        <x-tyro-dashboard::card title="Avatars & Badges">
            <div style="display:flex; flex-direction:column; gap:1rem;">
                <div style="display:flex; align-items:center; gap:0.75rem; flex-wrap:wrap;">
                    <x-tyro-dashboard::avatar :user="$user" size="sm" />
                    <x-tyro-dashboard::avatar :user="$user" />
                    <x-tyro-dashboard::avatar :user="$user" size="lg" />
                    <x-tyro-dashboard::avatar :user="$user" size="64px" />
                </div>
                <div style="display:flex; gap:0.5rem; flex-wrap:wrap;">
                    <x-tyro-dashboard::badge variant="primary">Primary</x-tyro-dashboard::badge>
                    <x-tyro-dashboard::badge variant="success">Success</x-tyro-dashboard::badge>
                    <x-tyro-dashboard::badge variant="warning">Warning</x-tyro-dashboard::badge>
                    <x-tyro-dashboard::badge variant="danger">Danger</x-tyro-dashboard::badge>
                    <x-tyro-dashboard::badge variant="secondary">Secondary</x-tyro-dashboard::badge>
                    <x-tyro-dashboard::badge variant="info">Info</x-tyro-dashboard::badge>
                </div>
            </div>
        </x-tyro-dashboard::card>
    </div>

    <div class="grid-2" style="margin-bottom: 1.5rem;">
        <x-tyro-dashboard::card title="Alerts">
            <div style="display:flex; flex-direction:column; gap:0.75rem;">
                <x-tyro-dashboard::alert variant="success" title="All systems operational">Queues are healthy and latency is stable.</x-tyro-dashboard::alert>
                <x-tyro-dashboard::alert variant="warning" title="Heads up">A few records are waiting for approval.</x-tyro-dashboard::alert>
                <x-tyro-dashboard::alert variant="error" title="Action required">Storage is nearing its limit.</x-tyro-dashboard::alert>
                <x-tyro-dashboard::alert variant="info" title="Did you know?">You can publish any of these components to override them.</x-tyro-dashboard::alert>
            </div>
        </x-tyro-dashboard::card>

        <x-tyro-dashboard::card title="Progress">
            <div style="display:flex; flex-direction:column; gap:1rem;">
                <x-tyro-dashboard::progress :value="72" variant="success" label="Onboarding Flow" show-label />
                <x-tyro-dashboard::progress :value="44" variant="warning" label="Audit Log" show-label />
                <x-tyro-dashboard::progress :value="18" variant="primary" label="Billing Webhooks" show-label />
                <x-tyro-dashboard::progress :value="91" variant="info" />
                <x-tyro-dashboard::progress :value="100" variant="success" />
            </div>
        </x-tyro-dashboard::card>
    </div>

    <div class="grid-2" style="margin-bottom: 1.5rem;">
        <x-tyro-dashboard::card title="Toggles">
            <div style="display:flex; flex-direction:column; gap:1rem;">
                <x-tyro-dashboard::toggle name="email_digest" label="Email digest" checked />
                <x-tyro-dashboard::toggle name="two_factor" label="Two-factor auth" />
                <x-tyro-dashboard::toggle name="maintenance" label="Maintenance mode" disabled />
                <div style="display:flex; align-items:center; gap:0.75rem; flex-wrap:wrap;">
                    <x-tyro-dashboard::toggle name="dnd" checked />
                    <x-tyro-dashboard::badge variant="warning">no label, on</x-tyro-dashboard::badge>
                </div>
                <div style="display:flex; align-items:center; gap:1rem; flex-wrap:wrap; margin-top:0.25rem;">
                    <x-tyro-dashboard::toggle name="c1" label="Primary" color="primary" checked />
                    <x-tyro-dashboard::toggle name="c2" label="Success" color="success" checked />
                    <x-tyro-dashboard::toggle name="c3" label="Warning" color="warning" checked />
                    <x-tyro-dashboard::toggle name="c4" label="Danger" color="danger" checked />
                    <x-tyro-dashboard::toggle name="c5" label="Info" color="info" checked />
                </div>
                <div style="display:flex; align-items:center; gap:1rem; flex-wrap:wrap;">
                    <x-tyro-dashboard::toggle name="c6" label="Primary" color="primary" />
                    <x-tyro-dashboard::toggle name="c7" label="Success" color="success" />
                    <x-tyro-dashboard::toggle name="c8" label="Danger" color="danger" />
                </div>
            </div>
        </x-tyro-dashboard::card>

        <x-tyro-dashboard::card title="Mixed Controls">
            <div style="display:flex; flex-direction:column; gap:1rem;">
                <x-tyro-dashboard::toggle name="sync" label="Auto-sync" checked />
                <div style="display:flex; align-items:center; justify-content:space-between; gap:0.75rem;">
                    <span class="toggle-text">Notifications</span>
                    <x-tyro-dashboard::toggle name="notifications" checked />
                </div>
                <div style="display:flex; align-items:center; justify-content:space-between; gap:0.75rem;">
                    <span class="toggle-text">Dark mode</span>
                    <x-tyro-dashboard::toggle name="dark_mode" />
                </div>
            </div>
        </x-tyro-dashboard::card>
    </div>

    <div class="grid-2" style="margin-bottom: 1.5rem;">
        <x-tyro-dashboard::card title="Checkboxes">
            <div style="display:flex; flex-direction:column; gap:1rem;">
                <x-tyro-dashboard::checkbox name="cb_email_digest" label="Email digest" checked />
                <x-tyro-dashboard::checkbox name="cb_two_factor" label="Two-factor auth" />
                <x-tyro-dashboard::checkbox name="cb_maintenance" label="Maintenance mode" disabled />
                <div style="display:flex; align-items:center; gap:0.75rem; flex-wrap:wrap;">
                    <x-tyro-dashboard::checkbox name="cb_dnd" checked />
                    <x-tyro-dashboard::badge variant="warning">no label, on</x-tyro-dashboard::badge>
                </div>
                <div style="display:flex; align-items:center; gap:1rem; flex-wrap:wrap; margin-top:0.25rem;">
                    <x-tyro-dashboard::checkbox name="cb_c1" label="Primary" color="primary" checked />
                    <x-tyro-dashboard::checkbox name="cb_c2" label="Success" color="success" checked />
                    <x-tyro-dashboard::checkbox name="cb_c3" label="Warning" color="warning" checked />
                    <x-tyro-dashboard::checkbox name="cb_c4" label="Danger" color="danger" checked />
                    <x-tyro-dashboard::checkbox name="cb_c5" label="Info" color="info" checked />
                </div>
                <div style="display:flex; align-items:center; gap:1rem; flex-wrap:wrap;">
                    <x-tyro-dashboard::checkbox name="cb_c6" label="Primary" color="primary" />
                    <x-tyro-dashboard::checkbox name="cb_c7" label="Success" color="success" />
                    <x-tyro-dashboard::checkbox name="cb_c8" label="Danger" color="danger" />
                </div>
            </div>
        </x-tyro-dashboard::card>

        <x-tyro-dashboard::card title="Checkbox Groups">
            <div style="display:flex; flex-direction:column; gap:0.75rem;">
                <x-tyro-dashboard::checkbox name="cb_feature_api" label="API access" color="primary" checked />
                <x-tyro-dashboard::checkbox name="cb_feature_webhooks" label="Webhooks" color="success" />
                <x-tyro-dashboard::checkbox name="cb_feature_exports" label="Bulk exports" color="warning" />
                <x-tyro-dashboard::checkbox name="cb_feature_audit" label="Audit logging" color="info" checked />
            </div>
        </x-tyro-dashboard::card>
    </div>

    <div class="grid-2" style="margin-bottom: 1.5rem;">
        <x-tyro-dashboard::card title="Indeterminate State">
            <div style="display:flex; flex-direction:column; gap:1rem;">
                <x-tyro-dashboard::checkbox name="cb_indet_default" label="Default indeterminate" indeterminate />
                <div style="display:flex; align-items:center; gap:1rem; flex-wrap:wrap;">
                    <x-tyro-dashboard::checkbox name="cb_indet_p" label="Primary" color="primary" indeterminate />
                    <x-tyro-dashboard::checkbox name="cb_indet_s" label="Success" color="success" indeterminate />
                    <x-tyro-dashboard::checkbox name="cb_indet_w" label="Warning" color="warning" indeterminate />
                    <x-tyro-dashboard::checkbox name="cb_indet_d" label="Danger" color="danger" indeterminate />
                    <x-tyro-dashboard::checkbox name="cb_indet_i" label="Info" color="info" indeterminate />
                </div>
            </div>
        </x-tyro-dashboard::card>
    </div>

    <div class="grid-2" style="margin-bottom: 1.5rem;">
        <x-tyro-dashboard::card title="Dropdowns">
            <div style="display:flex; gap:1rem; flex-wrap:wrap; align-items:flex-start;">
                <x-tyro-dashboard::dropdown>
                    <x-slot:trigger>
                        <button type="button" class="btn btn-primary btn-sm">
                            Actions
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:16px;height:16px;margin-left:0.25rem;"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                        </button>
                    </x-slot:trigger>
                    <x-tyro-dashboard::dropdown-item icon='<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>' href="#">Profile</x-tyro-dashboard::dropdown-item>
                    <x-tyro-dashboard::dropdown-item icon='<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317a2 2 0 013.35 0 2 2 0 003.05 1.276 2 2 0 012.79 2.79 2 2 0 001.276 3.05 2 2 0 010 3.35 2 2 0 00-1.276 3.05 2 2 0 01-2.79 2.79 2 2 0 00-3.05 1.276 2 2 0 01-3.35 0 2 2 0 00-3.05-1.276 2 2 0 01-2.79-2.79 2 2 0 00-1.276-3.05 2 2 0 010-3.35 2 2 0 001.276-3.05 2 2 0 012.79-2.79 2 2 0 003.05-1.276z"/><circle cx="12" cy="12" r="3"/></svg>' href="#">Settings</x-tyro-dashboard::dropdown-item>
                    <x-tyro-dashboard::dropdown-item href="#">Billing (No Icon)</x-tyro-dashboard::dropdown-item>
                    <x-tyro-dashboard::dropdown-divider />
                    <x-tyro-dashboard::dropdown-item variant="danger" icon='<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>'>Sign out</x-tyro-dashboard::dropdown-item>
                </x-tyro-dashboard::dropdown>

                <x-tyro-dashboard::dropdown align="end">
                    <x-slot:trigger>
                        <button type="button" class="btn btn-secondary btn-sm">Align end</button>
                    </x-slot:trigger>
                    <x-tyro-dashboard::dropdown-item>Open</x-tyro-dashboard::dropdown-item>
                    <x-tyro-dashboard::dropdown-item>Duplicate</x-tyro-dashboard::dropdown-item>
                    <x-tyro-dashboard::dropdown-divider />
                    <x-tyro-dashboard::dropdown-item variant="danger">Delete</x-tyro-dashboard::dropdown-item>
                </x-tyro-dashboard::dropdown>

                <x-tyro-dashboard::dropdown align="center">
                    <x-slot:trigger>
                        <span class="badge badge-info" style="cursor:pointer;">Hover/Click</span>
                    </x-slot:trigger>
                    <x-tyro-dashboard::dropdown-item>Centered item</x-tyro-dashboard::dropdown-item>
                    <x-tyro-dashboard::dropdown-item>Another</x-tyro-dashboard::dropdown-item>
                </x-tyro-dashboard::dropdown>
            </div>
        </x-tyro-dashboard::card>

        <x-tyro-dashboard::card title="Default Trigger">
            <p class="page-description" style="margin:0 0 1rem;">Omit the trigger slot to get a default button. Use <code>title="…"</code> to change its label.</p>
            <div style="display:flex; gap:1rem; flex-wrap:wrap; align-items:flex-start;">
                <x-tyro-dashboard::dropdown>
                    <x-tyro-dashboard::dropdown-item>First</x-tyro-dashboard::dropdown-item>
                    <x-tyro-dashboard::dropdown-item>Second</x-tyro-dashboard::dropdown-item>
                </x-tyro-dashboard::dropdown>

                <x-tyro-dashboard::dropdown title="Export">
                    <x-tyro-dashboard::dropdown-item onclick="alert('Exporting as CSV…')">Export as CSV</x-tyro-dashboard::dropdown-item>
                    <x-tyro-dashboard::dropdown-item onclick="alert('Exporting as JSON…')">Export as JSON</x-tyro-dashboard::dropdown-item>
                    <x-tyro-dashboard::dropdown-divider />
                    <x-tyro-dashboard::dropdown-item variant="danger" onclick="alert('Cancelled export')">Cancel</x-tyro-dashboard::dropdown-item>
                </x-tyro-dashboard::dropdown>
            </div>
        </x-tyro-dashboard::card>
    </div>

    <div class="grid-2" style="margin-bottom: 1.5rem;">
        <x-tyro-dashboard::card title="Selects">
            <div style="display:flex; flex-direction:column; gap:1rem;">
                <x-tyro-dashboard::select name="country" label="Country" placeholder="Choose a country" value="bd" :options="['us' => 'United States', 'bd' => 'Bangladesh', 'uk' => 'United Kingdom', 'ca' => 'Canada']" />

                <x-tyro-dashboard::select name="role" label="Role" size="sm" icon='<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>' :options="['admin' => 'Administrator', 'editor' => 'Editor', 'viewer' => 'Viewer']" hint="Controls what this user can access." />

                <x-tyro-dashboard::select name="timezone" label="Timezone" required>
                    <option value="" disabled hidden>Select…</option>
                    <option value="utc">UTC</option>
                    <option value="est" selected>EST (New York)</option>
                    <option value="bst">BST (London)</option>
                </x-tyro-dashboard::select>
            </div>
        </x-tyro-dashboard::card>

        <x-tyro-dashboard::card title="Select States">
            <div style="display:flex; flex-direction:column; gap:1rem;">
                <x-tyro-dashboard::select name="large" label="Large size" size="lg" :options="['a' => 'Option A', 'b' => 'Option B']" />

                <x-tyro-dashboard::select name="err" label="With error" variant="error" error="Please select a valid option." :options="['a' => 'Option A', 'b' => 'Option B']" />

                <x-tyro-dashboard::select name="dis" label="Disabled" disabled :options="['a' => 'Option A', 'b' => 'Option B']" />

                <x-tyro-dashboard::select name="tags" label="Multiple" multiple :value="['php', 'js']" :options="['php' => 'PHP', 'js' => 'JavaScript', 'go' => 'Go', 'rb' => 'Ruby']" />
            </div>
        </x-tyro-dashboard::card>
    </div>

    <x-tyro-dashboard::card title="Media">
        <x-slot:actions>
            <x-tyro-dashboard::badge variant="info">class-based</x-tyro-dashboard::badge>
        </x-slot:actions>
        @if($demoMediaId)
            <div style="display:flex; gap:1rem; flex-wrap:wrap; align-items:flex-start;">
                <div style="text-align:center;">
                    <x-tyro-dashboard-media :media="$demoMedia" width="160" height="120" rounded="md" />
                    <div style="margin-top:0.5rem; font-size:0.8125rem; color:var(--muted-foreground);">webp &middot; md</div>
                </div>
                <div style="text-align:center;">
                    <x-tyro-dashboard-media :media="$demoMedia" variant="thumb" width="120" height="120" circle />
                    <div style="margin-top:0.5rem; font-size:0.8125rem; color:var(--muted-foreground);">thumb &middot; circle</div>
                </div>
                <div style="text-align:center;">
                    <x-tyro-dashboard-media :media="$demoMedia" variant="original" width="240" height="135" rounded="lg" alt="Hero banner" show-title />
                    <div style="margin-top:0.5rem; font-size:0.8125rem; color:var(--muted-foreground);">original &middot; lg</div>
                </div>
            </div>
        @else
            <p class="page-description" style="margin:0;">Upload an image in the Media library to see <code>&lt;x-tyro-dashboard-media&gt;</code> rendered here.</p>
        @endif
    </x-tyro-dashboard::card>

    {{-- Data Tables --}}
    @php
        $defaultTableData = collect([
            ['name' => 'Alice Johnson', 'email' => 'alice@example.com', 'role' => 'Admin', 'status' => '<span class="badge badge-success">Active</span>'],
            ['name' => 'Bob Smith', 'email' => 'bob@example.com', 'role' => 'Editor', 'status' => '<span class="badge badge-success">Active</span>'],
            ['name' => 'Carol White', 'email' => 'carol@example.com', 'role' => 'Viewer', 'status' => '<span class="badge badge-warning">Invited</span>'],
            ['name' => 'Dan Brown', 'email' => 'dan@example.com', 'role' => 'Editor', 'status' => '<span class="badge badge-danger">Suspended</span>'],
        ]);
    @endphp
    <div style="margin-top: 1.5rem;">
    <x-tyro-dashboard::data-table
        title="Data Table (default, hover)"
        description="Auto-generated from a collection with column definitions."
        :collection="$defaultTableData"
        :columns="[
            ['key' => 'name', 'label' => 'Name'],
            ['key' => 'email', 'label' => 'Email'],
            ['key' => 'role', 'label' => 'Role'],
            ['key' => 'status', 'label' => 'Status', 'align' => 'right'],
        ]"
    />

    </div>

    <div class="grid-2" style="margin-top: 1.5rem; margin-bottom: 1.5rem;">
        <x-tyro-dashboard::data-table
            title="Striped"
            striped
            :collection="collect([
                ['item' => 'Alpha', 'value' => '$1,200'],
                ['item' => 'Beta', 'value' => '$3,400'],
                ['item' => 'Gamma', 'value' => '$2,100'],
            ])"
            :columns="['item' => 'Item', 'value' => 'Value']"
        />

        <x-tyro-dashboard::data-table
            title="Bordered"
            variant="bordered"
            :collection="collect([
                ['key' => 'APP_NAME', 'val' => 'Tyro Dashboard'],
                ['key' => 'APP_ENV', 'val' => 'local'],
                ['key' => 'APP_DEBUG', 'val' => 'true'],
            ])"
            :columns="[
                ['key' => 'key', 'label' => 'Variable', 'class' => 'font-mono'],
                ['key' => 'val', 'label' => 'Value', 'class' => 'font-mono'],
            ]"
        />
    </div>

    <div class="grid-2" style="margin-bottom: 1.5rem;">
        <x-tyro-dashboard::data-table
            title="Compact"
            variant="compact"
            :collection="collect([
                ['name' => 'Page A', 'visits' => 1200],
                ['name' => 'Page B', 'visits' => 3400],
                ['name' => 'Page C', 'visits' => 2100],
            ])"
            :columns="[
                ['key' => 'name', 'label' => 'Page'],
                ['key' => 'visits', 'label' => 'Visits', 'align' => 'right'],
            ]"
        />

        <x-tyro-dashboard::data-table
            title="Minimal, No Hover"
            variant="minimal"
            :hover="false"
            :collection="collect([
                ['metric' => 'Uptime', 'value' => '99.97%'],
                ['metric' => 'Errors', 'value' => '0.02%'],
                ['metric' => 'Avg.', 'value' => '240ms'],
            ])"
            :columns="[
                ['key' => 'metric', 'label' => 'Metric'],
                ['key' => 'value', 'label' => 'Value', 'align' => 'right'],
            ]"
        />
    </div>

    <x-tyro-dashboard::data-table
        title="Custom Slot (full table markup)"
        description="Pass your own &lt;thead&gt; and &lt;tbody&gt; via the default slot."
        :striped="true"
    >
        <thead>
            <tr>
                <th>Widget</th>
                <th>Status</th>
                <th style="text-align:right;">Price</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><strong>Pro Plan</strong></td>
                <td><span class="badge badge-success">Active</span></td>
                <td style="text-align:right;">$29/mo</td>
            </tr>
            <tr>
                <td><strong>Enterprise</strong></td>
                <td><span class="badge badge-warning">Pending</span></td>
                <td style="text-align:right;">$99/mo</td>
            </tr>
        </tbody>
    </x-tyro-dashboard::data-table>
</x-tyro-dashboard::card>
