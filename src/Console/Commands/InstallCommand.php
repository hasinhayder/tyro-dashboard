<?php

namespace HasinHayder\TyroDashboard\Console\Commands;

use Illuminate\Console\Command;

class InstallCommand extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'tyro-dashboard:install 
                            {--force : Overwrite existing files}';

    /**
     * The console command description.
     */
    protected $description = 'Install Tyro Dashboard package resources';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('');
        $this->info('  ╔════════════════════════════════════════╗');
        $this->info('  ║                                        ║');
        $this->info('  ║     Tyro Dashboard Installation        ║');
        $this->info('  ║                                        ║');
        $this->info('  ╚════════════════════════════════════════╝');
        $this->info('');

        // Check dependencies
        $this->info('Checking dependencies...');

        if (! $this->checkTyroInstalled()) {
            $this->error('   ✗ hasinhayder/tyro package is not installed');
            $this->error('   Please install it first: composer require hasinhayder/tyro');

            return self::FAILURE;
        }
        $this->info('   ✓ hasinhayder/tyro is installed');

        if (! $this->checkTyroLoginInstalled()) {
            $this->warn('   ⚠ hasinhayder/tyro-login package is not installed');
            $this->warn('   Some features may be limited. Install with: composer require hasinhayder/tyro-login');
        } else {
            $this->info('   ✓ hasinhayder/tyro-login is installed');
        }

        $this->info('');

        // Run tyro:install without seeding
        $this->info('Setting up Tyro framework...');
        if (! $this->runTyroInstall()) {
            return self::FAILURE;
        }
        $this->info('   ✓ Tyro framework setup complete');

        $this->info('');

        // Publish config
        $this->info('Publishing configuration...');
        $this->callSilently('vendor:publish', [
            '--tag' => 'tyro-dashboard-config',
            '--force' => $this->option('force'),
        ]);
        $this->info('   ✓ Configuration published to config/tyro-dashboard.php');

        // Ask about views publishing
        $this->info('');
        $this->info('View Publishing Options:');

        if ($this->confirm('Would you like to publish all dashboard views?', false)) {
            $this->info('Publishing all views...');
            $this->callSilently('vendor:publish', [
                '--tag' => 'tyro-dashboard-views',
                '--force' => $this->option('force'),
            ]);
            $this->info('   ✓ All views published to resources/views/vendor/tyro-dashboard/');
        } else {
            // Ask about admin views only
            if ($this->confirm('Would you like to publish admin views only?', false)) {
                $this->info('Publishing admin views...');
                $this->callSilently('vendor:publish', [
                    '--tag' => 'tyro-dashboard-views-admin',
                    '--force' => $this->option('force'),
                ]);
                $this->info('   ✓ Admin views published to resources/views/vendor/tyro-dashboard/');
            }

            // Ask about user views only
            if ($this->confirm('Would you like to publish user views only?', false)) {
                $this->info('Publishing user views...');
                $this->callSilently('vendor:publish', [
                    '--tag' => 'tyro-dashboard-views-user',
                    '--force' => $this->option('force'),
                ]);
                $this->info('   ✓ User views published to resources/views/vendor/tyro-dashboard/');
            }
        }

        // Check if user model has required trait
        $this->info('');
        $this->info('Checking User model...');
        if ($this->checkUserModelHasTrait()) {
            $this->info('   ✓ User model has HasTyroRoles trait');
        } else {
            $this->warn('   ⚠ User model may not have HasTyroRoles trait');
            $this->warn('   Run: php artisan tyro:prepare-user-model');
        }

        // Ask to create super user
        $this->info('');
        if ($this->confirm('Would you like to create a super user now?', true)) {
            $this->call('tyro-dashboard:createsuperuser');
        }

        $this->info('');
        $this->info('  ╔════════════════════════════════════════╗');
        $this->info('  ║                                        ║');
        $this->info('  ║   Tyro Dashboard installed!            ║');
        $this->info('  ║                                        ║');
        $this->info('  ╚════════════════════════════════════════╝');
        $this->info('');
        $this->info('  Next steps:');
        $this->info('  1. Make sure you have an admin user with the "admin" or "super-admin" role');
        $this->info('  2. Login and visit /dashboard to access the admin panel');
        $this->info('  3. Review config/tyro-dashboard.php for more customization');
        $this->info('');
        $this->info('  Dashboard Features:');
        $this->info('  - User Management   : View, create, edit, suspend users');
        $this->info('  - Role Management   : CRUD roles, assign privileges');
        $this->info('  - Privilege Mgmt    : CRUD privileges, assign to roles');
        $this->info('  - Profile Settings  : Users can update their profile');
        $this->info('  - Package Settings  : Configure Tyro & Tyro Login');
        $this->info('');
        $this->info('  Helpful commands:');
        $this->info('  - tyro-dashboard:version   : Show version info');
        $this->info('  - tyro:assign-role         : Assign role to a user');
        $this->info('  - tyro:list-users          : List all users');
        $this->info('');

        return self::SUCCESS;
    }

    /**
     * Check if Tyro package is installed.
     */
    protected function checkTyroInstalled(): bool
    {
        return class_exists(\HasinHayder\Tyro\Providers\TyroServiceProvider::class);
    }

    /**
     * Check if Tyro Login package is installed.
     */
    protected function checkTyroLoginInstalled(): bool
    {
        return class_exists(\HasinHayder\TyroLogin\Providers\TyroLoginServiceProvider::class);
    }

    /**
     * Check if User model has HasTyroRoles trait.
     */
    protected function checkUserModelHasTrait(): bool
    {
        $userModel = config('tyro-dashboard.user_model', config('tyro.models.user', 'App\\Models\\User'));

        if (! class_exists($userModel)) {
            return false;
        }

        return method_exists($userModel, 'tyroRoleSlugs');
    }

    /**
     * Run tyro:install and tyro-login:install without seeding.
     */
    protected function runTyroInstall(): bool
    {
        try {
            // Run tyro:install
            $exitCode = \Illuminate\Support\Facades\Artisan::call('tyro:install', [
                '--no-interaction' => true,
            ]);

            if ($exitCode !== 0) {
                return false;
            }

            // Run tyro-login:install
            $exitCode = \Illuminate\Support\Facades\Artisan::call('tyro-login:install', [
                '--no-interaction' => true,
            ]);

            if ($exitCode !== 0) {
                return false;
            }

            return true;
        } catch (\Exception $e) {
            $this->error('Failed to run installation commands: '.$e->getMessage());

            return false;
        }
    }
}
