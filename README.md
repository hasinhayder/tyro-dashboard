# Tyro Dashboard

<p align="center">
<img src="https://img.shields.io/packagist/v/hasinhayder/tyro-dashboard.svg?style=flat-square" alt="Latest Version on Packagist">
<img src="https://img.shields.io/packagist/php-v/hasinhayder/tyro-dashboard.svg?style=flat-square" alt="PHP Version">
<img src="https://img.shields.io/packagist/l/hasinhayder/tyro-dashboard.svg?style=flat-square" alt="License">
</p>

Beautiful, modern admin dashboard for managing Tyro roles, privileges, users, and package settings in Laravel 12.

## ✨ Features

- 🎨 **Beautiful UI** - Modern, responsive design that matches Tyro Login aesthetics
- 👥 **User Management** - Create, edit, suspend, and manage users
- 🔐 **Role Management** - Full CRUD operations for roles
- 🛡️ **Privilege Management** - Manage privileges and assign them to roles
- ⚙️ **Settings Management** - Configure Tyro and Tyro Login packages from UI
- 👤 **Profile Management** - Users can update their own profile
- 🌙 **Dark Mode** - Built-in dark/light theme support
- 📱 **Responsive** - Works perfectly on all device sizes

## 📦 Requirements

- PHP 8.2+
- Laravel 12.x
- [hasinhayder/tyro](https://github.com/hasinhayder/tyro) ^1.0
- [hasinhayder/tyro-login](https://github.com/hasinhayder/tyro-login) ^1.0 (optional but recommended)

## 🚀 Installation

Install the package via Composer:

```bash
composer require hasinhayder/tyro-dashboard
```

Run the interactive installer:

```bash
php artisan tyro-dashboard:install
```

The installer will:
- Check dependencies
- Publish the configuration file
- Optionally publish views for customization
- Help you configure admin roles and branding

### Manual Installation

If you prefer manual setup:

```bash
# Publish configuration
php artisan vendor:publish --tag=tyro-dashboard-config

# Optionally publish views
php artisan vendor:publish --tag=tyro-dashboard-views
```

## ⚙️ Configuration

After publishing, you can modify the configuration in `config/tyro-dashboard.php`:

```php
return [
    // Route settings
    'routes' => [
        'prefix' => env('TYRO_DASHBOARD_PREFIX', 'dashboard'),
        'middleware' => ['web', 'auth'],
        'name_prefix' => 'tyro-dashboard.',
    ],

    // Users with these roles have full admin access
    'admin_roles' => ['admin', 'super-admin'],

    // The user model to use
    'user_model' => 'App\\Models\\User',

    // Pagination settings
    'pagination' => [
        'users' => 15,
        'roles' => 15,
        'privileges' => 15,
    ],

    // Branding
    'branding' => [
        'app_name' => env('TYRO_DASHBOARD_APP_NAME', env('APP_NAME', 'Laravel')),
        'logo' => env('TYRO_DASHBOARD_LOGO', null),
    ],
];
```

## 🔗 Routes

The dashboard provides the following routes:

| Route | Description | Access |
|-------|-------------|--------|
| `/dashboard` | Main dashboard | All authenticated users |
| `/dashboard/profile` | User profile settings | All authenticated users |
| `/dashboard/users` | User management | Admin only |
| `/dashboard/users/create` | Create new user | Admin only |
| `/dashboard/users/{id}/edit` | Edit user | Admin only |
| `/dashboard/roles` | Role management | Admin only |
| `/dashboard/roles/create` | Create new role | Admin only |
| `/dashboard/roles/{id}` | View role details | Admin only |
| `/dashboard/privileges` | Privilege management | Admin only |
| `/dashboard/privileges/create` | Create new privilege | Admin only |
| `/dashboard/settings/tyro` | Tyro package settings | Admin only |
| `/dashboard/settings/tyro-login` | Tyro Login settings | Admin only |

## 🛡️ Access Control

- **Admin/Super-Admin**: Full access to all dashboard features including user management, role/privilege management, and package settings
- **Regular Users**: Access to dashboard home and profile management only

## 🎨 Customization

### Views

Publish and customize the views:

```bash
php artisan vendor:publish --tag=tyro-dashboard-views
```

Views will be published to `resources/views/vendor/tyro-dashboard/`.

### Branding

Set your branding in `.env`:

```env
TYRO_DASHBOARD_APP_NAME="My Application"
TYRO_DASHBOARD_LOGO="/images/logo.svg"
TYRO_DASHBOARD_PREFIX="admin"
```

## 📋 Commands

| Command | Description |
|---------|-------------|
| `php artisan tyro-dashboard:install` | Interactive installation wizard |
| `php artisan tyro-dashboard:version` | Display version information |

## 📝 License

The MIT License (MIT). Please see [License File](LICENSE) for more information.

## 🙏 Credits

- [Hasin Hayder](https://github.com/hasinhayder)
- [All Contributors](../../contributors)
