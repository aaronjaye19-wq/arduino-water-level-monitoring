<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckAdminRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // If user is not authenticated, redirect to login
        if (!auth()->check()) {
            return redirect('/login');
        }

        // If user is not admin, return 404 (makes it seem like route doesn't exist)
        if (!auth()->user()->is_admin) {
            abort(404);
        }

        return $next($request);
    }
}
