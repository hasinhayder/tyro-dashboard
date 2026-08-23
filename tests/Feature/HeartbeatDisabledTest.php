<?php

namespace HasinHayder\TyroDashboard\Tests\Feature;

use HasinHayder\TyroDashboard\Support\DashboardRoute;
use HasinHayder\TyroDashboard\Tests\TestCase;

/**
 * Route gating is evaluated when the service provider boots, so the feature
 * must already be off in defineEnvironment (before boot) — an in-test
 * config() call runs too late to influence route registration.
 */
class HeartbeatDisabledTest extends TestCase {
    protected function defineEnvironment($app) {
        parent::defineEnvironment($app);

        $app['config']->set('tyro-dashboard.features.heartbeat', false);
    }

    public function test_heartbeat_route_is_not_registered_when_the_feature_is_disabled(): void {
        $this->post('/dashboard/heartbeat')->assertNotFound();

        $this->assertNull(app('router')->getRoutes()->getByName(DashboardRoute::name('heartbeat')));

        // Route gating must match the emitted UI — no heartbeat JS either
        $this->assertStringNotContainsString(
            'tyro-dashboard-heartbeat',
            view('tyro-dashboard::partials.scripts')->render()
        );
    }
}
