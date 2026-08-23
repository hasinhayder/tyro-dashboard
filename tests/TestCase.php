<?php

namespace HasinHayder\TyroDashboard\Tests;

use HasinHayder\TyroDashboard\Providers\TyroDashboardServiceProvider;
use Illuminate\Foundation\Auth\User;
use Orchestra\Testbench\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase {
    protected function getPackageProviders($app) {
        return [TyroDashboardServiceProvider::class];
    }

    protected function defineEnvironment($app) {
        $app['config']->set('app.key', 'base64:'.base64_encode(str_repeat('a', 32)));
        $app['config']->set('tyro-dashboard.user_model', User::class);
        $app['config']->set('cache.default', 'array');
        $app['config']->set('session.driver', 'array');
    }

    protected function defineRoutes($router) {
        $router->get('login', fn () => 'login')->name('login');
    }
}
