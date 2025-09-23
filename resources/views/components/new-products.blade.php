{{-- Carousel Container --}}
<div class="relative">
    {{-- Navigation Buttons --}}
    <button id="prevBtn"
        class="absolute left-0 top-1/2 transform -translate-y-1/2 z-10 bg-white rounded-full shadow-lg p-2 hover:bg-gray-50 transition-colors">
        <i class="fas fa-chevron-left text-gray-600"></i>
    </button>
    <button id="nextBtn"
        class="absolute right-0 top-1/2 transform -translate-y-1/2 z-10 bg-white rounded-full shadow-lg p-2 hover:bg-gray-50 transition-colors">
        <i class="fas fa-chevron-right text-gray-600"></i>
    </button>

    {{-- Carousel Track --}}
    <div class="overflow-hidden">
        <div id="carouselTrack" class="flex transition-transform duration-300 ease-in-out gap-4">
            @foreach($newProducts as $product)
                <div class="flex-shrink-0 w-64">
                    <div class="product-card bg-white rounded-xl shadow-md overflow-hidden flex flex-col h-full">
                        <a href="{{ route('product.show', $product->id) }}">
                            <div class="h-48 w-full relative">
                                <img src="{{ $product->image }}" alt="{{ $product->name }}"
                                    class="w-full h-full object-cover transition-transform duration-300 hover:scale-105">
                                @if($product->discount > 0)
                                    <span
                                        class="absolute top-3 left-3 bg-red-500 text-white text-xs font-bold px-2 py-1 rounded">
                                        -{{ $product->discount }}%
                                    </span>
                                @endif
                            </div>
                            <div class="p-4 flex-1 flex flex-col justify-between">
                                <h3 class="text-lg font-bold text-gray-800 mb-2 line-clamp-1">{{ $product->name }}</h3>
                                <div class="flex items-center mb-2">
                                    <div class="flex text-yellow-400">
                                        @for($i = 1; $i <= 5; $i++)
                                            @if($i <= round($product->reviews_avg_rating ?? 0))
                                                <i class="fas fa-star"></i>
                                            @else
                                                <i class="far fa-star"></i>
                                            @endif
                                        @endfor
                                    </div>
                                    <span class="text-gray-500 text-sm ml-2">({{ $product->reviews_count ?? 0 }})</span>

                                </div>
                                <div class="flex items-center justify-between mt-2">
                                    <p class="text-purple-600 font-semibold">
                                        {{ number_format($product->price - ($product->price * ($product->discount ?? 0) / 100), 0, ',', '.') }}₫
                                    </p>
                                    @if(($product->discount ?? 0) > 0)
                                        <p class="text-gray-400 text-sm line-through">
                                            {{ number_format($product->price, 0, ',', '.') }}₫
                                        </p>
                                    @endif
                                </div>
                                <button
                                    class="mt-4 bg-purple-600 hover:bg-purple-700 text-white py-2 px-4 rounded-lg transition-all w-full">
                                    <i class="fas fa-shopping-cart mr-2"></i> Mua ngay
                                </button>
                            </div>
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    {{-- Dots Indicator --}}
    <div class="flex justify-center mt-4 space-x-2">
        @for($i = 0; $i < ceil($newProducts->count() / 4); $i++)
            <button
                class="carousel-dot w-2 h-2 rounded-full bg-gray-300 hover:bg-purple-600 transition-colors {{ $i === 0 ? 'bg-purple-600' : '' }}"
                data-slide="{{ $i }}"></button>
        @endfor
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const track = document.getElementById('carouselTrack');
        const prevBtn = document.getElementById('prevBtn');
        const nextBtn = document.getElementById('nextBtn');
        const dots = document.querySelectorAll('.carousel-dot');

        let currentSlide = 0;
        const itemsPerSlide = 4;
        const totalSlides = Math.ceil({{ $newProducts->count() }} / itemsPerSlide);

        function updateCarousel() {
            const translateX = -currentSlide * (256 + 16) * itemsPerSlide; // 256px width + 16px gap
            track.style.transform = `translateX(${translateX}px)`;

            // Update dots
            dots.forEach((dot, index) => {
                dot.classList.toggle('bg-purple-600', index === currentSlide);
                dot.classList.toggle('bg-gray-300', index !== currentSlide);
            });
        }

        prevBtn.addEventListener('click', () => {
            currentSlide = currentSlide > 0 ? currentSlide - 1 : totalSlides - 1;
            updateCarousel();
        });

        nextBtn.addEventListener('click', () => {
            currentSlide = currentSlide < totalSlides - 1 ? currentSlide + 1 : 0;
            updateCarousel();
        });

        // Dot navigation
        dots.forEach((dot, index) => {
            dot.addEventListener('click', () => {
                currentSlide = index;
                updateCarousel();
            });
        });

        // Auto-play (optional)
        setInterval(() => {
            currentSlide = currentSlide < totalSlides - 1 ? currentSlide + 1 : 0;
            updateCarousel();
        }, 5000);
    });
</script>