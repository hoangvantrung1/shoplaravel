<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>@yield('title','Admin Dashboard')</title>
<script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="flex h-screen bg-gray-100 font-sans">

{{-- Sidebar --}}
<aside x-data="{ open: true } class="bg-white shadow flex flex-col transition-all duration-300" :class="open ? 'w-64' : 'w-16'">
    <div class="p-6 flex justify-between items-center border-b">
        <span class="text-2xl font-bold text-green-600" x-show="open">AdminPanel</span>
        <button @click="open = !open" class="md:hidden focus:outline-none">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
            </svg>
        </button>
    </div>
    <nav class="flex-1 mt-6 flex flex-col">
        <a href="{{ route('admin.dashboard') }}" class="px-6 py-3 flex items-center font-semibold hover:bg-green-100 transition {{ request()->routeIs('admin.dashboard') ? 'bg-green-100' : '' }}">
            <span class="material-icons">dashboard</span>
            <span x-show="open" class="ml-2">Dashboard</span>
        </a>
        <a href="{{ route('admin.products.index') }}" class="px-6 py-3 flex items-center font-semibold hover:bg-green-100 transition {{ request()->routeIs('admin.products.*') ? 'bg-green-100' : '' }}">
            <span class="material-icons">inventory_2</span>
            <span x-show="open" class="ml-2">Sản phẩm</span>
        </a>
        <a href="{{ route('admin.orders.index') }}" class="px-6 py-3 flex items-center font-semibold hover:bg-green-100 transition">
            <span class="material-icons">shopping_cart</span>
            <span x-show="open" class="ml-2">Đơn hàng</span>
        </a>
        <a href="#" class="px-6 py-3 flex items-center font-semibold hover:bg-green-100 transition">
            <span class="material-icons">people</span>
            <span x-show="open" class="ml-2">Người dùng</span>
        </a>
    </nav>
</aside>

{{-- Main content --}}
<div class="flex-1 flex flex-col">

    {{-- Header / Navbar --}}
    <header class="bg-white shadow sticky top-0 z-10 py-4 px-6 flex justify-between items-center">
        <div>
            <h1 class="text-xl font-bold">@yield('title','Dashboard')</h1>
            {{-- Breadcrumb example --}}
            <nav class="text-gray-500 text-sm mt-1">@yield('breadcrumb')</nav>
        </div>
        <div>
            <a href="#" class="text-green-600 font-semibold hover:text-green-500 transition">Đăng xuất</a>
        </div>
    </header>

    {{-- Content --}}
    <main class="p-6 flex-1 overflow-auto bg-gray-50">
        @if(session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded mb-6 shadow">
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded mb-6 shadow">
                {{ session('error') }}
            </div>
        @endif

        @yield('content')
    </main>

    {{-- Footer --}}
    <footer class="bg-white py-4 text-center text-gray-600 shadow-inner mt-auto">
        © 2025 AdminPanel - All rights reserved.
    </footer>
</div>

{{-- Alpine.js for sidebar toggle --}}
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

</body>
</html>
