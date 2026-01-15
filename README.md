# Tyro Dashboard

A comprehensive, production-ready Laravel package that provides a complete admin and user dashboard with seamless role-based access control (RBAC), user management, and dynamic CRUD capabilities. Built on top of **Tyro** (RBAC framework) and **Tyro Login** (authentication system), Tyro Dashboard eliminates the need to build repetitive dashboard features from scratch.

![Laravel](https://img.shields.io/badge/Laravel-11.x-FF2D20?style=for-the-badge&logo=laravel)
![PHP](https://img.shields.io/badge/PHP-8.2+-777BB4?style=for-the-badge&logo=php)
![License](https://img.shields.io/badge/License-MIT-green?style=for-the-badge)

---

## Table of Contents

- [Overview](#overview)
- [Key Features](#key-features)
- [Why Tyro Dashboard?](#why-tyro-dashboard)
- [Core Capabilities](#core-capabilities)
  - [User Management](#user-management)
  - [Role & Privilege Management](#role--privilege-management)
  - [Dynamic Resource CRUD](#dynamic-resource-crud)
  - [Separate Dashboards](#separate-dashboards)
  - [Security & Authorization](#security--authorization)
- [Installation](#installation)
- [Configuration](#configuration)
- [Usage](#usage)
  - [Basic Setup](#basic-setup)
  - [Creating Dynamic Resources](#creating-dynamic-resources)
  - [Customizing the Dashboard](#customizing-the-dashboard)
- [Architecture](#architecture)
- [Use Cases](#use-cases)
- [API Reference](#api-reference)
- [Best Practices](#best-practices)
- [Troubleshooting](#troubleshooting)
- [Contributing](#contributing)
- [License](#license)

---

## Overview

Tyro Dashboard is a sophisticated, full-featured admin panel and user dashboard system for Laravel applications. It provides everything you need to manage users, roles, privileges, and custom resources through an intuitive web interface—no more building dashboards from scratch.

### What Problems Does It Solve?

1. **Repetitive Dashboard Development**: Eliminate hours spent building user/role management interfaces
2. **Complex Authorization**: Simplified role-based access control with visual management
3. **Rapid Prototyping**: Generate complete CRUD interfaces in minutes
4. **Customization Overhead**: Publishable views and fully configurable behavior
5. **Role Segregation**: Separate experiences for admins and regular users with extensibility

---

## Key Features

### ✨ Complete User Management
- Create, read, update, and delete users
- User suspension and unsuspension with optional reasons
- Two-factor authentication (2FA) support and reset
- Email verification tracking
- Role assignment and management
- User activity monitoring and filtering

### 🔐 Role-Based Access Control (RBAC)
- Intuitive role creation and management
- Privilege assignment to roles
- Protected role configurations (prevent deletion of critical roles)
- Role hierarchy and permission inheritance
- Visual role-privilege associations

### 🎫 Privilege Management
- Create and manage granular permissions (privileges)
- Organize privileges with descriptions
- Batch role assignment to privileges
- Privilege-role relationship tracking
- Searchable privilege directory

### 🏗️ Dynamic Resource CRUD
- Generate complete CRUD interfaces with minimal configuration
- Support for multiple field types (text, email, password, select, multiselect, checkbox, textarea, etc.)
- File upload handling
- Relationship management (belongsTo, hasMany, etc.)
- Advanced search and filtering
- Sortable columns
- Pagination support
- Role-based access control per resource
- Readonly modes for specific roles

### 📊 Separate Dashboards
- **Admin Dashboard**: Comprehensive statistics, user overview, role insights
- **User Dashboard**: Personalized experience for non-admin users
- Extensible view structure for custom dashboard content
- Statistics and metrics display
- Activity summaries

### 👤 User Profile Management
- Self-service profile updates
- Password change with validation
- 2FA management and reset
- Email update with re-verification
- Secure password rules enforcement

### 🎨 UI/UX Polished Interface
- Clean, modern interface built on shadcn components
- Responsive design for mobile, tablet, and desktop
- Intuitive navigation and menu system
- Flash messages for user feedback
- Search and filtering capabilities
- Pagination with query string persistence

### ⚙️ Highly Configurable
- Publishable views for full customization
- Configuration file for behavior tuning
- Middleware integration
- Route prefix and naming customization
- Pagination limits
- Admin role configuration
- Protected user and role lists

---

## Why Tyro Dashboard?

### For Development Teams

**Save Development Time**: A typical admin dashboard requires 40-60 hours of development. Tyro Dashboard cuts this to minutes of configuration.

**Reduce Code Duplication**: No more writing similar CRUD endpoints, validation logic, and templates across projects.

**Consistent Experience**: Every implementation follows the same patterns, making team collaboration seamless.

**Professional Foundation**: Built with industry best practices, security hardening, and performance optimization.

### For Product Managers

**Faster Time-to-Market**: Launch features faster with a pre-built admin interface.

**Scalability Ready**: Built to handle growing user bases and complex role hierarchies.

**Lower Maintenance**: Updates and improvements benefit all applications using the package.

**Feature Velocity**: Focus on business logic instead of infrastructure code.

### For Security Teams

**Authorization Built-in**: Integrated with Tyro's battle-tested RBAC framework.

**Access Control**: Fine-grained role and privilege management.

**Audit Trail Ready**: Suspension tracking, role changes, and user modifications are logged.

**Framework Security**: Leverages Laravel's security features (CSRF, validation, hashing, etc.).

---

## Core Capabilities

### User Management

Complete user lifecycle management with enterprise features:

```
✓ User CRUD operations
✓ Email verification tracking
✓ User suspension/unsuspension with reasons
✓ Two-factor authentication (2FA) reset
✓ Password management with strength rules
✓ Role assignment and modification
✓ Advanced search and filtering
✓ Status-based filtering (active/suspended)
✓ Role-based filtering
✓ Pagination with configurable per-page limits
```

**What You Get**: A fully functional user management interface without writing a single line of frontend code.

### Role & Privilege Management

Granular permission system with visual relationship management:

```
✓ Create and manage roles
✓ Create and manage privileges
✓ Assign privileges to roles (many-to-many)
✓ Remove privileges from roles
✓ Associate users with roles
✓ Protected role support (prevent critical role deletion)
✓ Search across all role/privilege management
✓ Activity tracking with relationship counts
```

**Use Case**: Set up a multi-tenant system where each tenant has their own roles and permissions without touching code.

### Dynamic Resource CRUD

The standout feature: describe your data model, get a complete admin interface.

#### Supported Field Types
- **Text Input**: Standard text fields
- **Email**: Email validation included
- **Password**: Hashed password storage
- **Textarea**: Multi-line text
- **Select**: Dropdown selection (for relationships)
- **Multiselect**: Multiple selection support
- **Checkbox**: Boolean values
- **Checkbox Group**: Multiple checkboxes
- **Date**: Date picker
- **File Upload**: Single file handling
- **Custom HTML**: Extensibility for special fields

#### Capabilities
```
✓ Automatic form generation
✓ Validation rules per field
✓ Relationship handling
✓ File upload and storage
✓ Search across multiple fields
✓ Sortable columns
✓ Pagination
✓ Role-based access (full/readonly/none)
✓ Create, read, update, delete operations
```

**Example**: Add a "Products" resource to your config, and within seconds you have a fully functional product management interface with search, filtering, and pagination.

### Separate Dashboards

Different experiences for different user types:

#### Admin Dashboard
- Total user count
- Suspended vs. active users
- Recent user list
- Total roles count
- Active roles
- Total privileges count
- Comprehensive statistics
- Activity overview

#### User Dashboard
- Personalized welcome
- Relevant information
- Non-admin features
- Extensible for custom metrics

**Why Separate Dashboards?**
- Different information needs
- Better UX for each user type
- Simplified navigation for users
- Admin-specific analytics available

### Security & Authorization

Built-in authorization at multiple levels:

```
✓ Middleware-based admin checks
✓ Per-resource access control
✓ Per-field readonly modes
✓ User suspension prevention of access
✓ Protected resource configuration
✓ Email verification requirement support
✓ Two-factor authentication integration
✓ Secure password hashing
```

---

## Installation

### Requirements

- Laravel 10.0+
- PHP 8.2+
- Tyro (RBAC package)
- Tyro Login (Authentication package)

### Step 1: Install via Composer

```bash
composer require hasinhayder/tyro-dashboard
```

### Step 2: Publish and Run Installation

```bash
php artisan tyro-dashboard:install
```

This command will:
- Publish configuration file
- Publish view files
- Update database (if needed)
- Create necessary directories

### Step 3: Configure Your Application

Update your `.env`:

```env
TYRO_DASHBOARD_ENABLED=true
```

### Step 4: Extend Your User Model

Ensure your User model uses the Tyro traits:

```php
use HasinHayder\Tyro\Traits\HasTyroRoles;

class User extends Authenticatable
{
    use HasTyroRoles;
    // ... rest of your model
}
```

---

## Configuration

The package publishes a configuration file at `config/tyro-dashboard.php`.

### Key Configuration Options

```php
return [
    // Route configuration
    'routes' => [
        'prefix' => 'dashboard',          // URL prefix
        'middleware' => ['web', 'auth'],  // Route middleware
        'name_prefix' => 'tyro-dashboard.', // Route name prefix
    ],

    // Pagination
    'pagination' => [
        'users' => 15,
        'roles' => 15,
        'privileges' => 15,
        'resources' => 15,
    ],

    // Admin role(s)
    'admin_roles' => ['admin', 'super-admin'],

    // Protected configurations
    'protected' => [
        'users' => [],     // User IDs that cannot be deleted
        'roles' => [],     // Role slugs that cannot be deleted
    ],

    // Dynamic resources
    'resources' => [
        // Example:
        // 'products' => [
        //     'model' => App\Models\Product::class,
        //     'roles' => ['admin'],
        //     'readonly' => ['manager'],
        //     'fields' => [
        //         'name' => ['type' => 'text', 'required' => true],
        //         'price' => ['type' => 'number', 'required' => true],
        //     ],
        // ]
    ],
];
```

### Publishing Views

Customize the dashboard by publishing views:

```bash
# Publish all views
php artisan vendor:publish --tag=tyro-dashboard-views

# Publish only admin views
php artisan vendor:publish --tag=tyro-dashboard-views-admin

# Publish only user views
php artisan vendor:publish --tag=tyro-dashboard-views-user

# Publish configuration
php artisan vendor:publish --tag=tyro-dashboard-config

# Publish theme
php artisan vendor:publish --tag=tyro-dashboard-theme
```

---

## Usage

### Basic Setup

After installation, your dashboard is immediately available at `/dashboard` (or your configured prefix).

### Creating Dynamic Resources

Dynamic resources are the most powerful feature. Here's how to create one:

#### Step 1: Add to Configuration

```php
// config/tyro-dashboard.php

'resources' => [
    'products' => [
        'model' => App\Models\Product::class,
        
        // Access control
        'roles' => ['admin', 'manager'],      // Who can access?
        'readonly' => ['viewer'],              // Who can only view?
        
        // Field definitions
        'fields' => [
            'name' => [
                'type' => 'text',
                'label' => 'Product Name',
                'required' => true,
                'validation' => 'max:255',
                'sortable' => true,
                'searchable' => true,
            ],
            'description' => [
                'type' => 'textarea',
                'label' => 'Description',
                'required' => false,
                'validation' => 'max:1000',
            ],
            'price' => [
                'type' => 'number',
                'label' => 'Price (USD)',
                'required' => true,
                'validation' => 'numeric|min:0',
                'sortable' => true,
            ],
            'category_id' => [
                'type' => 'select',
                'label' => 'Category',
                'required' => true,
                'relationship' => 'category',
                'validation' => 'exists:categories,id',
            ],
            'tags' => [
                'type' => 'multiselect',
                'label' => 'Tags',
                'required' => false,
                'relationship' => 'tags',
                'validation' => 'array',
            ],
            'image' => [
                'type' => 'file',
                'label' => 'Product Image',
                'required' => false,
                'storage' => 'public',
                'path' => 'products',
            ],
            'is_active' => [
                'type' => 'checkbox',
                'label' => 'Active',
                'default' => true,
            ],
        ],
    ],
];
```

#### Step 2: Access Your Resource

Navigate to `/dashboard/resources/products` and you instantly have:

- ✅ List all products with pagination
- ✅ Search products by name, description
- ✅ Sort by any sortable column
- ✅ Create new products with full validation
- ✅ Edit existing products
- ✅ Delete products
- ✅ Handle file uploads
- ✅ Manage relationships (categories, tags)
- ✅ Role-based access control

**No frontend code needed.**

### Customizing the Dashboard

#### Option 1: Customize Views

Publish views and modify them:

```bash
php artisan vendor:publish --tag=tyro-dashboard-views
```

Then edit files in `resources/views/vendor/tyro-dashboard/`.

#### Option 2: Extend Controllers

Create your own controller extending the base:

```php
namespace App\Http\Controllers;

use HasinHayder\TyroDashboard\Http\Controllers\UserController as BaseUserController;

class CustomUserController extends BaseUserController
{
    public function index(Request $request)
    {
        // Add custom logic
        return parent::index($request);
    }
}
```

#### Option 3: Custom Dashboard Views

Create separate dashboard views for different user types:

```php
// resources/views/vendor/tyro-dashboard/dashboard/custom-user.blade.php
@extends('tyro-dashboard::layouts.user')

@section('content')
    <div class="p-6">
        <h1>Welcome, {{ auth()->user()->name }}!</h1>
        <!-- Your custom content -->
    </div>
@endsection
```

---

## Architecture

### Layer Structure

```
┌─────────────────────────────────────────────────────┐
│              Views (Blade Templates)                │
│         Customizable & Publishable                  │
└──────────────────┬──────────────────────────────────┘
                   │
┌──────────────────v──────────────────────────────────┐
│            Controllers                              │
│  - UserController                                   │
│  - RoleController                                   │
│  - PrivilegeController                              │
│  - ResourceController (Dynamic CRUD)                │
│  - DashboardController                              │
│  - ProfileController                                │
└──────────────────┬──────────────────────────────────┘
                   │
┌──────────────────v──────────────────────────────────┐
│          Service Provider & Middleware              │
│  - Route Registration                               │
│  - Middleware: EnsureIsAdmin                        │
│  - View Publishing                                  │
│  - Configuration Loading                            │
└──────────────────┬──────────────────────────────────┘
                   │
┌──────────────────v──────────────────────────────────┐
│  Tyro (RBAC) & Tyro Login (Authentication)         │
│         Core Authorization & Auth Layer             │
└──────────────────┬──────────────────────────────────┘
                   │
┌──────────────────v──────────────────────────────────┐
│        Laravel Framework & Database                 │
└─────────────────────────────────────────────────────┘
```

### Key Components

| Component | Purpose | Customizable |
|-----------|---------|--------------|
| Controllers | Handle all business logic | Yes, extensible |
| Middleware | Authorization checks | Yes, replaceable |
| Views | UI templates | Yes, publishable |
| Config | Behavior configuration | Yes, publishable |
| Routes | URL definitions | Auto-generated |

---

## Use Cases

### Use Case 1: SaaS Multi-Tenant Admin Panel

**Scenario**: You're building a SaaS application where each tenant needs their own admin panel.

**How Tyro Dashboard Helps**:
- Create different roles per tenant (Admin, Manager, User)
- Define custom privileges for each role
- Use dynamic resources to manage tenant-specific data
- Different dashboards show tenant-specific statistics

**Result**: Your multi-tenant admin panel is ready without custom coding.

---

### Use Case 2: Enterprise User Management

**Scenario**: You have 1000+ employees and need centralized user management.

**How Tyro Dashboard Helps**:
- Suspend users for offboarding
- Assign roles based on departments
- Track user activity through the dashboard
- Use the resource CRUD for custom employee data

**Result**: HR can manage all employees through the web interface; no database access needed.

---

### Use Case 3: E-Commerce Admin Panel

**Scenario**: You're building an e-commerce platform with multiple product categories.

**How Tyro Dashboard Helps**:
- Create "Products" resource in config (2 minutes)
- Create "Categories" resource
- Create "Orders" resource
- Set roles: Admin (full access), Manager (readonly on products)

**Result**: Full e-commerce admin panel with search, filter, and bulk operations—ready in hours instead of weeks.

---

### Use Case 4: Content Management System (CMS)

**Scenario**: You need a CMS for managing blog posts, authors, and comments.

**How Tyro Dashboard Helps**:
- Resource for "Posts" with rich text support
- Resource for "Authors"
- Resource for "Categories"
- Set "Editor" role as readonly for published posts
- "Author" role can only edit their own posts (extensible)

**Result**: Complete CMS admin interface without frontend development.

---

### Use Case 5: Project Management Tool

**Scenario**: Building an internal project management tool.

**How Tyro Dashboard Helps**:
- "Projects" resource for project management
- "Tasks" resource with assignment to users
- "Teams" resource
- Different dashboards for managers vs. team members

**Result**: Dashboard admin interface with all CRUD operations and visualizations.

---

## API Reference

### Controllers

#### UserController

```php
// List users with filtering
GET /dashboard/users

// Show create form
GET /dashboard/users/create

// Store new user
POST /dashboard/users

// Edit user
GET /dashboard/users/{id}/edit

// Update user
PUT /dashboard/users/{id}

// Suspend user
POST /dashboard/users/{id}/suspend

// Unsuspend user
POST /dashboard/users/{id}/unsuspend

// Reset 2FA
DELETE /dashboard/users/{id}/2fa

// Delete user
DELETE /dashboard/users/{id}
```

#### RoleController

```php
// List roles
GET /dashboard/roles

// Show create form
GET /dashboard/roles/create

// Store new role
POST /dashboard/roles

// Show role details
GET /dashboard/roles/{id}

// Show edit form
GET /dashboard/roles/{id}/edit

// Update role
PUT /dashboard/roles/{id}

// Delete role
DELETE /dashboard/roles/{id}

// Remove user from role
DELETE /dashboard/roles/{id}/users/{userId}
```

#### PrivilegeController

```php
// List privileges
GET /dashboard/privileges

// Show create form
GET /dashboard/privileges/create

// Store new privilege
POST /dashboard/privileges

// Show privilege details
GET /dashboard/privileges/{id}

// Show edit form
GET /dashboard/privileges/{id}/edit

// Update privilege
PUT /dashboard/privileges/{id}

// Delete privilege
DELETE /dashboard/privileges/{id}

// Remove privilege from role
DELETE /dashboard/privileges/{id}/roles/{roleId}
```

#### Dynamic Resource Controller

```php
// List resources
GET /dashboard/resources/{resource}

// Show create form
GET /dashboard/resources/{resource}/create

// Store new resource
POST /dashboard/resources/{resource}

// Show resource details
GET /dashboard/resources/{resource}/{id}

// Show edit form
GET /dashboard/resources/{resource}/{id}/edit

// Update resource
PUT /dashboard/resources/{resource}/{id}

// Delete resource
DELETE /dashboard/resources/{resource}/{id}
```

### Configuration Methods

#### Resource Configuration

```php
'resources' => [
    'resource_name' => [
        'model' => Full\Model\Path::class,        // Required
        'roles' => ['admin'],                     // Access roles
        'readonly' => ['viewer'],                 // Readonly roles
        'fields' => [
            'field_name' => [
                'type' => 'text|email|password|number|select|multiselect|checkbox|file|textarea|date',
                'label' => 'Display Label',
                'required' => true|false,
                'validation' => 'laravel|validation|rules',
                'searchable' => true|false,
                'sortable' => true|false,
                'default' => 'default_value',
                'relationship' => 'relation_name',  // for select/multiselect
                'storage' => 'disk_name',          // for file uploads
                'path' => 'storage_path',          // for file uploads
            ],
        ],
    ],
]
```

---

## Best Practices

### 1. Security

**DO**:
- Always define explicit roles for resources
- Use readonly mode for sensitive operations
- Keep admin roles limited to trusted users
- Regularly audit user suspensions

**DON'T**:
- Leave resources open to all roles without explicit definition
- Use user IDs directly in URLs without authorization checks (package handles this)
- Store sensitive data in custom resource fields without encryption

### 2. Performance

**DO**:
- Set appropriate pagination limits for large datasets
- Use searchable/sortable only on indexed columns
- Eager load relationships in resource definitions
- Cache role/privilege lookups

**DON'T**:
- Make all fields searchable on large tables (impacts query performance)
- Forget to add database indexes for searchable fields
- Load unnecessary relationships in the dashboard

### 3. User Experience

**DO**:
- Use descriptive field labels
- Group related resources
- Provide clear error messages
- Test with actual user roles

**DON'T**:
- Make the dashboard too cluttered
- Hide important information behind extra clicks
- Change UI drastically between admin and user views

### 4. Customization

**DO**:
- Extend controllers instead of modifying them
- Use published views for styling changes
- Create custom commands for bulk operations
- Document your customizations

**DON'T**:
- Directly edit package files
- Create separate dashboard implementations
- Hardcode configuration values in code

---

## Troubleshooting

### Issue: "You do not have permission to access this area"

**Solution**: 
- Check if user has an admin role configured in `config/tyro-dashboard.php`
- Verify user roles are correctly assigned
- Ensure `tyro-dashboard.admin` middleware is applied

### Issue: Resource not appearing in dashboard

**Solution**:
- Verify resource is defined in `config/tyro-dashboard.php`
- Check model class exists and is correctly namespaced
- Ensure you have access roles defined for the resource
- Check that user's role is in the resource's `roles` array

### Issue: File uploads not working

**Solution**:
- Check disk configuration in `config/filesystems.php`
- Verify storage path is writable
- Ensure file validation rules are correct
- Check that 'storage' disk is properly configured

### Issue: Search/Sort not working

**Solution**:
- Verify fields have `searchable: true` and `sortable: true`
- Check database columns exist
- Add database indexes for searchable fields
- Clear query cache if using caching

### Issue: 2FA reset not working

**Solution**:
- Verify user model has 2FA columns
- Check Tyro package is properly installed
- Ensure user model uses required traits

---

## Console Commands

The package includes helpful console commands:

```bash
# Install the package
php artisan tyro-dashboard:install

# Create a new resource (guided)
php artisan tyro-dashboard:make-resource

# Publish assets
php artisan tyro-dashboard:publish

# Publish styles
php artisan tyro-dashboard:publish-style

# Show version
php artisan tyro-dashboard:version
```

---

## Contributing

We welcome contributions! Please follow these guidelines:

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

### Development Setup

```bash
# Clone repository
git clone https://github.com/hasinhayder/tyro-dashboard.git

# Install dependencies
composer install

# Run tests
composer test

# Run linting
composer lint
```

---

## Support

- 📧 **Email**: support@example.com
- 🐛 **Issues**: [GitHub Issues](https://github.com/hasinhayder/tyro-dashboard/issues)
- 💬 **Discussions**: [GitHub Discussions](https://github.com/hasinhayder/tyro-dashboard/discussions)
- 📚 **Documentation**: [Full Docs](https://tyro-dashboard.example.com)

---

## Changelog

### Version 1.0.0 (Initial Release)

- Complete user management system
- Role and privilege management
- Dynamic resource CRUD
- Separate admin and user dashboards
- Two-factor authentication support
- User suspension/unsuspension
- Comprehensive configuration options
- Publishable views and assets

---

## License

The Tyro Dashboard package is open-source software licensed under the [MIT license](LICENSE).

---

## Acknowledgments

- Built on top of [Tyro](https://github.com/hasinhayder/tyro) - RBAC framework
- Authentication powered by [Tyro Login](https://github.com/hasinhayder/tyro-login)
- UI components from shadcn design system
- Inspired by modern admin dashboard patterns

---

## Roadmap

### Planned Features (Future Releases)

- [ ] Bulk user import/export (CSV)
- [ ] Advanced filtering with saved filters
- [ ] Audit logging and activity tracking
- [ ] Email notifications for admin actions
- [ ] API token management
- [ ] Two-factor authentication methods (SMS, authenticator app)
- [ ] User activity logs
- [ ] Role duplication
- [ ] Permission inheritance chains
- [ ] Workflow automation

---

## FAQ

**Q: Can I use this in production?**
A: Yes, Tyro Dashboard is production-ready with comprehensive security measures.

**Q: Is it free?**
A: Yes, Tyro Dashboard is open-source and free to use.

**Q: Can I modify the dashboard for my needs?**
A: Absolutely! All views are publishable and controllers are extensible.

**Q: Does it support multiple databases?**
A: Yes, configure different databases in your Laravel config.

**Q: Can I add custom fields to resources?**
A: Yes, extend the ResourceController and field types are fully customizable.

**Q: What about API access?**
A: Currently focused on web interface. API endpoints can be added through extension.

**Q: How do I handle custom validation?**
A: Define validation rules in the resource configuration using Laravel's validation syntax.

**Q: Can I use this with headless architectures?**
A: The dashboard is web-based, but you can extend it to provide API endpoints.

---

## Getting Started

Ready to supercharge your Laravel application?

```bash
# 1. Install via Composer
composer require hasinhayder/tyro-dashboard

# 2. Run installation
php artisan tyro-dashboard:install

# 3. Visit your dashboard
# Navigate to /dashboard

# 4. Create your first resource
# Edit config/tyro-dashboard.php and define a resource

# 5. Start managing
# Your complete admin interface is ready!
```

---

**Made with ❤️ by HasinHayder**

**[GitHub](https://github.com/hasinhayder) • [Twitter](https://twitter.com) • [Website](https://example.com)**
