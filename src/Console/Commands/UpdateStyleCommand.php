<?php

namespace HasinHayder\TyroDashboard\Console\Commands;

use Illuminate\Console\Command;

class UpdateStyleCommand extends Command {
    protected $signature = 'tyro-dashboard:update-style';

    protected $description = 'Update published tyro-dashboard styles with the latest version';

    public function handle(): int {
        $publishedStylesPath = resource_path('views/vendor/tyro-dashboard/partials/styles.blade.php');

        if (! file_exists($publishedStylesPath)) {
            $this->warn('  ⚠ styles.blade.php is not published yet. Run tyro-dashboard:publish or tyro-dashboard:publish --views first.');

            return self::SUCCESS;
        }

        $this->call('tyro-dashboard:publish-style', ['--force' => true]);

        return self::SUCCESS;
    }
}
