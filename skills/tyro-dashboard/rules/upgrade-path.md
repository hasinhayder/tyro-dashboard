# Upgrade Path

## Core Principle

Upgrade pain is the number one reason developers abandon frameworks. A minor version upgrade must not require application code changes. Every friction point in the upgrade path costs the ecosystem users.

## The Deprecation Cycle

For any breaking change within a major version (1.x):

1. **Version 1.N:** Trigger a deprecation warning. The old API must continue to work. Document the migration path.
2. **Version 1.N+1:** Keep the deprecation warning. Ensure all internal usage has migrated. The old API still works.
3. **Version 2.0:** Remove the deprecated API.

This means a breaking change takes at minimum two versions to remove.

## What Requires Deprecation

### Config Changes
- Renaming a config key: support both old and new keys in 1.N, warn on old key usage, remove old key in 2.0
- Removing a config key: support it with a deprecation warning in 1.N, remove in 2.0
- Changing a config key's default value: acceptable in a minor version if it does not break existing applications

### Method Signature Changes
- Renaming a public method: add the new method, deprecate the old method (call the new one internally), remove old in 2.0
- Adding a required parameter: add optional parameter with default in 1.N, make required in 2.0
- Changing a return type: add new return type alongside old in 1.N (union type if possible), remove old in 2.0
- Removing a method: deprecate in 1.N, remove in 2.0

### Route Changes
- Renaming a route: register the route under both names in 1.N, deprecate the old name, remove in 2.0
- Removing a route: return a 410 Gone or redirect in 1.N, remove in 2.0
- Changing a route prefix: changing the default is breaking. Make it configurable with the old value as the default.

### View Changes
- Renaming a Blade section: support both names in 1.N, deprecate the old name, remove in 2.0
- Removing a section: keep the yield with empty default in 1.N, remove in 2.0
- Removing a view composer variable: keep sharing the variable with deprecation warning in 1.N, remove in 2.0

### Database Changes
- Changing a column type: create a new migration that adds a new column, deprecate the old column, remove in 2.0 migration
- Removing a column: nullable the column in 1.N migration, remove in 2.0 migration
- Renaming a table: support both table names via model configuration in 1.N, remove old in 2.0

## The Update Command

`tyro-dashboard:update` is the primary upgrade tool. It must:

1. **Be non-destructive to consumer customizations.** Never overwrite files in `resources/views/vendor/tyro-dashboard/`.
2. **Update only package-original files.** Views, styles, scripts, config from the package, not consumer overrides.
3. **Report what changed.** After running, list updated files so the consumer knows what to check.
4. **Be composable.** Sub-commands (`update-style`, `update-script`, `update-config`) exist for granular updates.
5. **Handle partial failures.** If one file fails to update, continue with others and report errors at the end.

## Minor Version Policy

A minor version (1.N → 1.N+1):

- **Must NOT** require application code changes
- **Must NOT** drop support for a Laravel minor version without deprecation
- **Must NOT** remove or rename public API
- **May** add new features, config keys, route names
- **May** change internal implementations
- **May** add new dependencies (with `suggests` in composer.json if optional)

## The Version Command

`tyro-dashboard:version` must report:
- Current framework version
- Laravel version compatibility
- PHP version requirements
- Dependency status (installed versions)
- Any breaking changes in the current version
