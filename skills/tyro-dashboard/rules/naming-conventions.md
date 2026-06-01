# Naming Conventions

## Core Principle

Consistent naming makes the codebase navigable. A developer familiar with one controller should immediately understand another. Wrong naming in session keys or cache keys causes cross-package collisions.

## PHP Identifiers

### Classes
- PascalCase matching directory structure
- `HasinHayder\TyroDashboard\Http\Controllers\UserController`
- `HasinHayder\TyroDashboard\Models\Media`
- `HasinHayder\TyroDashboard\Concerns\HasCrud`

### Methods & Properties
- camelCase: `getUserModel()`, `isAdmin()`, `$resourceFields`
- Boolean methods prefixed with `is` or `has`: `isAdmin()`, `hasAccess()`
- Trait boot methods: `boot{ClassName}()` pattern — not `initialize{ClassName}()`

### Traits & Concerns
- PascalCase: `HasCrud`, `HasProfilePhoto`, `HasTyroRoles`
- Descriptive prefix: `Has` or `Can` for traits that add capabilities

### Controllers
- PascalCase, `{Resource}Controller` pattern: `UserController`, `RoleController`
- `ResourceController` is the special dynamic CRUD controller
- `BaseController` is the abstract base — never instantiated directly

## Config Keys

- snake_case: `admin_roles`, `collapsible_sidebar`, `profile_photo_upload`
- Namespaced by section: `features.invitation_system`, `branding.sidebar_bg`
- `.env` variable: `TYRO_DASHBOARD_` prefix, uppercase with underscores

## Route Names

- Format: `{prefix}.{resource}.{action}`
- Examples: `tyro-dashboard.users.index`, `tyro-dashboard.roles.create`
- Actions: `index`, `create`, `store`, `show`, `edit`, `update`, `destroy`
- Custom actions: `{resource}.{verb}` — `users.suspend`, `users.unsuspend`
- Prefix is handled by `DashboardRoute` — never hardcoded in route definitions

## View Names

### File Names
- snake_case matching controller action: `index.blade.php`, `create.blade.php`, `edit.blade.php`

### View Namespace
- `tyro-dashboard::` prefix for all package views
- `tyro-dashboard::dashboard.admin`, `tyro-dashboard::users.index`
- Published views: same relative paths under `resources/views/vendor/tyro-dashboard/`

### Blade Section Names
- snake_case, stable across versions: `content`, `title`, `scripts`, `styles`
- Section names are public API — changing them breaks consumer-published layouts

## CSS & JavaScript

### CSS Classes
- kebab-case following shadcn/ui conventions
- Component-specific prefix where needed: `.media-card`, `.admin-bar`
- No BEM — keep it simple with shadcn/ui patterns

### CSS Custom Properties
- kebab-case with `--` prefix: `--primary`, `--sidebar-background`, `--admin-bar-height`
- Semantic naming: what it is, not what it looks like — `--primary` not `--blue-500`

### JavaScript Variables
- camelCase: `themePreference`, `sidebarCollapsed`
- Global API: `window.TyroDashboardMediaPicker` — namespaced, PascalCase constructor

## Cache & Session Keys

### Cache Keys
- Prefixed with package namespace: `tyro:user-{id}:roles`, `tyro:user-{id}:privileges`
- Tyro Dashboard cache: `tyro_dashboard_fields_{md5}_{hash}`, `tyro_dashboard_hash_{md5}`
- Tyro Login cache: `tyro-login:otp:{userId}`, `tyro-login:lockout-attempts:{ip}`

### Session Keys
- Tyro Login uses dot-namespaced keys: `tyro-login.otp.user_id`, `tyro-login.otp.*`
- Tyro Dashboard uses **flat, package-distinguishing** keys (no `tyro-dashboard.` prefix): `impersonator_id` is the historical example
- The flat convention predates the dot-namespaced style and is preserved for backward compatibility. New Tyro Dashboard session keys must follow the same flat, package-distinguishing style (e.g. `impersonator_id`, not `tyro-dashboard.impersonator_id`)
- Other examples: `login.id`, `login.remember`
- Session keys are public API — consumers and plugins may read them

## `.env` Variables

- Prefix by package: `TYRO_DASHBOARD_*`, `TYRO_*`, `TYRO_LOGIN_*`
- Uppercase with underscores: `TYRO_DASHBOARD_APP_NAME`, `TYRO_DASHBOARD_SIDEBAR_BG`
- Boolean convention: `TYRO_DASHBOARD_ENABLE_*` for feature toggles

## Database

### Tables
- snake_case plural with `tyro_` prefix: `tyro_media`, `tyro_starred_import_images`
- Pivot tables: alphabetical singular — `privilege_role` not `role_privilege`
- Pivot tables don't need `tyro_` prefix if they follow Laravel convention

### Columns
- snake_case: `user_id`, `created_at`, `profile_photo_path`
- Foreign keys: `{relation}_id` (singular)
- Boolean columns: `is_active`, `has_verified` or `use_gravatar`

## Artisan Commands

- Prefix: `tyro-dashboard:`
- Format: `{prefix}:{verb}-{noun}` — `tyro-dashboard:make-resource`, `tyro-dashboard:clear-cache`
- Verbs: `make`, `create`, `install`, `update`, `publish`, `clear`, `remove`, `setup`
