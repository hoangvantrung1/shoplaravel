@extends('layouts.admin')

@section('title', 'Quản lý người dùng')

@section('content')
<div class="p-6 bg-white rounded shadow">
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-xl font-bold">Danh sách người dùng</h2>
        <a href="{{ route('admin.users.create') }}" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">Thêm user</a>
    </div>
    <table class="w-full border-collapse border border-gray-200">
        <thead>
            <tr class="bg-green-100">
                <th class="border px-4 py-2">ID</th>
                <th class="border px-4 py-2">Tên</th>
                <th class="border px-4 py-2">Email</th>
                <th class="border px-4 py-2">Hành động</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $user)
            <tr>
                <td class="border px-4 py-2">{{ $user->id }}</td>
                <td class="border px-4 py-2">{{ $user->name }}</td>
                <td class="border px-4 py-2">{{ $user->email }}</td>
                <td class="border px-4 py-2 flex space-x-2">
                    <a href="{{ route('admin.users.edit', $user->id) }}" class="bg-yellow-400 px-3 py-1 rounded text-white hover:bg-yellow-500">Sửa</a>
                    <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Bạn có chắc muốn xóa?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="bg-red-500 px-3 py-1 rounded text-white hover:bg-red-600">Xóa</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="mt-4">
        {{ $users->links() }}
    </div>
</div>
@endsection
