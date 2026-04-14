<?php

namespace HasinHayder\TyroDashboard\Console\Commands;

use Illuminate\Console\Command;

class UpdateStyleCommand extends Command {
    protected $signature = 'tyro-dashboard:update-style';

    protected $description = 'Update published tyro-dashboard styles with the latest version';

    public function handle(): int {
        $this->call('tyro-dashboard:publish-style', ['--force' => true]);

        return self::SUCCESS;
    }
}