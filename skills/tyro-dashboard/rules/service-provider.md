# Service Provider

## Core Principle

The service provider is the single most important file in the framework. It is the integration seam between Tyro Dashboard and Laravel. A wrong boot sequence causes missing views, unregistered middleware, and broken publishing — bugs that are hard to diagnose.

## Boot Sequence

The boot sequence order is fixed. Changing it breaks the dependency chain.

```
register()            — mergeConfigFrom
registerViews()       — loadViewsFrom, Blade::component, Blade::anonymousComponentPath
registerRoutes()      — Route::group with prefix and middleware
registerViewComposers() — view()->composer() for data sharing
registerMiddleware()  — alias, web group push
registerCommands()    — $this->commands([...])
registerEventListeners() — Event::listen() for Login/Logout
registerPublishing()  — $this->publishes() for asset tags
loadMigrationsFrom()  — database migrations
```

### Why This Order
- `registerViews()` must run before `registerRoutes()` — routes reference views by namespace
- `registerRoutes()` must run before `registerMiddleware()` — routes reference middleware aliases
- `registerViewComposers()` must not depend on services registered later
- `registerMiddleware()` must run before any request that uses the middleware
- `registerPublishing()` is last because it depends on views and config being registered

## View Registration

- `loadViewsFrom(__DIR__.'/../../resources/views', 'tyro-dashboard')` registers the view namespace
- `Blade::component('tyro-dashboard-media-picker', MediaPicker::class)` registers class-based components
- `Blade::anonymousComponentPath(__DIR__.'/../../resources/views/components', 'tyro-dashboard')` registers anonymous components
- **Legacy misspellings** (`tyro-dashbaord-media-picker` and `tyro-dashbaord` anonymous-component namespace) are also registered for backward compatibility with consumers from before the spelling was corrected. Do not add more legacy aliases. The complete list of public-API legacy aliases lives in `rules/public-api-surface.md` under "Legacy Aliases".

## Middleware Registration

- `$router->aliasMiddleware('tyro-dashboard.admin', EnsureIsAdmin::class)` registers the admin middleware alias
- `$router->pushMiddlewareToGroup('web', HandleImpersonation::class)` pushes impersonation middleware to all web routes
- Core Tyro middleware aliases are registered by Tyro Core's service provider — do not re-register them here

## View Composers

- `view()->composer('*', ...)` shares global data: `$user` (auth user), `$dashboardRoute` (DashboardRoute instance)
- `view()->composer('tyro-dashboard::partials.admin-sidebar', ...)` shares sidebar-specific data: `$allResources`, `$adminMenuItems`, `$commonMenuItems`
- `view()->composer('tyro-dashboard::partials.user-sidebar', ...)` shares user sidebar data: `$allResources`, `$userMenuItems`, `$commonMenuItems`
- Composers read data from config and the authenticated user — they never mutate state

## Event Listeners

- `Event::listen(Login::class, fn(Login $event) => ...)` audits `user.login`
- `Event::listen(Logout::class, fn(Logout $event) => ...)` audits `user.logout`
- Listeners are feature-gated: wrapped in `if (config('tyro-dashboard.features.audit_logs'))`
- Listeners must be lightweight — one `TyroAudit::log()` call only

## Publishing

- Each publishable group uses a specific tag string
- `$this->publishes([config => config_path], 'tyro-dashboard-config')` for config
- `$this->publishes([views => resource_path], 'tyro-dashboard-views')` for all views
- Granular tags split by audience: `tyro-dashboard-views-admin`, `tyro-dashboard-views-user`
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

- **Registering routes before views.** Routes will fail with "View not found."
- **Registering middleware after routes.** Middleware alias won't be found.
- **Using view composers that depend on services not yet registered.** Composer failure is silent — the variable is simply null.
- **Duplicating registration from sibling packages.** Tyro Core middleware is registered by Tyro Core. Do not re-register.
- **Changing tag names without a deprecation cycle.** Deployment scripts break.

## Setup AI Skill Command

`tyro-dashboard:setup-ai-skill` copies the canonical skill directory (`skills/tyro-dashboard/` containing `SKILL.md` + `rules/`) from the package into the consumer app's base path under agent-specific discovery directories plus a universal location.

### Source Path
- Source is the package directory: `vendor/hasinhayder/tyro-dashboard/skills/tyro-dashboard/`
- Resolved via `__DIR__.'/../../../skills/tyro-dashboard'` — this is correct for both the in-repo package and the published `vendor/hasinhayder/tyro-dashboard/` layout because Composer preserves the relative directory structure
- It is a directory copy, not a single file copy — rule files are included

### Target Directories
Each agent installs into its own discovery directory. Additionally, the universal `UNIVERSAL_SKILL_DIR` constant (`.agents/skills/tyro-dashboard/`) is **always** installed exactly once, regardless of whether the user picks a single agent or `all`. This avoids redundant rewrites and keeps the universal location authoritative:
- `.kilo/skills/tyro-dashboard/` — Kilo
- `.claude/skills/tyro-dashboard/` — Claude
- `.github/skills/tyro-dashboard/` — GitHub Copilot
- `.codex/skills/tyro-dashboard/` — Codex
- `.gemini/skills/tyro-dashboard/` — Gemini
- `.ai/skills/tyro-dashboard/` — Laravel Boost
- `.agents/skills/tyro-dashboard/` — universal agents.md discovery (always)

### Install Strategy
- The new skill contents are staged in a sibling `.__installing__` directory
- The existing target directory is renamed to `.__backup__` (atomic on the same filesystem; falls back to copy+delete cross-device)
- The staged directory is renamed into the target's place
- On any failure, the backup is restored and the staging dir is cleaned
- On success, the backup is discarded

This guarantees the target directory is never left in a partially-wiped state, and a previous install can be recovered if the new copy fails.

### Trade-offs and Consumer Guidance
- The install wipes the entire target directory before recopying. Any file the consumer placed inside `.kilo/skills/tyro-dashboard/`, `.claude/skills/tyro-dashboard/`, etc. will be removed on the next run.
- This is intentional: it prevents stale rule files from previous framework versions from lingering and conflicting with the new version.
- Consumers who need to keep custom files should place them in a **sibling** directory (e.g., `.kilo/skills/tyro-dashboard-notes/`) rather than inside the target directory itself.
- The `tyro-dashboard:update` command follows the same update-safety principle for published views (never overwrites consumer overrides) — but for the AI skill files, where the framework is the authoritative owner, a clean replace is the only safe option.

When modifying the agent list, target paths, or source path, update this section in the same commit.
