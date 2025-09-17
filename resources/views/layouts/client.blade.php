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
    @include('layouts.navbar')

    {{-- Main Content --}}
    <main class="flex-1 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        @yield('content')
    </main>

    {{-- Footer --}}
    @include('layouts.footer')

    {{-- Mobile menu --}}
    <div id="mobile-menu" class="hidden md:hidden bg-white shadow-md border-t">
        <a href="{{ route('home') }}" class="block px-6 py-3 font-medium hover:bg-green-50">Trang chủ</a>
        <a href="{{ route('cart.index') }}" class="block px-6 py-3 font-medium hover:bg-green-50">
            Giỏ hàng ({{ count(session('cart', [])) }})
        </a>
        <a href="#" class="block px-6 py-3 font-medium hover:bg-green-50">Blog</a>
        <a href="#" class="block px-6 py-3 font-medium hover:bg-green-50">Liên hệ</a>

        @guest
        <a href="{{ route('login') }}" class="block px-6 py-3 font-medium hover:bg-green-50 flex items-center">
            Đăng nhập
        </a>
        @else
        <form action="{{ route('client.logout') }}" method="POST" class="block px-6 py-3">
            @csrf
            <button type="submit" class="w-full flex items-center text-left hover:bg-red-50 text-red-600">
                {{ Auth::user()->name }} (Thoát)
            </button>
        </form>
        @endguest
    </div>
</body>
</html>
