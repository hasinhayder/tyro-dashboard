<?php

namespace HasinHayder\TyroDashboard\Concerns;

trait HasCrud
{
    /**
     * Get the resource configuration for this model
     */
    public static function getResourceConfig(): array
    {
        $instance = new static();
        
        $defaultTitle = \Illuminate\Support\Str::title(
            str_replace('_', ' ', \Illuminate\Support\Str::plural(\Illuminate\Support\Str::snake(class_basename(static::class))))
        );
        
        $defaultTitleSingular = \Illuminate\Support\Str::title(
            str_replace('_', ' ', \Illuminate\Support\Str::snake(class_basename(static::class)))
        );
        
        return [
            'model' => static::class,
            'title' => $instance->resourceTitle ?? $defaultTitle,
            'title_singular' => $instance->resourceTitleSingular ?? $defaultTitleSingular,
            'fields' => $instance->resourceFields ?? [],
            'roles' => $instance->resourceRoles ?? [],
            'readonly' => $instance->resourceReadonly ?? [],
        ];
    }

    /**
     * Get the resource key for routing
     */
    public static function getResourceKey(): string
    {
        $instance = new static();
        return $instance->resourceKey ?? \Illuminate\Support\Str::plural(\Illuminate\Support\Str::snake(class_basename(static::class)));
    }
}
