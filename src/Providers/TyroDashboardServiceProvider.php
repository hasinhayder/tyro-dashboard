<?php

namespace HasinHayder\TyroDashboard\Providers;

use HasinHayder\TyroDashboard\Console\Commands\InstallCommand;
use HasinHayder\TyroDashboard\Console\Commands\VersionCommand;
use HasinHayder\TyroDashboard\Http\Middleware\EnsureIsAdmin;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class TyroDashboardServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../../config/tyro-dashboard.php', 'tyro-dashboard');
    }

    public function boot(): void
    {
        $this->registerPublishing();
        $this->registerRoutes();
        $this->registerViews();
        $this->registerMiddleware();
        $this->registerCommands();
    }

    protected function registerRoutes(): void
    {
        Route::group([
            'prefix' => config('tyro-dashboard.routes.prefix', 'dashboard'),
            'middleware' => config('tyro-dashboard.routes.middleware', ['web', 'auth']),
            'as' => config('tyro-dashboard.routes.name_prefix', 'tyro-dashboard.'),
        ], function (): void {
            $this->loadRoutesFrom(__DIR__ . '/../../routes/web.php');
        });
    }

    protected function registerViews(): void
    {
        $this->loadViewsFrom(__DIR__ . '/../../resources/views', 'tyro-dashboard');
    }

    protected function registerMiddleware(): void
    {
        /** @var Router $router */
        $router = $this->app['router'];
        $router->aliasMiddleware('tyro-dashboard.admin', EnsureIsAdmin::class);
    }

    protected function registerCommands(): void
    {
        if (!$this->app->runningInConsole()) {
            return;
        }

        $this->commands([
            InstallCommand::class,
            VersionCommand::class,
        ]);
    }

    protected function registerPublishing(): void
    {
        if (!$this->app->runningInConsole()) {
            return;
        }

        // Publish config
        $this->publishes([
            __DIR__ . '/../../config/tyro-dashboard.php' => config_path('tyro-dashboard.php'),
        ], 'tyro-dashboard-config');

        // Publish views
        $this->publishes([
            __DIR__ . '/../../resources/views' => resource_path('views/vendor/tyro-dashboard'),
        ], 'tyro-dashboard-views');

        // Publish all
        $this->publishes([
            __DIR__ . '/../../config/tyro-dashboard.php' => config_path('tyro-dashboard.php'),
            __DIR__ . '/../../resources/views' => resource_path('views/vendor/tyro-dashboard'),
        ], 'tyro-dashboard');
    }
}
