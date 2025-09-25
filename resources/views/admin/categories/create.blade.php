@extends('layouts.admin')

@section('title', 'Thêm danh mục mới')

@section('content')
    <div class="bg-white rounded-lg shadow-md overflow-hidden max-w-9xl mx-auto">
        <div class="bg-gradient-to-r from-green-500 to-teal-600 px-6 py-4">
            <div class="flex justify-between items-center">
                <h1 class="text-2xl font-bold text-white">Quản lý danh mục</h1>
            </div>
        </div>
        {{-- căn giữa nội dung --}}
        <div class="px-10 py-10 flex justify-center">
            <div class="w-full max-w-lg">
                @if(session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                        {{ session('success') }}
                    </div>
                @endif

                <form action="{{ route('admin.categories.store') }}" method="POST"
                    class="bg-white p-6 rounded-lg shadow-md">
                    @csrf

                    {{-- Tên danh mục --}}
                    <div class="mb-4">
                        <label for="name" class="block text-sm font-medium mb-1">Tên danh mục</label>
                        <input type="text" id="name" name="name" value="{{ old('name') }}"
                            class="w-full border @error('name') border-red-500 @enderror rounded px-3 py-2 focus:ring focus:ring-blue-200"
                            placeholder="Nhập tên danh mục" required>
                        @error('name')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Slug --}}
                    <div class="mb-4">
                        <label for="slug" class="block text-sm font-medium mb-1">Slug</label>
                        <input type="text" id="slug" name="slug" value="{{ old('slug') }}"
                            class="w-full border @error('slug') border-red-500 @enderror rounded px-3 py-2 focus:ring focus:ring-blue-200"
                            placeholder="ví dụ: dien-thoai" required readonly>
                        @error('slug')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex gap-2">
                        <a href="{{ route('admin.categories.index') }}"
                            class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded transition">
                            Hủy
                        </a>
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded transition">
                            Lưu
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('name').addEventListener('input', function () {
            let name = this.value;

            // Chuyển thành slug
            let slug = name.toLowerCase()
                .normalize('NFD').replace(/[\u0300-\u036f]/g, '') // bỏ dấu tiếng Việt
                .replace(/[^a-z0-9\s-]/g, '') // bỏ ký tự đặc biệt
                .replace(/\s+/g, '-')         // khoảng trắng -> dấu -
                .replace(/-+/g, '-')          // bỏ trùng dấu -
                .replace(/^-+|-+$/g, '');     // bỏ - ở đầu/cuối

            document.getElementById('slug').value = slug;
        });
    </script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
@endsection