<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminAdminController extends Controller
{
    public function show()
    {
        // Lấy thông tin admin đang đăng nhập
        $admin = Auth::guard('admin')->user();
        return view('admin.admins.show', compact('admin'));
    }

    // Có thể thêm method edit và update nếu cần
    public function edit()
    {
        $admin = Auth::guard('admin')->user();
        return view('admin.admins.edit', compact('admin'));
    }

    public function update(Request $request)
    {
        $admin = Auth::guard('admin')->user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:admins,email,' . $admin->id,
        ]);

        $admin->update($request->all());

        return redirect()->route('admin.admins.index')
            ->with('success', 'Cập nhật thông tin thành công');
    }
}