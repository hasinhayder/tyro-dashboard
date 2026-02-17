<?php

namespace HasinHayder\TyroDashboard\Http\Controllers;

use HasinHayder\Tyro\Models\Privilege;
use HasinHayder\Tyro\Models\Role;
use HasinHayder\Tyro\Support\TyroAudit;
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

        $selectedRoleIds = [];
        if (!empty($validated['roles'])) {
            $privilege->roles()->sync($validated['roles']);
            $selectedRoleIds = array_map('intval', $validated['roles']);

            $roles = Role::query()->whereIn('id', $selectedRoleIds)->get();
            foreach ($roles as $role) {
                $this->auditSafely('privilege.attached', $role, null, [
                    'privilege_id' => $privilege->id,
                    'privilege_slug' => $privilege->slug,
                ]);
            }
        }

        $this->auditSafely('privilege.created', $privilege, null, [
            'id' => $privilege->id,
            'name' => $privilege->name,
            'slug' => $privilege->slug,
            'description' => $privilege->description,
            'roles' => $selectedRoleIds,
        ]);

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
        $oldRoleIds = $privilege->roles()->pluck('roles.id')->map(fn ($item) => (int) $item)->values()->all();
        $oldValues = [
            'name' => $privilege->name,
            'slug' => $privilege->slug,
            'description' => $privilege->description,
            'roles' => $oldRoleIds,
        ];

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

        if ($oldValues['name'] !== $privilege->name) {
            $this->auditSafely('privilege.name_changed', $privilege, ['name' => $oldValues['name']], ['name' => $privilege->name]);
        }

        if ($oldValues['slug'] !== $privilege->slug) {
            $this->auditSafely('privilege.slug_changed', $privilege, ['slug' => $oldValues['slug']], ['slug' => $privilege->slug]);
        }

        if ($oldValues['description'] !== $privilege->description) {
            $this->auditSafely('privilege.description_changed', $privilege, ['description' => $oldValues['description']], ['description' => $privilege->description]);
        }

        if (isset($validated['roles'])) {
            $privilege->roles()->sync($validated['roles']);

            $newRoleIds = array_map('intval', $validated['roles']);
            $attachedRoleIds = array_values(array_diff($newRoleIds, $oldRoleIds));
            $detachedRoleIds = array_values(array_diff($oldRoleIds, $newRoleIds));

            if (!empty($attachedRoleIds)) {
                $attachedRoles = Role::query()->whereIn('id', $attachedRoleIds)->get();
                foreach ($attachedRoles as $role) {
                    $this->auditSafely('privilege.attached', $role, null, [
                        'privilege_id' => $privilege->id,
                        'privilege_slug' => $privilege->slug,
                    ]);
                }
            }

            if (!empty($detachedRoleIds)) {
                $detachedRoles = Role::query()->whereIn('id', $detachedRoleIds)->get();
                foreach ($detachedRoles as $role) {
                    $this->auditSafely('privilege.detached', $role, null, [
                        'privilege_id' => $privilege->id,
                        'privilege_slug' => $privilege->slug,
                    ]);
                }
            }
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
        $oldValues = [
            'id' => $privilege->id,
            'name' => $privilege->name,
            'slug' => $privilege->slug,
            'description' => $privilege->description,
        ];

        // Detach all roles before deletion
        $privilege->roles()->detach();
        $privilege->delete();

        $this->auditSafely('privilege.deleted', null, $oldValues, null);

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

        $role = Role::find($roleId);
        $this->auditSafely('privilege.detached', $role, null, [
            'privilege_id' => $privilege->id,
            'privilege_slug' => $privilege->slug,
        ]);

        return redirect()
            ->route('tyro-dashboard.privileges.show', $id)
            ->with('success', 'Privilege removed from role successfully.');
    }

    /**
     * Write an audit entry without breaking privilege management actions.
     */
    protected function auditSafely(string $event, $auditable = null, ?array $oldValues = null, ?array $newValues = null): void
    {
        try {
            TyroAudit::log($event, $auditable, $oldValues, $newValues);
        } catch (\Throwable $e) {
            // Intentionally ignore audit failures for dashboard stability.
        }
    }
}
