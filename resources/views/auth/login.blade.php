@extends('layouts.app')

@section('content')
<div class="max-w-md mx-auto bg-white shadow rounded p-6 mt-12">
    <h1 class="text-2xl font-bold mb-6 text-center">Admin Login</h1>

    @if(session('error'))
        <div class="bg-red-100 text-red-600 p-2 mb-4 rounded">
            {{ session('error') }}
        </div>
    @endif

    <form method="POST" action="{{ route('admin.login.submit') }}">
        @csrf

        <div class="mb-4">
            <label class="block font-semibold mb-1">Email</label>
            <input type="email" name="email" class="w-full border rounded px-3 py-2" value="{{ old('email') }}" required autofocus>
            @error('email')
                <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-4">
            <label class="block font-semibold mb-1">Mật khẩu</label>
            <input type="password" name="password" class="w-full border rounded px-3 py-2" required>
            @error('password')
                <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-4 flex items-center">
            <input type="checkbox" name="remember" class="mr-2">
            <span>Ghi nhớ đăng nhập</span>
        </div>

        <button type="submit" class="w-full bg-green-500 hover:bg-green-600 text-white py-2 rounded">
            Đăng nhập
        </button>
    </form>
</div>
@endsection
