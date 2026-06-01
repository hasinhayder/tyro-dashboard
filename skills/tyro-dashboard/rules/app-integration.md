# App Integration

## Core Principle

Consumer applications should extend Tyro Dashboard without becoming forks of Tyro Dashboard. In an app, prefer local routes, controllers, views, config overrides, published Blade files, middleware, events, and menu configuration. Package internals should remain untouched unless the requested change is truly a framework change.

## Decision Rule

Ask this before editing:

- **Is this feature only for this application?** Put it in the app.
- **Would every Tyro installation benefit from this behavior?** Consider package code.
- **Does this expose or alter a public contract?** Treat it as framework work and read the matching rule files.
- **Can a published view/config/menu hook solve it?** Use that instead of changing package internals.

## App-Level Admin Pages

Use this pattern for project-specific admin pages:

1. Inspect nearby project routes and controllers first.
2. Add an app controller under `app/Http/Controllers` for non-trivial pages.
3. Use `['auth', 'tyro-dashboard.admin']` unless the project has an established admin route group.
4. Use app route names such as `dashboard.system.environment` rather than package route names.
5. Place views under `resources/views/dashboard/...`.
6. Extend `tyro-dashboard::layouts.admin`.
7. Add links through published sidebar overrides or menu injection.

Example:

```php
Route::get('/dashboard/system/environment', [SystemEnvironmentController::class, 'index'])
    ->middleware(['auth', 'tyro-dashboard.admin'])
    ->name('dashboard.system.environment');
```

## Published View Overrides

Published views live at `resources/views/vendor/tyro-dashboard/`. They are app code, but they shadow framework views, so edit them carefully:

- Preserve expected variables such as `$dashboardRoute`, `$branding`, `$adminMenuItems`, `$userMenuItems`, `$commonMenuItems`, and `$allResources`.
- Keep section and stack names compatible with the package layout.
- Keep route helpers consistent with the surrounding file.
- Avoid heavy database, schema, HTTP, filesystem, or process checks inside Blade.
- If a published view has drifted far from the package view, make the smallest compatible change.

## Sidebar and Menus

Prefer the least invasive menu mechanism:

1. Existing app menu config/injection, if present.
2. Published sidebar override, if the app already owns one.
3. Package sidebar changes only for reusable framework features.

Sidebar links should:

- Use short labels.
- Use local route names for app pages.
- Use `request()->routeIs(...)` or the local `$dashboardRoute::pattern(...)` pattern consistently.
- Avoid expensive runtime checks.
- Hide unavailable framework features with the same condition that gates their routes.

## Diagnostics and Environment Pages

Admin diagnostics are useful but easy to overexpose.

Allowed:

- PHP/Laravel versions
- Hostname, OS, CPU, RAM, disk, paths
- Queue/cache/session/database driver names
- Service availability checks
- Extension/binary availability such as Redis, GD, Imagick, FFmpeg, FFprobe, Horizon

Avoid:

- Raw `.env` contents
- Secret values, API keys, tokens, private keys, signed URLs
- Full process lists or command output that may include credentials
- Slow checks on every request
- Shelling out when a safe filesystem/PHP check is enough

For command availability, prefer PATH lookup plus a short version command with graceful fallback. Never make diagnostics block the admin page if a binary is missing or a function is disabled.

## Settings in Consumer Apps

For app-specific settings:

- Keep a whitelist of writable keys.
- Validate every input.
- Mask secrets on display.
- Use clear names scoped to the application feature.
- Provide a config-cache clear path when needed.
- Do not add framework config keys unless this is intended as package API.

## Authorization

Every app-level admin route needs authorization. The default is `auth` plus `tyro-dashboard.admin`. If the app uses Tyro RBAC roles/privileges, follow the existing local pattern and read `authorization.md`.

Never rely on sidebar visibility alone. Hidden links are not access control.

## Validation

For app integration changes, normally run:

- `php -l` for new/changed PHP files
- `php artisan route:list --name=<route-fragment>` for new routes
- `php artisan view:cache` for Blade changes
- Focused tests when authorization, persistence, or critical workflows change

## Anti-Patterns

- Editing `vendor/` to customize a consuming app.
- Adding app-only pages to package routes.
- Registering admin routes without admin middleware.
- Showing secret config values on convenience pages.
- Creating new layouts when Tyro layouts already fit.
- Duplicating an existing menu/settings/CRUD pattern instead of extending it.
