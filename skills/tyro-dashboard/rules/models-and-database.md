# Models & Database

## Core Principle

Models and migrations are the data foundation of the framework. Wrong schema decisions become permanent when consumer applications run migrations. Adding columns is safe. Changing or removing columns requires careful migration planning.

## Package Models

### Media (`tyro_media`)
- `$fillable`: `user_id`, `filename`, `path`, `webp_path`, `thumbnail_path`, `disk`, `mime_type`, `size`, `alt_text`, `source_url`
- `uploader()` → `BelongsTo` configurable user model
- Accessors: `url`, `webp_url`, `thumbnail_url`, `formatted_size`, `is_image`
- Static helpers: `thumbnailUrlFrom()`, `webpUrlFrom()` — both accept nullable and return nullable
- URL resolution logic in static helpers: if input is an absolute URL, strips `/storage/` prefix to extract the relative path, then queries `tyro_media` by path. If no matching record found, `thumbnailUrlFrom()` returns the original URL and `webpUrlFrom()` returns null

### StarredImportImage (`tyro_starred_import_images`)
- `$fillable`: `user_id`, `star_key`, `provider`, `external_id`, `alt`, `author`, `thumb_url`, `preview_url`, `download_url`, `download_location`, `source_url`, `payload`, `starred_at`
- `$casts`: `payload` → `array`, `starred_at` → `datetime`
- Unique index on `[user_id, star_key]`
- `toImporterArray()` — serializes for API responses

## Database Conventions

### Table Naming
- Snake_case plural with `tyro_` prefix: `tyro_media`, `tyro_starred_import_images`
- The prefix prevents collisions with consumer application tables
- Pivot tables use alphabetical singular naming: `privilege_role`, `user_roles`

### Column Naming
- Foreign keys: `{relation}_id` (singular)
- Timestamps: standard Laravel `created_at`, `updated_at`
- Suspension: `suspended_at` (nullable timestamp), `suspension_reason` (nullable string)
- 2FA: `two_factor_secret`, `two_factor_recovery_codes`, `two_factor_confirmed_at`

### Foreign Keys
- `foreignId('user_id')->constrained()->cascadeOnDelete()` — standard pattern
- When a user is deleted, their media, starred images, and other owned records cascade
- Pivot tables: `foreignId('user_id')->constrained()->cascadeOnDelete()` for both sides

## Migration Rules

### Timestamp Ordering
- Migrations are timestamped for ordering: `2025_01_01_000001_create_media_table.php`
- Dependencies between migrations are documented in migration comments
- Consumer applications run migrations in timestamp order — never depend on a migration from another package being run first without documenting it

### Additive Changes Only (Within Major Version)
- Adding new columns is safe
- Adding new tables is safe
- Changing column types requires: create new column in migration N, deprecate old column, remove old column in migration N+1 (next major version)
- Removing columns is breaking — nullable them first, remove in next major version

### Pivot Tables
- `user_roles`: `user_id` + `role_id` (unique constraint)
- `privilege_role`: `role_id` + `privilege_id` (unique constraint)
- Pivot model boot events handle cache invalidation

## Accessor Rules

- Accessors return computed values without side effects
- `$media->url` returns a path string — it does not touch the filesystem
- `$media->is_image` checks `mime_type` — it does not read the file
- Accessors must be fast — they may be called in loops during table rendering

## User Model Integration

- The framework does not provide a User model — it extends the consumer's
- `config('tyro-dashboard.user_model')` resolves the user class
- User model must have `HasTyroRoles` trait (from Tyro Core)
- User model may have `HasProfilePhoto` trait (from Tyro Dashboard)
- Consumer adds columns via package migrations: `profile_photo_path`, `use_gravatar`, `suspended_at`, `suspension_reason`, 2FA columns
