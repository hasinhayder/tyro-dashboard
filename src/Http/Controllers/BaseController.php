<?php

namespace HasinHayder\TyroDashboard\Http\Controllers;

use Illuminate\Routing\Controller;

abstract class BaseController extends Controller
{
    /**
     * Get the user model class.
     */
    protected function getUserModel(): string
    {
        return config('tyro-dashboard.user_model', config('tyro.models.user', 'App\\Models\\User'));
    }

    /**
     * Check if the current user is an admin.
     */
    protected function isAdmin(): bool
    {
        $user = auth()->user();
        
        if (!$user || !method_exists($user, 'tyroRoleSlugs')) {
            return false;
        }

        $adminRoles = config('tyro-dashboard.admin_roles', ['admin', 'super-admin']);
        $userRoles = $user->tyroRoleSlugs();

        foreach ($adminRoles as $role) {
            if (in_array($role, $userRoles)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Get common view data.
     */
    protected function getViewData(array $data = []): array
    {
        return array_merge([
            'branding' => config('tyro-dashboard.branding'),
            'isAdmin' => $this->isAdmin(),
            'user' => auth()->user(),
        ], $data);
    }
}
