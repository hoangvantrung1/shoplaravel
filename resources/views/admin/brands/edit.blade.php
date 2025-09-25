@extends('layouts.admin')

@section('title', 'Thêm thương hiệu mới')

@section('content')
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <!-- Header -->
        <div class="bg-gradient-to-r from-green-500 to-teal-600 px-6 py-4">
            <div class="flex justify-between items-center">
                <h1 class="text-2xl font-bold text-white">Sửa thương hiệu mới</h1>
            </div>
        </div>

        <div class="p-6 bg-white rounded shadow max-w-lg mx-auto">
            @if ($errors->any())
                <div class="bg-red-100 border-l-4 border-red-600 text-red-800 p-4 rounded mb-4">
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <form action="{{ route('admin.brands.update', $brand->id) }}" method="POST"
                class="bg-white p-6 rounded shadow max-w-xl">
                @csrf
                @method('PUT')
                <div class="mb-4">
                    <label for="name" class="block text-sm font-medium mb-1">Tên thương hiệu</label>
                    <input type="text" id="name" name="name" value="{{ old('name', $brand->name) }}"
                        class="w-full border @error('name') border-red-500 @enderror rounded px-3 py-2 focus:ring focus:ring-blue-200"
                        placeholder="Nhập tên thương hiệu" required>
                    @error('name')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
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
                    <a href="{{ route('admin.brands.index') }}"
                        class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded">Hủy</a>
                    <button class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">Cập nhật</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Thêm Font Awesome nếu chưa có -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        .pagination {
            display: flex;
            justify-content: center;
            list-style: none;
            padding: 0;
        }

        .pagination li {
            margin: 0 4px;
        }

        .pagination li a,
        .pagination li span {
            display: inline-block;
            padding: 8px 12px;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            color: #374151;
            text-decoration: none;
            transition: all 0.2s;
        }

        .pagination li a:hover {
            background-color: #f3f4f6;
        }

        .pagination li.active span {
            background-color: #10b981;
            color: white;
            border-color: #10b981;
        }

        .pagination li.disabled span {
            color: #9ca3af;
            cursor: not-allowed;
        }
    </style>
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
@endsection