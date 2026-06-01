---
name: tyro-dashboard
description: Framework architecture skill for Tyro Dashboard — an admin panel framework comparable to Filament, Nova, Orchid, and Backpack. Governs how AI generates, reviews, refactors, and maintains Tyro Dashboard code at framework scale.
version: 2.0.0
target: hasinhayder/tyro-dashboard ^1.34
peers: Filament, Nova, Orchid, Backpack
---

# Tyro Dashboard Framework Skill

## Purpose

Tyro Dashboard is an **admin panel framework**. This skill encodes the architectural invariants that protect upgrade safety, plugin compatibility, convention discoverability, ecosystem consistency, public API stability, and coupling containment across hundreds of downstream applications.

## Activation

This skill activates when AI touches any file in `hasinhayder/tyro-dashboard`, `hasinhayder/tyro`, or `hasinhayder/tyro-login` — specifically:

- Any `public` or `protected` method — potential API surface
- Any `config/*.php` key — primary integration contract
- Any Blade section name, view composer key, or publishable tag — extension contract
- Any `.env` variable — public configuration contract
- Any `composer.json` dependency — transitive coupling for all consumers
- Any service provider registration — boot sequence dependency

## Consistency First

Tyro Dashboard is a framework, not an application. Frameworks live or die by consistency.

**The Law of One Pattern.** For any given task, there must be exactly one intended pattern. Two ways to achieve the same result is confusion that compounds across the ecosystem.

**The Stability Gradient.** Code exists on a spectrum. Internal private methods can change freely. Public method signatures, config keys, route names, Blade section names, and publishable tags must never change without a deprecation cycle.

**Backward Compatibility Contract.** Within a major version, the following are breaking changes: renaming a config key, changing a public method signature, removing a route name, changing a Blade section name, removing a publishable tag, changing a database column type, adding a required parameter, or removing a Blade directive. Deprecate first. Remove in the next major version.

**Extension over modification.** Never modify core framework files. Always use extension points: view publishing, config overrides, menu injection, middleware registration, event listeners.

**The Six Framework Questions.** Before writing any framework code, answer: Will this scale across hundreds of projects? Will this break plugins? Will this make upgrades harder? Will this create coupling? Can third-party developers extend this safely? Is there a more convention-driven solution?

## Quick Reference

### Priority 1 — Ecosystem Integrity
Violations break every consumer application and plugin.

- **Public API surface** → `rules/public-api-surface.md` — method signatures, config keys, route names, Blade directives, view composer variables
- **Plugin safety** → `rules/plugin-safety.md` — extension point stability, view override precedence, middleware stacking
- **Upgrade path** → `rules/upgrade-path.md` — deprecation policy, migration tooling, update commands
- **Coupling boundaries** → `rules/coupling-boundaries.md` — dependency graph, package internals access, Laravel version assumptions

### Priority 2 — Security & Authorization
Violations create vulnerabilities in every downstream application.

- **Authorization** → `rules/authorization.md` — RBAC pipeline, HasTyroRoles trait, caching, enforcement hierarchy
- **Security** → `rules/security.md` — impersonation, session management, brute-force lockout, 2FA reset, audit integrity

### Priority 3 — Extension & Customization
Violations prevent consumers from customizing without forking.

- **Extensibility** → `rules/extensibility.md` — view publishing, config publishing, menu injection, page scaffolding, Blade components
- **Configuration** → `rules/configuration.md` — config file structure, .env variables, SystemSettingsController, feature flags
- **Service provider** → `rules/service-provider.md` — boot sequence, registration order, view namespace, middleware aliasing

### Priority 4 — Core Subsystems
Violations break flagship features.

- **CRUD resources** → `rules/crud-resources.md` — HasCrud trait, ResourceController, field auto-detection, relationship handling
- **Media management** → `rules/media-management.md` — upload pipeline, image processing, stock photo import, MediaPicker
- **Controllers** → `rules/controllers.md` — BaseController, action patterns, audit safety, redirect conventions
- **Dashboard UI** → `rules/dashboard-ui.md` — CSS custom properties, light/dark theming, sidebar, admin bar, notifications
- **Views & Blade** → `rules/views-and-blade.md` — layout hierarchy, section naming, partial organization, component registration
- **Middleware** → `rules/middleware.md` — EnsureIsAdmin, HandleImpersonation, aliasing, execution order
- **Routes** → `rules/routes.md` — prefix, name prefix, grouping, feature gating, route model binding
- **Settings system** → `rules/settings-system.md` — .env editor, settings tabs, validation, persistence pipeline
- **Artisan commands** → `rules/artisan-commands.md` — naming, signatures, output, installer safety, update safety

### Priority 5 — Internal Maintainability
Violations make the framework harder to maintain long-term.

- **Models & database** → `rules/models-and-database.md` — Eloquent models, migrations, pivot tables, accessors
- **Traits & concerns** → `rules/traits-and-concerns.md` — HasCrud, HasProfilePhoto, HasTyroRoles, column checking
- **Events & listeners** → `rules/events-and-listeners.md` — Login/Logout audit listeners, feature gating
- **Error handling** → `rules/error-handling.md` — auditSafely, DB constraint parsing, graceful degradation
- **Support classes** → `rules/support-classes.md` — DashboardRoute, DashboardColors, AdminNotice
- **Testing** → `rules/testing.md` — integration preference, feature test coverage, security scenario testing
- **Naming conventions** → `rules/naming-conventions.md` — PHP identifiers, config keys, route names, CSS, JS, session keys, .env

## How to Apply

### For Code Generation
1. Identify the subsystem (CRUD, media, auth, settings, etc.)
2. Read the corresponding rule file
3. If the feature touches multiple subsystems, read all relevant rule files
4. Follow the Consistency First section — match existing patterns
5. Answer the Six Framework Questions before writing code

### For Pull Request Review
1. Identify which subsystems the PR touches
2. Read the corresponding rule files
3. Priority 1–2 rule violations are blocking
4. Priority 3–4 violations require justification
5. Priority 5 violations are advisory
6. Ask: "Would this break a plugin that extended this subsystem?"

### For Refactoring
1. Read `rules/public-api-surface.md` to identify public API impact
2. Read `rules/upgrade-path.md` to design the deprecation cycle
3. Read `rules/plugin-safety.md` to preserve extension points
4. Document breaking changes with migration paths

### For Architectural Decisions
1. Read `rules/coupling-boundaries.md` for dependency rules
2. Read `rules/extensibility.md` for extension point design
3. Design within existing patterns before proposing new ones