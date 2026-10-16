<?php

use Illuminate\Support\Facades\View;

test('sidebar renders the default color palette when not customized', function () {
    $html = View::make('tyro-dashboard::partials.styles')->render();

    expect($html)->toContain('--sidebar: #0e0e0e !important;')
        ->toContain('--sidebar-foreground: #f8fafc !important;')
        ->toContain('--sidebar-primary: #333333 !important;')
        ->toContain('--sidebar-accent: #f5f5f5 !important;')
        ->toContain('--sidebar-accent-foreground: #171717 !important;')
        ->toContain('--sidebar-header-border: #333c56 !important;');
});

test('sidebar colors can still be overridden via config', function () {
    config()->set('tyro-dashboard.branding.sidebar_bg', '#123456');

    $html = View::make('tyro-dashboard::partials.styles')->render();

    expect($html)->toContain('--sidebar: #123456 !important;');
});

test('sidebar falls back to the default palette when config values are null', function () {
    config()->set('tyro-dashboard.branding.sidebar_bg', null);
    config()->set('tyro-dashboard.branding.sidebar_text', null);

    $html = View::make('tyro-dashboard::partials.styles')->render();

    expect($html)->toContain('--sidebar: #0e0e0e !important;')
        ->toContain('--sidebar-foreground: #f8fafc !important;');
});
