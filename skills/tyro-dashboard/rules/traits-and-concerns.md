# Traits & Concerns

## Core Principle

Traits are the primary integration surface for consumer applications. A trait that assumes a column exists but doesn't check breaks the application. A trait method that conflicts with a model method causes hard-to-debug errors.

## Distinction: Trait vs Concern

### Traits (`src/Traits/`)
Designed for consumer application models (specifically the `User` model). They must be safe for consumers to use without understanding framework internals.

- `HasProfilePhoto` — Profile photo management for the User model

### Concerns (`src/Concerns/`)
Designed for package-internal model enhancement. Consumers use them but typically don't extend their behavior.

- `HasCrud` — Dynamic CRUD capabilities for any Eloquent model

### Tyro Core Traits
Located in `hasinhayder/tyro/src/Concerns/`:
- `HasTyroRoles` — Role and privilege management for the User model. Tyro Dashboard depends on this but does not own it.

## Trait Rules

### Column Documentation
Every trait must document which database columns it expects:
```
/**
 * Requires: profile_photo_path (string, nullable), use_gravatar (boolean, default false)
 */
```

### Column Checking
Traits must handle missing columns gracefully. Before accessing a column, verify it exists:
```php
if (!Schema::hasColumn($this->getTable(), 'profile_photo_path')) {
    return null; // or sensible default
}
```

### Method Naming
Trait methods must not conflict with common Laravel model method names:
- **Forbidden:** `save()`, `delete()`, `update()`, `fill()`, `create()`, `find()`, `first()`, `get()`, `all()`
- **Preferred:** Descriptive, domain-specific names: `updateProfilePhoto()`, `deleteProfilePhoto()`

### No Required Constructor
Traits must not define constructors. If initialization is needed, use the `boot{TraitName}()` convention:
```php
public static function bootHasProfilePhoto() { ... }
```

## HasCrud Specifics

### Field Caching
- Cache key: `hasinhayder:tyro-dashboard:resource-fields:{modelClass}:{fillableHash}`
- TTL: 6 hours
- Auto-invalidation: fillable array hash change automatically invalidates
- Manual invalidation: `tyro-dashboard:clear-cache`

### Getter Methods
- `getResourceConfig()` — returns complete resource configuration (public API for plugins)
- `getResourceKey()` — returns URL key (public API for plugins)
- `getCachedFieldsOrGenerate()` — internal, returns cached or freshly generated fields

### Property Overrides
- `$resourceFields` — explicit field definitions (replaces auto-detection)
- `$resourceFieldOverrides` — tweaks to auto-detected fields (merges)
- `$resourceTitle` / `$resourceTitleSingular` — custom display names
- `$resourceRoles` / `$resourceReadonly` — access control
- `$resourceUploadDisk` / `$resourceUploadDirectory` — upload settings

## HasProfilePhoto Specifics

### Required Columns
- `profile_photo_path` (string, nullable)
- `use_gravatar` (boolean, default false)

### Photo Processing
- Uses raw GD functions (not Intervention Image — separate from the media system)
- Resize + crop to configured dimensions (default 400×400)
- EXIF orientation correction for JPEG
- Configurable crop position (top/center/bottom)

### Fallback Chain
1. Stored profile photo URL
2. Gravatar URL (if `use_gravatar` is enabled)
3. UI Avatars fallback (initials-based avatar)

### Accessors
- `getProfilePhotoUrlAttribute()` — returns the appropriate photo URL
- `getGravatarUrlAttribute()` — MD5-based Gravatar URL
