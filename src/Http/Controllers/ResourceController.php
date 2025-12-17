<?php

namespace HasinHayder\TyroDashboard\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\QueryException;

class ResourceController extends BaseController
{
    protected function getResourceConfig($key)
    {
        $resources = config('tyro-dashboard.resources', []);
        if (!array_key_exists($key, $resources)) {
            abort(404, "Resource {$key} not found");
        }

        $config = $resources[$key];

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
        // If 'roles' (access_roles) is not defined, it's visible to all (default behavior)
        // unless we want to enforce strictness. Given the requirement "these resources will be hidden to all other roles",
        // it implies that IF roles are defined, we check. IF NOT, we assume open.
        $accessRoles = $config['roles'] ?? [];
        $readonlyRoles = $config['readonly'] ?? [];
        
        if (empty($accessRoles)) {
            // No strict access defined, so allowed.
            return true;
        }

        $user = auth()->user();
        if (!$user || !method_exists($user, 'tyroRoleSlugs')) {
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
        $sortDirection = request('sort_dir', 'desc');
        
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
            if (($field['type'] === 'select' || $field['type'] === 'multiselect' || $field['type'] === 'radio' || $field['type'] === 'checkbox') && isset($field['relationship'])) {
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
                $path = $request->file($field)->store($resource, 'public');
                $data[$field] = $path;
            }
        }

        // Separate relationship fields (multiselect/checkbox-group) that need syncing
        $relationshipsToSync = [];
        foreach ($config['fields'] as $field => $fieldConfig) {
            if (($fieldConfig['type'] === 'multiselect' || ($fieldConfig['type'] === 'checkbox' && isset($fieldConfig['relationship']))) && isset($fieldConfig['relationship'])) {
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
        
        return view('tyro-dashboard::resources.show', $this->getViewData([
            'resource' => $resource,
            'config' => $config,
            'item' => $item,
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
            if (($field['type'] === 'select' || $field['type'] === 'multiselect' || $field['type'] === 'radio' || $field['type'] === 'checkbox') && isset($field['relationship'])) {
                 $mainModel = new $modelClass;
                 if (method_exists($mainModel, $field['relationship'])) {
                     $relatedModel = $mainModel->{$field['relationship']}()->getRelated();
                     $viewData['options'][$key] = $relatedModel::all();
                 }
            }
            
            // Pre-calculate selected values for multiselect/checkbox-group
            if (($field['type'] === 'multiselect' || ($field['type'] === 'checkbox' && isset($field['relationship']))) && isset($field['relationship'])) {
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
        foreach ($config['fields'] as $field => $fieldConfig) {
            if ($fieldConfig['type'] === 'file') {
                if ($request->hasFile($field)) {
                    // Delete old file if exists
                    // Note: We might want to check if the old file exists on disk before deleting, but Storage::delete usually handles non-existence gracefully or we can check.
                    // Assuming 'public' disk for now.
                    if (!empty($item->$field)) {
                        // \Illuminate\Support\Facades\Storage::disk('public')->delete($item->$field);
                        // Using public_path if using 'public' disk usually means storage/app/public linked to public/storage
                        // But for simplicity let's assume standard storage structure.
                        // Ideally we should inject Storage facade or use it.
                        // For now let's just store new file. Old file cleanup is an optimization.
                    }
                    $path = $request->file($field)->store($resource, 'public');
                    $data[$field] = $path;
                } else {
                     // Keep old file if not uploaded
                     unset($data[$field]);
                }
            }
        }

        // Separate relationship fields (multiselect/checkbox-group) that need syncing
        $relationshipsToSync = [];
        foreach ($config['fields'] as $field => $fieldConfig) {
            if (($fieldConfig['type'] === 'multiselect' || ($fieldConfig['type'] === 'checkbox' && isset($fieldConfig['relationship']))) && isset($fieldConfig['relationship'])) {
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
        $item->delete();

        return redirect()->route('tyro-dashboard.resources.index', $resource)
            ->with('success', $config['title'] . ' deleted successfully.');
    }
}
