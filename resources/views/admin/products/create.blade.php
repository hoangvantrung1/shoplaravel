@extends('layouts.admin')

@section('title', 'Thêm sản phẩm mới')

@section('content')
    <h1 class="text-2xl font-bold mb-6">Thêm sản phẩm mới</h1>

    {{-- Hiển thị lỗi validation --}}
    @if ($errors->any())
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded shadow">
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
        @csrf

        {{-- Tên sản phẩm --}}
        <div>
            <label class="block font-semibold mb-1">Tên sản phẩm</label>
            <input type="text" name="name" value="{{ old('name', $product->name ?? '') }}"
                class="w-full border px-3 py-2 rounded" required>
        </div>

        {{-- Giá gốc --}}
        <div>
            <label class="block font-semibold mb-1">Giá gốc (₫)</label>
            <input type="number" name="price" value="{{ old('price') }}" 
                   class="w-full border px-3 py-2 rounded" required min="0" step="0.01">
            <p class="text-sm text-gray-500 mt-1">Giá bán thông thường của sản phẩm</p>
        </div>
        
        {{-- Giá khuyến mãi --}}
        <div>
            <label class="block font-semibold mb-1">Giá khuyến mãi (₫) <span class="text-gray-500 text-sm">(Tùy chọn)</span></label>
            <input type="number" name="sale_price" value="{{ old('sale_price') }}" 
                   class="w-full border px-3 py-2 rounded" min="0" step="0.01" 
                   placeholder="Để trống nếu không có khuyến mãi">
            <p class="text-sm text-gray-500 mt-1">Giá bán khuyến mãi. Phải nhỏ hơn giá gốc để có hiệu lực.</p>
        </div>
        <div>
            <label class="block font-semibold mb-2">Số lượng tồn kho</label>
            <input type="number" name="stock" value="{{ old('stock', 0) }}"
                class="w-full border border-gray-300 px-3 py-2 rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
                required min="0" placeholder="0">
        </div>
        <div class="mb-4">
            <label for="description" class="block text-gray-700">Mô tả sản phẩm</label>
            <textarea name="description" id="description" rows="4"
                class="w-full border-gray-300 rounded shadow-sm">{{ old('description', $product->description ?? '') }}</textarea>
        </div>
        <div>
            <label class="block font-semibold mb-1">Thương hiệu</label>
            <select name="brand_id" class="w-full border px-3 py-2 rounded" required>
                <option value="">-- Chọn thương hiệu --</option>
                @foreach($brands as $brand)
                    <option value="{{ $brand->id }}" {{ old('brand_id') == $brand->id ? 'selected' : '' }}>
                        {{ $brand->name }}
                    </option>
                @endforeach
            </select>
        </div>
        {{-- Danh mục --}}
        <div>
            <label class="block font-semibold mb-1">Danh mục</label>
            <select name="category_id" class="w-full border px-3 py-2 rounded" required>
                <option value="">-- Chọn danh mục --</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Ảnh sản phẩm --}}
        <div>
            <label class="block font-semibold mb-1">Ảnh</label>
            <input type="file" name="image" class="w-full" required>
        </div>

        {{-- Hot --}}
        <div>
            <label class="inline-flex items-center">
                <input type="checkbox" name="is_hot" value="1" class="form-checkbox" {{ old('is_hot') ? 'checked' : '' }}>
                <span class="ml-2">Hot</span>
            </label>
        </div>

        {{-- Submit --}}
        <button type="submit" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded">
            Tạo sản phẩm
        </button>
    </form>
@endsection