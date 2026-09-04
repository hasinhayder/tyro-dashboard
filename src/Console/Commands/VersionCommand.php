<?php

namespace HasinHayder\TyroDashboard\Console\Commands;

use Illuminate\Console\Command;

class VersionCommand extends Command {
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'tyro-dashboard:version';

    /**
     * The console command description.
     */
    protected $description = 'Display the current version of Tyro Dashboard';

    /**
     * Execute the console command.
     */
    public function handle(): int {
        $version = '1.51.3'; // fixed(media): improved media preview modal layout and styling

        $this->info('');
        $this->info('  ╔════════════════════════════════════════╗');
        $this->info('  ║                                        ║');
        $this->info('  ║        Tyro Dashboard                  ║');
        $this->info('  ║                                        ║');
        $this->info('  ╚════════════════════════════════════════╝');
        $this->info('');
        $this->info("  Version: <comment>{$version}</comment>");
        $this->info('  Laravel: <comment>'.app()->version().'</comment>');
        $this->info('  PHP: <comment>'.PHP_VERSION.'</comment>');
        $this->info('');
        $this->info('  Dependencies:');
        $this->info('  - hasinhayder/tyro: <comment>'.$this->isDependencyInstalled('tyro').'</comment>');
        $this->info('  - hasinhayder/tyro-login: <comment>'.$this->isDependencyInstalled('tyro-login').'</comment>');
        $this->info('');
        $this->info('  Documentation: <comment>https://hasinhayder.github.io/tyro-dashboard/doc.html</comment>');
        $this->info('  GitHub: <comment>https://github.com/hasinhayder/tyro-dashboard</comment>');
        $this->info('');

        return self::SUCCESS;
    }

    /**
     * Check if a dependency is installed
     */
    private function isDependencyInstalled(string $package): string {
        $lockFile = base_path('composer.lock');

        if (! file_exists($lockFile)) {
            return 'unknown';
        }

        $lockData = json_decode(file_get_contents($lockFile), true);

        if (! isset($lockData['packages'])) {
            return 'unknown';
        }

        foreach ($lockData['packages'] as $pkg) {
            if ($pkg['name'] === "hasinhayder/{$package}") {
                return 'installed';
            }
        }

        return 'not installed';
    }
}

// Changelog
// 1.51.3 - fixed(media): improved media preview modal layout and styling
// 1.51.2 - fix(media): media grid bulk-select checkbox now matches the action button size (1.7rem) with centered alignment so it no longer sits off-positioned next to the icons
// 1.51.1 - fix(smtp): SMTP Settings encryption dropdown now writes the Symfony DSN schemes smtp (TLS label) and smtps (SSL label) to MAIL_SCHEME instead of tls/ssl, which Symfony's EsmtpTransportFactory rejects — fixes Hostinger "SMTP/SMTP-S" (and similar providers) reporting TLS or SSL is not supported; form and preset validation updated to smtp/smtps
// 1.51.0 - feat(smtp): SMTP Settings admin page — live .env editing (MAIL_* + MAIL_FROM_*), presets table with encrypted passwords (CRUD + apply to .env; tyro_smtp_presets.host defaults to '' so non-smtp presets may omit host), tabular Use Preset modal (Preset/Host/Actions), Save current settings as preset, Clear Config Cache via showDanger, Esc-aware modals, and sidebar entry under Administration; routes and sidebar link are dual-gated by TYRO_DASHBOARD_ENABLE_SMTP_SETTINGS (features.smtp_settings, default true), and SmtpSettingsTest/SmtpSettingsDisabledTest cover authorization, .env writes, preset password encryption, and disabled-feature gating
// 1.50.0 - feat(logout): force logout with Redis session driver — users.logout now dispatches Tyro Login's ForceLogout event when session.driver=redis (logs the user out on their next web request; tyro-login floor bumped to ^2.14), the logout button/icon render for database+redis drivers with driver-aware messages and audit fields, OnlineUsers::forget() clears heartbeat presence immediately on successful session revocation, and UserForceLogoutTest covers redis/database/unsupported drivers (redis test skips gracefully when no server is reachable)
// 1.50.0 - feat(heartbeat): heartbeat API for online detection — auth-protected POST /heartbeat writes a per-user cache key (TTL via TYRO_DASHBOARD_HEARTBEAT_TTL, default 600s) every 5 minutes from the dashboard JS (survives page refreshes via localStorage timestamp, CSRF header, silent failures); isOnline badges, the logged-in user filter, and the dashboard logged-in stat now use the heartbeat cache unioned with the DB-session fallback; gated by TYRO_DASHBOARD_ENABLE_HEARTBEAT
// 1.49.1 - Log Viewer patch re-release (re-tag of v1.49.0 pointing at the same commit; no code changes)
// 1.49.0 - feat(logs): admin log viewer — browse application log files in storage/logs with per-level stat cards (distinct Lucide level icons, filter toggle), file/level/message filters, pagination, stack-trace details with copy, tail-capping for large files, and a clear-file action gated by the dashboard danger modal
// 1.48.0 - feat(logout): per-user logout and logged-in user filtering — added a "Logout" button to the user list for admins to log out individual users, and a "Logged In" filter to show only currently logged-in users in the user list
// 1.48.0 - feat(health): copy-as-image on System Health — per-card PNG copy buttons on all 13 cards plus a page-level "Copy page as image" button, vanilla-JS SVG composition with theme-aware colors, 2x canvas rasterization, clipboard write with download fallback and toast feedback; zero new dependencies
// 1.47.0 - feat(health): System Health admin page — read-only runtime diagnostics (PHP memory + upload/execution limits, OPcache, disk, database, cache round-trip, queue reachability, storage writability, runtime context with timezone mismatch check, tyro ecosystem versions) with ordered-hybrid probe caching, gated by TYRO_DASHBOARD_ENABLE_HEALTH
// 1.46.4 - fix(#7): Media Library alt-text field now saves correctly; alt route was POST-only so PATCH method spoofing returned a 405, and the alt/rename endpoints are now aligned on native PATCH
// 1.46.3 - fix(#8): app layout now uses config('tyro-dashboard.admin_roles') for the @hasanyrole sidebar check, fixing the admin sidebar collapsing into the user sidebar for the super-admin role
// 1.46.3 - fix(#6): disallow SVG uploads to prevent stored XSS
// 1.46.3 - fix(#4): Media Library preview button now uses Storage::url() for correct image URLs
// 1.46.2 - Fixed admin invitation page crash when an invitation link's owner was deleted; orphaned links (owner deleted) are now excluded from the admin listing
// 1.46.1 - Minor UI Tweaks
// 1.46.0 - Settings search feature with auto-fade highlighting, conditional-visibility-aware matching, and Ctrl+K shortcut
// 1.45.0 - Introduced dashboard components. Passkeys rename feature.
// 1.44.1 - Passkeys system settings vtab now collapses all label, route, and CDN config surfaces when the passkeys toggle (TYRO_LOGIN_PASSKEYS_ENABLED) is disabled, leaving only the feature toggle visible (reuses the settings conditional-visibility pattern)
// 1.44.0 - Passkeys support: new Passkeys system settings vtab (enable/disable toggle plus all label, route, and CDN config under TYRO_LOGIN_PASSKEYS_*) and a profile Passkeys card (list existing passkeys with authenticator/added/last-used, add a passkey via the @laravel/passkeys browser client, and remove a passkey with a destructive confirm) — gated on TYRO_LOGIN_PASSKEYS_ENABLED + laravel/passkeys for the profile UI/route, settings vtab always visible
// 1.43.3 - added TYRO_LOGIN_LOGO_BORDER_RADIUS setting to the Authentication tab branding details
// 1.43.2 - renamed TYRO_LOGIN_YOUTUBE_URL to TYRO_LOGIN_VIDEO_URL in settings controller and login-auth blade partial; updated default video URL
// 1.43.1 - tyro-dashboard:update-config now silently refreshes tyro and tyro-login configs (tyro:update-config, tyro-login:update-config) after publishing the dashboard config
// 1.43.0 - Added tidal, animated birds, particle network, and aurora waves animated auth layouts to the Authentication settings tab: dropdown options, per-layout conditional detail surfaces, color pickers with reset-to-default, and full settings persistence (validation, gather reads, defaults, booleans)
// 1.42.0 - Checkpoint management improvements: pre-flight restore check that refuses encrypted snapshots without the matching encryption key, locale-aware checkpoint timestamps via Carbon::translatedFormat(), and a Generate Key UI action that appends a fresh 32-char key to .env
// 1.41.0 - Animated/video auth layout backgrounds in settings: YouTube video, tidal, animated birds, particle network, and aurora waves layouts with per-layout config fields (video URL/blur/overlay/sound, tidal color/speed/bubbles, birds color, aurora color/speed/intensity, particle color/density/link distance/interactive), color pickers with reset-to-default, and conditional field visibility
// 1.40.0 - Integration with Tyro Checkpoint in Tyro Dashboard: database checkpoint management page with create/restore/delete/rename/encrypt/flush/lock/flag/note, HTTP-context operation via the package service, dedicated driver column, redesigned Create Checkpoint section, and green locked-lock indicator. Frontend link in the dashboard user dropdown menu (opens the app's APP_URL in a new tab)
// 1.39.0 - Bulk delete for media library grid and list views with dashboard modal confirmation
// 1.38.0 - Bulk delete for users with select-all and individual checkbox selection
// 1.37.0 - Improved role and privilege editors with sorted assignments, searchable assignment tables, clearer section layout, consistent header actions, and same-page redirects after updates
// 1.36.2 - AI skill rule updates for UI/UX guidance, shadcn theming discipline, and current configuration documentation
// 1.36.1 - AI skill setup now always refreshes existing target directories with the latest package skill files
// 1.36.0 - Added tyro-dashboard:publish --sidebar and --dashboard options plus tyro-dashboard-sidebar and tyro-dashboard-essentials publish tags
// 1.35.4 - AI skill YAML frontmatter quoting fix
// 1.35.2 - AI skill setup now installs a universal .agents copy, vendor-specific symlinks by default, --copy physical installs, --force non-interactive replacement, and staged swaps for safer updates
// 1.35.1 - AI skill fine tuning and new rules
// 1.35.0 - AI skill rules audit: corrected boot sequence, audit signatures, cache keys, color counts, route gating, controller docs, middleware fallbacks, JS system docs, field guessing pipeline
// 1.34.1 - AI skill documentation corrections (typo fixes, session key, command names, feature-flag classification)
// 1.34.0 - Media library copy URL modal with options to copy Original, WebP, or Thumbnail URL
// 1.33.0 - Initiate 2FA setup flow directly from the dashboard profile page (reuses tyro-login setup wizard via POST profile/2fa/setup, clears ignore cookie, sets url.intended for return)
// 1.32.0 - Added TYRO_DASHBOARD_SIDEBAR_ACCORDION_OPEN_SECTIONS setting (configurable number of default open sidebar sections)
// 1.31.1 - Fixed duplicate TYRO_DASHBOARD_SIDEBAR_ACCORDION_COMPACT form field in Dashboard tab causing save failures (field now only in Sidebar tab)
// 1.31.0 - AI skill paths updated to skills/*/SKILL.md convention, Laravel Boost support added
// 1.30.0 - Settings Editor, Dashboard color branding, Media Gallery and media picker
// 1.20.0 - AI skill setup command (tyro-dashboard:setup-ai-skill) for installing project context to Claude, Copilot, Codex, Gemini, and Kilo agents
// 1.19.0 - Toast notification system with configurable notification style (legacy/toast) and position (top-right/bottom-right)
// 1.18.1 - Reduced sidebar item spacing for better accommodation of more items
// 1.18.0 - Added tyro-dashboard:update-config, tyro-dashboard:update-style, tyro-dashboard:update-script, and tyro-dashboard:update commands
// 1.17.0 - Added tyro-dashboard:update-config, tyro-dashboard:update-style, and tyro-dashboard:update-script commands
// 1.16.0 - more sidebar color options
// 1.15.2 - fix(console): fix $dashboardRoute undefined error in create-admin, user and common page commands (closes #2)
// 1.15.1 - fix(config) - Named prefix for tyro dashboard routes is now working perfectly
// 1.15.0 - Laravel 13 support
// 1.14.0 - feat(audit-trail): export audit trail to CSV
// 1.13.1 - feat(audit-trail): audit log for user login and logout
// 1.13.0 - feat(admin-bar): add configurable admin notice bar with color, alignment, and config support
// 1.12.0 - Detail audit trail admin pages with filters, search and pagination
// 1.11.0 - replace JS confirm with built-in modal for impersonation confirmation
// 1.10.0 - User impersonation feature added with impersonate and leaveImpersonation methods in UserController + middleware and blade directive for showing impersonation banner + optional route for leaving impersonation
// 1.9.0 - Profile photo feature. Now users can upload profile photos or use Gravatar if enabled. User avatar is displayed in the dashboard user list and can be managed in the profile page. Configuration options added for enabling/disabling profile photos and gravatar support, max upload size and cropping position. This is a major step towards more personalized user experience in the dashboard.
// 1.8.0 - feat: modal dialog support added with showConfirm, showAlert and showDanger JS functions + used in various places for better UX
// 1.8.0 - feat: invitation and referral system added with invitation_links and invitation_referrals tables + invitation management UI in dashboard
// 1.7.1 - feat: add configurable sidebar menu items with icon support using $adminMenuItems, $commonMenuItems and $userMenuItems in config/menu.php
// 1.7.0 - Sidebar color customization support via TYRO_DASHBOARD_SIDEBAR_BG and TYRO_DASHBOARD_SIDEBAR_TEXT env variables. Sidebar Example pages and routes can be hidden using TYRO_DASHBOARD_DISABLE_EXAMPLES
// 1.6.6 - Dynamic CRUD - hide_in_create, hide_in_edit, default, placeholder, attributes, readonly and display_image and display_image_position support for CRUD fields
// 1.6.5 - Configurable auto-deletion of uploaded files on resource deletion + markdown field type field support
// 1.6.4 - Dynamic CRUD - Intelligent field type check precedence added for better compatibility
// 1.6.3 - Fix role-based access control for HasCrud resources
// 1.6.2 - File upload fields with disk and path configuration added
// 1.6.1 - Cache the field discovery for Dynamic CRUD resources and clear-cache command added
// 1.6.0 - Instant CRUD with HasCrud trait and pagination improvements
// 1.5.1 - Fix many-to-many relationship handling for select fields with multiple attribute
// 1.5.0 - collapsible sidebar feature added in this version
