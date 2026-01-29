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
        
        // Get fields from $resourceFields or auto-generate from $fillable (with caching)
        $fields = $instance->resourceFields ?? static::getCachedFieldsOrGenerate($instance);
        
        // Apply field overrides if provided
        if (isset($instance->resourceFieldOverrides) && is_array($instance->resourceFieldOverrides)) {
            foreach ($instance->resourceFieldOverrides as $fieldName => $overrides) {
                if (isset($fields[$fieldName])) {
                    // Merge overrides with existing field config
                    $fields[$fieldName] = array_merge($fields[$fieldName], $overrides);
                }
            }
        }
        
        // Get resource-level config from tyro-dashboard config file (for upload overrides)
        $resourceKey = static::getResourceKey();
        $resourceConfig = config('tyro-dashboard.resources.' . $resourceKey, []);
        
        return [
            'model' => static::class,
            'title' => $instance->resourceTitle ?? $defaultTitle,
            'title_singular' => $instance->resourceTitleSingular ?? $defaultTitleSingular,
            'fields' => $fields,
            'roles' => $instance->resourceRoles ?? [],
            'readonly' => $instance->resourceReadonly ?? [],
            'upload_disk' => $instance->resourceUploadDisk ?? $resourceConfig['upload_disk'] ?? config('tyro-dashboard.uploads.disk', 'public'),
            'upload_directory' => $instance->resourceUploadDirectory ?? $resourceConfig['upload_directory'] ?? config('tyro-dashboard.uploads.directory', 'uploads'),
        ];
    }
    
    /**
     * Get cached fields or generate and cache them
     */
    protected static function getCachedFieldsOrGenerate($instance): array
    {
        $modelClass = static::class;
        $fillable = $instance->getFillable();
        
        // Create a hash of fillable to detect changes
        $fillableHash = md5(serialize($fillable));
        
        // Cache key includes model class and fillable hash
        $cacheKey = 'tyro_dashboard_fields_' . md5($modelClass) . '_' . $fillableHash;
        
        // Try to get from cache (6 hours = 21600 seconds)
        $cached = \Illuminate\Support\Facades\Cache::get($cacheKey);
        
        if ($cached !== null) {
            return $cached;
        }
        
        // Generate fields if not cached
        $fields = static::generateFieldsFromFillable($instance);
        
        // Cache for 6 hours
        \Illuminate\Support\Facades\Cache::put($cacheKey, $fields, 21600);
        
        // Clear old cache entries for this model (with different fillable hash)
        static::clearOldCacheEntries($modelClass, $fillableHash);
        
        return $fields;
    }
    
    /**
     * Clear old cache entries for this model with different fillable hash
     */
    protected static function clearOldCacheEntries(string $modelClass, string $currentHash): void
    {
        // Store the current hash to help with cleanup
        $hashKey = 'tyro_dashboard_hash_' . md5($modelClass);
        $oldHash = \Illuminate\Support\Facades\Cache::get($hashKey);
        
        if ($oldHash && $oldHash !== $currentHash) {
            // Clear the old cache entry
            $oldCacheKey = 'tyro_dashboard_fields_' . md5($modelClass) . '_' . $oldHash;
            \Illuminate\Support\Facades\Cache::forget($oldCacheKey);
        }
        
        // Store the new hash
        \Illuminate\Support\Facades\Cache::put($hashKey, $currentHash, 21600);
    }
    
    /**
     * Clear the cached fields for this model
     */
    public static function clearFieldCache(): void
    {
        $modelClass = static::class;
        $hashKey = 'tyro_dashboard_hash_' . md5($modelClass);
        $currentHash = \Illuminate\Support\Facades\Cache::get($hashKey);
        
        if ($currentHash) {
            $cacheKey = 'tyro_dashboard_fields_' . md5($modelClass) . '_' . $currentHash;
            \Illuminate\Support\Facades\Cache::forget($cacheKey);
            \Illuminate\Support\Facades\Cache::forget($hashKey);
        }
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
        
        // Add relationship fields
        $relationshipFields = static::detectRelationships($instance);
        foreach ($relationshipFields as $key => $config) {
            $fields[$key] = $config;
        }
        
        return $fields;
    }
    
    /**
     * Detect relationships from model methods
     */
    protected static function detectRelationships($instance): array
    {
        $fields = [];
        $reflection = new \ReflectionClass($instance);
        
        foreach ($reflection->getMethods(\ReflectionMethod::IS_PUBLIC) as $method) {
            // Skip magic methods and constructor
            if ($method->isStatic() || $method->isConstructor() || \Illuminate\Support\Str::startsWith($method->getName(), '__')) {
                continue;
            }
            
            // Skip if method has parameters (relationships shouldn't need params)
            if ($method->getNumberOfParameters() > 0) {
                continue;
            }
            
            // Skip methods from Eloquent Model base class
            if ($method->getDeclaringClass()->getName() === 'Illuminate\Database\Eloquent\Model') {
                continue;
            }
            
            // Try to call the method and check if it returns a relationship
            try {
                $return = $method->invoke($instance);
                
                if ($return instanceof \Illuminate\Database\Eloquent\Relations\Relation) {
                    $methodName = $method->getName();
                    $relatedModel = get_class($return->getRelated());
                    
                    // Get option label from related model (try common fields)
                    $optionLabel = 'name';
                    foreach (['name', 'title', 'label', 'email', 'code'] as $field) {
                        if (\Illuminate\Support\Facades\Schema::hasColumn($return->getRelated()->getTable(), $field)) {
                            $optionLabel = $field;
                            break;
                        }
                    }
                    
                    if ($return instanceof \Illuminate\Database\Eloquent\Relations\BelongsTo) {
                        // BelongsTo: Don't add as separate field, it's already in fillable as foreign key
                        // The foreign key field will be auto-configured with relationship
                        continue;
                    } elseif ($return instanceof \Illuminate\Database\Eloquent\Relations\BelongsToMany) {
                        // BelongsToMany: Many-to-many relationship
                        $fields[$methodName] = [
                            'type' => 'select',
                            'label' => \Illuminate\Support\Str::headline($methodName),
                            'relationship' => $methodName,
                            'option_label' => $optionLabel,
                            'multiple' => true,
                            'hide_in_index' => true,
                        ];
                    } elseif ($return instanceof \Illuminate\Database\Eloquent\Relations\HasMany || 
                              $return instanceof \Illuminate\Database\Eloquent\Relations\HasOne) {
                        // HasMany/HasOne: Usually displayed on the related model's side
                        // We can add it but hide by default
                        $fields[$methodName] = [
                            'type' => 'select',
                            'label' => \Illuminate\Support\Str::headline($methodName),
                            'relationship' => $methodName,
                            'option_label' => $optionLabel,
                            'multiple' => $return instanceof \Illuminate\Database\Eloquent\Relations\HasMany,
                            'hide_in_index' => true,
                        ];
                    }
                }
            } catch (\Exception $e) {
                // Skip methods that can't be invoked or don't return relationships
                continue;
            }
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
        
        // Guess field type based on name patterns (check these FIRST before falling back to column type)
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
        } elseif (\Illuminate\Support\Str::contains($fieldName, ['markdown'])) {
            $config['type'] = 'markdown';
            $config['hide_in_index'] = true;
            $config['rules'] = $isNullable ? 'nullable' : 'required';
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
        } elseif (\Illuminate\Support\Str::contains($fieldName, ['image', 'photo', 'picture', 'avatar', 'file', 'document', 'attachment'])) {
            // Check file fields before numeric fields (since 'image' contains 'age')
            $config['type'] = 'file';
            $config['hide_in_index'] = true;
        } elseif (in_array($fieldName, ['price', 'amount', 'cost', 'salary', 'wage'])) {
            $config['type'] = 'number';
            $config['rules'] = ($isNullable ? 'nullable|' : 'required|') . 'numeric|min:0';
        } elseif (\Illuminate\Support\Str::contains($fieldName, ['quantity', 'count', 'number', 'age', 'year', 'population', 'pages'])) {
            $config['type'] = 'number';
            $config['rules'] = ($isNullable ? 'nullable|' : 'required|') . 'integer|min:0';
        } elseif (\Illuminate\Support\Str::startsWith($fieldName, ['is_', 'has_', 'can_', 'should_', 'must_'])) {
            $config['type'] = 'boolean';
        } elseif (\Illuminate\Support\Str::contains($fieldName, ['url', 'link', 'website'])) {
            $config['type'] = 'url';
            $rules = ($isNullable ? 'nullable|' : 'required|') . 'url';
            if ($maxLength) {
                $rules .= '|max:' . $maxLength;
            }
            $config['rules'] = $rules;
        } else {
            // No name pattern matched, fall back to database column type
            if ($columnType) {
                switch ($columnType) {
                    case 'boolean':
                        $config['type'] = 'boolean';
                        break;
                        
                    case 'integer':
                    case 'bigint':
                    case 'smallint':
                        $config['type'] = 'number';
                        $config['rules'] = ($isNullable ? 'nullable|' : 'required|') . 'integer';
                        break;
                        
                    case 'decimal':
                    case 'float':
                    case 'double':
                        $config['type'] = 'number';
                        $config['rules'] = ($isNullable ? 'nullable|' : 'required|') . 'numeric';
                        break;
                        
                    case 'text':
                    case 'longtext':
                    case 'mediumtext':
                        $config['type'] = 'textarea';
                        $config['hide_in_index'] = true;
                        $config['rules'] = $isNullable ? 'nullable' : 'required';
                        break;
                        
                    case 'date':
                        $config['type'] = 'date';
                        $config['rules'] = $isNullable ? 'nullable|date' : 'required|date';
                        break;
                        
                    case 'datetime':
                    case 'timestamp':
                        $config['type'] = 'datetime-local';
                        $config['rules'] = $isNullable ? 'nullable' : 'required';
                        break;
                        
                    case 'time':
                        $config['type'] = 'time';
                        $config['rules'] = $isNullable ? 'nullable' : 'required';
                        break;
                        
                    default:
                        // Default to text for unknown column types
                        $config['type'] = 'text';
                        $rules = $isNullable ? 'nullable' : 'required';
                        if ($maxLength) {
                            $rules .= '|max:' . $maxLength;
                        }
                        $config['rules'] = $rules;
                }
            } else {
                // No column type available, default to text
                $config['type'] = 'text';
                $rules = $isNullable ? 'nullable' : 'required';
                if ($maxLength) {
                    $rules .= '|max:' . $maxLength;
                }
                $config['rules'] = $rules;
            }
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
