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

## Setup AI Skill Command

`tyro-dashboard:setup-ai-skill` copies the canonical skill directory (`skills/tyro-dashboard/` containing `SKILL.md` + `rules/`) from the package into the consumer app's base path under agent-specific discovery directories plus a universal location.

### Interactive Flow
1. Displays a branded header
2. Validates source skill directory exists at `vendor/hasinhayder/tyro-dashboard/skills/tyro-dashboard/`
3. Prompts: `$this->choice()` — pick one agent or `all`
4. Installs to each selected agent's discovery directory
5. Always installs to the universal `.agents/skills/tyro-dashboard/` directory exactly once

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

### Install Strategy (Atomic Swap)
1. Stage new contents in sibling `.__installing__` temp directory
2. Rename existing target to `.__backup__` (atomic on same filesystem; falls back to copy+delete cross-device)
3. Rename staged directory into target's place
4. On failure: restore backup, clean staging dir
5. On success: discard backup

This guarantees the target directory is never left in a partially-wiped state.

### Consumer Guidance
- The install **wipes and replaces** the entire target directory — any custom files placed inside will be removed
- This is intentional: prevents stale rule files from previous framework versions from conflicting with new versions
- Consumers needing custom additions should place them in a **sibling** directory (e.g., `.kilo/skills/tyro-dashboard-custom/`)

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
