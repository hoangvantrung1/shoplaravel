@extends('layouts.admin')

@section('title', 'Thêm người dùng mới')

@section('content')
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <!-- Header -->
        <div class="bg-gradient-to-r from-green-500 to-teal-600 px-6 py-4">
            <div class="flex justify-between items-center">
                <h1 class="text-2xl font-bold text-white">Thêm người dùng</h1>
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

            <form action="{{ route('admin.users.store') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="block mb-1 font-semibold">Tên</label>
                    <input type="text" name="name" value="{{ old('name') }}" class="w-full border p-2 rounded" required>
                </div>
                <div class="mb-4">
                    <label class="block mb-1 font-semibold">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" class="w-full border p-2 rounded" required>
                </div>
                <div class="mb-4">
                    <label class="block mb-1 font-semibold">Mật khẩu</label>
                    <input type="password" name="password" class="w-full border p-2 rounded" required>
                </div>
                <div class="mb-4">
                    <label class="block mb-1 font-semibold">Xác nhận mật khẩu</label>
                    <input type="password" name="password_confirmation" class="w-full border p-2 rounded" required>
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">Thêm</button>
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
@endsection