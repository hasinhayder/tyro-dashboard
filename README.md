<p align="center">
  <img src="https://raw.githubusercontent.com/hasinhayder/tyro-dashboard/main/.github/assets/logo.svg" alt="Tyro Dashboard" width="150">
</p>

<h1 align="center">Tyro Dashboard</h1>

<p align="center">
  <strong>A Beautiful, Modern Admin Dashboard for Laravel 12</strong>
</p>

<p align="center">
  <a href="https://packagist.org/packages/hasinhayder/tyro-dashboard"><img src="https://img.shields.io/packagist/v/hasinhayder/tyro-dashboard.svg?style=for-the-badge&color=0d9488" alt="Latest Version on Packagist"></a>
  <a href="https://packagist.org/packages/hasinhayder/tyro-dashboard"><img src="https://img.shields.io/packagist/php-v/hasinhayder/tyro-dashboard.svg?style=for-the-badge&color=06b6d4" alt="PHP Version"></a>
  <a href="https://packagist.org/packages/hasinhayder/tyro-dashboard"><img src="https://img.shields.io/packagist/dt/hasinhayder/tyro-dashboard.svg?style=for-the-badge&color=14b8a6" alt="Total Downloads"></a>
  <a href="LICENSE"><img src="https://img.shields.io/packagist/l/hasinhayder/tyro-dashboard.svg?style=for-the-badge&color=0891b2" alt="License"></a>
</p>

<p align="center">
  <a href="#-features">Features</a> •
  <a href="#-quick-start">Quick Start</a> •
  <a href="#-configuration">Configuration</a> •
  <a href="#-routes">Routes</a> •
  <a href="#-customization">Customization</a> •
  <a href="#-license">License</a>
</p>

<p align="center">
  <img src="https://raw.githubusercontent.com/hasinhayder/tyro-dashboard/main/.github/assets/screenshot.png" alt="Tyro Dashboard Screenshot" width="800">
</p>

---

Tyro Dashboard is a beautiful, feature-rich admin dashboard for Laravel 12 that provides a complete interface for managing users, roles, privileges, and package settings. It seamlessly integrates with the [Tyro RBAC](https://github.com/hasinhayder/tyro) package and optionally with [Tyro Login](https://github.com/hasinhayder/tyro-login) for a complete user management solution.

## ✨ Features

<table>
  <tr>
    <td>🎨 <strong>Beautiful UI</strong></td>
    <td>Modern, responsive design that matches Tyro Login aesthetics with attention to every detail</td>
  </tr>
  <tr>
    <td>👥 <strong>User Management</strong></td>
    <td>Create, edit, suspend, and manage users with an intuitive interface and role assignment</td>
  </tr>
  <tr>
    <td>🔐 <strong>Role Management</strong></td>
    <td>Full CRUD operations for roles with privilege assignment and protection for critical roles</td>
  </tr>
  <tr>
    <td>🛡️ <strong>Privilege Management</strong></td>
    <td>Manage privileges and assign them to roles for fine-grained access control</td>
  </tr>
  <tr>
    <td>⚙️ <strong>Settings Management</strong></td>
    <td>Configure Tyro and Tyro Login packages from a beautiful UI interface</td>
  </tr>
  <tr>
    <td>👤 <strong>Profile Management</strong></td>
    <td>Users can update their own profile information with self-service account management</td>
  </tr>
  <tr>
    <td>🌙 <strong>Dark Mode</strong></td>
    <td>Built-in dark/light theme support that respects user preferences and system settings</td>
  </tr>
  <tr>
    <td>📱 <strong>Responsive Design</strong></td>
    <td>Works perfectly on all device sizes from mobile phones to large desktop monitors</td>
  </tr>
  <tr>
    <td>🔒 <strong>Role-Based Access</strong></td>
    <td>Admin-only features are protected by configurable role-based access control</td>
  </tr>
</table>

## 📦 Requirements

| Requirement | Version |
|------------|---------|
| **PHP** | 8.2+ |
| **Laravel** | 12.x |
| **[Tyro](https://github.com/hasinhayder/tyro)** | ^1.0 (required) |
| **[Tyro Login](https://github.com/hasinhayder/tyro-login)** | ^1.0 (optional) |

## 🚀 Quick Start

Get up and running in under 5 minutes!

### Step 1: Install via Composer

```bash
composer require hasinhayder/tyro-dashboard
```

### Step 2: Run the Interactive Installer

```bash
php artisan tyro-dashboard:install
```

**That's it!** 🎉 Your admin dashboard is now available at `/dashboard`

<details>
<summary><strong>📋 What the installer does</strong></summary>

- ✅ Checks dependencies (Tyro package)
- ✅ Publishes the configuration file
- ✅ Optionally publishes views for customization
- ✅ Helps you configure admin roles and branding

</details>

### Manual Installation

<details>
<summary>Click to expand manual setup instructions</summary>

If you prefer manual setup:

```bash
# Publish configuration
php artisan vendor:publish --tag=tyro-dashboard-config

# Optionally publish views
php artisan vendor:publish --tag=tyro-dashboard-views
```

</details>

## ⚙️ Configuration

After publishing, you can modify the configuration in `config/tyro-dashboard.php`:

<details>
<summary><strong>View full configuration file</strong></summary>

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

</details>

### Environment Variables

| Variable | Description | Default |
|----------|-------------|---------|
| `TYRO_DASHBOARD_PREFIX` | URL prefix for dashboard routes | `dashboard` |
| `TYRO_DASHBOARD_APP_NAME` | Application name shown in dashboard | `APP_NAME` |
| `TYRO_DASHBOARD_LOGO` | URL to custom logo | `null` |

```env
# Example .env configuration
TYRO_DASHBOARD_PREFIX="admin"
TYRO_DASHBOARD_APP_NAME="My Application"
TYRO_DASHBOARD_LOGO="/images/logo.svg"
```

## 🔗 Routes

The dashboard provides the following routes:

### Public Routes (All Authenticated Users)

| Route | Description |
|-------|-------------|
| `/dashboard` | Main dashboard home page |
| `/dashboard/profile` | User profile settings |

### Admin Routes (Admin Roles Only)

| Route | Description |
|-------|-------------|
| `/dashboard/users` | User management list |
| `/dashboard/users/create` | Create new user |
| `/dashboard/users/{id}/edit` | Edit user details |
| `/dashboard/roles` | Role management list |
| `/dashboard/roles/create` | Create new role |
| `/dashboard/roles/{id}` | View role details |
| `/dashboard/privileges` | Privilege management list |
| `/dashboard/privileges/create` | Create new privilege |
| `/dashboard/settings/tyro` | Tyro package settings |
| `/dashboard/settings/tyro-login` | Tyro Login settings |

## 🛡️ Access Control

Tyro Dashboard implements role-based access control:

| Access Level | Capabilities |
|--------------|--------------|
| **Admin/Super-Admin** | Full access to all dashboard features including user management, role/privilege management, and package settings |
| **Regular Users** | Access to dashboard home and profile management only |

> **💡 Tip:** Configure admin roles via the `admin_roles` setting in `config/tyro-dashboard.php`

## 🎨 Customization

### Publishing Views

Publish and customize the views to match your application's look and feel:

```bash
php artisan vendor:publish --tag=tyro-dashboard-views
```

Views will be published to `resources/views/vendor/tyro-dashboard/`.

### Branding Configuration

Set your branding in `.env`:

```env
TYRO_DASHBOARD_APP_NAME="My Application"
TYRO_DASHBOARD_LOGO="/images/logo.svg"
TYRO_DASHBOARD_PREFIX="admin"
```

## 📋 Artisan Commands

| Command | Description |
|---------|-------------|
| `php artisan tyro-dashboard:install` | Interactive installation wizard |
| `php artisan tyro-dashboard:version` | Display version information |

## 🤝 Part of the Tyro Ecosystem

Tyro Dashboard works seamlessly with:

| Package | Description |
|---------|-------------|
| **[Tyro](https://github.com/hasinhayder/tyro)** | Powerful RBAC (Role-Based Access Control) for Laravel |
| **[Tyro Login](https://github.com/hasinhayder/tyro-login)** | Beautiful, customizable authentication pages |

Together, they provide a complete user management solution for Laravel 12.

## 📝 License

The MIT License (MIT). Please see [License File](LICENSE) for more information.

## 🙏 Credits

- [Hasin Hayder](https://github.com/hasinhayder)
- [All Contributors](../../contributors)

---

<p align="center">
  <sub>Built with ❤️ for the Laravel community</sub>
</p>

<p align="center">
  <a href="https://github.com/hasinhayder/tyro-dashboard/stargazers">⭐ Star us on GitHub</a>
</p>