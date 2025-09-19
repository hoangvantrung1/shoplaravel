<header id="site-header"
    class="fixed top-0 left-0 w-full z-50 bg-white/80 backdrop-blur-sm shadow-md transition-all duration-300">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">

            {{-- Logo --}}
            <a href="{{ route('home') }}" class="flex items-center space-x-2 group">
                <div
                    class="flex items-center justify-center w-10 h-10 bg-gradient-to-r from-purple-600 to-blue-500 rounded-lg group-hover:scale-110 transition-transform">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
                <span
                    class="text-2xl font-bold bg-gradient-to-r from-purple-600 to-blue-500 bg-clip-text text-transparent group-hover:scale-105 transition-transform">
                    SOP
                </span>
            </a>

            {{-- Desktop Menu --}}
            <nav class="hidden md:flex items-center space-x-6 font-medium">
                <a href="{{ route('home') }}" class="nav-link text-gray-700 hover:text-purple-600">Trang chủ</a>

                {{-- Dropdown danh mục --}}
                <div class="relative group">
                    <button class="nav-link flex items-center text-gray-700 hover:text-purple-600 transition-all">
                        Danh mục
                        <svg xmlns="http://www.w3.org/2000/svg"
                            class="h-4 w-4 ml-1 transition-transform group-hover:rotate-180" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>

                    <div
                        class="absolute left-0 mt-2 w-48 bg-white rounded-lg shadow-lg py-2 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 transform -translate-y-2 group-hover:translate-y-0 z-50 border border-gray-100">
                        @foreach($categories as $category)
                            <a href="{{ route('products.index', ['category' => $category->id]) }}"
                                class="block px-4 py-2 text-gray-700 hover:bg-gray-100 hover:text-purple-600 transition-colors">
                                {{ $category->name }}
                            </a>
                        @endforeach
                    </div>
                </div>

            {{-- Search form (desktop) --}}
            <form action="{{ route('products.index') }}" method="GET" class="relative">
                <input
                    type="text"
                    name="q"
                    value="{{ request('q') }}"
                    placeholder="Tìm sản phẩm..."
                    class="pl-10 pr-3 py-2 border rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 w-64"
                />
                <svg class="w-5 h-5 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 10a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </form>

            <a href="#" class="nav-link text-gray-700 hover:text-purple-600">Blog</a>
                <a href="#" class="nav-link text-gray-700 hover:text-purple-600">Liên hệ</a>
                <a href="{{ route('cart.index') }}" class="nav-link text-gray-700 hover:text-purple-600 relative">
                    Giỏ hàng ({{ count(session('cart', [])) }})
                </a>

                {{-- Login / Logout --}}
                @guest
                    <a href="{{ route('login') }}"
                        class="ml-4 px-4 py-2 rounded-lg bg-gradient-to-r from-purple-600 to-blue-500 text-white hover:from-purple-700 hover:to-blue-600 transition-transform transform hover:scale-105">
                        Đăng nhập
                    </a>
                @else
                    <div class="relative group">
                        <button class="flex items-center space-x-2">
                            <div
                                class="w-8 h-8 rounded-full bg-gradient-to-r from-purple-600 to-blue-500 flex items-center justify-center text-white font-semibold text-sm">
                                {{ substr(Auth::user()->name, 0, 1) }}
                            </div>
                            <span>{{ Auth::user()->name }}</span>
                        </button>

                        <div
                            class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg py-2 border border-gray-100 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 z-50">
                            <form action="{{ route('client.logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="block w-full text-left px-4 py-2 hover:bg-gray-100">Đăng
                                    xuất</button>
                            </form>
                        </div>
                    </div>
                @endguest
            </nav>

            {{-- Mobile Menu Button --}}
            <button id="menu-btn" class="md:hidden p-2 rounded-lg hover:bg-gray-100 transition-all">
                <svg class="w-6 h-6 text-gray-800" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>
        </div>
    </div>
</header>

{{-- Flash Messages: Di chuyển ra ngoài, ngay sau header --}}
@if(session('success') || session('error'))
    <div id="flash-message" class="fixed top-16 left-0 right-0 z-40 p-4 transform transition-all duration-500 ease-out"
        style="transform: translateY(-100%); opacity: 0;">
        <div class="flex justify-end">
            @if(session('success'))
                <div class="bg-fuchsia-500 rounded-lg shadow-lg px-6 py-3 text-white">
                    <p>{{ session('success') }}</p>
                </div>
            @endif
            @if(session('error'))
                <div class="bg-red-500 rounded-lg shadow-lg px-6 py-3 text-white">
                    <p>{{ session('error') }}</p>
                </div>
            @endif
        </div>
    </div>
@endif
{{-- Mobile menu --}}
<div id="mobile-menu"
    class="fixed inset-0 z-50 hidden bg-white/95 backdrop-blur-md overflow-y-auto transition-transform transform -translate-x-full">
    <div class="flex justify-between items-center p-4 border-b border-gray-200">
        <span class="font-bold text-lg">Menu</span>
        <button id="mobile-menu-close" class="text-gray-700 text-2xl">✕</button>
    </div>

    <nav class="flex flex-col p-4 space-y-2">
        <a href="{{ route('home') }}" class="block px-4 py-2 rounded hover:bg-gray-100 transition-colors">Trang chủ</a>
        {{-- Search form (mobile) --}}
        <form action="{{ route('products.index') }}" method="GET" class="px-4 py-2">
            <div class="relative">
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Tìm sản phẩm..."
                    class="w-full pl-10 pr-3 py-2 border rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500" />
                <svg class="w-5 h-5 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 10a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>
        </form>
        <a href="{{ route('cart.index') }}" class="block px-4 py-2 rounded hover:bg-gray-100 transition-colors">
            Giỏ hàng ({{ count(session('cart', [])) }})
        </a>
        @foreach($categories as $category)
            <a href="{{ route('products.index', ['category' => $category->id]) }}"
                class="block px-6 py-2 rounded hover:bg-gray-100 transition-colors">
                {{ $category->name }}
            </a>
        @endforeach
        <a href="#" class="block px-4 py-2 rounded hover:bg-gray-100 transition-colors">Blog</a>
        <a href="#" class="block px-4 py-2 rounded hover:bg-gray-100 transition-colors">Liên hệ</a>

        @guest
            <a href="{{ route('login') }}"
                class="block px-4 py-2 rounded bg-gradient-to-r from-purple-600 to-blue-500 text-white text-center">Đăng
                nhập</a>
        @else
            <form action="{{ route('client.logout') }}" method="POST">
                @csrf
                <button type="submit" class="block w-full px-4 py-2 rounded bg-red-500 text-white text-center mt-2">
                    {{ Auth::user()->name }} (Thoát)
                </button>
            </form>
        @endguest
    </nav>
</div>

{{-- Script --}}
@if(session('success') || session('error'))
    <div id="flash-message" class="fixed top-16 left-0 right-0 z-40 p-4 transform transition-all duration-500 ease-out"
        style="transform: translateY(-100%); opacity: 0;">
        <div class="flex justify-end">
            @if(session('success'))
                <div class="bg-fuchsia-500 rounded-lg shadow-lg px-6 py-3 text-white">
                    <p>{{ session('success') }}</p>
                </div>
            @endif
            @if(session('error'))
                <div class="bg-red-500 rounded-lg shadow-lg px-6 py-3 text-white">
                    <p>{{ session('error') }}</p>
                </div>
            @endif
        </div>
    </div>
@endif

<script>
    document.addEventListener("DOMContentLoaded", () => {
        const flashMessage = document.getElementById('flash-message');
        if (flashMessage) {
            // Hiển thị thông báo bằng cách dịch chuyển và làm mờ
            setTimeout(() => {
                flashMessage.style.transform = 'translateY(0)';
                flashMessage.style.opacity = '1';
            }, 10); // Thêm một độ trễ nhỏ để đảm bảo hiệu ứng chạy

            // Tự động ẩn thông báo sau 3 giây
            setTimeout(() => {
                flashMessage.style.transform = 'translateY(-100%)';
                flashMessage.style.opacity = '0';

                // Xóa hoàn toàn thẻ khỏi DOM sau khi hiệu ứng kết thúc
                setTimeout(() => {
                    flashMessage.remove();
                }, 500); // Tương ứng với duration-500
            }, 3000);
        }
    });
</script>