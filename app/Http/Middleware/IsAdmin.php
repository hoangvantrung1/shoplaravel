<?php


namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class IsAdmin
{
    public function handle($request, Closure $next)
    {
        if (!Auth::check() || !Auth::user()->isAdmin()) {
            return redirect('/')->with('error', 'Bạn không có quyền truy cập admin.');
        }

        return $next($request);
    }
}

