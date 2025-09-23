@extends('layouts.admin')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold">Quản lý thương hiệu</h1>
    <a href="{{ route('admin.brands.create') }}" class="bg-green-500 hover:bg-green-600 text-white py-2 px-4 rounded">Thêm mới</a>
</div>

@if(session('success'))
    <div class="bg-green-200 text-green-800 p-3 rounded mb-4">{{ session('success') }}</div>
@endif

<table class="min-w-full bg-white rounded shadow overflow-hidden">
    <thead>
        <tr class="bg-gray-100">
            <th class="px-4 py-2 text-left">ID</th>
            <th class="px-4 py-2 text-left">Tên</th>
            <th class="px-4 py-2 text-left">Slug</th>
            <th class="px-4 py-2 text-left">Hành động</th>
        </tr>
    </thead>
    <tbody>
        @foreach($brands as $brand)
        <tr class="border-b">
            <td class="px-4 py-2">{{ $brand->id }}</td>
            <td class="px-4 py-2">{{ $brand->name }}</td>
            <td class="px-4 py-2">{{ $brand->slug }}</td>
            <td class="px-4 py-2 space-x-2">
                <a href="{{ route('admin.brands.edit', $brand->id) }}" class="bg-yellow-400 hover:bg-yellow-500 text-white px-3 py-1 rounded">Sửa</a>
                <form action="{{ route('admin.brands.destroy', $brand->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Bạn có chắc muốn xóa?');">
                    @csrf
                    @method('DELETE')
                    <button class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded">Xóa</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

{{-- <div class="mt-4">
    {{ $brands->links() }}
    </div> --}}
@endsection


