<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;

class ProfileController extends Controller
{
    /**
     * Display the user's profile information.
     */
    public function index(Request $request)
    {
        $user = $request->user();

        // Lấy danh sách địa chỉ của người dùng, sắp xếp địa chỉ mặc định lên đầu
        $addresses = $user->addresses()->orderByDesc('is_default')->get();
        
        // Trả về view với cả thông tin user và danh sách địa chỉ
        return view('client.profile.index', compact('user', 'addresses'));
    }

    /**
     * Show the form for editing the user's profile.
     */
    public function edit(Request $request)
    {
        $user = $request->user();
        $addresses = $user->addresses()->orderByDesc('is_default')->get();
        return view('client.profile.edit', compact('user', 'addresses'));
    }

    /**
     * Update the user's profile information.
     */
    public function update(Request $request)
    {
        $user = $request->user();
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
        ]);

        return redirect()->route('profile.index')->with('success', 'Cập nhật thông tin thành công!');
    }

    /**
     * Store a new address for the user.
     */
    public function storeAddress(Request $request)
    {
        $user = $request->user();
        
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'address_line' => 'required|string|max:500',
            'province' => 'nullable|string|max:100',
            'district' => 'nullable|string|max:100',
            'ward' => 'nullable|string|max:100',
        ]);

        // Nếu đây là địa chỉ đầu tiên, đặt làm mặc định
        $isDefault = $user->addresses()->count() === 0;

        $user->addresses()->create([
            'name' => $request->name,
            'phone' => $request->phone,
            'address_line' => $request->address_line,
            'province' => $request->province,
            'district' => $request->district,
            'ward' => $request->ward,
            'is_default' => $isDefault,
        ]);

        return redirect()->route('profile.edit')->with('success', 'Thêm địa chỉ thành công!');
    }

    /**
     * Display the user's profile form (Inertia version - deprecated).
     */
    public function editInertia(Request $request): Response
    {
        return Inertia::render('Profile/Edit', [
            'mustVerifyEmail' => $request->user() instanceof MustVerifyEmail,
            'status' => session('status'),
        ]);
    }


    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validate([
            'password' => ['required', 'current-password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
