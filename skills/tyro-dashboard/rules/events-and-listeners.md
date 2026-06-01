# Events & Listeners

## Core Principle

Event listeners run on every login and logout. A slow or failing listener degrades the authentication experience for every user. Feature-gated listeners must not fire when the feature is disabled.

## Current Listeners

### Login Audit Listener
```php
Event::listen(Login::class, function (Login $event) {
    if (!config('tyro-dashboard.features.audit_logs')) return;
    $user = $event->user;
    if ($user && class_exists(TyroAudit::class)) {
        TyroAudit::log('user.login', $user, null, ['email' => $user->email ?? 'unknown']);
    }
});
```

### Logout Audit Listener
```php
Event::listen(Logout::class, function (Logout $event) {
    if (!config('tyro-dashboard.features.audit_logs')) return;
    $user = $event->user;
    if ($user && class_exists(TyroAudit::class)) {
        TyroAudit::log('user.logout', $user, null, ['email' => $user->email ?? 'unknown']);
    }
});
```

## Registration

Listeners are registered in `TyroDashboardServiceProvider::registerEventListeners()` — NOT in `EventServiceProvider`. The framework uses closures for listener registration because the listener logic is simple and tightly coupled to the event.

## Feature Gating

Listeners check `config('tyro-dashboard.features.audit_logs')` before executing. When the feature is disabled:

- The listener closure runs but returns immediately — this is intentional, not a performance concern
- The cost of a config check + early return is negligible
- Alternative (conditional registration) would require the service provider to re-register listeners when config changes

## Listener Weight

- Listeners must be lightweight — one method call, no database queries beyond the audit insert
- Heavy processing (generating reports, sending notifications, updating aggregates) belongs in queued jobs
- If a listener needs more than 3 lines of logic, it should be a dedicated listener class, not a closure

## Consumer Listeners

Consumer applications can register additional listeners for the same events. The framework must not prevent this. Examples:

- `Login` event → send welcome back notification
- `Logout` event → update last_seen timestamp
- `Login` event → check subscription status

Consumers register their listeners in their own `EventServiceProvider`.

## Adding New Listeners

1. Choose the event to listen to (framework or custom)
2. Decide: closure (simple) or dedicated listener class (complex)
3. Register in `registerEventListeners()` in the service provider
4. Feature-gate with the appropriate config flag
5. Keep the listener logic minimal — offload heavy work to queued jobs
6. Document the listener so consumers know it exists (they may want to add their own handlers for the same event)
