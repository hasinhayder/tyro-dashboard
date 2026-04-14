<?php

namespace HasinHayder\TyroDashboard\Console\Commands;

use Illuminate\Console\Command;

class UpdateScriptCommand extends Command {
    protected $signature = 'tyro-dashboard:update-script';

    protected $description = 'Update published tyro-dashboard scripts with the latest version';

    public function handle(): int {
        $publishedScriptPath = resource_path('views/vendor/tyro-dashboard/partials/scripts.blade.php');

        if (! file_exists($publishedScriptPath)) {
            $this->warn('  ⚠ scripts.blade.php is not published yet. Run tyro-dashboard:publish or tyro-dashboard:publish --views first.');

            return self::SUCCESS;
        }

        $this->call('vendor:publish', [
            '--tag' => 'tyro-dashboard-scripts',
            '--force' => true,
        ]);

        return self::SUCCESS;
    }
}