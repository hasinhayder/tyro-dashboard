# Changelog

All notable changes to Tyro Dashboard are documented in this file.

## [v1.48.0](https://github.com/hasinhayder/tyro-dashboard/releases/tag/v1.48.0) - 2026-08-22
- Log Viewer admin page: browse `storage/logs/*.log` files (single + daily rotation) with file picker, per-level count cards as toggleable level filters, case-insensitive message/stack-trace search, per-page sizing, expandable stack traces with copy-to-clipboard, tail-capped parsing (default 16 MB, `TYRO_DASHBOARD_LOG_MAX_READ_BYTES`), and a confirm-guarded "Clear this file" truncate action (never deletes files) — admin-only, gated by `TYRO_DASHBOARD_ENABLE_LOG_VIEWER` with identical route and sidebar gating; consumers with a published sidebar need to re-publish `partials/admin-sidebar.blade.php` (or add the link manually) to see the new menu entry

## [v1.47.0](https://github.com/hasinhayder/tyro-dashboard/releases/tag/v1.47.0) - 2026-08-21
- System Health admin page: read-only runtime diagnostics (PHP memory + upload/execution limits, OPcache, disk usage, database driver/version/tables/size, cache round-trip latency, queue reachability ping, storage writability, runtime context with app/PHP timezone mismatch check, tyro ecosystem package versions from composer.lock) with ordered-hybrid probe caching (live cache probe + 60s expensive-bucket cache, graceful degradation when the cache store is down), gated by `TYRO_DASHBOARD_ENABLE_HEALTH`

## [v1.46.1](https://github.com/hasinhayder/tyro-dashboard/releases/tag/v1.46.1) - 2026-06-26
- Minor UI Tweaks

## [v1.46.0](https://github.com/hasinhayder/tyro-dashboard/releases/tag/v1.46.0) - 2026-06-26
- Settings search feature with auto-fade highlighting, conditional-visibility-aware matching, and Ctrl+K shortcut

## [v1.45.0](https://github.com/hasinhayder/tyro-dashboard/releases/tag/v1.45.0) - 2026-06-24
- Introduced dashboard components
- Passkeys rename feature

## [v1.44.1](https://github.com/hasinhayder/tyro-dashboard/releases/tag/v1.44.1) - 2026-06-23
- Passkeys system settings vtab now collapses all label, route, and CDN config surfaces when the passkeys toggle (`TYRO_LOGIN_PASSKEYS_ENABLED`) is disabled, leaving only the feature toggle visible (reuses the settings conditional-visibility pattern)

## [v1.44.0](https://github.com/hasinhayder/tyro-dashboard/releases/tag/v1.44.0) - 2026-06-23
- Passkeys support: new Passkeys system settings vtab (enable/disable toggle plus all label, route, and CDN config under `TYRO_LOGIN_PASSKEYS_*`) and a profile Passkeys card (list existing passkeys with authenticator/added/last-used, add a passkey via the `@laravel/passkeys` browser client, and remove a passkey with a destructive confirm) — gated on `TYRO_LOGIN_PASSKEYS_ENABLED` + `laravel/passkeys` for the profile UI/route, settings vtab always visible

## [v1.43.3](https://github.com/hasinhayder/tyro-dashboard/releases/tag/v1.43.3) - 2026-06-22
- Added `TYRO_LOGIN_LOGO_BORDER_RADIUS` setting to the Authentication tab branding details

## [v1.43.2](https://github.com/hasinhayder/tyro-dashboard/releases/tag/v1.43.2) - 2026-06-21
- Renamed `TYRO_LOGIN_YOUTUBE_URL` to `TYRO_LOGIN_VIDEO_URL` in settings controller and login-auth blade partial; updated default video URL

## [v1.43.1](https://github.com/hasinhayder/tyro-dashboard/releases/tag/v1.43.1) - 2026-06-21
- `tyro-dashboard:update-config` now silently refreshes tyro and tyro-login configs (`tyro:update-config`, `tyro-login:update-config`) after publishing the dashboard config

## [v1.43.0](https://github.com/hasinhayder/tyro-dashboard/releases/tag/v1.43.0) - 2026-06-21
- Added tidal, animated birds, particle network, and aurora waves animated auth layouts to the Authentication settings tab: dropdown options, per-layout conditional detail surfaces, color pickers with reset-to-default, and full settings persistence (validation, gather reads, defaults, booleans)

## [v1.42.0](https://github.com/hasinhayder/tyro-dashboard/releases/tag/v1.42.0) - 2026-06-19
- Checkpoint management improvements: pre-flight restore check that refuses encrypted snapshots without the matching encryption key, locale-aware checkpoint timestamps via `Carbon::translatedFormat()`, and a Generate Key UI action that appends a fresh 32-char key to `.env`

## [v1.41.0](https://github.com/hasinhayder/tyro-dashboard/releases/tag/v1.41.0) - 2026-06-19
- Animated/video auth layout backgrounds in settings: YouTube video, tidal, animated birds, particle network, and aurora waves layouts with per-layout config fields (video URL/blur/overlay/sound, tidal color/speed/bubbles, birds color, aurora color/speed/intensity, particle color/density/link distance/interactive), color pickers with reset-to-default, and conditional field visibility

## [v1.40.0](https://github.com/hasinhayder/tyro-dashboard/releases/tag/v1.40.0) - 2026-06-19
- Integration with Tyro Checkpoint in Tyro Dashboard: database checkpoint management page with create/restore/delete/rename/encrypt/flush/lock/flag/note, HTTP-context operation via the package service, dedicated driver column, redesigned Create Checkpoint section, and green locked-lock indicator
- Frontend link in the dashboard user dropdown menu (opens the app's `APP_URL` in a new tab)

## [v1.39.0](https://github.com/hasinhayder/tyro-dashboard/releases/tag/v1.39.0) - 2026-06-16
- Bulk delete for media library grid and list views with dashboard modal confirmation

## [v1.38.0](https://github.com/hasinhayder/tyro-dashboard/releases/tag/v1.38.0) - 2026-06-16
- Bulk delete for users with select-all and individual checkbox selection

## [v1.37.0](https://github.com/hasinhayder/tyro-dashboard/releases/tag/v1.37.0) - 2026-06-16
- Improved role and privilege editors with sorted assignments, searchable assignment tables, clearer section layout, consistent header actions, and same-page redirects after updates

## [v1.36.2](https://github.com/hasinhayder/tyro-dashboard/releases/tag/v1.36.2) - 2026-06-06
- AI skill rule updates for UI/UX guidance, shadcn theming discipline, and current configuration documentation

## [v1.36.1](https://github.com/hasinhayder/tyro-dashboard/releases/tag/v1.36.1) - 2026-06-06
- AI skill setup now always refreshes existing target directories with the latest package skill files

## [v1.36.0](https://github.com/hasinhayder/tyro-dashboard/releases/tag/v1.36.0) - 2026-06-06
- Added `tyro-dashboard:publish --sidebar` and `--dashboard` options plus `tyro-dashboard-sidebar` and `tyro-dashboard-essentials` publish tags

## [v1.35.5](https://github.com/hasinhayder/tyro-dashboard/releases/tag/v1.35.5) - 2026-06-05
- Release v1.35.5

## [v1.35.4](https://github.com/hasinhayder/tyro-dashboard/releases/tag/v1.35.4) - 2026-06-05
- AI skill YAML frontmatter quoting fix

## [v1.35.3](https://github.com/hasinhayder/tyro-dashboard/releases/tag/v1.35.3) - 2026-06-05
- Wrap YAML frontmatter strings in quotes, bump skill to 2.1.1

## [v1.35.2](https://github.com/hasinhayder/tyro-dashboard/releases/tag/v1.35.2) - 2026-06-01
- AI skill setup now installs a universal `.agents` copy, vendor-specific symlinks by default, `--copy` physical installs, `--force` non-interactive replacement, and staged swaps for safer updates

## [v1.35.1](https://github.com/hasinhayder/tyro-dashboard/releases/tag/v1.35.1) - 2026-06-01
- AI skill fine tuning and new rules

## [v1.35.0](https://github.com/hasinhayder/tyro-dashboard/releases/tag/v1.35.0) - 2026-06-01
- AI skill rules audit: corrected boot sequence, audit signatures, cache keys, color counts, route gating, controller docs, middleware fallbacks, JS system docs, field guessing pipeline

## [v1.34.1](https://github.com/hasinhayder/tyro-dashboard/releases/tag/v1.34.1) - 2026-06-01
- AI skill documentation corrections (typo fixes, session key, command names, feature-flag classification)

## [v1.34.0](https://github.com/hasinhayder/tyro-dashboard/releases/tag/v1.34.0) - 2026-05-27
- Media library copy URL modal with options to copy Original, WebP, or Thumbnail URL

## [v1.33.0](https://github.com/hasinhayder/tyro-dashboard/releases/tag/v1.33.0) - 2026-05-18
- Initiate 2FA setup flow directly from the dashboard profile page (reuses tyro-login setup wizard via POST `profile/2fa/setup`, clears ignore cookie, sets `url.intended` for return)

## [v1.32.0](https://github.com/hasinhayder/tyro-dashboard/releases/tag/v1.32.0) - 2026-05-12
- Added `TYRO_DASHBOARD_SIDEBAR_ACCORDION_OPEN_SECTIONS` setting (configurable number of default open sidebar sections)

## [v1.31.1](https://github.com/hasinhayder/tyro-dashboard/releases/tag/v1.31.1) - 2026-05-12
- Fixed duplicate `TYRO_DASHBOARD_SIDEBAR_ACCORDION_COMPACT` form field in Dashboard tab causing save failures (field now only in Sidebar tab)

## [v1.31.0](https://github.com/hasinhayder/tyro-dashboard/releases/tag/v1.31.0) - 2026-05-10
- AI skill paths updated to `skills/*/SKILL.md` convention, Laravel Boost support added

## [v1.30.2](https://github.com/hasinhayder/tyro-dashboard/releases/tag/v1.30.2) - 2026-05-10
- Login page logo branding URL via media picker fix

## [v1.30.1](https://github.com/hasinhayder/tyro-dashboard/releases/tag/v1.30.1) - 2026-05-09
- Media improvements and URL copy fixes

## [v1.30.0](https://github.com/hasinhayder/tyro-dashboard/releases/tag/v1.30.0) - 2026-05-08
- Settings Editor, Dashboard color branding, Media Gallery and media picker

## [v1.20.0](https://github.com/hasinhayder/tyro-dashboard/releases/tag/v1.20.0) - 2026-04-26
- AI skill setup command (`tyro-dashboard:setup-ai-skill`) for installing project context to Claude, Copilot, Codex, Gemini, and Kilo agents

## [v1.19.0](https://github.com/hasinhayder/tyro-dashboard/releases/tag/v1.19.0) - 2026-04-15
- Toast notification system with configurable notification style (legacy/toast) and position (top-right/bottom-right)

## [v1.18.1](https://github.com/hasinhayder/tyro-dashboard/releases/tag/v1.18.1) - 2026-04-14
- Reduced sidebar item spacing for better accommodation of more items

## [v1.18.0](https://github.com/hasinhayder/tyro-dashboard/releases/tag/v1.18.0) - 2026-04-14
- Added `tyro-dashboard:update-config`, `tyro-dashboard:update-style`, `tyro-dashboard:update-script`, and `tyro-dashboard:update` commands

## [v1.17.0](https://github.com/hasinhayder/tyro-dashboard/releases/tag/v1.17.0) - 2026-04-14
- Added `tyro-dashboard:update-config`, `tyro-dashboard:update-style`, and `tyro-dashboard:update-script` commands

## [v1.16.0](https://github.com/hasinhayder/tyro-dashboard/releases/tag/v1.16.0) - 2026-04-09
- More sidebar color options

## [v1.15.2](https://github.com/hasinhayder/tyro-dashboard/releases/tag/v1.15.2) - 2026-03-23
- Fix `$dashboardRoute` undefined error in create-admin, user and common page commands (closes #2)

## [v1.15.1](https://github.com/hasinhayder/tyro-dashboard/releases/tag/v1.15.1) - 2026-03-18
- Named prefix for tyro dashboard routes is now working correctly

## [v1.15.0](https://github.com/hasinhayder/tyro-dashboard/releases/tag/v1.15.0) - 2026-03-18
- Laravel 13 support

## [v1.14.0](https://github.com/hasinhayder/tyro-dashboard/releases/tag/v1.14.0) - 2026-03-07
- Export audit trail to CSV

## [v1.13.1](https://github.com/hasinhayder/tyro-dashboard/releases/tag/v1.13.1) - 2026-03-07
- Audit log for user login and logout

## [v1.13.0](https://github.com/hasinhayder/tyro-dashboard/releases/tag/v1.13.0) - 2026-02-21
- Configurable admin notice bar with color, alignment, and config support

## [v1.12.0](https://github.com/hasinhayder/tyro-dashboard/releases/tag/v1.12.0) - 2026-02-18
- Detail audit trail admin pages with filters, search and pagination

## [v1.11.0](https://github.com/hasinhayder/tyro-dashboard/releases/tag/v1.11.0) - 2026-02-10
- Replace JS confirm with built-in modal for impersonation confirmation

## [v1.10.0](https://github.com/hasinhayder/tyro-dashboard/releases/tag/v1.10.0) - 2026-02-10
- User impersonation feature added with `impersonate` and `leaveImpersonation` methods in `UserController` + middleware and blade directive for showing impersonation banner + optional route for leaving impersonation

## [v1.9.0](https://github.com/hasinhayder/tyro-dashboard/releases/tag/v1.9.0) - 2026-02-09
- Profile photo feature. Users can upload profile photos or use Gravatar if enabled. User avatar is displayed in the dashboard user list and can be managed in the profile page. Configuration options added for enabling/disabling profile photos and gravatar support, max upload size and cropping position.

## [v1.8.0](https://github.com/hasinhayder/tyro-dashboard/releases/tag/v1.8.0) - 2026-02-06
- Modal dialog support added with `showConfirm`, `showAlert`, and `showDanger` JS functions, used in various places for better UX
- Invitation and referral system added with `invitation_links` and `invitation_referrals` tables + invitation management UI in dashboard

## [v1.7.1](https://github.com/hasinhayder/tyro-dashboard/releases/tag/v1.7.1) - 2026-01-31
- Configurable sidebar menu items with icon support using `$adminMenuItems`, `$commonMenuItems`, and `$userMenuItems` in `config/menu.php`

## [v1.7.0](https://github.com/hasinhayder/tyro-dashboard/releases/tag/v1.7.0) - 2026-01-31
- Sidebar color customization support via `TYRO_DASHBOARD_SIDEBAR_BG` and `TYRO_DASHBOARD_SIDEBAR_TEXT` env variables
- Sidebar example pages and routes can be hidden using `TYRO_DASHBOARD_DISABLE_EXAMPLES`

## [v1.6.6](https://github.com/hasinhayder/tyro-dashboard/releases/tag/v1.6.6) - 2026-01-30
- Dynamic CRUD: `hide_in_create`, `hide_in_edit`, `default`, `placeholder`, `attributes`, `readonly`, `display_image`, and `display_image_position` support for CRUD fields

## [v1.6.5](https://github.com/hasinhayder/tyro-dashboard/releases/tag/v1.6.5) - 2026-01-29
- Configurable auto-deletion of uploaded files on resource deletion
- Markdown field type support

## [v1.6.4](https://github.com/hasinhayder/tyro-dashboard/releases/tag/v1.6.4) - 2026-01-29
- Dynamic CRUD: intelligent field type check precedence added for better compatibility

## [v1.6.3](https://github.com/hasinhayder/tyro-dashboard/releases/tag/v1.6.3) - 2026-01-29
- Fix role-based access control for HasCrud resources

## [v1.6.2](https://github.com/hasinhayder/tyro-dashboard/releases/tag/v1.6.2) - 2026-01-29
- File upload fields with disk and path configuration added

## [v1.6.1](https://github.com/hasinhayder/tyro-dashboard/releases/tag/v1.6.1) - 2026-01-29
- Cache the field discovery for Dynamic CRUD resources and `clear-cache` command added

## [v1.6.0](https://github.com/hasinhayder/tyro-dashboard/releases/tag/v1.6.0) - 2026-01-29
- Instant CRUD with `HasCrud` trait and pagination improvements

## [v1.5.1](https://github.com/hasinhayder/tyro-dashboard/releases/tag/v1.5.1) - 2026-01-28
- Fix many-to-many relationship handling for select fields with multiple attribute

## [v1.5.0](https://github.com/hasinhayder/tyro-dashboard/releases/tag/v1.5.0) - 2026-01-27
- Collapsible sidebar feature added

## [v1.4.2](https://github.com/hasinhayder/tyro-dashboard/releases/tag/v1.4.2) - 2026-01-23
- Remove admin/dashboard pages feature

## [v1.4.1](https://github.com/hasinhayder/tyro-dashboard/releases/tag/v1.4.1) - 2026-01-23
- Fix: resolve conditional sidebar rendering for user roles

## [v1.4.0](https://github.com/hasinhayder/tyro-dashboard/releases/tag/v1.4.0) - 2026-01-22
- New dashboard page creation commands
- Instant Admin Page
- Breadcrumb fix
- `$user` variable now always available in blade files without explicit passing

## [v1.3.2](https://github.com/hasinhayder/tyro-dashboard/releases/tag/v1.3.2) - 2026-01-21
- Version update

## [v1.3.1](https://github.com/hasinhayder/tyro-dashboard/releases/tag/v1.3.1) - 2026-01-18
- Install command now installs tyro and tyro-login in `--no-interaction` mode with sensible defaults

## [v1.3.0](https://github.com/hasinhayder/tyro-dashboard/releases/tag/v1.3.0) - 2026-01-16
- Super User command
- Color theme support
- UI fixes

## [v1.2.0](https://github.com/hasinhayder/tyro-dashboard/releases/tag/v1.2.0) - 2026-01-16
- Initial tagged release
