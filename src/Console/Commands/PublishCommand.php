<?php

namespace HasinHayder\TyroDashboard\Console\Commands;

use Illuminate\Console\Command;

class PublishCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tyro-dashboard:publish
                            {--force : Overwrite existing files}
                            {--all : Publish all resources}
                            {--style : Publish styles only}
                            {--views : Publish all views}
                            {--user : Publish user views only}
                            {--admin : Publish admin views only}
                            {--config : Publish config only}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Publish Tyro Dashboard resources (views, config, styles)';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('  ╔════════════════════════════════════════╗');
        $this->info('  ║     Tyro Dashboard Resource Publisher  ║');
        $this->info('  ╚════════════════════════════════════════╝');
        $this->info('');

        // If specific flags are provided, process them
        if ($this->option('all') || $this->option('style') || $this->option('views') || $this->option('user') || $this->option('admin') || $this->option('config')) {
            $this->processFlags();
            return self::SUCCESS;
        }

        // Interactive mode
        $choice = $this->choice(
            'What would you like to publish?',
            [
                'All resources (Config & Views)',
                'Configuration only',
                'Styles & Theme only',
                'All views',
                'Admin views only',
                'User views only',
                'Cancel',
            ],
            0
        );

        switch ($choice) {
            case 'All resources (Config & Views)':
                $this->publishTag('tyro-dashboard', 'All resources');
                break;
            case 'Configuration only':
                $this->publishTag('tyro-dashboard-config', 'Configuration');
                break;
            case 'Styles & Theme only':
                $this->publishTag('tyro-dashboard-styles', 'Styles & Theme');
                break;
            case 'All views':
                $this->publishTag('tyro-dashboard-views', 'All views');
                break;
            case 'Admin views only':
                $this->publishTag('tyro-dashboard-views-admin', 'Admin views');
                break;
            case 'User views only':
                $this->publishTag('tyro-dashboard-views-user', 'User views');
                break;
            case 'Cancel':
                $this->info('Operation cancelled.');
                break;
        }

        return self::SUCCESS;
    }

    /**
     * Process command line flags.
     */
    protected function processFlags(): void
    {
        if ($this->option('all')) {
            $this->publishTag('tyro-dashboard', 'All resources');
            return;
        }

        if ($this->option('config')) {
            $this->publishTag('tyro-dashboard-config', 'Configuration');
        }

        if ($this->option('style')) {
            $this->publishTag('tyro-dashboard-styles', 'Styles & Theme');
        }

        if ($this->option('views')) {
            $this->publishTag('tyro-dashboard-views', 'All views');
        }

        if ($this->option('admin')) {
            $this->publishTag('tyro-dashboard-views-admin', 'Admin views');
        }

        if ($this->option('user')) {
            $this->publishTag('tyro-dashboard-views-user', 'User views');
        }
    }

    /**
     * Publish resources for a specific tag.
     */
    protected function publishTag(string $tag, string $label): void
    {
        $this->info("Publishing {$label}...");
        
        $params = [
            '--tag' => $tag,
        ];

        if ($this->option('force')) {
            $params['--force'] = true;
        }

        $this->call('vendor:publish', $params);
        
        $this->info("✓ {$label} published successfully.");
        $this->info('');
    }
}
