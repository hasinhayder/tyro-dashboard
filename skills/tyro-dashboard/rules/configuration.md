# Configuration

## Core Principle

Configuration is the face of the framework for non-developers. When an admin sets `TYRO_DASHBOARD_APP_NAME` and nothing changes, they lose trust in the entire framework. Every configurable value must be reliable, predictable, and complete.

## The Config File

`config/tyro-dashboard.php` is the single source of truth for all framework configuration.

### Structure
- User-facing runtime settings get an `env()` call with a sensible default.
- Framework constants, fixed arrays, and package extension points may stay hardcoded when they are not intended for `.env` editing, such as `routes.middleware`, `routes.name_prefix`, `admin_roles`, `pagination`, `protected`, `widgets`, `resources`, `profile_photo.allowed_types`, and `profile_photo.auto_delete_on_user_delete`.
- Boolean env-backed config values use actual booleans or explicit boolean casting when needed: `env('KEY', true)` or `filter_var(env('KEY', false), FILTER_VALIDATE_BOOLEAN)` — never string booleans.
- Default values are the most common, least surprising choice
- Config is organized into logical sections: `routes`, `admin_roles`, `user_model`, `pagination`, `branding`, `admin_bar`, `collapsible_sidebar`, `features`, `protected`, `widgets`, `notifications`, `uploads`, `profile_photo`, `resources`, `resource_ui`, `disable_examples`, `media`

### Notable Config Keys
- `routes.prefix` — configurable URL prefix via `TYRO_DASHBOARD_PREFIX` (default: `dashboard`)
- `routes.middleware` — fixed package middleware array (`web`, `auth`); change only with public API review
- `routes.name_prefix` — fixed package default (`tyro-dashboard.`); route generation should still go through `DashboardRoute`
- `user_model` — config default comes from `TYRO_DASHBOARD_USER_MODEL`, defaulting to `App\Models\User`; `BaseController::getUserModel()` still falls back to `config('tyro.models.user')` for older integrations
- `pagination.users`, `pagination.roles`, `pagination.privileges` — per-resource pagination limits (default: 15)
- `branding.app_name` — dashboard app name; default follows `APP_NAME` then `Laravel`
- `branding.logo` — app logo URL (nullable)
- `branding.favicon` — favicon URL (nullable)
- `branding.sidebar_logo` — separate sidebar logo (nullable)
- `branding.logo_height` — logo height in CSS units (default: `32px`)
- `branding.sidebar_*` — sidebar color and accordion settings persisted through dashboard env settings
- `admin_bar.*` — persistent admin notice-bar settings
- `uploads.disk`, `uploads.directory`, `uploads.auto_delete_on_resource_delete` — resource upload defaults
- `profile_photo.*` — profile image processing defaults
- `resource_ui.show_global_errors` — show global validation error banner (default: true)
- `resource_ui.show_field_errors` — show per-field validation errors (default: true)
- `notifications.show_flash_messages` — enable/disable flash message display (default: true)
- `notifications.notification_style` — `legacy` or `toast` (default: `legacy`)
- `notifications.toast_position` — `top-right` or `bottom-right` (default: `bottom-right`)
- `media.max_size` — media upload limit in KB (default: `10240`)
- `media.api_keys.*` — stock image provider API keys for Freepik, Pexels, Unsplash, and Pixabay

### Adding a Config Key
1. Add the key to `config/tyro-dashboard.php` with an `env()` default
2. Add the default value to `SystemSettingsController::defaultValues()`
3. If boolean, add the key name to `SystemSettingsController::booleanKeys()`
4. Add a validation rule to `SystemSettingsController::update()`
5. Add the field to the appropriate settings tab partial in `resources/views/settings/partials/`
6. Add the key to `SystemSettingsController::gatherSettings()` so the settings UI reads the saved value

If the key is package-only and not exposed in the settings UI, document that choice in the relevant rule file and do not add unused settings-controller plumbing.

### Removing a Config Key
1. Deprecate in version 1.N: support both old and new keys
2. Trigger a deprecation warning when the old key is used
3. Remove in version 2.0

## `.env` Variables

### Naming Convention
- `TYRO_DASHBOARD_*` for dashboard settings
- `TYRO_*` for core RBAC settings (defined in `config/tyro.php`)
- `TYRO_LOGIN_*` for auth settings (defined in `config/tyro-login.php`)
- Cross-package prefix collisions are not allowed — each package owns its prefix

### Persistence
- `SystemSettingsController::update()` writes to `.env` via `file_get_contents()` / `file_put_contents()`
- After writing, `Artisan::call('config:clear')` is attempted to refresh config cache
- Default values are stripped from `.env` on save — only non-default values persist
- Boolean values serialize as `"true"` / `"false"` strings in `.env`
- The `.env` file must be writable by the web server process — this is a deployment consideration documented in installation

## Feature Flags

### Available Flags
True feature flags live under `config('tyro-dashboard.features.*')`:
- `user_management`, `role_management`, `privilege_management`
- `settings_management`, `profile_management`
- `invitation_system`, `audit_logs`, `system_settings`
- `show_roles_menu`, `show_privileges_menu`, `show_resources_menu`
- `profile_photo_upload`, `gravatar`
- `activity_log` (future feature, currently `false` — not yet implemented)

### Closely-Related Top-Level Toggles (NOT under `features.*`)
A few boolean toggles are at the top level of the config file, NOT under `features.*`. Do not move them under `features` and do not read them as `config('tyro-dashboard.features.collapsible_sidebar')` — they live at the top level:
- `config('tyro-dashboard.collapsible_sidebar')` — enable/disable the collapsible sidebar UI (default: `true`)
- `config('tyro-dashboard.disable_examples')` — hide the "Examples" sidebar section and disable example routes in production (default: `false`)

Other related booleans live in their own sections and should stay there:
- `config('tyro-dashboard.branding.sidebar_accordion_compact')`
- `config('tyro-dashboard.admin_bar.enabled')`
- `config('tyro-dashboard.notifications.show_flash_messages')`
- `config('tyro-dashboard.uploads.auto_delete_on_resource_delete')`
- `config('tyro-dashboard.profile_photo.auto_delete_on_user_delete')`

### Dual Gating
Every feature flag under `features.*` must gate BOTH the UI visibility AND the route registration. Never gate one without the other:
- Disabling `audit_logs` hides the sidebar link AND prevents audit route registration
- Disabling `system_settings` hides the settings link AND prevents settings route registration
- Disabling `show_roles_menu` hides the roles link but the roles routes remain active (admin can still manage roles if they know the URL) — this is intentional: `show_*_menu` flags are visibility-only, not access-control flags

### Adding a Feature Flag
1. Add the config key under `features`
2. Gate the sidebar link in the appropriate partial
3. Gate the route registration in `routes/web.php`
4. Gate any event listeners in the service provider

## Dashboard Colors

Dashboard colors use separate persistence from `.env`:

- **Storage:** `storage/app/dashboard-colors.json` via `DashboardColors` static class
- **Format:** JSON with `light` and `dark` keys, each containing CSS custom property definitions with `hex` and `alpha`
- **Loading:** `DashboardColors::load()` reads JSON; returns defaults if file doesn't exist
- **Saving:** `DashboardColors::save(array)` writes JSON; creates directory if needed
- **Color format:** `{hex: "#09090b", alpha: 100}` — hex string with alpha integer (0-100)
- **Conversion:** `DashboardColors::hexAlphaToRgba(string $hex, int $alpha)` produces CSS `rgba(r, g, b, a)`

### Color Variables (per theme)
`--background`, `--foreground`, `--card`, `--card-foreground`, `--popover`, `--popover-foreground`, `--primary`, `--primary-foreground`, `--secondary`, `--secondary-foreground`, `--muted`, `--muted-foreground`, `--accent`, `--accent-foreground`, `--destructive`, `--destructive-foreground`, `--border`, `--input`, `--ring`, `--success`, `--success-foreground`, `--warning`, `--warning-foreground`, `--info`, `--info-foreground`, `--danger`

## Settings UI

`SystemSettingsController` provides the web-based `.env` editor:

- `gatherSettings()` reads ~160 config values from `tyro-dashboard`, `tyro`, `tyro-login` namespaces
- `update()` validates, saves dashboard colors, writes `.env`, and attempts to clear config cache
- Settings view uses vertical tabs with partials per tab in `settings/partials/`
- Form submission is AJAX with JSON response and toast notifications
- The settings route is behind `tyro-dashboard.admin` middleware
