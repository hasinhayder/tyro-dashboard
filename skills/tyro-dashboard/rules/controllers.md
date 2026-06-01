# Controllers

## Core Principle

Controllers are where every HTTP request becomes a response. Inconsistent controller patterns force developers to read source code before they can use the framework. Consistent patterns mean developers can predict behavior.

## BaseController

All dashboard controllers must extend `BaseController`. It provides:

- `getUserModel()` — returns the configured user model class from `config('tyro-dashboard.user_model')` with fallback to `config('tyro.models.user')` then `App\Models\User`
- `isAdmin()` — checks if the authenticated user has a role in `config('tyro-dashboard.admin_roles')`
- `getViewData()` — returns shared view data array

A controller that does not extend `BaseController` loses access to shared behavior and creates inconsistency.

## Action Method Patterns

### Index (List)
```php
public function index() {
    $records = Model::query()->paginate($perPage);
    return view('tyro-dashboard::resource.index', compact('records'));
}
```

### Create (Form)
```php
public function create() {
    return view('tyro-dashboard::resource.create');
}
```

### Store (Save)
```php
public function store(Request $request) {
    $validated = $request->validate($rules);
    // Separate m2m fields
    $m2mFields = $request->only(['many_to_many_field']);
    $model = Model::create($request->except(['many_to_many_field']));
    // Sync after save
    $model->relationship()->sync($m2mFields['many_to_many_field']);
    // Audit safely
    auditSafely(function() use ($model) { TyroAudit::log(...); });
    return redirect()->route(DashboardRoute::name('resource.index'))->with('success', 'Created');
}
```

### Edit (Form)
```php
public function edit($id) {
    $record = Model::findOrFail($id);
    return view('tyro-dashboard::resource.edit', compact('record'));
}
```

### Update (Save)
```php
public function update(Request $request, $id) {
    $model = Model::findOrFail($id);
    // Handle boolean checkboxes: missing = false
    if (!$request->has('boolean_field')) { $request->merge(['boolean_field' => false]); }
    // Handle password: empty = skip
    // Separate m2m, update model, sync
}
```

### Destroy (Delete)
```php
public function destroy($id) {
    // Check protected resources
    if (in_array($id, config('tyro-dashboard.protected.users'))) { abort(403); }
    $model = Model::findOrFail($id);
    $model->delete();
}
```

## Response Conventions

### Blade Views (Primary)
- Most controller actions return Blade views — the standard pattern for page navigation
- Redirects use `DashboardRoute::name()` for route name generation — never hardcode route names
- Flash messages use `->with('success', '...')`, `->with('error', '...')`, `->with('warning', '...')`, `->with('info', '...')`
- The flash-messages partial renders these in the configured notification style

### JSON Responses (AJAX Endpoints)
Several controllers return `JsonResponse` for AJAX interactions:
- `MediaController` — all media operations (upload, crop, rename, alt text, starred images, search, import) return JSON
- `SystemSettingsController::update()` — settings save via AJAX, returns JSON
- `SystemSettingsController::clearConfigCache()` — returns JSON
- `WidgetsController` — proxy endpoints (xkcd, stocks, fx, flights) return JSON
- The API layer for REST consumers is in Tyro Core (`hasinhayder/tyro`)

## Authorization Pattern

- Admin panel controllers use `tyro-dashboard.admin` middleware on the route group — they do not check `isAdmin()` in every method
- `ResourceController` is the exception — it handles its own access control for per-resource role checks
- Destroy methods check protected resources from config before deleting
- Self-action prevention: controllers check that users are not suspending/deleting themselves

## Audit Pattern

Every controller action that modifies data must audit. `BaseController` provides a `auditSafely()` method:

```php
protected function auditSafely(string $event, $auditable, ?array $oldValues, ?array $newValues): void {
    try {
        TyroAudit::log($event, $auditable, $oldValues, $newValues);
    } catch (\Throwable $e) {
        // Log the exception but never break the user flow
    }
}
```

Usage in controllers:
```php
$this->auditSafely('user.created', $user, null, ['email' => $user->email]);
$this->auditSafely('role.deleted', $role, ['name' => $role->name], null);
```

- `auditSafely()` catches all `Throwable` silently — audit failure never breaks the user flow
- The method signature is: `(string $event, $auditable, ?array $oldValues, ?array $newValues)`
- `$auditable` is typically the Eloquent model being acted upon
- `$oldValues` captures state before the change (for updates/deletes)
- `$newValues` captures state after the change (for creates/updates)
- Event names follow pattern: `{resource}.{action}` (e.g., `user.created`, `role.deleted`, `user.suspended`)

## ResourceController Special Case

`ResourceController` handles ALL dynamic CRUD resources. It is not behind admin middleware:

- `hasAccess($config)` — checks user roles against resource's `roles` and `readonly` arrays
- `isReadonly($config)` — returns true if user is in `readonly` but not in `roles`
- Readonly users see the index and show views but cannot create, edit, or delete
- Resources with empty `roles` + `readonly` are admin-only
- `sanitizeRichtext($content)` — uses `Purifier::clean()` if HTML Purifier is available, else `strip_tags()` with an extensive allowlist of safe HTML tags
- `getModelsWithTrait()` — scans `app/Models/` via reflection for classes with `getResourceConfig()` and `getResourceKey()` (mirrors the service provider scanning)
- Cross-database constraint error parsing in `store()` and `update()`: handles MySQL (error codes 1048, 1364), SQLite (`NOT NULL constraint failed`), PostgreSQL (`violates not-null constraint`) — maps to user-friendly field error messages

## Additional Controllers

### MediaController
- 18 methods handling all media operations (upload, browse, crop/resize, rename, alt text, stock photo search/import, starred images)
- All methods return JSON responses (AJAX-driven UI)
- Access control: admins see all media; non-admins see only their own; admins impersonating see the impersonated user's media only
- Detailed in `rules/media-management.md`

### AuditController
- `index()` — paginated audit log listing with filtering
- `show()` — single audit entry detail
- `export()` — CSV export with CSV injection sanitization
- `bulkDestroy()` — bulk delete audit entries
- `flush()` — clear all audit entries
- `ensureAuditAvailable()` — triple-check: `features.audit_logs` config AND `tyro.audit.enabled` config AND `AuditLog` class exists AND audit table exists

### ProfileController
- `index()` — current user's profile view
- `update()` — update profile data (name, email, password)
- `updatePhoto()` — upload/update profile photo
- `deleteUserPhoto()` — admin deletes another user's photo (`DELETE users/{id}/photo`)
- `reset2FA()` — self-service 2FA reset
- `setup2FA()` — redirects to `tyro-login.two-factor.setup` after clearing 2FA data and `tyro_2fa_ignore_{id}` cookie
- Photo URL normalization strips app URL and storage path prefixes before saving

### InvitationController
- Consumer-facing invitation acceptance flows
- Delegates to Tyro Login for the underlying invitation system

### RoleController / PrivilegeController
- Standard CRUD plus `removeUser($id, $userId)` and `removeRole($id, $roleId)` for detaching pivot relationships

### Example Controllers (Non-Production Only)
- `ComponentsController` — renders example UI components
- `WidgetsController` — renders widgets page; also proxies third-party APIs (XKCD, stocks, FX rates, flight data) as same-origin endpoints to avoid CORS
- `XComponentsController` — conditionally loaded only when `hasinhayder/tyro-dashboard-components` package is installed
- All example routes are gated: `!config('disable_examples') && !app()->environment('production')`
