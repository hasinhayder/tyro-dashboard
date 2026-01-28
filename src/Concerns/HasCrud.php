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
        
        // Get fields from $resourceFields or auto-generate from $fillable
        $fields = $instance->resourceFields ?? static::generateFieldsFromFillable($instance);
        
        return [
            'model' => static::class,
            'title' => $instance->resourceTitle ?? $defaultTitle,
            'title_singular' => $instance->resourceTitleSingular ?? $defaultTitleSingular,
            'fields' => $fields,
            'roles' => $instance->resourceRoles ?? [],
            'readonly' => $instance->resourceReadonly ?? [],
        ];
    }
    
    /**
     * Generate field configuration from fillable attributes
     */
    protected static function generateFieldsFromFillable($instance): array
    {
        $fields = [];
        $fillable = $instance->getFillable();
        
        foreach ($fillable as $field) {
            $fields[$field] = static::guessFieldConfig($field);
        }
        
        return $fields;
    }
    
    /**
     * Guess field configuration based on field name
     */
    protected static function guessFieldConfig(string $fieldName): array
    {
        $config = [
            'label' => \Illuminate\Support\Str::headline($fieldName),
        ];
        
        // Guess field type based on name patterns
        if (\Illuminate\Support\Str::endsWith($fieldName, '_id')) {
            // Foreign key - likely a select field
            $config['type'] = 'select';
            $relationName = \Illuminate\Support\Str::camel(
                \Illuminate\Support\Str::beforeLast($fieldName, '_id')
            );
            $config['relationship'] = $relationName;
        } elseif (in_array($fieldName, ['email', 'email_address'])) {
            $config['type'] = 'email';
            $config['rules'] = 'nullable|email';
            $config['searchable'] = true;
        } elseif (in_array($fieldName, ['password', 'password_hash'])) {
            $config['type'] = 'password';
            $config['rules'] = 'nullable|min:8';
            $config['hide_in_index'] = true;
        } elseif (\Illuminate\Support\Str::contains($fieldName, ['description', 'bio', 'content', 'body', 'notes', 'comment'])) {
            $config['type'] = 'textarea';
            $config['hide_in_index'] = true;
        } elseif (\Illuminate\Support\Str::contains($fieldName, ['date']) && !\Illuminate\Support\Str::contains($fieldName, ['update', 'create'])) {
            $config['type'] = 'date';
        } elseif (\Illuminate\Support\Str::contains($fieldName, ['time']) && !\Illuminate\Support\Str::contains($fieldName, ['update', 'create'])) {
            $config['type'] = 'time';
        } elseif (in_array($fieldName, ['price', 'amount', 'cost', 'salary', 'wage'])) {
            $config['type'] = 'number';
            $config['rules'] = 'nullable|numeric|min:0';
        } elseif (\Illuminate\Support\Str::contains($fieldName, ['quantity', 'count', 'number', 'age', 'year', 'population', 'pages'])) {
            $config['type'] = 'number';
            $config['rules'] = 'nullable|integer|min:0';
        } elseif (\Illuminate\Support\Str::startsWith($fieldName, ['is_', 'has_', 'can_', 'should_', 'must_'])) {
            $config['type'] = 'boolean';
        } elseif (\Illuminate\Support\Str::contains($fieldName, ['image', 'photo', 'picture', 'avatar', 'file', 'document', 'attachment'])) {
            $config['type'] = 'file';
            $config['hide_in_index'] = true;
        } elseif (\Illuminate\Support\Str::contains($fieldName, ['url', 'link', 'website'])) {
            $config['type'] = 'url';
            $config['rules'] = 'nullable|url';
        } else {
            // Default to text
            $config['type'] = 'text';
            $config['rules'] = 'nullable|max:255';
        }
        
        // Add searchable flag for common searchable fields
        if (!isset($config['searchable']) && in_array($fieldName, ['name', 'title', 'code', 'slug'])) {
            $config['searchable'] = true;
            $config['sortable'] = true;
        }
        
        return $config;
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
