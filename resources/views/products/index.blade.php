@extends('layouts.app')
<link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css">

@section('content')
    {{-- Kiểm tra nếu có tên danh mục hoặc thương hiệu, hiển thị tiêu đề --}}
    @if($categoryName || $brandName)
        <div class="mb-8">
            <div class="flex items-center space-x-4">
                <div class="w-1 h-8 bg-gradient-to-b from-purple-500 to-blue-500 rounded-full"></div>
                <div>
                    <h3 class="text-3xl font-bold text-gray-800">
                        @if($categoryName)
                            {{ $categoryName }}
                        @elseif($brandName)
                            {{ $brandName }}
                        @else
                            Tất cả sản phẩm
                        @endif
                    </h3>
                    <div class="flex items-center space-x-2 text-gray-500 mt-1">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                        <span class="text-sm">
                            @if($categoryName)
                                Danh mục sản phẩm
                            @elseif($brandName)
                                Thương hiệu
                            @else
                                Tất cả danh mục
                            @endif
                        </span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Hiển thị bộ lọc tìm kiếm --}}
        <div class="mb-6 p-4 bg-white rounded-lg shadow-md">
            <form action="{{ route('products.index') }}" method="GET" class="space-y-4">
                {{-- Giữ lại các tham số hiện tại --}}
                @if(request('category'))
                    <input type="hidden" name="category" value="{{ request('category') }}">
                @endif
                @if(request('brand_id'))
                    <input type="hidden" name="brand_id" value="{{ request('brand_id') }}">
                @endif

                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    {{-- Tìm kiếm --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tìm kiếm</label>
                        <input type="text" name="q" value="{{ request('q') }}" placeholder="Nhập tên sản phẩm..."
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-purple-500 focus:border-purple-500">
                    </div>

                    {{-- Lọc theo giá --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Giá từ</label>
                        <input type="number" name="min_price" value="{{ request('min_price') }}" placeholder="0"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-purple-500 focus:border-purple-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Giá đến</label>
                        <input type="number" name="max_price" value="{{ request('max_price') }}" placeholder="10000000"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-purple-500 focus:border-purple-500">
                    </div>

                    {{-- Sắp xếp --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Sắp xếp theo</label>
                        <select name="sort_by"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-purple-500 focus:border-purple-500">
                            <option value="created_at" {{ request('sort_by') == 'created_at' ? 'selected' : '' }}>Mới nhất
                            </option>
                            <option value="name" {{ request('sort_by') == 'name' ? 'selected' : '' }}>Tên A-Z</option>
                            <option value="price" {{ request('sort_by') == 'price' ? 'selected' : '' }}>Giá thấp đến cao</option>
                        </select>
                    </div>
                </div>

                <div class="flex space-x-2">
                    <button type="submit"
                        class="px-4 py-2 bg-purple-600 text-white rounded-md hover:bg-purple-700 transition-colors">
                        Áp dụng bộ lọc
                    </button>
                    <a href="{{ route('products.index') }}"
                        class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600 transition-colors">
                        Xóa bộ lọc
                    </a>
                </div>
            </form>
        </div>

        {{-- Hiển thị sản phẩm theo danh mục/thương hiệu --}}
        @if($products->count() > 0)
            <section class="mb-12">
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                    @foreach($products as $product)
                        <div class="bg-white rounded-lg shadow-md hover:shadow-xl overflow-hidden transition-shadow duration-300">
                            <a href="{{ route('product.show', $product->id) }}">
                                <div class="w-full aspect-square bg-gray-100 overflow-hidden">
                                    <img src="{{ $product->image }}" alt="{{ $product->name }}"
                                        class="w-full h-full object-cover transition-transform duration-300 hover:scale-105">
                                </div>
                                <div class="p-4">
                                    <h3 class="text-gray-800 font-semibold mb-1 line-clamp-1">{{ $product->name }}</h3>
                                    <p class="text-purple-600 font-bold mb-2">{{ number_format($product->price, 0, ',', '.') }}₫</p>
                                    <p class="text-gray-500 text-sm line-clamp-2">{{ $product->description }}</p>
                                    @if($product->brand)
                                        <p class="text-xs text-gray-400 mt-1">{{ $product->brand->name }}</p>
                                    @endif
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>

                {{-- Phân trang --}}
                <div class="mt-8">
                    {{ $products->links() }}
                </div>
            </section>
        @else
            <div class="text-center py-10 text-gray-500">
                <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
                </svg>
                <p class="text-lg font-medium">Không tìm thấy sản phẩm nào</p>
                <p class="text-sm">Thử điều chỉnh bộ lọc hoặc tìm kiếm với từ khóa khác</p>
            </div>
        @endif
    @else
        {{-- Trang chủ - hiển thị sản phẩm nổi bật --}}
        <h2 class="text-3xl font-bold mb-8 text-gray-800 text-center">
            @if(request('q'))
                Kết quả tìm kiếm: "{{ request('q') }}"
            @else
                Sản phẩm nổi bật
            @endif
        </h2>

        {{-- Bộ lọc tìm kiếm cho trang chủ --}}
        <div class="mb-6 p-4 bg-white rounded-lg shadow-md">
            <form action="{{ route('products.index') }}" method="GET" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                    {{-- Tìm kiếm --}}
                    <div>
                        <input type="text" name="q" value="{{ request('q') }}" placeholder="Tìm sản phẩm..."
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-purple-500 focus:border-purple-500">
                    </div>

                    {{-- Danh mục --}}
                    <div>
                        <select name="category"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-purple-500 focus:border-purple-500">
                            <option value="">Tất cả danh mục</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Thương hiệu --}}
                    <div>
                        <select name="brand_id"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-purple-500 focus:border-purple-500">
                            <option value="">Tất cả thương hiệu</option>
                            @foreach($brands as $brand)
                                <option value="{{ $brand->id }}" {{ request('brand_id') == $brand->id ? 'selected' : '' }}>
                                    {{ $brand->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Sắp xếp --}}
                    <div>
                        <select name="sort_by"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-purple-500 focus:border-purple-500">
                            <option value="created_at" {{ request('sort_by') == 'created_at' ? 'selected' : '' }}>Mới nhất
                            </option>
                            <option value="name" {{ request('sort_by') == 'name' ? 'selected' : '' }}>Tên A-Z</option>
                            <option value="price" {{ request('sort_by') == 'price' ? 'selected' : '' }}>Giá thấp đến cao</option>
                        </select>
                    </div>

                    <div>
                        <button type="submit"
                            class="w-full px-4 py-2 bg-purple-600 text-white rounded-md hover:bg-purple-700 transition-colors">
                            Tìm kiếm
                        </button>
                    </div>
                </div>
            </form>
        </div>

        @if($products->count() > 0)
            <section class="mb-12">
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                    @foreach($products as $product)
                        <div class="bg-white rounded-lg shadow-md hover:shadow-xl overflow-hidden transition-shadow duration-300">
                            <a href="{{ route('product.show', $product->id) }}">
                                <div class="w-full aspect-square bg-gray-100 overflow-hidden">
                                    <img src="{{ $product->image }}" alt="{{ $product->name }}"
                                        class="w-full h-full object-cover transition-transform duration-300 hover:scale-105">
                                </div>
                                <div class="p-4">
                                    <h3 class="text-gray-800 font-semibold mb-1 line-clamp-1">{{ $product->name }}</h3>
                                    <p class="text-purple-600 font-bold mb-2">{{ number_format($product->price, 0, ',', '.') }}₫</p>
                                    <p class="text-gray-500 text-sm line-clamp-2">{{ $product->description }}</p>
                                    @if($product->brand)
                                        <p class="text-xs text-gray-400 mt-1">{{ $product->brand->name }}</p>
                                    @endif
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>

                {{-- Phân trang --}}
                <div class="flex justify-center mt-8">
                    {{ $products->onEachSide(1)->links('components.pagination') }}
                </div>
            </section>
        @else
            <div class="text-center py-10 text-gray-500">
                <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 003.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 000 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 000-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
                </svg>
                <p class="text-lg font-medium">Không tìm thấy sản phẩm nào</p>
                <p class="text-sm">Thử tìm kiếm với từ khóa khác hoặc điều chỉnh bộ lọc</p>
            </div>
        @endif
    @endif

    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
@endsection