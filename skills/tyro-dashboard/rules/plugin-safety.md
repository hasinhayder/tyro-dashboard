# Plugin Safety

## Core Principle

Every extension point is a contract. A framework without plugin safety becomes a walled garden — developers fork instead of extending, and the ecosystem stalls. This rule protects third-party extensions from breaking on framework upgrades.

## Extension Point Contracts

### View Publishing
- **Granular tags are mandatory.** A plugin that publishes `tyro-dashboard-views-admin` must not be broken because the framework changed the user sidebar. Never collapse tags.
- **Tag names are public API.** `tyro-dashboard-config`, `tyro-dashboard-views`, `tyro-dashboard-views-admin`, `tyro-dashboard-views-user`, `tyro-dashboard-sidebar`, `tyro-dashboard-essentials`, `tyro-dashboard-styles`, `tyro-dashboard-scripts`, `tyro-dashboard-theme`
- **Published views survive updates.** The `tyro-dashboard:update` command touches package-original files only. Published files in `resources/views/vendor/tyro-dashboard/` are never overwritten.
- **View override precedence is Laravel's standard.** Published views take priority over package views. Do not implement custom view resolution — it breaks consumer expectations.

### Menu Injection
- **Menu injection is the only supported way to add sidebar items.** Consumers read from `config/menu.adminMenuItems`, `config/menu.commonMenuItems`, `config/menu.userMenuItems`.
- **The array structure is a contract.** Each item has a stable shape (label, route, icon, roles). Adding optional keys is safe. Renaming or removing required keys is breaking.
- **View composers inject menus into sidebar views.** The composer must not mutate menu data — it reads config and passes it to the view.

### View Composer Data
- **Shared variable shapes are contracts.** `$allResources` has a specific structure. Plugins that iterate resources in custom views depend on it.
- **Adding keys to shared arrays is safe.** Removing or renaming keys is breaking.
- **View composers must not depend on services registered later in the service provider boot sequence.** A composer that references middleware or routes that haven't been registered yet will fail.

### Middleware Aliases
- **`tyro-dashboard.admin` is the public alias for EnsureIsAdmin.** Plugins use this to protect custom routes.
- **Core Tyro middleware aliases (`role`, `roles`, `privilege`, `privileges`) are stable.** They are registered by Tyro Core, not Tyro Dashboard.
- **Middleware parameter format is stable.** Comma-separated values. Changing to pipe-separated or array format breaks plugin routes.

### HasCrud Extension
- **`getResourceConfig()` return shape is an extension contract.** Plugins that decorate or extend resources call this method. The return array structure must be stable.
- **`getResourceKey()` return value is an extension contract.** Plugins that reference resources by key depend on stable keys.
- **Field config array structure is an extension contract.** `type`, `label`, `rules`, `options`, `relationship`, `option_label` — these keys must be stable.

### Cookie and LocalStorage Keys
- **`tyro-dashboard-theme` localStorage key is an extension contract.** Plugins that sync theme state depend on it.
- **Browser storage key format is stable.** Changing key names or storage format (JSON vs plain string) breaks plugins.

## Plugin-Safe Development Rules

1. **Add, don't remove.** Adding to an extension point (new menu items, new view composer variables) is safe. Removing is breaking.
2. **Document the extension contract.** If a plugin developer can extend it, write down what they can depend on.
3. **Test with a mock plugin.** Before releasing, test the upgrade path with a plugin that extends every extension point.
4. **Deprecate before removing an extension point.** If a menu injection point must change, deprecate the old key and support both for one major version.
5. **Never break view override precedence.** Consumer-published views must always take priority over package views. Do not implement custom view caching that bypasses Laravel's view finder.

## Anti-Patterns

- **Modifying shared data in view composers.** Composers share data; they do not mutate it. A composer that modifies `$allResources` breaks other composers and plugins.
- **Hardcoding view paths instead of using view namespaces.** `view('tyro-dashboard::dashboard.admin')` not `view(__DIR__ . '/../resources/views/dashboard/admin.blade.php')`. The former respects view overrides; the latter does not.
- **Adding new Blade directives without namespace consideration.** Directive names are global. `@hasRole` is fine. `@role` would conflict with other packages. Always prefix with the domain.
- **Changing the service provider boot sequence without checking plugin impact.** Plugins that extend the framework may depend on services being available at specific points in the boot sequence.
