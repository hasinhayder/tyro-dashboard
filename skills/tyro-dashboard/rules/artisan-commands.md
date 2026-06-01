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
- `tyro-dashboard:clear-cache` — Clear `HasCrud` field config caches
- `tyro-dashboard:setup-ai-skill` — Install AI agent context files
- `tyro-dashboard:version` — Display version and dependency status

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
- Never delete files without confirmation (except `remove-*` commands which are explicit)

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
