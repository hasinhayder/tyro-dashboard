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
        $version = "1.4.0";
        
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
