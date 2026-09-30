<?php

namespace HasinHayder\TyroDashboard\Tests\Feature;

use HasinHayder\TyroDashboard\Support\DashboardRoute;
use HasinHayder\TyroDashboard\Tests\TestCase;

class EmailerDisabledTest extends TestCase {
    protected function defineEnvironment($app) {
        parent::defineEnvironment($app);

        $app['config']->set('tyro-dashboard.features.emailer', false);
    }

    public function test_emailer_routes_are_not_registered_when_feature_is_disabled(): void {
        $this->get('/dashboard/emailer')->assertNotFound();

        foreach ([
            'emailer.index',
            'emailer.send',
            'emailer.preview',
        ] as $name) {
            $this->assertNull(
                app('router')->getRoutes()->getByName(DashboardRoute::name($name)),
                "Route {$name} should not be registered when emailer is disabled."
            );
        }
    }

    public function test_sidebar_link_is_hidden_when_emailer_is_disabled(): void {
        $html = view('tyro-dashboard::partials.admin-sidebar')->render();

        $this->assertStringNotContainsString('Emailer', $html);
    }
}
