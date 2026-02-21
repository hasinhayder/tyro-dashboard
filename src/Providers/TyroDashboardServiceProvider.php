<?php

namespace HasinHayder\TyroDashboard\Providers;

use HasinHayder\TyroDashboard\Console\Commands\CreateAdminPageCommand;
use HasinHayder\TyroDashboard\Console\Commands\CreateCommonPageCommand;
use HasinHayder\TyroDashboard\Console\Commands\CreateSuperUserCommand;
use HasinHayder\TyroDashboard\Console\Commands\CreateUserPageCommand;
use HasinHayder\TyroDashboard\Console\Commands\InstallCommand;
use HasinHayder\TyroDashboard\Console\Commands\RemoveAdminPageCommand;
use HasinHayder\TyroDashboard\Console\Commands\RemoveCommonPageCommand;
use HasinHayder\TyroDashboard\Console\Commands\RemoveUserPageCommand;
use HasinHayder\TyroDashboard\Console\Commands\MakeResourceCommand;
use HasinHayder\TyroDashboard\Console\Commands\PublishCommand;
use HasinHayder\TyroDashboard\Console\Commands\PublishStyleCommand;
use HasinHayder\TyroDashboard\Console\Commands\VersionCommand;
use HasinHayder\TyroDashboard\Console\Commands\ClearResourceCacheCommand;
use HasinHayder\TyroDashboard\Http\Middleware\EnsureIsAdmin;
use HasinHayder\TyroDashboard\Http\Middleware\HandleImpersonation;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class TyroDashboardServiceProvider extends ServiceProvider {
    public function register(): void {
        $this->mergeConfigFrom(__DIR__ . '/../../config/tyro-dashboard.php', 'tyro-dashboard');
    }

    public function boot(): void {
        $this->registerPublishing();
        $this->loadMigrationsFrom(__DIR__ . '/../../database/migrations');
        $this->registerRoutes();
        $this->registerViews();
        $this->registerViewComposers();
        $this->registerMiddleware();
        $this->registerCommands();
    }

    protected function registerRoutes(): void {
        Route::group([
            'prefix' => config('tyro-dashboard.routes.prefix', 'dashboard'),
            'middleware' => config('tyro-dashboard.routes.middleware', ['web', 'auth']),
            'as' => config('tyro-dashboard.routes.name_prefix', 'tyro-dashboard.'),
        ], function (): void {
            $this->loadRoutesFrom(__DIR__ . '/../../routes/web.php');
        });
    }

    protected function registerViews(): void {
        $this->loadViewsFrom(__DIR__ . '/../../resources/views', 'tyro-dashboard');
    }

    protected function registerViewComposers(): void {
        // Share authenticated user with all dashboard views
        View::composer(['tyro-dashboard::*', 'dashboard.*'], function ($view) {
            $view->with('user', auth()->user());
        });

        // Share filtered resources with sidebar views (based on user's role)
        View::composer(['tyro-dashboard::partials.admin-sidebar', 'tyro-dashboard::partials.user-sidebar'], function ($view) {
            $user = auth()->user();
            $resources = $this->getAllResources($user);
            $view->with('allResources', $resources);
        });

        View::composer(['tyro-dashboard::partials.admin-sidebar', 'tyro-dashboard::partials.user-sidebar'], function ($view) {
            $data = $view->getData();

            if (!isset($data['adminMenuItems'])) {
                $view->with('adminMenuItems', config('menu.adminMenuItems', []));
            }
            if (!isset($data['commonMenuItems'])) {
                $view->with('commonMenuItems', config('menu.commonMenuItems', []));
            }
            if (!isset($data['userMenuItems'])) {
                $view->with('userMenuItems', config('menu.userMenuItems', []));
            }
        });
    }

    protected function getAllResources($user = null): array {
        $resources = [];

        // Get config-based resources
        $configResources = config('tyro-dashboard.resources', []);
        foreach ($configResources as $key => $config) {
            $resources[$key] = $config;
        }

        // Get trait-based resources
        $traitResources = $this->getTraitBasedResources();
        foreach ($traitResources as $key => $config) {
            // Don't override config-based resources
            if (!isset($resources[$key])) {
                $resources[$key] = $config;
            }
        }

        // Filter resources based on user's role
        if ($user) {
            $resources = $this->filterResourcesByUserRole($resources, $user);
        }

        return $resources;
    }

    protected function filterResourcesByUserRole(array $resources, $user): array {
        // Check if user is admin
        $isAdmin = false;
        if (method_exists($user, 'tyroRoleSlugs')) {
            $adminRoles = config('tyro-dashboard.admin_roles', ['admin', 'super-admin']);
            $userRoles = $user->tyroRoleSlugs();
            foreach ($adminRoles as $role) {
                if (in_array($role, $userRoles)) {
                    $isAdmin = true;
                    break;
                }
            }
        }

        // If admin, return all resources
        if ($isAdmin) {
            return $resources;
        }

        // If not admin, filter resources based on user's roles
        $filteredResources = [];

        if (!method_exists($user, 'tyroRoleSlugs')) {
            return $filteredResources; // No roles method, no access
        }

        $userRoles = $user->tyroRoleSlugs();

        foreach ($resources as $key => $config) {
            $accessRoles = $config['roles'] ?? [];
            $readonlyRoles = $config['readonly'] ?? [];

            // If no roles defined, it's admin-only (skip for non-admin users)
            if (empty($accessRoles) && empty($readonlyRoles)) {
                continue;
            }

            // Check if user has access role
            $hasAccess = false;
            foreach ($accessRoles as $role) {
                if (in_array($role, $userRoles)) {
                    $hasAccess = true;
                    break;
                }
            }

            // Check if user has readonly role
            if (!$hasAccess) {
                foreach ($readonlyRoles as $role) {
                    if (in_array($role, $userRoles)) {
                        $hasAccess = true;
                        break;
                    }
                }
            }

            if ($hasAccess) {
                $filteredResources[$key] = $config;
            }
        }

        return $filteredResources;
    }

    protected function getTraitBasedResources(): array {
        $resources = [];
        $modelPath = app_path('Models');

        if (!is_dir($modelPath)) {
            return $resources;
        }

        $files = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($modelPath, \RecursiveDirectoryIterator::SKIP_DOTS)
        );

        foreach ($files as $file) {
            if ($file->isDir() || $file->getExtension() !== 'php') {
                continue;
            }

            $relativePath = str_replace($modelPath . DIRECTORY_SEPARATOR, '', $file->getPathname());
            $className = 'App\\Models\\' . str_replace(['/', '.php'], ['\\', ''], $relativePath);

            if (class_exists($className)) {
                try {
                    $reflection = new \ReflectionClass($className);
                    if ($reflection->hasMethod('getResourceConfig') && $reflection->hasMethod('getResourceKey')) {
                        $key = $className::getResourceKey();
                        $resources[$key] = $className::getResourceConfig();
                    }
                } catch (\Exception $e) {
                    // Skip models that can't be reflected
                    continue;
                }
            }
        }

        return $resources;
    }

    protected function registerMiddleware(): void {
        /** @var Router $router */
        $router = $this->app['router'];
        $router->aliasMiddleware('tyro-dashboard.admin', EnsureIsAdmin::class);
        
        // Add impersonation handler to web middleware group
        $router->pushMiddlewareToGroup('web', HandleImpersonation::class);
    }

    protected function registerCommands(): void {
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
            RemoveAdminPageCommand::class,
            RemoveCommonPageCommand::class,
            RemoveUserPageCommand::class,
            VersionCommand::class,
            ClearResourceCacheCommand::class,
        ]);
    }

    protected function registerPublishing(): void {
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
            $viewsPath . '/partials/admin-bar.blade.php' => resource_path('views/vendor/tyro-dashboard/partials/admin-bar.blade.php'),
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
            $viewsPath . '/partials/admin-bar.blade.php' => resource_path('views/vendor/tyro-dashboard/partials/admin-bar.blade.php'),
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
