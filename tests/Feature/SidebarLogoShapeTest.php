<?php

use Illuminate\Support\Facades\View;

test('sidebar colors tab renders circle logo shape controls by default', function () {
    config()->set('tyro-dashboard.branding.sidebar_logo_shape', null);
    config()->set('tyro-dashboard.branding.sidebar_logo_border_radius', null);

    $html = View::make('tyro-dashboard::settings.partials._tab-sidebar-colors', [
        'settings' => ['TYRO_DASHBOARD_SIDEBAR_LOGO' => null],
    ])->render();

    expect($html)->toContain('name="TYRO_DASHBOARD_SIDEBAR_LOGO_SHAPE"')
        ->toContain('name="TYRO_DASHBOARD_SIDEBAR_LOGO_BORDER_RADIUS"')
        ->toContain('id="sb-logo-radius-group"')
        ->toContain('<option value="circle" selected>')
        ->toContain('border-radius: 50%; overflow: hidden;')
        ->toContain('display:none;');
});

test('sidebar colors tab renders rectangle logo shape controls from settings', function () {
    $html = View::make('tyro-dashboard::settings.partials._tab-sidebar-colors', [
        'settings' => [
            'TYRO_DASHBOARD_SIDEBAR_LOGO' => 'media/logo.png',
            'TYRO_DASHBOARD_SIDEBAR_LOGO_SHAPE' => 'rectangle',
            'TYRO_DASHBOARD_SIDEBAR_LOGO_BORDER_RADIUS' => '12px',
        ],
    ])->render();

    expect($html)->toContain('<option value="rectangle" selected>')
        ->toContain('sb-logo-rectangle')
        ->toContain('border-radius: 12px; overflow: hidden;')
        ->toContain('value="12px"')
        ->toContain('data-sb-preview-logo');
});

test('sidebar styles emit circle logo custom properties by default', function () {
    config()->set('tyro-dashboard.branding.sidebar_logo_shape', 'circle');

    $html = View::make('tyro-dashboard::partials.styles')->render();

    expect($html)->toContain('--sidebar-logo-radius: 50%;')
        ->toContain('--sidebar-logo-width: 36px;')
        ->toContain('--sidebar-logo-fit: cover;');
});

test('sidebar styles emit rectangle logo custom properties when configured', function () {
    config()->set('tyro-dashboard.branding.sidebar_logo_shape', 'rectangle');
    config()->set('tyro-dashboard.branding.sidebar_logo_border_radius', '12px');

    $html = View::make('tyro-dashboard::partials.styles')->render();

    expect($html)->toContain('--sidebar-logo-radius: 12px;')
        ->toContain('--sidebar-logo-width: auto;')
        ->toContain('--sidebar-logo-fit: contain;')
        ->toContain('flex-shrink: 1;');
});
