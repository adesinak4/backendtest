<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $role
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $role)
    {
        if (Auth::check() && Auth::user()->role == $role) {
            return $next($request);
        }
        return response()->json(['message' => 'Unauthorized'], 403);
    }


    // public function handle(Request $request, Closure $next, ...$roles)
    // {

    //     $user = $request->user();

    //     if (!$user || !in_array($user->role, $roles)) {
    //         abort(403, 'Unauthorized');
    //     }

    //     return $next($request);
    // }

    // public function handle($request, Closure $next, $role)
    // {
    //     if (Auth::check() && Auth::user()->role == $role) {
    //         return $next($request);
    //     }

    //     // If the user is not authorized, redirect them
    //     return redirect('/home')->with('error', 'You do not have access to this section.');
    // }
}
