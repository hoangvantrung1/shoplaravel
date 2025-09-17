@extends('admin.dashboard')
<form action="{{ isset($product) ? route('admin.products.update', $product->id) : route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    @if(isset($product))
        @method('PUT')
    @endif

    <div class="mb-4">
        <label class="block mb-1 font-semibold">Tên sản phẩm</label>
        <input type="text" name="name" value="{{ $product->name ?? old('name') }}" class="w-full border px-3 py-2 rounded">
    </div>

    <div class="mb-4">
        <label class="block mb-1 font-semibold">Slug</label>
        <input type="text" name="slug" value="{{ $product->slug ?? old('slug') }}" class="w-full border px-3 py-2 rounded">
    </div>

    <div class="mb-4">
        <label class="block mb-1 font-semibold">Giá</label>
        <input type="number" name="price" value="{{ $product->price ?? old('price') }}" class="w-full border px-3 py-2 rounded">
    </div>

    <div class="mb-4">
        <label class="block mb-1 font-semibold">Ảnh</label>
        <input type="file" name="image" class="w-full">
        @if(isset($product) && $product->image)
            <img src="{{ asset($product->image) }}" class="w-32 mt-2">
        @endif
    </div>

    <div class="mb-4">
        <label class="inline-flex items-center">
            <input type="checkbox" name="is_hot" class="form-checkbox" {{ isset($product) && $product->is_hot ? 'checked' : '' }}>
            <span class="ml-2">Hot</span>
        </label>
    </div>

    <button type="submit" class="bg-green-500 hover:bg-green-600 text-white py-2 px-6 rounded">
        {{ isset($product) ? 'Cập nhật' : 'Tạo mới' }}
    </button>
</form>
