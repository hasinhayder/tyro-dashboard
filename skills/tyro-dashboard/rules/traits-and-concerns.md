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

### Field Auto-Detection Pipeline
When no explicit `$resourceFields` is defined, `guessFieldConfig()` generates field config from:
1. **Database enum columns** — MySQL `ENUM` parsed via raw `SHOW COLUMNS` → `select` with options
2. **Column name patterns** (evaluated in order):
   - `*_id` → `select` with `relationship` auto-wired
   - `email`, `email_address` → `email` with email validation
   - `password`, `password_hash` → `password`, `hide_in_index: true`
   - `*markdown*` → `markdown` type, `hide_in_index: true`
   - `*description*`, `*bio*`, `*content*`, `*body*`, `*notes*`, `*comment*` → `textarea`, `hide_in_index: true`
   - `*date*` (not `updated_at`/`created_at`) → `date`
   - `*time*` (not timestamps) → `time`
   - `*image*`, `*photo*`, `*picture*`, `*avatar*`, `*file*`, `*document*`, `*attachment*` → `file`, `hide_in_index: true`
   - `price`, `amount`, `cost`, `salary`, `wage` → `number` with `numeric|min:0`
   - `*quantity*`, `*count*`, `*number*`, `*age*`, `*year*`, `*population*`, `*pages*` → `number` with `integer|min:0`
   - `is_*`, `has_*`, `can_*`, `should_*`, `must_*` → `boolean`
   - `*url*`, `*link*`, `*website*` → `url` with URL validation
3. **Database column type** (fallback when no name pattern matches): `boolean`, `integer`/`bigint`/`smallint` → number, `decimal`/`float` → number, `text`/`longtext`/`mediumtext` → textarea, `date` → date, `datetime`/`timestamp` → datetime-local, `time` → time
4. **Default** → `text`

### Relationship Detection
`detectRelationships()` scans public methods on the model via reflection:
- `BelongsTo` → skipped (foreign key already in fillable as `*_id`)
- `BelongsToMany` → `select` with `multiple: true`, `hide_in_index: true`
- `HasMany`/`HasOne` → `select` with appropriate `multiple`, `hide_in_index: true`
- Display column auto-detected: checks for `name`, `title`, `label`, `email`, `code` columns on related model

### Automatic Searchable/Sortable Flags
Fields named `name`, `title`, `code`, `slug` automatically get `searchable: true` and `sortable: true`.

### Nullable Detection
Database column's `nullable` attribute determines `required` vs `nullable` validation rules. String columns with a length constraint get `max:{length}` validation.

### Field Caching
- Cache key: `tyro_dashboard_fields_{md5(modelClass)}_{fillableHash}`
- Hash tracking key: `tyro_dashboard_hash_{md5(modelClass)}` — stores current fillable hash for cleanup
- TTL: 6 hours (21600 seconds)
- Auto-invalidation: fillable array hash change triggers `clearOldCacheEntries()` which removes stale cache entries from previous fillable configurations
- Manual invalidation: `tyro-dashboard:clear-cache` or call `HasCrud::clearFieldCache()` on the model

### Getter Methods
- `getResourceConfig()` — returns complete resource configuration (public API for plugins)
- `getResourceKey()` — returns URL key (public API for plugins)
- `getCachedFieldsOrGenerate()` — internal, returns cached or freshly generated fields
- `clearFieldCache()` — public, clears cached fields and hash for this model (called by `tyro-dashboard:clear-cache`)

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
- Uses raw GD functions (`imagecreatetruecolor`, `imagecopyresampled`) — not Intervention Image (separate from the media system)
- Resize + crop to configured dimensions (default 400×400 from `config('tyro-dashboard.profile_photo.*')`)
- EXIF orientation correction for JPEG (handles orientations 3, 6, 8 with dimension swapping)
- Configurable crop position (top/center/bottom) for both wider and taller originals
- Alpha channel preservation for PNG/WebP via `imagealphablending`/`imagesavealpha`
- Falls back to `$file->storePublicly()` when GD extension is not available

### Fallback Chain
1. Stored profile photo URL
2. Gravatar URL (if `use_gravatar` is enabled)
3. UI Avatars fallback (initials-based avatar)

### Accessors
- `getProfilePhotoUrlAttribute()` — returns the appropriate photo URL
- `getGravatarUrlAttribute()` — MD5-based Gravatar URL
