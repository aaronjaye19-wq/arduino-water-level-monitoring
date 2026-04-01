<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Services\UserStorageService;

class InitializeStorage
{
    public function handle(Request $request, Closure $next)
    {
        // Initialize storage on every request - ensures files are always in place
        // This is a simple initialization that creates default users if needed
        try {
            $userService = new UserStorageService();
            // The constructor will handle creating default users if needed
        } catch (\Exception $e) {
            // Silently fail during initialization
        }

        return $next($request);
    }
}
