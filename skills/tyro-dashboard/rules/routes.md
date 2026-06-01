# Routes

## Core Principle

Routes define the public URL surface of the framework. Changing a route name breaks every `route()` call in every consumer application. Changing a route prefix breaks every bookmark and integration.

## Route Group

All routes are wrapped in a single group:

```php
Route::prefix(config('tyro-dashboard.routes.prefix', 'dashboard'))
     ->middleware(['web', 'auth'])
     ->name(DashboardRoute::normalizePrefix(config('tyro-dashboard.routes.name_prefix', 'tyro-dashboard.')))
     ->group(function () {
         // all routes
     });
```

- Prefix is configurable via `TYRO_DASHBOARD_PREFIX` (default: `dashboard`)
- Middleware is `web` and `auth` — all routes require authentication
- Name prefix is configurable — consumers can change it

## Route Name Management

### DashboardRoute Class
- `DashboardRoute::name('users.index')` — generates a full route name with the configured prefix
- `DashboardRoute::normalizePrefix($prefix)` — ensures trailing dot
- `DashboardRoute::translate($name)` — handles legacy route name fallback
- **Never hardcode `tyro-dashboard.` prefix** in route names or `route()` calls. Always use `DashboardRoute`.

### Legacy Route Name Support
- `resolveMissingNamedRoutesUsing` hook registered in the service provider
- If a route name with the current prefix doesn't exist, the hook tries the legacy prefix
- This allows consumers to migrate their route name references gradually

## Route Groups by Access Level

### Public (All Authenticated Users)
- Dashboard home: `GET /` → `DashboardController@index`
- Profile: `GET/PUT/DELETE /profile/*` → `ProfileController`
- User invitations: `GET/POST /invitations` → `InvitationController`
- Leave impersonation: `POST /leave-impersonation` → `UserController`
- Media library: All `/media/*` routes
- Dynamic resources: `GET|POST|PUT|DELETE /resources/{resource}/*`

### Admin-Only (tyro-dashboard.admin middleware)
- Users CRUD: `/users/*`
- Roles CRUD: `/roles/*`
- Privileges CRUD: `/privileges/*`
- Admin invitations: `/invitations/admin/*`
- Audit logs: `/audits/*`
- System settings: `/settings/system/*`

### Dynamic Resources (Own Access Control)
- Resources are NOT behind `tyro-dashboard.admin` middleware
- `ResourceController` handles access control per-resource
- This allows non-admin users to access resources their role permits

## Feature Gating

Routes for disabled features must not be registered:

```php
if (config('tyro-dashboard.features.invitation_system')) {
    Route::prefix('invitations')->group(...);
}

if (config('tyro-dashboard.features.system_settings')) {
    Route::prefix('settings/system')->group(...);
}
```

Feature gating must match the sidebar gating exactly. If a feature's route is registered but the sidebar link is hidden, the URL is still accessible — a security concern.

## Example/Demo Routes

```php
if (app()->environment() !== 'production' || !config('tyro-dashboard.disable_examples')) {
    // example routes
}
```

Demo routes must never be accessible in production environments. The `disable_examples` config provides a manual override.

## Route Model Binding

- User model binding uses the configured user model: `config('tyro-dashboard.user_model')`
- Never hardcode `App\Models\User` in route model binding
- Resource model binding in `ResourceController` resolves dynamically from config or trait

## Anti-Patterns

- **Hardcoding `route('tyro-dashboard.users.index')`** — the prefix is configurable. Use `DashboardRoute`.
- **Adding admin middleware to individual routes** — it belongs on the route group.
- **Registering routes for disabled features** — the URL remains accessible.
- **Changing route names without deprecation** — consumer redirects break.
