<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckMfa
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if user has MFA enabled
        if (auth()->check() && auth()->user()->mfa_enabled) {
            // If session doesn't have mfa_verified flag, redirect to MFA verification
            if (!session()->has('mfa_verified')) {
                return redirect('/mfa/verify');
            }
        }

        return $next($request);
    }
}
