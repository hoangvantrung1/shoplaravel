{{-- Flash Sale / Deal of the Day Section --}}
<section class="py-8 sm:py-12 md:py-16 bg-gradient-to-br from-red-50 via-orange-50 to-yellow-50 relative overflow-hidden">
    {{-- Decorative elements --}}
    <div class="absolute top-0 right-0 w-64 h-64 bg-red-200/30 rounded-full blur-3xl -mr-32 -mt-32"></div>
    <div class="absolute bottom-0 left-0 w-64 h-64 bg-orange-200/30 rounded-full blur-3xl -ml-32 -mb-32"></div>
    
    <div class="max-w-7xl mx-auto px-3 sm:px-4 md:px-6 lg:px-8 relative z-10">
        <div class="bg-white rounded-2xl sm:rounded-3xl shadow-2xl p-4 sm:p-6 md:p-8 border-4 border-red-500">
            {{-- Header --}}
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between mb-6 sm:mb-8 gap-4">
                <div>
                    <div class="flex items-center gap-2 sm:gap-3 mb-2">
                        <span class="bg-red-500 text-white text-xs sm:text-sm font-bold px-3 sm:px-4 py-1 sm:py-1.5 rounded-full animate-pulse">
                            🔥 FLASH SALE
                        </span>
                        <span class="text-xs sm:text-sm text-gray-600">Chỉ hôm nay</span>
                    </div>
                    <h2 class="text-2xl sm:text-3xl md:text-4xl font-bold text-gray-900">
                        Deal hôm nay
                    </h2>
                    <p class="text-xs sm:text-sm md:text-base text-gray-600 mt-1">
                        Sản phẩm nổi bật với giá tốt nhất
                    </p>
                </div>
                
                {{-- Countdown Timer --}}
                <div id="countdown" class="flex items-center gap-2 sm:gap-3 bg-gradient-to-r from-red-500 to-orange-500 text-white px-4 sm:px-6 py-2 sm:py-3 rounded-xl shadow-lg">
                    <i class="fas fa-clock text-sm sm:text-base"></i>
                    <div class="flex items-center gap-1 sm:gap-2">
                        <div class="text-center">
                            <div id="hours" class="text-lg sm:text-xl md:text-2xl font-bold">00</div>
                            <div class="text-[8px] sm:text-xs">Giờ</div>
                        </div>
                        <span class="text-lg sm:text-xl">:</span>
                        <div class="text-center">
                            <div id="minutes" class="text-lg sm:text-xl md:text-2xl font-bold">00</div>
                            <div class="text-[8px] sm:text-xs">Phút</div>
                        </div>
                        <span class="text-lg sm:text-xl">:</span>
                        <div class="text-center">
                            <div id="seconds" class="text-lg sm:text-xl md:text-2xl font-bold">00</div>
                            <div class="text-[8px] sm:text-xs">Giây</div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Flash Sale Products --}}
            <div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-4 gap-3 sm:gap-4 md:gap-6">
                @php
                    // Lấy sản phẩm cho Deal
                    // Ưu tiên sản phẩm có sale_price (nếu có trong database)
                    $flashSaleProducts = collect();
                    
                    // Kiểm tra xem có sản phẩm với sale_price không
                    $productsWithSale = $newProducts->filter(function($product) {
                        if (!isset($product->sale_price) || $product->sale_price === null) {
                            return false;
                        }
                        $salePrice = (float) $product->sale_price;
                        $price = (float) $product->price;
                        return $salePrice > 0 && $salePrice < $price && $product->stock > 0;
                    });
                    
                    // Nếu có sản phẩm sale, lấy 4 sản phẩm đó
                    if ($productsWithSale->count() > 0) {
                        $flashSaleProducts = $productsWithSale->take(4);
                    } else {
                        // Nếu không có sản phẩm sale, lấy 4 sản phẩm mới nhất còn hàng
                        $flashSaleProducts = $newProducts->where('stock', '>', 0)->take(4);
                    }
                @endphp
                
                @if($flashSaleProducts->count() > 0)
                    @foreach($flashSaleProducts as $product)
                        @php
                            // Kiểm tra xem có sale_price hợp lệ không
                            $hasSale = false;
                            $discount = 0;
                            if (isset($product->sale_price) && $product->sale_price !== null) {
                                $salePrice = (float) $product->sale_price;
                                $price = (float) $product->price;
                                if ($salePrice > 0 && $salePrice < $price && $price > 0) {
                                    $hasSale = true;
                                    $discount = max(0, min(99, round((1 - $salePrice / $price) * 100)));
                                }
                            }
                            $finalPrice = $hasSale ? (float) $product->sale_price : (float) $product->price;
                        @endphp
                        
                        <div class="group bg-white rounded-lg sm:rounded-xl border-2 border-red-200 hover:border-red-400 transition-all duration-300 overflow-hidden">
                            <a href="{{ route('product.show', $product->id) }}" class="block">
                                <div class="relative w-full aspect-[4/3] bg-gray-100 overflow-hidden">
                                    <img src="{{ $product->image }}" alt="{{ $product->name }}"
                                         class="w-full h-full object-contain transition-transform duration-500 group-hover:scale-110">
                                    
                                    {{-- Chỉ hiển thị badge discount khi thực sự có giảm giá --}}
                                    @if($hasSale && $discount > 0)
                                        <div class="absolute top-1.5 left-1.5 sm:top-2 sm:left-2">
                                            <span class="bg-red-500 text-white text-[10px] sm:text-xs font-bold px-1.5 sm:px-2 py-0.5 sm:py-1 rounded-full shadow-lg animate-pulse">
                                                -{{ $discount }}%
                                            </span>
                                        </div>
                                    @endif
                                </div>
                            </a>

                            <div class="p-2 sm:p-3">
                                <a href="{{ route('product.show', $product->id) }}" class="block">
                                    <h3 class="text-xs sm:text-sm font-semibold text-gray-900 mb-1 sm:mb-2 line-clamp-2 group-hover:text-red-600 transition-colors leading-tight">
                                        {{ $product->name }}
                                    </h3>
                                </a>

                                <div class="flex items-center justify-between mb-2">
                                    <div class="flex items-center gap-2 flex-wrap">
                                        @if($hasSale)
                                            {{-- Có giảm giá: hiển thị giá sale và giá gốc cùng hàng --}}
                                            <span class="text-sm sm:text-base font-bold text-red-600">
                                                {{ number_format($finalPrice, 0, ',', '.') }}₫
                                            </span>
                                            <span class="text-[10px] sm:text-xs text-gray-400 line-through">
                                                {{ number_format($product->price, 0, ',', '.') }}₫
                                            </span>
                                        @else
                                            {{-- Không có giảm giá: chỉ hiển thị giá gốc --}}
                                            <span class="text-sm sm:text-base font-bold text-gray-900">
                                                {{ number_format($finalPrice, 0, ',', '.') }}₫
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <form action="{{ route('cart.add', $product->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" 
                                            class="w-full bg-gradient-to-r from-red-500 to-orange-500 hover:from-red-600 hover:to-orange-600 text-white py-1.5 sm:py-2 rounded-lg transition-all duration-300 transform hover:scale-105 text-xs sm:text-sm font-semibold">
                                        Mua ngay
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                @else
                    {{-- Fallback nếu không có sản phẩm sale --}}
                    <div class="col-span-2 md:col-span-4 text-center py-8">
                        <p class="text-gray-600">Chưa có deal hôm nay</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Countdown Timer
    function updateCountdown() {
        const now = new Date().getTime();
        const endOfDay = new Date();
        endOfDay.setHours(23, 59, 59, 999);
        const distance = endOfDay - now;

        const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        const seconds = Math.floor((distance % (1000 * 60)) / 1000);

        const hoursEl = document.getElementById('hours');
        const minutesEl = document.getElementById('minutes');
        const secondsEl = document.getElementById('seconds');

        if (hoursEl) hoursEl.textContent = String(hours).padStart(2, '0');
        if (minutesEl) minutesEl.textContent = String(minutes).padStart(2, '0');
        if (secondsEl) secondsEl.textContent = String(seconds).padStart(2, '0');
    }

    updateCountdown();
    setInterval(updateCountdown, 1000);
});
</script>

