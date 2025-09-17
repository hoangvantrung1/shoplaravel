<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    // Hiển thị form login
    public function showLoginForm()
    {
        return view('client.auth.login');
    }

    // Xử lý login client
    public function login(Request $request)
    {
        // Validate input
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        // Login với guard mặc định 'web'
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->route('home')->with('success', 'Đăng nhập thành công!');
        }

        // Nếu login fail
        return back()->withErrors([
            'email' => 'Thông tin đăng nhập không chính xác.',
        ])->onlyInput('email');
    }

    // Logout client
    public function logout(Request $request)
    {
        Auth::logout(); // guard web
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login')->with('success', 'Đã đăng xuất thành công!');
    }

    // Hiển thị form đăng ký
    public function showRegistrationForm()
    {
        return view('client.auth.register');
    }

    // Xử lý đăng ký client
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'is_admin' => 0, // client
        ]);

        // Tự động login sau khi đăng ký
        Auth::login($user);

        return redirect()->route('home')->with('success', 'Đăng ký thành công!');
    }
}
