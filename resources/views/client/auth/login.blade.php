@extends('layouts.app')

@section('title', 'Đăng nhập')

@section('content')
<div class="max-w-md mx-auto mt-10 bg-white p-6 rounded-lg shadow">
    <h2 class="text-xl font-bold mb-4">Đăng nhập</h2>

    {{-- Hiển thị lỗi --}}
    @if($errors->any())
        <div class="mb-4 text-red-600">
            {{ $errors->first() }}
        </div>
    @endif

    <form action="{{ route('login.submit') }}" method="POST">
        @csrf
        <div class="mb-4">
            <label>Email</label>
            <input type="email" name="email" value="{{ old('email') }}" class="w-full border p-2 rounded">
        </div>
        <div class="mb-4">
            <label>Password</label>
            <input type="password" name="password" class="w-full border p-2 rounded">
        </div>
        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Đăng nhập</button>
    </form>
</div>
@endsection
