@extends('tyro-dashboard::layouts.admin')

@section('title', 'System Settings')

@section('breadcrumb')
<a href="{{ route($dashboardRoute::name('index')) }}">Dashboard</a>
<span class="breadcrumb-separator">/</span>
<span>System Settings</span>
@endsection

@push('styles')
    @include('settings.partials._styles')
@endpush

@section('content')
<div class="page-header">
    <div class="page-header-row">
        <div>
            <h1 class="page-title">System Settings</h1>
            <p class="page-description">Configure the dashboard, appearance, sign-in and security, and access control. Use search (⌘K) to jump to any setting.</p>
        </div>
        <div>
            <button type="submit" form="systemSettingsForm" class="btn btn-primary" id="systemSettingsSaveButton">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width:18px;height:18px;"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path><polyline points="17 21 17 13 7 13 7 21"></polyline><polyline points="7 3 7 8 15 8"></polyline></svg>
                Save Settings
            </button>
        </div>
    </div>
</div>

<form id="systemSettingsForm" method="POST" action="{{ route($dashboardRoute::name('settings.system.update')) }}">
    @csrf

    <div class="vtabs-layout">
        <nav class="vtabs-sidebar">
            @php
                $settingsNav = [
                    'General' => [
                        ['general', 'General', 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6'],
                        ['features', 'Features', 'M4 6h16M4 12h16M4 18h7'],
                    ],
                    'Appearance' => [
                        ['sidebar-colors', 'Sidebar', 'M3 5a2 2 0 012-2h14a2 2 0 012 2v14a2 2 0 01-2 2H5a2 2 0 01-2-2V5zm6-2v18'],
                        ['admin-bar-colors', 'Admin bar', 'M3 4h18a1 1 0 011 1v4a1 1 0 01-1 1H3a1 1 0 01-1-1V5a1 1 0 011-1zm0 10h18a1 1 0 011 1v4a1 1 0 01-1 1H3a1 1 0 01-1-1v-4a1 1 0 011-1z'],
                        ['dashboard-colors', 'Theme colors', 'M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485'],
                    ],
                    'Sign-in & security' => [
                        ['auth-signin', 'Login & registration', 'M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1'],
                        ['auth-verification', 'Verification & captcha', 'M12 2l7 4v6c0 5-3.5 9.5-7 10-3.5-.5-7-5-7-10V6l7-4zm-3 10l2 2 4-4'],
                        ['auth-social', 'Social login', 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z'],
                        ['passkeys', 'Passkeys', 'M21 2l-2 2m-7.61 7.61a5.5 5.5 0 11-7.778 7.778 5.5 5.5 0 017.777-7.777zm0 0L15.5 7.5m0 0l3 3L22 7l-3-3m-3.5 3.5L19 4'],
                        ['auth-lockout', 'Lockout & tokens', 'M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z'],
                        ['auth-pages', 'Auth pages', 'M4 5a1 1 0 011-1h14a1 1 0 011 1v14a1 1 0 01-1 1H5a1 1 0 01-1-1V5zm0 5h16'],
                        ['auth-emails', 'Auth emails', 'M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z'],
                    ],
                    'Access control' => [
                        ['access', 'Roles & API', 'M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z'],
                        ['access-caching', 'Caching & audit', 'M4 7v10c0 2 3.6 3 8 3s8-1 8-3V7M4 7c0 2 3.6 3 8 3s8-1 8-3M4 7c0-2 3.6-3 8-3s8 1 8 3m0 5c0 2-3.6 3-8 3s-8-1-8-3'],
                    ],
                    'Integrations' => [
                        ['media', 'Media', 'M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5z'],
                    ],
                ];
            @endphp
            @foreach($settingsNav as $groupLabel => $groupItems)
                <div class="vtabs-group" data-vtab-group>
                    <div class="vtabs-group-label">{{ $groupLabel }}</div>
                    @foreach($groupItems as [$tabId, $tabLabel, $tabIcon])
                        <button class="vtabs-item{{ $loop->parent->first && $loop->first ? ' active' : '' }}" data-vtab="{{ $tabId }}" type="button">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $tabIcon }}"/></svg>
                            {{ $tabLabel }}
                        </button>
                    @endforeach
                </div>
            @endforeach
            <div class="vtabs-save-bar">
                <button type="submit" form="systemSettingsForm" class="btn btn-primary btn-sm" style="width:100%;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width:16px;height:16px;"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path><polyline points="17 21 17 13 7 13 7 21"></polyline><polyline points="7 3 7 8 15 8"></polyline></svg>
                    Save Settings
                </button>
            </div>
        </nav>

        <div class="vtabs-content" id="settingsContent">
            <div class="settings-search-bar">
                <svg class="settings-search-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><path d="M21 21l-4.35-4.35"/></svg>
                <input type="text" id="settingsSearchInput" class="settings-search-input" placeholder="Search settings by name or key…" autocomplete="off" spellcheck="false" aria-label="Search settings">
                <span class="settings-search-shortcut" id="settingsSearchShortcut">⌘K</span>
                <button type="button" id="settingsSearchClear" class="settings-search-clear" aria-label="Clear search" tabindex="-1">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6L6 18"/><path d="M6 6l12 12"/></svg>
                </button>
            </div>
            @include('settings.partials._tab-general')
            @include('settings.partials._tab-features')
            @include('settings.partials._tab-sidebar-colors')
            @include('settings.partials._tab-admin-bar-colors')
            @include('settings.partials._tab-dashboard-colors')
            @include('settings.partials._tab-auth-signin')
            @include('settings.partials._tab-auth-verification')
            @include('settings.partials._tab-auth-social')
            @include('settings.partials._tab-passkeys')
            @include('settings.partials._tab-auth-lockout')
            @include('settings.partials._tab-auth-pages')
            @include('settings.partials._tab-auth-emails')
            @include('settings.partials._tab-access')
            @include('settings.partials._tab-access-caching')
            @include('settings.partials._tab-media')

            <div class="search-no-results" id="settingsNoResults">
                <div class="search-no-results-inner">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><path d="M21 21l-4.35-4.35"/><line x1="8" y1="8" x2="14" y2="14"/></svg>
                    <p class="search-no-results-title">No settings found</p>
                    <p class="search-no-results-text">No settings match "<span id="settingsNoResultsTerm"></span>". Try a different search term.</p>
                </div>
            </div>
        </div>
    </div>

</form>
@endsection

@push('scripts')
  @once
    @include('settings.partials._scripts')
  @endonce
@endpush
