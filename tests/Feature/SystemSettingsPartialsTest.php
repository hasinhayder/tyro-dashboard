<?php

use HasinHayder\TyroDashboard\Http\Controllers\SystemSettingsController;
use Illuminate\Support\Facades\View;

function systemSettingsDefaults(): array {
    $controller = app(SystemSettingsController::class);
    $method = new ReflectionMethod($controller, 'defaultValues');
    $method->setAccessible(true);

    return $method->invoke($controller);
}

it('renders every grouped settings panel with its tab id', function (string $panel) {
    $html = View::make("tyro-dashboard::settings.partials._tab-{$panel}", [
        'settings' => systemSettingsDefaults(),
        'roleOptions' => ['user' => 'User'],
    ])->render();

    expect($html)->toContain('id="vtab-'.$panel.'"');
})->with([
    'general', 'features', 'auth-signin', 'auth-verification', 'auth-social',
    'auth-lockout', 'auth-pages', 'auth-emails', 'access', 'access-caching',
]);

it('does not render a settings field in more than one panel', function () {
    $panels = [
        'general', 'features', 'auth-signin', 'auth-verification', 'auth-social',
        'auth-lockout', 'auth-pages', 'auth-emails', 'access', 'access-caching',
    ];

    $seen = [];
    foreach ($panels as $panel) {
        $html = View::make("tyro-dashboard::settings.partials._tab-{$panel}", [
            'settings' => systemSettingsDefaults(),
            'roleOptions' => ['user' => 'User'],
        ])->render();

        preg_match_all('/<input type="hidden" name="([A-Z0-9_]+)"|<(?:input|select|textarea)[^>]*\bname="([A-Z0-9_]+)(?:\[\])?"/', $html, $m);
        $names = array_unique(array_filter(array_merge($m[1], $m[2])));

        foreach ($names as $name) {
            expect($seen)->not->toHaveKey($name);
            $seen[$name] = $panel;
        }
    }
});

it('compiles the grouped system settings page to valid php', function () {
    $source = file_get_contents(__DIR__.'/../../resources/views/settings/system.blade.php');
    $compiled = app('blade.compiler')->compileString($source);

    $file = tempnam(sys_get_temp_dir(), 'blade');
    file_put_contents($file, $compiled);
    exec('php -l '.escapeshellarg($file).' 2>&1', $out, $code);
    unlink($file);

    expect($code)->toBe(0, implode("\n", $out));
    expect($source)->toContain("settings.partials._tab-access-caching")
        ->not->toContain('_tab-login-auth-advanced');
});
