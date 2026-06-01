# Support Classes

## Core Principle

Support classes are the glue between config, views, and controllers. Wrong route name resolution breaks every redirect. Wrong color storage corrupts the theme. Small utility classes have outsized impact.

## DashboardRoute

### Purpose
Manages route name prefixing so controllers and views never hardcode route name strings.

### Public API
- `DashboardRoute::prefix()` — returns the normalized route name prefix from config (with trailing dot)
- `DashboardRoute::legacyPrefix()` — returns the hardcoded legacy prefix `tyro-dashboard.` (with trailing dot)
- `DashboardRoute::name(string $name)` — generates a full route name with the configured prefix
- `DashboardRoute::pattern(string $pattern)` — generates a full route pattern (like `name()` but defaults to `*` for route pattern matching)
- `DashboardRoute::translate(string $name)` — resolves missing named routes between legacy and current prefixes
- `DashboardRoute::normalizePrefix(?string $prefix)` — ensures trailing dot, handles null/empty

### Usage
```php
// In controllers:
redirect()->route(DashboardRoute::name('users.index'));

// In views:
{{ route(DashboardRoute::name('users.index')) }}
```

Never use `route('tyro-dashboard.users.index')` — the prefix is configurable.

### Legacy Support
- `resolveMissingNamedRoutesUsing` hook checks legacy prefix when current prefix doesn't match
- This allows consumers to migrate route name references gradually

## DashboardColors

### Purpose
Manages the shadcn/ui theme color palette, stored as JSON separately from `.env`.

### Storage
- File: `storage/app/dashboard-colors.json`
- Format: `{ "light": {...}, "dark": {...} }`
- Each color: `{ "hex": "#09090b", "alpha": 100 }`

### Public API
- `DashboardColors::defaults()` — returns all 50 color definitions (25 light + 25 dark)
- `DashboardColors::load()` — reads JSON, returns arrays with defaults fallback
- `DashboardColors::save(array $data)` — writes JSON, creates directory if needed
- `DashboardColors::form()` — merges saved overrides with defaults for the settings form
- `DashboardColors::hexAlphaToRgba(string $hex, int $alpha)` — converts to CSS `rgba(r, g, b, a)`

### Integration
- `shadcn-theme.blade.php` calls `DashboardColors::load()` and emits CSS custom properties
- The settings UI tab calls `DashboardColors::form()` for the color picker values
- Writes go through `DashboardColors::save()` before the `.env` write in `SystemSettingsController`

### Design Constraints
- Never add a second storage mechanism for colors — JSON file is the single source of truth
- Never mix color storage with `.env` — they are separate persistence mechanisms
- The JSON structure is part of the public API — changing it breaks saved color configurations

## AdminNotice

### Purpose
Provides both config-driven and programmatic admin bar activation.

### Location
`src/Services/AdminNotice.php` under namespace `HasinHayder\TyroDashboard\Services`

### Public API
- `AdminNotice::show(string $message, ?string $bgColor, ?string $textColor, ?string $align)` — programmatic activation
- `AdminNotice::hasNotice()` — returns true if programmatic notice is set OR config-driven bar is enabled with a message
- `AdminNotice::getMessage()` — current message (programmatic or config fallback); strips all tags except `<p><a><b><i><s><u><span>`
- `AdminNotice::getBgColor()` — current background color (programmatic or config fallback)
- `AdminNotice::getTextColor()` — current text color (programmatic or config fallback)
- `AdminNotice::getAlign()` — current alignment (programmatic or config fallback)
- `AdminNotice::getHeight()` — current height (from config only)

### Activation Modes
- **Config-driven:** `config('tyro-dashboard.admin_bar.enabled')` — persistent from `.env`
- **Programmatic:** `AdminNotice::show(...)` — runtime-only, one request
- Programmatic takes precedence over config — if both are active, programmatic wins

### Integration
- `admin-bar.blade.php` partial reads from `AdminNotice` methods
- Config values are fallbacks when programmatic mode is not active
- The admin bar partial is included in all layouts

## Adding a New Support Class

1. Place in `src/Support/` under `HasinHayder\TyroDashboard\Support` (for pure utility classes with no HTTP context) or `src/Services/` under `HasinHayder\TyroDashboard\Services` (for service classes that may hold request-scoped state)
2. Use static methods where instantiation is unnecessary
3. Do not depend on HTTP context unless explicitly passed
4. Document the public API — support classes are often used by consumers
5. Do not add a Facade unless the class is called from userland application code
