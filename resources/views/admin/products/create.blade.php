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

        {{-- Giá --}}
        <div>
            <label class="block font-semibold mb-1">Giá</label>
            <input type="number" name="price" value="{{ old('price') }}" class="w-full border px-3 py-2 rounded" required
                min="0">
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