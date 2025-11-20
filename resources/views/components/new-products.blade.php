{{-- New Products Carousel with Brand --}}
<div class="relative group">
    {{-- Navigation Buttons --}}
    <button id="prevBtn"
        class="absolute left-0 top-1/2 transform -translate-y-1/2 z-10 bg-white/90 backdrop-blur-sm rounded-full shadow-pro p-3 hover:bg-white hover:scale-110 transition-all duration-300 opacity-0 group-hover:opacity-100 -translate-x-2 group-hover:translate-x-0">
        <i class="fas fa-chevron-left text-purple-600 text-sm"></i>
    </button>
    <button id="nextBtn"
        class="absolute right-0 top-1/2 transform -translate-y-1/2 z-10 bg-white/90 backdrop-blur-sm rounded-full shadow-pro p-3 hover:bg-white hover:scale-110 transition-all duration-300 opacity-0 group-hover:opacity-100 translate-x-2 group-hover:translate-x-0">
        <i class="fas fa-chevron-right text-purple-600 text-sm"></i>
    </button>

    {{-- Carousel Track --}}
    <div class="overflow-hidden py-4">
        <div id="carouselTrack" class="flex transition-transform duration-500 ease-out gap-6">
            @foreach($newProducts as $newProduct)
                @php
                    $hasDiscount = ($newProduct->discount ?? 0) > 0;
                    $finalPrice = $hasDiscount ? 
                        $newProduct->price - ($newProduct->price * $newProduct->discount / 100) : 
                        $newProduct->price;
                    $rating = $newProduct->reviews_avg_rating ?? 0;
                    $reviewCount = $newProduct->reviews_count ?? 0;
                @endphp
                
                <div class="flex-shrink-0 w-72">
                    <div class="product-card bg-white rounded-2xl shadow-pro overflow-hidden flex flex-col h-full hover:shadow-pro-lg border border-gray-100">
                        <a href="{{ route('product.show', $newProduct->id) }}" class="block relative">
                            <div class="relative w-full aspect-[4/3] bg-gray-100 overflow-hidden">
                                <img src="{{ $newProduct->image }}" alt="{{ $newProduct->name }}"
                                    class="w-full h-full object-cover transition-transform duration-500 hover:scale-110">
                                
                                {{-- Discount Badge --}}
                                @if($hasDiscount)
                                    <span class="absolute top-3 left-3 bg-gradient-to-r from-red-500 to-pink-600 text-white text-xs font-bold px-3 py-1.5 rounded-full shadow-lg">
                                        -{{ $newProduct->discount }}%
                                    </span>
                                @endif

                                {{-- New Badge --}}
                                <span class="absolute top-3 right-3 bg-gradient-to-r from-blue-500 to-cyan-500 text-white text-xs font-bold px-3 py-1.5 rounded-full shadow-lg">
                                    MỚI
                                </span>

                                {{-- Quick Actions Overlay --}}
                                <div class="absolute inset-0 bg-black/0 hover:bg-black/10 transition-all duration-300 flex items-end justify-end p-4 opacity-0 hover:opacity-100">
                                    <div class="flex space-x-2 transform translate-y-4 hover:translate-y-0 transition-transform duration-300">
                                        <button onclick="toggleWishlist({{ $newProduct->id }}, this)" 
                                                class="wishlist-btn bg-white/90 hover:bg-white text-gray-800 p-3 rounded-full shadow-lg transition-all duration-300 transform hover:scale-110 {{ auth()->check() && $newProduct->isInWishlist() ? 'text-red-500' : '' }}">
                                            <i class="fas fa-heart text-sm"></i>
                                        </button>
                                        <a href="{{ route('product.show', $newProduct->id) }}"
                                        class="bg-white/90 hover:bg-white text-gray-800 p-3 rounded-full shadow-lg transition-all duration-300 transform hover:scale-110 inline-block">
                                            <i class="fas fa-eye text-sm"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </a>
                        
                        <div class="p-5 flex-1 flex flex-col">
                            {{-- Brand Info --}}
                            @if($newProduct->brand)
                                <div class="mb-3">
                                    <span class="inline-flex items-center px-3 py-1.5 bg-gray-100 text-gray-700 rounded-full text-xs font-medium border border-gray-200">
                                        @if($newProduct->brand->logo)
                                            <img src="{{ $newProduct->brand->logo }}" alt="{{ $newProduct->brand->name }}" class="w-4 h-4 rounded-full mr-2">
                                        @else
                                            <i class="fas fa-tag text-purple-500 text-xs mr-1.5"></i>
                                        @endif
                                        {{ $newProduct->brand->name }}
                                    </span>
                                </div>
                            @endif

                            {{-- Product Info --}}
                            <div class="flex-1">
                                <a href="{{ route('product.show', $newProduct->id) }}" class="block group">
                                    <h3 class="text-lg font-bold text-gray-900 mb-2 line-clamp-2 group-hover:text-purple-600 transition-colors duration-300 leading-tight">
                                        {{ $newProduct->name }}
                                    </h3>
                                </a>

                                {{-- Rating --}}
                                <div class="flex items-center mb-3">
                                    <div class="flex text-amber-400 text-sm">
                                        @for($i = 1; $i <= 5; $i++)
                                            @if($i <= floor($rating))
                                                <i class="fas fa-star"></i>
                                            @elseif($i - 0.5 <= $rating)
                                                <i class="fas fa-star-half-alt"></i>
                                            @else
                                                <i class="far fa-star"></i>
                                            @endif
                                        @endfor
                                    </div>
                                    <span class="text-gray-500 text-sm ml-2">({{ $reviewCount }})</span>
                                </div>

                                {{-- Price --}}
                                <div class="flex items-center justify-between mb-4">
                                    <div class="flex items-center space-x-2">
                                        <p class="text-2xl font-bold text-purple-600">
                                            {{ number_format($finalPrice, 0, ',', '.') }}₫
                                        </p>
                                        @if($hasDiscount)
                                            <p class="text-gray-400 text-sm line-through">
                                                {{ number_format($newProduct->price, 0, ',', '.') }}₫
                                            </p>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            {{-- Stock Status --}}
                            <div class="mb-3">
                                @if($newProduct->stock > 0)
                                    <span class="inline-flex items-center text-sm text-green-600 bg-green-50 px-3 py-1 rounded-full">
                                        <i class="fas fa-check-circle mr-1.5 text-green-500"></i>
                                        Còn {{ $newProduct->stock }} sản phẩm
                                    </span>
                                @else
                                    <span class="inline-flex items-center text-sm text-red-600 bg-red-50 px-3 py-1 rounded-full">
                                        <i class="fas fa-times-circle mr-1.5 text-red-500"></i>
                                        Tạm hết hàng
                                    </span>
                                @endif
                            </div>

                            {{-- Action Button --}}
                            <div class="mt-auto">
                                @if($newProduct->stock > 0)
                                    <form action="{{ route('cart.add', $newProduct->id) }}" method="POST" class="w-full">
                                        @csrf
                                        <input type="hidden" name="quantity" value="1">
                                        <button type="submit" 
                                                class="w-full bg-gradient-to-r from-purple-600 to-blue-500 hover:from-purple-700 hover:to-blue-600 text-white py-3 px-4 rounded-xl transition-all duration-300 transform hover:scale-105 shadow-lg shadow-purple-500/25 font-semibold flex items-center justify-center group/btn">
                                            <i class="fas fa-shopping-cart mr-2 transition-transform group-hover/btn:scale-110"></i> 
                                            Thêm vào giỏ
                                        </button>
                                    </form>
                                @else
                                    <button class="w-full bg-gray-400 text-white py-3 px-4 rounded-xl font-semibold flex items-center justify-center cursor-not-allowed" disabled>
                                        <i class="fas fa-ban mr-2"></i> 
                                        Hết hàng
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    {{-- Dots Indicator --}}
    <div class="flex justify-center mt-6 space-x-2">
        @for($i = 0; $i < ceil($newProducts->count() / 4); $i++)
            <button
                class="carousel-dot w-3 h-3 rounded-full bg-gray-300 hover:bg-purple-400 transition-all duration-300 {{ $i === 0 ? 'bg-gradient-to-r from-purple-600 to-blue-500 w-8' : '' }}"
                data-slide="{{ $i }}"></button>
        @endfor
    </div>
</div><script>
    document.addEventListener('DOMContentLoaded', function () {
        const track = document.getElementById('carouselTrack');
        const prevBtn = document.getElementById('prevBtn');
        const nextBtn = document.getElementById('nextBtn');
        const dots = document.querySelectorAll('.carousel-dot');

        let currentSlide = 0;
        const itemsPerSlide = 4;
        const totalSlides = Math.ceil({{ $newProducts->count() }} / itemsPerSlide);
        let autoPlayInterval;

        function updateCarousel() {
            const slideWidth = 288; // 288px width (w-72)
            const gap = 24; // gap-6 = 24px
            const translateX = -currentSlide * (slideWidth + gap) * itemsPerSlide;
            track.style.transform = `translateX(${translateX}px)`;

            // Update dots
            dots.forEach((dot, index) => {
                const isActive = index === currentSlide;
                dot.classList.toggle('bg-gradient-to-r', isActive);
                dot.classList.toggle('from-purple-600', isActive);
                dot.classList.toggle('to-blue-500', isActive);
                dot.classList.toggle('bg-gray-300', !isActive);
                dot.classList.toggle('w-8', isActive);
                dot.classList.toggle('w-3', !isActive);
            });
        }

        function nextSlide() {
            currentSlide = currentSlide < totalSlides - 1 ? currentSlide + 1 : 0;
            updateCarousel();
        }

        function prevSlide() {
            currentSlide = currentSlide > 0 ? currentSlide - 1 : totalSlides - 1;
            updateCarousel();
        }

        prevBtn.addEventListener('click', () => {
            prevSlide();
            resetAutoPlay();
        });

        nextBtn.addEventListener('click', () => {
            nextSlide();
            resetAutoPlay();
        });

        // Dot navigation
        dots.forEach((dot, index) => {
            dot.addEventListener('click', () => {
                currentSlide = index;
                updateCarousel();
                resetAutoPlay();
            });
        });

        // Auto-play
        function startAutoPlay() {
            autoPlayInterval = setInterval(nextSlide, 5000);
        }

        function resetAutoPlay() {
            clearInterval(autoPlayInterval);
            startAutoPlay();
        }

        // Pause auto-play on hover
        const carouselContainer = document.querySelector('.relative.group');
        carouselContainer.addEventListener('mouseenter', () => {
            clearInterval(autoPlayInterval);
        });

        carouselContainer.addEventListener('mouseleave', () => {
            startAutoPlay();
        });

        // Initialize
        updateCarousel();
        startAutoPlay();

        // Keyboard navigation
        document.addEventListener('keydown', (e) => {
            if (e.key === 'ArrowLeft') {
                prevSlide();
                resetAutoPlay();
            } else if (e.key === 'ArrowRight') {
                nextSlide();
                resetAutoPlay();
            }
        });

        // Add to cart functionality
        const addToCartForms = document.querySelectorAll('form[action*="cart.add"]');
        addToCartForms.forEach(form => {
            form.addEventListener('submit', function(e) {
                const button = this.querySelector('button[type="submit"]');
                const originalHTML = button.innerHTML;
                
                // Show loading state
                button.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Đang thêm...';
                button.disabled = true;
                
                // Form will submit normally, the loading state is just for UX
                setTimeout(() => {
                    button.innerHTML = originalHTML;
                    button.disabled = false;
                }, 2000);
            });
        });
    });
</script>

