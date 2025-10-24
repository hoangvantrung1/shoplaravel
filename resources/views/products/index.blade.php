@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 pt-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        
        {{-- Header Section --}}
        <div class="mb-8 opacity-0 animate-fade-in">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <div class="w-1 h-12 bg-gradient-to-b from-purple-500 to-blue-500 rounded-full"></div>
                    <div>
                        <h1 class="text-4xl font-bold text-gray-900">
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
                        <p class="text-gray-600 mt-2 flex items-center space-x-2">
                            <i class="fas fa-tag text-purple-500"></i>
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

        {{-- Compact Filter Section --}}
        <div class="mb-8 opacity-0 animate-slide-up" style="animation-delay: 0.2s;">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <form action="{{ route('products.index') }}" method="GET" class="space-y-4">
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

        {{-- Products Grid --}}
        @if($products->count() > 0)
            <section class="opacity-0 animate-fade-in" style="animation-delay: 0.4s;">
                {{-- Products --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 md:gap-8">
                    @foreach($products as $index => $product)
                        @php
                            $hasSale = isset($product->sale_price) && (float) $product->sale_price > 0 && (float) $product->sale_price < (float) $product->price;
                            $finalPrice = $hasSale ? (float) $product->sale_price : (float) $product->price;
                            $discount = $hasSale && (float) $product->price > 0 ? max(0, min(99, round((1 - $product->sale_price / $product->price) * 100))) : null;
                            $isHot = isset($product->is_hot) ? (bool)$product->is_hot : (isset($product->hot) ? (bool)$product->hot : false);
                            $isNew = $product->created_at->gt(now()->subDays(7));
                        @endphp
                        
                        <div class="group bg-white rounded-2xl shadow-sm hover:shadow-2xl transition-all duration-500 overflow-hidden opacity-0 animate-product-card"
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
                                        <div class="absolute top-3 left-3 flex flex-col space-y-2">
                                            @if($hasSale)
                                                <span class="bg-red-500 text-white text-xs font-bold px-2 py-1 rounded-full shadow-lg">
                                                    -{{ $discount }}%
                                                </span>
                                            @endif
                                            @if($isHot)
                                                <span class="bg-orange-500 text-white text-xs font-bold px-2 py-1 rounded-full shadow-lg">
                                                    HOT
                                                </span>
                                            @endif
                                            @if($isNew)
                                                <span class="bg-green-500 text-white text-xs font-bold px-2 py-1 rounded-full shadow-lg">
                                                    MỚI
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </a>

                                {{-- Quick Actions --}}
                                <div class="absolute top-3 right-3 opacity-0 group-hover:opacity-100 transition-all duration-500 transform translate-x-4 group-hover:translate-x-0">
                                    <button class="bg-white/90 hover:bg-white text-gray-800 p-2 rounded-full shadow-lg transition-all duration-300 transform hover:scale-110 mb-2">
                                        <i class="fas fa-heart text-sm"></i>
                                    </button>
                                    <button class="bg-white/90 hover:bg-white text-gray-800 p-2 rounded-full shadow-lg transition-all duration-300 transform hover:scale-110">
                                        <i class="fas fa-eye text-sm"></i>
                                    </button>
                                </div>
                            </div>

                            {{-- Product Info --}}
                            <div class="p-4 md:p-6">
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
                                    <h3 class="text-gray-900 font-semibold mb-2 line-clamp-2 group-hover:text-purple-600 transition-colors duration-300">
                                        {{ $product->name }}
                                    </h3>
                                </a>

                                {{-- Description --}}
                                <p class="text-gray-500 text-sm mb-4 line-clamp-2 leading-relaxed">
                                    {{ $product->description }}
                                </p>

                                {{-- Price & Actions --}}
                                <div class="flex items-center justify-between">
                                    <div class="flex flex-col">
                                        <div class="flex items-center space-x-2">
                                            <span class="text-xl font-bold text-purple-600">
                                                {{ number_format($finalPrice, 0, ',', '.') }}₫
                                            </span>
                                            @if($hasSale)
                                                <span class="text-gray-400 line-through text-sm">
                                                    {{ number_format($product->price, 0, ',', '.') }}₫
                                                </span>
                                            @endif
                                        </div>
                                        @if($product->stock > 0)
                                            <span class="text-green-600 text-xs font-medium mt-1">
                                                <i class="fas fa-check-circle mr-1"></i>
                                                Còn hàng
                                            </span>
                                        @else
                                            <span class="text-red-600 text-xs font-medium mt-1">
                                                <i class="fas fa-times-circle mr-1"></i>
                                                Hết hàng
                                            </span>
                                        @endif
                                    </div>

                                    {{-- Add to Cart Button (FIXED) --}}
                                    @if($product->stock > 0)
                                        <form action="{{ route('cart.add', $product->id) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" 
                                                    class="bg-gradient-to-r from-purple-500 to-blue-500 hover:from-purple-600 hover:to-blue-600 text-white p-3 rounded-xl transition-all duration-300 transform hover:scale-110 shadow-lg hover:shadow-xl">
                                                <i class="fas fa-shopping-cart"></i>
                                            </button>
                                        </form>
                                    @else
                                        <button class="bg-gray-400 text-white p-3 rounded-xl cursor-not-allowed" disabled>
                                            <i class="fas fa-ban"></i>
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
    function showNotification(message, type = 'success') {
        const notification = document.createElement('div');
        notification.className = `fixed top-20 right-4 z-50 px-6 py-3 rounded-lg shadow-lg transition-all duration-300 transform translate-x-full ${
            type === 'success' ? 'bg-purple-500 text-white' : 'bg-red-500 text-white'
        }`;
        notification.innerHTML = `
            <div class="flex items-center">
                <i class="fas ${type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'} mr-2"></i>
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

    // Kiểm tra và hiển thị thông báo từ session
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