<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class EnsureUserIsClient
{
    public function handle($request, Closure $next)
    {
        if (Auth::check() && Auth::user()->is_admin == 0) {
            return $next($request);
        }

        return redirect()->route('client.login')->withErrors([
            'email' => 'Bạn không có quyền truy cập client.',
        ]);
    }
}
