<?php

namespace HasinHayder\TyroDashboard\Providers;

use HasinHayder\TyroDashboard\Console\Commands\CreateAdminPageCommand;
use HasinHayder\TyroDashboard\Console\Commands\CreateCommonPageCommand;
use HasinHayder\TyroDashboard\Console\Commands\CreateSuperUserCommand;
use HasinHayder\TyroDashboard\Console\Commands\CreateUserPageCommand;
use HasinHayder\TyroDashboard\Console\Commands\InstallCommand;
use HasinHayder\TyroDashboard\Console\Commands\MakeResourceCommand;
use HasinHayder\TyroDashboard\Console\Commands\PublishCommand;
use HasinHayder\TyroDashboard\Console\Commands\PublishStyleCommand;
use HasinHayder\TyroDashboard\Console\Commands\VersionCommand;
use HasinHayder\TyroDashboard\Http\Middleware\EnsureIsAdmin;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;
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
        $this->registerViewComposers();
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

    protected function registerViewComposers(): void
    {
        // Share authenticated user with all dashboard views
        View::composer(['tyro-dashboard::*', 'dashboard.*'], function ($view) {
            $view->with('user', auth()->user());
        });
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
            CreateAdminPageCommand::class,
            CreateCommonPageCommand::class,
            CreateSuperUserCommand::class,
            CreateUserPageCommand::class,
            InstallCommand::class,
            MakeResourceCommand::class,
            PublishCommand::class,
            PublishStyleCommand::class,
            VersionCommand::class,
        ]);
    }

    protected function registerPublishing(): void
    {
        if (!$this->app->runningInConsole()) {
            return;
        }

        $viewsPath = __DIR__ . '/../../resources/views';

        // Publish config
        $this->publishes([
            __DIR__ . '/../../config/tyro-dashboard.php' => config_path('tyro-dashboard.php'),
        ], 'tyro-dashboard-config');

        // Publish all views
        $this->publishes([
            $viewsPath => resource_path('views/vendor/tyro-dashboard'),
        ], 'tyro-dashboard-views');

        // Publish admin views only (layouts, partials, dashboard, users, roles, privileges)
        $this->publishes([
            $viewsPath . '/layouts/admin.blade.php' => resource_path('views/vendor/tyro-dashboard/layouts/admin.blade.php'),
            $viewsPath . '/layouts/app.blade.php' => resource_path('views/vendor/tyro-dashboard/layouts/app.blade.php'),
            $viewsPath . '/partials/admin-sidebar.blade.php' => resource_path('views/vendor/tyro-dashboard/partials/admin-sidebar.blade.php'),
            $viewsPath . '/partials/sidebar.blade.php' => resource_path('views/vendor/tyro-dashboard/partials/sidebar.blade.php'),
            $viewsPath . '/partials/topbar.blade.php' => resource_path('views/vendor/tyro-dashboard/partials/topbar.blade.php'),
            $viewsPath . '/partials/flash-messages.blade.php' => resource_path('views/vendor/tyro-dashboard/partials/flash-messages.blade.php'),
            $viewsPath . '/partials/shadcn-theme.blade.php' => resource_path('views/vendor/tyro-dashboard/partials/shadcn-theme.blade.php'),
            $viewsPath . '/partials/styles.blade.php' => resource_path('views/vendor/tyro-dashboard/partials/styles.blade.php'),
            $viewsPath . '/partials/scripts.blade.php' => resource_path('views/vendor/tyro-dashboard/partials/scripts.blade.php'),
            $viewsPath . '/dashboard/admin.blade.php' => resource_path('views/vendor/tyro-dashboard/dashboard/admin.blade.php'),
            $viewsPath . '/dashboard/index.blade.php' => resource_path('views/vendor/tyro-dashboard/dashboard/index.blade.php'),
            $viewsPath . '/users' => resource_path('views/vendor/tyro-dashboard/users'),
            $viewsPath . '/roles' => resource_path('views/vendor/tyro-dashboard/roles'),
            $viewsPath . '/privileges' => resource_path('views/vendor/tyro-dashboard/privileges'),
        ], 'tyro-dashboard-views-admin');

        // Publish user views only (user layout, user sidebar, user dashboard, profile)
        $this->publishes([
            $viewsPath . '/layouts/user.blade.php' => resource_path('views/vendor/tyro-dashboard/layouts/user.blade.php'),
            $viewsPath . '/layouts/app.blade.php' => resource_path('views/vendor/tyro-dashboard/layouts/app.blade.php'),
            $viewsPath . '/partials/user-sidebar.blade.php' => resource_path('views/vendor/tyro-dashboard/partials/user-sidebar.blade.php'),
            $viewsPath . '/partials/topbar.blade.php' => resource_path('views/vendor/tyro-dashboard/partials/topbar.blade.php'),
            $viewsPath . '/partials/flash-messages.blade.php' => resource_path('views/vendor/tyro-dashboard/partials/flash-messages.blade.php'),
            $viewsPath . '/partials/shadcn-theme.blade.php' => resource_path('views/vendor/tyro-dashboard/partials/shadcn-theme.blade.php'),
            $viewsPath . '/partials/styles.blade.php' => resource_path('views/vendor/tyro-dashboard/partials/styles.blade.php'),
            $viewsPath . '/partials/scripts.blade.php' => resource_path('views/vendor/tyro-dashboard/partials/scripts.blade.php'),
            $viewsPath . '/dashboard/user.blade.php' => resource_path('views/vendor/tyro-dashboard/dashboard/user.blade.php'),
            $viewsPath . '/profile' => resource_path('views/vendor/tyro-dashboard/profile'),
        ], 'tyro-dashboard-views-user');

        // Publish styles
        $this->publishes([
            $viewsPath . '/partials/shadcn-theme.blade.php' => resource_path('views/vendor/tyro-dashboard/partials/shadcn-theme.blade.php'),
            $viewsPath . '/partials/styles.blade.php' => resource_path('views/vendor/tyro-dashboard/partials/styles.blade.php'),
        ], 'tyro-dashboard-styles');

        // Publish theme only (for quick theme customization)
        $this->publishes([
            $viewsPath . '/partials/shadcn-theme.blade.php' => resource_path('views/vendor/tyro-dashboard/partials/shadcn-theme.blade.php'),
        ], 'tyro-dashboard-theme');

        // Publish all
        $this->publishes([
            __DIR__ . '/../../config/tyro-dashboard.php' => config_path('tyro-dashboard.php'),
            $viewsPath => resource_path('views/vendor/tyro-dashboard'),
        ], 'tyro-dashboard');
    }
}
