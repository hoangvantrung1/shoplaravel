<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    public function handle($request, Closure $next)
    {
        // Nếu chưa đăng nhập hoặc không phải admin thì đá ra trang login
        if (!Auth::check() || !Auth::user()->isAdmin()) {
            return redirect('/login')->with('error', 'Bạn không có quyền truy cập!');
        }

        return $next($request);
    }
}
