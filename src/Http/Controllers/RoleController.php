<?php

namespace HasinHayder\TyroDashboard\Http\Controllers;

use HasinHayder\Tyro\Models\Privilege;
use HasinHayder\Tyro\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class RoleController extends BaseController
{
    /**
     * Display a listing of roles.
     */
    public function index(Request $request)
    {
        $perPage = config('tyro-dashboard.pagination.roles', 15);

        $query = Role::withCount(['users', 'privileges']);

        // Search
        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('slug', 'like', "%{$search}%");
            });
        }

        $roles = $query->latest()->paginate($perPage)->withQueryString();
        $protectedRoles = config('tyro-dashboard.protected.roles', []);

        return view('tyro-dashboard::roles.index', $this->getViewData([
            'roles' => $roles,
            'protectedRoles' => $protectedRoles,
            'filters' => $request->only(['search']),
        ]));
    }

    /**
     * Show the form for creating a new role.
     */
    public function create()
    {
        $privileges = Privilege::all();

        return view('tyro-dashboard::roles.create', $this->getViewData([
            'privileges' => $privileges,
        ]));
    }

    /**
     * Store a newly created role.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:roles,slug'],
            'privileges' => ['array'],
            'privileges.*' => ['exists:privileges,id'],
        ]);

        $role = Role::create([
            'name' => $validated['name'],
            'slug' => $validated['slug'] ?: Str::slug($validated['name']),
        ]);

        if (!empty($validated['privileges'])) {
            $role->privileges()->sync($validated['privileges']);
        }

        return redirect()
            ->route('tyro-dashboard.roles.index')
            ->with('success', 'Role created successfully.');
    }

    /**
     * Display the specified role.
     */
    public function show($id)
    {
        $role = Role::with(['privileges', 'users'])->findOrFail($id);

        return view('tyro-dashboard::roles.show', $this->getViewData([
            'role' => $role,
        ]));
    }

    /**
     * Show the form for editing the specified role.
     */
    public function edit($id)
    {
        $role = Role::with('privileges')->findOrFail($id);
        $privileges = Privilege::all();
        $protectedRoles = config('tyro-dashboard.protected.roles', []);

        return view('tyro-dashboard::roles.edit', $this->getViewData([
            'role' => $role,
            'privileges' => $privileges,
            'protectedRoles' => $protectedRoles,
        ]));
    }

    /**
     * Update the specified role.
     */
    public function update(Request $request, $id)
    {
        $role = Role::findOrFail($id);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:roles,slug,' . $role->id],
            'privileges' => ['array'],
            'privileges.*' => ['exists:privileges,id'],
        ]);

        $role->update([
            'name' => $validated['name'],
            'slug' => $validated['slug'] ?: Str::slug($validated['name']),
        ]);

        if (isset($validated['privileges'])) {
            $role->privileges()->sync($validated['privileges']);
        }

        return redirect()
            ->route('tyro-dashboard.roles.index')
            ->with('success', 'Role updated successfully.');
    }

    /**
     * Remove the specified role.
     */
    public function destroy($id)
    {
        $role = Role::findOrFail($id);

        // Check if role is protected
        $protectedRoles = config('tyro-dashboard.protected.roles', []);
        if (in_array($role->slug, $protectedRoles)) {
            return redirect()
                ->route('tyro-dashboard.roles.index')
                ->with('error', 'This role is protected and cannot be deleted.');
        }

        // Detach all users and privileges before deletion
        $role->users()->detach();
        $role->privileges()->detach();
        $role->delete();

        return redirect()
            ->route('tyro-dashboard.roles.index')
            ->with('success', 'Role deleted successfully.');
    }

    /**
     * Remove a user from the specified role.
     */
    public function removeUser($id, $userId)
    {
        $role = Role::findOrFail($id);
        $role->users()->detach($userId);

        return redirect()
            ->route('tyro-dashboard.roles.show', $id)
            ->with('success', 'User removed from role successfully.');
    }
}
