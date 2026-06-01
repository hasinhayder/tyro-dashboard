# Testing

## Core Principle

A framework package without tests is unmaintainable. Every feature added risks breaking existing features. Tests are the only way to ensure backward compatibility across versions.

## Testing Philosophy

### Integration Over Unit
- **Prefer integration tests.** Test how Tyro Dashboard composes with Tyro Core + Tyro Login + Laravel.
- Unit tests are valuable for isolated logic (field type guessing, constraint parsing, color conversion) but insufficient for verifying the framework works end-to-end.
- The framework's value is in composition — tests must verify the composition.

### Feature Tests
Feature tests must cover the complete HTTP lifecycle:
```
request → middleware → controller → view → response
```

A test that only checks the controller return value misses middleware failures, view rendering errors, and response format issues.

## Required Test Coverage

### Authorization Tests
- User with correct role CAN access protected routes
- User with incorrect role CANNOT access (gets redirect or 403)
- User with wildcard `*` role CAN access everything
- User with correct privilege CAN access
- User with incorrect privilege CANNOT access
- Cache invalidation: remove role, verify access is immediately revoked (not after TTL)
- Self-action prevention: user cannot suspend/delete themselves
- Protected roles cannot be deleted through the UI

### CRUD Tests
- Auto-detection works for models with `$fillable` only (no `$resourceFields`)
- Explicit field config works (with `$resourceFields`)
- Field overrides merge correctly (with `$resourceFieldOverrides`)
- File uploads are stored and referenced correctly
- BelongsTo relationships render as select dropdowns
- BelongsToMany relationships sync correctly after save
- Resource access control: admin role has full access, readonly role has view-only, no role has no access
- Search filters correctly across searchable fields
- Boolean fields handle unchecked state (missing from request → false)

### Media Tests
- Upload produces original + WebP + thumbnail
- PNG transparency is preserved in WebP convert
- Crop and resize work in both replace-in-place and create-new modes
- Stock photo import handles API errors gracefully
- Delete removes files from disk
- MediaPicker component renders and selects media
- Permission: regular user cannot delete another user's media

### Settings Tests
- `gatherSettings()` returns all expected config values
- `update()` writes valid values to `.env`
- Default values are stripped from `.env`
- Dashboard colors save to JSON
- `config:clear` runs after save
- Validation rejects invalid values (wrong hex format, out-of-range values)

### Impersonation Tests
- Admin can login as another user
- `impersonator_id` is stored in session
- Impersonated user's session does not expose `impersonator_id`
- Logout during impersonation redirects to leave-impersonation
- Leave-impersonation restores the admin session
- Non-admin cannot impersonate

## Test Organization

- Tests live in the package's `tests/` directory if the package has its own test suite
- Feature tests that require a full Laravel application should use Orchestra Testbench or run within a test application
- Database tests use an in-memory SQLite database for speed
- Media tests use a local disk driver, not cloud storage

## Test Data

- Use model factories for test data where possible
- Seed default roles and privileges before authorization tests
- Create test users with specific roles for each test — do not share users across tests
- Clean up uploaded files after media tests

## Regression Testing

When a bug is fixed:
1. Write a test that reproduces the bug (fails before the fix)
2. Apply the fix
3. Verify the test passes
4. The test stays as regression protection
