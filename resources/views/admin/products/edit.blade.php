@extends('layouts.app')

@section('content')
    <h1 class="text-2xl font-bold mb-6">Chỉnh sửa sản phẩm</h1>

    <form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data"
        class="space-y-4">
        @csrf
        @method('PUT')
        <div>
            <label class="block font-semibold mb-1">Tên sản phẩm</label>
            <input type="text" name="name" value="{{ $product->name }}" class="w-full border px-3 py-2 rounded">
        </div>
        <div>
            <label class="block font-semibold mb-1">Giá</label>
            <input type="number" name="price" value="{{ $product->price }}" class="w-full border px-3 py-2 rounded">
        </div>
        <div>
            <label class="block font-semibold mb-1">Danh mục</label>
            <select name="category_id" class="w-full border px-3 py-2 rounded" required>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ $product->category_id == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block font-semibold mb-1">Ảnh</label>
            <input type="file" name="image" class="w-full">
            @if($product->image)
                <img src="{{ asset($product->image) }}" class="w-32 mt-2">
            @endif
        </div>

        <div>
            <label class="inline-flex items-center">
                <input type="checkbox" name="is_hot" class="form-checkbox" {{ $product->is_hot ? 'checked' : '' }}>
                <span class="ml-2">Hot</span>
            </label>
        </div>
        <button type="submit" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded">Cập nhật sản
            phẩm</button>
    </form>
@endsection