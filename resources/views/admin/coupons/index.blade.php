@extends('layouts.admin')

@section('title', 'Danh sách phiếu giảm giá')

@section('content')
    <div class="bg-white rounded-lg shadow-md overflow-hidden max-w-9xl mx-auto">
        <!-- Header -->
        <div class="bg-gradient-to-r from-green-500 to-teal-600 px-6 py-4">
            <div class="flex justify-between items-center">
                <h1 class="text-2xl font-bold text-white">Quản lý phiếu giảm giá</h1>
                <a href="{{ route('admin.coupons.create') }}"
                    class="bg-white text-green-600 px-4 py-2 rounded-lg font-medium hover:bg-gray-100 transition duration-200 flex items-center">
                    <i class="fas fa-plus mr-2"></i> Thêm mới phiếu
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
                    <th class="px-4 py-2 text-left">Code</th>
                    <th class="px-4 py-2 text-left">Loại</th>
                    <th class="px-4 py-2 text-left">Giá trị</th>
                    <th class="px-4 py-2 text-left">Dùng/Limit</th>
                    <th class="px-4 py-2 text-left">Hiệu lực</th>
                    <th class="px-4 py-2 text-left">Hành động</th>
                </tr>
            </thead>
            <tbody>
                @foreach($coupons as $c)
                    <tr class="border-b">
                        <td class="px-4 py-2 font-mono">{{ $c->code }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $c->type }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $c->value }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $c->usage_count }} / {{ $c->usage_limit ?? '∞' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($c->is_active)
                                <span class="px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800">
                                    Đang bật
                                </span>
                            @else
                                <span class="px-2 py-1 text-xs font-medium rounded-full bg-red-100 text-red-800">
                                    Tắt
                                </span>
                            @endif
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex space-x-4 text-sm font-medium">
                                <a href="{{ route('admin.coupons.edit', $c) }}"
                                    class="text-yellow-600 hover:text-yellow-900 transition duration-200 flex items-center">
                                    <i class="fas fa-edit mr-1"></i> Sửa
                                </a>
                                <form action="{{ route('admin.coupons.destroy', $c) }}" method="POST" class="inline"
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
                @endforeach
            </tbody>
        </table>

        <div class="mt-4">{{ $coupons->links() }}</div>
    </div>

    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
@endsection