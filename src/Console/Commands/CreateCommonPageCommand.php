<?php

namespace HasinHayder\TyroDashboard\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;

class CreateCommonPageCommand extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'tyro-dashboard:create-common-page
                            {name? : The name of the page}
                            {--force : Overwrite existing files}';

    /**
     * The console command description.
     */
    protected $description = 'Create a new common dashboard page (visible in both user and admin sidebars)';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $name = $this->argument('name');
        
        // Ask for page name if not provided
        if (!$name) {
            $name = $this->ask('What is the name of the common page?');
            
            if (!$name) {
                $this->error('Page name is required.');
                return self::FAILURE;
            }
        }
        $pageName = Str::slug($name);
        $pageTitle = Str::title(str_replace(['-', '_'], ' ', $name));

        $this->info('');
        $this->info('  ╔════════════════════════════════════════╗');
        $this->info('  ║   Creating Common Dashboard Page       ║');
        $this->info('  ╚════════════════════════════════════════╝');
        $this->info('');

        // Check if views directory exists, if not publish
        $viewsPath = resource_path('views/vendor/tyro-dashboard');
        if (!file_exists($viewsPath)) {
            $this->info('Publishing dashboard views...');
            $this->callSilently('vendor:publish', [
                '--tag' => 'tyro-dashboard-views',
            ]);
            $this->info('   ✓ Dashboard views published');
            $this->info('');
        }

        // Create dashboard directory if it doesn't exist
        $dashboardViewPath = resource_path('views/dashboard');
        if (!is_dir($dashboardViewPath)) {
            mkdir($dashboardViewPath, 0755, true);
            $this->info('   ✓ Created dashboard views directory');
        }

        // Create the view file
        $viewFile = $dashboardViewPath . '/' . $pageName . '.blade.php';
        
        if (file_exists($viewFile) && !$this->option('force')) {
            $this->error('   ✗ View file already exists: views/dashboard/' . $pageName . '.blade.php');
            $this->info('   Use --force to overwrite');
            return self::FAILURE;
        }

        $viewContent = $this->getViewTemplate($pageTitle);
        file_put_contents($viewFile, $viewContent);
        $this->info('   ✓ Created view: views/dashboard/' . $pageName . '.blade.php');

        // Add route to web.php
        $this->addRouteToWebFile($pageName);

        // Add link to both user and admin sidebars
        $this->addLinkToSidebar($pageName, $pageTitle, 'user');
        $this->addLinkToSidebar($pageName, $pageTitle, 'admin');

        $this->info('');
        $this->info('  ╔════════════════════════════════════════╗');
        $this->info('  ║            Page Created!               ║');
        $this->info('  ╚════════════════════════════════════════╝');
        $this->info('');
        $this->info('  Page URL: /dashboard/' . $pageName);
        $this->info('  View: resources/views/dashboard/' . $pageName . '.blade.php');
        $this->info('  Visible in: User & Admin sidebars');
        $this->info('');

        return self::SUCCESS;
    }

    /**
     * Get the view template content.
     */
    protected function getViewTemplate(string $pageTitle): string
    {
        return <<<BLADE
@extends('tyro-dashboard::layouts.app')

@section('title', '{$pageTitle}')

@section('breadcrumb')
<a href="{{ route('tyro-dashboard.index') }}">Dashboard</a>
<span class="breadcrumb-separator">/</span>
<span>{$pageTitle}</span>
@endsection

@section('content')
<div class="page-header">
    <div class="page-header-row">
        <div>
            <h1 class="page-title">{$pageTitle}</h1>
            <p class="page-description" style="font-size: 1rem;">A common page accessible to all users.</p>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h3 class="card-title" style="font-size: 1.0625rem;">{$pageTitle} Content</h3>
    </div>
    <div class="card-body">
        <p>This is a common dashboard page visible to both regular users and administrators. Start building your content here.</p>
    </div>
</div>
@endsection

BLADE;
    }

    /**
     * Add route to web.php file.
     */
    protected function addRouteToWebFile(string $pageName): void
    {
        $webFile = base_path('routes/web.php');
        
        if (!file_exists($webFile)) {
            $this->warn('   ⚠ routes/web.php not found');
            return;
        }

        $content = file_get_contents($webFile);
        $routeLine = "Route::view('dashboard/{$pageName}', 'dashboard.{$pageName}')->middleware(['auth'])->name('dashboard.{$pageName}');";

        // Check if route already exists
        if (strpos($content, "dashboard.{$pageName}") !== false) {
            $this->warn('   ⚠ Route already exists in web.php');
            return;
        }

        // Add route at the end of the file
        $content = rtrim($content) . "\n\n" . $routeLine . "\n";
        file_put_contents($webFile, $content);
        
        $this->info('   ✓ Added route to routes/web.php');
    }

    /**
     * Add link to sidebar.
     */
    protected function addLinkToSidebar(string $pageName, string $pageTitle, string $sidebarType): void
    {
        $sidebarFile = resource_path("views/vendor/tyro-dashboard/partials/{$sidebarType}-sidebar.blade.php");
        
        if (!file_exists($sidebarFile)) {
            $this->warn("   ⚠ {$sidebarType}-sidebar.blade.php not found. Publish views first if you want to customize the sidebar.");
            return;
        }

        $content = file_get_contents($sidebarFile);
        
        // Check if link already exists
        if (strpos($content, "dashboard.{$pageName}") !== false) {
            $this->warn("   ⚠ Link already exists in {$sidebarType} sidebar");
            return;
        }

        // Create the link HTML
        $linkHtml = <<<HTML

            <a href="{{ route('dashboard.{$pageName}') }}" class="sidebar-link {{ request()->routeIs('dashboard.{$pageName}') ? 'active' : '' }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                {$pageTitle}
            </a>
HTML;

        // Find the Menu section and add the link
        if ($sidebarType === 'admin') {
            // For admin, try to add to Menu section first, fallback to Administration
            $pattern = '/(<div class="sidebar-section">.*?<div class="sidebar-section-title">Menu<\/div>.*?<a href=.*?My Profile.*?<\/a>)/s';
        } else {
            // For user, add to Menu section
            $pattern = '/(<div class="sidebar-section">.*?<div class="sidebar-section-title">Menu<\/div>.*?<a href=.*?My Profile.*?<\/a>)/s';
        }
        
        if (preg_match($pattern, $content, $matches)) {
            $replacement = $matches[1] . $linkHtml;
            $content = preg_replace($pattern, $replacement, $content);
            file_put_contents($sidebarFile, $content);
            $this->info("   ✓ Added link to {$sidebarType} sidebar");
        } else {
            $this->warn("   ⚠ Could not automatically add link to {$sidebarType} sidebar. Please add manually.");
        }
    }
}
