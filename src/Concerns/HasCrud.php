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
        
        return [
            'model' => static::class,
            'title' => $instance->resourceTitle ?? str_replace('_', ' ', \Illuminate\Support\Str::plural(\Illuminate\Support\Str::snake(class_basename(static::class)))),
            'title_singular' => $instance->resourceTitleSingular ?? str_replace('_', ' ', \Illuminate\Support\Str::snake(class_basename(static::class))),
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
