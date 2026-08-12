<div align="center">

# Tyro Dashboard

### Build Powerful Admin Panels in Minutes, Not Weeks

[![Packagist](https://img.shields.io/packagist/v/hasinhayder/tyro-dashboard?style=for-the-badge&logo=packagist&logoColor=white&label=Packagist)](https://packagist.org/packages/hasinhayder/tyro-dashboard) [![Laravel](https://img.shields.io/badge/Laravel-12.x-FF2D20?style=for-the-badge&logo=laravel)](https://laravel.com) [![Laravel](https://img.shields.io/badge/Laravel-13.x-FF2D20?style=for-the-badge&logo=laravel)](https://laravel.com) [![PHP](https://img.shields.io/badge/PHP-8.2+-777BB4?style=for-the-badge&logo=php)](https://php.net) [![License](https://img.shields.io/badge/License-MIT-green?style=for-the-badge)](LICENSE) [![CLI Ready](https://img.shields.io/badge/CLI-Ready-2EA44F?style=for-the-badge&logo=terminal&logoColor=white)](https://github.com/hasinhayder/tyro-dashboard)

**Stop building the same admin dashboard over and over.**

A production-ready Laravel package that delivers a complete admin & user dashboard with RBAC, user management, and **magical dynamic CRUD**, all configured through a single file.

[Full Documentation](http://hasinhayder.github.io/tyro-dashboard/doc.html) • [GitHub](https://github.com/hasinhayder/tyro-dashboard)

</div>

---

**Tyro Dashboard** is a comprehensive admin panel package for Laravel 12 and 13, built on top of [Tyro](https://github.com/hasinhayder/tyro) (RBAC) and [Tyro Login](https://github.com/hasinhayder/tyro-login) (authentication). It gives you user management, role & privilege administration, separate admin/user dashboards, audit trails, and dynamic CRUD for your own models, all with a beautiful shadcn-based UI.

What would take 40-60 hours of development now takes minutes of configuration.

## Features

- **User management**: full CRUD, search, suspension, 2FA, role assignment, impersonation
- **RBAC & privileges**: visual role and privilege management with protected roles
- **Dynamic resource CRUD**: describe a model, get a complete admin interface
- **Separate dashboards**: distinct admin and user experiences out of the box
- **Audit trail**: searchable logs of all admin activities and resource changes
- **Admin bar**: global maintenance and announcement notices in seconds
- **Invitation system**: referral links with automatic signup tracking
- **Profile photos**: custom uploads or Gravatar
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

## Configuration

Publish and customize everything:

```bash
php artisan tyro-dashboard:publish --config
```

Key options in `config/tyro-dashboard.php`:

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
