<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $role
     * @return mixed
     */
    public function handle($request, Closure $next, $role)
    {
        $user = Auth::user();

        // Check if user is authenticated
        if (!$user) {
            return redirect('/login'); // or handle accordingly
        }

        dd($user->role);

        // Check if the authenticated user has the correct role
        if (!$user->hasRole($role)) {
            // Return a 403 error if the role doesn't match
            abort(403, 'Unauthorized action.');
        }

        return $next($request);
    }
}
