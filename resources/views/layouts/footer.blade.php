<footer class="bg-gray-50 text-gray-800 border-t border-gray-200 mt-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 grid grid-cols-1 md:grid-cols-4 gap-8">

        {{-- Logo + Mô tả --}}
        <div>
            <a href="{{ route('home') }}" class="flex items-center space-x-2 mb-4 group">
                <div class="w-10 h-10 bg-gradient-to-r from-purple-600 to-blue-500 rounded-lg flex items-center justify-center text-white transition-transform group-hover:scale-110">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                        d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </div>
                <span class="text-xl font-bold bg-gradient-to-r from-purple-600 to-blue-500 bg-clip-text text-transparent">
                    SOP
                </span>
            </a>
            <p class="text-gray-600 text-sm">ShopLaravel – Nơi mua sắm trực tuyến tiện lợi, nhanh chóng và hiện đại.</p>
        </div>

        {{-- Danh mục --}}
        <div>
            <h3 class="text-lg font-semibold mb-3">Danh mục</h3>
            <ul class="space-y-2">
                @foreach($categories as $category)
                    <li>
                        <a href="{{ route('products.index', ['category' => $category->id]) }}" 
                           class="hover:text-purple-600 transition-colors">{{ $category->name }}</a>
                    </li>
                @endforeach
            </ul>
        </div>

        {{-- Liên kết --}}
        <div>
            <h3 class="text-lg font-semibold mb-3">Liên kết</h3>
            <ul class="space-y-2">
                <li><a href="{{ route('home') }}" class="hover:text-purple-600 transition-colors">Trang chủ</a></li>
                <li><a href="#" class="hover:text-purple-600 transition-colors">Blog</a></li>
                <li><a href="#" class="hover:text-purple-600 transition-colors">Liên hệ</a></li>
                <li><a href="{{ route('cart.index') }}" class="hover:text-purple-600 transition-colors">Giỏ hàng</a></li>
            </ul>
        </div>

        {{-- Kết nối mạng xã hội --}}
        <div>
            <h3 class="text-lg font-semibold mb-3">Theo dõi chúng tôi</h3>
            <div class="flex space-x-4">
                <a href="#" class="text-gray-500 hover:text-blue-500 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M24 4.557a9.83 9.83 0 01-2.828.775 ..."/>
                    </svg>
                </a>
                <a href="#" class="text-gray-500 hover:text-pink-500 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 2.163c-5.46 0-9.878 4.418 ..."/>
                    </svg>
                </a>
                <a href="#" class="text-gray-500 hover:text-blue-700 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M22.162 5.656c-.793.352-1.644.591-2.538.696 ..."/>
                    </svg>
                </a>
            </div>
        </div>

    </div>

    <div class="text-center py-4 border-t border-gray-200 text-sm text-gray-500">
        © {{ date('Y') }} ShopLaravel. Bảo lưu mọi quyền.
    </div>
</footer>
