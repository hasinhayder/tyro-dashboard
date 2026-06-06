# Settings System

## Core Principle

The settings system is the bridge between developers (who understand `.env`) and administrators (who understand web forms). A broken save corrupts the `.env` file — that takes down the entire application, not just the dashboard.

## SystemSettingsController

### index() — Read
- `gatherSettings()` reads ~160 config values from three namespaces: `tyro-dashboard`, `tyro`, `tyro-login`
- Returns values keyed by their `.env` variable names
- Passes to the settings Blade view

### update() — Write
1. Validate all ~160 form fields against per-field rules (string, boolean, integer, regex for hex colors, `in:` for enums)
2. Process `dashboard_colors` separately via `DashboardColors::save()`
3. Open `.env` via `file_get_contents(base_path('.env'))`
4. For each submitted value:
   - If `null`: remove the line from `.env`
   - If matches hardcoded default: remove from `.env` (keeps file clean)
   - Otherwise: write `KEY="value"` to `.env` (update existing or append)
5. Attempt `Artisan::call('config:clear')`; the save response still succeeds if cache clearing throws
6. Return JSON response

### Boolean Handling
- `booleanKeys()` returns an array of 55 boolean `.env` key names
- Boolean values serialize as `"true"` / `"false"` strings in `.env`
- Form fields for booleans are checkboxes (checked = true, unchecked = false)

### Default Values
- `defaultValues()` returns hardcoded defaults for all ~160 env vars
- If submitted value equals the default, the key is removed from `.env`
- This keeps `.env` readable — only customized values appear

## Adding a Config Key (Checklist)

When adding a new configurable setting:
1. Add config key to `config/tyro-dashboard.php` with `env()` default
2. Add default value to `SystemSettingsController::defaultValues()`
3. If boolean, add key name to `SystemSettingsController::booleanKeys()`
4. Add validation rule to `update()` method
5. Add field to the appropriate settings tab partial in `resources/views/settings/partials/`
6. Add to `gatherSettings()` for read path

## Settings UI

### Tab Layout
Vertical tabs (vtabs) with left sidebar navigation and right content panel:

| Tab | Partial | Config Namespace |
|-----|---------|-----------------|
| Dashboard | `_tab-dashboard` | `tyro-dashboard` |
| Authentication | `_tab-login-auth` | `tyro-login` |
| Authorization | `_tab-rbac` | `tyro` |
| Authentication+ | `_tab-login-auth-advanced` | `tyro-login` |
| Authorization+ | `_tab-rbac-advanced` | `tyro` |
| Sidebar | `_tab-sidebar-colors` | `tyro-dashboard.branding` |
| Admin Bar | `_tab-admin-bar-colors` | `tyro-dashboard.admin_bar` |
| Dashboard Colors | `_tab-dashboard-colors` | JSON (not `.env`) |
| Media | `_tab-media` | `tyro-dashboard.media` |

### Conditional Field Visibility
Fields that depend on a parent toggle use JavaScript to show/hide. OTP fields are hidden when OTP is disabled. Social login fields are hidden when social login is disabled. This prevents confusion about which settings apply.

### AJAX Submission
- Form posts via `fetch()` to `update()` endpoint
- Response is JSON: `{success: true/false, message: '...'}`
- Success → toast notification, no page reload
- Error → toast notification with error message

### Live Previews
- Sidebar color pickers update a live sidebar preview
- Admin bar color pickers update a live admin bar preview
- Dashboard colors update the shadcn theme live via CSS custom property injection

## Dashboard Colors Persistence

- Dashboard colors are NOT stored in `.env`
- Stored in `storage/app/dashboard-colors.json` via `DashboardColors`
- Light mode and dark mode palettes are stored separately
- Each color has `hex` and `alpha` components
- The settings tab has hex color pickers and alpha sliders per variable
- Global reset resets all variables to defaults
- Per-variable reset resets one variable to its default

## `.env` Write Safety

- The `.env` file must be writable by the web server process
- This is a deployment consideration — document it
- If `.env` is not writable, the save fails and an error is returned
- Never assume `.env` is writable — handle the error gracefully
- After writing, `config:clear` is attempted so the new values can be picked up without a manual cache clear
- The explicit clear-cache endpoint reports `Config cache cleared.` on success and `Config clear skipped.` when Artisan throws
