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

---

Tyro Dashboard is a beautiful, feature-rich admin dashboard for Laravel 12 that provides a complete interface for managing users, roles, privileges, and package settings. It seamlessly integrates with the [Tyro RBAC](https://github.com/hasinhayder/tyro) package and optionally with [Tyro Login](https://github.com/hasinhayder/tyro-login) for a complete user management solution.

## Features

<table>
  <tr>
    <td><strong>Beautiful UI</strong></td>
    <td>Modern, responsive design that matches Tyro Login aesthetics with attention to every detail</td>
  </tr>
  <tr>
    <td><strong>User Management</strong></td>
    <td>Create, edit, suspend, and manage users with an intuitive interface and role assignment</td>
  </tr>
  <tr>
    <td><strong>Role Management</strong></td>
    <td>Full CRUD operations for roles with privilege assignment and protection for critical roles</td>
  </tr>
  <tr>
    <td><strong>Privilege Management</strong></td>
    <td>Manage privileges and assign them to roles for fine-grained access control</td>
  </tr>
  <tr>
    <td><strong>Settings Management</strong></td>
    <td>Configure Tyro and Tyro Login packages from a beautiful UI interface</td>
  </tr>
  <tr>
    <td><strong>Profile Management</strong></td>
    <td>Users can update their own profile information with self-service account management</td>
  </tr>
  <tr>
    <td><strong>2FA Management</strong></td>
    <td>Self-service 2FA setup and reset for users, with administrative override capabilities</td>
  </tr>
  <tr>
    <td><strong>Dark Mode</strong></td>
    <td>Built-in dark/light theme support that respects user preferences and system settings</td>
  </tr>
  <tr>
    <td><strong>Responsive Design</strong></td>
    <td>Works perfectly on all device sizes from mobile phones to large desktop monitors</td>
  </tr>
  <tr>
    <td><strong>Role-Based Access</strong></td>
    <td>Admin-only features are protected by configurable role-based access control</td>
  </tr>
</table>

## Requirements

| Requirement | Version |
|------------|---------|
| **PHP** | 8.2+ |
| **Laravel** | 12.x |
| **[Tyro](https://github.com/hasinhayder/tyro)** | ^1.0 (required) |
| **[Tyro Login](https://github.com/hasinhayder/tyro-login)** | ^1.0 (optional) |

## Quick Start

Get up and running in under 5 minutes!

### Step 1: Install via Composer

```bash
composer require hasinhayder/tyro-dashboard
```

### Step 2: Run the Interactive Installer

```bash
php artisan tyro-dashboard:install
```

**That's it!** Your admin dashboard is now available at `/dashboard`

<details>
<summary><strong>What the installer does</strong></summary>

- Checks dependencies (Tyro package)
- Publishes the configuration file
- Optionally publishes views for customization
- Helps you configure admin roles and branding

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

## Configuration

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
```

</details>

## Dynamic CRUD Resources

Tyro Dashboard allows you to instantly generate complete CRUD interfaces for your models by simply adding them to the configuration. No need to create controllers or views manually!

### Workflow

1.  **Create your Model & Migration**: Standard Laravel models. Ensure relationships are defined if you plan to use them.
2.  **Configure Resource**: Add the resource definition to `config/tyro-dashboard.php`.
3.  **Scaffold (Optional)**: Use the helper command to generate files if starting from scratch.

### 1. Scaffolding (Optional)

If you need to create the Model, Migration, and other resources in your main application, you can use the helper command:

```bash
php artisan tyro-dashboard:make-resource Post
```

This will interactively create the Model, Migration, Controller (optional), and Requests, and provide the configuration snippet to add to `tyro-dashboard.php`.

### 2. Configure Resources

Open `config/tyro-dashboard.php` and add your resources to the `resources` array. Here is a comprehensive example showing all available field types:

```php
'resources' => [
    'posts' => [
        'model' => 'App\Models\Post',
        'title' => 'Posts',
        // 'icon' => '<svg>...</svg>', // Optional SVG icon
        
        // Full Access roles (Optional): Only these roles can access this resource.
        // If not set, all admin users can access.
        'roles' => ['admin', 'manager'],

        // Read-only configuration: Users with these roles can only view (index/show)
        // They can access even if they are not in the 'roles' list above.
        'readonly' => ['editor', 'viewer'],

        'fields' => [
            // Basic Fields
            'title' => ['type' => 'text', 'label' => 'Title', 'rules' => 'required|max:255', 'searchable' => true],
            'slug' => ['type' => 'text', 'label' => 'Slug', 'hide_in_index' => true],
            'content' => ['type' => 'textarea', 'label' => 'Content', 'rules' => 'required', 'hide_in_index' => true],
            'published_at' => ['type' => 'date', 'label' => 'Publish Date'],
            
            // Boolean (Toggle)
            'is_published' => ['type' => 'boolean', 'label' => 'Published'],
            
            // File Upload
            // Files are automatically stored in public disk under the resource name folder
            'cover_image' => ['type' => 'file', 'label' => 'Cover Image', 'rules' => 'image|max:2048'],
            
            // Select (BelongsTo Relationship)
            'category_id' => [
                'type' => 'select', 
                'label' => 'Category', 
                'relationship' => 'category', // Method name in Post model (belongsTo)
                'option_label' => 'name',     // Attribute to display
                'rules' => 'required'
            ],

            // Radio Buttons (Static Options)
            'priority' => [
                'type' => 'radio',
                'label' => 'Priority',
                'options' => [
                    'low' => 'Low Priority',
                    'medium' => 'Medium Priority',
                    'high' => 'High Priority',
                ],
                'default' => 'medium'
            ],

            // Multiselect (BelongsToMany Relationship)
            'tags' => [
                'type' => 'multiselect', // or 'checkbox' for a list of checkboxes
                'label' => 'Tags',
                'relationship' => 'tags', // Method name in Post model (belongsToMany)
                'option_label' => 'name',
            ],
            'status' => [
                'type' => 'select',
                'label' => 'Status',
                'options' => [
                    'draft' => 'Draft',
                    'published' => 'Published',
                    'archived' => 'Archived',
                ],
                'rules' => 'required',
            ],
        ],
    ],
],
```

### Field Definitions & Options

| Option | Description |
|--------|-------------|
| `type` | Field type. See **Supported Field Types** below. |
| `label` | Label to display in forms and tables. |
| `rules` | Laravel validation rules (e.g., `required|email`). |
| `searchable` | Set to `true` to include this field in search queries. |
| `sortable` | Set to `true` to allow sorting by this field. |
| `hide_in_index` | Set to `true` to hide this field from the list view. |
| `hide_in_form` | Set to `true` to hide this field from create/edit forms. |
| `relationship` | The name of the relationship method on the model. Required for relational fields. |
| `option_label` | The attribute to display for options (e.g., `name`, `title`). Default: `name`. |
| `options` | Array of key-value pairs for static options (for `select`, `radio`, `checkbox`). |

### Supported Field Types

| Type | Description | Relationship Support |
|------|-------------|---------------------|
| `text` | Standard text input | No |
| `textarea` | Multi-line text area | No |
| `email` | Email input | No |
| `number` | Number input | No |
| `password` | Password input (value hidden in edit) | No |
| `date` | Date picker input | No |
| `boolean` | Checkbox for boolean values (true/false) | No |
| `file` | File upload input. Handles storage automatically. | No |
| `select` | Dropdown menu. | **Yes** (`belongsTo`) |
| `radio` | Radio button group. | **Yes** (`belongsTo`) |
| `multiselect` | Multiple select dropdown. | **Yes** (`belongsToMany`) |
| `checkbox` | Checkbox group for multiple selection. | **Yes** (`belongsToMany`) |

### Handling Relationships

**BelongsTo (Single Selection)**
Use `select` or `radio` types.
-   Set `relationship` to the method name in your model (e.g., `'category'`).
-   Set `option_label` to the column you want to display (e.g., `'name'`).
-   The field key should be the foreign key (e.g., `'category_id'`).

**BelongsToMany (Multiple Selection)**
Use `multiselect` or `checkbox` types.
-   Set `relationship` to the method name in your model (e.g., `'tags'`).
-   The field key can be the relationship name or any unique string (e.g., `'tags'`).
-   Tyro Dashboard automatically handles `sync()` for these relationships.

```php
// In Post Model
public function category() {
    return $this->belongsTo(Category::class);
}

public function tags() {
    return $this->belongsToMany(Tag::class);
}
```

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

## Routes

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

## Access Control

Tyro Dashboard implements role-based access control:

| Access Level | Capabilities |
|--------------|--------------|
| **Admin/Super-Admin** | Full access to all dashboard features including user management, role/privilege management, and package settings |
| **Regular Users** | Access to dashboard home and profile management only |

> **Tip:** Configure admin roles via the `admin_roles` setting in `config/tyro-dashboard.php`

## Customization

### Publishing Views

Publish and customize the views to match your application's look and feel:

```bash
php artisan vendor:publish --tag=tyro-dashboard-views
```

Views will be published to `resources/views/vendor/tyro-dashboard/`.

### Theme Customization (shadcn Variables)

Tyro Dashboard uses [shadcn/ui](https://ui.shadcn.com) CSS variables for theming, making it easy to customize colors and integrate with shadcn-based projects.

#### Publishing Theme Files

Publish the theme variables to customize the look and feel:

```bash
# Publish only theme variables (recommended for color customization)
php artisan tyro-dashboard:publish-style --theme-only

# Or publish complete styles (theme + component styles)
php artisan tyro-dashboard:publish-style
```

Theme files will be published to `resources/views/vendor/tyro-dashboard/partials/`.

#### Visual Theme Editing with tweakcn (free)

The easiest way to customize your theme is using [tweakcn.com](https://tweakcn.com):

1. Visit [tweakcn.com](https://tweakcn.com)
2. Use the visual editor to create your perfect color palette
3. Copy the generated CSS variables
4. Publish your theme: `php artisan tyro-dashboard:publish-style --theme-only`
5. Paste the variables into `resources/views/vendor/tyro-dashboard/partials/shadcn-theme.blade.php`

#### Theme File Structure

After publishing, your theme structure will be:

```
resources/views/vendor/tyro-dashboard/partials/
├── shadcn-theme.blade.php  # Theme variables (edit this!)
└── styles.blade.php        # Component styles (includes theme)
```

The `shadcn-theme.blade.php` file contains only CSS variables, making it safe to edit without breaking component styles.

#### Available CSS Variables

Tyro Dashboard uses standard shadcn CSS variables in oklch color format for both light and dark modes:

- `--background` - Page background
- `--foreground` - Default text color
- `--primary` - Primary buttons and links
- `--secondary` - Secondary elements
- `--destructive` - Error/danger states
- `--border` - Border colors
- `--input` - Input field borders
- `--ring` - Focus ring colors
- `--card` - Card backgrounds
- `--muted` - Muted backgrounds

### Branding Configuration

Set your branding in `.env`:

```env
TYRO_DASHBOARD_APP_NAME="My Application"
TYRO_DASHBOARD_LOGO="/images/logo.svg"
TYRO_DASHBOARD_PREFIX="admin"
```

## Artisan Commands

| Command | Description |
|---------|-------------|
| `php artisan tyro-dashboard:install` | Interactive installation wizard |
| `php artisan tyro-dashboard:version` | Display version information |
| `php artisan tyro-dashboard:publish-style` | Publish styles and theme files |
| `php artisan tyro-dashboard:publish-style --theme-only` | Publish only theme variables (recommended) |

## Part of the Tyro Ecosystem

Tyro Dashboard works seamlessly with:

| Package | Description |
|---------|-------------|
| **[Tyro](https://github.com/hasinhayder/tyro)** | Powerful RBAC (Role-Based Access Control) for Laravel |
| **[Tyro Login](https://github.com/hasinhayder/tyro-login)** | Beautiful, customizable authentication pages |

Together, they provide a complete user management solution for Laravel 12.

## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.

## Credits

- [Hasin Hayder](https://github.com/hasinhayder)
- [All Contributors](../../contributors)

---

<p align="center">
  <sub>Built with love for the Laravel community</sub>
</p>

<p align="center">
  <a href="https://github.com/hasinhayder/tyro-dashboard/stargazers">Star us on GitHub</a>
</p>