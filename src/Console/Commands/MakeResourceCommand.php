<?php

namespace HasinHayder\TyroDashboard\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;

class MakeResourceCommand extends Command
{
    protected $signature = 'tyro-dashboard:make-resource {name : The name of the resource (e.g. Post)}';

    protected $description = 'Scaffold a new resource (Model, Migration, Controller, etc.) for the application';

    public function handle()
    {
        $name = Str::studly($this->argument('name'));
        
        $this->info("Scaffolding resource: {$name}");

        // 1. Model & Migration
        if ($this->checkAndConfirm("App\\Models\\{$name}", "Model")) {
            $this->call('make:model', [
                'name' => $name,
                '--migration' => true,
            ]);
        }

        // 2. Controller
        $controllerName = "{$name}Controller";
        if ($this->checkAndConfirm("App\\Http\\Controllers\\{$controllerName}", "Controller")) {
            if ($this->confirm("Do you want to create a resource controller for {$name}?", true)) {
                $this->call('make:controller', [
                    'name' => $controllerName,
                    '--resource' => true,
                    '--model' => $name,
                ]);
            }
        }

        // 3. Form Requests
        $storeRequest = "Store{$name}Request";
        if ($this->checkAndConfirm("App\\Http\\Requests\\{$storeRequest}", "Store Request")) {
             if ($this->confirm("Do you want to create a Store Request for {$name}?", false)) {
                $this->call('make:request', ['name' => $storeRequest]);
             }
        }
        
        $updateRequest = "Update{$name}Request";
        if ($this->checkAndConfirm("App\\Http\\Requests\\{$updateRequest}", "Update Request")) {
             if ($this->confirm("Do you want to create an Update Request for {$name}?", false)) {
                $this->call('make:request', ['name' => $updateRequest]);
             }
        }

        // Output Config Snippet
        $resourceKey = Str::snake(Str::plural($name));
        $this->info("\n---------------------------------------------------------");
        $this->info("To enable the CRUD interface, add this to config/tyro-dashboard.php:");
        $this->info("---------------------------------------------------------");
        $this->line("'{$resourceKey}' => [");
        $this->line("    'model' => 'App\\Models\\{$name}',");
        $this->line("    'title' => '".Str::plural(Str::title(Str::snake($name, ' ')))."',");
        $this->line("    'icon' => null, // Optional SVG");
        $this->line("    'fields' => [");
        $this->line("        'name' => ['type' => 'text', 'label' => 'Name', 'rules' => 'required'],");
        $this->line("        // Add more fields here...");
        $this->line("    ],");
        $this->line("],");
        $this->info("---------------------------------------------------------");
    }

    protected function checkAndConfirm($class, $type)
    {
        if (class_exists($class)) {
            $this->warn("{$type} [{$class}] already exists.");
            return false;
        }
        return true;
    }
}
