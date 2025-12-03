<?php

namespace HasinHayder\TyroDashboard\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

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
        
        if (!$this->checkTyroInstalled()) {
            $this->error('   ✗ hasinhayder/tyro package is not installed');
            $this->error('   Please install it first: composer require hasinhayder/tyro');
            return self::FAILURE;
        }
        $this->info('   ✓ hasinhayder/tyro is installed');

        if (!$this->checkTyroLoginInstalled()) {
            $this->warn('   ⚠ hasinhayder/tyro-login package is not installed');
            $this->warn('   Some features may be limited. Install with: composer require hasinhayder/tyro-login');
        } else {
            $this->info('   ✓ hasinhayder/tyro-login is installed');
        }

        $this->info('');

        // Publish config
        $this->info('Publishing configuration...');
        $this->callSilently('vendor:publish', [
            '--tag' => 'tyro-dashboard-config',
            '--force' => $this->option('force'),
        ]);
        $this->info('   ✓ Configuration published to config/tyro-dashboard.php');

        // Ask about views
        if ($this->confirm('Would you like to publish the views for customization?', false)) {
            $this->info('Publishing views...');
            $this->callSilently('vendor:publish', [
                '--tag' => 'tyro-dashboard-views',
                '--force' => $this->option('force'),
            ]);
            $this->info('   ✓ Views published to resources/views/vendor/tyro-dashboard/');
        }

        // Ask about admin role configuration
        $this->info('');
        $this->info('Configuring admin access...');
        
        $defaultAdminRoles = config('tyro-dashboard.admin_roles', ['admin', 'super-admin']);
        $this->info('   Current admin roles: ' . implode(', ', $defaultAdminRoles));
        
        if ($this->confirm('Would you like to customize the admin role slugs?', false)) {
            $roles = $this->ask('Enter admin role slugs (comma-separated)', implode(',', $defaultAdminRoles));
            $this->updateAdminRoles(array_map('trim', explode(',', $roles)));
        }

        // Ask about route prefix
        if ($this->confirm('Would you like to customize the route prefix? (default: dashboard)', false)) {
            $prefix = $this->ask('Enter route prefix', 'dashboard');
            $this->updateRoutePrefix($prefix);
        }

        // Ask about branding
        if ($this->confirm('Would you like to customize branding settings?', false)) {
            $this->customizeBranding();
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
        
        if (!class_exists($userModel)) {
            return false;
        }

        return method_exists($userModel, 'tyroRoleSlugs');
    }

    /**
     * Update admin roles in config.
     */
    protected function updateAdminRoles(array $roles): void
    {
        $configPath = config_path('tyro-dashboard.php');
        
        if (!File::exists($configPath)) {
            $this->warn('   Config file not found, skipping...');
            return;
        }

        $content = File::get($configPath);
        
        // Convert roles array to PHP array string
        $rolesString = "['" . implode("', '", $roles) . "']";
        
        // Replace the admin_roles line
        $content = preg_replace(
            "/'admin_roles'\s*=>\s*\[.*?\]/s",
            "'admin_roles' => " . $rolesString,
            $content
        );
        
        File::put($configPath, $content);
        $this->info('   ✓ Admin roles updated');
    }

    /**
     * Update route prefix in config.
     */
    protected function updateRoutePrefix(string $prefix): void
    {
        $configPath = config_path('tyro-dashboard.php');
        
        if (!File::exists($configPath)) {
            return;
        }

        $content = File::get($configPath);
        
        $content = preg_replace(
            "/'prefix'\s*=>\s*env\([^)]+\)/",
            "'prefix' => env('TYRO_DASHBOARD_PREFIX', '{$prefix}')",
            $content
        );
        
        File::put($configPath, $content);
        $this->info('   ✓ Route prefix updated to: /' . $prefix);
    }

    /**
     * Customize branding settings.
     */
    protected function customizeBranding(): void
    {
        $appName = $this->ask('Application name', config('app.name'));
        
        $envPath = base_path('.env');
        if (File::exists($envPath)) {
            $envContent = File::get($envPath);
            
            if (!str_contains($envContent, 'TYRO_DASHBOARD_APP_NAME')) {
                File::append($envPath, "\n# Tyro Dashboard Branding\nTYRO_DASHBOARD_APP_NAME=\"{$appName}\"\n");
                $this->info('   ✓ Branding settings added to .env');
            }
        }
    }
}
