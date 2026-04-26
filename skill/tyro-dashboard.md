# Tyro Dashboard - AI Agent Skill

## Overview

Tyro Dashboard is a comprehensive Laravel package (`hasinhayder/tyro-dashboard`) that provides a complete admin & user dashboard with RBAC, user management, and dynamic CRUD interfaces. It is built on top of **Tyro** (RBAC framework) and **Tyro Login** (authentication system).

- **Package**: `hasinhayder/tyro-dashboard`
- **Version**: `1.20.0`
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
    Controllers/             # Dashboard, User, Role, Privilege, Resource, etc.
    Middleware/              # EnsureIsAdmin, HandleImpersonation
  Providers/
    TyroDashboardServiceProvider.php
  Services/
    AdminNotice.php          # Runtime admin bar notices
  Support/
    DashboardRoute.php       # Route name helper with prefix translation
  Traits/
    HasProfilePhoto.php      # Profile photo upload & Gravatar support

resources/views/
  layouts/                   # admin.blade.php, user.blade.php, app.blade.php
  partials/                  # sidebars, styles, scripts, flash-messages, etc.
  dashboard/                 # admin.blade.php, user.blade.php, index.blade.php
  users/                     # CRUD views
  roles/                     # CRUD views
  privileges/                # CRUD views
  resources/                 # Dynamic CRUD views (index, create, edit, show)
  profile/                   # Profile management views
  invitations/               # Invitation system views
  audits/                    # Audit log views
  examples/                  # Widgets & components demos
  errors/                    # Missing tables, maintenance pages

config/tyro-dashboard.php    # Main configuration file
routes/web.php               # Package routes
```

## Dependencies

- `hasinhayder/tyro` (^1.5) — RBAC framework (Role, Privilege, AuditLog models)
- `hasinhayder/tyro-login` (^2.4) — Authentication & invitation system
- Optional: `mews/purifier` — HTML sanitization for richtext fields

## Console Commands

All commands use the `tyro-dashboard:` namespace.

### Installation & Setup

| Command | Description |
|---------|-------------|
| `tyro-dashboard:install [--force]` | Interactive installer. Checks deps, publishes config/views, creates super user |
| `tyro-dashboard:createsuperuser` | Create a superuser with admin role interactively |
| `tyro-dashboard:version` | Display version, Laravel/PHP info, dependency status |
| `tyro-dashboard:setup-ai-skill` | Install the AI skill file for Claude, Copilot, Codex, Gemini, or Kilo |

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
- **`branding`** — `app_name`, `logo`, `sidebar_bg`, `sidebar_text`, `sidebar_primary`, `sidebar_accent`, etc.
- **`admin_bar`** — Enable global notices with colors/alignment
- **`features`** — Toggle: `user_management`, `role_management`, `privilege_management`, `settings_management`, `profile_management`, `invitation_system`, `audit_logs`, `profile_photo_upload`, `gravatar`
- **`protected`** — Role slugs and user IDs that cannot be deleted
- **`widgets`** — Dashboard widget toggles
- **`notifications`** — `show_flash_messages`, `auto_dismiss_seconds`, `notification_style` (`legacy` or `toast`), `toast_position`
- **`uploads`** — Default disk (`public`), directory, auto-delete behavior
- **`profile_photo`** — Disk, directory, max size, dimensions, quality, crop position, allowed types
- **`resources`** — Array of dynamic CRUD resource definitions
- **`resource_ui`** — `show_global_errors`, `show_field_errors`
- **`disable_examples`** — Hide example routes and sidebar sections

## Routes (`routes/web.php`)

All routes are grouped under the configured prefix (default `/dashboard`) with `web` and `auth` middleware. Route names use the `tyro-dashboard.` prefix by default (configurable via `routes.name_prefix`).

### Route Groups

1. **Dashboard Home** — `GET /`
2. **Examples** (disabled in production or via `disable_examples`)
   - `/components`, `/examples/components`
   - `/widgets`, `/examples/widgets`
   - `/x-components` (if `TyroDashboardComponentsServiceProvider` exists)
   - Widget proxies: `/examples/widgets/xkcd/{id}`, `/examples/widgets/stocks/{symbol}`, `/examples/widgets/fx/{base}`, `/examples/widgets/flights`
3. **Profile** (`prefix: profile`)
   - `GET /` — View profile
   - `PUT /update` — Update profile
   - `PUT /password` — Change password
   - `DELETE /photo` — Delete own photo
   - `DELETE /2fa/reset` — Reset 2FA
4. **Invitations** (if enabled)
   - `GET /invitations` — User invitation panel
   - `POST /invitations/create` — Create own invitation link
5. **Leave Impersonation** — `POST /leave-impersonation`
6. **Admin Routes** (`middleware: tyro-dashboard.admin`)
   - **Users**: `/users` — CRUD, suspend, unsuspend, login-as, reset 2FA, delete photo
   - **Roles**: `/roles` — CRUD, remove user from role
   - **Privileges**: `/privileges` — CRUD, remove role from privilege
   - **Invitations Admin**: `/invitations/admin` — Manage invitation links
   - **Audits**: `/audits` — View, export CSV, bulk delete, flush
7. **Dynamic Resources** (`prefix: resources/{resource}`)
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
- `components(Request)` — Demo page with KPIs, charts, progress, info cards, activity (for copy-paste)

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

### View Composers (in ServiceProvider)

All `tyro-dashboard::*` and `dashboard.*` views receive:
- `$user` — Authenticated user
- `$dashboardRoute` — `DashboardRoute::class`

Sidebar views additionally receive:
- `$allResources` — Filtered resources based on user's role
- `$adminMenuItems`, `$commonMenuItems`, `$userMenuItems` — From `config/menu.php`

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

### Enable Profile Photos

1. Run migrations (adds `profile_photo_path` and `use_gravatar` columns)
2. Add `HasProfilePhoto` trait to User model
3. Set `TYRO_DASHBOARD_ENABLE_PROFILE_PHOTO=true` in `.env`
4. Run `php artisan storage:link`

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

## Important Files to Know

| File | Purpose |
|------|---------|
| `src/Providers/TyroDashboardServiceProvider.php` | Bootstraps routes, views, middleware, commands, view composers, event listeners |
| `src/Http/Controllers/BaseController.php` | Common controller logic (isAdmin, getUserModel, getViewData) |
| `src/Support/DashboardRoute.php` | Route name builder with prefix awareness |
| `src/Concerns/HasCrud.php` | Auto-CRUD trait for models |
| `config/tyro-dashboard.php` | All package configuration |
| `routes/web.php` | Package route definitions |
| `resources/views/layouts/admin.blade.php` | Admin layout |
| `resources/views/layouts/user.blade.php` | User layout |
| `resources/views/partials/flash-messages.blade.php` | Notification system (legacy + toast) |
| `resources/views/partials/styles.blade.php` | All dashboard styles |
| `resources/views/partials/scripts.blade.php` | All dashboard scripts |
