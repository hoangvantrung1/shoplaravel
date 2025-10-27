{{-- resources/views/wishlist/index.blade.php --}}
@extends('layouts.client')

@section('title', 'Sản Phẩm Yêu Thích')

@section('content')
<div class="min-h-screen bg-gray-50 pt-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        
        {{-- Header --}}
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <div class="w-1 h-12 bg-gradient-to-b from-red-500 to-pink-500 rounded-full"></div>
                    <div>
                        <h1 class="text-4xl font-bold text-gray-900">Sản Phẩm Yêu Thích</h1>
                        <p class="text-gray-600 mt-2 flex items-center space-x-2">
                            <span>Danh sách sản phẩm bạn yêu thích</span>
                        </p>
                    </div>
                </div>
                <div class="text-right">
                    <p class="text-gray-600">Tổng số sản phẩm</p>
                    <p class="text-2xl font-bold text-red-500">{{ $wishlistItems->total() }}</p>
                </div>
            </div>
        </div>

        {{-- Wishlist Items --}}
        @if($wishlistItems->count() > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 md:gap-8">
                @foreach($wishlistItems as $product)
                    @php
                        $hasSale = isset($product->sale_price) && (float) $product->sale_price > 0 && (float) $product->sale_price < (float) $product->price;
                        $finalPrice = $hasSale ? (float) $product->sale_price : (float) $product->price;
                        $discount = $hasSale && (float) $product->price > 0 ? max(0, min(99, round((1 - $product->sale_price / $product->price) * 100))) : null;
                        $isHot = isset($product->is_hot) ? (bool)$product->is_hot : (isset($product->hot) ? (bool)$product->hot : false);
                        $isNew = $product->created_at->gt(now()->subDays(7));
                    @endphp
                    
                    <div class="group bg-white rounded-2xl shadow-sm hover:shadow-2xl transition-all duration-500 overflow-hidden flex flex-col h-full">
                        {{-- Product Image --}}
                        <div class="relative overflow-hidden flex-shrink-0">
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

                            {{-- Remove from Wishlist Button --}}
                            <div class="absolute top-3 right-3">
                                <button onclick="removeFromWishlist({{ $product->id }}, this)"
                                        class="bg-white/90 hover:bg-white text-red-500 p-2 rounded-full shadow-lg transition-all duration-300 transform hover:scale-110">
                                    <i class="fas fa-heart text-sm"></i>
                                </button>
                            </div>
                        </div>

                        {{-- Product Info --}}
                        <div class="p-4 md:p-6 flex flex-col flex-grow">
                            {{-- Brand --}}
                            @if($product->brand)
                                <div class="mb-2">
                                    <span class="inline-block bg-gray-100 text-gray-600 text-xs font-medium px-2 py-1 rounded">
                                        {{ $product->brand->name }}
                                    </span>
                                </div>
                            @endif

                            {{-- Title --}}
                            <a href="{{ route('product.show', $product->id) }}" class="block group flex-grow">
                                <h3 class="text-gray-900 font-semibold mb-2 line-clamp-2 group-hover:text-purple-600 transition-colors duration-300 leading-tight">
                                    {{ $product->name }}
                                </h3>
                            </a>

                            {{-- Rating --}}
                            @if($product->reviews_avg_rating)
                                <div class="flex items-center space-x-2 mb-3">
                                    <div class="flex text-amber-400 text-sm">
                                        @for($i = 1; $i <= 5; $i++)
                                            <i class="{{ $i <= floor($product->reviews_avg_rating) ? 'fas fa-star' : ($i - $product->reviews_avg_rating < 1 && $i - $product->reviews_avg_rating > 0 ? 'fas fa-star-half-alt' : 'far fa-star') }}"></i>
                                        @endfor
                                    </div>
                                    <span class="text-gray-500 text-sm">({{ $product->reviews_count }})</span>
                                </div>
                            @endif

                            {{-- Description --}}
                            <p class="text-gray-500 text-sm mb-4 line-clamp-2 leading-relaxed flex-grow">
                                {{ $product->description }}
                            </p>

                            {{-- Price & Actions --}}
                            <div class="flex items-center justify-between mt-auto">
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

                                {{-- Add to Cart Button --}}
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

            {{-- Pagination --}}
            <div class="mt-12 flex justify-center">
                {{ $wishlistItems->links() }}
            </div>
        @else
            {{-- Empty Wishlist --}}
            <div class="text-center py-16">
                <div class="max-w-md mx-auto">
                    <div class="w-24 h-24 mx-auto mb-6 bg-gradient-to-r from-red-100 to-pink-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-heart text-3xl text-red-400"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-3">Danh sách yêu thích trống</h3>
                    <p class="text-gray-600 mb-8 leading-relaxed">
                        Bạn chưa có sản phẩm nào trong danh sách yêu thích. 
                        Hãy khám phá các sản phẩm và thêm vào danh sách yêu thích của bạn!
                    </p>
                    <a href="{{ route('products.index') }}"
                       class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-purple-600 to-blue-500 text-white font-semibold rounded-xl hover:from-purple-700 hover:to-blue-600 transition-all duration-300 transform hover:scale-105">
                        <i class="fas fa-shopping-bag mr-2"></i>
                        Khám phá sản phẩm
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>

<style>
/* Đảm bảo các card có cùng chiều cao */
.grid > div {
    display: flex;
    flex-direction: column;
}

/* Fix chiều cao hình ảnh */
.aspect-\[4\/3\] {
    aspect-ratio: 4/3;
    min-height: 200px;
}

/* Đảm bảo nội dung căn đều */
.flex-grow {
    flex-grow: 1;
}

/* Fix khoảng cách giữa các card */
.grid {
    align-items: stretch;
}

/* Đảm bảo các phần tử trong card căn đều */
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.leading-tight {
    line-height: 1.25;
}
</style>

<script>
async function removeFromWishlist(productId, button) {
    try {
        const response = await fetch(`/wishlist/${productId}`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        });

        const data = await response.json();
        
        if (data.success) {
            // Remove the product card
            const productCard = button.closest('.grid > div');
            productCard.style.opacity = '0';
            productCard.style.transform = 'scale(0.8)';
            
            setTimeout(() => {
                productCard.remove();
                showNotification('Đã xóa khỏi danh sách yêu thích', 'info');
                
                // Update wishlist count
                if (typeof updateWishlistCount === 'function') {
                    updateWishlistCount(data.wishlist_count);
                }
                
                // If no items left, reload to show empty state
                const remainingItems = document.querySelectorAll('.grid > div').length;
                if (remainingItems === 0) {
                    setTimeout(() => {
                        window.location.reload();
                    }, 500);
                }
            }, 300);
        }
    } catch (error) {
        console.error('Error:', error);
        showNotification('Có lỗi xảy ra', 'error');
    }
}

function showNotification(message, type = 'success') {
    const notification = document.createElement('div');
    notification.className = `fixed top-20 right-4 z-50 px-6 py-3 rounded-lg shadow-lg transition-all duration-300 transform translate-x-full ${
        type === 'success' ? 'bg-purple-500 text-white' : 
        type === 'error' ? 'bg-red-500 text-white' : 
        'bg-blue-500 text-white'
    }`;
    notification.innerHTML = `
        <div class="flex items-center">
            <i class="fas ${type === 'success' ? 'fa-check-circle' : 
                          type === 'error' ? 'fa-exclamation-circle' : 
                          'fa-info-circle'} mr-2"></i>
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
</script>
@endsection