{{-- Categories Section với Icons và Images --}}
<section class="py-8 sm:py-12 md:py-16 bg-white">
    <div class="max-w-7xl mx-auto px-3 sm:px-4 md:px-6 lg:px-8">
        <div class="text-center mb-6 sm:mb-8">
            <h2 class="text-2xl sm:text-3xl md:text-4xl font-bold text-gray-900 mb-2 sm:mb-3">
                Khám phá theo danh mục
            </h2>
            <p class="text-sm sm:text-base md:text-lg text-gray-600 max-w-2xl mx-auto">
                Tìm kiếm sản phẩm yêu thích của bạn theo từng danh mục
            </p>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-3 sm:gap-4 md:gap-6">
            @foreach($categories->take(12) as $category)
                <a href="{{ route('products.index', ['category' => $category->id]) }}" 
                   class="category-card group bg-gradient-to-br from-white to-gray-50 rounded-xl sm:rounded-2xl p-4 sm:p-6 text-center hover:shadow-xl transition-all duration-300 border border-gray-100 hover:border-purple-300">
                    <div class="w-12 h-12 sm:w-16 sm:h-16 md:w-20 md:h-20 mx-auto mb-3 sm:mb-4 bg-gradient-to-br from-purple-100 to-blue-100 rounded-full flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                        <i class="fas fa-mobile-alt text-purple-600 text-lg sm:text-xl md:text-2xl"></i>
                    </div>
                    <h3 class="text-xs sm:text-sm md:text-base font-semibold text-gray-900 group-hover:text-purple-600 transition-colors line-clamp-2">
                        {{ $category->name }}
                    </h3>
                    <p class="text-[10px] sm:text-xs text-gray-500 mt-1 hidden sm:block">
                        {{ $category->products_count ?? 0 }} sản phẩm
                    </p>
                </a>
            @endforeach
        </div>

        <div class="text-center mt-6 sm:mt-8">
            <a href="{{ route('products.index') }}" 
               class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-purple-600 to-blue-500 text-white font-semibold rounded-xl hover:from-purple-700 hover:to-blue-600 transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-xl">
                Xem tất cả danh mục
                <i class="fas fa-arrow-right ml-2"></i>
            </a>
        </div>
    </div>
</section>

