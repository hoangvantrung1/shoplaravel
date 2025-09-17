<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // Kiểm tra đã đăng nhập chưa
        if (!Auth::check()) {
            return redirect()->route('admin.login')->with('error', 'Vui lòng đăng nhập.');
        }

        // Kiểm tra có phải admin không (is_admin = 1)
        if (Auth::user()->is_admin != 1) {
            Auth::logout();
            return redirect()->route('admin.login')
                ->with('error', 'Bạn không có quyền truy cập trang quản trị.');
        }

        return $next($request);
    }
}