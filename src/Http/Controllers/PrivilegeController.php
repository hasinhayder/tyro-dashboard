<?php

namespace HasinHayder\TyroDashboard\Http\Controllers;

use HasinHayder\Tyro\Models\Privilege;
use HasinHayder\Tyro\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PrivilegeController extends BaseController
{
    /**
     * Display a listing of privileges.
     */
    public function index(Request $request)
    {
        $perPage = config('tyro-dashboard.pagination.privileges', 15);

        $query = Privilege::withCount('roles');

        // Search
        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('slug', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $privileges = $query->latest()->paginate($perPage)->withQueryString();

        return view('tyro-dashboard::privileges.index', $this->getViewData([
            'privileges' => $privileges,
            'filters' => $request->only(['search']),
        ]));
    }

    /**
     * Show the form for creating a new privilege.
     */
    public function create()
    {
        $roles = Role::all();

        return view('tyro-dashboard::privileges.create', $this->getViewData([
            'roles' => $roles,
        ]));
    }

    /**
     * Store a newly created privilege.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:privileges,slug'],
            'description' => ['nullable', 'string', 'max:500'],
            'roles' => ['array'],
            'roles.*' => ['exists:roles,id'],
        ]);

        $privilege = Privilege::create([
            'name' => $validated['name'],
            'slug' => $validated['slug'] ?: Str::slug($validated['name']),
            'description' => $validated['description'] ?? null,
        ]);

        if (!empty($validated['roles'])) {
            $privilege->roles()->sync($validated['roles']);
        }

        return redirect()
            ->route('tyro-dashboard.privileges.index')
            ->with('success', 'Privilege created successfully.');
    }

    /**
     * Display the specified privilege.
     */
    public function show($id)
    {
        $privilege = Privilege::with('roles')->findOrFail($id);

        return view('tyro-dashboard::privileges.show', $this->getViewData([
            'privilege' => $privilege,
        ]));
    }

    /**
     * Show the form for editing the specified privilege.
     */
    public function edit($id)
    {
        $privilege = Privilege::with('roles')->findOrFail($id);
        $roles = Role::all();

        return view('tyro-dashboard::privileges.edit', $this->getViewData([
            'privilege' => $privilege,
            'roles' => $roles,
        ]));
    }

    /**
     * Update the specified privilege.
     */
    public function update(Request $request, $id)
    {
        $privilege = Privilege::findOrFail($id);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:privileges,slug,' . $privilege->id],
            'description' => ['nullable', 'string', 'max:500'],
            'roles' => ['array'],
            'roles.*' => ['exists:roles,id'],
        ]);

        $privilege->update([
            'name' => $validated['name'],
            'slug' => $validated['slug'] ?: Str::slug($validated['name']),
            'description' => $validated['description'] ?? null,
        ]);

        if (isset($validated['roles'])) {
            $privilege->roles()->sync($validated['roles']);
        }

        return redirect()
            ->route('tyro-dashboard.privileges.index')
            ->with('success', 'Privilege updated successfully.');
    }

    /**
     * Remove the specified privilege.
     */
    public function destroy($id)
    {
        $privilege = Privilege::findOrFail($id);

        // Detach all roles before deletion
        $privilege->roles()->detach();
        $privilege->delete();

        return redirect()
            ->route('tyro-dashboard.privileges.index')
            ->with('success', 'Privilege deleted successfully.');
    }

    /**
     * Remove this privilege from a specific role.
     */
    public function removeRole($id, $roleId)
    {
        $privilege = Privilege::findOrFail($id);
        $privilege->roles()->detach($roleId);

        return redirect()
            ->route('tyro-dashboard.privileges.show', $id)
            ->with('success', 'Privilege removed from role successfully.');
    }
}
