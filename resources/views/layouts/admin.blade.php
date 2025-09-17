<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard')</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="flex h-screen bg-gray-100 font-sans">

    {{-- Sidebar --}}
    @include('layouts.sidebar')
    {{-- Main content --}}
    <div class="flex-1 flex flex-col">

        {{-- Header / Navbar --}}
        @include('layouts.header')

        {{-- Content --}}
        <main class="p-6 flex-1 overflow-auto bg-gray-50">


            @yield('content')

            {{-- Phần phân trang --}}

        </main>

        @include('layouts.footeradmin')
    </div>

    {{-- Alpine.js for sidebar toggle --}}
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

</body>

</html>