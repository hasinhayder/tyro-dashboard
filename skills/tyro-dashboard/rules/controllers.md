# Controllers

## Core Principle

Controllers are where every HTTP request becomes a response. Inconsistent controller patterns force developers to read source code before they can use the framework. Consistent patterns mean developers can predict behavior.

## BaseController

All dashboard controllers must extend `BaseController`. It provides:

- `getUserModel()` — returns the configured user model class from `config('tyro-dashboard.user_model')`
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

- All controllers return Blade views — never JSON API responses
- The API layer is in Tyro Core (`hasinhayder/tyro`)
- Redirects use `DashboardRoute::name()` for route name generation — never hardcode route names
- Flash messages use `->with('success', '...')`, `->with('error', '...')`, `->with('warning', '...')`, `->with('info', '...')`
- The flash-messages partial renders these in the configured notification style

## Authorization Pattern

- Admin panel controllers use `tyro-dashboard.admin` middleware on the route group — they do not check `isAdmin()` in every method
- `ResourceController` is the exception — it handles its own access control for per-resource role checks
- Destroy methods check protected resources from config before deleting
- Self-action prevention: controllers check that users are not suspending/deleting themselves

## Audit Pattern

Every controller action that modifies data must audit:

```php
auditSafely(function() use ($model, $action) {
    TyroAudit::log($action, [
        'model_type' => get_class($model),
        'model_id' => $model->id,
        'changes' => $model->getChanges(),
    ]);
});
```

- `auditSafely()` catches exceptions silently — audit failure never breaks the user flow
- Audit metadata must be JSON-serializable — no closures, resources, or streams
- Event names follow pattern: `{resource}.{action}` (e.g., `user.created`, `role.deleted`)

## ResourceController Special Case

`ResourceController` handles ALL dynamic CRUD resources. It is not behind admin middleware:

- `hasAccess($config)` — checks user roles against resource's `roles` and `readonly` arrays
- `isReadonly($config)` — returns true if user is in `readonly` but not in `roles`
- Readonly users see the index and show views but cannot create, edit, or delete
- Resources with empty `roles` + `readonly` are admin-only
