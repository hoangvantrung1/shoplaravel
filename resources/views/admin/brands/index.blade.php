@extends('layouts.admin')

@section('title', 'Danh sách thương hiệu')

@section('content')
    <div class="bg-white rounded-lg shadow-md overflow-hidden max-w-9xl mx-auto">
        <!-- Header -->
        <div class="bg-gradient-to-r from-green-500 to-teal-600 px-6 py-4">
            <div class="flex justify-between items-center">
                <h1 class="text-2xl font-bold text-white">Quản lý thương hiệu</h1>
                <a href="{{ route('admin.brands.create') }}"
                    class="bg-white text-green-600 px-4 py-2 rounded-lg font-medium hover:bg-gray-100 transition duration-200 flex items-center">
                    <i class="fas fa-plus mr-2"></i> Thêm mới
                </a>
            </div>
        </div>
                    @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                    {{ session('success') }}
                </div>
            @endif
        <!-- Table -->
        <table class="min-w-full bg-white rounded shadow overflow-hidden">
            <thead>
                <tr class="bg-gray-100">
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tên
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Slug
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Hành động
                    </th>
                </tr>
            </thead>
            <tbody>
                @forelse($brands as $brand)
                    <tr class="border-b hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-sm font-medium text-gray-900">#{{ $brand->id }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-sm font-medium text-gray-900">{{ $brand->name }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-sm font-medium text-gray-900">{{ $brand->slug }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex space-x-4 text-sm font-medium">
                                <a href="{{ route('admin.brands.edit', $brand->id) }}"
                                    class="text-yellow-600 hover:text-yellow-900 transition duration-200 flex items-center">
                                    <i class="fas fa-edit mr-1"></i> Sửa
                                </a>
                                <form action="{{ route('admin.brands.destroy', $brand->id) }}" method="POST" class="inline"
                                    onsubmit="return confirm('Bạn có chắc muốn xóa thương hiệu này?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="text-red-600 hover:text-red-900 transition duration-200 flex items-center">
                                        <i class="fas fa-trash mr-1"></i> Xóa
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-6 py-4 text-center text-gray-500">
                            Không có thương hiệu nào.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
@endsection