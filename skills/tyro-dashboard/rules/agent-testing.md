# Agent Testing

## Core Principle

Verification must increase confidence without damaging the user's application. In Tyro Dashboard projects, agents must be conservative with databases, storage, queues, caches, external services, and long-running workers. Prefer read-only checks and narrow tests. Never assume the current database is disposable.

## Safety Classification

### Generally Safe

These are safe in most projects:

- `php -l path/to/file.php`
- `php artisan route:list --name=<fragment>`
- `php artisan view:cache`
- `php artisan config:show <key>` when available and non-secret
- `composer validate --no-check-publish`
- Reading files with `rg`, `sed`, `ls`, `find`, `git diff`, `git status`

Notes:

- `php artisan view:cache` writes compiled views, but does not alter application data.
- `php artisan route:list` may boot the app. If boot side effects are suspected, stop and inspect first.

### Usually Safe, But Check Context

Use judgment for:

- Focused Pest/PHPUnit tests that are clearly isolated.
- `php artisan test --filter=<specific-test>`.
- `php artisan config:clear`, `cache:clear`, `route:clear`, `view:clear`.
- `php artisan queue:failed`, `schedule:list`, `about`.

These can affect runtime cache or reveal environment behavior. They should not modify business records, but they may disrupt a running app if executed on a shared or production host.

### Approval Required or User-Explicit Only

Do not run these unless the user explicitly asks, the environment is confirmed disposable/test-only, or you have clear approval:

- `php artisan migrate`
- `php artisan migrate:fresh`
- `php artisan migrate:refresh`
- `php artisan migrate:reset`
- `php artisan db:wipe`
- `php artisan db:seed`
- `php artisan tinker` commands that write data
- `php artisan queue:work`
- `php artisan horizon`
- `php artisan schedule:run`
- Any command that dispatches jobs, sends mail, charges payments, uploads/deletes remote files, or calls production APIs
- Broad full-suite tests when the database configuration is unknown

Never run destructive database commands against a user application merely to verify a code change.

## Database Protection

Before running tests that may touch the database:

1. Inspect `phpunit.xml`, `.env.testing`, and test setup if available.
2. Confirm the test database is isolated from the development/production database.
3. Prefer a specific test filter over the full suite.
4. Do not run tests that use `RefreshDatabase`, `DatabaseMigrations`, `DatabaseTruncation`, or seeders unless the database is confirmed test-only.
5. If database isolation is unclear, skip the test and report why.

Warning signs:

- `DB_DATABASE` points to a normal app database name.
- `.env.testing` is missing.
- Tests use SQLite file paths inside project storage that may contain real data.
- Tests include payment, mail, queue, media deletion, storage deletion, or external upload flows.
- The command name includes `fresh`, `refresh`, `reset`, `wipe`, `truncate`, `delete`, `prune`, `flush`, or `clear` for application data.

## Safe Verification by Task Type

### Blade-only Change

Prefer:

- `php artisan view:cache`
- Inspect changed Blade file for expected variables and route names

Avoid:

- Browser automation that submits forms or triggers destructive actions unless explicitly needed.

### New Admin Route or Page

Prefer:

- `php -l` on new/changed controller
- `php artisan route:list --name=<new-route-name>`
- `php artisan view:cache`

Run feature tests only when they are already present and isolated.

### Sidebar/Menu Change

Prefer:

- `php artisan view:cache`
- `php artisan route:list --name=<linked-route>`

Avoid:

- Adding runtime database checks to the sidebar.

### Controller Logic

Prefer:

- `php -l`
- Existing focused tests with a safe test database
- Static inspection for authorization, validation, redirects, and error handling

Avoid:

- Manual `tinker` writes.
- Hitting routes that perform writes without CSRF/auth context.

### Settings or `.env` Editing

Prefer:

- `php -l`
- Validation review
- Unit/focused tests with fake filesystem if present

Avoid:

- Writing real `.env` values during verification.
- Clearing config cache on production/shared hosts unless needed and acceptable.
- Printing secrets in logs or final responses.

### Migrations and Models

Prefer:

- Inspect migration SQL/columns manually.
- `php -l` on related PHP files.
- Focused tests only with confirmed isolated test DB.

Avoid:

- `migrate`, `migrate:fresh`, `db:wipe`, `schema:dump`, or seeders on unknown databases.

### Jobs, Queues, Horizon, Media, and External Services

Prefer:

- Static checks and focused tests with fakes/mocks.
- `php -l` on jobs/services.
- Verify route/config presence without processing queues.

Avoid:

- `queue:work`, `horizon`, `schedule:run`.
- Commands that upload, transcode, delete, charge, notify, or call external APIs.

## If Verification Is Unsafe

Do not force it. Say exactly what was skipped and why:

```text
I did not run the focused database test because `.env.testing` is missing and the test uses `RefreshDatabase`, so it could alter the configured database.
```

Then provide the safest checks that were run.

## Anti-Patterns

- Running `migrate:fresh` to make tests pass.
- Running the full test suite without checking database isolation.
- Using `tinker` to create/update/delete real records for verification.
- Starting workers or Horizon to "see what happens."
- Clearing application caches on a live/shared app without need.
- Treating local development data as disposable.
- Calling external payment, mail, storage, AI, or media APIs during verification without fakes.
