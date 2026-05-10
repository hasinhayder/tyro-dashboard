# Tyro Dashboard - AI Agent Skill

## Overview

Tyro Dashboard is a comprehensive Laravel package (`hasinhayder/tyro-dashboard`) that provides a complete admin & user dashboard with RBAC, user management, and dynamic CRUD interfaces. It is built on top of **Tyro** (RBAC framework) and **Tyro Login** (authentication system).

- **Package**: `hasinhayder/tyro-dashboard`
- **Version**: `1.31.0`
- **Namespace**: `HasinHayder\TyroDashboard`
- **Source**: `src/`
- **Views**: `resources/views/`
- **Routes**: `routes/web.php`
- **Config**: `config/tyro-dashboard.php`
- **PHP**: `^8.2`
- **Laravel**: `^12.0 || ^13.0`

## Architecture

```
src/
  Console/Commands/          # Artisan commands
  Concerns/                  # Traits for models (HasCrud)
  Http/
    Controllers/             # Dashboard, User, Role, Privilege, Resource, Media, Settings, Widgets, etc.
    Middleware/              # EnsureIsAdmin, HandleImpersonation
  Models/
    Media.php                # Media library model (tyro_media table)
    StarredImportImage.php   # Starred imported images model
  Providers/
    TyroDashboardServiceProvider.php
  Services/
    AdminNotice.php          # Runtime admin bar notices
  Support/
    DashboardRoute.php       # Route name helper with prefix translation
    DashboardColors.php      # Light/dark mode CSS variable overrides
  Traits/
    HasProfilePhoto.php      # Profile photo upload & Gravatar support
  View/Components/
    MediaPicker.php          # Media picker Blade component

resources/views/
  layouts/                   # admin.blade.php, user.blade.php, app.blade.php
  partials/                  # sidebars, styles, scripts, flash-messages, media-styles, media-script, etc.
  dashboard/                 # admin.blade.php, user.blade.php, index.blade.php
  users/                     # CRUD views
  roles/                     # CRUD views
  privileges/                # CRUD views
  resources/                 # Dynamic CRUD views (index, create, edit, show)
  profile/                   # Profile management views
  invitations/               # Invitation system views
  audits/                    # Audit log views
  examples/                  # Widgets & components demos
  media/                     # Media library index (grid/list browser, upload, crop/resize)
  settings/                  # System settings UI with tabbed env management
  pagination/                # Custom pagination view (tyro.blade.php)
  components/                # Anonymous Blade components (media-picker.blade.php)
  errors/                    # Missing tables, maintenance pages

config/tyro-dashboard.php    # Main configuration file
routes/web.php               # Package routes
```

## Dependencies

- `hasinhayder/tyro` (^1.5) — RBAC framework (Role, Privilege, AuditLog models)
- `hasinhayder/tyro-login` (^2.4) — Authentication & invitation system
- `intervention/image` (^4.0) — Image processing (crop, resize, WebP conversion, thumbnail generation)
- Optional: `mews/purifier` — HTML sanitization for richtext fields

## Console Commands

All commands use the `tyro-dashboard:` namespace.

### Installation & Setup

| Command | Description |
|---------|-------------|
| `tyro-dashboard:install [--force]` | Interactive installer. Checks deps, publishes config/views, creates super user |
| `tyro-dashboard:createsuperuser` | Create a superuser with admin role interactively |
| `tyro-dashboard:version` | Display version, Laravel/PHP info, dependency status |
| `tyro-dashboard:setup-ai-skill` | Install the AI skill file for Claude, Copilot, Codex, Gemini, Kilo, or Laravel Boost |

### Publishing Resources

| Command | Description |
|---------|-------------|
| `tyro-dashboard:publish [--force] [--all] [--style] [--views] [--user] [--admin] [--config]` | Publish views, config, or styles interactively or via flags |
| `tyro-dashboard:publish-style [--force] [--theme-only]` | Publish styles & shadcn theme variables |

### Page Generators

| Command | Description |
|---------|-------------|
| `tyro-dashboard:create-admin-page [name] [--force]` | Create an admin-only page (extends `layouts.admin`) + route + sidebar link |
| `tyro-dashboard:create-user-page [name] [--force]` | Create a user page (extends `layouts.user`) + route + sidebar link |
| `tyro-dashboard:create-common-page [name] [--force]` | Create a page visible in both sidebars (extends `layouts.app`) + route |
| `tyro-dashboard:remove-admin-page [name]` | Remove admin page, route, and sidebar link |
| `tyro-dashboard:remove-user-page [name]` | Remove user page, route, and sidebar link |
| `tyro-dashboard:remove-common-page [name]` | Remove common page, route, and both sidebar links |

### Resource & CRUD

| Command | Description |
|---------|-------------|
| `tyro-dashboard:make-resource {name}` | Scaffold Model, Migration, Controller, Form Requests. Outputs config snippet for `config/tyro-dashboard.php` |
| `tyro-dashboard:clear-cache [--model=]` | Clear cached field configurations for Dynamic CRUD resources |

### Updates

| Command | Description |
|---------|-------------|
| `tyro-dashboard:update` | Run `update-style`, `update-script`, `update-config`, and patch published sidebars/flash-messages |
| `tyro-dashboard:update-config [--with-backup]` | Force-publish latest config (with optional backup) |
| `tyro-dashboard:update-style` | Force-publish latest styles |
| `tyro-dashboard:update-script` | Force-publish latest scripts |

## Configuration (`config/tyro-dashboard.php`)

Key sections agents should know:

- **`routes`** — `prefix` (default `dashboard`), `middleware`, `name_prefix`
- **`admin_roles`** — `['admin', 'super-admin']`
- **`user_model`** — Default `App\Models\User`
- **`pagination`** — `users`, `roles`, `privileges` per-page defaults (all `15`)
- **`branding`** — `app_name`, `logo`, `logo_height`, `favicon`, `sidebar_bg`, `sidebar_text`, `sidebar_primary`, `sidebar_accent`, `sidebar_accent_foreground`, `sidebar_header_border`, `sidebar_accordion_compact`, `sidebar_logo`
- **`admin_bar`** — Enable global notices with colors/alignment, `height`
- **`collapsible_sidebar`** — Enable collapsible sidebar (default `true`)
- **`features`** — Toggle: `user_management`, `role_management`, `privilege_management`, `settings_management`, `profile_management`, `invitation_system`, `audit_logs`, `system_settings`, `show_roles_menu`, `show_privileges_menu`, `show_resources_menu`, `activity_log` (future), `profile_photo_upload`, `gravatar`
- **`protected`** — Role slugs and user IDs that cannot be deleted
- **`widgets`** — Dashboard widget toggles
- **`notifications`** — `show_flash_messages`, `auto_dismiss_seconds`, `notification_style` (`legacy` or `toast`), `toast_position`
- **`uploads`** — Default disk (`public`), directory, auto-delete behavior
- **`profile_photo`** — Disk, directory, max size, dimensions, quality, crop position, allowed types, `auto_delete_on_user_delete`
- **`resources`** — Array of dynamic CRUD resource definitions
- **`resource_ui`** — `show_global_errors`, `show_field_errors`
- **`disable_examples`** — Hide example routes and sidebar sections
- **`media`** — `max_size`, `api_keys` (freepik, pexels, unsplash, pixabay)

## Routes (`routes/web.php`)

All routes are grouped under the configured prefix (default `/dashboard`) with `web` and `auth` middleware. Route names use the `tyro-dashboard.` prefix by default (configurable via `routes.name_prefix`).

### Route Groups

1. **Dashboard Home** — `GET /`
2. **Examples** (disabled in production or via `disable_examples`)
   - `/components`, `/examples/components` — Demo page with KPIs, charts, progress, info cards
   - `/widgets`, `/examples/widgets` — Interactive widget demo page
   - `/x-components` (if `TyroDashboardComponentsServiceProvider` exists)
   - Widget proxy routes: `xkcd/{id}`, `stocks/{symbol}`, `fx/{base}`, `flights`
3. **Profile** (`prefix: profile`)
   - `GET /` — View profile
   - `PUT /update` — Update profile
   - `PUT /password` — Change password
   - `DELETE /photo` — Delete own photo
   - `DELETE /2fa/reset` — Reset own 2FA
4. **Invitations** (if enabled)
   - `GET /invitations` — User invitation panel
   - `POST /invitations/create` — Create own invitation link
5. **Media Library** (all authenticated users)
   - `GET /media` — Browse media library (grid/list view)
   - `POST /media/upload` — Upload with WebP auto-conversion, thumbnail generation
   - `GET /media/picker` — AJAX picker modal
   - `GET /media/image-search` — External image search (Unsplash, Pixabay, Freepik, Pexels)
   - `POST /media/image-import` — Import selected image
   - `POST /media/starred-images` / `DELETE /media/starred-images` — Starred import management
   - `POST /media/{media}/alt` — Update alt text
   - `PATCH /media/{media}/rename` — Rename file
   - `POST /media/{media}/crop-resize` — Crop/resize with Intervention Image
   - `DELETE /media/{media}` — Delete media
6. **Leave Impersonation** — `POST /leave-impersonation`
7. **Admin Routes** (`middleware: tyro-dashboard.admin`)
   - **Users**: `/users` — CRUD, suspend, unsuspend, login-as, reset 2FA (`DELETE /users/{id}/2fa`), delete photo
   - **Roles**: `/roles` — CRUD, remove user from role
   - **Privileges**: `/privileges` — CRUD, remove role from privilege
   - **Invitations Admin**: `/invitations/admin` — Manage invitation links
   - **Audits**: `/audits` — View, export CSV, bulk delete, flush
   - **System Settings** (if enabled): `/settings/system` — `.env` management UI, config cache clear
8. **Dynamic Resources** (`prefix: resources/{resource}`)
   - Full CRUD: index, create, store, show, edit, update, destroy

### Route Name Helper

Always use `DashboardRoute::name('route.name')` instead of hardcoding `tyro-dashboard.route.name`. This ensures compatibility when users change `routes.name_prefix` in config.

```php
use HasinHayder\TyroDashboard\Support\DashboardRoute;

DashboardRoute::name('users.index');      // tyro-dashboard.users.index
DashboardRoute::name('resources.index');  // tyro-dashboard.resources.index
```

## Controllers

### BaseController

All controllers extend `BaseController`. It provides:

- `getUserModel(): string` — Returns configured user model class
- `isAdmin(): bool` — Checks if current user has any `admin_roles`
- `getViewData(array $data = []): array` — Merges `branding`, `isAdmin`, `user` into view data

### DashboardController

- `index(Request)` — Shows `dashboard.admin` for admins, `dashboard.user` for regular users

### UserController (admin-only)

- `index(Request)` — List users with search, role filter, status filter (suspended/active)
- `create()`, `store(Request)`, `edit($id)`, `update(Request, $id)`, `destroy($id)`
- `suspend(Request, $id)` — With reason validation
- `unsuspend($id)`
- `reset2FA($id)`
- `loginAs($id)` — Impersonation (stores `impersonator_id` in session)
- `leaveImpersonation()` — Return to original admin account
- Protected users and self-deletion/suspension are blocked

### RoleController (admin-only)

- Full CRUD + `show($id)` with privileges and users
- `removeUser($id, $userId)` — Detach user from role
- Protected roles (from config) cannot be deleted
- Audit logging for all changes

### PrivilegeController (admin-only)

- Full CRUD + `show($id)` with roles
- `removeRole($id, $roleId)` — Detach role from privilege
- Audit logging for all changes

### ProfileController (all authenticated users)

- `index(Request)` — Profile page
- `update(Request)` — Update name, email, photo, gravatar preference
- `updatePassword(Request)` — Change password (requires current_password)
- `reset2FA(Request)` — Reset own 2FA
- `deletePhoto(Request)` — Delete own photo
- `deleteUserPhoto(Request, $id)` — Admin: delete another user's photo

### ResourceController (Dynamic CRUD)

Handles all dynamic resources defined in config or via `HasCrud` trait.

- `index($resource)` — List with search, sort, eager loading
- `create($resource)` — Form with relationship options pre-loaded
- `store(Request, $resource)` — Validation, file uploads, boolean handling, relationship syncing
- `show($resource, $id)` — Detail view with richtext sanitization
- `edit($resource, $id)` — Form with pre-selected relationship values
- `update(Request, $resource, $id)` — Validation with unique rule ID injection, file replacement, relationship syncing
- `destroy($resource, $id)` — Delete with optional file cleanup

Access control: `hasAccess()` checks `roles`/`readonly` config. `isReadonly()` restricts create/edit/update/destroy.

### AuditController (admin-only)

- `index(Request)` — Searchable/filterable audit logs (event, actor, date range)
- `destroy($id)` — Single entry delete
- `bulkDestroy(Request)` — Multi-select delete
- `flush(Request)` — Delete all audit logs
- `exportCsv(Request)` — Streamed CSV download with current filters
- `ensureAuditAvailable()` — Redirects if audit logs disabled or table missing

### InvitationController

- `adminIndex(Request)`, `adminCreate()`, `adminStore(Request)`, `adminDestroy($id)`
- `userIndex()`, `userCreate()`
- Checks if invitation system is enabled and tables exist

### ComponentsController (all authenticated users, examples)

- `components(Request)` — Demo page with KPIs, charts, progress, info cards, activity

### WidgetsController (all authenticated users, examples)

- `widgets(Request)` — Interactive widget demo page
- `xkcd($id)` — Same-origin proxy for XKCD comic API
- `stockQuote($symbol)` — Same-origin proxy for Stooq stock API
- `fxRates($base)` — Same-origin proxy for open.er-api.com FX rates
- `flightStates()` — Same-origin proxy for OpenSky Network flight data

### XComponentsController (all authenticated users, optional)

- `index()` — Reusable form components demo (when `TyroDashboardComponentsServiceProvider` exists)

### MediaController (all authenticated users)

- `index(Request)` — Media library with grid/list view, search, load-more
- `store(Request)` — Upload file with WebP conversion, thumbnail generation (via Intervention Image)
- `picker(Request)` — AJAX-based media picker modal for use in forms
- `imageSearch(Request)` — Search external image providers (Unsplash, Pixabay, Freepik, Pexels)
- `imageImport(Request)` — Download and store a selected external image
- `storeStarredImage(Request)` — Star/bookmark an import result
- `destroyStarredImage(Request)` — Remove star from an import result
- `updateAlt(Request, $media)` — Update alt text
- `rename(Request, $media)` — Rename file
- `cropResize(Request, $media)` — Crop/resize using Intervention Image
- `destroy($media)` — Delete media file

### SystemSettingsController (admin-only)

- `index(Request)` — Settings page with tabbed UI for Tyro Dashboard, Tyro (RBAC), and Tyro Login `.env` configs
- `update(Request)` — Save env settings (writes to `.env`, supports DashboardColors light/dark mode overrides)
- `clearConfigCache(Request)` — Run `config:clear` Artisan command

## Middleware

### EnsureIsAdmin

- Checks authenticated user has any role in `admin_roles` config
- Redirects to `tyro-login.login` > `login` > `/login` if unauthenticated
- Redirects to dashboard index with error if not admin

### HandleImpersonation

- Pushed to `web` middleware group automatically
- Intercepts logout requests (`tyro-login.logout`) when `impersonator_id` is in session
- Redirects to `leave-impersonation` instead of logging out

## Dynamic CRUD (`HasCrud` Trait)

Models can use `HasinHayder\TyroDashboard\Concerns\HasCrud` to auto-generate admin interfaces.

### How to Use

```php
use HasinHayder\TyroDashboard\Concerns\HasCrud;

class Post extends Model {
    use HasCrud;

    protected $fillable = ['title', 'body', 'published_at', 'category_id'];

    // Optional overrides
    protected $resourceTitle = 'Blog Posts';
    protected $resourceTitleSingular = 'Blog Post';
    protected $resourceKey = 'posts'; // URL slug
    protected $resourceRoles = ['admin', 'editor']; // Who can access
    protected $resourceReadonly = ['viewer']; // Read-only roles
    protected $resourceUploadDisk = 'public';
    protected $resourceUploadDirectory = 'blog-images';

    // Optional: explicit field definitions (overrides auto-detection)
    protected $resourceFields = [
        'title' => ['type' => 'text', 'label' => 'Post Title', 'rules' => 'required|max:255', 'searchable' => true],
        'body' => ['type' => 'richtext', 'label' => 'Content'],
        'published_at' => ['type' => 'date', 'label' => 'Publish Date'],
        'category_id' => ['type' => 'select', 'label' => 'Category', 'relationship' => 'category', 'option_label' => 'name'],
    ];

    // Optional: override specific auto-detected fields
    protected $resourceFieldOverrides = [
        'body' => ['type' => 'markdown'],
    ];

    public function category() {
        return $this->belongsTo(Category::class);
    }
}
```

Visit `/dashboard/resources/posts` — full CRUD is live.

### Auto-Detected Field Types

`HasCrud::guessFieldConfig()` inspects column names and database schema:

| Pattern | Type |
|---------|------|
| `_id` suffix | `select` with relationship |
| `email` | `email` |
| `password` | `password` (hidden in index) |
| `markdown` | `markdown` |
| `description`, `bio`, `content`, `body`, `notes`, `comment` | `textarea` |
| `date` (not update/create) | `date` |
| `time` (not update/create) | `time` |
| `image`, `photo`, `picture`, `avatar`, `file`, `document` | `file` |
| `price`, `amount`, `cost`, `salary` | `number` (numeric) |
| `quantity`, `count`, `number`, `age`, `year`, `pages` | `number` (integer) |
| `is_`, `has_`, `can_`, `should_`, `must_` prefix | `boolean` |
| `url`, `link`, `website` | `url` |
| DB `boolean` | `boolean` |
| DB `integer`, `bigint`, `smallint` | `number` (integer) |
| DB `decimal`, `float`, `double` | `number` (numeric) |
| DB `text`, `longtext`, `mediumtext` | `textarea` |
| DB `date` | `date` |
| DB `datetime`, `timestamp` | `datetime-local` |
| DB `time` | `time` |
| DB `enum` | `select` with options |
| Default | `text` |

### Supported Field Types in Views

- `text`, `email`, `password`, `url`, `number`, `textarea`, `richtext`, `markdown`
- `date`, `time`, `datetime-local`
- `boolean` (checkbox)
- `file` (upload with disk/directory config)
- `select` (single or `multiple` with `relationship`)
- `multiselect` (many-to-many relationship)
- `radio`, `checkbox` (with `relationship` for many-to-many)

### Field Options

- `label` — Display label (auto-generated from field name if omitted)
- `rules` — Laravel validation rules
- `relationship` — Eloquent relationship method name
- `option_label` — Column to display in select options (tries `name`, `title`, `label`, `email`, `code`)
- `multiple` — For select fields (true = many-to-many)
- `searchable` — Include in search queries
- `sortable` — Allow column sorting
- `hide_in_index` — Hide from list view
- `hide_in_create` / `hide_in_edit` — Hide from specific forms
- `default` — Default value
- `placeholder` — Placeholder text
- `attributes` — Additional HTML attributes array
- `readonly` — Make field read-only in forms
- `display_image` — Show image in index view
- `display_image_position` — Image position in index (`left`, `right`, `background`)

### File Uploads

Three-tier priority:
1. Model-level: `$resourceUploadDisk`, `$resourceUploadDirectory`
2. Resource-level: `upload_disk`, `upload_directory` in config
3. Global-level: `tyro-dashboard.uploads.disk`, `tyro-dashboard.uploads.directory`

Auto-delete on resource deletion controlled by `uploads.auto_delete_on_resource_delete`.

### Caching

`HasCrud` caches generated field configs for 6 hours. Use `tyro-dashboard:clear-cache` or `$model::clearFieldCache()` after schema changes.

## Traits

### HasProfilePhoto

Add to User model for profile photo support:

```php
use HasinHayder\TyroDashboard\Traits\HasProfilePhoto;

class User extends Authenticatable {
    use HasProfilePhoto;
}
```

Provides:
- `updateProfilePhoto($uploadedFile)` — Resize, orient, store
- `deleteProfilePhoto()` — Remove from storage
- `getProfilePhotoUrlAttribute()` — URL or Gravatar or UI Avatars fallback
- `getGravatarUrlAttribute()` — Gravatar URL
- `hasProfilePhotoColumn()`, `hasGravatarColumn()` — Schema checks

Requires migration columns: `profile_photo_path` (string, nullable), `use_gravatar` (boolean, default false).

## Services

### AdminNotice

Display runtime admin bar notices:

```php
use HasinHayder\TyroDashboard\Services\AdminNotice;

AdminNotice::show('Maintenance at 10 PM', '#ffcc00', '#000000', 'center');
```

Falls back to `config('tyro-dashboard.admin_bar')` if not set programmatically.

## Support Classes

### DashboardRoute

Handles route name prefixing and legacy name translation.

```php
DashboardRoute::name('users.index');        // tyro-dashboard.users.index
DashboardRoute::prefix();                   // tyro-dashboard.
DashboardRoute::translate('tyro-dashboard.users.index'); // Handles custom prefixes
```

Always use this in controllers/views instead of hardcoded strings.

### DashboardColors

Stores/loads light/dark mode CSS variable overrides in `storage/app/dashboard-colors.json`. Provides defaults for 20+ shadcn CSS custom properties.

```php
use HasinHayder\TyroDashboard\Support\DashboardColors;

$colors = DashboardColors::load();                    // Load with defaults merged
$saved  = DashboardColors::save($overrides);           // Save overrides
$light  = DashboardColors::getLightDefaults();         // Get defaults for light mode
$dark   = DashboardColors::getDarkDefaults();          // Get defaults for dark mode
```

## Models

### Media

Eloquent model for the `tyro_media` table. Columns: `user_id`, `filename`, `path`, `webp_path`, `thumbnail_path`, `disk`, `mime_type`, `size`, `alt_text`, `source_url`. Provides accessors: `url`, `webp_url`, `thumbnail_url`, `formatted_size`, `is_image`. Static helpers: `thumbnailUrlFrom()`, `webpUrlFrom()`.

### StarredImportImage

Eloquent model for the `tyro_starred_import_images` table. Columns: `user_id`, `star_key`, `provider`, `external_id`, `alt`, `author`, `thumb_url`, `preview_url`, `download_url`, `download_location`, `source_url`, `payload` (JSON), `starred_at`. Has `toImporterArray()` for API serialization.

## View Components

### MediaPicker (Blade Component)

Registered as `<x-tyro-dashboard-media-picker>` (also aliased as `<x-tyro-dashbaord-media-picker>` for backwards compatibility). Props: `name`, `id`, `value`, `output` (original/thumb/webp/select), `buttonText`, `placeholder`, `label`, `width`, `button` (style), `preview` (boolean), `preview_position`, `preview_width`, `preview_height`, `circle`. Pushes `media-styles` and `media-script` to `@stack('styles')` and `@stack('scripts')` via `@once`.

## Views & Blade

### Layouts

- `tyro-dashboard::layouts.admin` — Admin dashboard layout
- `tyro-dashboard::layouts.user` — User dashboard layout
- `tyro-dashboard::layouts.app` — Common/generic layout

### Key Sections

All layouts support:
- `@section('title')` — Page title
- `@section('breadcrumb')` — Breadcrumb trail
- `@section('content')` — Main content

### Partials

- `partials/admin-sidebar.blade.php` — Admin navigation
- `partials/user-sidebar.blade.php` — User navigation
- `partials/topbar.blade.php` — Top bar with user menu
- `partials/flash-messages.blade.php` — Success/error notifications
- `partials/styles.blade.php` — All CSS/styles
- `partials/scripts.blade.php` — All JavaScript
- `partials/shadcn-theme.blade.php` — shadcn CSS variables
- `partials/admin-bar.blade.php` — Global notice bar
- `partials/impersonation-banner.blade.php` — Impersonation indicator
- `partials/modal.blade.php` — Reusable modal component
- `partials/media-styles.blade.php` — Media picker modal CSS
- `partials/media-script.blade.php` — Media picker modal JS (upload, search, load-more, selection)

### View Composers & Service Provider Features

All `tyro-dashboard::*` and `dashboard.*` views receive:
- `$user` — Authenticated user
- `$dashboardRoute` — `DashboardRoute::class`

Sidebar views additionally receive:
- `$allResources` — Filtered resources based on user's role
- `$adminMenuItems`, `$commonMenuItems`, `$userMenuItems` — From `config/menu.php`

Additional Service Provider registrations:
- **Event Listeners**: `Login` → logs `user.login`, `Logout` → logs `user.logout` audit events
- **Named Route Resolution**: Fallback `resolveMissingNamedRoutesUsing` translates legacy routes via `DashboardRoute::translate()`
- **Blade Components**: Registers `<x-tyro-dashboard-media-picker>` (and typo alias `<x-tyro-dashbaord-media-picker>`)
- **Anonymous Components**: `resources/views/components` path registered under `tyro-dashboard` namespace (plus typo alias)
- **View Location**: `resources/views` added as a loadable path (enables `vendor.pagination.tyro` without namespace)

## Publishing Tags

| Tag | What It Publishes |
|-----|-------------------|
| `tyro-dashboard` | Config + all views |
| `tyro-dashboard-config` | `config/tyro-dashboard.php` |
| `tyro-dashboard-views` | All views |
| `tyro-dashboard-views-admin` | Admin layouts, partials, dashboard, users, roles, privileges |
| `tyro-dashboard-views-user` | User layouts, partials, dashboard, profile |
| `tyro-dashboard-styles` | `styles.blade.php` + `shadcn-theme.blade.php` |
| `tyro-dashboard-scripts` | `scripts.blade.php` |
| `tyro-dashboard-theme` | `shadcn-theme.blade.php` only |

## Testing

- Uses **Pest PHP** (`vendor/bin/pest`)
- Test namespace: `HasinHayder\TyroDashboard\Tests`
- Orchestra Testbench for package testing

## Code Style

- **Laravel Pint** config in `pint.json`
- No trailing commas in arrays (follow existing style)
- Use `self::SUCCESS` / `self::FAILURE` in commands
- Use `DashboardRoute::name()` for all route references
- Use `auditSafely()` wrapper for all audit logging to prevent failures from breaking UI

## Common Tasks for Agents

### Add a New Resource

1. Create model: `php artisan make:model Product --migration`
2. Add `HasCrud` trait and `$fillable`
3. Run migrations
4. Optionally add to `config/tyro-dashboard.php` `resources` array for fine-grained control
5. Access at `/dashboard/resources/products`

### Create a Custom Admin Page

```bash
php artisan tyro-dashboard:create-admin-page "Reports"
```

This creates:
- `resources/views/dashboard/reports.blade.php`
- Route in `routes/web.php`
- Sidebar link in `admin-sidebar.blade.php`

### Modify Sidebar Links

Publish views first:
```bash
php artisan tyro-dashboard:publish --admin
```

Then edit `resources/views/vendor/tyro-dashboard/partials/admin-sidebar.blade.php`.

### Configure System Settings

The system settings UI at `/dashboard/settings/system` lets admins manage `.env` values for:
- **Dashboard**: App name, logo, branding colors, sidebar colors, pagination, notifications, features, media API keys
- **Login/Auth**: Login methods, OAuth, 2FA, registration, password rules, session, throttle, email verification
- **RBAC**: Audit log settings, caching

Enable/disable with `TYRO_DASHBOARD_ENABLE_SYSTEM_SETTINGS=true` in `.env` and `system_settings` feature toggle in config.

### Add a New System Setting

See the [System Settings Management](#system-settings-management) section above for the complete step-by-step guide covering all six touchpoints (config, gatherSettings, validation, booleanKeys, defaultValues, and the blade view tab partial).

### Enable Profile Photos

1. Run migrations (adds `profile_photo_path` and `use_gravatar` columns)
2. Add `HasProfilePhoto` trait to User model
3. Set `TYRO_DASHBOARD_ENABLE_PROFILE_PHOTO=true` in `.env`
4. Run `php artisan storage:link`

### Use the Media Picker in a Form

```blade
{{-- Single image picker --}}
<x-tyro-dashboard-media-picker
    name="thumbnail"
    output="thumb"
    label="Thumbnail"
/>

{{-- Multiple image select --}}
<x-tyro-dashboard-media-picker
    name="gallery[]"
    output="select"
    multiple
    label="Gallery Images"
/>

{{-- With preview --}}
<x-tyro-dashboard-media-picker
    name="avatar"
    output="original"
    label="Avatar"
    :preview="true"
    preview_position="top"
    preview_width="150"
    preview_height="150"
    :circle="true"
/>
```

### Add Audit Logging to Custom Actions

```php
use HasinHayder\Tyro\Support\TyroAudit;

TyroAudit::log('custom.event', $model, $oldValues, $newValues);
```

Or use the safe wrapper pattern from controllers:
```php
protected function auditSafely(string $event, $auditable = null, ?array $oldValues = null, ?array $newValues = null): void {
    try {
        TyroAudit::log($event, $auditable, $oldValues, $newValues);
    } catch (\Throwable $e) {
        // Ignore
    }
}
```

## Security Considerations

- Always check `$this->isAdmin()` or use `tyro-dashboard.admin` middleware for admin features
- Use `DashboardRoute::name()` for redirects to avoid prefix mismatches
- Sanitize richtext with `Purifier` or `strip_tags` before display
- File uploads respect configured disk and directory
- Protected roles/users prevent accidental deletion of critical data
- Self-deletion and self-suspension are blocked
- Impersonation stores original admin ID in session; intercepts logout
- System settings page is admin-only and guarded by `features.system_settings` config and `tyro-dashboard.admin` middleware
- Media uploads respect configured disk and directory; file type validation is enforced

## System Settings Management

The system settings page at `/dashboard/settings/system` provides an admin UI for managing `.env` configuration values across Tyro Dashboard, Tyro (RBAC), and Tyro Login. All settings are written directly to `.env` and persisted via `config:clear`. This section documents the architecture and the step-by-step process for adding new settings.

### Architecture Overview

The system settings flow works as follows:

1. **On page load**, the controller's `gatherSettings()` method reads values from config (which reads from `.env`/defaults) and passes them to the view as `$settings['ENV_KEY']`.
2. **On form submit**, an AJAX POST is sent to the `update()` method which validates, writes to `.env`, and runs `config:clear`.
3. **Defaults-based pruning**: If a submitted value matches the `defaultValues()` entry for that key, the line is removed from `.env` entirely (so the config file's default takes effect).

### Six Touchpoints to Add a New Setting

Every new setting requires changes in exactly six locations:

| # | Location | Responsibility |
|---|----------|----------------|
| 1 | `config/tyro-dashboard.php` | Wire the env var to a config key with a fallback default |
| 2 | `SystemSettingsController::gatherSettings()` | Read the config value so the form is pre-populated |
| 3 | `SystemSettingsController::update()` validation array | Validate the incoming value |
| 4 | `SystemSettingsController::booleanKeys()` (if boolean) | Mark the key for true/false serialization |
| 5 | `SystemSettingsController::defaultValues()` | Define the canonical default (used for pruning) |
| 6 | `resources/views/settings/partials/_tab-*.blade.php` | Render the form field(s) in the appropriate tab |

#### Step 1 — Config File (`config/tyro-dashboard.php`)

Add an `env()` wrapper inside the appropriate config section. The second argument is the fallback default:

```php
// Inside an existing or new config section
'my_feature' => env('TYRO_DASHBOARD_MY_FEATURE', false),
```

The env key **must** use the `TYRO_DASHBOARD_`, `TYRO_`, or `TYRO_LOGIN_` prefix to avoid clashes. Convention:
- `TYRO_DASHBOARD_*` — dashboard-specific settings (branding, features, media keys)
- `TYRO_*` — RBAC/tyro core settings
- `TYRO_LOGIN_*` — authentication settings

#### Step 2 — `gatherSettings()`

Add a key-value pair to the array. The key is the **env variable name**, the value reads from the config path:

```php
'TYRO_DASHBOARD_MY_FEATURE' => config('tyro-dashboard.my_feature'),
```

#### Step 3 — `update()` Validation

Add a validation rule to the `$request->validate()` array. Follow existing patterns:

```php
// String/text field
'TYRO_DASHBOARD_MY_FEATURE' => 'nullable|string|max:255',

// Boolean toggle
'TYRO_DASHBOARD_MY_FEATURE' => 'nullable|boolean',

// Select from list
'TYRO_DASHBOARD_MY_FEATURE' => 'nullable|in:option_a,option_b,option_c',

// Integer with bounds
'TYRO_DASHBOARD_MY_FEATURE' => 'nullable|integer|min:0|max:100',
```

#### Step 4 — `booleanKeys()` (booleans only)

If the setting is a boolean toggle, add its env key to the `booleanKeys()` array. This ensures the value is serialized as `"true"`/`"false"` in `.env` (not `"1"`/`"0"` or `"on"`).

```php
protected function booleanKeys(): array {
    return [
        // ... existing keys ...
        'TYRO_DASHBOARD_MY_FEATURE',
    ];
}
```

#### Step 5 — `defaultValues()`

Add the canonical default so the controller can prune lines that match it:

```php
'TYRO_DASHBOARD_MY_FEATURE' => false,
```

When the submitted value equals this default, the `.env` line is removed — keeping the config file clean and ensuring the config file's default always applies.

#### Step 6 — Tab Partial Blade View

Create a new tab partial at `resources/views/settings/partials/_tab-{name}.blade.php` or add fields to an existing one. Three form field patterns are used:

**A) Text / number input:**
```blade
<div class="form-group">
    <label for="TYRO_DASHBOARD_MY_FEATURE" class="form-label">Label (TYRO_DASHBOARD_MY_FEATURE)</label>
    <input type="text" name="TYRO_DASHBOARD_MY_FEATURE" id="TYRO_DASHBOARD_MY_FEATURE"
           class="form-input" maxlength="255"
           value="{{ old('TYRO_DASHBOARD_MY_FEATURE', $settings['TYRO_DASHBOARD_MY_FEATURE']) }}">
    <p class="form-hint">Description of what this setting does.</p>
</div>
```

Use `type="password"` for API keys or secrets. Use `type="number"` with `min`/`max` for numeric fields.

**B) Boolean toggle:**
```blade
<div class="sys-settings-toggle">
    <div class="sys-settings-toggle-top">
        <div>
            <p class="sys-settings-toggle-title">Title <span style="font-weight:normal">(<code>TYRO_DASHBOARD_MY_FEATURE</code>)</span></p>
            <p class="sys-settings-toggle-description">Description of the toggle.</p>
        </div>
        <div>
            <input type="hidden" name="TYRO_DASHBOARD_MY_FEATURE" value="0">
            <label class="toggle-label">
                <input type="checkbox" name="TYRO_DASHBOARD_MY_FEATURE" value="1" class="toggle-input"
                       {{ old('TYRO_DASHBOARD_MY_FEATURE', $settings['TYRO_DASHBOARD_MY_FEATURE']) ? 'checked' : '' }}>
                <span class="toggle-slider"></span>
            </label>
        </div>
    </div>
</div>
```

The hidden input with `value="0"` ensures an unchecked checkbox still sends `0` (not nothing). The `toggle-input`/`toggle-slider` CSS classes render the shadcn-style switch.

**C) Select dropdown:**
```blade
<div class="form-group">
    <label for="TYRO_DASHBOARD_MY_FEATURE" class="form-label">Label (TYRO_DASHBOARD_MY_FEATURE)</label>
    <select name="TYRO_DASHBOARD_MY_FEATURE" id="TYRO_DASHBOARD_MY_FEATURE" class="form-select">
        <option value="option_a" {{ old('TYRO_DASHBOARD_MY_FEATURE', $settings['TYRO_DASHBOARD_MY_FEATURE']) === 'option_a' ? 'selected' : '' }}>Option A</option>
        <option value="option_b" {{ old('TYRO_DASHBOARD_MY_FEATURE', $settings['TYRO_DASHBOARD_MY_FEATURE']) === 'option_b' ? 'selected' : '' }}>Option B</option>
    </select>
</div>
```

### Registering a New Tab

If the setting belongs in a **new tab** (rather than an existing one), two additional files must be updated:

1. **`resources/views/settings/system.blade.php`** — Add a sidebar button and the partial include:
   ```blade
   {{-- Sidebar button (line ~36-78) --}}
   <button class="vtabs-item" data-vtab="my-tab" type="button">
       <svg viewBox="0 0 24 24" ...>{{-- 16px SVG icon --}}</svg>
       My Tab
   </button>

   {{-- Content include (line ~81-89) --}}
   @include('settings.partials._tab-my-tab')
   ```

2. **New partial file** — Create `resources/views/settings/partials/_tab-my-tab.blade.php` using the patterns above. Follow the existing wrapper structure:
   ```blade
   {{-- My Tab --}}
   <div class="vtabs-panel" id="vtab-my-tab">
       <div class="card">
           <div class="card-header" style="display:flex;align-items:center;justify-content:space-between;gap:1rem;">
               <h3 class="card-title">My Tab</h3>
               <button type="submit" form="systemSettingsForm" class="btn btn-primary btn-sm section-save-button">Save</button>
           </div>
           <div class="card-body">
               <div class="sys-settings-section-intro">
                   <div class="sys-settings-section-copy">
                       <h4 class="sys-settings-section-heading">Section heading</h4>
                       <p class="sys-settings-section-description">Description. All values are written to <code>.env</code>.</p>
                   </div>
                   <span class="sys-settings-section-badge">.env</span>
               </div>
               <div class="sys-settings-grid">
                   {{-- fields go here --}}
               </div>
           </div>
       </div>
   </div>
   ```

### Conditional Field Visibility

When a toggle controls whether other fields are relevant, wire it in `resources/views/settings/partials/_scripts.blade.php`:

1. Give the container element an `id` attribute.
2. Add a `{ toggle, target }` pair to the `pairs` array in the "Conditional Field Visibility" IIFE (line ~320-357):
   ```js
   { toggle: 'TYRO_DASHBOARD_MY_FEATURE', target: 'my-feature-details-surface' },
   ```
3. In the blade view, wrap the conditional fields in:
   ```blade
   <div id="my-feature-details-surface">
       {{-- fields that depend on the toggle being checked --}}
   </div>
   ```

### Existing Tab Reference

| Tab ID (`data-vtab`) | Partial File | JS Pairs Entry |
|---|---|---|
| `dashboard` | `_tab-dashboard.blade.php` | — |
| `login-auth` | `_tab-login-auth.blade.php` | — |
| `rbac` | `_tab-rbac.blade.php` | `tyro_audit_retention_group` (audit enabled) |
| `login-auth-advanced` | `_tab-login-auth-advanced.blade.php` | `otp-details-surface`, `twofa-details-surface`, `social-details-surface`, `lockout-details-surface`, `captcha-details-surface` |
| `rbac-advanced` | `_tab-rbac-advanced.blade.php` | — |
| `sidebar-colors` | `_tab-sidebar-colors.blade.php` | — |
| `admin-bar-colors` | `_tab-admin-bar-colors.blade.php` | — |
| `dashboard-colors` | `_tab-dashboard-colors.blade.php` | — |
| `media` | `_tab-media.blade.php` | — |

### Adding a Setting with a New Config Section

If the setting requires a **new config section** (e.g. `config('tyro-dashboard.my_section.setting')`), also update the `config/tyro-dashboard.php` file to add the full section:

```php
/*
|--------------------------------------------------------------------------
| My Section
|--------------------------------------------------------------------------
|
| Description of what this section controls.
|
*/
'my_section' => [
    'setting' => env('TYRO_DASHBOARD_MY_SECTION_SETTING', 'default_value'),
],
```

Then in `gatherSettings()`:
```php
'TYRO_DASHBOARD_MY_SECTION_SETTING' => config('tyro-dashboard.my_section.setting'),
```

### Important Files to Know

| File | Purpose |
|------|---------|
| `src/Providers/TyroDashboardServiceProvider.php` | Bootstraps routes, views, middleware, commands, view composers, event listeners |
| `src/Http/Controllers/BaseController.php` | Common controller logic (isAdmin, getUserModel, getViewData) |
| `src/Http/Controllers/MediaController.php` | Media library with upload, crop, resize, external imports |
| `src/Http/Controllers/SystemSettingsController.php` | .env management UI for Dashboard, RBAC, and Login configs |
| `src/Support/DashboardRoute.php` | Route name builder with prefix awareness |
| `src/Support/DashboardColors.php` | Light/dark mode CSS variable overrides storage |
| `src/Concerns/HasCrud.php` | Auto-CRUD trait for models |
| `src/Models/Media.php` | Media library Eloquent model |
| `src/View/Components/MediaPicker.php` | Media picker Blade component class |
| `config/tyro-dashboard.php` | All package configuration |
| `routes/web.php` | Package route definitions |
| `resources/views/layouts/admin.blade.php` | Admin layout |
| `resources/views/layouts/user.blade.php` | User layout |
| `resources/views/media/index.blade.php` | Full media library page (grid/list, upload, crop/resize) |
| `resources/views/settings/system.blade.php` | System settings with tabbed env management |
| `resources/views/components/media-picker.blade.php` | Media picker anonymous Blade component |
| `resources/views/partials/flash-messages.blade.php` | Notification system (legacy + toast) |
| `resources/views/partials/styles.blade.php` | All dashboard styles |
| `resources/views/partials/scripts.blade.php` | All dashboard scripts |
| `resources/views/partials/media-styles.blade.php` | Media picker modal CSS |
| `resources/views/partials/media-script.blade.php` | Media picker modal JavaScript |
| `resources/views/pagination/tyro.blade.php` | Custom pagination view |
