# Views & Blade

## Core Principle

Blade templates are the most customized part of any Laravel framework. Section naming and publishable tag design are the difference between "I extended it" and "I forked it."

## Layout Hierarchy

### Three Layout Files
- `app.blade.php` — Role-aware layout. Checks `@hasanyrole('admin', 'superadmin')` to include admin sidebar for admins, user sidebar for non-admins. Includes impersonation banner. Used by the main dashboard page.
- `admin.blade.php` — Always renders admin sidebar. No impersonation banner. Used by admin-only pages (users, roles, privileges, settings, audits).
- `user.blade.php` — Always renders user sidebar. Used by user-facing pages.
### Layout Structure
```
<html>
  <head> — fonts, CSRF, color-scheme meta
  <body>
    @include('tyro-dashboard::partials.admin-bar')
    <div class="dashboard-layout">
      <aside class="sidebar">...</aside>
      <div class="main-content">
        <header class="topbar">...</header>
        <main class="page-content">@yield('content')</main>
      </div>
    </div>
    <div class="sidebar-overlay">
    @include('tyro-dashboard::partials.modal')
    @include('tyro-dashboard::partials.scripts')
```

## Partials

All partials live in `partials/` and have a single responsibility:

- `admin-sidebar` — Navigation for admin users
- `user-sidebar` — Navigation for non-admin users
- `topbar` — Top bar with search, theme toggle, user menu
- `admin-bar` — Fixed announcement bar at page top
- `flash-messages` — Session flash rendering (legacy or toast)
- `shadcn-theme` — CSS custom properties for light/dark theme
- `styles` — All component styles
- `scripts` — All JavaScript (theme, sidebar, modal, toasts, dropdowns)
- `modal` — Global modal dialog container
- `impersonation-banner` — "You are logged in as..." banner
- `media-styles` — Media library specific styles
- `media-script` — Media library and MediaPicker JavaScript

## View Namespacing

- Views are loaded from the package with namespace `tyro-dashboard::`
- Consumer applications override views by placing files at `resources/views/vendor/tyro-dashboard/`
- Laravel's standard view override takes priority — never implement custom resolution

## View Directory Structure

```
resources/views/
├── layouts/          — app, admin, user layouts
├── partials/         — reusable partials (12 files)
├── components/       — anonymous Blade components (media-picker)
├── dashboard/        — admin and user dashboard home pages
├── users/            — user CRUD views (index, create, edit, show)
├── roles/            — role CRUD views
├── privileges/       — privilege CRUD views
├── settings/         — settings layout and partials/
│   └── partials/     — 11 tab partials + scripts + styles
├── audits/           — audit log views (index, show)
├── media/            — media library views
├── profile/          — profile edit, photo, 2FA views
├── invitations/      — invitation acceptance views
├── resources/        — dynamic resource CRUD views (index, create, edit, show)
├── examples/         — example/demo component showcase
└── errors/           — error views (invitation-maintenance, missing-invitation-tables)
```

### Settings Tab Partials
Located in `resources/views/settings/partials/`, prefixed with underscore:
`_tab-dashboard`, `_tab-login-auth`, `_tab-rbac`, `_tab-login-auth-advanced`, `_tab-rbac-advanced`, `_tab-sidebar-colors`, `_tab-admin-bar-colors`, `_tab-dashboard-colors`, `_tab-media`, `_scripts`, `_styles`

### Error Views
- `errors/invitation-maintenance.blade.php` — shown to non-admin users when invitation tables are missing ("system under maintenance")
- `errors/missing-invitation-tables.blade.php` — shown to admin users with migration instructions

## Section & Stack Names

Section names are part of the public API:

- `@yield('title')` — Page title in `<title>` tag
- `@section('content')` — Main page content
- `@stack('scripts')` — Page-specific JavaScript (after framework scripts)
- `@stack('styles')` — Page-specific CSS (after framework styles)

Changing any of these names breaks every consumer-published layout.

## Blade Components

### Anonymous Components
- Registered under `resources/views/components` with namespace `tyro-dashboard`
- `<x-tyro-dashboard::component-name>` syntax
- A legacy misspelled namespace `tyro-dashbaord` is also registered for backward compatibility — see `rules/public-api-surface.md`. New code must use `tyro-dashboard`.

### Class-Based Components
- Registered via `Blade::component()` in the service provider
- `<x-tyro-dashboard-media-picker>` is the primary class-based component
- A legacy alias `<x-tyro-dashbaord-media-picker>` is also registered for backward compatibility — see `rules/public-api-surface.md`
- Component class lives in `src/View/Components/`

## Publishable Tags

- `tyro-dashboard-views` — All views
- `tyro-dashboard-views-admin` — Admin-only views
- `tyro-dashboard-views-user` — User-facing views
- `tyro-dashboard-sidebar` — Admin and user sidebar partials only
- `tyro-dashboard-essentials` — Dashboard shell partials plus the `dashboard` view directory
- `tyro-dashboard-styles` — `styles.blade.php` and `shadcn-theme.blade.php`
- `tyro-dashboard-scripts` — `scripts.blade.php`
- `tyro-dashboard-theme` — `shadcn-theme.blade.php` only
- `tyro-dashboard` — Everything (umbrella tag)

## Common Mistakes

- Renaming a Blade section without a deprecation cycle
- Adding new required sections to layouts without documenting the migration
- Changing the layout structure without updating published layouts
- Using `@include` when a `@stack` would let consumers inject content
