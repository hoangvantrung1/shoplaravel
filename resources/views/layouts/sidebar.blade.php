<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
<script src="https://kit.fontawesome.com/bdc6329ab7.js" crossorigin="anonymous"></script>
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

<aside x-data="{ open: true }" class="bg-white shadow flex flex-col transition-all duration-300 relative"
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
        <!-- Dashboard -->
        <a href="{{ route('admin.dashboard') }}"
            class="px-6 py-3 flex items-center font-semibold hover:bg-green-100 transition {{ request()->routeIs('admin.dashboard') ? 'bg-green-100' : '' }}">
            <span class="material-icons">dashboard</span>
            <span x-show="open" class="ml-2">Dashboard</span>
        </a>

        <!-- Sản phẩm -->
        <a href="{{ route('admin.products.index') }}"
            class="px-6 py-3 flex items-center font-semibold hover:bg-green-100 transition {{ request()->routeIs('admin.products.*') ? 'bg-green-100' : '' }}">
            <span class="material-icons">inventory_2</span>
            <span x-show="open" class="ml-2">Sản phẩm</span>
        </a>

        <!-- Đơn hàng -->
        <a href="{{ route('admin.orders.index') }}"
            class="px-6 py-3 flex items-center font-semibold hover:bg-green-100 transition {{ request()->routeIs('admin.orders.*') ? 'bg-green-100' : '' }}">
            <span class="material-icons">shopping_cart</span>
            <span x-show="open" class="ml-2">Đơn hàng</span>
        </a>

        <!-- Dropdown Quản lý tài khoản -->
        <div x-data="{ openDropdown: false }">
            <button @click="openDropdown = !openDropdown"
                class="px-6 py-3 flex items-center justify-between w-full font-semibold hover:bg-green-100 transition {{ request()->routeIs('admin.users.*') || request()->routeIs('admin.admins.*') ? 'bg-green-100' : '' }}">
                <div class="flex items-center">
                    <span class="material-icons">account_circle</span>
                    <span x-show="open" class="ml-2">Quản lý tài khoản</span>
                </div>
                <span x-show="open" class="material-icons text-sm transition-transform duration-200"
                    :class="{ 'rotate-180': openDropdown }">expand_more</span>
            </button>

            <!-- Dropdown nằm ngay dưới, không position absolute -->
            <div x-show="openDropdown"
                x-transition
                class="bg-gray-50 ml-6 mt-1 rounded-lg overflow-hidden"
                @click.outside="openDropdown = false"
                style="display: none;">
                <a href="{{ route('admin.users.index') }}"
                class="flex items-center px-6 py-2 text-sm text-gray-700 hover:bg-green-50 border-b border-gray-100 transition-colors duration-200"
                @click="openDropdown = false">
                    <span class="material-icons text-blue-500 mr-2 text-lg">people</span>
                    <span>Quản lý User</span>
                </a>

                <a href="{{ route('admin.admins.index') }}"
                class="flex items-center px-6 py-2 text-sm text-gray-700 hover:bg-green-50 transition-colors duration-200"
                @click="openDropdown = false">
                    <span class="material-icons text-purple-500 mr-2 text-lg">admin_panel_settings</span>
                    <span>Quản lý Admin</span>
                </a>
            </div>
        </div>
        <!-- Danh mục -->
        <a href="{{ route('admin.categories.index') }}"
            class="px-6 py-3 flex items-center font-semibold hover:bg-green-100 transition {{ request()->routeIs('admin.categories.*') ? 'bg-green-100' : '' }}">
            <span class="material-icons">category</span>
            <span x-show="open" class="ml-2">Quản lý danh mục</span>
        </a>

        <!-- Thương hiệu -->
        <a href="{{ route('admin.brands.index') }}"
            class="px-6 py-3 flex items-center font-semibold hover:bg-green-100 transition {{ request()->routeIs('admin.brands.*') ? 'bg-green-100' : '' }}">
            <span class="material-icons">shopping_bag</span>
            <span x-show="open" class="ml-2">Quản lý thương hiệu</span>
        </a>

        <!-- Doanh thu -->
        <a href="{{ route('admin.reports.sales') }}"
            class="px-6 py-3 flex items-center font-semibold hover:bg-green-100 transition {{ request()->routeIs('admin.reports.sales') ? 'bg-green-100' : '' }}">
            <span class="material-icons">account_balance</span>
            <span x-show="open" class="ml-2">Quản lý doanh thu</span>
        </a>

        <!-- Phiếu giảm giá -->
        <a href="{{ route('admin.coupons.index') }}"
            class="px-6 py-3 flex items-center font-semibold hover:bg-green-100 transition {{ request()->routeIs('admin.coupons.*') ? 'bg-green-100' : '' }}">
            <span class="material-icons">confirmation_number</span>
            <span x-show="open" class="ml-2">Quản lý phiếu giảm giá</span>
        </a>
        <a href="{{ route('admin.reviews.index') }}"
            class="px-6 py-3 flex items-center font-semibold hover:bg-green-100 transition {{ request()->routeIs('admin.reviews.*') ? 'bg-green-100' : '' }}">
            <span class="material-icons">reviews</span>
            <span x-show="open" class="ml-2">Quản lý đánh giá</span>
        </a>
        <a href="{{ route('admin.posts.index') }}"
            class="px-6 py-3 flex items-center font-semibold hover:bg-green-100 transition {{ request()->routeIs('admin.posts.*') ? 'bg-green-100' : '' }}">
            <span class="material-icons">article</span>
            <span x-show="open" class="ml-2">Quản lý bài viết</span>
        </a>
        <a href="{{ route('admin.contacts.index') }}"
            class="px-6 py-3 flex items-center font-semibold hover:bg-green-100 transition {{ request()->routeIs('admin.contacts.*') ? 'bg-green-100' : '' }}">
            <span class="material-icons">contact_mail</span>
            <span x-show="open" class="ml-2">Quản lý liên hệ</span>
        </a>
        <a href="{{ route('admin.addresses.index') }}"
            class="px-6 py-3 flex items-center font-semibold hover:bg-green-100 transition {{ request()->routeIs('admin.addresses.*') ? 'bg-green-100' : '' }}">
            <span class="material-icons">location_on</span>
            <span x-show="open" class="ml-2">Quản lý địa chỉ</span>
        </a>
        <a href="{{ route('admin.wishlists.index') }}"
            class="px-6 py-3 flex items-center font-semibold hover:bg-green-100 transition {{ request()->routeIs('admin.wishlists.*') ? 'bg-green-100' : '' }}">
            <span class="material-icons">favorite</span>
            <span x-show="open" class="ml-2">Sản phẩm yêu thích</span>
        </a>
    </nav>
</aside>