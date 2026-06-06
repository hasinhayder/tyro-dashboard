---
name: tyro-dashboard
description: "Use for Tyro Dashboard framework or app integration work: admin pages, routes, sidebar/menu changes, settings, CRUD resources, Blade overrides, RBAC, media, plugins, service providers, public APIs, and upgrade-safe framework maintenance for hasinhayder/tyro-dashboard, hasinhayder/tyro, tyro-login, or Laravel apps consuming Tyro Dashboard."
version: 2.1.1
target: "hasinhayder/tyro-dashboard ^1.34"
peers: "Filament, Nova, Orchid, Backpack"
---

# Tyro Dashboard Agent Guide

Tyro Dashboard is an admin panel framework. Treat package code like framework surface area and app-level published overrides like consumer customization. Your job is to preserve one clear pattern, stable extension contracts, and upgrade safety while still shipping the user's requested change.

## First Decision

Before editing, classify the work:

- **App integration**: routes, controllers, views, config overrides, published `resources/views/vendor/tyro-dashboard/*`, project-specific dashboard pages. Prefer local app code and existing project patterns.
- **Framework internals**: package source, service providers, config defaults, route names, Blade sections, public/protected methods, traits, commands, migrations, publishable assets. Apply strict compatibility rules.
- **Plugin/ecosystem work**: anything a third-party package might call, override, publish, or extend. Preserve extension points and document migration paths.

If unsure, inspect the local code first. Do not assume package internals need changing when a published view, config override, menu hook, route, middleware, or event listener can solve it.

## Core Rules

- **One intended pattern**: follow the pattern already used nearest to the change.
- **Extension over modification**: in consuming apps, avoid editing vendor/package internals; use published views, config, routes, middleware, events, and menu injection.
- **Public surface is sticky**: route names, config keys, Blade sections, view composer variables, public/protected method signatures, publish tags, commands, and env keys require backward compatibility.
- **Security is default**: admin features need `auth` plus Tyro admin middleware or the established Tyro authorization layer. Never expose secrets, tokens, private keys, or raw `.env` values in diagnostic UI.
- **Small blast radius**: keep changes scoped to the subsystem and avoid opportunistic framework redesign.

## Agent Loop

Use this loop for coding tasks:

1. **Inspect**: read nearby routes, controllers, views, config, and tests before choosing a pattern.
2. **Classify**: decide app integration, framework internals, or plugin/ecosystem work.
3. **Load rules**: read only the relevant rule files.
4. **Implement narrowly**: use the nearest established convention and avoid unrelated refactors.
5. **Verify**: run the smallest meaningful syntax, route, Blade, or test checks.
6. **Report**: name changed files, verification, and any residual risk.

## Fast File Maps

Use `rg` first. Typical app-level locations:

- Admin routes: `routes/web.php`
- App controllers: `app/Http/Controllers`
- Published admin layout: `resources/views/vendor/tyro-dashboard/layouts/admin.blade.php`
- Published admin sidebar: `resources/views/vendor/tyro-dashboard/partials/admin-sidebar.blade.php`
- App dashboard views: `resources/views/dashboard`
- Tyro config override: `config/tyro-dashboard.php`

Typical package/framework locations:

- Service providers: `src/*ServiceProvider.php`
- Routes: `routes/*.php`
- Config defaults: `config/*.php`
- Framework views: `resources/views`
- Support APIs: `src/Support`
- Traits/concerns: `src/Concerns`, `src/Traits`
- Controllers/resources: `src/Http`, `src/Resources`

Common publish tags:

- `tyro-dashboard-sidebar`: publishes `partials/admin-sidebar.blade.php` and `partials/user-sidebar.blade.php` only.
- `tyro-dashboard-essentials`: publishes dashboard shell partials (`admin-bar`, sidebars, `topbar`) plus `resources/views/dashboard`.

## Recipes

### Add an app-level admin page

1. Inspect nearby dashboard routes/controllers/views.
2. Add an app controller unless a closure/view route is already the local convention.
3. Use admin middleware, usually `['auth', 'tyro-dashboard.admin']`, or the existing dashboard group.
4. Name routes consistently, e.g. `dashboard.system.environment`.
5. Extend `tyro-dashboard::layouts.admin`.
6. Add sidebar/menu entry only through a published sidebar override or menu extension point.
7. Verify with `php -l`, `php artisan route:list --name=...`, and `php artisan view:cache`.

### Add or change a sidebar item

1. Prefer configured/menu-injected items if the project uses them.
2. If the app has a published sidebar override, edit that override, not vendor package files.
3. Use `request()->routeIs(...)` or the local `$dashboardRoute::pattern(...)` pattern consistently.
4. Keep labels short and icons consistent with surrounding links.
5. Do not make the sidebar query-heavy; guard expensive checks behind config/schema checks.

### Add a settings page

1. Read the existing settings controller/view pattern first.
2. Validate inputs server-side.
3. For `.env` writes, whitelist keys and treat secrets carefully.
4. Do not display secret values unless masked.
5. Provide config-cache guidance or a clear-cache action if changed settings require it.
6. Read `rules/settings-system.md` and `rules/configuration.md` for framework-level settings.

### Add a CRUD resource

1. Check whether the local project uses Tyro resource config, custom controllers, or both.
2. Follow the nearest CRUD pattern for route names, redirects, validation, and Blade structure.
3. Protect admin routes with Tyro authorization.
4. Keep relationship loading explicit to avoid N+1 regressions.
5. Read `rules/crud-resources.md`, `rules/controllers.md`, and `rules/authorization.md` for framework resources.

### Override or create Blade UI

1. Extend the established Tyro layout.
2. Use existing CSS variables and component classes before adding new styles.
3. Keep view section names stable.
4. Avoid card-in-card layouts; use cards for actual panels/items.
5. Check mobile wrapping for long labels, paths, and generated values.
6. Read `rules/user-experience.md`, `rules/views-and-blade.md`, and `rules/dashboard-ui.md` for framework UI.

### Change framework package behavior

1. Identify public API impact before editing.
2. Read the relevant rule files listed below.
3. Preserve existing routes/config/view names unless adding a deprecation path.
4. Prefer optional config flags and additive APIs.
5. Add or update tests for the contract.
6. Document migration notes for any behavior change.

## Rule Index

Load only the rule files needed for the task.

### Ecosystem Integrity

- `rules/public-api-surface.md`: public/protected signatures, config keys, route names, Blade directives, view data
- `rules/plugin-safety.md`: extension point stability, view override precedence, middleware stacking
- `rules/upgrade-path.md`: deprecations, migrations, update commands
- `rules/coupling-boundaries.md`: dependencies, internals access, Laravel version assumptions

### Security and Authorization

- `rules/authorization.md`: RBAC pipeline, `HasTyroRoles`, caching, enforcement hierarchy
- `rules/security.md`: impersonation, sessions, brute force, 2FA reset, audit integrity

### Extension and Configuration

- `rules/app-integration.md`: consuming-app routes, controllers, published views, sidebar overrides, diagnostics
- `rules/extensibility.md`: publishing, config overrides, menu injection, page scaffolding
- `rules/configuration.md`: config structure, env variables, system settings, feature flags
- `rules/service-provider.md`: boot order, view namespace, middleware aliases

### Core Subsystems

- `rules/crud-resources.md`: resources, fields, relationships
- `rules/media-management.md`: uploads, processing, stock photos, media picker
- `rules/controllers.md`: base controllers, actions, audit safety, redirects
- `rules/user-experience.md`: admin UX hierarchy, states, forms, tables, responsive behavior, accessibility
- `rules/dashboard-ui.md`: CSS variables, theming, sidebar, admin bar, notifications
- `rules/views-and-blade.md`: layouts, sections, partials, components
- `rules/middleware.md`: admin/auth middleware, impersonation, order
- `rules/routes.md`: prefix/name prefix, feature gates, route model binding
- `rules/settings-system.md`: env editor, settings tabs, validation, persistence
- `rules/artisan-commands.md`: command naming, installer/update safety

### Maintainability

- `rules/models-and-database.md`: models, migrations, pivots, accessors
- `rules/traits-and-concerns.md`: reusable behavior and schema checks
- `rules/events-and-listeners.md`: audit/login/logout and feature gating
- `rules/error-handling.md`: audit-safe behavior and graceful degradation
- `rules/support-classes.md`: `DashboardRoute`, colors, notices
- `rules/agent-testing.md`: safe verification commands, non-destructive testing boundaries, database protection
- `rules/testing.md`: integration tests and security scenarios
- `rules/naming-conventions.md`: PHP, config, routes, CSS, JS, env/session names

## Anti-Patterns

Avoid these unless the user explicitly asks and you explain the tradeoff:

- Editing files in `vendor/`.
- Modifying framework internals when an app-level route/controller/view/config override works.
- Adding admin pages without Tyro admin authorization.
- Changing route names, config keys, Blade section names, or public method signatures casually.
- Introducing a second pattern for menus, settings, CRUD, or route naming.
- Reading or displaying secrets from `.env`, storage keys, OAuth tokens, API keys, or private keys.
- Running expensive system checks on every sidebar render.
- Replacing established Tyro UI/layout conventions with one-off design systems.
- Adding dependencies to the framework package for convenience without evaluating downstream impact.

## Validation Checklist

Choose checks proportional to the change:

- New/changed PHP file: `php -l path/to/file.php`
- New route: `php artisan route:list --name=<route-fragment>`
- Blade changes: `php artisan view:cache`
- Config changes: `php artisan config:clear` or targeted config assertion when safe
- Tests exist and risk is meaningful: run the focused Pest/PHPUnit test
- Package API behavior changed: add/update tests and read `rules/public-api-surface.md`
- Security/auth changed: read `rules/security.md` and `rules/authorization.md`
- Before running migrations, seeders, queue workers, destructive Artisan commands, or broad tests, read `rules/agent-testing.md`

## Review Stance

For reviews, lead with risks:

1. Security/authorization leaks
2. Breaking public API or plugin contracts
3. Upgrade path regressions
4. Route/config/view naming drift
5. Missing tests for changed behavior
6. Performance issues in shared UI, middleware, or service providers

If no issues are found, say so clearly and mention any residual test gap.
