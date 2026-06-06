# Artisan Commands

## Core Principle

Artisan commands are often the first interaction a developer has with the framework. A confusing install experience or a scaffolding command that destroys existing code creates bad first impressions.

## Naming Convention

- All commands use the `tyro-dashboard:` prefix
- Format: `tyro-dashboard:{verb}-{noun}`
- Examples: `tyro-dashboard:install`, `tyro-dashboard:make-resource`, `tyro-dashboard:clear-cache`
- The prefix prevents collisions with consumer application commands and other packages

## Command Inventory (18 Commands)

### Installation & Setup
- `tyro-dashboard:install` — Full interactive installer with dependency checks and publishing
- `tyro-dashboard:createsuperuser` — Interactive wizard for admin user creation

### Scaffolding
- `tyro-dashboard:make-resource {name}` — Model + Migration + Controller + config snippet
- `tyro-dashboard:create-admin-page {name}` — View + route + sidebar link
- `tyro-dashboard:create-user-page {name}` — Same for user pages
- `tyro-dashboard:create-common-page {name}` — Same for common pages

### Publishing
- `tyro-dashboard:publish` — Interactive publisher with granular options
- `tyro-dashboard:publish-style` — Publish styles only

### Updates
- `tyro-dashboard:update` — Aggregated update command
- `tyro-dashboard:update-style` — Re-publish style partials
- `tyro-dashboard:update-script` — Re-publish script partials
- `tyro-dashboard:update-config` — Re-publish config with merge

### Removal
- `tyro-dashboard:remove-admin-page` — Remove a created admin page
- `tyro-dashboard:remove-user-page` — Remove a created user page
- `tyro-dashboard:remove-common-page` — Remove a created common page

### Maintenance
- `tyro-dashboard:clear-cache` — Clear `HasCrud` field config caches. Accepts `--model=App\Models\Post` option to clear a specific model's cache only (clears all models when omitted)
- `tyro-dashboard:setup-ai-skill` — Install AI agent context files (detailed below)
- `tyro-dashboard:version` — Display version and dependency status

### Naming Note
`tyro-dashboard:createsuperuser` uses a single unbroken word (not `create-super-user`). This is a legacy naming inconsistency preserved for backward compatibility. New commands must follow the `tyro-dashboard:{verb}-{noun}` convention.

## Command Quick Reference

Use this section when choosing a command. Prefer the narrowest command or flag that matches the task. Before running commands that touch the database, storage, published app files, or broad verification state, read `rules/agent-testing.md`.

### `tyro-dashboard:install`
Purpose: Run the full interactive dashboard installer.
Use when: Bootstrapping Tyro Dashboard in a consuming Laravel app.
Important flags: `--force` passes overwrite behavior to vendor publishing.
Writes to: `config/tyro-dashboard.php`, optionally `resources/views/vendor/tyro-dashboard/`, and any sibling package installer outputs.
Caution: Runs `tyro:install` and `tyro-login:install` without interaction; do not run casually in an existing app.

### `tyro-dashboard:createsuperuser`
Purpose: Create an admin-capable user interactively.
Use when: The app needs its first admin/super-admin account.
Important flags: None.
Writes to: Application user/role data.
Caution: Database-affecting command; read `rules/agent-testing.md` before running.

### `tyro-dashboard:publish`
Purpose: Publish Tyro Dashboard resources for app customization.
Use when: A consumer needs config, views, sidebars, dashboard shell views, styles, or all resources.
Important flags: `--all`, `--config`, `--views`, `--admin`, `--user`, `--sidebar`, `--dashboard`, `--style`, `--force`.
Writes to: `config/tyro-dashboard.php` and/or `resources/views/vendor/tyro-dashboard/`.
Caution: `--force` can overwrite consumer-published files. Use `--sidebar` for only sidebars and `--dashboard` for the `tyro-dashboard-essentials` tag.

### `tyro-dashboard:publish-style`
Purpose: Publish style/theme partials.
Use when: A consumer wants to customize CSS variables or Tyro Dashboard component styles.
Important flags: `--theme-only`, `--force`.
Writes to: `resources/views/vendor/tyro-dashboard/partials/shadcn-theme.blade.php` and optionally `partials/styles.blade.php`.
Caution: `--force` overwrites published style partials.

### `tyro-dashboard:make-resource {name}`
Purpose: Scaffold a Dynamic CRUD resource.
Use when: Creating a new model-backed resource with Tyro Dashboard CRUD support.
Important flags: None.
Writes to: Model, migration, controller, and related resource scaffold files.
Caution: Creates application files and may imply a migration workflow; read `rules/agent-testing.md` before running migrations.

### `tyro-dashboard:create-admin-page {name?}`
Purpose: Scaffold an admin-only dashboard page.
Use when: Adding a custom page visible from the admin sidebar.
Important flags: `--force`.
Writes to: `resources/views/dashboard/{page}.blade.php`, `routes/web.php`, and published `partials/admin-sidebar.blade.php` when present.
Caution: Adds `auth` plus `tyro-dashboard.admin` middleware. `--force` overwrites the generated view only.

### `tyro-dashboard:create-user-page {name?}`
Purpose: Scaffold a user dashboard page.
Use when: Adding a custom page for non-admin/authenticated users.
Important flags: `--force`.
Writes to: `resources/views/dashboard/{page}.blade.php`, `routes/web.php`, and published `partials/user-sidebar.blade.php` when present.
Caution: Publishes user views if no published Tyro Dashboard views exist.

### `tyro-dashboard:create-common-page {name?}`
Purpose: Scaffold a page shared by admin and user sidebars.
Use when: Adding a dashboard page that both audiences should see.
Important flags: `--force`.
Writes to: `resources/views/dashboard/{page}.blade.php`, `routes/web.php`, and published sidebar partials when present.
Caution: Check both sidebar outputs after running because custom published sidebars may not match the insertion pattern.

### `tyro-dashboard:remove-admin-page {name?}`
Purpose: Remove a scaffolded admin dashboard page.
Use when: Reversing `create-admin-page`.
Important flags: None.
Writes to: Deletes `resources/views/dashboard/{page}.blade.php`, edits `routes/web.php`, and edits published `partials/admin-sidebar.blade.php`.
Caution: Destructive command with confirmation. Do not run without explicit intent.

### `tyro-dashboard:remove-user-page {name?}`
Purpose: Remove a scaffolded user dashboard page.
Use when: Reversing `create-user-page`.
Important flags: None.
Writes to: Deletes `resources/views/dashboard/{page}.blade.php`, edits `routes/web.php`, and edits published `partials/user-sidebar.blade.php`.
Caution: Destructive command with confirmation. Do not run without explicit intent.

### `tyro-dashboard:remove-common-page {name?}`
Purpose: Remove a scaffolded common dashboard page.
Use when: Reversing `create-common-page`.
Important flags: None.
Writes to: Deletes `resources/views/dashboard/{page}.blade.php`, edits `routes/web.php`, and edits published sidebar partials.
Caution: Destructive command with confirmation. Do not run without explicit intent.

### `tyro-dashboard:update`
Purpose: Aggregate update for published style, script, config, sidebars, and flash messages.
Use when: Upgrading a consuming app's published Tyro Dashboard support files.
Important flags: None.
Writes to: Published style/script/config targets plus existing published sidebar and flash-message partials.
Caution: Calls `update-config`, which force-publishes config. Review app overrides before running.

### `tyro-dashboard:update-config`
Purpose: Re-publish the package config.
Use when: Pulling new config defaults into a consuming app.
Important flags: `--with-backup`.
Writes to: `config/tyro-dashboard.php`; optional backup in `config/`.
Caution: Uses `vendor:publish --force`; use `--with-backup` when preserving local edits matters.

### `tyro-dashboard:update-style`
Purpose: Re-publish Tyro Dashboard style partials.
Use when: Pulling updated package CSS/theme partials into published views.
Important flags: None.
Writes to: Published style partials under `resources/views/vendor/tyro-dashboard/partials/`.
Caution: Overwrites published style partials.

### `tyro-dashboard:update-script`
Purpose: Re-publish Tyro Dashboard script partials.
Use when: Pulling updated package JavaScript partials into published views.
Important flags: None.
Writes to: Published script partials under `resources/views/vendor/tyro-dashboard/partials/`.
Caution: Overwrites published script partials.

### `tyro-dashboard:clear-cache`
Purpose: Clear Dynamic CRUD field configuration caches.
Use when: HasCrud field definitions or model schemas changed and cached field metadata is stale.
Important flags: `--model=App\\Models\\Post` for one model.
Writes to: Cache only.
Caution: Safe for app data, but the broad form scans `app/Models`; prefer `--model` when possible.

### `tyro-dashboard:setup-ai-skill`
Purpose: Install or refresh the Tyro Dashboard AI skill files.
Use when: Updating `.agents` or vendor-specific agent skill directories after a package upgrade.
Important flags: `--copy`.
Writes to: `.agents/skills/tyro-dashboard/` and selected agent directories such as `.codex/skills/tyro-dashboard/`.
Caution: Existing target directories are always replaced with the latest package skill files.

### `tyro-dashboard:version`
Purpose: Display Tyro Dashboard version, dependency status, and links.
Use when: Verifying installed package version or dependency presence.
Important flags: None.
Writes to: Nothing.
Caution: Reads `composer.lock`; dependency status is `unknown` if the lock file is unavailable.

## Setup AI Skill Command

`tyro-dashboard:setup-ai-skill` copies the canonical skill directory (`skills/tyro-dashboard/` containing `SKILL.md` + `rules/`) from the package into the consumer app's base path under the universal `.agents/` discovery directory. Agent-specific discovery directories symlink to that universal copy by default, or receive physical copies when `--copy` is passed.

### Interactive Flow
1. Displays a branded header
2. Validates source skill directory exists at `vendor/hasinhayder/tyro-dashboard/skills/tyro-dashboard/`
3. Prompts: `$this->choice()` — pick one agent or `all`
4. Always installs a physical copy to the universal `.agents/skills/tyro-dashboard/` directory exactly once
5. Installs to each selected agent's discovery directory as a symlink to `.agents` by default, or as a physical copy with `--copy`
6. If install targets already exist, lists them and replaces them with the latest package skill files

### Supported Agents
| Agent | Target Directory |
|-------|-----------------|
| Kilo | `.kilo/skills/tyro-dashboard/` |
| Claude | `.claude/skills/tyro-dashboard/` |
| GitHub Copilot | `.github/skills/tyro-dashboard/` |
| Codex | `.codex/skills/tyro-dashboard/` |
| Gemini | `.gemini/skills/tyro-dashboard/` |
| Laravel Boost | `.ai/skills/tyro-dashboard/` |
| Universal (always) | `.agents/skills/tyro-dashboard/` |

### Install Strategy (Staged Swap)
1. Stage new contents in a sibling `.<target>.__installing__` temp path
2. Rename existing target to sibling `.<target>.__backup__`
3. Rename staged directory into target's place
4. On failure: restore backup, clean staging dir
5. On success: discard backup

This prevents delete-first replacement from leaving the target directory partially wiped if staging fails.

### Consumer Guidance
- The install **wipes and replaces** the entire target directory — any custom files placed inside will be removed
- This is intentional: prevents stale rule files from previous framework versions from conflicting with new versions
- Consumers needing custom additions should place them in a **sibling** directory (e.g., `.kilo/skills/tyro-dashboard-custom/`)
- Use `--copy` when the environment cannot use symlinks or when each agent needs an independent physical copy

## Command Implementation Rules

### Extend Laravel's Command
All commands extend `Illuminate\Console\Command`. Never implement a custom base command class without strong justification.

### Interactive Prompts
- Use `$this->ask()` for text input
- Use `$this->confirm()` for yes/no
- Use `$this->choice()` for selection from options
- Never use raw `readline()` or `STDIN`

### Output Formatting
- `$this->info()` — Success messages, green text
- `$this->error()` — Error messages, red text
- `$this->warn()` — Warnings, yellow text
- `$this->line()` — Neutral output, white text
- `$this->newLine()` — Blank line for spacing
- Output must be consistent across all commands

### File System Safety
- Installer commands must check for existing files before overwriting
- `--force` flag for non-interactive overwrites
- Scaffolding commands check if target model/controller/view already exists
- Update commands never overwrite `resources/views/vendor/tyro-dashboard/`
- Never delete files without confirmation except explicit `remove-*` commands and `setup-ai-skill` managed skill-directory refreshes

### Production Safety
- Every command must be safe to run in production
- If a command is destructive in production, it must confirm before proceeding
- Commands that modify `.env` (like install) must warn about production impact
- Cache-clear commands are safe — they clear framework caches, not application data

## Installer-Specific Rules

- `tyro-dashboard:install` checks for Tyro Core and Tyro Login dependencies
- Runs sibling package installers if needed
- Publishes config with merge — never overwrites existing consumer config
- Offers to publish views (interactive)
- Offers to create superuser (interactive)
- Each step reports success/failure independently
