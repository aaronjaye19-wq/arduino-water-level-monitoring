<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AuthenticateSession
{
    public function handle(Request $request, Closure $next)
    {
        // Check if user is authenticated via session
        if (!session()->has('authenticated') || !session()->get('authenticated')) {
            return redirect()->route('login')->with('error', 'Please login first.');
        }

        return $next($request);
    }
}
