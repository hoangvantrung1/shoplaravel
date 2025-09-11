<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'ShopLaravel')</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 font-sans text-gray-800 flex flex-col min-h-screen">

{{-- Navbar --}}
<header class="bg-white shadow-md sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
            <a href="{{ route('home') }}" class="text-2xl font-extrabold text-green-600 hover:text-green-500 transition">ShopLaravel</a>

            {{-- Desktop menu --}}
            <nav class="hidden md:flex space-x-8 font-medium">
                <a href="{{ route('home') }}" class="hover:text-green-500 transition">Trang chủ</a>
                <a href="{{ route('cart.index') }}" class="hover:text-green-500 transition">Giỏ hàng 
                    <span class="ml-1 bg-green-500 text-white text-xs px-2 py-1 rounded-full">
                        {{ count(session('cart',[])) }}
                    </span>
                </a>
                <a href="#" class="hover:text-green-500 transition">Blog</a>
                <a href="#" class="hover:text-green-500 transition">Liên hệ</a>
            </nav>

            {{-- Mobile menu button --}}
            <button id="menu-btn" class="md:hidden focus:outline-none">
                <svg class="w-7 h-7 text-gray-800" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                          d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>
        </div>
    </div>

    {{-- Mobile menu --}}
    <div id="mobile-menu" class="hidden md:hidden bg-white shadow-md border-t">
        <a href="{{ route('home') }}" class="block px-6 py-3 font-medium hover:bg-green-50">Trang chủ</a>
        <a href="{{ route('cart.index') }}" class="block px-6 py-3 font-medium hover:bg-green-50">
            Giỏ hàng ({{ count(session('cart',[])) }})
        </a>
        <a href="#" class="block px-6 py-3 font-medium hover:bg-green-50">Blog</a>
        <a href="#" class="block px-6 py-3 font-medium hover:bg-green-50">Liên hệ</a>
    </div>
</header>

{{-- Main Content --}}
<main class="flex-1 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-600 text-green-800 p-4 rounded mb-6 shadow-sm">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="bg-red-100 border-l-4 border-red-600 text-red-800 p-4 rounded mb-6 shadow-sm">
            {{ session('error') }}
        </div>
    @endif

    @yield('content')
</main>

@extends('layouts.footer')
{{-- Mobile menu script --}}
<script>
    const btn = document.getElementById('menu-btn');
    const menu = document.getElementById('mobile-menu');
    btn.addEventListener('click', () => menu.classList.toggle('hidden'));
</script>

</body>
</html>
