@extends('layouts.admin')

@section('title', 'Danh sách sản phẩm')

@section('content')
    <div class="bg-white rounded-lg shadow-md overflow-hidden max-w-9xl mx-auto">
        <div class="bg-gradient-to-r from-green-500 to-teal-600 px-6 py-4">
            <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                <h1 class="text-2xl font-bold text-white">Quản lý sản phẩm</h1>
                <div class="flex flex-wrap items-center gap-3">
                    <a href="{{ route('admin.products.deals') }}"
                        class="bg-white/20 text-white px-4 py-2 rounded-lg font-medium hover:bg-white/30 transition duration-200 flex items-center">
                        <i class="fas fa-bolt mr-2"></i> Xem deal
                    </a>
                    <a href="{{ route('admin.products.create') }}"
                        class="bg-white text-green-600 px-4 py-2 rounded-lg font-medium hover:bg-gray-100 transition duration-200 flex items-center">
                        <i class="fas fa-plus mr-2"></i> Thêm mới
                    </a>
                </div>
            </div>
        </div>

        <div class="px-6 py-5 border-b border-gray-100 bg-white">
            <form method="GET" action="{{ route('admin.products.index') }}" class="grid grid-cols-1 lg:grid-cols-4 gap-4">
                <div>
                    <label class="text-sm font-medium text-gray-600 mb-1 block">Tìm kiếm</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                            <i class="fas fa-search"></i>
                        </span>
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Tên hoặc slug sản phẩm..."
                            class="w-full pl-10 pr-4 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-400">
                    </div>
                </div>

                <div>
                    <label class="text-sm font-medium text-gray-600 mb-1 block">Danh mục</label>
                    <select name="category_id"
                        class="w-full border border-gray-200 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-green-400">
                        <option value="">Tất cả</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="text-sm font-medium text-gray-600 mb-1 block">Thương hiệu</label>
                    <select name="brand_id"
                        class="w-full border border-gray-200 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-green-400">
                        <option value="">Tất cả</option>
                        @foreach($brands as $brand)
                            <option value="{{ $brand->id }}" {{ request('brand_id') == $brand->id ? 'selected' : '' }}>
                                {{ $brand->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="text-sm font-medium text-gray-600 mb-1 block">Tồn kho</label>
                    <div class="flex gap-2 items-center">
                        <select name="stock_status"
                            class="w-full border border-gray-200 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-green-400">
                            <option value="">Tất cả</option>
                            <option value="in_stock" {{ request('stock_status') === 'in_stock' ? 'selected' : '' }}>Còn hàng</option>
                            <option value="out_stock" {{ request('stock_status') === 'out_stock' ? 'selected' : '' }}>Hết hàng</option>
                        </select>
                        <button type="submit"
                            class="px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 transition flex items-center gap-2">
                            <i class="fas fa-filter"></i>
                            Lọc
                        </button>
                        @if(request()->hasAny(['search','category_id','brand_id','stock_status']))
                            <a href="{{ route('admin.products.index') }}"
                               class="px-4 py-3 bg-red-100 text-red-600 rounded-lg hover:bg-red-200 transition flex items-center gap-2"
                               title="Xóa bộ lọc">
                                <i class="fas fa-undo"></i>
                            </a>
                        @endif
                    </div>
                </div>
            </form>
        </div>

        <div class="px-6 py-8">
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                    {{ session('success') }}
                </div>
            @endif

            <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 rounded-lg">
                <table class="min-w-full divide-y divide-gray-300">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ảnh
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tên
                                sản phẩm</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Giá
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Giá khuyến mãi
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Danh
                                mục</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Thương hiệu</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tồn
                                kho</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Hành động</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($products as $product)
                            <tr class="hover:bg-gray-50 transition duration-150">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-sm font-medium text-gray-900">#{{ $product->id }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div
                                        class="w-12 h-12 bg-gray-100 rounded-lg overflow-hidden flex items-center justify-center">
                                        @if($product->image)
                                            <img src="{{ asset($product->image) }}" alt="{{ $product->name }}"
                                                class="w-full h-full object-cover">
                                        @else
                                            <i class="fas fa-image text-gray-400"></i>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm font-medium text-gray-900">{{ Str::limit($product->name, 50) }}</div>
                                    @if($product->description)
                                        <div class="text-sm text-gray-500 mt-1">{{ Str::limit($product->description, 70) }}</div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex flex-col">
                                        <span class="text-sm font-semibold text-green-600">
                                            {{ number_format($product->price, 0, ',', '.') }}₫
                                        </span>
                                        @if($product->sale_price && $product->sale_price > 0 && $product->sale_price < $product->price)
                                            @php
                                                $discount = round((1 - $product->sale_price / $product->price) * 100);
                                            @endphp
                                            <span class="text-xs text-gray-500 line-through mt-1">
                                                Giá gốc
                                            </span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($product->sale_price && $product->sale_price > 0 && $product->sale_price < $product->price)
                                        @php
                                            $discount = round((1 - $product->sale_price / $product->price) * 100);
                                        @endphp
                                        <div class="flex flex-col">
                                            <span class="text-sm font-semibold text-red-600">
                                                {{ number_format($product->sale_price, 0, ',', '.') }}₫
                                            </span>
                                            <span class="text-xs font-medium text-red-500 mt-1">
                                                Giảm {{ $discount }}%
                                            </span>
                                        </div>
                                    @else
                                        <span class="text-sm text-gray-400 italic">—</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-sm text-gray-700">
                                        {{ $product->category ? $product->category->name : '—' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-sm text-gray-700">
                                        {{ $product->brand ? $product->brand->name : '—' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($product->stock > 10)
                                        <span class="px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800">
                                            {{ $product->stock }} sản phẩm
                                        </span>
                                    @elseif($product->stock > 0)
                                        <span class="px-2 py-1 text-xs font-medium rounded-full bg-yellow-100 text-yellow-800">
                                            {{ $product->stock }} sản phẩm
                                        </span>
                                    @else
                                        <span class="px-2 py-1 text-xs font-medium rounded-full bg-red-100 text-red-800">
                                            Hết hàng
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-center">
                                    <div class="flex justify-center space-x-2">
                                        <a href="{{ route('admin.products.edit', $product->id) }}"
                                            class="text-yellow-600 hover:text-yellow-900 transition duration-200 flex items-center">
                                            <i class="fas fa-edit mr-1"></i> Sửa
                                        </a>
                                        <span class="text-gray-300">|</span>
                                        <a href="{{ route('admin.products.logs', $product->id) }}"
                                            class="text-blue-600 hover:text-blue-900 transition duration-200 flex items-center"
                                            title="Xem lịch sử thay đổi">
                                            <i class="fas fa-history mr-1"></i> Log
                                        </a>
                                        <span class="text-gray-300">|</span>
                                        <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST"
                                            class="inline" onsubmit="return confirm('Bạn có chắc muốn xóa sản phẩm này?');">
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
            </div>

            <!-- Phân trang -->
            <div class="mt-6">
                {{ $products->links() }}
            </div>
        </div>
    </div>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
@endsection