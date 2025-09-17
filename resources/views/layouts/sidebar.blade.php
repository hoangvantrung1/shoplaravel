<aside x-data="{ open: true } class=" bg-white shadow flex flex-col transition-all duration-300"
    :class="open ? 'w-64' : 'w-16'">
    <div class="p-6 flex justify-between items-center border-b">
        <span class="text-2xl font-bold text-green-600" x-show="open">AdminPanel</span>
        <button @click="open = !open" class="md:hidden focus:outline-none">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </button>
    </div>
    <nav class="flex-1 mt-6 flex flex-col">
        <a href="{{ route('admin.dashboard') }}"
            class="px-6 py-3 flex items-center font-semibold hover:bg-green-100 transition {{ request()->routeIs('admin.dashboard') ? 'bg-green-100' : '' }}">
            <span class="material-icons">dashboard</span>
            <span x-show="open" class="ml-2">Dashboard</span>
        </a>
        <a href="{{ route('admin.products.index') }}"
            class="px-6 py-3 flex items-center font-semibold hover:bg-green-100 transition {{ request()->routeIs('admin.products.*') ? 'bg-green-100' : '' }}">
            <span class="material-icons">inventory_2</span>
            <span x-show="open" class="ml-2">Sản phẩm</span>
        </a>
        <a href="{{ route('admin.orders.index') }}"
            class="px-6 py-3 flex items-center font-semibold hover:bg-green-100 transition">
            <span class="material-icons">shopping_cart</span>
            <span x-show="open" class="ml-2">Đơn hàng</span>
        </a>
        <a href="{{ route('admin.users.index') }}"
            class="px-6 py-3 flex items-center font-semibold hover:bg-green-100 transition {{ request()->routeIs('admin.users.*') ? 'bg-green-100' : '' }}">
            <span class="material-icons">account_circle</span>
            <span x-show="open" class="ml-2">Quản lý tài khoản</span>
        </a>

    </nav>
</aside>