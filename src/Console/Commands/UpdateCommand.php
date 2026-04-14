<?php

namespace HasinHayder\TyroDashboard\Console\Commands;

use Illuminate\Console\Command;

class UpdateCommand extends Command {
    protected $signature = 'tyro-dashboard:update';

    protected $description = 'Update published Tyro Dashboard resources and published sidebar overrides';

    public function handle(): int {
        $this->call('tyro-dashboard:update-style');
        $this->call('tyro-dashboard:update-script');
        $this->call('tyro-dashboard:update-config');

        $this->updatePublishedSidebar(
            resource_path('views/vendor/tyro-dashboard/partials/admin-sidebar.blade.php')
        );
        $this->updatePublishedSidebar(
            resource_path('views/vendor/tyro-dashboard/partials/user-sidebar.blade.php')
        );

        $this->updatePublishedFlashMessages(
            resource_path('views/vendor/tyro-dashboard/partials/flash-messages.blade.php')
        );

        return self::SUCCESS;
    }

    protected function updatePublishedSidebar(string $sidebarPath): void {
        if (! file_exists($sidebarPath)) {
            return;
        }

        $oldNavLine = '<nav class="sidebar-nav">';
        $newNavLine = '<nav class="sidebar-nav sidebar-accordion"' . "\n"
            . '        data-sidebar-accordion' . "\n"
            . '        data-sidebar-accordion-compact="{{ config(\'tyro-dashboard.branding.sidebar_accordion_compact\', false) ? \'true\' : \'false\' }}">';

        $content = file_get_contents($sidebarPath);

        if ($content === false || strpos($content, $oldNavLine) === false) {
            return;
        }

        $updatedContent = str_replace($oldNavLine, $newNavLine, $content);

        if ($updatedContent !== $content) {
            file_put_contents($sidebarPath, $updatedContent);
        }
    }

    protected function updatePublishedFlashMessages(string $flashMessagesPath): void {
        if (! file_exists($flashMessagesPath)) {
            return;
        }

        $updatedContent = @file_get_contents(__DIR__ . '/../../../resources/views/partials/flash-messages.blade.php');

        if ($updatedContent === false || $updatedContent === '') {
            return;
        }

        file_put_contents($flashMessagesPath, $updatedContent);
    }
}