@extends('layouts.admin')

@section('title', 'Sửa người dùng')

@section('content')
<div class="p-6 bg-white rounded shadow max-w-lg mx-auto">
    <h2 class="text-xl font-bold mb-4">Sửa người dùng</h2>

    @if ($errors->any())
        <div class="bg-red-100 border-l-4 border-red-600 text-red-800 p-4 rounded mb-4">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-4">
            <label class="block mb-1 font-semibold">Tên</label>
            <input type="text" name="name" value="{{ old('name', $user->name) }}" class="w-full border p-2 rounded" required>
        </div>
        <div class="mb-4">
            <label class="block mb-1 font-semibold">Email</label>
            <input type="email" name="email" value="{{ old('email', $user->email) }}" class="w-full border p-2 rounded" required>
        </div>
        <div class="mb-4">
            <label class="block mb-1 font-semibold">Mật khẩu mới (để trống nếu không đổi)</label>
            <input type="password" name="password" class="w-full border p-2 rounded">
        </div>
        <div class="flex justify-end">
            <button type="submit" class="bg-yellow-500 text-white px-4 py-2 rounded hover:bg-yellow-600">Cập nhật</button>
        </div>
    </form>
</div>
@endsection
