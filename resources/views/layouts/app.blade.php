<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'ShopLaravel - Cửa hàng công nghệ hàng đầu')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Tối ưu hóa các animation */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translate3d(0, 30px, 0);
            }

            to {
                opacity: 1;
                transform: translate3d(0, 0, 0);
            }
        }

        @keyframes zoomIn {
            from {
                opacity: 0;
                transform: scale3d(0.95, 0.95, 0.95);
            }

            to {
                opacity: 1;
                transform: scale3d(1, 1, 1);
            }
        }

        @keyframes subtleGlow {

            0%,
            100% {
                box-shadow: 0 0 15px rgba(168, 85, 247, 0.4);
            }

            50% {
                box-shadow: 0 0 25px rgba(192, 38, 211, 0.6);
            }
        }

        @keyframes slideInLeft {
            from {
                opacity: 0;
                transform: translate3d(-50px, 0, 0);
            }

            to {
                opacity: 1;
                transform: translate3d(0, 0, 0);
            }
        }

        @keyframes pulse {
            0% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.05);
            }

            100% {
                transform: scale(1);
            }
        }

        /* Sử dụng will-change để tối ưu hiệu năng */
        .animate-fadeUp {
            animation: fadeInUp 0.6s ease-out forwards;
            opacity: 0;
            will-change: opacity, transform;
        }

        .animate-zoom {
            animation: zoomIn 0.5s ease-out forwards;
            opacity: 0;
            will-change: opacity, transform;
        }

        .animate-glow {
            animation: subtleGlow 3s infinite ease-in-out;
            will-change: box-shadow;
        }

        .animate-slideLeft {
            animation: slideInLeft 0.7s ease-out forwards;
            opacity: 0;
            will-change: opacity, transform;
        }

        .animate-pulse-slow {
            animation: pulse 3s infinite ease-in-out;
        }

        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        /* Chatbot dark mode (scoped) */
        #chatbot.dark {
            background: #0f172a; /* slate-900 */
            color: #e2e8f0; /* slate-200 */
            border-color: #1f2937; /* gray-800 */
        }
        #chatbot.dark .chat-header {
            background: #111827; /* gray-900 */
            color: #e5e7eb;
        }
        #chatbot.dark .chat-input {
            background: #0b1220;
            border-color: #1f2937;
            color: #e5e7eb;
        }
        #chatbot.dark .chat-send {
            background: #7c3aed;
        }
        #chatbot.dark .bubble-user { background: rgba(147,197,253,.12); color: #e2e8f0; }
        #chatbot.dark .bubble-bot { background: rgba(148,163,184,.2); color: #e2e8f0; }
        #chatbot.dark .suggest-card { background: #0b1220; border-color: #1f2937; }
        #chatbot.dark .product-link { background: #0b1220; border-color: #1f2937; }
        #chatbot.dark .divider { border-color: #1f2937; }

        /* Typing indicator */
        .typing { display: inline-flex; align-items: center; gap: 4px; }
        .typing .dot { width: 6px; height: 6px; border-radius: 9999px; background: #6b7280; animation: blink 1.2s infinite; }
        .typing .dot:nth-child(2){ animation-delay:.2s }
        .typing .dot:nth-child(3){ animation-delay:.4s }
        @keyframes blink { 0%,80%,100%{ opacity: .2 } 40%{ opacity: 1 } }

        ::-webkit-scrollbar-thumb {
            background: #a855f7;
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #9333ea;
        }

        /* Back to top button */
        .back-to-top {
            display: none;
            position: fixed;
            bottom: 30px;
            right: 30px;
            z-index: 99;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: #a855f7;
            color: white;
            border: none;
            cursor: pointer;
            box-shadow: 0 4px 12px rgba(168, 85, 247, 0.3);
            transition: all 0.3s;
        }

        .back-to-top:hover {
            background: #9333ea;
            transform: translateY(-3px);
        }

        /* Giảm chuyển động cho những người nhạy cảm */
        @media (prefers-reduced-motion: reduce) {

            .animate-fadeUp,
            .animate-zoom,
            .animate-glow,
            .animate-slideLeft,
            .animate-pulse-slow {
                animation: none;
                opacity: 1;
                transform: none;
            }
        }

        /* Category hover effect */
        .category-card {
            transition: all 0.3s ease;
        }

        .category-card:hover {
            transform: translateY(-5px);
        }

        /* Product card enhancements */
        .product-card {
            transition: all 0.3s ease;
            overflow: hidden;
        }

        .product-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }

        /* Newsletter form */
        .newsletter-form input:focus {
            outline: none;
            box-shadow: 0 0 0 3px rgba(168, 85, 247, 0.3);
        }

        /* Testimonial card */
        .testimonial-card {
            transition: all 0.3s ease;
        }

        .testimonial-card:hover {
            transform: scale(1.02);
        }

        /* Brand logo hover */
        .brand-logo {
            transition: all 0.3s ease;
            filter: grayscale(100%);
            opacity: 0.7;
        }

        .brand-logo:hover {
            filter: grayscale(0%);
            opacity: 1;
            transform: scale(1.1);
        }
    </style>
</head>

<body class="bg-gray-50 font-sans text-gray-800 flex flex-col min-h-screen">

    {{-- Navbar --}}
    @include('layouts.navbar')

    {{-- Banner Video --}}
    @include('components.banner-video')

    {{-- Main Content --}}
    <main class="flex-1 w-full">
        @yield('content')

        {{-- Danh mục sản phẩm --}}

        {{-- Sản phẩm mới - chỉ hiển thị khi không có bộ lọc --}}
        @if(isset($isHomePage) && $isHomePage)
            <section class="pt-8 pb-6 bg-gray-50">
                <div class="max-w-7xl mx-auto px-6">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-3xl font-bold text-gray-800 animate-fadeUp">
                            Sản phẩm mới
                        </h2>
                        <a href="{{ route('products.index') }}"
                            class="text-purple-600 hover:text-purple-800 font-semibold flex items-center">
                            Xem tất cả <i class="fas fa-arrow-right ml-2"></i>
                        </a>
                    </div>
                    <div class="animate-fadeUp">
                        @include('components.new-products', ['newProducts' => $newProducts])
                    </div>
            </section>
        @endif

        {{-- Sản phẩm bán chạy --}}

        {{-- Thương hiệu nổi bật --}}
        @include('components.brands-section')
        {{-- Blog & Tin tức --}}
        @if(isset($posts) && $posts->count() > 0)
            <section class="pt-0 pb-6 bg-white">
                <div class="max-w-7xl mx-auto px-6">
                    <div class="text-center mb-8">
                        <h2 class="text-3xl font-bold text-gray-800 mb-3">Blog & Tin tức</h2>
                        <p class="text-lg text-gray-600 max-w-3xl mx-auto">
                            Cập nhật những tin tức mới nhất về công nghệ
                        </p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-8">
                        @foreach($posts->take(3) as $post)
                                    <article class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition">
                                        <a href="{{ route('blog.detail', $post->slug) }}" class="block">
                                            <div class="relative w-full aspect-[16/9] overflow-hidden bg-gray-100">
                                                <img src="{{ $post->featured_image_url }}"
                                                    alt="{{ $post->title }}"
                                                    class="w-full h-full object-cover hover:scale-105 transition-transform duration-300"
                                                    onerror="this.onerror=null; this.src='https://via.placeholder.com/800x450/9ca3af/ffffff?text=No+Image'">
                                            </div>
                                        </a>
                                        <div class="p-6">
                                            <div class="flex items-center text-sm text-gray-500 mb-2">
                                                @php
                                                    $category = 'Công nghệ';
                                                    $categoryColor = 'purple';
                                                    if (str_contains($post->slug, 'airpods') || str_contains($post->title, 'AirPods')) {
                                                        $category = 'Âm thanh';
                                                        $categoryColor = 'green';
                                                    } elseif (str_contains($post->slug, 'meo') || str_contains($post->title, 'mẹo')) {
                                                        $category = 'Mẹo hay';
                                                        $categoryColor = 'blue';
                                                    }
                                                @endphp
                            <span
                                                    class="bg-{{ $categoryColor }}-100 text-{{ $categoryColor }}-600 px-2 py-1 rounded text-xs">
                                                    {{ $category }}
                                                </span>
                                                <span class="mx-2">•</span>
                                                <span>{{ $post->published_at->format('d/m/Y') }}</span>
                                            </div>
                                            <h3 class="text-xl font-bold text-gray-800 mb-3 hover:text-purple-600 transition">
                                                <a href="{{ route('blog.detail', $post->slug) }}">{{ Str::limit($post->title, 50) }}</a>
                                            </h3>
                                            <p class="text-gray-600 mb-4">
                                                {{ Str::limit($post->excerpt ?: strip_tags($post->content), 100) }}</p>
                                            <a href="{{ route('blog.detail', $post->slug) }}"
                                                class="text-purple-600 font-semibold hover:text-purple-800 flex items-center">
                                                Đọc tiếp <i class="fas fa-arrow-right ml-2 text-sm"></i>
                                            </a>
                                        </div>
                                    </article>
                        @endforeach
                    </div>

                    <div class="text-center">
                        <a href="{{ route('blog') }}"
                            class="bg-purple-600 text-white px-6 py-3 rounded-lg hover:bg-purple-700 transition font-semibold">
                            Xem tất cả bài viết
                        </a>
                    </div>
                </div>
            </section>
        @endif
        {{-- Tại sao chọn chúng tôi --}}
        <section class="py-6 bg-white">
            <div class="max-w-7xl mx-auto px-6">
                <h2 class="text-3xl font-bold mb-6 text-center text-gray-800 animate-fadeUp">Tại sao chọn chúng tôi?
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                    <div class="p-6 bg-gray-100 rounded-lg shadow-md hover:shadow-xl transition-all text-center">
                        <div class="text-4xl text-purple-600 mb-4">
                            <i class="fas fa-award"></i>
                        </div>
                        <h3 class="text-xl font-semibold mb-2">Chất lượng cao</h3>
                        <p class="text-gray-600">Sản phẩm chính hãng, được kiểm tra nghiêm ngặt, đảm bảo chất lượng hàng
                            đầu.</p>
                    </div>
                    <div class="p-6 bg-gray-100 rounded-lg shadow-md hover:shadow-xl transition-all text-center">
                        <div class="text-4xl text-purple-600 mb-4">
                            <i class="fas fa-shipping-fast"></i>
                        </div>
                        <h3 class="text-xl font-semibold mb-2">Giao hàng nhanh</h3>
                        <p class="text-gray-600">Hệ thống giao hàng siêu tốc, nhận hàng trong 2 giờ tại nội thành.</p>
                    </div>
                    <div class="p-6 bg-gray-100 rounded-lg shadow-md hover:shadow-xl transition-all text-center">
                        <div class="text-4xl text-purple-600 mb-4">
                            <i class="fas fa-headset"></i>
                        </div>
                        <h3 class="text-xl font-semibold mb-2">Hỗ trợ 24/7</h3>
                        <p class="text-gray-600">Đội ngũ hỗ trợ luôn sẵn sàng giải đáp mọi thắc mắc của bạn mọi lúc.</p>
                    </div>
                    <div class="p-6 bg-gray-100 rounded-lg shadow-md hover:shadow-xl transition-all text-center">
                        <div class="text-4xl text-purple-600 mb-4">
                            <i class="fas fa-undo"></i>
                        </div>
                        <h3 class="text-xl font-semibold mb-2">Đổi trả dễ dàng</h3>
                        <p class="text-gray-600">Chính sách đổi trả linh hoạt trong vòng 30 ngày nếu có lỗi từ NSX.</p>
                    </div>
                </div>
            </div>
        </section>
        {{-- Khuyến mãi nổi bật --}}


        {{-- Newsletter --}}
        <section class="relative py-12 bg-gradient-to-br from-purple-600 via-purple-700 to-purple-800 text-white overflow-hidden">
            <!-- Decorative background elements -->
            <div class="absolute inset-0 overflow-hidden pointer-events-none">
                <div class="absolute top-0 -right-20 w-64 h-64 bg-purple-500/20 rounded-full blur-3xl"></div>
                <div class="absolute bottom-0 -left-20 w-64 h-64 bg-purple-400/20 rounded-full blur-3xl"></div>
            </div>
            
            <div class="relative max-w-4xl mx-auto px-6 text-center">
                <!-- Icon -->
                <div class="mb-4 flex justify-center">
                    <div class="w-16 h-16 bg-white/20 backdrop-blur-sm rounded-full flex items-center justify-center">
                        <i class="fas fa-envelope text-2xl text-white"></i>
                    </div>
                </div>
                
                <h2 class="text-3xl sm:text-4xl font-bold mb-3 animate-fadeUp">Đăng ký nhận thông báo</h2>
                <p class="text-lg text-purple-100 mb-8 max-w-2xl mx-auto leading-relaxed">
                    Nhận thông tin về sản phẩm mới, khuyến mãi đặc biệt và các sự kiện công nghệ độc quyền
                </p>

                <form class="newsletter-form flex flex-col sm:flex-row gap-3 max-w-lg mx-auto mb-6">
                    <div class="flex-1 relative">
                        <input type="email" 
                               placeholder="Nhập email của bạn" 
                               class="w-full px-5 py-4 pr-12 rounded-xl text-gray-800 placeholder-gray-400 focus:ring-2 focus:ring-white focus:ring-opacity-50 focus:outline-none shadow-lg transition-all"
                               required>
                        <i class="fas fa-envelope absolute right-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                    </div>
                    <button type="submit"
                        class="group relative px-8 py-4 bg-white text-purple-600 font-semibold rounded-xl hover:bg-gray-50 transition-all shadow-lg hover:shadow-xl hover:-translate-y-0.5 overflow-hidden">
                        <span class="relative z-10 flex items-center gap-2">
                            Đăng ký ngay
                            <i class="fas fa-arrow-right text-sm group-hover:translate-x-1 transition-transform"></i>
                        </span>
                        <!-- Shine effect -->
                        <span class="absolute inset-0 bg-gradient-to-r from-transparent via-white/30 to-transparent -translate-x-full group-hover:translate-x-full transition-transform duration-700"></span>
                    </button>
                </form>

                <div class="flex items-center justify-center gap-2 text-sm text-purple-200">
                    <i class="fas fa-shield-alt text-purple-300"></i>
                    <p>Chúng tôi tôn trọng quyền riêng tư của bạn và không chia sẻ thông tin với bên thứ ba.</p>
                </div>
            </div>
        </section>
    </main>
       <!-- Chatbot floating -->
    <div id="chatbot"
        class="fixed bottom-6 right-6 w-96 bg-white shadow-2xl rounded-3xl border border-gray-200 hidden flex-col overflow-hidden z-40 transition-all duration-300"
        role="dialog" aria-label="Chat hỗ trợ tự động">
        <!-- Header với gradient -->
        <div class="chat-header bg-gradient-to-r from-purple-600 to-purple-700 text-white p-4 flex justify-between items-center shadow-md">
            <div class="flex items-center gap-3">
                <div class="relative">
                    <div class="w-10 h-10 bg-white/20 backdrop-blur-sm rounded-full flex items-center justify-center">
                        <i class="fas fa-robot text-lg"></i>
                    </div>
                    <span class="absolute -top-1 -right-1 inline-flex h-3 w-3 rounded-full bg-emerald-400 animate-pulse border-2 border-white"></span>
                </div>
                <div>
                    <h4 class="font-semibold text-base">Hỗ trợ tự động</h4>
                    <p class="text-xs text-purple-100">Trực tuyến</p>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <button id="toggleTheme" 
                        class="w-8 h-8 flex items-center justify-center rounded-lg text-white/90 hover:text-white hover:bg-white/20 transition-colors" 
                        title="Chuyển giao diện" 
                        aria-label="Chuyển giao diện">
                    <i class="fas fa-moon text-sm"></i>
                </button>
                <button id="closeChat" 
                        class="w-8 h-8 flex items-center justify-center rounded-lg text-white/90 hover:text-white hover:bg-white/20 transition-colors" 
                        title="Đóng" 
                        aria-label="Đóng chat">
                    <i class="fas fa-times text-sm"></i>
                </button>
            </div>
        </div>
        
        <!-- Chat Body -->
        <div id="chatBody" class="p-4 h-96 overflow-y-auto space-y-3 text-sm bg-gradient-to-b from-gray-50 to-white">
            <!-- Welcome message với quick actions -->
            <div id="welcomeMessage" class="space-y-3">
                <div class="flex items-start gap-2">
                    <div class="w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-robot text-purple-600 text-xs"></i>
                    </div>
                    <div class="flex-1">
                        <div class="bg-white border border-gray-200 rounded-2xl rounded-tl-sm px-4 py-3 shadow-sm">
                            <p class="text-gray-800 font-medium mb-2">Xin chào! 👋</p>
                            <p class="text-gray-600 text-sm mb-3">Tôi có thể giúp bạn:</p>
                            <div class="flex flex-wrap gap-2">
                                <button class="quick-action-btn px-3 py-1.5 bg-purple-50 text-purple-700 rounded-lg text-xs font-medium hover:bg-purple-100 transition-colors" data-action="giá sản phẩm">
                                    💰 Giá sản phẩm
                                </button>
                                <button class="quick-action-btn px-3 py-1.5 bg-blue-50 text-blue-700 rounded-lg text-xs font-medium hover:bg-blue-100 transition-colors" data-action="hàng tồn kho">
                                    📦 Hàng tồn kho
                                </button>
                                <button class="quick-action-btn px-3 py-1.5 bg-green-50 text-green-700 rounded-lg text-xs font-medium hover:bg-green-100 transition-colors" data-action="mô tả sản phẩm">
                                    📝 Mô tả sản phẩm
                                </button>
                                <button class="quick-action-btn px-3 py-1.5 bg-orange-50 text-orange-700 rounded-lg text-xs font-medium hover:bg-orange-100 transition-colors" data-action="hỗ trợ">
                                    🆘 Hỗ trợ
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Input area -->
        <div class="p-4 border-t border-gray-200 bg-white">
            <div class="flex items-end gap-2">
                <div class="flex-1 relative">
                    <input id="chatInput" 
                           type="text" 
                           placeholder="Nhập câu hỏi của bạn..." 
                           aria-label="Ô nhập câu hỏi"
                           class="chat-input w-full border-2 border-gray-200 rounded-xl px-4 py-3 pr-10 text-sm focus:ring-2 focus:ring-purple-400 focus:border-purple-400 focus:outline-none transition-all">
                    <i class="fas fa-paper-plane absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs pointer-events-none"></i>
                </div>
                <button id="sendChat"
                        class="w-11 h-11 bg-gradient-to-r from-purple-600 to-purple-700 text-white rounded-xl hover:from-purple-700 hover:to-purple-800 transition-all shadow-lg hover:shadow-xl flex items-center justify-center group"
                        title="Gửi (Enter)" 
                        aria-label="Gửi tin nhắn">
                    <i class="fas fa-paper-plane text-sm group-hover:translate-x-0.5 group-hover:-translate-y-0.5 transition-transform"></i>
                </button>
            </div>
            <p class="text-xs text-gray-400 mt-2 text-center">Nhấn Enter để gửi</p>
        </div>
    </div>

    <!-- Chat bubble icon với animation -->
    <button id="openChat"
        class="fixed bottom-8 right-8 bg-gradient-to-r from-purple-600 to-purple-700 text-white rounded-full w-16 h-16 shadow-2xl flex items-center justify-center text-2xl hover:from-purple-700 hover:to-purple-800 transition-all hover:scale-110 z-30 group">
        <span class="group-hover:scale-110 transition-transform">💬</span>
        <span class="absolute -top-1 -right-1 w-4 h-4 bg-red-500 rounded-full border-2 border-white animate-pulse"></span>
    </button>
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            // Flash message handling
            const flashMessage = document.getElementById('flash-message');
            if (flashMessage) {
                setTimeout(() => {
                    flashMessage.style.opacity = '0';
                    setTimeout(() => {
                        flashMessage.remove();
                    }, 500);
                }, 3000);
            }

            // Back to top button
            const backToTopButton = document.getElementById('backToTop');

            window.addEventListener('scroll', () => {
                if (window.pageYOffset > 300) {
                    backToTopButton.style.display = 'block';
                } else {
                    backToTopButton.style.display = 'none';
                }
            });

            backToTopButton.addEventListener('click', () => {
                window.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });
            });

            // Product card animation on scroll
            const productCards = document.querySelectorAll('.product-card');
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.style.opacity = 1;
                        entry.target.style.transform = 'translateY(0)';
                        observer.unobserve(entry.target);
                    }
                });
            }, {
                threshold: 0.1
            });

            productCards.forEach(card => {
                card.style.opacity = 0;
                card.style.transform = 'translateY(20px)';
                card.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
                observer.observe(card);
            });
        });
    </script>
     

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const chatBox = document.getElementById('chatbot');
        const openChat = document.getElementById('openChat');
        const closeChat = document.getElementById('closeChat');
        const chatBody = document.getElementById('chatBody');
        const chatInput = document.getElementById('chatInput');
        const sendChat = document.getElementById('sendChat');

        // Dark mode preference
        const THEME_KEY = 'chat_theme';
        function applyTheme(theme){
            if(theme === 'dark'){ chatBox.classList.add('dark'); }
            else { chatBox.classList.remove('dark'); }
        }
        applyTheme(localStorage.getItem(THEME_KEY) || 'light');

        // Quick action buttons
        document.querySelectorAll('.quick-action-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                const action = btn.getAttribute('data-action');
                chatInput.value = action;
                sendMessage();
            });
        });

        // Toggle mở / đóng chat với animation
        openChat.addEventListener('click', () => {
            chatBox.classList.remove('hidden');
            chatBox.style.opacity = '0';
            chatBox.style.transform = 'translateY(20px) scale(0.95)';
            setTimeout(() => {
                chatBox.style.transition = 'all 0.3s ease-out';
                chatBox.style.opacity = '1';
                chatBox.style.transform = 'translateY(0) scale(1)';
            }, 10);
            openChat.classList.add('hidden');
            chatInput.focus();
        });

        closeChat.addEventListener('click', () => {
            chatBox.style.transition = 'all 0.3s ease-in';
            chatBox.style.opacity = '0';
            chatBox.style.transform = 'translateY(20px) scale(0.95)';
            setTimeout(() => {
                chatBox.classList.add('hidden');
                openChat.classList.remove('hidden');
            }, 300);
        });

        // Toggle theme
        const toggleThemeBtn = document.getElementById('toggleTheme');
        toggleThemeBtn.addEventListener('click', () => {
            const isDark = chatBox.classList.toggle('dark');
            localStorage.setItem(THEME_KEY, isDark ? 'dark' : 'light');
        });

        // Gửi tin nhắn
        async function sendMessage() {
            const message = chatInput.value.trim();
            if (!message) return;

            // Hiển thị tin nhắn người dùng (escape an toàn)
            const safeUser = message.replace(/</g, '&lt;').replace(/>/g, '&gt;');
            chatBody.innerHTML += `<div class="text-right"><span class="bubble-user inline-block bg-purple-100 text-gray-800 px-3 py-2 rounded-lg mb-1">${safeUser}</span></div>`;
            chatInput.value = '';
            chatInput.disabled = true;
            sendChat.disabled = true;

            // Loading bubble
            const loadingId = `ld-${Date.now()}`;
            chatBody.innerHTML += `<div id="${loadingId}"><span class="bubble-bot inline-block bg-gray-100 text-gray-600 px-3 py-2 rounded-lg mb-1 typing"><span class="dot"></span><span class="dot"></span><span class="dot"></span></span></div>`;
            chatBody.scrollTop = chatBody.scrollHeight;

            try {
                const res = await fetch('{{ route('chat.send') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ message })
                });

                if (!res.ok) {
                    const text = await res.text();
                    throw new Error(`Lỗi ${res.status}: ${text || 'Yêu cầu không thành công'}`);
                }

                const data = await res.json();
                const safeReply = String(data.reply ?? '').replace(/</g, '&lt;').replace(/>/g, '&gt;');
                document.getElementById(loadingId)?.remove();
                chatBody.innerHTML += `<div><span class=\"bubble-bot inline-block bg-gray-200 text-gray-800 px-3 py-2 rounded-lg mb-1\">${safeReply}</span></div>`;

                // Render product card if present
                if (data.product) {
                    const p = data.product;
                    const img = p.image ? `<img src=\"${p.image}\" alt=\"${p.name}\" class=\"w-12 h-12 object-cover rounded mr-3\">` : '';
                    const stock = p.stock > 0 ? `<span class=\"text-green-600\">Còn hàng: ${p.stock}</span>` : `<span class=\"text-red-600\">Hết hàng</span>`;
                    const card = `
                    <a href=\"${p.url}\" class=\"flex items-center p-3 mt-1 bg-white border border-gray-200 rounded-lg shadow-sm hover:shadow transition\">
                        ${img}
                        <div class=\"min-w-0\">
                            <div class=\"font-semibold text-gray-900 truncate\">${String(p.name ?? '').replace(/</g,'&lt;').replace(/>/g,'&gt;')}</div>
                            <div class=\"text-sm text-gray-700\">${p.display_price}</div>
                            <div class=\"text-xs mt-0.5\">${stock}</div>
                        </div>
                    </a>
                    <div class=\"flex gap-2 mt-2\">
                        <button class=\"qa-btn px-2 py-1 text-xs bg-purple-100 text-purple-700 rounded\" data-q=\"giá ${String(p.name ?? '').replace(/"/g,'&quot;')}\">Giá</button>
                        <button class=\"qa-btn px-2 py-1 text-xs bg-blue-100 text-blue-700 rounded\" data-q=\"${String(p.name ?? '').replace(/"/g,'&quot;')} còn hàng không\">Còn hàng?</button>
                        <button class=\"qa-btn px-2 py-1 text-xs bg-gray-100 text-gray-700 rounded\" data-q=\"mô tả ${String(p.name ?? '').replace(/"/g,'&quot;')}\">Mô tả</button>
                    </div>`;
                    chatBody.innerHTML += `<div>${card}</div>`;
                    // attach quick actions
                    chatBody.querySelectorAll('.qa-btn').forEach(btn => {
                        btn.addEventListener('click', () => {
                            const q = btn.getAttribute('data-q');
                            chatInput.value = q;
                            sendMessage();
                        });
                    });
                }

                // Render suggestions if any (as mini product cards grid)
                if (Array.isArray(data.suggestions) && data.suggestions.length) {
                    const cards = data.suggestions.map(p => {
                        const name = String(p.name ?? '').replace(/</g,'&lt;').replace(/>/g,'&gt;');
                        const img = p.image ? `<img src=\"${p.image}\" alt=\"${name}\" class=\"w-full h-20 object-cover rounded-t\">` : '';
                        const stock = p.stock > 0 ? `<span class=\"text-green-600\">Còn: ${p.stock}</span>` : `<span class=\"text-red-600\">Hết hàng</span>`;
                        return `
                        <div class=\"bg-white border border-gray-200 rounded-lg overflow-hidden shadow-sm\">
                            ${img}
                            <div class=\"p-2\">
                                <div class=\"text-sm font-semibold truncate\" title=\"${name}\">${name}</div>
                                <div class=\"text-xs text-gray-700\">${p.display_price}</div>
                                <div class=\"text-[11px] mt-0.5\">${stock}</div>
                                <div class=\"flex gap-1 mt-2\">
                                    <button class=\"sg-qa px-2 py-1 text-[11px] bg-purple-100 text-purple-700 rounded\" data-q=\"giá ${name}\">Giá</button>
                                    <button class=\"sg-qa px-2 py-1 text-[11px] bg-blue-100 text-blue-700 rounded\" data-q=\"${name} còn hàng không\">Còn hàng?</button>
                                    <button class=\"sg-qa px-2 py-1 text-[11px] bg-gray-100 text-gray-700 rounded\" data-q=\"mô tả ${name}\">Mô tả</button>
                                </div>
                                <a href=\"${p.url}\" class=\"inline-block mt-2 text-[11px] text-purple-700 hover:underline\">Xem chi tiết</a>
                            </div>
                        </div>`;
                    }).join('');
                    chatBody.innerHTML += `<div class=\"mt-2 grid grid-cols-2 gap-2\">${cards}</div>`;

                    chatBody.querySelectorAll('.sg-qa').forEach(btn => {
                        btn.addEventListener('click', () => {
                            const q = btn.getAttribute('data-q');
                            chatInput.value = q;
                            sendMessage();
                        });
                    });
                }
            } catch (err) {
                document.getElementById(loadingId)?.remove();
                const safeErr = String(err.message).replace(/</g, '&lt;').replace(/>/g, '&gt;');
                chatBody.innerHTML += `<div><span class="inline-block bg-red-100 text-red-700 px-3 py-2 rounded-lg mb-1">${safeErr}</span></div>`;
                console.error(err);
            } finally {
                chatBody.scrollTop = chatBody.scrollHeight;
                chatInput.disabled = false;
                sendChat.disabled = false;
                chatInput.focus();
            }
        }

        sendChat.addEventListener('click', sendMessage);
        chatInput.addEventListener('keydown', e => {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                sendMessage();
            }
            if ((e.ctrlKey || e.metaKey) && e.key.toLowerCase() === 'k') {
                e.preventDefault();
                chatInput.focus();
            }
        });
    });
    </script>

    {{-- Footer --}}
    @include('layouts.footer')
</body>

</html>