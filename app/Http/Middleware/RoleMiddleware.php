<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string $role)
    {
        $user = $request->user();
        if (!$user) {
            abort(403);
        }

        // Simple rule: 'admin' -> is_admin = 1; 'user' -> any logged-in user
        if ($role === 'admin' && (int)($user->is_admin ?? 0) !== 1) {
            abort(403);
        }

        return $next($request);
    }
}



