<?php

namespace HasinHayder\TyroDashboard\Console\Commands;

use Illuminate\Console\Command;

class UpdateConfigCommand extends Command {
    protected $signature = 'tyro-dashboard:update-config {--with-backup : Create backup before publishing}';

    protected $description = 'Update tyro-dashboard config with the latest version';

    public function handle(): int {
        $appConfigPath = config_path('tyro-dashboard.php');

        if ($this->option('with-backup')) {
            $backupFilename = 'tyro-dashboard-backup-'.date('Y-m-d-His').'.txt';
            $backupPath = config_path($backupFilename);

            if (file_exists($appConfigPath)) {
                copy($appConfigPath, $backupPath);
                $this->info("  ✓ Backup created: {$backupFilename}");
            }
        }

        $this->call('vendor:publish', [
            '--tag' => 'tyro-dashboard-config',
            '--force' => true,
        ]);

        $this->callSilent('tyro:update-config');
        $this->callSilent('tyro-login:update-config');

        return self::SUCCESS;
    }
}
