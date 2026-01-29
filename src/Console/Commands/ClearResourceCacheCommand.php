<?php

namespace HasinHayder\TyroDashboard\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use HasinHayder\TyroDashboard\Concerns\HasCrud;

/**
 * @since 1.6.1
 */
class ClearResourceCacheCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tyro-dashboard:clear-cache 
                            {--model= : Specific model class to clear cache for}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear the cached field configurations for Dynamic CRUD resources';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $modelClass = $this->option('model');
        
        if ($modelClass) {
            // Clear cache for specific model
            if (!class_exists($modelClass)) {
                $this->error("Model class {$modelClass} not found.");
                return 1;
            }
            
            if (!method_exists($modelClass, 'clearFieldCache')) {
                $this->error("Model {$modelClass} does not use the HasCrud trait.");
                return 1;
            }
            
            $modelClass::clearFieldCache();
            $this->info("Cache cleared for {$modelClass}");
            return 0;
        }
        
        // Clear cache for all models with HasCrud trait
        $models = $this->getModelsWithTrait(HasCrud::class);
        
        if (empty($models)) {
            $this->info('No models found with HasCrud trait.');
            return 0;
        }
        
        $cleared = 0;
        foreach ($models as $model) {
            $model::clearFieldCache();
            $cleared++;
        }
        
        $this->info("Cache cleared for {$cleared} model(s).");
        
        return 0;
    }
    
    /**
     * Get all models using the HasCrud trait
     */
    protected function getModelsWithTrait($trait): array
    {
        $models = [];
        $modelPath = app_path('Models');
        
        if (!is_dir($modelPath)) {
            return $models;
        }
        
        $files = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($modelPath)
        );
        
        foreach ($files as $file) {
            if ($file->isDir() || $file->getExtension() !== 'php') {
                continue;
            }
            
            $relativePath = str_replace($modelPath . DIRECTORY_SEPARATOR, '', $file->getPathname());
            $className = 'App\\Models\\' . str_replace(['/', '.php'], ['\\', ''], $relativePath);
            
            if (class_exists($className)) {
                $reflection = new \ReflectionClass($className);
                if ($reflection->hasMethod('clearFieldCache')) {
                    $models[] = $className;
                }
            }
        }
        
        return $models;
    }
}
