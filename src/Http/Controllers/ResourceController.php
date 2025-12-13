<?php

namespace HasinHayder\TyroDashboard\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Schema;

class ResourceController extends BaseController
{
    protected function getResourceConfig($key)
    {
        $resources = config('tyro-dashboard.resources', []);
        if (!array_key_exists($key, $resources)) {
            abort(404, "Resource {$key} not found");
        }
        return $resources[$key];
    }

    public function index($resource)
    {
        $config = $this->getResourceConfig($resource);
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
                 foreach($config['fields'] as $field => $fieldConfig) {
                     if (($fieldConfig['searchable'] ?? false)) {
                         $q->orWhere($field, 'like', "%{$search}%");
                     }
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
            'items' => $items
        ]));
    }

    public function create($resource)
    {
        $config = $this->getResourceConfig($resource);
        
        $viewData = [
            'resource' => $resource,
            'config' => $config,
            'options' => []
        ];

        // Load options for relationships
        foreach ($config['fields'] as $key => $field) {
            if (($field['type'] === 'select' || $field['type'] === 'multiselect') && isset($field['relationship'])) {
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

        $modelClass::create($data);

        return redirect()->route('tyro-dashboard.resources.index', $resource)
            ->with('success', $config['title'] . ' created successfully.');
    }

    public function show($resource, $id)
    {
        $config = $this->getResourceConfig($resource);
        $modelClass = $config['model'];
        
        $item = $modelClass::findOrFail($id);
        
        return view('tyro-dashboard::resources.show', $this->getViewData([
            'resource' => $resource,
            'config' => $config,
            'item' => $item
        ]));
    }

    public function edit($resource, $id)
    {
        $config = $this->getResourceConfig($resource);
        $modelClass = $config['model'];
        
        $item = $modelClass::findOrFail($id);
        
        $viewData = [
            'resource' => $resource,
            'config' => $config,
            'item' => $item,
            'options' => []
        ];

        // Load options for relationships
        foreach ($config['fields'] as $key => $field) {
            if (($field['type'] === 'select' || $field['type'] === 'multiselect') && isset($field['relationship'])) {
                 $mainModel = new $modelClass;
                 if (method_exists($mainModel, $field['relationship'])) {
                     $relatedModel = $mainModel->{$field['relationship']}()->getRelated();
                     $viewData['options'][$key] = $relatedModel::all();
                 }
            }
        }
        
        return view('tyro-dashboard::resources.edit', $this->getViewData($viewData));
    }

    public function update(Request $request, $resource, $id)
    {
        $config = $this->getResourceConfig($resource);
        $modelClass = $config['model'];
        
        $item = $modelClass::findOrFail($id);

        $rules = [];
        foreach ($config['fields'] as $field => $fieldConfig) {
            if (isset($fieldConfig['rules'])) {
                $rules[$field] = $fieldConfig['rules'];
                // Fix unique validation on update if necessary
                // This is a basic implementation, user might need to use Rule::unique()->ignore(...) in rules string or array
                // Ideally, we parse rules and append ignore logic, but that's complex for strings.
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

        $item->update($data);

        return redirect()->route('tyro-dashboard.resources.index', $resource)
            ->with('success', $config['title'] . ' updated successfully.');
    }

    public function destroy($resource, $id)
    {
        $config = $this->getResourceConfig($resource);
        $modelClass = $config['model'];
        
        $item = $modelClass::findOrFail($id);
        $item->delete();

        return redirect()->route('tyro-dashboard.resources.index', $resource)
            ->with('success', $config['title'] . ' deleted successfully.');
    }
}
