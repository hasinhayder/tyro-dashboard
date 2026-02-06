<?php

namespace HasinHayder\TyroDashboard\Console\Commands;

use Illuminate\Console\Command;

class VersionCommand extends Command
{
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
    public function handle(): int
    {
        $version = "1.8.0"; //Invitation/Referral System Addition + Modals
        
        $this->info('');
        $this->info('  ╔════════════════════════════════════════╗');
        $this->info('  ║                                        ║');
        $this->info('  ║        Tyro Dashboard                  ║');
        $this->info('  ║                                        ║');
        $this->info('  ╚════════════════════════════════════════╝');
        $this->info('');
        $this->info("  Version: <comment>{$version}</comment>");
        $this->info('  Laravel: <comment>' . app()->version() . '</comment>');
        $this->info('  PHP: <comment>' . PHP_VERSION . '</comment>');
        $this->info('');
        $this->info('  Dependencies:');
        $this->info('  - hasinhayder/tyro: <comment>' . $this->isDependencyInstalled('tyro') . '</comment>');
        $this->info('  - hasinhayder/tyro-login: <comment>' . $this->isDependencyInstalled('tyro-login') . '</comment>');
        $this->info('');
        $this->info('  Documentation: <comment>https://hasinhayder.github.io/tyro-dashboard/doc.html</comment>');
        $this->info('  GitHub: <comment>https://github.com/hasinhayder/tyro-dashboard</comment>');
        $this->info('');

        return self::SUCCESS;
    }

    /**
     * Check if a dependency is installed
     */
    private function isDependencyInstalled(string $package): string
    {
        $lockFile = base_path('composer.lock');
        
        if (!file_exists($lockFile)) {
            return 'unknown';
        }

        $lockData = json_decode(file_get_contents($lockFile), true);
        
        if (!isset($lockData['packages'])) {
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

//Changelog
//1.8.0 - feat: modal dialog support added with showConfirm, showAlert and showDanger JS functions + used in various places for better UX
//1.8.0 - feat: invitation and referral system added with invitation_links and invitation_referrals tables + invitation management UI in dashboard
//1.7.1 - feat: add configurable sidebar menu items with icon support using $adminMenuItems, $commonMenuItems and $userMenuItems in config/menu.php
//1.7.0 - Sidebar color customization support via TYRO_DASHBOARD_SIDEBAR_BG and TYRO_DASHBOARD_SIDEBAR_TEXT env variables. Sidebar Example pages and routes can be hidden using TYRO_DASHBOARD_DISABLE_EXAMPLES 
//1.6.6 - Dynamic CRUD - hide_in_create, hide_in_edit, default, placeholder, attributes, readonly and display_image and display_image_position support for CRUD fields
//1.6.5 - Configurable auto-deletion of uploaded files on resource deletion + markdown field type field support
//1.6.4 - Dynamic CRUD - Intelligent field type check precedence added for better compatibility
//1.6.3 - Fix role-based access control for HasCrud resources
//1.6.2 - File upload fields with disk and path configuration added
//1.6.1 - Cache the field discovery for Dynamic CRUD resources and clear-cache command added
//1.6.0 - Instant CRUD with HasCrud trait and pagination improvements
//1.5.1 - Fix many-to-many relationship handling for select fields with multiple attribute
//1.5.0 - collapsible sidebar feature added in this version