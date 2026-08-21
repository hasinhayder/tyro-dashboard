<div align="center">

# Tyro Dashboard

### Build Powerful Admin Panels in Minutes, Not Weeks

[![Packagist](https://img.shields.io/packagist/v/hasinhayder/tyro-dashboard?style=for-the-badge&logo=packagist&logoColor=white&label=Packagist)](https://packagist.org/packages/hasinhayder/tyro-dashboard) [![Laravel](https://img.shields.io/badge/Laravel-12.x-FF2D20?style=for-the-badge&logo=laravel)](https://laravel.com) [![Laravel](https://img.shields.io/badge/Laravel-13.x-FF2D20?style=for-the-badge&logo=laravel)](https://laravel.com) [![PHP](https://img.shields.io/badge/PHP-8.2+-777BB4?style=for-the-badge&logo=php)](https://php.net) [![License](https://img.shields.io/badge/License-MIT-green?style=for-the-badge)](LICENSE) [![CLI Ready](https://img.shields.io/badge/CLI-Ready-2EA44F?style=for-the-badge&logo=terminal&logoColor=white)](https://github.com/hasinhayder/tyro-dashboard)

**Stop building the same admin dashboard over and over.**

A production-ready Laravel package that delivers a complete admin & user dashboard with RBAC, user management, and **magical dynamic CRUD**, all configured through a single file.

[Full Documentation](http://hasinhayder.github.io/tyro-dashboard/doc.html) • [GitHub](https://github.com/hasinhayder/tyro-dashboard)

</div>

---

**Tyro Dashboard** is a comprehensive admin panel package for Laravel 12 and 13, built on top of [Tyro](https://github.com/hasinhayder/tyro) (RBAC) and [Tyro Login](https://github.com/hasinhayder/tyro-login) (authentication). It gives you user management, role & privilege administration, separate admin/user dashboards, audit trails, dynamic CRUD for your own models, and a full media library — all with a beautiful shadcn-based UI.

What would take 40-60 hours of development now takes minutes of configuration.

## Features

- **User management**: full CRUD, search, suspension, 2FA, passkeys, role assignment
- **Impersonation**: log in as any user to troubleshoot and verify features
- **RBAC & privileges**: visual role and privilege management with protected roles
- **Dynamic resource CRUD**: describe a model, get a complete admin interface
- **Separate dashboards**: distinct admin and user experiences out of the box
- **Audit trail**: searchable logs of all admin activities and resource changes
- **Admin bar**: global maintenance and announcement notices in seconds
- **Invitation system**: referral links with automatic signup tracking
- **Profile photos**: custom uploads or Gravatar
- **Media library**: full media management, uploads, WebP conversion, thumbnails, stock photo search, and a reusable media picker
- **System health**: read-only runtime diagnostics — PHP memory, OPcache, disk, database, cache, and queue status
- **Beautiful UI**: modern, responsive, shadcn components, dark/light themes
- **Security first**: middleware checks, per-resource access, protected resources

## Requirements

- PHP 8.2+
- Laravel 12 or 13
- [Tyro](https://github.com/hasinhayder/tyro) package
- [Tyro Login](https://github.com/hasinhayder/tyro-login) package

## Installation

Get up and running in under 3 minutes:

```bash
composer require hasinhayder/tyro-dashboard
php artisan tyro-dashboard:install
```

Then visit `/dashboard` in your browser. `tyro-dashboard:install` publishes the config, views, routes, and middleware for you.

> **Note:** if you're updating to a version with the invitation system, run `php artisan migrate` to create the `invitation_links` and `invitation_referrals` tables.

## Dynamic CRUD in 30 Seconds

Add the `HasCrud` trait to your model:

```php
use HasinHayder\TyroDashboard\Concerns\HasCrud;

class Product extends Model
{
    use HasCrud;
}
```

Then describe the resource in `config/tyro-dashboard.php`:

```php
'resources' => [
    'products' => [
        'model' => App\Models\Product::class,
        'roles' => ['admin', 'manager'],
        'fields' => [
            'name' => ['type' => 'text', 'required' => true, 'searchable' => true],
            'price' => ['type' => 'number', 'required' => true, 'sortable' => true],
            'category_id' => ['type' => 'select', 'relationship' => 'category'],
            'image' => ['type' => 'file'],
            'is_active' => ['type' => 'checkbox', 'default' => true],
        ],
    ],
]
```

Visit `/dashboard/resources/products` and you have a live admin interface with list views, pagination, search, sortable columns, create/edit forms with validation, file uploads, relationships, and role-based access.

**No controllers. No views. No routes. No validation logic. Just configuration.**

## CLI at a glance

### Install & Setup

| Command | Description |
| --- | --- |
| `tyro-dashboard:install` | Install package resources (config, views, routes, middleware) |
| `tyro-dashboard:publish` | Publish views, config, and styles (with per-area options) |
| `tyro-dashboard:publish-style` | Publish styles to customize shadcn variables |
| `tyro-dashboard:update` | Update published resources and sidebar overrides |
| `tyro-dashboard:update-config` | Update config with the latest version |
| `tyro-dashboard:update-style` | Update published styles with the latest version |
| `tyro-dashboard:update-script` | Update published scripts with the latest version |
| `tyro-dashboard:setup-ai-skill` | Install the Tyro Dashboard AI skill for your agent |

### Pages & Resources

| Command | Description |
| --- | --- |
| `tyro-dashboard:create-admin-page` | Create a new admin dashboard page |
| `tyro-dashboard:create-user-page` | Create a new user dashboard page |
| `tyro-dashboard:create-common-page` | Create a page visible in both user and admin sidebars |
| `tyro-dashboard:remove-admin-page` | Remove an admin dashboard page |
| `tyro-dashboard:remove-user-page` | Remove a user dashboard page |
| `tyro-dashboard:remove-common-page` | Remove a common dashboard page |
| `tyro-dashboard:make-resource` | Scaffold a new resource (model, migration, controller) |
| `tyro-dashboard:clear-cache` | Clear cached field configurations for Dynamic CRUD |

### Users & Info

| Command | Description |
| --- | --- |
| `tyro-dashboard:createsuperuser` | Create a superuser with admin privileges |
| `tyro-dashboard:version` | Display the current version |

Run `php artisan list tyro-dashboard` to see every available command.

## UI Components

Tyro Dashboard ships a library of shadcn-styled Blade components you can drop into any page immediately — no build step required. Use them with the `tyro-dashboard::` namespace, e.g. `<x-tyro-dashboard::card>`.

| Component | Description |
| --- | --- |
| `<x-tyro-dashboard::alert>` | Contextual alert with icon; `variant` = `info`, `success`, `warning`, `error` |
| `<x-tyro-dashboard::avatar>` | User avatar with photo/Gravatar fallback to initials; `size`, `user` |
| `<x-tyro-dashboard::badge>` | Status badge; `variant` = `primary`, `success`, `warning`, `danger`, `secondary`, `info` |
| `<x-tyro-dashboard::card>` | Card panel with `title`, `description`, `actions` and `footer` slots |
| `<x-tyro-dashboard::checkbox>` | Styled checkbox with label and color variants; supports `indeterminate` |
| `<x-tyro-dashboard::data-table>` | Table from a collection + columns with formatting, striped/hover/compact variants; customizable empty state via `empty` and `emptyTitle` |
| `<x-tyro-dashboard::dropdown>` | Dropdown menu with `trigger` slot, alignment, and items |
| `<x-tyro-dashboard::dropdown-item>` | Dropdown item; `href`, `icon`, `variant` (incl. `danger`) |
| `<x-tyro-dashboard::dropdown-divider>` | Divider for dropdown menus |
| `<x-tyro-dashboard::page-header>` | Page title + description with an `actions` slot |
| `<x-tyro-dashboard::progress>` | Progress bar with label and percent; `variant`, `height` |
| `<x-tyro-dashboard::select>` | Select with searchable multi-select mode, placeholder, hint/error states |
| `<x-tyro-dashboard::stat>` | Stat card with icon, value, label, change and trend (up/down) |
| `<x-tyro-dashboard::toggle>` | Toggle switch with label and color variants |
| `<x-tyro-dashboard::media>` | Renders media with WebP/thumbnail fallback, sizing, rounded/circle, lazy loading |
| `<x-tyro-dashboard-media-picker>` | Media library picker for any form field (see Media Library) |

Example:

```blade
<x-tyro-dashboard::card title="Monthly Report" description="Q3 performance">
    <x-slot:actions>
        <a href="#" class="btn btn-primary btn-sm">Export</a>
    </x-slot:actions>

    <div class="grid grid-cols-3 gap-4">
        <x-tyro-dashboard::stat label="Revenue" value="$42,500" trend="up" change="+12%" />
        <x-tyro-dashboard::stat label="Orders" value="1,204" variant="info" />
        <x-tyro-dashboard::stat label="Refunds" value="38" variant="danger" trend="down" change="-3%" />
    </div>
</x-tyro-dashboard::card>
```

## Configuration

Publish and customize everything:

```bash
php artisan tyro-dashboard:publish --config
```

Key options in `config/tyro-dashboard.php`:

> Most settings are driven by env vars (table below). Some are plain config arrays — e.g. `pagination` (users/roles/privileges/resources per page) and `protected` (roles and user IDs that cannot be deleted) — customize those directly in the published config file.

| Env var | Default | Description |
| --- | --- | --- |
| `TYRO_DASHBOARD_PREFIX` | `dashboard` | URL prefix for the dashboard |
| `TYRO_DASHBOARD_USER_MODEL` | `App\Models\User` | User model the dashboard operates on |
| `TYRO_DASHBOARD_ENABLE_INVITATION` | `true` | Enable the invitation/referral system |
| `TYRO_DASHBOARD_ENABLE_AUDIT_LOGS` | `true` | Enable audit logging |
| `TYRO_DASHBOARD_ENABLE_SYSTEM_SETTINGS` | `true` | Enable system settings page |
| `TYRO_DASHBOARD_ENABLE_CHECKPOINTS` | `true` | Enable checkpoint feature |
| `TYRO_DASHBOARD_SHOW_ROLES_MENU` | `true` | Show the roles menu item |
| `TYRO_DASHBOARD_SHOW_PRIVILEGES_MENU` | `true` | Show the privileges menu item |
| `TYRO_DASHBOARD_SHOW_RESOURCES_MENU` | `true` | Show the resources menu item |
| `TYRO_DASHBOARD_COLLAPSIBLE_SIDEBAR` | `true` | Allow the sidebar to collapse |
| `TYRO_DASHBOARD_ADMIN_BAR_ENABLED` | `false` | Show the global admin notice bar |
| `TYRO_DASHBOARD_ADMIN_BAR_MESSAGE` | `''` | Admin bar notice text |
| `TYRO_DASHBOARD_ADMIN_BAR_BG_COLOR` | `#000000` | Admin bar background color |
| `TYRO_DASHBOARD_ADMIN_BAR_TEXT_COLOR` | `#ffffff` | Admin bar text color |
| `TYRO_DASHBOARD_ADMIN_BAR_ALIGN` | `left` | Admin bar text alignment |
| `TYRO_DASHBOARD_ADMIN_BAR_HEIGHT` | `40px` | Admin bar height |
| `TYRO_DASHBOARD_APP_NAME` | `Laravel` | App name shown in the dashboard |
| `TYRO_DASHBOARD_LOGO` | `null` | Logo URL |
| `TYRO_DASHBOARD_LOGO_HEIGHT` | `32px` | Logo display height |
| `TYRO_DASHBOARD_FAVICON` | `null` | Favicon URL |
| `TYRO_DASHBOARD_SIDEBAR_BG` | `null` | Sidebar background color |
| `TYRO_DASHBOARD_SIDEBAR_TEXT` | `null` | Sidebar text color |
| `TYRO_DASHBOARD_SIDEBAR_PRIMARY` | `null` | Sidebar primary color |
| `TYRO_DASHBOARD_SIDEBAR_ACCENT` | `null` | Sidebar accent color |
| `TYRO_DASHBOARD_SIDEBAR_ACCENT_FOREGROUND` | `null` | Sidebar accent text color |
| `TYRO_DASHBOARD_SIDEBAR_HEADER_BORDER` | `null` | Sidebar header border color |
| `TYRO_DASHBOARD_SIDEBAR_ACCORDION_COMPACT` | `false` | Compact sidebar accordion sections |
| `TYRO_DASHBOARD_SIDEBAR_ACCORDION_OPEN_SECTIONS` | `1` | Number of accordion sections open by default |
| `TYRO_DASHBOARD_SIDEBAR_LOGO` | `null` | Sidebar logo URL (falls back to `TYRO_DASHBOARD_LOGO` behavior) |
| `TYRO_DASHBOARD_UPLOAD_DISK` | `public` | Storage disk for resource uploads |
| `TYRO_DASHBOARD_UPLOAD_DIRECTORY` | `uploads` | Storage directory for resource uploads |
| `TYRO_DASHBOARD_AUTO_DELETE_UPLOADS` | `true` | Delete files when a resource is deleted |
| `TYRO_DASHBOARD_ENABLE_PROFILE_PHOTO` | `false` | Enable profile photo uploads |
| `TYRO_DASHBOARD_ENABLE_GRAVATAR` | `false` | Use Gravatar for profile photos |
| `TYRO_DASHBOARD_PROFILE_PHOTO_DISK` | `public` | Storage disk for profile photos |
| `TYRO_DASHBOARD_PROFILE_PHOTO_DIRECTORY` | `profile_images` | Profile photo directory |
| `TYRO_DASHBOARD_PROFILE_PHOTO_MAX_SIZE` | `10240` | Profile photo max size (KB) |
| `TYRO_DASHBOARD_PROFILE_PHOTO_WIDTH` | `400` | Profile photo width (px) |
| `TYRO_DASHBOARD_PROFILE_PHOTO_HEIGHT` | `400` | Profile photo height (px) |
| `TYRO_DASHBOARD_PROFILE_PHOTO_QUALITY` | `90` | Profile photo JPEG quality |
| `TYRO_DASHBOARD_PROFILE_PHOTO_CROP` | `center` | Profile photo crop position: `top`, `center`, or `bottom` |
| `TYRO_DASHBOARD_NOTIFICATION_STYLE` | `legacy` | Notification style: `legacy` or `toast` |
| `TYRO_DASHBOARD_TOAST_POSITION` | `bottom-right` | Toast position: `top-right` or `bottom-right` |
| `TYRO_DASHBOARD_DISABLE_EXAMPLES` | `false` | Disable example resources/pages |
| `TYRO_DASHBOARD_MEDIA_MAX_SIZE` | `10240` | Media library max upload size (KB) |
| `TYRO_DASHBOARD_FREEPIK_KEY` | `null` | FreePik API key for media library |
| `TYRO_DASHBOARD_PEXELS_KEY` | `null` | Pexels API key for media library |
| `TYRO_DASHBOARD_UNSPLASH_ACCESS_KEY` | `null` | Unsplash API key for media library |
| `TYRO_DASHBOARD_PIXABAY_KEY` | `null` | Pixabay API key for media library |
| `TYRO_SHOW_GLOBAL_ERRORS` | `true` | Show global form errors |
| `TYRO_SHOW_FIELD_ERRORS` | `true` | Show per-field form errors |

### Updating the Config

When you upgrade Tyro Dashboard, refresh your published config to pick up new keys and defaults:

```bash
php artisan tyro-dashboard:update-config
```

This force-publishes the latest `config/tyro-dashboard.php` and also refreshes the Tyro and Tyro Login configs. **It overwrites your published config**, so pass `--with-backup` to keep a timestamped copy first:

```bash
php artisan tyro-dashboard:update-config --with-backup
```

The backup is saved as `config/tyro-dashboard-backup-YYYY-MM-DD-HHMMSS.txt`. To update everything at once (styles, scripts, config, and published sidebar/flash-message overrides), run the full update command instead:

```bash
php artisan tyro-dashboard:update
```

## Impersonation

Admins can temporarily log in as any user from the user management interface, without affecting the user's session. Perfect for troubleshooting, customer support, and feature verification. Only admins can impersonate, and impersonation respects existing security controls (2FA, email verification, etc.).

## Admin Bar & Global Notices

Show globally visible announcements at the top of all dashboard layouts:

```env
TYRO_DASHBOARD_ADMIN_BAR_ENABLED=true
TYRO_DASHBOARD_ADMIN_BAR_MESSAGE="System maintenance this Sunday at 10:00 PM."
TYRO_DASHBOARD_ADMIN_BAR_BG_COLOR="#ffcc00"
```

Or programmatically:

```php
use HasinHayder\TyroDashboard\Services\AdminNotice;

AdminNotice::show('Sale ends in 24 hours! <b>Don\'t miss out!</b>');
AdminNotice::show('Server SSD capacity is critically low (< 5%).', '#dc2626', '#ffffff');
```

## Audit Trail

Every role/privilege change, user lifecycle event, and CRUD operation on tracked resources is logged with who, what, and when. Browse, search, and filter logs from the **Audit Logs** page in the admin dashboard (admin only).

## Profile Photos

```bash
php artisan migrate
php artisan storage:link
```

Add the `HasProfilePhoto` trait to your User model, then enable uploads or Gravatar in `.env` (`TYRO_DASHBOARD_ENABLE_PROFILE_PHOTO=true` / `TYRO_DASHBOARD_ENABLE_GRAVATAR=true`).

## Media Library

Full media management from day one — every authenticated user gets a media library at `/dashboard/media` where they can upload, search, crop, resize, rename, and organize files.

```bash
php artisan migrate
php artisan storage:link
```

### What you get

- **Uploads with automatic processing**: every image gets a WebP variant and a 600px thumbnail (Intervention Image v3, GD or Imagick)
- **Media picker**: a reusable `<x-tyro-dashboard-media-picker>` Blade component that works in any form — pick from the library or upload right from the field, with output options for original, WebP, or thumbnail
- **Media display**: `<x-tyro-dashboard-media>` renders any media record with smart variant (WebP/thumbnail) fallback, sizing, rounding, and lazy loading
- **`HasMedia` trait**: add to your `User` model to get `media()`, `mediaLibrary()`, `mediaUrl()`, `deleteMedia()` and more — programmatic access to the library
- **Crop & resize**: visual Cropper.js selection with replace-in-place or create-a-new-file modes
- **Stock photo search**: search and import from Unsplash, Pixabay, Freepik, and Pexels directly into your library (set your API keys in `.env`)
- **Starred images**: save favorites from stock providers for quick access
- **Bulk operations**: select and delete multiple files, plus per-file alt text and rename support
- **Sensible access control**: all authenticated users can use the library; admins and editors can manage any file, regular users only their own

### Configuration

| Env var | Default | Description |
| --- | --- | --- |
| `TYRO_DASHBOARD_UPLOAD_DISK` | `public` | Storage disk for uploads |
| `TYRO_DASHBOARD_UPLOAD_DIRECTORY` | `uploads` | Storage directory for uploads |
| `TYRO_DASHBOARD_MEDIA_MAX_SIZE` | `10240` | Max upload size (KB) |
| `TYRO_DASHBOARD_AUTO_DELETE_UPLOADS` | `true` | Delete files when a resource is deleted |
| `TYRO_DASHBOARD_UNSPLASH_ACCESS_KEY` | `null` | Unsplash API key for stock photo search |
| `TYRO_DASHBOARD_PIXABAY_KEY` | `null` | Pixabay API key for stock photo search |
| `TYRO_DASHBOARD_FREEPIK_KEY` | `null` | Freepik API key for stock photo search |
| `TYRO_DASHBOARD_PEXELS_KEY` | `null` | Pexels API key for stock photo search |

## Use Cases

- **E-commerce**: resources for Products, Categories, Orders with role-based access
- **Enterprise user management**: roles and privileges for HR, managers, employees
- **SaaS multi-tenant**: tenant-specific resources with custom role assignments
- **CMS**: Posts, Authors, Categories with relationship fields

## Full Documentation

For detailed configuration, all field types, customization guides, and best practices:

**[View Complete Documentation](http://hasinhayder.github.io/tyro-dashboard/doc.html)**

## License

The Tyro Dashboard package is open-source software licensed under the [MIT license](LICENSE).

## Acknowledgments

Built on top of amazing packages:

- [Tyro](https://github.com/hasinhayder/tyro): RBAC framework
- [Tyro Login](https://github.com/hasinhayder/tyro-login): authentication system

---

## Ready to Supercharge Your Laravel App?

```
composer require hasinhayder/tyro-dashboard
php artisan tyro-dashboard:install
open http://localhost:8000/dashboard
```

<div align="center">

**Made with love by [Hasin Hayder](https://github.com/hasinhayder)**

[GitHub](https://github.com/hasinhayder/tyro-dashboard) • [Documentation](http://hasinhayder.github.io/tyro-dashboard/doc.html) • [Issues](https://github.com/hasinhayder/tyro-dashboard/issues) • [Discussions](https://github.com/hasinhayder/tyro-dashboard/discussions)

</div>
