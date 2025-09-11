@extends('layouts.admin')

@section('title','Danh sách sản phẩm')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h2 class="text-2xl font-bold text-gray-800">Quản lý sản phẩm</h2>
    <a href="{{ route('admin.products.create') }}" class="bg-green-500 hover:bg-green-600 text-white py-2 px-4 rounded shadow transition">Thêm mới</a>
</div>

<div class="overflow-x-auto bg-white rounded shadow">
<table class="min-w-full divide-y divide-gray-200">
<thead class="bg-green-100">
<tr>
    <th class="px-4 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">ID</th>
    <th class="px-4 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Ảnh</th>
    <th class="px-4 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Tên sản phẩm</th>
    <th class="px-4 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Giá</th>
    <th class="px-4 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Hot</th>
    <th class="px-4 py-3 text-center text-xs font-medium text-gray-700 uppercase tracking-wider">Hành động</th>
</tr>
</thead>
<tbody class="bg-white divide-y divide-gray-200">
@foreach($products as $product)
<tr class="hover:bg-green-50 transition">
    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-700">{{ $product->id }}</td>
    
    {{-- Thumbnail ảnh --}}
    <td class="px-4 py-3 whitespace-nowrap">
        <img src="{{ asset($product->image) }}" alt="{{ $product->name }}" class="w-16 h-16 object-cover rounded">
    </td>

    <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-gray-900">{{ $product->name }}</td>
    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-700">{{ number_format($product->price) }} đ</td>
    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-700">{{ $product->is_hot ? 'Có' : 'Không' }}</td>
    <td class="px-4 py-3 whitespace-nowrap text-sm text-center space-x-2 flex justify-center">
        <a href="{{ route('admin.products.edit',$product->id) }}" class="bg-yellow-400 hover:bg-yellow-500 text-white px-3 py-1 rounded shadow transition">Sửa</a>
        <form action="{{ route('admin.products.destroy',$product->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Bạn có chắc muốn xóa?');">
            @csrf
            @method('DELETE')
            <button class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded shadow transition">Xóa</button>
        </form>
    </td>
</tr>
@endforeach
</tbody>
</table>
</div>

<div class="mt-4">
    {{ $products->links() }}
</div>
@endsection
