<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class AdminUserController extends Controller
{
    // Hiển thị danh sách user
    public function index()
    {
        $users = User::orderBy('id', 'desc')->paginate(10);
        return view('admin.users.index', compact('users'));
    }

    // Form tạo user mới
    public function create()
    {
        return view('admin.users.create');
    }

    // Lưu user mới
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'is_admin' => 0,
        ]);

        return redirect()->route('admin.users.index')->with('success', 'Tạo user thành công!');
    }

    // Form sửa user
    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    // Cập nhật user
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,'.$user->id,
        ]);

        $user->update($request->only('name','email'));

        return redirect()->route('admin.users.index')->with('success', 'Cập nhật user thành công!');
    }

    // Xóa user
    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'Xóa user thành công!');
    }
}
