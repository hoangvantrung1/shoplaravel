@extends('layouts.admin')

@section('title', 'Chỉnh sửa thông tin cá nhân')

@section('content')
<div class="bg-white rounded-lg shadow-md overflow-hidden max-w-2xl mx-auto">
    <div class="bg-gradient-to-r from-green-500 to-teal-600 px-6 py-4">
        <h1 class="text-2xl font-bold text-white">Chỉnh sửa thông tin cá nhân</h1>
    </div>

    <div class="px-6 py-8">
        <!-- SỬA FORM ACTION THÀNH admins.update -->
        <form action="{{ route('admin.admins.update') }}" method="POST">
            @csrf
            @method('PUT')

            <div class="space-y-6">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700">Họ và tên</label>
                    <input type="text" name="name" id="name" value="{{ old('name', $admin->name) }}"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500">
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                    <input type="email" name="email" id="email" value="{{ old('email', $admin->email) }}"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500">
                    @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="border-t pt-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Đổi mật khẩu</h3>
                    
                    <div class="space-y-4">
                        <div>
                            <label for="current_password" class="block text-sm font-medium text-gray-700">Mật khẩu hiện tại</label>
                            <input type="password" name="current_password" id="current_password"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500">
                            @error('current_password')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="new_password" class="block text-sm font-medium text-gray-700">Mật khẩu mới</label>
                            <input type="password" name="new_password" id="new_password"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500">
                            @error('new_password')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="new_password_confirmation" class="block text-sm font-medium text-gray-700">Xác nhận mật khẩu mới</label>
                            <input type="password" name="new_password_confirmation" id="new_password_confirmation"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500">
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex justify-end space-x-3 mt-8">
                <!-- SỬA NÚT HỦY THÀNH admins.index -->
                <a href="{{ route('admin.admins.index') }}"
                    class="bg-gray-500 text-white px-6 py-2 rounded-lg font-medium hover:bg-gray-600 transition duration-200">
                    Hủy
                </a>
                <button type="submit"
                    class="bg-green-600 text-white px-6 py-2 rounded-lg font-medium hover:bg-green-700 transition duration-200 flex items-center">
                    <i class="fas fa-save mr-2"></i> Cập nhật
                </button>
            </div>
        </form>
    </div>
</div>
@endsection