<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthenticateAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  ...$guards
     * @return mixed
     */
    public function handle(Request $request, Closure $next, ...$guards)
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check() && Auth::guard($guard)->user()->role === 'admin') { // Check if user has admin role
                return $next($request);
            }
        }
        
        // If you want to redirect to a specific admin login route:
        // return redirect()->route('admin.login'); 
        // Or, for a generic unauthorized response:
        // return response('Unauthorized.', 401);
        // Or, if you want to redirect to the default login page if not an admin
        return redirect('/login'); 
    }
}