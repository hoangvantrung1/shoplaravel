<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'SOP - Shop Online')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        .nav-link {
            position: relative;
            transition: all 0.3s ease;
        }
        
        .nav-link::after {
            content: '';
            position: absolute;
            width: 0;
            height: 2px;
            bottom: -5px;
            left: 0;
            background-color: #8b5cf6;
            transition: width 0.3s ease;
        }
        
        .nav-link:hover::after {
            width: 100%;
        }
        
        .animate-fadeUp {
            animation: fadeUp 0.6s ease-out;
        }
        
        @keyframes fadeUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .line-clamp-1 {
            display: -webkit-box;
            -webkit-line-clamp: 1;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        
        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
    </style>
</head>

<body class="bg-gray-50 font-sans text-gray-800 flex flex-col min-h-screen">

    {{-- Navbar --}}
    @include('layouts.navbar')

    {{-- Main Content --}}
    <main class="flex-1">
        @yield('content')
    </main>

    {{-- Footer --}}
    @include('layouts.footer')

    {{-- Flash Messages --}}
    @if(session('success') || session('error'))
        <div id="flash-message" class="fixed top-16 left-0 right-0 z-40 p-4 transform transition-all duration-500 ease-out"
            style="transform: translateY(-100%); opacity: 0;">
            <div class="flex justify-end">
                @if(session('success'))
                    <div class="bg-fuchsia-500 rounded-lg shadow-lg px-6 py-3 text-white">
                        <p>{{ session('success') }}</p>
                    </div>
                @endif
                @if(session('error'))
                    <div class="bg-red-500 rounded-lg shadow-lg px-6 py-3 text-white">
                        <p>{{ session('error') }}</p>
                    </div>
                @endif
            </div>
        </div>
    @endif

    <script>
        document.addEventListener("DOMContentLoaded", () => {
            // Flash message handling
            const flashMessage = document.getElementById('flash-message');
            if (flashMessage) {
                setTimeout(() => {
                    flashMessage.style.transform = 'translateY(0)';
                    flashMessage.style.opacity = '1';
                }, 10);

                setTimeout(() => {
                    flashMessage.style.transform = 'translateY(-100%)';
                    flashMessage.style.opacity = '0';

                    setTimeout(() => {
                        flashMessage.remove();
                    }, 500);
                }, 3000);
            }
        });
    </script>
    
    {{-- Stack for page-specific scripts --}}
    @stack('scripts')
</body>
</html>
