<header class="bg-white shadow sticky top-0 z-10 py-4 px-6 flex justify-between items-center">
    <div class="flex items-center space-x-4">
        <span class="font-bold text-lg"></span>
    </div>
    <div class="flex items-center space-x-4">
        {{-- Hiển thị tên admin --}}
        @auth('admin')
            <div class="flex items-center text-gray-700 space-x-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M5.121 17.804A9.004 9.004 0 0112 15c2.21 0 4.21.802 5.879 2.137M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                <span class="font-medium">Xin chào, {{ Auth::guard('admin')->user()->name }}</span>
                <span class="text-xs bg-green-100 text-green-800 px-2 py-1 rounded ml-2">Admin</span>
            </div>
        @endauth

        {{-- Đăng xuất --}}
        <form method="POST" action="{{ route('admin.logout') }}">
            @csrf
            <button type="submit" class="flex items-center text-red-600 font-semibold hover:text-red-500 transition">
                {{-- Icon logout --}}
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1m0-14v1" />
                </svg>
                Đăng xuất
            </button>
        </form>
    </div>
</header>