<?php

namespace HasinHayder\TyroDashboard\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class HandleImpersonation
{
    /**
     * Handle an incoming request.
     *
     * If the user is currently impersonating and tries to logout,
     * redirect them to leave impersonation instead.
     */
    public function handle(Request $request, Closure $next)
    {
        // Check if this is a logout request and we're impersonating
        if ($request->routeIs('tyro-login.logout') && session('impersonator_id')) {
            // Redirect to leave impersonation instead of logging out
            return redirect()->route('tyro-dashboard.leave-impersonation');
        }

        return $next($request);
    }
}
