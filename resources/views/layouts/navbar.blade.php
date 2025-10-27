<header id="site-header"
    class="fixed top-0 left-0 w-full z-50 bg-white/80 backdrop-blur-sm shadow-md transition-all duration-300 opacity-0 transform -translate-y-4 animate-header-fade-in">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">

            {{-- Logo với hiệu ứng --}}
            <a href="{{ route('home') }}" class="flex items-center space-x-2 group opacity-0 animate-logo-slide" style="animation-delay: 0.2s;">
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
                {{-- Trang chủ --}}
                <a href="{{ route('home') }}" class="nav-link text-gray-700 hover:text-purple-600 opacity-0 animate-nav-item" style="animation-delay: 0.3s;">Trang chủ</a>

                {{-- Dropdown danh mục --}}
                <div class="relative group opacity-0 animate-nav-item" style="animation-delay: 0.4s;">
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

                {{-- Dropdown thương hiệu --}}
                <div class="relative group opacity-0 animate-nav-item" style="animation-delay: 0.5s;">
                    <button class="nav-link flex items-center text-gray-700 hover:text-purple-600 transition-all">
                        Thương hiệu
                        <svg xmlns="http://www.w3.org/2000/svg"
                            class="h-4 w-4 ml-1 transition-transform group-hover:rotate-180" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>

                    <div
                        class="absolute left-0 mt-2 w-48 bg-white rounded-lg shadow-lg py-2 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 transform -translate-y-2 group-hover:translate-y-0 z-50 border border-gray-100">
                        @foreach($brands as $brand)
                            <a href="{{ route('products.index', ['brand_id' => $brand->id]) }}"
                                class="block px-4 py-2 text-gray-700 hover:bg-gray-100 hover:text-purple-600 transition-colors">
                                {{ $brand->name }}
                            </a>
                        @endforeach
                    </div>
                </div>

                {{-- Blog --}}
                <a href="{{ route('blog') }}"
                    class="nav-link text-gray-700 hover:text-purple-600 {{ request()->routeIs('blog*') ? 'text-purple-600 font-semibold' : '' }} opacity-0 animate-nav-item" style="animation-delay: 0.6s;">Blog</a>

                {{-- Liên hệ --}}
                <a href="{{ route('contact') }}"
                    class="nav-link text-gray-700 hover:text-purple-600 {{ request()->routeIs('contact') ? 'text-purple-600 font-semibold' : '' }} opacity-0 animate-nav-item" style="animation-delay: 0.7s;">Liên
                    hệ</a>

                {{-- Giỏ hàng --}}
                <a href="{{ route('cart.index') }}" class="relative inline-flex items-center text-gray-700 hover:text-purple-600 opacity-0 animate-nav-item" style="animation-delay: 0.8s;">
                    <i class="fas fa-shopping-cart text-lg"></i>
                    @php $cartCount = count(session('cart', [])); @endphp
                    @if($cartCount > 0)
                        <span class="absolute -top-2 -right-3 bg-red-500 text-white text-xs rounded-full px-1.5 py-0.5">{{ $cartCount }}</span>
                    @endif
                    <span class="ml-2 hidden lg:inline">Giỏ hàng</span>
                </a>

                {{-- Login / Logout --}}
                @guest
                    <a href="{{ route('login') }}"
                        class="ml-4 px-4 py-2 rounded-lg bg-gradient-to-r from-purple-600 to-blue-500 text-white hover:from-purple-700 hover:to-blue-600 transition-transform transform hover:scale-105 opacity-0 animate-nav-item" style="animation-delay: 0.9s;">
                        Đăng nhập
                    </a>
                @else
                    <div class="relative group opacity-0 animate-nav-item" style="animation-delay: 0.9s;">
                        <button class="flex items-center space-x-2">
                            <div
                                class="w-8 h-8 rounded-full bg-gradient-to-r from-purple-600 to-blue-500 flex items-center justify-center text-white font-semibold text-sm">
                                {{ substr(Auth::user()->name, 0, 1) }}
                            </div>
                            <span>{{ Auth::user()->name }}</span>
                        </button>

                        <div
                            class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg py-2 border border-gray-100 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 z-50">
                            <a href="{{ route('client.orders.index') }}"
                                class="block px-4 py-2 text-gray-700 hover:bg-gray-100 transition-colors">
                                <i class="fas fa-shopping-bag mr-2"></i>Đơn hàng của tôi
                            </a>
                            <a href="{{ route('wishlist.index') }}"
                                class="block px-4 py-2 text-gray-700 hover:bg-gray-100 transition-colors">
                                <i class="fas fa-heart mr-2"></i>Yêu thích
                            </a>
                            <a href="{{ route('profile.index') }}"
                                class="block px-4 py-2 text-gray-700 hover:bg-gray-100 transition-colors">
                                <i class="fas fa-user mr-2"></i>Thông tin cá nhân
                            </a>
                            <a href="{{ route('addresses.index') }}"
                                class="block px-4 py-2 text-gray-700 hover:bg-gray-100 transition-colors">
                                <i class="fas fa-map-marker-alt mr-2"></i>Địa chỉ
                            </a>

                            <div class="border-t border-gray-200 my-1"></div>
                            <form action="{{ route('client.logout') }}" method="POST">
                                @csrf
                                <button type="submit"
                                    class="block w-full text-left px-4 py-2 text-gray-700 hover:bg-gray-100 transition-colors">
                                    <i class="fas fa-sign-out-alt mr-2"></i>Đăng xuất
                                </button>
                            </form>
                        </div>
                    </div>
                @endguest
            </nav>

            {{-- Mobile Menu Button --}}
            <button id="menu-btn" class="md:hidden p-2 rounded-lg hover:bg-gray-100 transition-all opacity-0 animate-nav-item" style="animation-delay: 0.3s;">
                <svg class="w-6 h-6 text-gray-800" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>
        </div>
    </div>
</header>

<style>
/* Header fade in animation */
@keyframes header-fade-in {
    from {
        opacity: 0;
        transform: translateY(-20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.animate-header-fade-in {
    animation: header-fade-in 0.8s ease-out forwards;
    animation-delay: 0.1s;
}

/* Logo slide animation */
@keyframes logo-slide {
    from {
        opacity: 0;
        transform: translateX(-30px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

.animate-logo-slide {
    animation: logo-slide 0.6s ease-out forwards;
}

/* Nav item animation */
@keyframes nav-item {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.animate-nav-item {
    animation: nav-item 0.5s ease-out forwards;
}

/* Staggered animation for nav items */
.animate-nav-item:nth-child(1) { animation-delay: 0.3s; }
.animate-nav-item:nth-child(2) { animation-delay: 0.4s; }
.animate-nav-item:nth-child(3) { animation-delay: 0.5s; }
.animate-nav-item:nth-child(4) { animation-delay: 0.6s; }
.animate-nav-item:nth-child(5) { animation-delay: 0.7s; }
.animate-nav-item:nth-child(6) { animation-delay: 0.8s; }
.animate-nav-item:nth-child(7) { animation-delay: 0.9s; }

/* Header scroll effect */
.header-scrolled {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(12px);
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
}

/* Smooth transitions for header */
#site-header {
    transition: all 0.3s ease;
}

/* Mobile menu animations */
#mobile-menu {
    transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .animate-header-fade-in {
        animation-duration: 0.6s;
    }
    
    .animate-logo-slide {
        animation-duration: 0.5s;
    }
    
    .animate-nav-item {
        animation-duration: 0.4s;
    }
}
</style>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const header = document.getElementById('site-header');
    
    // Header scroll effect
    let lastScrollY = window.scrollY;
    
    window.addEventListener('scroll', () => {
        if (window.scrollY > 100) {
            header.classList.add('header-scrolled');
        } else {
            header.classList.remove('header-scrolled');
        }
        
        // Hide header on scroll down, show on scroll up
        if (window.scrollY > lastScrollY && window.scrollY > 200) {
            header.style.transform = 'translateY(-100%)';
        } else {
            header.style.transform = 'translateY(0)';
        }
        
        lastScrollY = window.scrollY;
    });

    // Mobile menu behavior (giữ nguyên từ code cũ)
    const openBtn = document.getElementById('menu-btn');
    const closeBtn = document.getElementById('mobile-menu-close');
    const mobileMenu = document.getElementById('mobile-menu');
    const body = document.body;

    function openMenu(){
        if(!mobileMenu) return;
        mobileMenu.classList.remove('hidden');
        setTimeout(()=>{
            mobileMenu.classList.remove('-translate-x-full');
        }, 10);
        body.style.overflow = 'hidden';
    }
    
    function closeMenu(){
        if(!mobileMenu) return;
        mobileMenu.classList.add('-translate-x-full');
        setTimeout(()=>{
            mobileMenu.classList.add('hidden');
            body.style.overflow = '';
        }, 250);
    }

    openBtn?.addEventListener('click', openMenu);
    closeBtn?.addEventListener('click', closeMenu);
    mobileMenu?.addEventListener('click', (e) => {
        if (e.target === mobileMenu) closeMenu();
    });

    // Flash message handling (giữ nguyên từ code cũ)
    const flashMessage = document.getElementById('flash-message');
    if (flashMessage) {
        setTimeout(() => {
            flashMessage.style.transform = 'translateY(0)';
            flashMessage.style.opacity = '1';
        }, 10);

        setTimeout(() => {
            flashMessage.style.transform = 'translateY(-100%)';
            flashMessage.style.opacity = '0';
            setTimeout(() => {
                flashMessage.remove();
            }, 500);
        }, 3000);
    }
});
</script>