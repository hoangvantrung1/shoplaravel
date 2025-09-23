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
        if (!Auth::guard('admin')->check()) {
            return redirect()->route('admin.login.post')->with('error', 'Vui lòng đăng nhập.');
        }

        // Kiểm tra có phải admin không
        if (Auth::guard('admin')->user()->is_admin != 1) {
            Auth::guard('admin')->logout();
            return redirect()->route('admin.login.post')
                ->with('error', 'Bạn không có quyền truy cập trang quản trị.');
        }


        return $next($request);
    }
}