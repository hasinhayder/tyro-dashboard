<?php

namespace HasinHayder\TyroDashboard\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpFoundation\Response;

class EnsureIsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (!$user) {
            // Try tyro-login route first, then Laravel's login, then fallback
            if (Route::has('tyro-login.login')) {
                return redirect()->route('tyro-login.login');
            } elseif (Route::has('login')) {
                return redirect()->route('login');
            }
            return redirect('/login');
        }

        $adminRoles = config('tyro-dashboard.admin_roles', ['admin', 'super-admin']);

        // Check if user has HasTyroRoles trait and has any admin role
        if (method_exists($user, 'tyroRoleSlugs')) {
            $userRoles = $user->tyroRoleSlugs();
            
            foreach ($adminRoles as $role) {
                if (in_array($role, $userRoles)) {
                    return $next($request);
                }
            }
        }

        // If no admin role found, redirect to dashboard with error
        return redirect()
            ->route('tyro-dashboard.index')
            ->with('error', 'You do not have permission to access this area.');
    }
}
