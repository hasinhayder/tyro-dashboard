<div align="center">

# Tyro Dashboard

### Build Powerful Admin Panels in Minutes, Not Weeks

[![Laravel](https://img.shields.io/badge/Laravel-12.x-FF2D20?style=for-the-badge&logo=laravel)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.2+-777BB4?style=for-the-badge&logo=php)](https://php.net)
[![License](https://img.shields.io/badge/License-MIT-green?style=for-the-badge)](LICENSE)

**Stop building the same admin dashboard over and over.**

A production-ready Laravel package that delivers a complete admin & user dashboard with RBAC, user management, and **magical dynamic CRUD** — all configured through a single file.

[Full Documentation](http://hasinhayder.github.io/tyro-dashboard/doc.html) • [GitHub](https://github.com/hasinhayder/tyro-dashboard)

</div>

---

## Table of Contents

- [Overview](#overview)
- [Key Features](#key-features)
- [Why Tyro Dashboard?](#why-tyro-dashboard)
- [Save Time, Focus on Your Product](#save-time-focus-on-your-product)
- [Core Capabilities](#core-capabilities)
  - [User Management](#user-management)
  - [Role & Privilege Management](#role--privilege-management)
  - [Dynamic Resource CRUD](#dynamic-resource-crud)
  - [Separate Dashboards](#separate-dashboards)
  - [Impersonation & User Debugging](#impersonation--user-debugging)
  - [Audit Trail & Compliance](#audit-trail--compliance)
  - [Security & Authorization](#security--authorization)
- [Installation](#installation)
- [Invitation & Referral System](#invitationreferral-system)
- [Use Cases](#use-cases)
- [Full Documentation](#full-documentation)
- [License](#license)

---

## Overview

Tyro Dashboard is a comprehensive Laravel package that eliminates the need to build repetitive admin panel features from scratch. Built on top of **Tyro** (RBAC framework) and **Tyro Login** (authentication system), it provides everything you need to manage users, roles, privileges, and custom resources through an intuitive web interface.

**What would take 40-60 hours of development now takes minutes of configuration.**

---

## Key Features

- **Complete User Management** — Full CRUD, 2FA, suspension, role assignment
- **Role-Based Access Control** — Granular privileges with visual management
- **Dynamic Resource CRUD** — Describe your model, get a full admin interface
- **Separate Dashboards** — Admin and user experiences out of the box
- **User Impersonation** — Debug user issues by securely logging in as them
- **Audit Trail & Compliance** — Track all admin activities with searchable logs
- **Beautiful UI** — Modern, responsive, built with shadcn components
- **Highly Configurable** — Publishable views, extensible controllers
- **Security First** — Built-in authorization, protected resources, audit-ready

---

## Why Tyro Dashboard?

### For Development Teams

| Problem | Solution |
|---------|----------|
| Spending 40-60 hours on every admin panel | **Minutes of configuration** |
| Writing similar CRUD code across projects | **Declare once, reuse forever** |
| Inconsistent implementations across teams | **Standardized patterns** |
| Building user management from scratch | **Ready to use, day one** |

### For Product Managers

- **Faster Time-to-Market** — Launch features faster with pre-built admin
- **Scalability Ready** — Built to handle growing user bases
- **Feature Velocity** — Focus on business logic, not infrastructure
- **Lower Maintenance** — Package updates benefit everyone

### For Security Teams

- Integrated with Tyro's battle-tested RBAC
- Fine-grained role & privilege management
- Suspension tracking, role changes logged
- Leverages Laravel's security features

---

## Save Time, Focus on Your Product

Every hour spent building admin features is an hour not spent on your core product.

### The Real Cost of Building From Scratch

| Feature | Time to Build | With Tyro Dashboard | Time Saved |
|---------|---------------|---------------------|------------|
| User Management (CRUD, search, filters) | 12-16 hours | **0 minutes** | 12-16 hours |
| Role & Privilege System | 8-12 hours | **0 minutes** | 8-12 hours |
| Admin Dashboard UI | 6-10 hours | **0 minutes** | 6-10 hours |
| Authentication & Authorization | 4-6 hours | **0 minutes** | 4-6 hours |
| Resource CRUD (per resource) | 6-10 hours | **2 minutes** | 6-10 hours |
| Form Validation & Error Handling | 3-5 hours | **0 minutes** | 3-5 hours |
| **Total for First Project** | **40-60 hours** | **5 minutes** | **40-60 hours** |
| **Each Additional Project** | **40-60 hours** | **5 minutes** | **40-60 hours** |

### What You Can Do With the Time You Save

**Instead of building admin panels for the 10th time, you could:**

- Ship that feature your customers have been asking for
- Refactor that technical debt you've been avoiding
- Add the polish that makes your product stand out
- Actually take that weekend off
- Onboard 3 new team members in the time you'd spend training them on your custom admin code
- Focus 100% on what makes your product unique

### The Compound Effect

```
Without Tyro Dashboard:
Project 1: 50 hours on admin
Project 2: 50 hours on admin
Project 3: 50 hours on admin
Project 4: 50 hours on admin
= 200 hours spent on repetitive work

With Tyro Dashboard:
Project 1: 5 minutes setup
Project 2: 5 minutes setup
Project 3: 5 minutes setup
Project 4: 5 minutes setup
= 20 minutes total
= 199.67 hours saved
```

That's **5 full weeks** of work time you can invest in your core product.

### Stop Reinventing the Wheel

Your business isn't building user management systems. It's not building role-based access control. It's not building CRUD interfaces for the hundredth time.

**Your business is whatever makes your product unique.**

Tyro Dashboard handles the admin infrastructure so you can focus on what actually matters to your customers.

---

## Core Capabilities

### User Management

Complete user lifecycle management without writing a single line of frontend code:

- Full CRUD operations with search & filtering
- User suspension/unsuspension with reasons
- Two-factor authentication (2FA) management
- Email verification tracking
- Role assignment and bulk operations
- Self-suspension protection
- Protected user configuration

**What You Get**: A fully functional user management interface immediately after installation.

### Role & Privilege Management

Enterprise-grade permission system with visual relationship management:

- Create and manage roles with ease
- Define granular privileges
- Many-to-many role-privilege relationships
- Protected roles (prevent deletion of critical roles)
- Visual role management interface
- User-to-role assignment

**Use Case**: Set up a multi-tenant system where each tenant has their own roles and permissions without touching code.

### Dynamic Resource CRUD

**The game-changing feature** — define your data model through configuration, and Tyro Dashboard automatically generates a complete, production-ready admin interface.

#### What Is Dynamic Resource CRUD?

Dynamic Resource CRUD is a declarative way to define how your data should be managed. Instead of writing controllers, views, routes, validation rules, and handling file uploads manually, you simply describe your model structure and field types in the configuration file. Tyro Dashboard then:

- **Generates the UI** — Creates list views, forms, and detail pages automatically
- **Handles validation** — Applies Laravel validation rules based on your configuration
- **Manages relationships** — Supports belongsTo, hasMany, and other Eloquent relationships
- **Processes files** — Handles file uploads and storage with configurable disks
- **Enforces security** — Applies role-based access control per resource
- **Provides search & sort** — Enables searching across fields and sorting columns

#### How It Works

Simply add your resource definition to `config/tyro-dashboard.php`:

```php
'resources' => [
    'products' => [
        'model' => App\Models\Product::class,
        'roles' => ['admin', 'manager'],
        'readonly' => ['viewer'],
        'upload_disk' => 'public',  // Optional: per-resource storage disk
        'upload_directory' => 'products',  // Optional: per-resource directory
        'fields' => [
            'name' => ['type' => 'text', 'required' => true, 'searchable' => true],
            'price' => ['type' => 'number', 'required' => true, 'sortable' => true],
            'category_id' => ['type' => 'select', 'relationship' => 'category'],
            'image' => ['type' => 'file'],  // File uploads use resource/global upload config
            'is_active' => ['type' => 'checkbox', 'default' => true],
        ],
    ],
]
```

Instantly you get:
- List view with pagination
- Search across multiple fields
- Sortable columns
- Create/Edit forms with validation
- Delete operations
- File upload handling
- Relationship management
- Role-based access control

**No frontend code. No API endpoints. No validation logic. Just configuration.**

#### Learn More

For comprehensive field types, advanced configuration, real-world examples, and best practices:

**[View Complete Documentation](http://hasinhayder.github.io/tyro-dashboard/doc.html)**

### Separate Dashboards

Different experiences for different user types:

#### Admin Dashboard
- Total user count & statistics
- Suspended vs. active users
- Recent user list
- Total roles & privileges count
- Comprehensive system insights

#### User Dashboard
- Personalized welcome
- Relevant metrics
- Non-admin features
- Extensible for custom content

**Why Separate Dashboards?**
- Different information needs
- Better UX for each user type
- Simplified navigation for users

### Impersonation & User Debugging

Securely log in as another user to diagnose issues, verify functionality, and provide support without needing their password.

#### Key Features

- **Seamless User Switching** — Admins can temporarily log in as any user from the user management interface
- **Non-Invasive** — The impersonated user's session is not affected; admins have their own separate session
- **Quick Access** — Impersonate directly from the user list or user detail page
- **Easy Exit** — Return to admin account with a single click

#### Use Cases

**Troubleshooting**: 
- User reports a bug? Log in as them to reproduce the issue in their exact environment
- Verify permissions are working correctly for specific roles
- Check if a feature is visible to users with certain privileges

**Customer Support**: 
- Help users navigate the platform by seeing what they see
- Verify account settings and permissions
- Check if user-facing features are working as expected

**Feature Verification**: 
- Test new features from different user perspectives
- Verify role-based visibility of content
- Validate permission-based feature access

#### Security

- Only users with admin privileges can impersonate other users
- The impersonated user is not logged out; admins operate in a separate session
- Impersonation respects all existing security controls (2FA, email verification, etc.)

#### How to Use

1. Navigate to **User Management** in the admin dashboard
2. Find the user you want to debug
3. Click the **"Impersonate"** button from the user row or detail page
4. You'll be logged in as that user while maintaining your admin session
5. Browse the application as the user would see it
6. Click **"Exit Impersonation"** to return to your admin account

### Audit Trail & Compliance

Comprehensive audit logging for complete transparency and compliance tracking:

#### What's Tracked

- **System Events** — Role and privilege changes, user lifecycle events (creation, suspension, deletion)
- **Administrative Actions** — All admin dashboard activities with who, what, and when
- **User & Security Events** — Password changes, 2FA updates, permission modifications
- **Resource Changes** — All CRUD operations on tracked resources with before/after states

#### Features

- **Searchable Logs** — Find events by actor, event type, or target resource
- **Advanced Filtering** — Filter by event type, user, date range, and more
- **Full Details** — View complete event data including changes made and affected records
- **Privacy Protection** — Sensitive data handling for compliance requirements
- **Admin Control** — Selective flushing of audit records when needed

#### Use Cases

**Security & Compliance**:
- Demonstrate regulatory compliance (SOC 2, GDPR, HIPAA audit requirements)
- Investigate security incidents with complete activity trails
- Monitor for unauthorized access attempts or suspicious activity

**Operational Insights**:
- Track who made what changes and when
- Debug issues by reviewing related events
- Verify administrative actions for accountability

**User Support**:
- Review user activity to assist with support requests
- Verify account changes and permission updates
- Timeline reconstruction for troubleshooting

#### Access

Navigate to **Audit Logs** in the admin dashboard to access the audit trail interface. Only users with admin privileges can view and manage audit logs.

### File Upload Configuration

Intelligent three-tier file upload system for flexible storage management:

#### Priority System
1. **Model-level** (highest) — Define in model using HasCrud trait
2. **Resource-level** (middle) — Configure per-resource in config file  
3. **Global-level** (fallback) — Set global defaults for all resources


### Security & Authorization

Built-in authorization at multiple levels:

- Middleware-based admin checks 
- Per-resource access control
- Per-field readonly modes
- User suspension prevention of access
- Protected resource configuration
- Email verification requirement support
- Two-factor authentication integration
- Secure password hashing

---

## Installation

**Get up and running in under 3 minutes.** No complicated setup, no configuration headaches — just install and go.

### Prerequisites

- Laravel 10.0+ or 11.x
- PHP 8.2+
- [Tyro RBAC](https://github.com/hasinhayder/tyro) package
- [Tyro Login](https://github.com/hasinhayder/tyro-login) package

### That's It — Three Simple Steps

#### Step 1: Install via Composer

```bash
composer require hasinhayder/tyro-dashboard
```

#### Step 2: Run the Installer

```bash
php artisan tyro-dashboard:install
```

This one command does **everything** for you:
- Publishes the configuration file
- Publishes all view files
- Registers routes and middleware
- Sets up your dashboard structure

#### Step 3: Visit Your Dashboard

Navigate to `/dashboard` in your browser.

**That's it!** You now have a fully functional admin dashboard with:
- Complete user management
- Role & privilege administration
- Separate admin and user dashboards
- Beautiful, responsive UI

### Add Your First Resource in 30 Seconds

```php
// app/Models/Product.php
use HasinHayder\TyroDashboard\Concerns\HasCrud;

class Product extends Model
{
    use HasCrud;  // That's it!
    
    protected $fillable = ['name', 'price', 'description'];
}
```

Visit `/dashboard/resources/products` — **your admin interface is live.**

No controllers. No views. No routes. No validation. **Just add the trait and you're done.**

---

## Invitation/Referral System

Built-in referral program to drive organic user growth:

- **Admin Dashboard** — Manage all invitation links, view referral statistics, create links for users
- **User Dashboard** — Generate personal invitation link and track referrals
- **Automatic Tracking** — Signups through invitation links are automatically tracked
- **Fully Configurable** — Enable/disable with `TYRO_DASHBOARD_ENABLE_INVITATION` environment variable (enabled by default)

#### Updating From Previous Versions

If you're updating to a version that includes the invitation system, you **must run migrations** to create the required database tables:

```bash
# Option 1: Run all pending migrations
php artisan migrate

# Option 2: Run only Tyro Login migrations
php artisan migrate --path=vendor/hasinhayder/tyro-login/database/migrations
```

This creates the `invitation_links` and `invitation_referrals` tables needed for the referral system to function.

---

## Profile Photo & Gravatar Support

Each user can now have a custom profile photo or use Gravatar by default.

### Setup Instructions

1. **Run Migrations** — Add the necessary columns to your users table:
   ```bash
   php artisan migrate
   ```

2. **Update User Model** — Add the `HasProfilePhoto` trait to your `User` model:
   ```php
   use HasinHayder\TyroDashboard\Traits\HasProfilePhoto;

   class User extends Authenticatable
   {
       use HasProfilePhoto;
       // ...
   }
   ```

3. **Storage Link** — Ensure your public storage is linked:
   ```bash
   php artisan storage:link
   ```


---

## Use Cases

### E-Commerce Admin Panel

**Challenge**: Build an admin panel to manage products, categories, and orders.

**Solution**: Add resources for Products, Categories, and Orders. Set role-based access (Admin: full, Manager: read-only). **Result**: Full e-commerce admin panel in under 30 minutes.

### Enterprise User Management

**Challenge**: Manage 1000+ employees with different departments and access levels.

**Solution**: Create roles (admin, hr, manager, employee), define privileges (manage_users, view_reports, approve_leave), assign to users. **Result**: HR manages all employees via web interface — no database access needed.

### SaaS Multi-Tenant Platform

**Challenge**: Each tenant needs their own admin panel with custom roles.

**Solution**: Use tenant-specific resource configuration with custom role assignments. **Result**: Multi-tenant admin panels without code duplication.

### Content Management System

**Challenge**: Blog CMS with posts, authors, and comments.

**Solution**: Create resources for Posts, Authors, and Categories with relationship fields. **Result**: Complete CMS admin interface without frontend development.

---

## Full Documentation

For detailed configuration, customization guides, API reference, best practices, and troubleshooting:

**[View Complete Documentation](http://hasinhayder.github.io/tyro-dashboard/doc.html)**

Inside you'll find:
- Detailed configuration options
- All field types with examples
- Customization guides (views, controllers)
- Architecture overview
- Console commands reference
- Best practices & security guidelines
- Troubleshooting guide
- FAQ

---

## License

The Tyro Dashboard package is open-source software licensed under the [MIT license](LICENSE).

---

## Acknowledgments

Built on top of amazing packages:
- [Tyro](https://github.com/hasinhayder/tyro) — RBAC framework
- [Tyro Login](https://github.com/hasinhayder/tyro-login) — Authentication system

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
