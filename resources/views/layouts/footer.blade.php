<footer class="bg-gray-50 text-gray-800 border-t border-gray-200 mt-8 sm:mt-12 w-full overflow-x-hidden">
    <div class="max-w-7xl mx-auto px-3 sm:px-4 md:px-6 lg:px-8 py-6 sm:py-8 md:py-12">
        <div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-4 gap-4 sm:gap-6 md:gap-8">

            {{-- Logo + Mô tả --}}
            <div class="col-span-2 sm:col-span-2 md:col-span-1">
                <a href="{{ route('home') }}" class="flex items-center space-x-2 mb-3 sm:mb-4 group">
                    <div class="w-8 h-8 sm:w-10 sm:h-10 bg-gradient-to-r from-purple-600 to-blue-500 rounded-lg flex items-center justify-center text-white transition-transform group-hover:scale-110">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 sm:h-6 sm:w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                            d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                    </div>
                    <span class="text-lg sm:text-xl font-bold bg-gradient-to-r from-purple-600 to-blue-500 bg-clip-text text-transparent">
                        SOP
                    </span>
                </a>
                <p class="text-gray-600 text-xs sm:text-sm leading-relaxed">ShopLaravel – Nơi mua sắm trực tuyến tiện lợi, nhanh chóng và hiện đại.</p>
            </div>

            {{-- Danh mục --}}
            <div class="col-span-1 sm:col-span-1">
                <h3 class="text-sm sm:text-base md:text-lg font-semibold mb-2 sm:mb-3">Danh mục</h3>
                <ul class="space-y-1.5 sm:space-y-2">
                    @foreach($categories->take(5) as $category)
                        <li>
                            <a href="{{ route('products.index', ['category' => $category->id]) }}" 
                               class="text-xs sm:text-sm hover:text-purple-600 transition-colors block">{{ $category->name }}</a>
                        </li>
                    @endforeach
                    @if($categories->count() > 5)
                        <li>
                            <a href="{{ route('products.index') }}" 
                               class="text-xs sm:text-sm text-purple-600 hover:text-purple-700 font-medium transition-colors">
                                Xem thêm...
                            </a>
                        </li>
                    @endif
                </ul>
            </div>

            {{-- Liên kết --}}
            <div class="col-span-1 sm:col-span-1">
                <h3 class="text-sm sm:text-base md:text-lg font-semibold mb-2 sm:mb-3">Liên kết</h3>
                <ul class="space-y-1.5 sm:space-y-2">
                    <li><a href="{{ route('home') }}" class="text-xs sm:text-sm hover:text-purple-600 transition-colors block">Trang chủ</a></li>
                    <li><a href="{{ route('blog') }}" class="text-xs sm:text-sm hover:text-purple-600 transition-colors block">Blog</a></li>
                    <li><a href="{{ route('contact') }}" class="text-xs sm:text-sm hover:text-purple-600 transition-colors block">Liên hệ</a></li>
                    <li><a href="{{ route('cart.index') }}" class="text-xs sm:text-sm hover:text-purple-600 transition-colors block">Giỏ hàng</a></li>
                </ul>
            </div>

            {{-- Kết nối mạng xã hội --}}
            <div class="col-span-2 sm:col-span-2 md:col-span-1">
                <h3 class="text-sm sm:text-base md:text-lg font-semibold mb-2 sm:mb-3">Theo dõi chúng tôi</h3>
                <div class="flex space-x-3 sm:space-x-4">
                    <a href="#" class="text-gray-500 hover:text-blue-500 transition-colors" aria-label="Facebook">
                        <i class="fab fa-facebook text-lg sm:text-xl"></i>
                    </a>
                    <a href="#" class="text-gray-500 hover:text-pink-500 transition-colors" aria-label="Instagram">
                        <i class="fab fa-instagram text-lg sm:text-xl"></i>
                    </a>
                    <a href="#" class="text-gray-500 hover:text-blue-700 transition-colors" aria-label="Twitter">
                        <i class="fab fa-twitter text-lg sm:text-xl"></i>
                    </a>
                    <a href="#" class="text-gray-500 hover:text-red-500 transition-colors" aria-label="YouTube">
                        <i class="fab fa-youtube text-lg sm:text-xl"></i>
                    </a>
                </div>
                <div class="mt-4 sm:mt-6">
                    <h4 class="text-xs sm:text-sm font-semibold mb-2 text-gray-700">Đăng ký nhận tin</h4>
                    <form class="flex flex-col sm:flex-row gap-2">
                        <input type="email" placeholder="Email của bạn" 
                               class="flex-1 px-3 py-2 text-xs sm:text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                        <button type="submit" 
                                class="px-4 py-2 bg-gradient-to-r from-purple-600 to-blue-500 text-white text-xs sm:text-sm font-semibold rounded-lg hover:from-purple-700 hover:to-blue-600 transition-all whitespace-nowrap">
                            Đăng ký
                        </button>
                    </form>
                </div>
            </div>

        </div>

        {{-- Copyright --}}
        <div class="text-center py-3 sm:py-4 border-t border-gray-200 mt-6 sm:mt-8 text-xs sm:text-sm text-gray-500">
            <p>© {{ date('Y') }} ShopLaravel. Bảo lưu mọi quyền.</p>
            <div class="flex flex-wrap justify-center gap-2 sm:gap-4 mt-2 text-xs">
                <a href="#" class="hover:text-purple-600 transition-colors">Chính sách bảo mật</a>
                <span class="text-gray-300">|</span>
                <a href="#" class="hover:text-purple-600 transition-colors">Điều khoản sử dụng</a>
                <span class="text-gray-300">|</span>
                <a href="#" class="hover:text-purple-600 transition-colors">Chính sách đổi trả</a>
            </div>
        </div>
    </div>
</footer>
