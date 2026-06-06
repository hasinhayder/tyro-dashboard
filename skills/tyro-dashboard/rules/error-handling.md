# Error Handling

## Core Principle

Error handling is the difference between a framework that feels polished and one that feels fragile. Audit failures should never prevent a user from saving a record. DB errors should produce human-readable messages, not SQL dumps.

## auditSafely()

All audit log calls in controllers must use `auditSafely()`:

```php
auditSafely(function() use ($model) {
    TyroAudit::log('user.created', ['user_id' => $model->id]);
});
```

### Behavior
- Wraps the closure in a try-catch
- If the closure throws, the exception is logged but not re-thrown
- The controller action continues as if the audit succeeded
- The user never sees an audit failure

### Why It Exists
Audit logging is a secondary concern. If the audit database is down or disk is full, the user's operation (create, update, delete) must still succeed. Audit is important but not more important than the operation it is auditing.

### Where to Use
- Every `TyroAudit::log()` call in a controller
- Not needed in event listeners (listeners already run in a separate context)
- Not needed in artisan commands (commands should fail visibly on errors)

## Database Constraint Violation Parsing

`ResourceController` parses database errors into user-friendly field messages:

### MySQL (SQLSTATE[23000])
- Matches column names in error messages
- Maps to: "The {field} field is required."

### SQLite
- Matches: `NOT NULL constraint failed: {table}.{column}`
- Maps to: "The {field} field is required."

### PostgreSQL
- Matches: `violates not-null constraint`
- Maps to: "The {field} field is required."

### Unrecognized Errors
- Surfaced as: "An error occurred while saving. Please try again."
- Never expose raw SQL to the user
- Log the raw error for debugging

## Service Provider Failures

Service provider registration failures should log, not throw:

- Missing Tyro Core: "Tyro Core package is required but not installed. Run: composer require hasinhayder/tyro"
- Missing Tyro Login: "Tyro Login package is recommended for authentication features."
- Never let a missing optional dependency cause a fatal error

## Graceful Degradation

### Media Processing
- WebP conversion fails → store original, log error, continue
- Thumbnail generation fails → store original + WebP, log error, continue
- Crop/resize fails → original unchanged, log error, show user-friendly message

### Settings
- `.env` file not writable → show clear error: "The .env file is not writable. Check file permissions."
- `config:clear` during settings save is best-effort; the current save response still succeeds if Artisan throws.
- Explicit clear-cache action failure returns a non-fatal "Config clear skipped." JSON message.

### Stock Photo Import
- Provider API down → surface: "Unable to search {provider}. Please try another provider or upload directly."
- Rate limited → retry with backoff, then surface: "Search temporarily unavailable. Please try again in a moment."

## Anti-Patterns

- **Letting audit failures propagate to users.** Always use `auditSafely()` in controllers.
- **Showing raw SQL errors.** Always parse and map to user-friendly messages.
- **Silently swallowing errors without logging.** Always log the actual exception before showing a generic message.
- **Failing the entire operation when a non-critical subsystem fails.** Audit, image processing, and external APIs should degrade gracefully.
