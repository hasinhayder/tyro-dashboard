# Service Provider

## Core Principle

The service provider is the single most important file in the framework. It is the integration seam between Tyro Dashboard and Laravel. A wrong boot sequence causes missing views, unregistered middleware, and broken publishing — bugs that are hard to diagnose.

## Boot Sequence

The boot sequence order is fixed. Changing it breaks the dependency chain.

```
register()              — mergeConfigFrom
registerPublishing()    — $this->publishes() for asset tags
loadMigrationsFrom()    — database migrations
registerRoutes()        — Route::group with prefix and middleware
registerViews()         — loadViewsFrom, Blade::component, Blade::anonymousComponentPath, View::addLocation
registerViewComposers() — view()->composer() for data sharing
registerMiddleware()    — alias, web group push
registerCommands()      — $this->commands([...])
registerEventListeners()— Event::listen() for Login/Logout
```

### Why This Order
- `registerPublishing()` and `loadMigrationsFrom()` run first — they are registration-only operations with no runtime dependencies
- `registerRoutes()` loads route definitions before view registration (routes reference views by namespace string, resolved lazily at request time)
- `registerViews()` registers the view namespace and Blade components needed for rendering
- `registerViewComposers()` runs after views are registered — composers attach to resolved view names
- `registerMiddleware()` registers aliases and web group pushes, needed before any incoming request
- `registerCommands()` runs late — commands are only needed in console mode
- `registerEventListeners()` runs last — listeners are event-driven, not boot-dependent

## View Registration

- `loadViewsFrom(__DIR__.'/../../resources/views', 'tyro-dashboard')` registers the view namespace
- `Blade::component(MediaPicker::class, 'tyro-dashboard-media-picker')` registers class-based components
- `Blade::component(MediaPicker::class, 'tyro-dashbaord-media-picker')` registers legacy misspelled alias
- `Blade::anonymousComponentPath(__DIR__.'/../../resources/views/components', 'tyro-dashboard')` registers anonymous components
- `Blade::anonymousComponentPath(__DIR__.'/../../resources/views/components', 'tyro-dashbaord')` registers legacy misspelled anonymous namespace
- `View::addLocation(__DIR__.'/../../resources/views')` adds the package views directory as a general view location so non-namespaced references (e.g. `vendor.pagination.tyro`) resolve within the package
- **Legacy misspellings** (`tyro-dashbaord-media-picker` and `tyro-dashbaord` anonymous-component namespace) are also registered for backward compatibility with consumers from before the spelling was corrected. Do not add more legacy aliases. The complete list of public-API legacy aliases lives in `rules/public-api-surface.md` under "Legacy Aliases".

## Middleware Registration

- `$router->aliasMiddleware('tyro-dashboard.admin', EnsureIsAdmin::class)` registers the admin middleware alias
- `$router->pushMiddlewareToGroup('web', HandleImpersonation::class)` pushes impersonation middleware to all web routes
- Core Tyro middleware aliases are registered by Tyro Core's service provider — do not re-register them here

## View Composers

- `view()->composer(['tyro-dashboard::*', 'dashboard.*'], ...)` shares global data: `$user` (auth user), `$dashboardRoute` (DashboardRoute class)
- `view()->composer(['tyro-dashboard::partials.admin-sidebar', 'tyro-dashboard::partials.user-sidebar'], ...)` shares resources: `$allResources` (filtered by user role)
- `view()->composer(['tyro-dashboard::partials.admin-sidebar', 'tyro-dashboard::partials.user-sidebar'], ...)` shares menu items: `$adminMenuItems`, `$commonMenuItems`, `$userMenuItems` (from config, only set if not already present in view data)
- Composers read data from config and the authenticated user — they never mutate state

## Event Listeners

- `Event::listen(Login::class, fn(Login $event) => ...)` audits `user.login` via `TyroAudit::log('user.login', $user, null, ['email' => $user->email])`
- `Event::listen(Logout::class, fn(Logout $event) => ...)` audits `user.logout` via `TyroAudit::log('user.logout', $user, null, ['email' => $user->email])`
- Listeners are feature-gated: wrapped in `if (config('tyro-dashboard.features.audit_logs'))` and `class_exists(TyroAudit::class)`
- Listeners must be lightweight — one `TyroAudit::log()` call only

## Publishing

- Each publishable group uses a specific tag string
- `$this->publishes([config => config_path], 'tyro-dashboard-config')` for config
- `$this->publishes([views => resource_path], 'tyro-dashboard-views')` for all views
- Granular tags split by audience: `tyro-dashboard-views-admin`, `tyro-dashboard-views-user`
- Focused view tags: `tyro-dashboard-sidebar` for sidebar partials and `tyro-dashboard-essentials` for dashboard shell partials plus dashboard views
- Asset tags split by type: `tyro-dashboard-styles`, `tyro-dashboard-scripts`, `tyro-dashboard-theme`
- Umbrella tag `tyro-dashboard` publishes everything

## Command Registration

- `$this->commands([...18 commands...])` registers all artisan commands
- Commands are only registered in console mode
- Command classes are in `HasinHayder\TyroDashboard\Console\Commands`

## Resource Scanning

The service provider scans for CRUD resources:
1. Config-based resources: `config('tyro-dashboard.resources')`
2. Trait-based resources: scans `app/Models/` for classes using `HasCrud` trait (reflection-based)
3. Resources are filtered by user role via `filterResourcesByUserRole()`
4. Filtered resources are shared with sidebar views via view composers

## Anti-Patterns

- **Registering routes before views.** Routes will fail with "View not found." (Note: current code registers routes before views, but this works because route definitions reference views by namespace string which is resolved lazily at request time.)
- **Registering middleware after routes.** Middleware alias won't be found.
- **Using view composers that depend on services not yet registered.** Composer failure is silent — the variable is simply null.
- **Duplicating registration from sibling packages.** Tyro Core middleware is registered by Tyro Core. Do not re-register.
- **Changing tag names without a deprecation cycle.** Deployment scripts break.

## Setup AI Skill Command

`tyro-dashboard:setup-ai-skill` copies the canonical skill directory (`skills/tyro-dashboard/` containing `SKILL.md` + `rules/`) from the package into `.agents/skills/tyro-dashboard`. Agent-specific discovery directories symlink there by default, or receive physical copies when `--copy` is passed. Existing targets are always refreshed to the latest package skill files. Full documentation is in `rules/artisan-commands.md`.

### Source Path
- Source is the package directory: `vendor/hasinhayder/tyro-dashboard/skills/tyro-dashboard/`
- Resolved via `__DIR__.'/../../../skills/tyro-dashboard'` — correct for both in-repo and published Composer layouts
- The universal install is a directory copy, not a single file copy — rule files are included

### Registration
- The command class is `HasinHayder\TyroDashboard\Console\Commands\SetupAiSkillCommand`
- Registered in `registerCommands()` alongside all other artisan commands
- Console-only (guarded by `$this->app->runningInConsole()`)

When modifying the agent list, target paths, or source path, update both this section and `rules/artisan-commands.md` in the same commit.
