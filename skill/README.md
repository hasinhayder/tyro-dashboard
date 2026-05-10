# Tyro Dashboard — AI Skill Usage Guide

This folder contains the **Tyro Dashboard AI Skill** (`tyro-dashboard.md`), a comprehensive context file that gives AI coding agents deep knowledge of the Tyro Dashboard package so they can work faster and more accurately.

## What the Skill Covers

The skill file teaches AI agents about:

- **Package architecture** — Directory structure, dependencies, service provider bootstrapping
- **All 17 console commands** — What each command does, its flags, and when to use it
- **Configuration system** — Every key in `config/tyro-dashboard.php` and how to extend it
- **Routes & controllers** — Route groups, middleware, controller methods, authorization logic
- **Dynamic CRUD (`HasCrud`)** — How to add auto-generated admin interfaces to any Eloquent model
- **Views & Blade** — Layouts, partials, sections, and shared variables
- **Security patterns** — Admin checks, impersonation, audit logging, protected roles/users
- **Common tasks** — Step-by-step patterns for features agents build most often

## How to Install the Skill

### Option 1: Use the Installer Command (Recommended)

Run the built-in setup command from your Laravel application:

```bash
php artisan tyro-dashboard:setup-ai-skill
```

You will be prompted to choose an agent:

```
Which AI agent would you like to install the skill for?
  [0] kilo
  [1] claude
  [2] github copilot
  [3] codex
  [4] gemini
  [5] laravel boost
  [6] all
```

Select your agent (or `all`) and the skill file will be copied to the correct location automatically.

### Option 2: Manual Copy

If you prefer, copy `tyro-dashboard.md` manually to your agent's expected path:

| AI Agent | Target Path |
|----------|-------------|
| **Kilo** | `.kilo/skills/tyro-dashboard/SKILL.md` |
| **Claude** | `.claude/skills/tyro-dashboard/SKILL.md` |
| **GitHub Copilot** | `.github/skills/tyro-dashboard/SKILL.md` |
| **Codex** | `.codex/skills/tyro-dashboard/SKILL.md` |
| **Gemini** | `.gemini/skills/tyro-dashboard/SKILL.md` |
| **Laravel Boost** | `.ai/skills/tyro-dashboard/SKILL.md` |

Create the parent directory if it doesn't exist, then copy the file:

```bash
mkdir -p .kilo/skills/tyro-dashboard
cp vendor/hasinhayder/tyro-dashboard/skill/tyro-dashboard.md .kilo/skills/tyro-dashboard/SKILL.md
```

## Agent-Specific Notes

### Kilo
- Reads `.kilo/skills/*/SKILL.md` files automatically when working in the project.
- No additional configuration needed after copying the file.

### Claude (Claude Code / Claude Desktop)
- Reads `.claude/skills/*/SKILL.md` files at the project root.
- Restart Claude or run `/refresh` after placing the file.

### GitHub Copilot
- Reads `.github/skills/*/SKILL.md` files when using the new Copilot skills system.
- Ensure the file is committed to your repo for the best experience across machines.

### Codex (OpenAI)
- Reads `.codex/skills/*/SKILL.md` files when using Codex CLI or IDE integrations.

### Gemini (Google)
- Reads `.gemini/skills/*/SKILL.md` files when using Gemini Code Assist or similar tools.

### Laravel Boost
- Reads `.ai/skills/*/SKILL.md` files. Run `php artisan boost:update` to install custom skills alongside Boost's built-ins.

## What the Skill Enables

With this skill loaded, an AI agent can:

1. **Generate new admin pages** correctly using existing command signatures and Blade layout conventions.
2. **Add Dynamic CRUD resources** by applying the `HasCrud` trait and configuring fields properly.
3. **Modify routes safely** using `DashboardRoute::name()` instead of hardcoded strings.
4. **Respect security patterns** — checking `isAdmin()`, using middleware, protecting critical users/roles.
5. **Extend views** by publishing the right tag and editing the correct partial.
6. **Use audit logging** with the safe `auditSafely()` wrapper pattern.
7. **Handle file uploads** following the three-tier priority (model → resource → global config).

## Keeping the Skill Up to Date

When you update the Tyro Dashboard package, the skill file in `vendor/hasinhayder/tyro-dashboard/skill/tyro-dashboard.md` may have changed. Re-run the installer to propagate updates:

```bash
php artisan tyro-dashboard:setup-ai-skill
```

Or manually copy the latest file from the vendor directory.

## Tips for Best Results

- **Be specific** — Mention controller names, route names, or config keys when asking the agent to make changes. The skill has the full reference.
- **Reference commands** — Instead of saying "create a new admin page," say "run `tyro-dashboard:create-admin-page` for Reports." The agent knows the exact behavior.
- **Mention security** — When adding features, prompt the agent to "ensure admin-only access" or "add audit logging" and it will apply the correct patterns from the skill.
- **Use `DashboardRoute::name()`** — Remind the agent to use this helper for route names so custom prefixes continue to work.

## Troubleshooting

**Agent doesn't seem to know about Tyro Dashboard?**
- Verify the skill file exists at the correct path for your agent.
- For Claude: run `/refresh` or restart.
- For Copilot: ensure `.github/skills/tyro-dashboard/SKILL.md` is in the repo root.

**Skill is outdated?**
- Check `vendor/hasinhayder/tyro-dashboard/skill/tyro-dashboard.md` for the latest version.
- Re-run `php artisan tyro-dashboard:setup-ai-skill` to reinstall.

## Version

This skill documentation corresponds to **Tyro Dashboard v1.20.0**.
