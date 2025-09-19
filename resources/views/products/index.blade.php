@extends('layouts.app')
<link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css">

@section('content')
    @php($search = request('q'))
    {{-- Kết quả tìm kiếm --}}
    @if($search)
        <div class="flex items-center space-x-2 mb-5">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 10a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
            <h3 class="text-xl font-bold text-gray-800">Kết quả cho "{{ $search }}"</h3>
        </div>

        @if($products->count() > 0)
            <section class="mb-6">
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                    @foreach($products as $product)
                        <div class="bg-white rounded-lg shadow-md hover:shadow-xl overflow-hidden transition-shadow duration-300">
                            <a href="{{ route('product.show', $product->slug) }}">
                                <div class="w-full h-40 bg-gray-100 overflow-hidden">
                                    <img src="{{ $product->image }}" alt="{{ $product->name }}" class="w-full h-full object-cover transition-transform duration-300 hover:scale-105">
                                </div>
                                <div class="p-4">
                                    <h3 class="text-gray-800 font-semibold mb-1 line-clamp-1">{{ $product->name }}</h3>
                                    <p class="text-purple-600 font-bold mb-2">{{ number_format($product->price, 0, ',', '.') }}₫</p>
                                    <p class="text-gray-500 text-sm line-clamp-2">{{ $product->description }}</p>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
            </section>
            <div class="mt-6">
                {{ $products->links() }}
            </div>
        @else
            <div class="text-center py-10 text-gray-500">
                Không tìm thấy sản phẩm phù hợp với từ khóa.
            </div>
        @endif

    {{-- Lọc theo thương hiệu --}}
    @elseif(!empty($brandName))
        <div class="flex items-center space-x-2 mb-5">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
            <h3 class="text-xl font-bold text-gray-800">Thương hiệu: {{ $brandName }}</h3>
        </div>
        @if($products->count() > 0)
            <section class="mb-12">
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                    @foreach($products as $product)
                        @include('components.product-card', ['product' => $product])
                    @endforeach
                </div>
            </section>
            <div class="mt-6">
                {{ $products->links() }}
            </div>
        @else
            <div class="text-center py-10 text-gray-500">
                Không có sản phẩm nào của thương hiệu này.
            </div>
        @endif

    {{-- Lọc theo danh mục --}}
    @elseif($categoryName)
        <div class="flex items-center space-x-2 mb-5">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-700" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
            <h3 class="text-xl font-bold text-gray-800">{{ $categoryName }}</h3>
        </div>

        {{-- Hiển thị sản phẩm theo danh mục --}}
        @if($products->count() > 0)
            <section class="mb-12">
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                    @foreach($products as $product)
                        @include('components.product-card', ['product' => $product])
                    @endforeach
                </div>
            </section>
            <div class="mt-6">
                {{ $products->links() }}
            </div>
        @else
            <div class="text-center py-10 text-gray-500">
                Không có sản phẩm nào trong danh mục này.
            </div>
        @endif
    @else
        <h2 class="text-3xl font-bold mb-8 text-gray-800 text-center">
            Sản phẩm nổi bật
        </h2>
        @if($products->count() > 0)
            <section class="mb-12">
                <div class="relative max-w-7xl mx-auto">
                    <button
                        class="prevBtn absolute left-0 top-1/2 -translate-y-1/2 bg-white shadow-lg rounded-full p-3 z-10 hover:bg-gray-100 transition-colors">
                        <i class="fas fa-chevron-left text-gray-600"></i>
                    </button>
                    <div class="overflow-hidden">
                        <div class="flex transition-transform duration-700 ease-in-out">
                            @foreach($products->chunk(4) as $chunk)
                                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 min-w-full">
                                    @foreach($chunk as $product)
                                        <div class="bg-white rounded-lg shadow-md hover:shadow-xl overflow-hidden transition-shadow duration-300">
                                            <a href="{{ route('product.show', $product->slug) }}">
                                                <div class="w-full h-40 bg-gray-100 overflow-hidden">
                                                    <img src="{{ $product->image }}" alt="{{ $product->name }}"
                                                        class="w-full h-full object-cover transition-transform duration-300 hover:scale-105">
                                                </div>
                                                <div class="p-4">
                                                    <h3 class="text-gray-800 font-semibold mb-1 line-clamp-1">{{ $product->name }}</h3>
                                                    <p class="text-purple-600 font-bold mb-2">{{ number_format($product->price, 0, ',', '.') }}₫</p>
                                                    <p class="text-gray-500 text-sm line-clamp-2">{{ $product->description }}</p>
                                                </div>
                                            </a>
                                        </div>
                                    @endforeach
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <button
                        class="nextBtn absolute right-0 top-1/2 -translate-y-1/2 bg-white shadow-lg rounded-full p-3 z-10 hover:bg-gray-100 transition-colors">
                        <i class="fas fa-chevron-right text-gray-600"></i>
                    </button>
                </div>
            </section>
        @endif
    @endif

    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>

    <script>
        document.addEventListener("DOMContentLoaded", () => {
            document.querySelectorAll(".relative.max-w-7xl").forEach((sliderWrapper) => {
                const slider = sliderWrapper.querySelector(".flex");
                const slides = slider.children;
                const prevBtn = sliderWrapper.querySelector(".prevBtn");
                const nextBtn = sliderWrapper.querySelector(".nextBtn");

                let index = 0;

                function goToSlide(i) {
                    index = i;
                    slider.style.transform = `translateX(-${index * 100}%)`;
                }

                prevBtn.addEventListener("click", () => {
                    goToSlide(index > 0 ? index - 1 : slides.length - 1);
                });

                nextBtn.addEventListener("click", () => {
                    goToSlide(index < slides.length - 1 ? index + 1 : 0);
                });

                setInterval(() => {
                    goToSlide(index < slides.length - 1 ? index + 1 : 0);
                }, 5000);
            });
        });
    </script>
@endsection