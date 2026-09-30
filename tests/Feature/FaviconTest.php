<?php

use Illuminate\Support\Facades\View;

test('admin layout renders favicon when configured', function () {
    config()->set('tyro-dashboard.branding.favicon', 'media/favicon.ico');

    $user = new \Illuminate\Foundation\Auth\User;
    $user->forceFill(['id' => 1, 'name' => 'Admin User', 'email' => 'admin@example.com']);
    $this->actingAs($user);

    $html = View::make('tyro-dashboard::layouts.admin', [
        'errors' => new \Illuminate\Support\ViewErrorBag,
    ])->render();

    expect($html)->toContain('<link rel="icon" href="')
        ->toContain('media/favicon.ico');
});

test('admin layout does not render favicon link when not configured', function () {
    config()->set('tyro-dashboard.branding.favicon', null);

    $user = new \Illuminate\Foundation\Auth\User;
    $user->forceFill(['id' => 1, 'name' => 'Admin User', 'email' => 'admin@example.com']);
    $this->actingAs($user);

    $html = View::make('tyro-dashboard::layouts.admin', [
        'errors' => new \Illuminate\Support\ViewErrorBag,
    ])->render();

    expect($html)->not->toContain('<link rel="icon"');
});

test('system settings dashboard tab renders favicon media picker', function () {
    $settings = [
        'TYRO_DASHBOARD_APP_NAME' => 'Tyro App',
        'TYRO_DASHBOARD_LOGO_HEIGHT' => '32px',
        'TYRO_DASHBOARD_FAVICON' => 'media/favicon.png',
        'TYRO_DASHBOARD_COLLAPSIBLE_SIDEBAR' => true,
        'TYRO_DASHBOARD_DISABLE_EXAMPLES' => false,
        'TYRO_DASHBOARD_SHOW_ROLES_MENU' => true,
        'TYRO_DASHBOARD_SHOW_PRIVILEGES_MENU' => true,
        'TYRO_DASHBOARD_SHOW_RESOURCES_MENU' => true,
        'TYRO_DASHBOARD_ENABLE_INVITATION' => true,
        'TYRO_DASHBOARD_ENABLE_EMAILER' => true,
        'TYRO_DASHBOARD_ENABLE_AUDIT_LOGS' => true,
        'TYRO_DASHBOARD_ENABLE_PROFILE_PHOTO' => true,
        'TYRO_DASHBOARD_ENABLE_GRAVATAR' => false,
        'TYRO_DASHBOARD_NOTIFICATION_STYLE' => 'legacy',
        'TYRO_DASHBOARD_TOAST_POSITION' => 'bottom-right',
    ];

    $html = View::make('tyro-dashboard::settings.partials._tab-dashboard', [
        'settings' => $settings,
    ])->render();

    expect($html)->toContain('name="TYRO_DASHBOARD_FAVICON"')
        ->toContain('data-tyro-media-output="thumb"')
        ->toContain('media/favicon.png')
        ->toContain('Select Favicon');
});
