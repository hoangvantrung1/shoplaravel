<header class="sticky top-0 z-20 bg-white/80 backdrop-blur border-b border-gray-100">
    <div class="max-w-8xl mx-auto px-4 sm:px-6 lg:px-8 py-4 flex items-center justify-between gap-6">
        <div class="flex-1"></div>

        <div class="flex items-center space-x-4">
            @auth('admin')
                <div class="hidden sm:flex items-center bg-gray-50 rounded-2xl px-4 py-2 border border-gray-100">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-emerald-500 to-green-500 text-white flex items-center justify-center font-semibold uppercase mr-3">
                        {{ strtoupper(Str::substr(Auth::guard('admin')->user()->name, 0, 1)) }}
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-gray-900">{{ Auth::guard('admin')->user()->name }}</p>
                        <p class="text-xs text-gray-500">Super Admin</p>
                    </div>
                </div>
            @endauth

            <form method="POST" action="{{ route('admin.logout') }}">
                @csrf
                <button type="submit"
                    class="flex items-center gap-2 px-4 py-2 rounded-xl border border-red-100 text-red-600 font-semibold hover:bg-red-50 transition">
                    <span class="material-icons text-base">logout</span>
                    <span class="hidden sm:inline">Đăng xuất</span>
                </button>
            </form>
        </div>
    </div>
</header>