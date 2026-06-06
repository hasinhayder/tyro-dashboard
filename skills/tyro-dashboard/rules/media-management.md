# Media Management

## Core Principle

The media system handles user files — the most irreplaceable data in most applications. A bug that corrupts uploads, loses files, or generates broken thumbnails is a data-loss bug.

## Upload Pipeline

### Processing Flow
1. File uploaded via `POST /media/upload`
2. Original file stored: `{disk}/{directory}/{hash}.{ext}`
3. WebP variant generated: `{hash}.webp` (same directory)
4. Thumbnail generated: `{hash}_thumb.webp` (max 600px width)
5. Media record created in `tyro_media` table

### Storage
- Disk: configurable via `config('tyro-dashboard.uploads.disk')` (default: `public`)
- Directory: configurable via `config('tyro-dashboard.uploads.directory')` (default: `uploads`)
- Naming: MD5 hash of `uniqid('', true)` + original extension
- URLs generated via `Storage::disk($disk)->url($path)` — consumer apps handle URL resolution

### Failure Handling
- If WebP conversion fails, store the original and log the error
- If thumbnail generation fails, store original + WebP and log the error
- Never roll back an upload because a variant generation failed
- Surface errors to the user as warnings, not blocking errors

## Image Processing

### Technology
- Intervention Image v3 with driver abstraction
- GD driver is the default and minimum requirement
- Imagick is supported but must not be a hard dependency
- Driver is configurable — never hardcode `new GdDriver()`

### WebP Conversion
- Supported input: JPEG, PNG, GIF, BMP, TIFF
- PNG transparency must be preserved during WebP conversion
- Alpha channel handling must be tested with transparent PNGs
- Output quality is configurable

### Thumbnail Generation
- Max width: 600px (configurable)
- Maintains aspect ratio
- Output format: WebP
- Naming: `{original_hash}_thumb.webp`

### Crop/Resize
- Uses Cropper.js for the UI selection
- Intervention Image for the server-side operation
- Two modes: replace-in-place (mutates original) and create-new (creates new media record)
- Supports crop (x, y, width, height) and resize (width, height, maintain aspect ratio)

## Stock Photo Import

### Providers
- Unsplash (`api.unsplash.com`)
- Pixabay (`pixabay.com/api`)
- Freepik (`api.freepik.com/v1/resources`)
- Pexels (`api.pexels.com/v1/search`)

### Provider Independence
- Each provider integration must be self-contained
- If Unsplash API is down, Pixabay must still work
- If all providers are down, local upload must still work
- Providers are independently degradable — never make one provider's failure break another

### API Key Resolution
- Check `App\Support\SiteSettings` first (app-level runtime override)
- Fall back to `config('tyro-dashboard.media.api_keys.*')` (env-based)
- SiteSettings class is optional — check for its existence before calling

### Rate Limiting
- 429 responses: retry up to 3 times with exponential backoff
- After max retries: surface user-friendly error
- Respect provider rate limits — do not implement aggressive polling

### Download Safety
- Validate download URLs against an allowlist of expected domains
- Use browser-mimicking headers for download requests
- Downloaded images go through the standard upload pipeline (WebP + thumbnail)

## Starred Images

- `tyro_starred_import_images` table stores favorited external images
- Unique per user + star_key (SHA-256 hash)
- Stores provider metadata: provider name, external ID, URLs, author, payload (JSON)
- `StarredImportImage::toImporterArray()` serializes for API responses
- Starring/unstarring persists across sessions

## MediaPicker Component

### Independence
- The MediaPicker works in any Blade form, not just Tyro Dashboard resource forms
- It is a reusable Blade component, not coupled to the CRUD system
- Registered as `<x-tyro-dashboard-media-picker>`

### Props
- `name`, `id` — form field identification
- `value` — pre-selected media path
- `output` — `original`, `webp`, `thumb`, or `select` (which variant to use)
- `buttonText`, `placeholder` — UI customization
- `preview`, `preview_position`, `preview_width`, `preview_height` — preview configuration
- `circle` — circular preview
- `full_url` — return full URL vs relative path
- `label` — form label text
- `size` — `default`, `medium`, or `small`

### Behavior
- Clicking opens the media picker modal
- Modal shows: search, media grid, upload button, load-more pagination
- Output mode selector: original / WebP / thumb
- Upload progress bar (XHR with progress events)
- Selection sets the associated input value and shows preview
- Delete button clears selection

## Media Model

### Properties
- `$fillable`: `user_id`, `filename`, `path`, `webp_path`, `thumbnail_path`, `disk`, `mime_type`, `size`, `alt_text`, `source_url`
- `$table`: `tyro_media`

### Accessors
- `url` — returns `path` directly (relative path, consumer resolves URL)
- `webp_url` — returns `webp_path` or null
- `thumbnail_url` — returns `thumbnail_path` or falls back to `path`
- `formatted_size` — human-readable size (B/KB/MB)
- `is_image` — boolean, checks `mime_type` starts with `image/`

### Static Helpers
- `Media::thumbnailUrlFrom(?string $urlOrPath)` — finds media by path, returns thumbnail URL
- `Media::webpUrlFrom(?string $urlOrPath)` — finds media by path, returns WebP URL
- Both accept nullable input and return nullable output

## Access Control
- All authenticated users can access the media library
- Delete permission: admins and editors can delete any media; regular users can only delete their own
- **Impersonation scoping:** When an admin is impersonating another user (`session('impersonator_id')` exists), they see only the impersonated user's media, not all media. This prevents accidental exposure of other users' files during impersonation sessions.
- Access control is in the controller, not middleware — media routes are not behind admin middleware

## Cache Flushing
- After media mutations, `App\Support\BlogCache::flushMedia()` is called if available
- BlogCache is an optional consumer class — check existence before calling
- Media cache flushing is separate from RBAC cache flushing
