# Authorization

## Core Principle

Authorization is the security foundation of every Tyro Dashboard application. A missing cache invalidation, a bypassable middleware, or an incorrect privilege check creates vulnerabilities that affect every user of every application built on this framework.

## The RBAC Model

### Data Model
- **Users** belong to many **Roles** through `user_roles` pivot
- **Roles** belong to many **Privileges** through `privilege_role` pivot
- **Privileges are never assigned directly to users.** Always through roles. This is transitive inheritance — the framework's core authorization invariant.

### The Wildcard
- The `*` role slug grants all access — equivalent to having every role
- The `*` privilege slug grants all access — equivalent to having every privilege
- Any authorization check must handle the wildcard before applying specific rules

### Protected Entities
- `config('tyro-dashboard.protected.roles')` lists role slugs that cannot be deleted (default: `admin`, `super-admin`, `user`)
- `config('tyro-dashboard.protected.users')` lists user IDs that cannot be deleted
- Protected role/user checks must happen in the controller `destroy()` methods, not in the model
- Self-action prevention: a user cannot suspend themselves, remove their own admin role, or delete their own account

## The Enforcement Hierarchy

Authorization is enforced at four layers. Each layer must be independently correct.

### Layer 1 — Route Middleware (Route-Level)
- `role:admin,super-admin` — user must have ALL specified roles (AND)
- `roles:admin,editor` — user must have ANY specified roles (OR)
- `privilege:users.manage` — user must have ALL specified privileges (AND)
- `privileges:users.view,billing.view` — user must have ANY specified privileges (OR)
- `tyro-dashboard.admin` — user must have a role in `config('tyro-dashboard.admin_roles')`
- Core Tyro middleware is registered by Tyro Core's service provider. Tyro Dashboard must not re-register.

### Layer 2 — Controller Access Control (Resource-Level)
- `ResourceController::hasAccess()` checks user's `tyroRoleSlugs()` against the resource's `roles` and `readonly` arrays
- Empty `roles` + `readonly` = admin-only (secure by default)
- `ResourceController::isReadonly()` prevents write actions for readonly roles

### Layer 3 — Model can() Override (Model-Level)
- `$user->can('users.manage')` checks: privilege slug → role slug → Laravel Gate
- This override bridges Tyro's RBAC with Laravel's native `can()` method
- The order matters: privilege checks first (more specific), then role checks, then Gates

### Layer 4 — Blade Directives (View-Level)
- `@hasRole('admin')`, `@hasAnyRole('admin', 'editor')`, `@hasAllRoles('admin', 'super-admin')`
- `@hasPrivilege('users.manage')`, `@hasAnyPrivilege('users.view', 'users.edit')`, `@hasAllPrivileges('users.view', 'users.create')`
- `@userCan('users.manage')` — delegates to the `can()` override
- Directives are presentation-level enforcement. Always pair with middleware for security.

## Cache Invalidation

`TyroCache` is the authorization cache layer. Stale caches mean users retain privileges after revocation.

### When to Invalidate
- **User ↔ Role change:** `TyroCache::forgetUser($userId)` — invalidates that user's role/privilege caches
- **Role ↔ Privilege change:** `TyroCache::forgetUsersByRole($role)` — finds all users of that role, invalidates each
- **Role name/slug change:** Same as Role ↔ Privilege change — users of that role may have cached the old slug
- **Bulk cache clear:** `TyroCache::forgetAllUsersWithRoles()` — full flush, used sparingly

### How Invalidation Works
- Pivot models (`UserRole`, `RolePrivilege`) fire cache invalidation in their `booted()` methods via `saved` and `deleted` events
- Any new pivot relationship must follow this pattern
- The cache key format is `tyro:user-{id}:roles` and `tyro:user-{id}:privileges`
- Cache TTL is configurable via `config('tyro.cache.ttl')` — default 300 seconds
- Runtime version bumping: when cache is invalidated mid-request, an in-memory version counter ensures the same request sees fresh data

### Anti-Patterns
- **Skipping cache invalidation "for performance."** Stale permissions are a security vulnerability. Never skip.
- **Invalidating only the direct user.** When a role's privileges change, every user with that role must be invalidated.
- **Direct cache manipulation.** Always use `TyroCache` methods. Never call `Cache::forget()` directly.

## Middleware Registration

- `EnsureIsAdmin` is registered as `tyro-dashboard.admin` in Tyro Dashboard's service provider
- Core Tyro middleware (`EnsureTyroRole`, `EnsureAnyTyroRole`, `EnsureTyroPrivilege`, `EnsureAnyTyroPrivilege`) is registered by Tyro Core's service provider
- Middleware aliases are the public names consumers use. Changing an alias breaks consumer routes.

## Blade Directive Registration

- Directives are registered in Tyro Core's service provider via `registerBladeDirectives()`
- Each directive has camelCase and lowercase aliases: `@hasRole` / `@hasrole`
- Directives receive the authenticated user via `auth()->user()` — they do not accept a user parameter
- Adding a new directive requires registration in Tyro Core. Coordinate cross-package.

## can() Override

The `HasTyroRoles::can()` override:

```php
public function can($ability, $arguments = []): bool {
    if (is_string($ability) && $this->hasPrivilege($ability)) return true;
    if (is_string($ability) && $this->hasRole($ability)) return true;
    return Gate::forUser($this)->check($ability, $arguments);
}
```

- The order is fixed: privilege → role → Gate
- `$ability` is checked as both a privilege slug and a role slug
- If neither matches, Laravel's native Gate system handles it
- This override must never be removed or reordered — consumers depend on the behavior

## Security Testing Requirements

Every authorization change must be tested with:
1. User with correct role — CAN access
2. User with incorrect role — CANNOT access
3. User with wildcard `*` role — CAN access everything
4. User with correct privilege — CAN access
5. User with incorrect privilege — CANNOT access
6. Cache invalidation: remove role, verify access is immediately revoked (not after cache TTL)
