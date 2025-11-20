@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 pt-20 w-full overflow-x-hidden">
    <div class="max-w-7xl mx-auto px-2 sm:px-4 md:px-6 lg:px-8 py-4 sm:py-6 md:py-8 w-full box-border" style="margin-left: auto; margin-right: auto;">
        
        {{-- Header Section - Đơn giản hơn trên mobile --}}
        <div class="mb-4 sm:mb-6 md:mb-8 opacity-0 animate-fade-in">
            <div class="flex items-center justify-between flex-wrap gap-2 sm:gap-0">
                <div class="flex items-center space-x-2 sm:space-x-3 md:space-x-4 flex-1 min-w-0">
                    <div class="w-0.5 sm:w-1 h-6 sm:h-8 md:h-12 bg-gradient-to-b from-purple-500 to-blue-500 rounded-full flex-shrink-0"></div>
                    <div class="flex-1 min-w-0">
                        <h1 class="text-lg sm:text-2xl md:text-3xl lg:text-4xl font-bold text-gray-900 break-words">
                            @if($categoryName)
                                {{ $categoryName }}
                            @elseif($brandName)
                                {{ $brandName }}
                            @elseif(request('q'))
                                Tìm kiếm: "{{ request('q') }}"
                            @else
                                Sản Phẩm Nổi Bật
                            @endif
                        </h1>
                        <p class="text-gray-600 mt-1 sm:mt-2 flex items-center space-x-1 sm:space-x-2 text-xs sm:text-sm md:text-base hidden sm:flex">
                            <i class="fas fa-tag text-purple-500 text-xs sm:text-sm"></i>
                            <span>
                                @if($categoryName)
                                    Danh mục sản phẩm chất lượng
                                @elseif($brandName)
                                    Thương hiệu uy tín
                                @else
                                    Khám phá bộ sưu tập đa dạng
                                @endif
                            </span>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Filter Button - Mobile (Popup/Drawer) --}}
        <div class="mb-4 sm:mb-6 md:mb-8 md:hidden">
            <button id="filterDrawerBtn" 
                    class="w-full flex items-center justify-between bg-gradient-to-r from-purple-600 to-blue-500 text-white px-4 py-3 rounded-xl font-semibold shadow-lg hover:from-purple-700 hover:to-blue-600 transition-all duration-300">
                <span class="flex items-center">
                    <i class="fas fa-filter mr-2"></i>
                    Bộ lọc sản phẩm
                </span>
                <i class="fas fa-chevron-down transition-transform duration-300" id="filterDrawerIcon"></i>
            </button>
        </div>

        {{-- Compact Filter Section - Desktop --}}
        <div class="mb-8 opacity-0 animate-slide-up hidden md:block" style="animation-delay: 0.2s;">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <form action="{{ route('products.index') }}" method="GET" class="space-y-4" id="desktopFilterForm">
                    {{-- Hidden fields for current filters --}}
                    @if(request('category'))
                        <input type="hidden" name="category" value="{{ request('category') }}">
                    @endif
                    @if(request('brand_id'))
                        <input type="hidden" name="brand_id" value="{{ request('brand_id') }}">
                    @endif

                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        {{-- Search --}}
                        <div class="md:col-span-2">
                            <div class="relative">
                                <input type="text" name="q" value="{{ request('q') }}" 
                                       placeholder="Tìm kiếm sản phẩm..."
                                       class="w-full pl-10 pr-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-300">
                                <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                            </div>
                        </div>

                        {{-- Category & Brand --}}
                        <div>
                            <select name="category"
                                    class="w-full px-3 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-300">
                                <option value="">Tất cả danh mục</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <select name="brand_id"
                                    class="w-full px-3 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-300">
                                <option value="">Tất cả thương hiệu</option>
                                @foreach($brands as $brand)
                                    <option value="{{ $brand->id }}" {{ request('brand_id') == $brand->id ? 'selected' : '' }}>
                                        {{ $brand->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- Second Row - Price & Sort --}}
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        {{-- Price Range --}}
                        <div class="md:col-span-2">
                            <div class="flex space-x-2">
                                <input type="number" name="min_price" value="{{ request('min_price') }}" 
                                       placeholder="Giá từ"
                                       class="flex-1 px-3 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-300">
                                <input type="number" name="max_price" value="{{ request('max_price') }}" 
                                       placeholder="Giá đến"
                                       class="flex-1 px-3 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-300">
                            </div>
                        </div>

                        {{-- Sort --}}
                        <div>
                            <select name="sort_by"
                                    class="w-full px-3 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-300">
                                <option value="created_at" {{ request('sort_by') == 'created_at' ? 'selected' : '' }}>Mới nhất</option>
                                <option value="name" {{ request('sort_by') == 'name' ? 'selected' : '' }}>Tên A-Z</option>
                                <option value="price" {{ request('sort_by') == 'price' ? 'selected' : '' }}>Giá cao đến thấp</option>
                                <option value="price_desc" {{ request('sort_by') == 'price_desc' ? 'selected' : '' }}>Giá thấp đến cao</option>
                            </select>
                        </div>

                        {{-- Action Buttons --}}
                        <div class="flex space-x-2">
                            <button type="submit"
                                    class="flex-1 bg-gradient-to-r from-purple-600 to-blue-500 text-white px-4 py-3 rounded-xl hover:from-purple-700 hover:to-blue-600 transition-all duration-300 font-semibold">
                                <i class="fas fa-filter mr-2"></i>
                                Lọc
                            </button>
                            <a href="{{ route('products.index') }}"
                               class="px-4 py-3 border border-gray-300 text-gray-700 rounded-xl hover:bg-gray-50 transition-all duration-300 flex items-center justify-center">
                                <i class="fas fa-redo"></i>
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        {{-- Filter Drawer - Mobile (Popup) --}}
        <div id="filterDrawer" class="fixed inset-0 bg-black/50 z-50 hidden md:hidden transition-opacity duration-300">
            <div class="absolute bottom-0 left-0 right-0 bg-white rounded-t-3xl shadow-2xl max-h-[90vh] overflow-y-auto transform translate-y-full transition-transform duration-300">
                <div class="sticky top-0 bg-white border-b border-gray-200 px-4 py-4 flex items-center justify-between z-10">
                    <h3 class="text-lg font-bold text-gray-900 flex items-center">
                        <i class="fas fa-filter mr-2 text-purple-600"></i>
                        Bộ lọc sản phẩm
                    </h3>
                    <button id="closeFilterDrawer" class="p-2 rounded-full hover:bg-gray-100 transition-colors">
                        <i class="fas fa-times text-gray-600"></i>
                    </button>
                </div>
                
                <form action="{{ route('products.index') }}" method="GET" class="p-4 space-y-4" id="mobileFilterForm">
                    {{-- Hidden fields for current filters --}}
                    @if(request('category'))
                        <input type="hidden" name="category" value="{{ request('category') }}">
                    @endif
                    @if(request('brand_id'))
                        <input type="hidden" name="brand_id" value="{{ request('brand_id') }}">
                    @endif

                    {{-- Search --}}
                    <div class="relative">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-search mr-2 text-purple-500"></i>
                            Tìm kiếm
                        </label>
                        <input type="text" name="q" value="{{ request('q') }}" 
                               placeholder="Tìm kiếm sản phẩm..."
                               class="w-full pl-10 pr-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-300 text-sm">
                        <i class="fas fa-search absolute left-3 top-[42px] transform -translate-y-1/2 text-gray-400 text-sm"></i>
                    </div>

                    {{-- Category --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-tags mr-2 text-purple-500"></i>
                            Danh mục
                        </label>
                        <select name="category"
                                class="w-full px-3 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-300 text-sm">
                            <option value="">Tất cả danh mục</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Brand --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-certificate mr-2 text-purple-500"></i>
                            Thương hiệu
                        </label>
                        <select name="brand_id"
                                class="w-full px-3 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-300 text-sm">
                            <option value="">Tất cả thương hiệu</option>
                            @foreach($brands as $brand)
                                <option value="{{ $brand->id }}" {{ request('brand_id') == $brand->id ? 'selected' : '' }}>
                                    {{ $brand->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Price Range --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-dollar-sign mr-2 text-purple-500"></i>
                            Khoảng giá
                        </label>
                        <div class="flex space-x-2">
                            <input type="number" name="min_price" value="{{ request('min_price') }}" 
                                   placeholder="Từ"
                                   class="flex-1 px-3 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-300 text-sm">
                            <input type="number" name="max_price" value="{{ request('max_price') }}" 
                                   placeholder="Đến"
                                   class="flex-1 px-3 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-300 text-sm">
                        </div>
                    </div>

                    {{-- Sort --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-sort mr-2 text-purple-500"></i>
                            Sắp xếp
                        </label>
                        <select name="sort_by"
                                class="w-full px-3 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-300 text-sm">
                            <option value="created_at" {{ request('sort_by') == 'created_at' ? 'selected' : '' }}>Mới nhất</option>
                            <option value="name" {{ request('sort_by') == 'name' ? 'selected' : '' }}>Tên A-Z</option>
                            <option value="price" {{ request('sort_by') == 'price' ? 'selected' : '' }}>Giá cao đến thấp</option>
                            <option value="price_desc" {{ request('sort_by') == 'price_desc' ? 'selected' : '' }}>Giá thấp đến cao</option>
                        </select>
                    </div>

                    {{-- Action Buttons --}}
                    <div class="flex space-x-2 pt-2 sticky bottom-0 bg-white pb-4 border-t border-gray-200 -mx-4 px-4 mt-4">
                        <button type="submit"
                                class="flex-1 bg-gradient-to-r from-purple-600 to-blue-500 text-white px-4 py-3 rounded-xl hover:from-purple-700 hover:to-blue-600 transition-all duration-300 font-semibold text-sm">
                            <i class="fas fa-filter mr-2"></i>
                            Áp dụng
                        </button>
                        <a href="{{ route('products.index') }}"
                           class="px-4 py-3 border border-gray-300 text-gray-700 rounded-xl hover:bg-gray-50 transition-all duration-300 flex items-center justify-center">
                            <i class="fas fa-redo"></i>
                        </a>
                    </div>
                </form>
            </div>
        </div>

        {{-- Products Grid - 2 cột trên mobile --}}
        @if($products->count() > 0)
            <section class="opacity-0 animate-fade-in w-full" style="animation-delay: 0.4s;">
                {{-- Products --}}
                <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-2 sm:gap-3 md:gap-4 lg:gap-6 xl:gap-8 w-full">
                    @foreach($products as $index => $product)
                        @php
                            $hasSale = isset($product->sale_price) && (float) $product->sale_price > 0 && (float) $product->sale_price < (float) $product->price;
                            $finalPrice = $hasSale ? (float) $product->sale_price : (float) $product->price;
                            $discount = $hasSale && (float) $product->price > 0 ? max(0, min(99, round((1 - $product->sale_price / $product->price) * 100))) : null;
                            $isHot = isset($product->is_hot) ? (bool)$product->is_hot : (isset($product->hot) ? (bool)$product->hot : false);
                            $isNew = $product->created_at->gt(now()->subDays(7));
                        @endphp
                        
                        <div class="group bg-white rounded-lg sm:rounded-xl md:rounded-2xl shadow-sm hover:shadow-xl md:hover:shadow-2xl transition-all duration-500 overflow-hidden opacity-0 animate-product-card w-full"
                             style="animation-delay: {{ $index * 0.1 }}s;">
                            {{-- Product Image --}}
                            <div class="relative overflow-hidden">
                                <a href="{{ route('product.show', $product->id) }}" class="block">
                                    <div class="relative w-full aspect-[4/3] bg-gray-100 overflow-hidden">
                                        <img src="{{ $product->image }}" alt="{{ $product->name }}"
                                             class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110"
                                             loading="lazy" decoding="async">
                                        <div class="absolute inset-0 bg-black/0 group-hover:bg-black/10 transition-all duration-500"></div>
                                        
                                        {{-- Badges --}}
                                        <div class="absolute top-1.5 left-1.5 sm:top-2 sm:left-2 md:top-3 md:left-3 flex flex-col space-y-1">
                                            @if($hasSale)
                                                <span class="bg-red-500 text-white text-[10px] sm:text-xs font-bold px-1.5 sm:px-2 py-0.5 sm:py-1 rounded-full shadow-lg">
                                                    -{{ $discount }}%
                                                </span>
                                            @endif
                                            @if($isHot)
                                                <span class="bg-orange-500 text-white text-[10px] sm:text-xs font-bold px-1.5 sm:px-2 py-0.5 sm:py-1 rounded-full shadow-lg">
                                                    HOT
                                                </span>
                                            @endif
                                            @if($isNew)
                                                <span class="bg-green-500 text-white text-[10px] sm:text-xs font-bold px-1.5 sm:px-2 py-0.5 sm:py-1 rounded-full shadow-lg">
                                                    MỚI
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </a>

                                {{-- Quick Actions --}}
                                <div class="absolute top-1.5 right-1.5 sm:top-2 sm:right-2 md:top-3 md:right-3 opacity-100 sm:opacity-0 sm:group-hover:opacity-100 transition-all duration-500 transform translate-x-0 sm:translate-x-4 sm:group-hover:translate-x-0">
                                    {{-- Wishlist Button --}}
                                    <button onclick="toggleWishlist({{ $product->id }}, this)" 
                                            class="wishlist-btn bg-white/90 hover:bg-white text-gray-800 p-1.5 sm:p-2 rounded-full shadow-lg transition-all duration-300 transform hover:scale-110 mb-1 sm:mb-2 {{ auth()->check() && $product->isInWishlist() ? 'text-red-500' : '' }}">
                                        <i class="fas fa-heart text-xs sm:text-sm"></i>
                                    </button>
                                    
                                    {{-- Quick View Button --}}
                                        <button onclick="window.location.href='{{ route('product.show', $product->id) }}'"
                                                class="bg-white/90 hover:bg-white text-gray-800 p-1.5 sm:p-2 rounded-full shadow-lg transition-all duration-300 transform hover:scale-110">
                                            <i class="fas fa-eye text-xs sm:text-sm"></i>
                                        </button>
                                </div>
                            </div>

                            {{-- Product Info --}}
                            <div class="p-2.5 sm:p-3 md:p-4 lg:p-6">
                                {{-- Brand --}}
                                @if($product->brand)
                                    <div class="mb-2">
                                        <span class="inline-block bg-gray-100 text-gray-600 text-xs font-medium px-2 py-1 rounded">
                                            {{ $product->brand->name }}
                                        </span>
                                    </div>
                                @endif

                                {{-- Title --}}
                                <a href="{{ route('product.show', $product->id) }}" class="block group">
                                    <h3 class="text-gray-900 font-semibold mb-1 sm:mb-2 line-clamp-2 group-hover:text-purple-600 transition-colors duration-300 text-xs sm:text-sm md:text-base leading-tight">
                                        {{ $product->name }}
                                    </h3>
                                </a>

                                {{-- Description - Ẩn trên mobile --}}
                                <p class="text-gray-500 text-xs sm:text-sm mb-2 sm:mb-3 md:mb-4 line-clamp-2 leading-relaxed hidden sm:block">
                                    {{ $product->description }}
                                </p>

                                {{-- Price & Actions --}}
                                <div class="flex items-center justify-between gap-1.5 sm:gap-2">
                                    <div class="flex flex-col flex-1 min-w-0">
                                        <div class="flex items-center space-x-1 sm:space-x-2 flex-wrap">
                                            <span class="text-sm sm:text-base md:text-lg lg:text-xl font-bold text-purple-600 whitespace-nowrap">
                                                {{ number_format($finalPrice, 0, ',', '.') }}₫
                                            </span>
                                            @if($hasSale)
                                                <span class="text-gray-400 line-through text-[10px] sm:text-xs whitespace-nowrap">
                                                    {{ number_format($product->price, 0, ',', '.') }}₫
                                                </span>
                                            @endif
                                        </div>
                                        @if($product->stock > 0)
                                            <span class="text-green-600 text-[10px] sm:text-xs font-medium mt-0.5 sm:mt-1">
                                                <i class="fas fa-check-circle mr-0.5 sm:mr-1 text-[8px] sm:text-[10px]"></i>
                                                Còn hàng
                                            </span>
                                        @else
                                            <span class="text-red-600 text-[10px] sm:text-xs font-medium mt-0.5 sm:mt-1">
                                                <i class="fas fa-times-circle mr-0.5 sm:mr-1 text-[8px] sm:text-[10px]"></i>
                                                Hết hàng
                                            </span>
                                        @endif
                                    </div>

                                    {{-- Add to Cart Button --}}
                                    @if($product->stock > 0)
                                        <form action="{{ route('cart.add', $product->id) }}" method="POST" class="inline flex-shrink-0">
                                            @csrf
                                            <button type="submit" 
                                                    class="bg-gradient-to-r from-purple-500 to-blue-500 hover:from-purple-600 hover:to-blue-600 text-white p-1.5 sm:p-2 md:p-3 rounded-lg sm:rounded-xl transition-all duration-300 transform hover:scale-110 shadow-md hover:shadow-lg">
                                                <i class="fas fa-shopping-cart text-xs sm:text-sm md:text-base"></i>
                                            </button>
                                        </form>
                                    @else
                                        <button class="bg-gray-400 text-white p-1.5 sm:p-2 md:p-3 rounded-lg sm:rounded-xl cursor-not-allowed flex-shrink-0" disabled>
                                            <i class="fas fa-ban text-xs sm:text-sm md:text-base"></i>
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Pagination - Centered --}}
                <div class="mt-12 flex justify-center opacity-0 animate-fade-in" style="animation-delay: 0.6s;">
                    {{ $products->onEachSide(1)->links('components.pagination') }}
                </div>
            </section>
        @else
            {{-- Empty State --}}
            <div class="text-center py-16 opacity-0 animate-fade-in" style="animation-delay: 0.3s;">
                <div class="max-w-md mx-auto">
                    <div class="w-24 h-24 mx-auto mb-6 bg-gradient-to-r from-purple-100 to-blue-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-search text-3xl text-purple-500"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-3">Không tìm thấy sản phẩm</h3>
                    <p class="text-gray-600 mb-8 leading-relaxed">
                        @if(request('q'))
                            Không có kết quả phù hợp với từ khóa "{{ request('q') }}". 
                            Hãy thử tìm kiếm với từ khóa khác hoặc điều chỉnh bộ lọc.
                        @else
                            Hiện không có sản phẩm nào phù hợp với tiêu chí lọc của bạn.
                            Vui lòng thử lại với bộ lọc khác.
                        @endif
                    </p>
                    <a href="{{ route('products.index') }}"
                       class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-purple-600 to-blue-500 text-white font-semibold rounded-xl hover:from-purple-700 hover:to-blue-600 transition-all duration-300 transform hover:scale-105">
                        <i class="fas fa-redo mr-2"></i>
                        Xóa bộ lọc
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>

<style>
/* Đảm bảo responsive hoàn toàn */
html, body {
    overflow-x: hidden;
    max-width: 100vw;
}

/* Đảm bảo grid 2 cột trên mobile - Force override cho TẤT CẢ các trang */
@media (max-width: 639px) {
    /* Target tất cả grid có grid-cols-2 */
    .grid.grid-cols-2,
    .grid[class*="grid-cols-2"],
    section .grid.grid-cols-2,
    section .grid[class*="grid-cols-2"],
    div .grid.grid-cols-2,
    div .grid[class*="grid-cols-2"] {
        display: grid !important;
        grid-template-columns: repeat(2, minmax(0, 1fr)) !important;
        gap: 0.5rem !important;
        width: 100% !important;
    }
    
    /* Đảm bảo product cards không bị stretch */
    .grid.grid-cols-2 > div,
    section .grid.grid-cols-2 > div,
    div .grid.grid-cols-2 > div {
        width: 100% !important;
        max-width: 100% !important;
        min-width: 0 !important;
        box-sizing: border-box !important;
    }
    
    /* Đảm bảo container không bị overflow */
    .max-w-7xl {
        padding-left: 0.5rem !important;
        padding-right: 0.5rem !important;
    }
}

.animate-fade-in {
    animation: fadeIn 0.8s ease-out forwards;
}

.animate-slide-up {
    animation: slideUp 0.8s ease-out forwards;
}

.animate-product-card {
    animation: productCard 0.6s ease-out forwards;
}

@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes slideUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes productCard {
    from {
        opacity: 0;
        transform: translateY(20px) scale(0.95);
    }
    to {
        opacity: 1;
        transform: translateY(0) scale(1);
    }
}

.line-clamp-1 {
    display: -webkit-box;
    -webkit-line-clamp: 1;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.aspect-\[4\/3\] {
    aspect-ratio: 4/3;
}

/* Custom pagination styling */
.pagination {
    display: flex;
    justify-content: center;
    list-style: none;
    padding: 0;
    margin: 0;
}

.pagination li {
    margin: 0 4px;
}

.pagination li a,
.pagination li span {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 40px;
    height: 40px;
    padding: 0 12px;
    border: 1px solid #e5e7eb;
    border-radius: 10px;
    background: white;
    color: #6b7280;
    font-weight: 500;
    transition: all 0.3s ease;
}

.pagination li a:hover {
    background: #f3f4f6;
    border-color: #d1d5db;
    color: #374151;
}

.pagination li.active span {
    background: linear-gradient(135deg, #8b5cf6, #3b82f6);
    border-color: #8b5cf6;
    color: white;
}

.pagination li.disabled span {
    background: #f9fafb;
    color: #9ca3af;
    cursor: not-allowed;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Filter Drawer - Mobile
    const filterDrawerBtn = document.getElementById('filterDrawerBtn');
    const filterDrawer = document.getElementById('filterDrawer');
    const closeFilterDrawer = document.getElementById('closeFilterDrawer');
    const filterDrawerIcon = document.getElementById('filterDrawerIcon');
    const body = document.body;

    function openFilterDrawer() {
        if (!filterDrawer) return;
        filterDrawer.classList.remove('hidden');
        setTimeout(() => {
            const drawerContent = filterDrawer.querySelector('.absolute');
            if (drawerContent) {
                drawerContent.classList.remove('translate-y-full');
            }
            filterDrawerIcon.classList.add('rotate-180');
        }, 10);
        body.style.overflow = 'hidden';
    }

    function closeFilterDrawerFunc() {
        if (!filterDrawer) return;
        const drawerContent = filterDrawer.querySelector('.absolute');
        if (drawerContent) {
            drawerContent.classList.add('translate-y-full');
        }
        filterDrawerIcon.classList.remove('rotate-180');
        setTimeout(() => {
            filterDrawer.classList.add('hidden');
            body.style.overflow = '';
        }, 300);
    }

    if (filterDrawerBtn) {
        filterDrawerBtn.addEventListener('click', openFilterDrawer);
    }

    if (closeFilterDrawer) {
        closeFilterDrawer.addEventListener('click', closeFilterDrawerFunc);
    }

    if (filterDrawer) {
        filterDrawer.addEventListener('click', function(e) {
            if (e.target === filterDrawer) {
                closeFilterDrawerFunc();
            }
        });
    }

    // Add intersection observer for product cards
    const productCards = document.querySelectorAll('.animate-product-card');
    
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.animationPlayState = 'running';
            }
        });
    }, { threshold: 0.1 });

    productCards.forEach(card => {
        observer.observe(card);
    });

    // Wishlist functionality
    window.toggleWishlist = async function(productId, button) {
        // Removed console.log for production
        const icon = button.querySelector('i');
        const isInWishlist = icon.classList.contains('text-red-500');
        
        try {
            const response = await fetch(`/wishlist/${productId}/toggle`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            });

            const data = await response.json();
            // Removed console.log for production
            
            if (data.success) {
                if (data.action === 'added') {
                    icon.classList.add('text-red-500');
                    showNotification('Đã thêm vào danh sách yêu thích', 'success');
                } else {
                    icon.classList.remove('text-red-500');
                    showNotification('Đã xóa khỏi danh sách yêu thích', 'info');
                }
                
                // Update wishlist count in header
                if (typeof updateWishlistCount === 'function') {
                    updateWishlistCount(data.wishlist_count);
                }
            } else {
                if (response.status === 401) {
                    showNotification('Vui lòng đăng nhập để sử dụng tính năng yêu thích', 'error');
                    // Redirect to login page after 2 seconds
                    setTimeout(() => {
                        window.location.href = '{{ route("login") }}';
                    }, 2000);
                } else {
                    showNotification(data.message || 'Có lỗi xảy ra', 'error');
                }
            }
        } catch (error) {
            console.error('Error:', error);
            showNotification('Có lỗi xảy ra khi kết nối', 'error');
        }
    };

    // Show notification function
    function showNotification(message, type = 'success') {
        const notification = document.createElement('div');
        notification.className = `fixed top-20 right-4 z-50 px-6 py-3 rounded-lg shadow-lg transition-all duration-300 transform translate-x-full ${
            type === 'success' ? 'bg-purple-500 text-white' : 
            type === 'error' ? 'bg-red-500 text-white' : 
            type === 'info' ? 'bg-blue-500 text-white' : 'bg-gray-500 text-white'
        }`;
        notification.innerHTML = `
            <div class="flex items-center">
                <i class="fas ${type === 'success' ? 'fa-check-circle' : 
                              type === 'error' ? 'fa-exclamation-circle' : 
                              type === 'info' ? 'fa-info-circle' : 'fa-bell'} mr-2"></i>
                <span>${message}</span>
            </div>
        `;
        
        document.body.appendChild(notification);
        
        // Hiệu ứng xuất hiện
        setTimeout(() => {
            notification.classList.remove('translate-x-full');
        }, 100);
        
        // Tự động xóa sau 4 giây
        setTimeout(() => {
            notification.classList.add('translate-x-full');
            setTimeout(() => {
                if (notification.parentNode) {
                    notification.parentNode.removeChild(notification);
                }
            }, 300);
        }, 4000);
    }

    // Add to cart functionality với hiển thị thông báo
    const addToCartButtons = document.querySelectorAll('form[action*="cart.add"] button');
    
    addToCartButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            
            const form = this.closest('form');
            const originalHTML = this.innerHTML;
            
            // Add loading state
            this.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
            this.disabled = true;
            
            // Submit form bình thường (không dùng AJAX)
            form.submit();
            
            // Khôi phục button sau 2 giây (phòng trường hợp có lỗi)
            setTimeout(() => {
                this.innerHTML = originalHTML;
                this.disabled = false;
            }, 2000);
        });
    });

    // Hiển thị thông báo từ session
    @if(session('success'))
        showNotification("{{ session('success') }}", 'success');
    @endif

    @if(session('error'))
        showNotification("{{ session('error') }}", 'error');
    @endif

    // Ẩn thông báo cũ nếu có
    const oldSuccessMessage = document.querySelector('.bg-green-500');
    if (oldSuccessMessage && !oldSuccessMessage.classList.contains('fixed')) {
        oldSuccessMessage.remove();
    }
});
</script>
@endsection