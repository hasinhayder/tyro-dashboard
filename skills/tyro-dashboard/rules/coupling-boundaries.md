# Coupling Boundaries

## Core Principle

Tight coupling is invisible until it breaks. Every hard dependency on a sibling package's internals, a Laravel implementation detail, or an external service's undocumented behavior is a future outage. Coupling boundaries are insurance against upstream changes.

## Package Dependency Boundaries

### hasinhayder/tyro (Tyro Core)
- **Allowed:** Public models (`Role`, `Privilege`, `AuditLog`), public traits (`HasTyroRoles`), public middleware, public config (`config/tyro.php`), public service `TyroAudit`, public cache class `TyroCache`, public helper `PasswordRules`
- **Forbidden:** Private methods on any Tyro Core class, accessing Tyro Core's internal cache keys directly (use `TyroCache` methods), assuming Tyro Core's migration order, hardcoding Tyro Core's table names (use model `getTable()`), bypassing `TyroAudit` to write directly to the audit_logs table
- **Detection:** If a Tyro Dashboard class has a `use` statement for a Tyro Core class that is not in the public API list above, it is coupled to internals.

### hasinhayder/tyro-login (Tyro Login)
- **Allowed:** Public controllers for redirect purposes (route names), public config (`config/tyro-login.php`), public models (`InvitationLink`, `InvitationReferral`, `SocialAccount`), public traits (`HasTwoFactorAuth`)
- **Forbidden:** Accessing Tyro Login's session keys directly (use Tyro Login's public methods), calling private methods on Tyro Login controllers, assuming Tyro Login's authentication flow internals, hardcoding Tyro Login's view paths (use route names to redirect)
- **Detection:** If Tyro Dashboard code calls a Tyro Login controller method directly instead of redirecting to its route, it is coupled to internals.

## Laravel Dependency Boundaries

### Allowed (Contracts & Facades)
- Laravel contracts (`Illuminate\Contracts\*`)
- Laravel facades (`Auth`, `Cache`, `Config`, `DB`, `Event`, `File`, `Hash`, `Log`, `Mail`, `Request`, `Route`, `Schema`, `Session`, `Storage`, `Validator`, `View`)
- Standard Eloquent patterns (`Model`, `BelongsTo`, `HasMany`, `BelongsToMany`)
- Blade directives and view rendering
- Artisan console infrastructure

### Forbidden (Implementation Details)
- Accessing `Illuminate\*` classes that are not covered by contracts
- Relying on undocumented Laravel internal behavior that could change in a minor release
- Using string class references to Laravel internals that may be refactored (e.g., specific auth guard implementations)
- Assuming a specific cache driver is available (always use `Cache` facade, never `Redis::` or `Memcached::` directly)

## Image Processing Boundaries

- **Driver:** Use Intervention Image v3's driver abstraction. Never hardcode `new GdDriver()` — use the configured driver.
- **Format:** WebP conversion is the framework's default. JPEG/PNG/GIF/TIFF/BMP input must be supported.
- **Transparency:** PNG transparency must survive WebP conversion. Test with transparent PNGs.
- **No Imagick requirement:** GD is the minimum requirement. Imagick support is optional.

## External API Boundaries

### Stock Photo Providers (Unsplash, Pixabay, Freepik, Pexels)
- **Each provider is independently degradable.** If Unsplash API changes or is down, Pixabay, Freepik, and Pexels must continue to work. Each provider's integration must be self-contained.
- **API key resolution uses the app-override pattern.** Check `App\Support\SiteSettings` first, fall back to config. Never require a specific class name — make it configurable.
- **Rate limit handling:** 429 responses must be retried with exponential backoff (max 3 retries). After max retries, surface a user-friendly error.
- **Domain allowlist:** Downloaded images must come from expected domains. Validate URLs before downloading.

### CDN Dependencies (JavaScript libraries)
- **EasyMDE, Quill.js, Cropper.js, marked.js, DOMPurify** are loaded from CDN via view partials.
- **CDN URLs are configurable.** Consumers must be able to host these libraries locally or use a different CDN.
- **Never bundle JS dependencies in the package.** Bundled dependencies create version conflicts with consumer applications.
- **Version pinning in CDN URLs.** Use specific versions (`quill@2.0.0`) not `@latest`. Surprise major version changes break consumer applications.

## Consumer Application Boundaries

- **User model:** Resolved via `config('tyro-dashboard.user_model')`. Default is `App\Models\User`. Never hardcode the user model class.
- **Site settings:** `App\Support\SiteSettings` is an optional consumer class. Check for its existence before calling it. Fall back to config.
- **Blog cache:** `App\Support\BlogCache::flushMedia()` is an optional consumer class. Check for its existence before calling it.
- **Custom menu items:** Injected via `config/menu.php`. Never assume a specific menu structure.

## Detection Rules

When reviewing code for coupling violations, flag:
1. `use` statement for a Tyro Core or Tyro Login class not in the allowed list
2. Direct `new` instantiation of a driver or implementation class (use the abstraction)
3. Hardcoded string class references to consumer application classes (`App\Models\User`)
4. Assumptions about which cache driver, queue driver, or filesystem driver is available
5. CDN URLs without version pinning
