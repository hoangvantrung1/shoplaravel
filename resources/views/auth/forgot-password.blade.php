@extends('layouts.client')

@section('content')
<div class="container mx-auto max-w-md py-10">
    <h1 class="text-2xl font-bold mb-6">Quên mật khẩu</h1>
    @if (session('status'))
        <div class="bg-green-100 text-green-700 p-3 rounded mb-4">{{ session('status') }}</div>
    @endif
    <form method="POST" action="{{ route('password.email') }}" class="bg-white p-6 rounded shadow">
        @csrf
        <div class="mb-4">
            <label class="block mb-1">Email</label>
            <input type="email" name="email" class="w-full border rounded px-3 py-2" required>
            @error('email')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
        </div>
        <button class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">Gửi link đặt lại mật khẩu</button>
    </form>
</div>
@endsection



