<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Closure;

class Authenticate extends Middleware
{
    /**
     * Handle an unauthenticated user.
     */
    protected function redirectTo($request)
    {
        // 👇 IMPORTANT: return nothing if it's an API request
        if (!$request->expectsJson()) {
            return route('login'); // Only for web apps
        }
    }

    protected function unauthenticated($request, array $guards)
    {
        // 👇 Return JSON instead of redirect
        abort(response()->json([
            'message' => 'Unauthenticated.'
        ], 401));
    }
}
