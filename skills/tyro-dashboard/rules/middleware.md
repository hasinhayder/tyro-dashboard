# Middleware

## Core Principle

Middleware is the first line of defense for route protection. Wrong registration, wrong aliasing, or wrong execution order creates security gaps.

## EnsureIsAdmin

**Alias:** `tyro-dashboard.admin`
**Class:** `HasinHayder\TyroDashboard\Http\Middleware\EnsureIsAdmin`

### Behavior
- If user is not authenticated: redirects to login using a three-step fallback chain: `tyro-login.login` route → `login` route → `/login` path
- If user is authenticated but not admin: checks `auth()->user()->tyroRoleSlugs()` intersects with `config('tyro-dashboard.admin_roles')`, redirects to dashboard with error flash message
- Does not throw an exception — admin panel access failures are UX issues (user is already authenticated), not security violations
- Also checks for the `*` wildcard role which grants admin access

### Route Application
- Applied to route groups, not individual routes
- All admin panel routes (users, roles, privileges, settings, audits, admin invitations) are behind this middleware
- Dynamic resource routes are NOT behind this middleware — they have their own access control

## HandleImpersonation

**Class:** `HasinHayder\TyroDashboard\Http\Middleware\HandleImpersonation`
**Registration:** Pushed to `web` middleware group

### Behavior
- Checks for `$request->routeIs('tyro-login.logout')` AND `session('impersonator_id')` on every request
- If both conditions are met: redirects to `leave-impersonation` route instead of logging out
- Must execute on EVERY request to intercept logout
- **Coupling note:** The middleware hardcodes a dependency on the `tyro-login.logout` route name from `hasinhayder/tyro-login`. If that package changes its route name, the impersonation system breaks silently. This coupling is unavoidable but must be documented.

### Why Web Group
- Pushing to `web` group ensures the middleware runs on all web routes, not just dashboard routes
- If a consumer removes this middleware, the impersonation security model breaks
- It must not be opt-in

## Core Tyro Middleware

Registered by Tyro Core's service provider, NOT Tyro Dashboard:

- `role` → `EnsureTyroRole` — user must have ALL specified roles (AND)
- `roles` → `EnsureAnyTyroRole` — user must have ANY specified roles (OR)
- `privilege` → `EnsureTyroPrivilege` — user must have ALL specified privileges (AND)
- `privileges` → `EnsureAnyTyroPrivilege` — user must have ANY specified privileges (OR)

### Parameter Format
- Comma-separated values: `middleware('role:admin,super-admin')`
- The format is stable. Do not change to pipe-separated or array format.
- On failure: throws `AuthorizationException('ACCESS DENIED.')`

## Middleware Registration

### In Service Provider
```php
// Alias (named middleware)
$router->aliasMiddleware('tyro-dashboard.admin', EnsureIsAdmin::class);

// Web group push (runs on every request)
$router->pushMiddlewareToGroup('web', HandleImpersonation::class);
```

### Execution Order
1. `web` group middleware (Laravel's: session, CSRF, etc.)
2. `HandleImpersonation` (in web group)
3. `auth` middleware
4. `tyro-dashboard.admin` middleware (if applied to route)
5. Controller action

## Adding New Middleware

1. Create the class in `src/Http/Middleware/`
2. Register the alias in the service provider via `aliasMiddleware()`
3. If it must run on all requests, push to the `web` group
4. Document the middleware for consumers who will use it to protect custom routes
5. The alias name is part of the public API — choose carefully
