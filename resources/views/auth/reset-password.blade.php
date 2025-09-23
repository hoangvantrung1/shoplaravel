@extends('layouts.client')

@section('content')
<div class="container mx-auto max-w-md py-10">
    <h1 class="text-2xl font-bold mb-6">Đặt lại mật khẩu</h1>
    <form method="POST" action="{{ route('password.update') }}" class="bg-white p-6 rounded shadow">
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">
        <div class="mb-4">
            <label class="block mb-1">Email</label>
            <input type="email" name="email" class="w-full border rounded px-3 py-2" required>
            @error('email')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
        </div>
        <div class="mb-4">
            <label class="block mb-1">Mật khẩu mới</label>
            <input type="password" name="password" class="w-full border rounded px-3 py-2" required>
            @error('password')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
        </div>
        <div class="mb-4">
            <label class="block mb-1">Xác nhận mật khẩu</label>
            <input type="password" name="password_confirmation" class="w-full border rounded px-3 py-2" required>
        </div>
        <button class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">Cập nhật mật khẩu</button>
    </form>
</div>
@endsection



