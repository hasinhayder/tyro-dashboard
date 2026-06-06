# Public API Surface

## Core Principle

Every public and protected method, every config key, every route name, every Blade directive, every view composer variable, every session key, and every publishable asset tag is part of the public API contract. Changing any of them is a breaking change. This is the most impactful rule file in the entire framework — a violation here breaks every consumer application on upgrade.

## What Is the Public API

### Method Signatures
- Any `public` method on a controller, trait, model, support class, concern, or service is public API
- Any `protected` method on a controller or trait that is designed to be called or overridden by subclasses is public API
- `private` methods are NOT public API and can change freely
- Methods marked `@internal` in docblocks are NOT public API — but prefer making them private instead

### Config Keys
- Every key in `config/tyro-dashboard.php` is public API
- Config keys in sibling packages (`config/tyro.php`, `config/tyro-login.php`) are public API of those packages — never modify them from Tyro Dashboard
- Adding a new config key is safe (additive change)
- Renaming or removing a config key is a breaking change — requires deprecation cycle

### Route Names
- All route names resolved via `DashboardRoute` are public API — consumer applications redirect to them
- The route name prefix (`config('tyro-dashboard.routes.name_prefix')`) is configurable but the relative names (`users.index`, `roles.create`) are stable
- Adding new route names is safe
- Renaming or removing a route name is a breaking change

### Blade Directives
- `@hasRole`, `@hasAnyRole`, `@hasAllRoles`, `@hasPrivilege`, `@hasAnyPrivilege`, `@hasAllPrivileges`, `@userCan` are public API
- Adding a new directive is safe
- Renaming or removing a directive is a breaking change

### View Composer Variables
- Variables shared via view composers (`$user`, `$dashboardRoute`, `$allResources`, `$adminMenuItems`, `$commonMenuItems`, `$userMenuItems`) are public API
- Adding new keys to shared arrays is safe
- Renaming or removing a shared variable is a breaking change
- Removing a key from a shared array is a breaking change

### Session Keys
- `impersonator_id`, `tyro-login.otp.*`, `tyro-login.captcha.*`, `login.id`, `login.remember` are public API
- Consumer applications and plugins may read these session keys
- Adding a new session key is safe
- Renaming or changing the format of a session key is a breaking change
- The impersonation key is stored unnamespaced as `impersonator_id` (legacy from before namespacing was introduced) — do not add a `tyro-dashboard.` prefix when adding new session keys; the new convention is flat, package-distinguishing names (e.g. `impersonator_id`, `flash_message`, not `tyro-dashboard.flash_message`)

### Publishable Asset Tags
- `tyro-dashboard-config`, `tyro-dashboard-views`, `tyro-dashboard-views-admin`, `tyro-dashboard-views-user`, `tyro-dashboard-sidebar`, `tyro-dashboard-essentials`, `tyro-dashboard-styles`, `tyro-dashboard-scripts`, `tyro-dashboard-theme`, `tyro-dashboard` are public API
- Adding a new tag is safe
- Renaming or removing a tag is a breaking change — consumer deployment scripts depend on them

### `.env` Variables
- `TYRO_DASHBOARD_*`, `TYRO_*`, `TYRO_LOGIN_*` are public API
- Adding a new `.env` variable is safe
- Renaming or removing a `.env` variable is a breaking change

### Blade Section Names
- `@section('content')`, `@yield('title')`, stack names (`@push('scripts')`) are public API
- Consumer-published layouts depend on these names
- Renaming a section is a breaking change

## What Is NOT Public API

- CSS class names (consumers should use CSS custom properties, not internal class names)
- JavaScript internal function names (consumers should use the documented API: `window.TyroDashboardMediaPicker`)
- Private method implementations
- Internal event listener registration details
- Artisan command output formatting (consumers should not parse command output)

## Legacy Aliases (Deprecated, Public API)

A small number of intentionally-misspelled identifiers are kept as part of the public API for backward compatibility with consumers who adopted them before the spelling was corrected. They are slated for removal in 2.0.

- **Blade component alias** `tyro-dashbaord-media-picker` — class-based `<x-tyro-dashbaord-media-picker>` is registered alongside the canonical `<x-tyro-dashboard-media-picker>` (`TyroDashboardServiceProvider`).
- **Anonymous component namespace** `tyro-dashbaord` — `<x-tyro-dashbaord::component-name>` is registered alongside the canonical `<x-tyro-dashboard::component-name>`.

These exist because the package name was misspelled in the first public release. New code must use the canonical `tyro-dashboard` spellings. Do not add new aliases to this list; do not add the misspellings to documentation, examples, or tests.

## Adding to the Public API

Before adding anything to the public API:
1. Is it a method? Document it with a docblock. Consider `@since` annotation.
2. Is it a config key? Add it to `config/tyro-dashboard.php` with a default. If exposed in settings, add it to `SystemSettingsController::defaultValues()`, add it to `booleanKeys()` if boolean, add validation in `update()`, and add it to `gatherSettings()`.
3. Is it a route? Add it to `routes/web.php`. Ensure it has a route name.
4. Is it a Blade directive? Register it in the service provider.

## Removing from the Public API

1. Deprecate in version 1.N: trigger a deprecation warning, document the replacement.
2. Keep the deprecated API working in 1.N+1.
3. Remove in 2.0.
