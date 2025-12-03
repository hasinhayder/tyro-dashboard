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
        $version = config('tyro-dashboard.version', '1.0.0');

        $this->info('');
        $this->info('  ╔════════════════════════════════════════╗');
        $this->info('  ║                                        ║');
        $this->info('  ║     Tyro Dashboard v' . str_pad($version, 19) . '║');
        $this->info('  ║                                        ║');
        $this->info('  ╚════════════════════════════════════════╝');
        $this->info('');
        $this->info('  Admin Dashboard for Laravel applications');
        $this->info('  Built with ❤️ by Hasin Hayder');
        $this->info('');
        $this->info('  Dependencies:');
        
        // Check Tyro version
        if (class_exists(\HasinHayder\Tyro\Providers\TyroServiceProvider::class)) {
            $tyroVersion = config('tyro.version', 'installed');
            $this->info('  - hasinhayder/tyro: v' . $tyroVersion);
        } else {
            $this->warn('  - hasinhayder/tyro: not installed');
        }

        // Check Tyro Login version
        if (class_exists(\HasinHayder\TyroLogin\Providers\TyroLoginServiceProvider::class)) {
            $tyroLoginVersion = config('tyro-login.version', 'installed');
            $this->info('  - hasinhayder/tyro-login: v' . $tyroLoginVersion);
        } else {
            $this->warn('  - hasinhayder/tyro-login: not installed');
        }

        $this->info('');
        $this->info('  Configuration:');
        $this->info('  - Route prefix: /' . config('tyro-dashboard.routes.prefix', 'dashboard'));
        $this->info('  - Admin roles: ' . implode(', ', config('tyro-dashboard.admin_roles', ['admin', 'super-admin'])));
        $this->info('');

        return self::SUCCESS;
    }
}
