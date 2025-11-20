{{-- Featured Products Section --}}
<section class="py-8 sm:py-12 md:py-16 bg-gradient-to-b from-gray-50 to-white">
    <div class="max-w-7xl mx-auto px-3 sm:px-4 md:px-6 lg:px-8">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 sm:mb-8 gap-3 sm:gap-0">
            <div>
                <h2 class="text-2xl sm:text-3xl md:text-4xl font-bold text-gray-900 mb-2">
                    Sản phẩm nổi bật
                </h2>
                <p class="text-sm sm:text-base text-gray-600">
                    Những sản phẩm được yêu thích nhất
                </p>
            </div>
            <a href="{{ route('products.index') }}?sort_by=created_at" 
               class="text-purple-600 hover:text-purple-800 font-semibold flex items-center text-sm sm:text-base whitespace-nowrap">
                Xem tất cả <i class="fas fa-arrow-right ml-2"></i>
            </a>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3 sm:gap-4 md:gap-6">
            @foreach($featuredProducts->take(8) as $product)
                @php
                    $hasSale = isset($product->sale_price) && (float) $product->sale_price > 0 && (float) $product->sale_price < (float) $product->price;
                    $finalPrice = $hasSale ? (float) $product->sale_price : (float) $product->price;
                    $discount = $hasSale && (float) $product->price > 0 ? max(0, min(99, round((1 - $product->sale_price / $product->price) * 100))) : null;
                @endphp
                
                <div class="group bg-white rounded-lg sm:rounded-xl md:rounded-2xl shadow-sm hover:shadow-xl md:hover:shadow-2xl transition-all duration-500 overflow-hidden">
                    <a href="{{ route('product.show', $product->id) }}" class="block">
                        <div class="relative w-full aspect-[4/3] bg-gray-100 overflow-hidden">
                            <img src="{{ $product->image }}" alt="{{ $product->name }}"
                                 class="w-full h-full object-contain transition-transform duration-700 group-hover:scale-110"
                                 loading="lazy">
                            
                            {{-- Badges --}}
                            <div class="absolute top-1.5 left-1.5 sm:top-2 sm:left-2 md:top-3 md:left-3 flex flex-col space-y-1">
                                @if($hasSale)
                                    <span class="bg-red-500 text-white text-[10px] sm:text-xs font-bold px-1.5 sm:px-2 py-0.5 sm:py-1 rounded-full shadow-lg">
                                        -{{ $discount }}%
                                    </span>
                                @endif
                                @if($product->is_hot ?? false)
                                    <span class="bg-orange-500 text-white text-[10px] sm:text-xs font-bold px-1.5 sm:px-2 py-0.5 sm:py-1 rounded-full shadow-lg">
                                        HOT
                                    </span>
                                @endif
                            </div>
                        </div>
                    </a>

                    <div class="p-2.5 sm:p-3 md:p-4">
                        <a href="{{ route('product.show', $product->id) }}" class="block">
                            <h3 class="text-xs sm:text-sm md:text-base font-semibold text-gray-900 mb-1 sm:mb-2 line-clamp-2 group-hover:text-purple-600 transition-colors leading-tight">
                                {{ $product->name }}
                            </h3>
                        </a>

                        <div class="flex items-center justify-between mt-auto">
                            <div class="flex items-center gap-2 flex-wrap">
                                @if($hasSale)
                                    {{-- Có giảm giá: hiển thị giá sale và giá gốc cùng hàng --}}
                                    <span class="text-sm sm:text-base md:text-lg font-bold text-red-600">
                                        {{ number_format($finalPrice, 0, ',', '.') }}₫
                                    </span>
                                    <span class="text-[10px] sm:text-xs text-gray-400 line-through">
                                        {{ number_format($product->price, 0, ',', '.') }}₫
                                    </span>
                                @else
                                    {{-- Không có giảm giá: chỉ hiển thị giá gốc --}}
                                    <span class="text-sm sm:text-base md:text-lg font-bold text-purple-600">
                                        {{ number_format($finalPrice, 0, ',', '.') }}₫
                                    </span>
                                @endif
                            </div>
                        </div>

                        <form action="{{ route('cart.add', $product->id) }}" method="POST" class="mt-2 sm:mt-3">
                            @csrf
                            <button type="submit" 
                                    class="w-full bg-gradient-to-r from-purple-500 to-blue-500 hover:from-purple-600 hover:to-blue-600 text-white py-2 sm:py-2.5 rounded-lg sm:rounded-xl transition-all duration-300 transform hover:scale-105 text-xs sm:text-sm font-semibold flex items-center justify-center gap-1.5">
                                <i class="fas fa-shopping-cart text-xs sm:text-sm"></i>
                                <span class="hidden sm:inline">Thêm vào giỏ</span>
                                <span class="sm:hidden">Mua</span>
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

