<?php

namespace HasinHayder\TyroDashboard\Http\Controllers;

use HasinHayder\Tyro\Models\Privilege;
use HasinHayder\Tyro\Models\Role;
use HasinHayder\Tyro\Support\TyroAudit;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
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

        $selectedPrivilegeIds = [];
        if (!empty($validated['privileges'])) {
            $role->privileges()->sync($validated['privileges']);
            $selectedPrivilegeIds = array_map('intval', $validated['privileges']);

            $assignedPrivileges = Privilege::query()
                ->whereIn('id', $selectedPrivilegeIds)
                ->get(['id', 'slug']);

            $this->auditPrivilegeAssignments($role, $assignedPrivileges, true);
        }

        $this->auditSafely('role.created', $role, null, [
            'id' => $role->id,
            'name' => $role->name,
            'slug' => $role->slug,
            'privileges' => $selectedPrivilegeIds,
        ]);

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
        $oldPrivilegeIds = $role->privileges()->pluck('privileges.id')->map(fn ($item) => (int) $item)->values()->all();
        $oldValues = [
            'name' => $role->name,
            'slug' => $role->slug,
            'privileges' => $oldPrivilegeIds,
        ];

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

        if ($oldValues['name'] !== $role->name) {
            $this->auditSafely('role.name_changed', $role, ['name' => $oldValues['name']], ['name' => $role->name]);
        }

        if ($oldValues['slug'] !== $role->slug) {
            $this->auditSafely('role.slug_changed', $role, ['slug' => $oldValues['slug']], ['slug' => $role->slug]);
        }

        if (isset($validated['privileges'])) {
            $role->privileges()->sync($validated['privileges']);

            $newPrivilegeIds = array_map('intval', $validated['privileges']);
            $attachedPrivilegeIds = array_values(array_diff($newPrivilegeIds, $oldPrivilegeIds));
            $detachedPrivilegeIds = array_values(array_diff($oldPrivilegeIds, $newPrivilegeIds));

            if (!empty($attachedPrivilegeIds)) {
                $attachedPrivileges = Privilege::query()
                    ->whereIn('id', $attachedPrivilegeIds)
                    ->get(['id', 'slug']);
                $this->auditPrivilegeAssignments($role, $attachedPrivileges, true);
            }

            if (!empty($detachedPrivilegeIds)) {
                $detachedPrivileges = Privilege::query()
                    ->whereIn('id', $detachedPrivilegeIds)
                    ->get(['id', 'slug']);
                $this->auditPrivilegeAssignments($role, $detachedPrivileges, false);
            }
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
        $oldValues = [
            'id' => $role->id,
            'name' => $role->name,
            'slug' => $role->slug,
        ];

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

        $this->auditSafely('role.deleted', null, $oldValues, null);

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

        $userModel = $this->getUserModel();
        $targetUser = $userModel::find($userId);

        $this->auditSafely('role.removed', $targetUser, null, [
            'role_id' => $role->id,
            'role_slug' => $role->slug,
        ]);

        return redirect()
            ->route('tyro-dashboard.roles.show', $id)
            ->with('success', 'User removed from role successfully.');
    }

    /**
     * Write an audit entry without breaking role management actions.
     */
    protected function auditSafely(string $event, $auditable = null, ?array $oldValues = null, ?array $newValues = null): void
    {
        try {
            TyroAudit::log($event, $auditable, $oldValues, $newValues);
        } catch (\Throwable $e) {
            // Intentionally ignore audit failures for dashboard stability.
        }
    }

    /**
     * Write one audit entry per privilege attachment/detachment on a role.
     */
    protected function auditPrivilegeAssignments(Role $role, Collection $privileges, bool $attached): void
    {
        $event = $attached ? 'role.privilege_attached' : 'role.privilege_detached';

        foreach ($privileges as $privilege) {
            $this->auditSafely($event, $privilege, null, [
                'role_id' => $role->id,
                'role_slug' => $role->slug,
                'privilege_id' => $privilege->id,
                'privilege_slug' => $privilege->slug,
            ]);
        }
    }
}
