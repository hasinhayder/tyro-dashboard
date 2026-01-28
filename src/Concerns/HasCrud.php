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
        $tableName = $instance->getTable();
        
        foreach ($fillable as $field) {
            $fields[$field] = static::guessFieldConfig($field, $tableName);
        }
        
        return $fields;
    }
    
    /**
     * Guess field configuration based on field name and database schema
     */
    protected static function guessFieldConfig(string $fieldName, string $tableName): array
    {
        $config = [
            'label' => \Illuminate\Support\Str::headline($fieldName),
        ];
        
        // Try to get column info from database
        $columnType = null;
        $enumValues = null;
        $isNullable = true;
        $maxLength = null;
        
        try {
            if (\Illuminate\Support\Facades\Schema::hasColumn($tableName, $fieldName)) {
                $columnType = \Illuminate\Support\Facades\Schema::getColumnType($tableName, $fieldName);
                
                // Get full column details
                $connection = \Illuminate\Support\Facades\Schema::getConnection();
                $schemaManager = $connection->getDoctrineSchemaManager();
                $columns = $schemaManager->listTableColumns($tableName);
                
                if (isset($columns[$fieldName])) {
                    $column = $columns[$fieldName];
                    $isNullable = !$column->getNotnull();
                    
                    // Get string length
                    if ($column->getLength()) {
                        $maxLength = $column->getLength();
                    }
                    
                    // Check for enum/set values
                    if (method_exists($column->getType(), 'getValues')) {
                        $enumValues = $column->getType()->getValues();
                    }
                    
                    // For MySQL enum, we need to parse it differently
                    if ($columnType === 'string' && !$enumValues) {
                        $platform = $connection->getDoctrineConnection()->getDatabasePlatform()->getName();
                        if ($platform === 'mysql') {
                            $result = $connection->select("SHOW COLUMNS FROM `{$tableName}` WHERE Field = ?", [$fieldName]);
                            if (!empty($result) && isset($result[0]->Type)) {
                                if (preg_match("/^enum\((.*)\)$/", $result[0]->Type, $matches)) {
                                    $enumValues = array_map(function($value) {
                                        return trim($value, "'");
                                    }, explode(',', $matches[1]));
                                }
                            }
                        }
                    }
                }
            }
        } catch (\Exception $e) {
            // If schema introspection fails, continue with name-based guessing
        }
        
        // If we found enum values, create a select field
        if ($enumValues && !empty($enumValues)) {
            $config['type'] = 'select';
            $config['options'] = array_combine($enumValues, array_map('ucfirst', $enumValues));
            $config['rules'] = $isNullable ? 'nullable' : 'required';
            return $config;
        }
        
        // Use database column type if available
        if ($columnType) {
            switch ($columnType) {
                case 'boolean':
                    $config['type'] = 'boolean';
                    return $config;
                    
                case 'integer':
                case 'bigint':
                case 'smallint':
                    $config['type'] = 'number';
                    $config['rules'] = ($isNullable ? 'nullable|' : 'required|') . 'integer';
                    return $config;
                    
                case 'decimal':
                case 'float':
                case 'double':
                    $config['type'] = 'number';
                    $config['rules'] = ($isNullable ? 'nullable|' : 'required|') . 'numeric';
                    return $config;
                    
                case 'text':
                case 'longtext':
                case 'mediumtext':
                    $config['type'] = 'textarea';
                    $config['hide_in_index'] = true;
                    $config['rules'] = $isNullable ? 'nullable' : 'required';
                    return $config;
                    
                case 'date':
                    $config['type'] = 'date';
                    $config['rules'] = $isNullable ? 'nullable|date' : 'required|date';
                    return $config;
                    
                case 'datetime':
                case 'timestamp':
                    $config['type'] = 'datetime-local';
                    $config['rules'] = $isNullable ? 'nullable' : 'required';
                    return $config;
                    
                case 'time':
                    $config['type'] = 'time';
                    $config['rules'] = $isNullable ? 'nullable' : 'required';
                    return $config;
            }
        }
        
        // Guess field type based on name patterns
        if (\Illuminate\Support\Str::endsWith($fieldName, '_id')) {
            // Foreign key - likely a select field
            $config['type'] = 'select';
            $relationName = \Illuminate\Support\Str::camel(
                \Illuminate\Support\Str::beforeLast($fieldName, '_id')
            );
            $config['relationship'] = $relationName;
            $config['rules'] = $isNullable ? 'nullable' : 'required';
        } elseif (in_array($fieldName, ['email', 'email_address'])) {
            $config['type'] = 'email';
            $rules = ($isNullable ? 'nullable|' : 'required|') . 'email';
            if ($maxLength) {
                $rules .= '|max:' . $maxLength;
            }
            $config['rules'] = $rules;
            $config['searchable'] = true;
        } elseif (in_array($fieldName, ['password', 'password_hash'])) {
            $config['type'] = 'password';
            $config['rules'] = ($isNullable ? 'nullable|' : 'required|') . 'min:8';
            $config['hide_in_index'] = true;
        } elseif (\Illuminate\Support\Str::contains($fieldName, ['description', 'bio', 'content', 'body', 'notes', 'comment'])) {
            $config['type'] = 'textarea';
            $config['hide_in_index'] = true;
            $config['rules'] = $isNullable ? 'nullable' : 'required';
        } elseif (\Illuminate\Support\Str::contains($fieldName, ['date']) && !\Illuminate\Support\Str::contains($fieldName, ['update', 'create'])) {
            $config['type'] = 'date';
            $config['rules'] = $isNullable ? 'nullable|date' : 'required|date';
        } elseif (\Illuminate\Support\Str::contains($fieldName, ['time']) && !\Illuminate\Support\Str::contains($fieldName, ['update', 'create'])) {
            $config['type'] = 'time';
            $config['rules'] = $isNullable ? 'nullable' : 'required';
        } elseif (in_array($fieldName, ['price', 'amount', 'cost', 'salary', 'wage'])) {
            $config['type'] = 'number';
            $config['rules'] = ($isNullable ? 'nullable|' : 'required|') . 'numeric|min:0';
        } elseif (\Illuminate\Support\Str::contains($fieldName, ['quantity', 'count', 'number', 'age', 'year', 'population', 'pages'])) {
            $config['type'] = 'number';
            $config['rules'] = ($isNullable ? 'nullable|' : 'required|') . 'integer|min:0';
        } elseif (\Illuminate\Support\Str::startsWith($fieldName, ['is_', 'has_', 'can_', 'should_', 'must_'])) {
            $config['type'] = 'boolean';
        } elseif (\Illuminate\Support\Str::contains($fieldName, ['image', 'photo', 'picture', 'avatar', 'file', 'document', 'attachment'])) {
            $config['type'] = 'file';
            $config['hide_in_index'] = true;
        } elseif (\Illuminate\Support\Str::contains($fieldName, ['url', 'link', 'website'])) {
            $config['type'] = 'url';
            $rules = ($isNullable ? 'nullable|' : 'required|') . 'url';
            if ($maxLength) {
                $rules .= '|max:' . $maxLength;
            }
            $config['rules'] = $rules;
        } else {
            // Default to text
            $config['type'] = 'text';
            $rules = $isNullable ? 'nullable' : 'required';
            if ($maxLength) {
                $rules .= '|max:' . $maxLength;
            }
            $config['rules'] = $rules;
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
