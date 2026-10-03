<?php

namespace HasinHayder\TyroDashboard\Support;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class ModelFormFieldResolver {
    protected static array $columnListings = [];

    protected static array $columnTypes = [];

    public static function resolve($model, array $types = [], array $labels = [], array $fieldConfigs = [], array $exclude = []): array {
        $isInstance = $model instanceof Model;
        $instance = $isInstance ? $model : new $model;

        $fields = static::resolveFields($instance, ! $isInstance);

        $hidden = $instance->getHidden();

        foreach ($fields as $name => $config) {
            if (in_array($name, $hidden) || in_array($name, $exclude)) {
                unset($fields[$name]);
            }
        }

        foreach ($fields as $name => $config) {
            if (isset($types[$name])) {
                $config['type'] = $types[$name];
            }
            if (isset($labels[$name])) {
                $config['label'] = $labels[$name];
            }
            if (isset($fieldConfigs[$name]) && is_array($fieldConfigs[$name])) {
                $config = array_merge($config, $fieldConfigs[$name]);
            }
            $fields[$name] = $config;
        }

        return $fields;
    }

    public static function resolveSelectOptions($model, string $name, array $field): ?array {
        if (isset($field['options']) && is_array($field['options'])) {
            $options = [];
            foreach ($field['options'] as $value => $label) {
                $optionValue = is_int($value) ? $label : $value;
                $options[$optionValue] = $label;
            }

            return $options;
        }

        $instance = $model instanceof Model ? $model : new $model;
        $relationship = $field['relationship'] ?? null;

        if ($relationship && method_exists($instance, $relationship)) {
            try {
                $related = $instance->{$relationship}()->getRelated();
                $labelColumn = $field['option_label'] ?? null;

                if (! $labelColumn) {
                    $columns = static::columnListing($related->getTable());
                    foreach (['name', 'title', 'label', 'email', 'code'] as $column) {
                        if (in_array($column, $columns, true)) {
                            $labelColumn = $column;
                            break;
                        }
                    }
                }

                if (! $labelColumn) {
                    $labelColumn = $related->getKeyName();
                }

                $query = $related->query();
                $limit = $field['option_limit'] ?? null;

                if (is_int($limit) && $limit > 0) {
                    $query->limit($limit);
                }

                return $query->pluck($labelColumn, $related->getKeyName())->all();
            } catch (\Exception $e) {
                return null;
            }
        }

        return null;
    }

    protected static function resolveFields(Model $instance, bool $isCreate): array {
        if (method_exists($instance, 'getResourceConfig')) {
            $config = $instance->getResourceConfig();
            $fields = $config['fields'] ?? [];

            foreach ($fields as $name => $field) {
                if (($field['hide_in_form'] ?? false)
                    || ($isCreate && ($field['hide_in_create'] ?? false))
                    || (! $isCreate && ($field['hide_in_edit'] ?? false))) {
                    unset($fields[$name]);
                }
            }

            return $fields;
        }

        $fillable = $instance->getFillable();

        if (empty($fillable)) {
            $fillable = static::allColumns($instance);
        }

        $fields = [];
        foreach ($fillable as $name) {
            $fields[$name] = static::guessFieldConfig($name, $instance->getTable());
        }

        return $fields;
    }

    protected static function allColumns(Model $instance): array {
        $columns = static::columnListing($instance->getTable());

        $keyName = $instance->getKeyName();

        return array_values(array_filter($columns, function ($column) use ($keyName) {
            return $column !== $keyName;
        }));
    }

    protected static function columnListing(string $table): array {
        if (! array_key_exists($table, static::$columnListings)) {
            try {
                static::$columnListings[$table] = Schema::getColumnListing($table);
            } catch (\Exception $e) {
                static::$columnListings[$table] = [];
            }
        }

        return static::$columnListings[$table];
    }

    protected static function columnType(string $table, string $column): ?string {
        $key = $table.'.'.$column;

        if (! array_key_exists($key, static::$columnTypes)) {
            try {
                static::$columnTypes[$key] = Schema::hasColumn($table, $column)
                    ? Schema::getColumnType($table, $column)
                    : null;
            } catch (\Exception $e) {
                static::$columnTypes[$key] = null;
            }
        }

        return static::$columnTypes[$key];
    }

    protected static function guessFieldConfig(string $fieldName, string $tableName): array {
        $config = [
            'label' => Str::headline($fieldName),
            'type' => 'text',
        ];

        $columnType = static::columnType($tableName, $fieldName);

        if ($columnType) {
            switch ($columnType) {
                case 'boolean':
                    $config['type'] = 'boolean';
                    break;
                case 'integer':
                case 'bigint':
                case 'smallint':
                case 'decimal':
                case 'float':
                case 'double':
                    $config['type'] = 'number';
                    break;
                case 'text':
                case 'longtext':
                case 'mediumtext':
                    $config['type'] = 'textarea';
                    break;
                case 'date':
                    $config['type'] = 'date';
                    break;
                case 'datetime':
                case 'timestamp':
                    $config['type'] = 'datetime-local';
                    break;
                case 'time':
                    $config['type'] = 'time';
                    break;
            }
        }

        if (Str::endsWith($fieldName, '_id')) {
            $config['type'] = 'select';
            $config['relationship'] = Str::camel(Str::beforeLast($fieldName, '_id'));
        } elseif (in_array($fieldName, ['email', 'email_address'])) {
            $config['type'] = 'email';
        } elseif (in_array($fieldName, ['password', 'password_hash'])) {
            $config['type'] = 'password';
        } elseif (Str::contains($fieldName, ['description', 'bio', 'content', 'body', 'notes', 'comment'])) {
            $config['type'] = 'textarea';
        } elseif (Str::contains($fieldName, ['image', 'photo', 'picture', 'avatar', 'file', 'document', 'attachment'])) {
            $config['type'] = 'media';
        } elseif (in_array($fieldName, ['price', 'amount', 'cost', 'salary', 'wage'])) {
            $config['type'] = 'number';
        } elseif (Str::startsWith($fieldName, ['is_', 'has_', 'can_', 'should_', 'must_'])) {
            $config['type'] = 'boolean';
        } elseif (Str::contains($fieldName, ['url', 'link', 'website'])) {
            $config['type'] = 'url';
        }

        return $config;
    }
}
