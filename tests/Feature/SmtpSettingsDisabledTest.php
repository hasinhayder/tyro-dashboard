<?php

namespace HasinHayder\TyroDashboard\Tests\Feature;

use HasinHayder\TyroDashboard\Support\DashboardRoute;
use HasinHayder\TyroDashboard\Tests\TestCase;

/**
 * Route gating is evaluated when the service provider boots, so the feature
 * must already be off in defineEnvironment (before boot) — an in-test
 * config() call runs too late to influence route registration.
 */
class SmtpSettingsDisabledTest extends TestCase {
    protected function defineEnvironment($app) {
        parent::defineEnvironment($app);

        $app['config']->set('tyro-dashboard.features.smtp_settings', false);
    }

    public function test_smtp_routes_are_not_registered_when_the_feature_is_disabled(): void {
        $this->get('/dashboard/settings/smtp')->assertNotFound();

        foreach ([
            'settings.smtp.index',
            'settings.smtp.update',
            'settings.smtp.clear-config-cache',
            'settings.smtp.test',
            'settings.smtp.presets.store',
            'settings.smtp.presets.update',
            'settings.smtp.presets.destroy',
            'settings.smtp.presets.apply',
        ] as $name) {
            $this->assertNull(
                app('router')->getRoutes()->getByName(DashboardRoute::name($name)),
                "Route {$name} should not be registered when smtp_settings is disabled."
            );
        }
    }

    public function test_sidebar_link_is_hidden_when_the_feature_is_disabled(): void {
        // Route gating must match the emitted UI — no sidebar link either.
        $html = view('tyro-dashboard::partials.admin-sidebar')->render();

        $this->assertStringNotContainsString('SMTP Settings', $html);
    }
}
