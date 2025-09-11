<nav class="bg-white shadow py-4">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex justify-between items-center">
        <a href="#" class="text-2xl font-bold text-green-600">ShopLaravel</a>

        {{-- Desktop Menu --}}
        <div class="hidden md:flex space-x-6">
            <a href="/" class="font-semibold hover:text-green-500 transition">Trang Chủ</a>
            <a href="#" class="font-semibold hover:text-green-500 transition">Blog</a>
            <a href="#" class="font-semibold hover:text-green-500 transition">Liên hệ</a>
            <a href="#" class="font-semibold hover:text-green-500 transition">Giới thiệu</a>
            <a href="/cart" class="font-semibold hover:text-green-500 transition">
                Giỏ Hàng (0)
            </a>
        </div>

        {{-- Mobile menu button --}}
        <div class="md:hidden">
            <button id="menu-btn" class="focus:outline-none">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>
        </div>
    </div>

    {{-- Mobile Menu --}}
    <div id="mobile-menu" class="hidden md:hidden bg-white shadow">
        <a href="/" class="block px-4 py-2 font-semibold hover:bg-green-100">Trang Chủ</a>
        <a href="#" class="block px-4 py-2 font-semibold hover:bg-green-100">Blog</a>
        <a href="#" class="block px-4 py-2 font-semibold hover:bg-green-100">Liên hệ</a>
        <a href="#" class="block px-4 py-2 font-semibold hover:bg-green-100">Giới thiệu</a>
        <a href="/cart" class="block px-4 py-2 font-semibold hover:bg-green-100">Giỏ Hàng (0)</a>
    </div>

    <script>
        const btn = document.getElementById('menu-btn');
        const menu = document.getElementById('mobile-menu');
        btn.addEventListener('click', () => {
            menu.classList.toggle('hidden');
        });
    </script>
</nav>
