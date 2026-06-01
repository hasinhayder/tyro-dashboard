# Security

## Core Principle

A framework security bug is the same bug in every application built on the framework. Impersonation escalation is an admin-in-any-account vulnerability. Session fixation is a complete-auth-bypass vulnerability. These bugs justify emergency releases.

## Impersonation

### Session Model
- When an admin clicks "Login As" (`UserController::loginAs()`), the original admin's user ID is stored in `session('impersonator_id')`
- The session is then logged in as the target user via `auth()->login($targetUser)`
- The impersonation session key must never be exposed to the impersonated user's session context
- The impersonation banner is a UI convenience, not a security control — it can be hidden in published views

### Logout Interception
- `HandleImpersonation` middleware intercepts logout requests when `session('impersonator_id')` exists
- Instead of logging out, the middleware redirects to `leave-impersonation`
- If the admin session is destroyed while impersonating (e.g., browser close, session timeout), the admin loses access to their original account
- The middleware is pushed to the `web` group — it must execute on every request

### Leave Impersonation
- `UserController::leaveImpersonation()` reads `impersonator_id` from session
- Finds the original admin user via `auth()->login($originalUser)`
- Clears `session()->forget('impersonator_id')`

### Security Constraints
- **Never** expose `impersonator_id` in views, API responses, or logs accessible to the impersonated user
- **Never** allow a non-admin to impersonate (the route must be behind `tyro-dashboard.admin` middleware)
- **Never** allow an admin to impersonate themselves (self-impersonation check)
- **Never** store impersonator information in cookies — cookies are accessible to client-side JavaScript in the impersonated context

## Session Management

### Key Namespacing
- Session keys are namespaced by package where appropriate: `tyro-login.*`, `login.*`
- One historical key is unnamespaced by design: `impersonator_id` (set by `UserController::loginAs()` and read by `HandleImpersonation` middleware). Do not "fix" this to `tyro-dashboard.impersonator_id` — that would break every existing consumer.
- New session keys added by Tyro Dashboard should follow the same unnamespaced, package-distinguishing convention as `impersonator_id` rather than the `tyro-dashboard.*` dot-namespaced style used by other packages
- Never use bare generic keys (`flash`, `user`, `error`) that could collide with consumer application keys
- Session key names are public API — consumers and plugins may read them

### Session Regeneration
- Session must regenerate on: login, logout, OTP phase transitions, 2FA phase transitions
- `$request->session()->regenerate()` is called at each transition point
- This prevents session fixation attacks

### Token Revocation
- On user suspension: all Sanctum tokens are revoked
- On password change: configurable whether to revoke other sessions (`delete_previous_access_tokens_on_login`)
- On 2FA reset: existing sessions are not revoked (the next login will require 2FA setup)

## Brute-Force Lockout

- IP-based lockout tracking via cache: `tyro-login:lockout-attempts:{ip}`
- Failed attempts increment the counter; successful login resets it
- After `max_attempts` (default 5), the IP is locked out for `duration_minutes` (default 15)
- Lockout page shows a countdown timer (optional, configurable)
- Attempts-remaining count can be shown in error messages (optional, configurable)
- Lockout is at the controller level — it does not rely on Laravel's built-in throttle middleware

## Two-Factor Authentication

### Admin Reset
- Admin can reset 2FA for a user via `UserController::reset2FA()`
- Reset clears `two_factor_secret`, `two_factor_recovery_codes`, and `two_factor_confirmed_at`
- This route must be behind `tyro-dashboard.admin` middleware
- Self-service reset is available via `ProfileController::reset2FA()`

### Forced 2FA
- `config('tyro-login.two_factor.forced_roles')` lists role slugs that cannot skip 2FA
- Users with forced roles must set up 2FA before accessing the application
- The skip and ignore mechanisms do not apply to forced roles

### Secret Encryption
- 2FA secrets use the `EncryptedOrPlaintext` cast
- On write: always encrypts via `Crypt::encryptString()`
- On read: tries decryption first, falls back to plaintext (backward compatibility)
- Recovery codes are stored as a JSON-encrypted array

## Audit Trail Integrity

### Mandatory Events
- The following events must always be audited regardless of `features.audit_logs` config:
  - `user.login` and `user.logout`
  - `user.suspended` and `user.unsuspended`
  - Role assignment and removal
- The `features.audit_logs` toggle controls the audit UI visibility, not the underlying logging

### Audit Safety
- All audit calls in controllers use `auditSafely()`: `auditSafely(function() { TyroAudit::log(...); })`
- If the audit system fails (DB down, disk full), the user's action must still succeed
- Audit is important but not more important than the operation it is auditing
- `auditSafely()` catches exceptions and logs them without throwing

### Audit Data Integrity
- Audit entries include: user ID, IP address, user agent, event name, metadata JSON
- Metadata must be JSON-serializable — no resources, closures, or streams
- Audit entries are immutable — never update an audit entry after creation
- CSV export must sanitize data to prevent CSV injection

## Security Checklist for New Features

Before merging a new feature:
1. Does it touch session keys? Verify namespacing and regeneration.
2. Does it change authorization? Verify all four enforcement layers.
3. Does it handle user input? Verify validation and sanitization.
4. Does it affect impersonation? Verify the `HandleImpersonation` middleware still works.
5. Does it add new routes? Verify they are behind appropriate middleware.
6. Does it access the filesystem? Verify path traversal is prevented.
7. Does it use external APIs? Verify TLS, timeouts, and error handling.
