# Extensibility

## Core Principle

Extensibility determines whether Tyro Dashboard is a platform or a product. A platform grows an ecosystem. A product is used as-is or abandoned. Every extension point preserved is a reason someone builds on Tyro instead of replacing it.

## Extension Mechanisms

### View Publishing
- **Granularity is mandatory.** Ten publishable tags: `tyro-dashboard-config`, `tyro-dashboard-views`, `tyro-dashboard-views-admin`, `tyro-dashboard-views-user`, `tyro-dashboard-sidebar`, `tyro-dashboard-essentials`, `tyro-dashboard-styles`, `tyro-dashboard-scripts`, `tyro-dashboard-theme`, `tyro-dashboard`
- A consumer who only wants to change the admin sidebar must not be forced to publish all admin views
- Use `tyro-dashboard-sidebar` for only admin/user sidebar partials
- Use `tyro-dashboard-essentials` for dashboard shell partials plus the `dashboard` view directory
- Tags are registered in `TyroDashboardServiceProvider::registerPublishing()` via `$this->publishes()`
- Tag names are part of the public API — changing one breaks deployment scripts

### Config Publishing
- Config is published via `vendor:publish --tag=tyro-dashboard-config`
- Published config uses `mergeConfigFrom()` semantics — consumer overrides merge with framework defaults
- Never use `config_path()` or assume the config file exists at a specific path in the consumer app

### Menu Injection
- **The only way to add sidebar links.** Three injection points:
  - `config('menu.adminMenuItems')` — visible to admin users
  - `config('menu.commonMenuItems')` — visible to all authenticated users
  - `config('menu.userMenuItems')` — visible to non-admin users
- Menu item structure: `['label' => '...', 'route' => '...', 'icon' => '...', 'roles' => ['...']]`
- Adding optional keys (`badge`, `target`) is safe. Renaming or removing required keys is breaking.
- View composers inject menu items into sidebar views — they read config, never mutate it

### Custom Page Scaffolding
- `tyro-dashboard:create-admin-page {name}` — creates view + route + sidebar link
- `tyro-dashboard:create-user-page {name}` — same for user-facing pages
- `tyro-dashboard:create-common-page {name}` — same for common pages
- All three operations must happen: view, route, sidebar link. Missing one creates a broken page.
- Remove commands (`remove-admin-page`, `remove-user-page`, `remove-common-page`) must reverse all three operations
- Scaffolded views extend `tyro-dashboard::layouts.admin` or `tyro-dashboard::layouts.user`
- Scaffolded routes use the configured route prefix and name prefix

### Blade Components
- Class-based components are registered via `Blade::component()` in the service provider
- Anonymous components are registered via `Blade::anonymousComponentPath()` with namespace `tyro-dashboard`
- Component tag names are kebab-case: `<x-tyro-dashboard-media-picker>`
- Component props are the public API — adding optional props is safe; removing required props is breaking

### Event Listeners
- Framework-level listeners are registered in the service provider for `Login` and `Logout` events
- Consumer applications can register additional listeners for the same events — the framework must not prevent this
- Listener closures are feature-gated: `if (config('tyro-dashboard.features.audit_logs'))`
- Listeners must be lightweight — heavy processing belongs in queued jobs

### View Composers
- Composers share data with views: `view()->composer('*', fn($view) => $view->with('user', auth()->user()))`
- Composers never mutate application state — no DB writes, no session changes, no cache modifications
- Composer variable names are public API (`$user`, `$dashboardRoute`, `$allResources`)
- Adding new composer variables is safe. Removing or renaming is breaking.

### HasCrud Extension
- `HasCrud::getResourceConfig()` returns the full resource configuration — plugins can call this to inspect resources
- `HasCrud::getResourceKey()` returns the URL key — plugins use this to reference resources
- `$resourceFields` completely replaces auto-detected fields
- `$resourceFieldOverrides` tweaks specific auto-detected fields without replacing all of them
- Both override mechanisms must work independently

## Extension Point Stability Rules

1. **Add, don't remove.** New extension points are always welcome. Removing an existing extension point is a breaking change.
2. **Document the contract.** If a plugin developer can extend it, the expected behavior must be documented.
3. **Test extension points with a mock plugin.** Before release, verify that a plugin extending every point survives the upgrade.
4. **Deprecate before removal.** If a menu injection point must change, support both old and new keys for one major version.
5. **Never break view override precedence.** Laravel's standard view override (published > package) is sacred.

## Anti-Patterns

- **Telling consumers to modify vendor files.** Always provide a config key, publishing tag, or event listener hook.
- **Creating a second way to do something without deprecating the first.** The Law of One Pattern applies to extension points too.
- **Adding extension points without testing them.** An untested extension point is worse than none — it creates false confidence.
- **Making extension points dependent on unpublished implementation details.** An extension point that only works when the GD driver is active is broken.
