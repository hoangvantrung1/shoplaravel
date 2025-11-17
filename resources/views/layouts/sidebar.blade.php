<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

@php
    $menuItems = [
        ['label' => 'Dashboard', 'icon' => 'dashboard', 'route' => 'admin.dashboard', 'match' => 'admin.dashboard'],
        ['label' => 'Sản phẩm', 'icon' => 'inventory_2', 'route' => 'admin.products.index', 'match' => 'admin.products.*'],
        ['label' => 'Đơn hàng', 'icon' => 'shopping_cart', 'route' => 'admin.orders.index', 'match' => 'admin.orders.*'],
        ['label' => 'Danh mục', 'icon' => 'category', 'route' => 'admin.categories.index', 'match' => 'admin.categories.*'],
        ['label' => 'Thương hiệu', 'icon' => 'shopping_bag', 'route' => 'admin.brands.index', 'match' => 'admin.brands.*'],
        ['label' => 'Doanh thu', 'icon' => 'account_balance', 'route' => 'admin.reports.sales', 'match' => 'admin.reports.sales'],
        ['label' => 'Phiếu giảm giá', 'icon' => 'confirmation_number', 'route' => 'admin.coupons.index', 'match' => 'admin.coupons.*'],
        ['label' => 'Đánh giá', 'icon' => 'reviews', 'route' => 'admin.reviews.index', 'match' => 'admin.reviews.*'],
        ['label' => 'Bài viết', 'icon' => 'article', 'route' => 'admin.posts.index', 'match' => 'admin.posts.*'],
        ['label' => 'Liên hệ', 'icon' => 'contact_mail', 'route' => 'admin.contacts.index', 'match' => 'admin.contacts.*'],
        ['label' => 'Địa chỉ', 'icon' => 'location_on', 'route' => 'admin.addresses.index', 'match' => 'admin.addresses.*'],
        ['label' => 'Yêu thích', 'icon' => 'favorite', 'route' => 'admin.wishlists.index', 'match' => 'admin.wishlists.*'],
    ];
@endphp

<aside x-data="{ open: true, accountOpen: {{ request()->routeIs('admin.users.*') || request()->routeIs('admin.admins.*') ? 'true' : 'false' }} }"
    class="bg-white/95 backdrop-blur shadow-lg flex flex-col transition-all duration-300 border-r border-gray-100"
    :class="open ? 'w-64' : 'w-20'">

    <div class="p-5 border-b border-gray-100 flex items-center justify-between">
        <div class="flex items-center space-x-3">
            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-emerald-500 to-green-400 text-white flex items-center justify-center font-bold">AP</div>
            <div x-show="open" class="leading-tight">
                <p class="text-sm text-gray-500">Quản trị hệ thống</p>
                <p class="text-lg font-semibold text-gray-900">Admin Panel</p>
            </div>
        </div>
        <button @click="open = !open" class="hidden md:flex items-center justify-center w-9 h-9 rounded-full border border-gray-200 text-gray-500 hover:bg-gray-50 transition">
            <span class="material-icons" x-show="open">chevron_left</span>
            <span class="material-icons" x-show="!open">chevron_right</span>
        </button>
    </div>

    <div class="px-5 py-4 border-b border-gray-50" x-show="open">
        <div class="flex items-center space-x-3">
            <div class="w-12 h-12 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center text-xl font-semibold">
                {{ strtoupper(substr(auth('admin')->user()->name ?? 'A', 0, 1)) }}
            </div>
            <div>
                <p class="text-sm text-gray-500">Xin chào,</p>
                <p class="font-semibold text-gray-900 truncate">{{ auth('admin')->user()->name ?? 'Admin' }}</p>
            </div>
        </div>
    </div>

    <nav class="flex-1 py-4 space-y-1 overflow-y-auto">
        <p class="text-xs uppercase tracking-widest text-gray-400 px-4" x-show="open">Chức năng chính</p>

        @foreach ($menuItems as $item)
            @php
                $active = request()->routeIs($item['match']);
            @endphp
            <a href="{{ route($item['route']) }}"
                class="group mx-3 px-3 py-2 rounded-xl flex items-center text-sm font-medium transition
                    {{ $active ? 'bg-emerald-50 text-emerald-600' : 'text-gray-600 hover:bg-gray-50' }}"
                title="{{ $item['label'] }}">
                <span class="material-icons text-[20px]">{{ $item['icon'] }}</span>
                <span x-show="open" class="ml-3 flex-1">{{ $item['label'] }}</span>
                @if($active)
                    <span x-show="open" class="text-xs font-semibold text-emerald-500">Đang xem</span>
                @endif
            </a>
        @endforeach

        <div class="px-3">
            <div class="h-px bg-gray-100 my-3"></div>
        </div>

        <!-- Quản lý tài khoản -->
        <div class="mx-3">
            <button @click="accountOpen = !accountOpen"
                class="w-full px-3 py-2 rounded-xl flex items-center justify-between text-sm font-semibold text-gray-600 hover:bg-gray-50 transition">
                <div class="flex items-center space-x-3">
                    <span class="material-icons text-[20px]">manage_accounts</span>
                    <span x-show="open">Quản lý tài khoản</span>
                </div>
                <span class="material-icons text-sm transition-transform duration-200"
                    :class="{ 'rotate-180': accountOpen }" x-show="open">expand_more</span>
            </button>
            <div x-show="accountOpen" x-collapse class="mt-2 space-y-1 pl-3 border-l border-gray-100" x-cloak>
                <a href="{{ route('admin.users.index') }}"
                    class="flex items-center px-3 py-2 text-sm rounded-lg {{ request()->routeIs('admin.users.*') ? 'bg-emerald-50 text-emerald-600' : 'text-gray-600 hover:bg-gray-50' }}">
                    <span class="material-icons text-sm mr-2 text-blue-500">people</span>
                    <span x-show="open">Quản lý User</span>
                </a>
                <a href="{{ route('admin.admins.index') }}"
                    class="flex items-center px-3 py-2 text-sm rounded-lg {{ request()->routeIs('admin.admins.*') ? 'bg-emerald-50 text-emerald-600' : 'text-gray-600 hover:bg-gray-50' }}">
                    <span class="material-icons text-sm mr-2 text-purple-500">admin_panel_settings</span>
                    <span x-show="open">Quản lý Admin</span>
                </a>
            </div>
        </div>
    </nav>
</aside>