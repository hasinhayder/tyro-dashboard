<?php

namespace HasinHayder\TyroDashboard\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\QueryException;
use HasinHayder\TyroDashboard\Concerns\HasCrud;

class ResourceController extends BaseController
{
    protected function getResourceConfig($key)
    {
        // First, check config-based resources (backward compatibility)
        $resources = config('tyro-dashboard.resources', []);
        
        if (array_key_exists($key, $resources)) {
            $config = $resources[$key];
        } else {
            // Try to find a model with HasCrud trait
            $config = $this->getTraitBasedResourceConfig($key);
            
            if (!$config) {
                abort(404, "Resource {$key} not found");
            }
        }

        // Auto-generate labels if missing
        if (isset($config['fields'])) {
            foreach ($config['fields'] as $fieldKey => &$fieldConfig) {
                if (!isset($fieldConfig['label'])) {
                    $fieldConfig['label'] = Str::headline($fieldKey);
                }
            }
        }

        return $config;
    }

    protected function getTraitBasedResourceConfig($key)
    {
        // Get all models from the application
        $models = $this->getModelsWithTrait(HasCrud::class);
        
        foreach ($models as $modelClass) {
            if (method_exists($modelClass, 'getResourceKey')) {
                if ($modelClass::getResourceKey() === $key) {
                    return $modelClass::getResourceConfig();
                }
            }
        }
        
        return null;
    }

    protected function getModelsWithTrait($trait)
    {
        static $cachedModels = null;
        
        if ($cachedModels !== null) {
            return $cachedModels;
        }
        
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
                if ($reflection->hasMethod('getResourceConfig')) {
                    $models[] = $className;
                }
            }
        }
        
        $cachedModels = $models;
        return $models;
    }

    protected function isReadonly($config)
    {
        $readonlyRoles = $config['readonly'] ?? [];
        if (empty($readonlyRoles)) {
            return false;
        }

        $user = auth()->user();
        if (!$user || !method_exists($user, 'tyroRoleSlugs')) {
            return false;
        }

        $userRoles = $user->tyroRoleSlugs();
        
        foreach ($readonlyRoles as $role) {
            if (in_array($role, $userRoles)) {
                return true;
            }
        }

        return false;
    }

    protected function hasAccess($config)
    {
        $user = auth()->user();
        
        // Must be authenticated
        if (!$user) {
            return false;
        }
        
        $accessRoles = $config['roles'] ?? [];
        $readonlyRoles = $config['readonly'] ?? [];
        
        // If no roles are defined, only admins can access (secure by default)
        if (empty($accessRoles) && empty($readonlyRoles)) {
            // Check if user is admin
            if (method_exists($user, 'tyroRoleSlugs')) {
                $adminRoles = config('tyro-dashboard.admin_roles', ['admin', 'super-admin']);
                $userRoles = $user->tyroRoleSlugs();
                foreach ($adminRoles as $role) {
                    if (in_array($role, $userRoles)) {
                        return true;
                    }
                }
            }
            return false;
        }

        // If roles are defined, check if user has the required role
        if (!method_exists($user, 'tyroRoleSlugs')) {
            return false;
        }

        $userRoles = $user->tyroRoleSlugs();

        // Check for full access
        foreach ($accessRoles as $role) {
            if (in_array($role, $userRoles)) {
                return true;
            }
        }

        // Check for readonly access (which also grants visibility)
        foreach ($readonlyRoles as $role) {
            if (in_array($role, $userRoles)) {
                return true;
            }
        }

        return false;
    }

    public function index($resource)
    {
        $config = $this->getResourceConfig($resource);
        
        if (!$this->hasAccess($config)) {
            abort(403, 'You do not have permission to view this resource.');
        }

        $modelClass = $config['model'];
        
        if (!class_exists($modelClass)) {
            abort(500, "Model class {$modelClass} not found");
        }

        $query = $modelClass::query();
        
        // Eager load relationships
        $with = [];
        foreach ($config['fields'] as $field => $fieldConfig) {
            if (isset($fieldConfig['relationship'])) {
                $with[] = $fieldConfig['relationship'];
            }
        }
        if (!empty($with)) {
            $query->with($with);
        }

        // Search
        if (request()->has('search') && request('search')) {
            $search = request('search');
            $query->where(function($q) use ($search, $config) {
                $searchableFields = $config['search'] ?? [];

                foreach($config['fields'] as $field => $fieldConfig) {
                    if (($fieldConfig['searchable'] ?? false)) {
                        $searchableFields[] = $field;
                    }
                }
                
                $searchableFields = array_unique($searchableFields);

                foreach($searchableFields as $field) {
                    // Check if the field is a relationship field or a regular column
                    // For now, we assume simple column search unless complex logic is needed.
                    // To be safe, we can check if it exists in fields config to see type, 
                    // but user might want to search hidden columns too.
                    $q->orWhere($field, 'like', "%{$search}%");
                }
            });
        }

        // Sort
        $sortField = request('sort_by', 'created_at');
        $sortDirection = in_array(strtolower(request('sort_dir', 'desc')), ['asc', 'desc'])
            ? strtolower(request('sort_dir', 'desc'))
            : 'desc';

        // Check if sort field exists in model table or config to avoid SQL injection/errors
        // Simple check: if it's in fields config and sortable
        if (isset($config['fields'][$sortField]) && ($config['fields'][$sortField]['sortable'] ?? false)) {
             $query->orderBy($sortField, $sortDirection);
        } elseif ($sortField === 'created_at') {
             // Default sort
             $query->latest();
        }

        $items = $query->paginate(config('tyro-dashboard.pagination.resources', 15));

        return view('tyro-dashboard::resources.index', $this->getViewData([
            'resource' => $resource,
            'config' => $config,
            'items' => $items,
            'isReadonly' => $this->isReadonly($config)
        ]));
    }

    public function create($resource)
    {
        $config = $this->getResourceConfig($resource);
        
        if (!$this->hasAccess($config)) {
            abort(403, 'You do not have permission to view this resource.');
        }

        if ($this->isReadonly($config)) {
            abort(403, 'This resource is read-only for your role.');
        }
        
        $viewData = [
            'resource' => $resource,
            'config' => $config,
            'options' => []
        ];

        // Load options for relationships
        foreach ($config['fields'] as $key => $field) {
            $needsOptions = ($field['type'] === 'select' || $field['type'] === 'multiselect' || $field['type'] === 'radio' || $field['type'] === 'checkbox') && isset($field['relationship']);
            if ($needsOptions) {
                 $modelClass = $config['model'];
                 $mainModel = new $modelClass;
                 if (method_exists($mainModel, $field['relationship'])) {
                     $relatedModel = $mainModel->{$field['relationship']}()->getRelated();
                     // Use a configured scope or just all()
                     $viewData['options'][$key] = $relatedModel::all();
                 }
            }
        }
        
        return view('tyro-dashboard::resources.create', $this->getViewData($viewData));
    }

    public function store(Request $request, $resource)
    {
        $config = $this->getResourceConfig($resource);
        
        if (!$this->hasAccess($config)) {
            abort(403, 'You do not have permission to view this resource.');
        }

        if ($this->isReadonly($config)) {
            abort(403, 'This resource is read-only for your role.');
        }

        $modelClass = $config['model'];

        $rules = [];
        foreach ($config['fields'] as $field => $fieldConfig) {
            if (isset($fieldConfig['rules'])) {
                $rules[$field] = $fieldConfig['rules'];
            }
        }

        $validated = $request->validate($rules);
        
        // Collect all fields defined in config
        $data = $request->only(array_keys($config['fields']));
        
        // Merge validated data to ensure any transformation in validation (if any) is kept, though unlikely with standard rules
        $data = array_merge($data, $validated);

        // Handle booleans (checkboxes) that might be missing from request if unchecked
        foreach ($config['fields'] as $field => $fieldConfig) {
            if ($fieldConfig['type'] === 'boolean' && !isset($data[$field])) {
                $data[$field] = false;
            }
        }

        // Handle file uploads
        foreach ($config['fields'] as $field => $fieldConfig) {
            if ($fieldConfig['type'] === 'file' && $request->hasFile($field)) {
                $uploadDisk = $config['upload_disk'] ?? config('tyro-dashboard.uploads.disk', 'public');
                $uploadDirectory = $config['upload_directory'] ?? config('tyro-dashboard.uploads.directory', 'uploads');
                $path = $request->file($field)->store($uploadDirectory, $uploadDisk);
                $data[$field] = $path;
            }
        }

        // Separate relationship fields (multiselect/checkbox-group/select with multiple) that need syncing
        $relationshipsToSync = [];
        foreach ($config['fields'] as $field => $fieldConfig) {
            $isMultipleRelationship = (
                $fieldConfig['type'] === 'multiselect' || 
                ($fieldConfig['type'] === 'checkbox' && isset($fieldConfig['relationship'])) ||
                ($fieldConfig['type'] === 'select' && ($fieldConfig['multiple'] ?? false))
            ) && isset($fieldConfig['relationship']);
            
            if ($isMultipleRelationship) {
                if (isset($data[$field])) {
                    $relationshipsToSync[$field] = $data[$field];
                }
                unset($data[$field]); // Remove from model attributes
            }
        }

        try {
            $item = $modelClass::create($data);
        } catch (QueryException $e) {
            $errorCode = $e->errorInfo[1] ?? 0;
            $errorMessage = $e->getMessage();
            $field = null;

            // MySQL: Column 'title' cannot be null (1048)
            if ($errorCode == 1048 && preg_match("/Column '([^']+)' cannot be null/", $errorMessage, $matches)) {
                $field = $matches[1];
            }
            // MySQL: Field 'title' doesn't have a default value (1364)
            elseif ($errorCode == 1364 && preg_match("/Field '([^']+)' doesn't have a default value/", $errorMessage, $matches)) {
                $field = $matches[1];
            }
            // SQLite: NOT NULL constraint failed: posts.title
            elseif (strpos($errorMessage, 'NOT NULL constraint failed') !== false) {
                if (preg_match("/NOT NULL constraint failed: .+\.([^\s]+)/", $errorMessage, $matches)) {
                    $field = $matches[1];
                }
            }
            // PostgreSQL: null value in column "title" violates not-null constraint
            elseif (strpos($errorMessage, 'violates not-null constraint') !== false) {
                if (preg_match('/null value in column "([^"]+)"/', $errorMessage, $matches)) {
                    $field = $matches[1];
                }
            }

            if ($field) {
                return back()->withInput()->withErrors([$field => "The {$field} field is required."]);
            }
            
            // Fallback if we can't identify the field but it's a constraint violation
            if ($errorCode == 1048 || $errorCode == 1364 || strpos($errorMessage, 'constraint') !== false) {
                 return back()->withInput()->with('error', 'Database error: Missing required fields.');
            }

            throw $e;
        }

        // Sync relationships
        foreach ($relationshipsToSync as $field => $values) {
            $fieldConfig = $config['fields'][$field];
            if (method_exists($item, $fieldConfig['relationship'])) {
                $item->{$fieldConfig['relationship']}()->sync($values);
            }
        }

        return redirect()->route('tyro-dashboard.resources.index', $resource)
            ->with('success', $config['title'] . ' created successfully.');
    }

    public function show($resource, $id)
    {
        $config = $this->getResourceConfig($resource);
        
        if (!$this->hasAccess($config)) {
            abort(403, 'You do not have permission to view this resource.');
        }

        $modelClass = $config['model'];
        
        $item = $modelClass::findOrFail($id);

        // Sanitize richtext fields server-side before passing to view to prevent stored XSS.
        $sanitizedRichtext = [];
        foreach ($config['fields'] as $key => $field) {
            if (($field['type'] ?? '') === 'richtext' && isset($item->$key)) {
                $sanitizedRichtext[$key] = function_exists('clean')
                    ? clean($item->$key)
                    : strip_tags($item->$key, '<p><br><b><strong><i><em><u><s><ul><ol><li><a><h1><h2><h3><h4><h5><h6><blockquote><pre><code><hr><img><table><thead><tbody><tr><th><td><span><div>');
            }
        }
        
        return view('tyro-dashboard::resources.show', $this->getViewData([
            'resource' => $resource,
            'config' => $config,
            'item' => $item,
            'sanitizedRichtext' => $sanitizedRichtext,
            'isReadonly' => $this->isReadonly($config)
        ]));
    }

    public function edit($resource, $id)
    {
        $config = $this->getResourceConfig($resource);
        
        if (!$this->hasAccess($config)) {
            abort(403, 'You do not have permission to view this resource.');
        }

        if ($this->isReadonly($config)) {
            abort(403, 'This resource is read-only for your role.');
        }

        $modelClass = $config['model'];
        
        $item = $modelClass::findOrFail($id);
        
        $viewData = [
            'resource' => $resource,
            'config' => $config,
            'item' => $item,
            'options' => [],
            'selectedValues' => []
        ];

        // Load options for relationships
        foreach ($config['fields'] as $key => $field) {
            $needsOptions = ($field['type'] === 'select' || $field['type'] === 'multiselect' || $field['type'] === 'radio' || $field['type'] === 'checkbox') && isset($field['relationship']);
            if ($needsOptions) {
                 $mainModel = new $modelClass;
                 if (method_exists($mainModel, $field['relationship'])) {
                     $relatedModel = $mainModel->{$field['relationship']}()->getRelated();
                     $viewData['options'][$key] = $relatedModel::all();
                 }
            }
            
            // Pre-calculate selected values for multiselect/checkbox-group/select with multiple
            $isMultipleRelationship = (
                $field['type'] === 'multiselect' || 
                ($field['type'] === 'checkbox' && isset($field['relationship'])) ||
                ($field['type'] === 'select' && ($field['multiple'] ?? false))
            ) && isset($field['relationship']);
            
            if ($isMultipleRelationship) {
                 if (method_exists($item, $field['relationship'])) {
                     $viewData['selectedValues'][$key] = $item->{$field['relationship']}->pluck('id')->toArray();
                 }
            }
        }
        
        return view('tyro-dashboard::resources.edit', $this->getViewData($viewData));
    }

    public function update(Request $request, $resource, $id)
    {
        $config = $this->getResourceConfig($resource);
        
        if (!$this->hasAccess($config)) {
            abort(403, 'You do not have permission to view this resource.');
        }

        if ($this->isReadonly($config)) {
            abort(403, 'This resource is read-only for your role.');
        }

        $modelClass = $config['model'];
        
        $item = $modelClass::findOrFail($id);

        $rules = [];
        foreach ($config['fields'] as $field => $fieldConfig) {
            if (isset($fieldConfig['rules'])) {
                $fieldRules = $fieldConfig['rules'];

                // Helper to append ignore ID to unique rules
                $processRule = function($rule) use ($field, $id) {
                    if (is_string($rule) && Str::startsWith($rule, 'unique:')) {
                        $parts = explode(',', substr($rule, 7));
                        // Case 1: unique:table
                        if (count($parts) == 1) {
                            return "unique:{$parts[0]},{$field},{$id}";
                        }
                        // Case 2: unique:table,column
                        elseif (count($parts) == 2) {
                            return $rule . ",{$id}";
                        }
                    }
                    return $rule;
                };

                if (is_string($fieldRules)) {
                    $rulesList = explode('|', $fieldRules);
                    foreach ($rulesList as &$r) {
                        $r = $processRule($r);
                    }
                    $rules[$field] = implode('|', $rulesList);
                } elseif (is_array($fieldRules)) {
                    foreach ($fieldRules as &$r) {
                        $r = $processRule($r);
                    }
                    $rules[$field] = $fieldRules;
                } else {
                    $rules[$field] = $fieldRules;
                }
            }
        }

        $validated = $request->validate($rules);

        // Collect all fields defined in config
        $data = $request->only(array_keys($config['fields']));
        
        // Merge validated data
        $data = array_merge($data, $validated);

        // Handle booleans (checkboxes)
        foreach ($config['fields'] as $field => $fieldConfig) {
            if ($fieldConfig['type'] === 'boolean' && !isset($data[$field])) {
                $data[$field] = false;
            }
             // Don't update password if empty
            if ($fieldConfig['type'] === 'password' && empty($data[$field])) {
                unset($data[$field]);
            }
        }

        // Handle file uploads
        $uploadDisk = $config['upload_disk'] ?? config('tyro-dashboard.uploads.disk', 'public');
        $uploadDirectory = $config['upload_directory'] ?? config('tyro-dashboard.uploads.directory', 'uploads');
        
        foreach ($config['fields'] as $field => $fieldConfig) {
            if ($fieldConfig['type'] === 'file') {
                if ($request->hasFile($field)) {
                    // Delete old file if exists
                    if (!empty($item->$field)) {
                        \Illuminate\Support\Facades\Storage::disk($uploadDisk)->delete($item->$field);
                    }
                    $path = $request->file($field)->store($uploadDirectory, $uploadDisk);
                    $data[$field] = $path;
                } else {
                     // Keep old file if not uploaded
                     unset($data[$field]);
                }
            }
        }

        // Separate relationship fields (multiselect/checkbox-group/select with multiple) that need syncing
        $relationshipsToSync = [];
        foreach ($config['fields'] as $field => $fieldConfig) {
            $isMultipleRelationship = (
                $fieldConfig['type'] === 'multiselect' || 
                ($fieldConfig['type'] === 'checkbox' && isset($fieldConfig['relationship'])) ||
                ($fieldConfig['type'] === 'select' && ($fieldConfig['multiple'] ?? false))
            ) && isset($fieldConfig['relationship']);
            
            if ($isMultipleRelationship) {
                if (isset($data[$field])) {
                    $relationshipsToSync[$field] = $data[$field];
                } else {
                    // If not present (e.g. all unchecked), sync empty array
                    $relationshipsToSync[$field] = [];
                }
                unset($data[$field]); // Remove from model attributes
            }
        }

        try {
            $item->update($data);
        } catch (QueryException $e) {
            $errorCode = $e->errorInfo[1] ?? 0;
            $errorMessage = $e->getMessage();
            $field = null;

            // MySQL: Column 'title' cannot be null (1048)
            if ($errorCode == 1048 && preg_match("/Column '([^']+)' cannot be null/", $errorMessage, $matches)) {
                $field = $matches[1];
            }
            // MySQL: Field 'title' doesn't have a default value (1364)
            elseif ($errorCode == 1364 && preg_match("/Field '([^']+)' doesn't have a default value/", $errorMessage, $matches)) {
                $field = $matches[1];
            }
            // SQLite: NOT NULL constraint failed: posts.title
            elseif (strpos($errorMessage, 'NOT NULL constraint failed') !== false) {
                if (preg_match("/NOT NULL constraint failed: .+\.([^\s]+)/", $errorMessage, $matches)) {
                    $field = $matches[1];
                }
            }
            // PostgreSQL: null value in column "title" violates not-null constraint
            elseif (strpos($errorMessage, 'violates not-null constraint') !== false) {
                if (preg_match('/null value in column "([^"]+)"/', $errorMessage, $matches)) {
                    $field = $matches[1];
                }
            }

            if ($field) {
                return back()->withInput()->withErrors([$field => "The {$field} field is required."]);
            }
            
            // Fallback if we can't identify the field but it's a constraint violation
            if ($errorCode == 1048 || $errorCode == 1364 || strpos($errorMessage, 'constraint') !== false) {
                 return back()->withInput()->with('error', 'Database error: Missing required fields.');
            }

            throw $e;
        }

        // Sync relationships
        foreach ($relationshipsToSync as $field => $values) {
            $fieldConfig = $config['fields'][$field];
            if (method_exists($item, $fieldConfig['relationship'])) {
                $item->{$fieldConfig['relationship']}()->sync($values);
            }
        }

        return redirect()->route('tyro-dashboard.resources.index', $resource)
            ->with('success', $config['title'] . ' updated successfully.');
    }

    public function destroy($resource, $id)
    {
        $config = $this->getResourceConfig($resource);
        
        if (!$this->hasAccess($config)) {
            abort(403, 'You do not have permission to view this resource.');
        }

        if ($this->isReadonly($config)) {
            abort(403, 'This resource is read-only for your role.');
        }

        $modelClass = $config['model'];
        
        $item = $modelClass::findOrFail($id);
        
        // Auto-delete uploaded files if configured
        if (config('tyro-dashboard.uploads.auto_delete_on_resource_delete', true)) {
            $uploadDisk = $config['upload_disk'] ?? config('tyro-dashboard.uploads.disk', 'public');
            
            foreach ($config['fields'] as $field => $fieldConfig) {
                if ($fieldConfig['type'] === 'file' && !empty($item->$field)) {
                    try {
                        \Illuminate\Support\Facades\Storage::disk($uploadDisk)->delete($item->$field);
                    } catch (\Exception $e) {
                        // Continue even if file deletion fails (file might not exist)
                    }
                }
            }
        }
        
        $item->delete();

        return redirect()->route('tyro-dashboard.resources.index', $resource)
            ->with('success', $config['title'] . ' deleted successfully.');
    }
}
