<?php

namespace HasinHayder\TyroDashboard\Http\Controllers;

use HasinHayder\Tyro\Models\Role;
use HasinHayder\Tyro\Support\PasswordRules;
use HasinHayder\Tyro\Support\TyroAudit;
use HasinHayder\TyroDashboard\Support\DashboardRoute;
use HasinHayder\TyroDashboard\Support\OnlineUsers;
use HasinHayder\TyroLogin\Events\ForceLogout;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserController extends BaseController {
    /**
     * Display a listing of users.
     */
    public function index(Request $request) {
        $userModel = $this->getUserModel();
        $perPage = config('tyro-dashboard.pagination.users', 15);

        $onlineUserIds = $this->getOnlineUserIds();
        $query = $userModel::with('roles');

        // Search
        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Filter by role
        if ($role = $request->get('role')) {
            $query->whereHas('roles', function ($q) use ($role) {
                $q->where('slug', $role);
            });
        }

        // Filter by status
        if ($request->get('status') === 'suspended') {
            $query->whereNotNull('suspended_at');
        } elseif ($request->get('status') === 'active') {
            $query->whereNull('suspended_at');
        } elseif ($request->get('status') === 'logged_in') {
            $query->whereIn('id', $onlineUserIds->all());
        }

        $users = $query->latest()->paginate($perPage)->withQueryString();
        $roles = Role::all();

        return view('tyro-dashboard::users.index', $this->getViewData([
            'users' => $users,
            'roles' => $roles,
            'onlineUserIds' => $onlineUserIds,
            'filters' => $request->only(['search', 'role', 'status']),
        ]));
    }

    /**
     * Show the form for creating a new user.
     */
    public function create() {
        $roles = Role::all();

        return view('tyro-dashboard::users.create', $this->getViewData([
            'roles' => $roles,
        ]));
    }

    /**
     * Store a newly created user.
     */
    public function store(Request $request) {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => array_merge(['required', 'confirmed'], PasswordRules::get(['name' => $request->input('name'), 'email' => $request->input('email')])),
            'roles' => ['array'],
            'roles.*' => ['exists:roles,id'],
        ]);

        $userModel = $this->getUserModel();

        $user = $userModel::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        if (! empty($validated['roles'])) {
            $user->roles()->sync($validated['roles']);

            $assignedRoleIds = array_map('intval', $validated['roles']);
            $assignedRoles = Role::query()->whereIn('id', $assignedRoleIds)->get(['id', 'slug']);
            $this->auditRoleAssignments($user, $assignedRoles, true);
        }

        return redirect()
            ->route(DashboardRoute::name('users.index'))
            ->with('success', 'User created successfully.');
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit($id) {
        $userModel = $this->getUserModel();
        $user = $userModel::with('roles')->findOrFail($id);
        $roles = Role::all();

        return view('tyro-dashboard::users.edit', $this->getViewData([
            'editUser' => $user,
            'isOnline' => $this->getOnlineUserIds()->containsStrict((string) $user->getKey()),
            'roles' => $roles,
        ]));
    }

    /**
     * Update the specified user.
     */
    public function update(Request $request, $id) {
        $userModel = $this->getUserModel();
        $user = $userModel::findOrFail($id);
        $oldName = $user->name;
        $oldEmail = $user->email;
        $oldRoleIds = $user->roles()->pluck('roles.id')->map(fn ($item) => (int) $item)->values()->all();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,'.$user->id],
            'password' => array_merge(['nullable', 'confirmed'], PasswordRules::get(['name' => $request->input('name'), 'email' => $request->input('email')])),
            'roles' => ['array'],
            'roles.*' => ['exists:roles,id'],
        ]);

        $user->name = $validated['name'];
        $user->email = $validated['email'];

        if (! empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        if ($oldEmail !== $user->email) {
            $this->auditSafely('user.email_changed', $user, ['email' => $oldEmail], ['email' => $user->email]);
        }

        if (isset($validated['roles'])) {
            $user->roles()->sync($validated['roles']);

            $newRoleIds = array_map('intval', $validated['roles']);
            $attachedRoleIds = array_values(array_diff($newRoleIds, $oldRoleIds));
            $detachedRoleIds = array_values(array_diff($oldRoleIds, $newRoleIds));

            if (! empty($attachedRoleIds)) {
                $attachedRoles = Role::query()->whereIn('id', $attachedRoleIds)->get(['id', 'slug']);
                $this->auditRoleAssignments($user, $attachedRoles, true);
            }

            if (! empty($detachedRoleIds)) {
                $detachedRoles = Role::query()->whereIn('id', $detachedRoleIds)->get(['id', 'slug']);
                $this->auditRoleAssignments($user, $detachedRoles, false);
            }
        }

        return redirect()
            ->route(DashboardRoute::name('users.index'))
            ->with('success', 'User updated successfully.');
    }

    /**
     * Suspend the specified user.
     */
    public function suspend(Request $request, $id) {
        $userModel = $this->getUserModel();
        $user = $userModel::findOrFail($id);

        // Prevent self-suspension
        if ($user->id === auth()->id()) {
            return redirect()
                ->route(DashboardRoute::name('users.index'))
                ->with('error', 'You cannot suspend yourself.');
        }

        $validated = $request->validate([
            'reason' => ['nullable', 'string', 'max:500'],
        ]);

        $user->suspend($validated['reason'] ?? null);

        return redirect()
            ->route(DashboardRoute::name('users.index'))
            ->with('success', 'User suspended successfully.');
    }

    /**
     * Unsuspend the specified user.
     */
    public function unsuspend($id) {
        $userModel = $this->getUserModel();
        $user = $userModel::findOrFail($id);

        $user->unsuspend();

        return redirect()
            ->route(DashboardRoute::name('users.index'))
            ->with('success', 'User unsuspended successfully.');
    }

    /**
     * Remove the specified user.
     */
    public function destroy($id) {
        $userModel = $this->getUserModel();
        $user = $userModel::findOrFail($id);

        // Prevent self-deletion
        if ($user->id === auth()->id()) {
            return redirect()
                ->route(DashboardRoute::name('users.index'))
                ->with('error', 'You cannot delete yourself.');
        }

        // Check if user is protected
        $protectedUsers = config('tyro-dashboard.protected.users', []);
        if (in_array($user->id, $protectedUsers)) {
            return redirect()
                ->route(DashboardRoute::name('users.index'))
                ->with('error', 'This user is protected and cannot be deleted.');
        }

        $user->delete();

        return redirect()
            ->route(DashboardRoute::name('users.index'))
            ->with('success', 'User deleted successfully.');
    }

    /**
     * Remove selected users in bulk.
     */
    public function bulkDestroy(Request $request): RedirectResponse {
        $validated = $request->validate([
            'selected_ids' => ['required', 'array', 'min:1'],
            'selected_ids.*' => ['integer'],
        ]);

        $userModel = $this->getUserModel();
        $currentUserId = auth()->id();
        $protectedUsers = config('tyro-dashboard.protected.users', []);

        $query = $userModel::query()->whereIn('id', $validated['selected_ids']);

        // Exclude current user and protected users
        $query->where('id', '!=', $currentUserId);
        if (! empty($protectedUsers)) {
            $query->whereNotIn('id', $protectedUsers);
        }

        $deletedCount = $query->delete();

        return redirect()
            ->route(DashboardRoute::name('users.index'))
            ->with('success', "Deleted {$deletedCount} users.");
    }

    /**
     * Log the specified user out of all supported sessions.
     */
    public function logout($id) {
        $userModel = $this->getUserModel();
        $user = $userModel::findOrFail($id);

        if ($user->id === auth()->id()) {
            return redirect()
                ->route(DashboardRoute::name('users.index'))
                ->with('error', 'You cannot log yourself out.');
        }

        $tokenCount = $user->tokens()->count();
        $user->tokens()->delete();

        $sessionCount = 0;
        $sessionDriver = config('session.driver');
        $sessionsRevoked = false;
        $sessionRevocationFailed = false;
        $forceLogoutDispatched = false;

        if ($sessionDriver === 'database') {
            try {
                $sessionCount = DB::connection(config('session.connection'))
                    ->table(config('session.table', 'sessions'))
                    ->where('user_id', $user->getAuthIdentifier())
                    ->delete();
                $sessionsRevoked = true;
            } catch (\Throwable $e) {
                $sessionRevocationFailed = true;
            }
        } elseif ($sessionDriver === 'redis') {
            // Redis sessions cannot be enumerated like database rows, so mark the
            // user for logout instead; Tyro Login logs them out on their next web request.
            try {
                event(new ForceLogout((int) $user->getAuthIdentifier()));
                $forceLogoutDispatched = true;
                $sessionsRevoked = true;
            } catch (\Throwable $e) {
                $sessionRevocationFailed = true;
            }
        }

        if ($sessionsRevoked) {
            OnlineUsers::forget($user->getAuthIdentifier());
        }

        $user->setRememberToken(Str::random(60));
        $user->save();

        $result = $sessionsRevoked ? 'complete' : 'partial';
        $this->auditSafely('user.logout', $user, null, [
            'target_user_id' => $user->getAuthIdentifier(),
            'target_user_email' => $user->email,
            'api_tokens_revoked' => $tokenCount,
            'browser_sessions_revoked' => $sessionCount,
            'browser_session_revocation_failed' => $sessionRevocationFailed,
            'force_logout_dispatched' => $forceLogoutDispatched,
            'session_driver' => $sessionDriver,
            'result' => $result,
        ]);

        if ($forceLogoutDispatched) {
            $message = "{$user->name} has been marked for logout and will be logged out on their next request. {$tokenCount} API token(s) were revoked.";
        } elseif ($sessionsRevoked) {
            $message = "{$user->name} has been logged out of all browser sessions and {$tokenCount} API token(s) were revoked.";
        } elseif ($sessionRevocationFailed) {
            $message = "{$user->name}'s {$tokenCount} API token(s) were revoked, but browser sessions could not be terminated because the session store could not be accessed.";
        } else {
            $message = "{$user->name}'s {$tokenCount} API token(s) were revoked, but browser sessions could not be terminated because the session driver is not supported.";
        }

        return redirect()
            ->route(DashboardRoute::name('users.index'))
            ->with($sessionsRevoked ? 'success' : 'warning', $message);
    }

    /**
     * Reset 2FA for the specified user.
     */
    public function reset2FA($id) {
        $userModel = $this->getUserModel();
        $user = $userModel::findOrFail($id);

        $user->forceFill([
            'two_factor_secret' => null,
            'two_factor_recovery_codes' => null,
            'two_factor_confirmed_at' => null,
        ])->save();

        return redirect()
            ->route(DashboardRoute::name('users.edit'), $user->id)
            ->with('success', 'Two-factor authentication has been reset for this user.');
    }

    /**
     * Impersonate the specified user.
     */
    public function loginAs($id) {
        // Only admins can impersonate
        if (! $this->isAdmin()) {
            abort(403, 'Unauthorized action.');
        }

        $userModel = $this->getUserModel();
        $targetUser = $userModel::findOrFail($id);

        // Prevent impersonating yourself
        if ($targetUser->id === auth()->id()) {
            return redirect()
                ->route(DashboardRoute::name('users.index'))
                ->with('error', 'You cannot impersonate yourself.');
        }

        // Store original user ID in session
        session(['impersonator_id' => auth()->id()]);

        // Log in as the target user
        auth()->login($targetUser);

        return redirect()
            ->route(DashboardRoute::name('index'))
            ->with('success', "You are now logged in as {$targetUser->name}.");
    }

    /**
     * Leave impersonation and return to original admin account.
     */
    public function leaveImpersonation() {
        $impersonatorId = session('impersonator_id');

        if (! $impersonatorId) {
            return redirect()
                ->route(DashboardRoute::name('index'))
                ->with('error', 'You are not impersonating anyone.');
        }

        $userModel = $this->getUserModel();
        $originalUser = $userModel::findOrFail($impersonatorId);

        // Remove impersonator ID from session
        session()->forget('impersonator_id');

        // Log back in as original user
        auth()->login($originalUser);

        return redirect()
            ->route(DashboardRoute::name('users.index'))
            ->with('success', 'You have stopped impersonating and returned to your account.');
    }

    protected function getOnlineUserIds(): Collection {
        $sessionIds = collect();

        if (config('session.driver') === 'database') {
            try {
                $sessionIds = DB::connection(config('session.connection'))
                    ->table(config('session.table', 'sessions'))
                    ->where('last_activity', '>=', now()->subMinutes(config('session.lifetime', 120))->getTimestamp())
                    ->whereNotNull('user_id')
                    ->distinct()
                    ->pluck('user_id')
                    ->map(fn ($id) => (string) $id);
            } catch (\Throwable $e) {
                $sessionIds = collect();
            }
        }

        // Heartbeat cache is the primary signal; DB sessions stay as a fallback
        return OnlineUsers::onlineUserIds()->merge($sessionIds)->unique()->values();
    }

    /**
     * Write one audit entry per role assignment/removal for a user.
     */
    protected function auditRoleAssignments($user, Collection $roles, bool $assigned): void {
        $event = $assigned ? 'role.assigned' : 'role.removed';

        foreach ($roles as $role) {
            $this->auditSafely($event, $user, null, [
                'role_id' => $role->id,
                'role_slug' => $role->slug,
            ]);
        }
    }

    /**
     * Write an audit entry without breaking user management actions.
     */
    protected function auditSafely(string $event, $auditable = null, ?array $oldValues = null, ?array $newValues = null): void {
        try {
            TyroAudit::log($event, $auditable, $oldValues, $newValues);
        } catch (\Throwable $e) {
            // Intentionally ignore audit failures for dashboard stability.
        }
    }
}
