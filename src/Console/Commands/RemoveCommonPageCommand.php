<?php

namespace HasinHayder\TyroDashboard\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;

class RemoveCommonPageCommand extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'tyro-dashboard:remove-common-page
                            {name? : The name of the page to remove}';

    /**
     * The console command description.
     */
    protected $description = 'Remove a common dashboard page';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $name = $this->argument('name');
        
        // Ask for page name if not provided
        if (!$name) {
            $name = $this->ask('What is the name of the common page to remove?');
            
            if (!$name) {
                $this->error('Page name is required.');
                return self::FAILURE;
            }
        }
        
        $pageName = Str::slug($name);
        $pageTitle = Str::title(str_replace(['-', '_'], ' ', $name));

        $this->info('');
        $this->info('  ╔════════════════════════════════════════╗');
        $this->info('  ║  Removing Common Dashboard Page        ║');
        $this->info('  ╚════════════════════════════════════════╝');
        $this->info('');

        // Check if the view file exists
        $viewFile = resource_path('views/dashboard/' . $pageName . '.blade.php');
        
        if (!file_exists($viewFile)) {
            $this->error('   ✗ Page not found: ' . $pageName);
            $this->info('   View file does not exist: views/dashboard/' . $pageName . '.blade.php');
            $this->info('');
            return self::FAILURE;
        }

        // Show warning and confirm
        $this->warn('   ⚠ WARNING: This will permanently delete the following:');
        $this->warn('     • View file: views/dashboard/' . $pageName . '.blade.php');
        $this->warn('     • Route from routes/web.php');
        $this->warn('     • Sidebar links from user-sidebar.blade.php and admin-sidebar.blade.php');
        $this->info('');

        if (!$this->confirm('Are you sure you want to remove this page?', false)) {
            $this->info('   Operation cancelled.');
            $this->info('');
            return self::SUCCESS;
        }

        $this->info('');

        // Remove the view file
        if (unlink($viewFile)) {
            $this->info('   ✓ Deleted view file');
        } else {
            $this->error('   ✗ Failed to delete view file');
        }

        // Remove route from web.php
        $this->removeRouteFromWebFile($pageName);

        // Remove link from both sidebars
        $this->removeLinkFromSidebar($pageName, 'user');
        $this->removeLinkFromSidebar($pageName, 'admin');

        $this->info('');
        $this->info('  ╔════════════════════════════════════════╗');
        $this->info('  ║          Page Removed!                 ║');
        $this->info('  ╚════════════════════════════════════════╝');
        $this->info('');
        $this->info('  Page "' . $pageTitle . '" has been successfully removed.');
        $this->info('');

        return self::SUCCESS;
    }

    /**
     * Remove route from web.php file.
     */
    protected function removeRouteFromWebFile(string $pageName): void
    {
        $webFile = base_path('routes/web.php');
        
        if (!file_exists($webFile)) {
            $this->warn('   ⚠ routes/web.php not found');
            return;
        }

        $content = file_get_contents($webFile);
        
        // Pattern to match the route line (auth only middleware)
        $patterns = [
            "/Route::view\('dashboard\/{$pageName}', 'dashboard\.{$pageName}'\)->middleware\(\['auth'\]\)->name\('dashboard\.{$pageName}'\);?\n?/",
            "/Route::view\(\"dashboard\/{$pageName}\", \"dashboard\.{$pageName}\"\)->middleware\(\['auth'\]\)->name\('dashboard\.{$pageName}'\);?\n?/",
        ];

        $found = false;
        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $content)) {
                $content = preg_replace($pattern, '', $content);
                $found = true;
                break;
            }
        }

        if ($found) {
            // Clean up multiple consecutive blank lines
            $content = preg_replace("/\n{3,}/", "\n\n", $content);
            file_put_contents($webFile, $content);
            $this->info('   ✓ Removed route from routes/web.php');
        } else {
            $this->warn('   ⚠ Route not found in web.php (may have been removed already)');
        }
    }

    /**
     * Remove link from sidebar.
     */
    protected function removeLinkFromSidebar(string $pageName, string $sidebarType): void
    {
        $sidebarFile = resource_path("views/vendor/tyro-dashboard/partials/{$sidebarType}-sidebar.blade.php");
        
        if (!file_exists($sidebarFile)) {
            $this->warn("   ⚠ {$sidebarType}-sidebar.blade.php not found");
            return;
        }

        $content = file_get_contents($sidebarFile);
        
        // Pattern to match the sidebar link block
        $pattern = "/\n\s*<a href=\"\{\{ route\('dashboard\.{$pageName}'\) \}\}\".*?<\/a>\n/s";
        
        if (preg_match($pattern, $content)) {
            $content = preg_replace($pattern, '', $content);
            file_put_contents($sidebarFile, $content);
            $this->info("   ✓ Removed link from {$sidebarType} sidebar");
        } else {
            $this->warn("   ⚠ Link not found in {$sidebarType} sidebar (may have been removed already)");
        }
    }
}
