{{-- Reusable UI components showcase: <x-tyro-dashboard::*> and <x-tyro-dashboard-media> --}}
@php
    try {
        $demoMedia = \HasinHayder\TyroDashboard\Models\Media::whereNotNull('path')->orderBy('id')->first();
    } catch (\Throwable $e) {
        $demoMedia = null;
    }
    $demoMediaId = $demoMedia?->id;
@endphp

<x-tyro-dashboard::page-header title="Reusable Components" description="Drop-in Blade components. Each block below shows the tag you would use to assemble dashboard pages.">
    <x-slot:actions>
        <span class="badge badge-secondary">Additive</span>
        <span class="badge badge-success">No new CSS</span>
    </x-slot:actions>
</x-tyro-dashboard::page-header>

<div class="stats-grid">
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
                <x-tyro-dashboard-media :media="$demoMedia" variant="original" width="240" height="135" rounded="lg" alt="Hero banner" />
                <div style="margin-top:0.5rem; font-size:0.8125rem; color:var(--muted-foreground);">original &middot; lg</div>
            </div>
        </div>
    @else
        <p class="page-description" style="margin:0;">Upload an image in the Media library to see <code>&lt;x-tyro-dashboard-media&gt;</code> rendered here.</p>
    @endif
</x-tyro-dashboard::card>
