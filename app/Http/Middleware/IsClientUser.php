<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IsClientUser
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        // Nếu chưa đăng nhập thì cho qua, middleware khác (auth) sẽ xử lý
        if (!$user) {
            return $next($request);
        }

        // Nếu là admin thì cấm vào client
        if ($user->is_admin == 1) {
            Auth::logout();
            return redirect()->route('client.login')
                ->withErrors(['email' => '❌ Admin không được phép vào client.']);
        }

        return $next($request);
    }
}
