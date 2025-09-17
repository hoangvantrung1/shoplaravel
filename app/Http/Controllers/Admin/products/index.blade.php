@extends('layouts.admin')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold">Quản lý sản phẩm</h1>
    <a href="{{ route('admin.products.create') }}" class="bg-green-500 hover:bg-green-600 text-white py-2 px-4 rounded">Thêm mới</a>
</div>

@if(session('success'))
<div class="bg-green-200 text-green-800 p-3 rounded mb-4">{{ session('success') }}</div>
@endif

<table class="min-w-full bg-white rounded shadow overflow-hidden">
    <thead>
        <tr class="bg-gray-100">
            <th class="px-4 py-2">ID</th>
            <th class="px-4 py-2">Tên</th>
            <th class="px-4 py-2">Giá</th>
            <th class="px-4 py-2">Hot</th>
            <th class="px-4 py-2">Hành động</th>
        </tr>
    </thead>
    <tbody>
        @foreach($products as $product)
        <tr class="border-b">
            <td class="px-4 py-2">{{ $product->id }}</td>
            <td class="px-4 py-2">{{ $product->name }}</td>
            <td class="px-4 py-2">{{ number_format($product->price) }} đ</td>
            <td class="px-4 py-2">{{ $product->is_hot ? 'Có' : 'Không' }}</td>
            <td class="px-4 py-2 space-x-2">
                <a href="{{ route('admin.products.edit', $product->id) }}" class="bg-yellow-400 hover:bg-yellow-500 text-white px-3 py-1 rounded">Sửa</a>
                <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Bạn có chắc muốn xóa?');">
                    @csrf
                    @method('DELETE')
                    <button class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded">Xóa</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

<div class="mt-4">
    {{ $products->links() }}
</div>
@endsection
