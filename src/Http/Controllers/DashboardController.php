<?php

namespace HasinHayder\TyroDashboard\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class DashboardController extends BaseController
{
    /**
     * Display the dashboard home.
     */
    public function index(Request $request)
    {
        $userModel = $this->getUserModel();
        
        $stats = [];
        
        if ($this->isAdmin()) {
            try {
                // User stats
                $stats['total_users'] = class_exists($userModel) ? $userModel::count() : 0;
                
                // Try to get suspended users count
                if (class_exists($userModel)) {
                    $user = new $userModel;
                    if (method_exists($user, 'isSuspended') || $user->getConnection()->getSchemaBuilder()->hasColumn($user->getTable(), 'suspended_at')) {
                        $stats['suspended_users'] = $userModel::whereNotNull('suspended_at')->count();
                    } else {
                        $stats['suspended_users'] = 0;
                    }
                    $stats['recent_users'] = $userModel::latest()->take(5)->get();
                } else {
                    $stats['suspended_users'] = 0;
                    $stats['recent_users'] = new Collection();
                }
                
                // Role stats
                if (class_exists('\HasinHayder\Tyro\Models\Role')) {
                    $roleModel = '\HasinHayder\Tyro\Models\Role';
                    $stats['total_roles'] = $roleModel::count();
                    
                    $roles = $roleModel::withCount('users')->get();
                    $stats['role_distribution'] = $roles->map(function ($role) {
                        return [
                            'name' => $role->name,
                            'count' => $role->users_count,
                        ];
                    });
                } else {
                    $stats['total_roles'] = 0;
                    $stats['role_distribution'] = new Collection();
                }
                
                // Privilege stats
                if (class_exists('\HasinHayder\Tyro\Models\Privilege')) {
                    $privilegeModel = '\HasinHayder\Tyro\Models\Privilege';
                    $stats['total_privileges'] = $privilegeModel::count();
                } else {
                    $stats['total_privileges'] = 0;
                }
                
            } catch (\Exception $e) {
                // If any error occurs, provide default stats
                $stats = [
                    'total_users' => 0,
                    'total_roles' => 0,
                    'total_privileges' => 0,
                    'recent_users' => new Collection(),
                    'suspended_users' => 0,
                    'role_distribution' => new Collection(),
                ];
            }
        }

        return view('tyro-dashboard::dashboard.index', $this->getViewData([
            'stats' => $stats,
        ]));
    }
}
