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
    <main class="flex-1 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        @yield('content')

        {{-- Danh mục sản phẩm --}}

        {{-- Sản phẩm mới --}}
        <section class="py-12 bg-gray-50">
            <div class="max-w-7xl mx-auto px-6">
                <div class="flex justify-between items-center mb-8">
                    <h2 class="text-3xl font-bold text-gray-800 animate-fadeUp">
                        Sản phẩm mới
                    </h2>
                    <a href="{{ route('products.index') }}" class="text-purple-600 hover:text-purple-800 font-semibold flex items-center">
                        Xem tất cả <i class="fas fa-arrow-right ml-2"></i>
                    </a>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                    {{-- Loop through new products --}}
                    @foreach($newProducts as $product)
                        <div class="product-card bg-white rounded-xl shadow-md overflow-hidden flex flex-col h-full">
                            <a href="{{ route('product.show', $product->slug) }}">
                                <div class="h-48 w-full relative">
                                    <img src="{{ $product->image }}" alt="{{ $product->name }}"
                                        class="w-full h-full object-cover transition-transform duration-300 hover:scale-105">
                                    @if($product->discount > 0)
                                        <span class="absolute top-3 left-3 bg-red-500 text-white text-xs font-bold px-2 py-1 rounded">
                                            -{{ $product->discount }}%
                                        </span>
                                    @endif
                                </div>
                                <div class="p-4 flex-1 flex flex-col justify-between">
                                    <h3 class="text-lg font-bold text-gray-800 mb-2 line-clamp-1">{{ $product->name }}</h3>
                                    <div class="flex items-center mb-2">
                                        <div class="flex text-yellow-400">
                                            @for($i = 1; $i <= 5; $i++)
                                                @if($i <= round($product->rating))
                                                    <i class="fas fa-star"></i>
                                                @else
                                                    <i class="far fa-star"></i>
                                                @endif
                                            @endfor
                                        </div>
                                        <span class="text-gray-500 text-sm ml-2">({{ $product->review_count }})</span>
                                    </div>
                                    <div class="flex items-center justify-between mt-2">
                                        <p class="text-purple-600 font-semibold">
                                            {{ number_format($product->price - ($product->price * $product->discount / 100), 0, ',', '.') }}₫
                                        </p>
                                        @if($product->discount > 0)
                                            <p class="text-gray-400 text-sm line-through">
                                                {{ number_format($product->price, 0, ',', '.') }}₫
                                            </p>
                                        @endif
                                    </div>
                                    <button class="mt-4 bg-purple-600 hover:bg-purple-700 text-white py-2 px-4 rounded-lg transition-all w-full">
                                        <i class="fas fa-shopping-cart mr-2"></i> Mua ngay
                                    </button>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>

        {{-- Sản phẩm bán chạy --}}

        {{-- Thương hiệu nổi bật --}}
        <section class="py-12 bg-gray-50">
            <div class="max-w-7xl mx-auto px-6">
                <h2 class="text-3xl font-bold mb-8 text-center text-gray-800 animate-fadeUp">
                    Thương hiệu nổi bật
                </h2>

                <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-6">
                    <div class="brand-logo bg-white p-6 rounded-xl shadow-sm flex items-center justify-center">
                        <img src="https://upload.wikimedia.org/wikipedia/commons/f/fa/Apple_logo_black.svg" alt="Apple" class="h-10">
                    </div>
                    <div class="brand-logo bg-white p-6 rounded-xl shadow-sm flex items-center justify-center">
                        <img src="https://upload.wikimedia.org/wikipedia/commons/2/24/Samsung_Logo.svg" alt="Samsung" class="h-10">
                    </div>
                    <div class="brand-logo bg-white p-6 rounded-xl shadow-sm flex items-center justify-center">
                        <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/a/ae/Xiaomi_logo_%282021-%29.svg/512px-Xiaomi_logo_%282021-%29.svg.png" alt="Xiaomi" class="h-10">
                    </div>
                    <div class="brand-logo bg-white p-6 rounded-xl shadow-sm flex items-center justify-center">
                        <img src="https://upload.wikimedia.org/wikipedia/en/thumb/0/04/Huawei_Standard_logo.svg/2016px-Huawei_Standard_logo.svg.png" alt="Huawei" class="h-8">
                    </div>
                    <div class="brand-logo bg-white p-6 rounded-xl shadow-sm flex items-center justify-center">
                        <img src="https://e7.pngegg.com/pngimages/699/661/png-clipart-jbl-logo-jbl-logo-icons-logos-emojis-shop-logos-thumbnail.png" alt="JBL" class="h-8">
                    </div>
                    <div class="brand-logo bg-white p-6 rounded-xl shadow-sm flex items-center justify-center">
                        <img src="https://pluspng.com/logo-img/so142son03e9-sony-logo-sony-logo-png-transparent-amp-svg-vector-freebie-supply.png" alt="Sony" class="h-8">
                    </div>
                </div>
            </div>
        </section>

        {{-- Tại sao chọn chúng tôi --}}
        <section class="py-12 bg-white">
            <div class="max-w-7xl mx-auto px-6">
                <h2 class="text-3xl font-bold mb-8 text-center text-gray-800 animate-fadeUp">Tại sao chọn chúng tôi?</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                    <div class="p-6 bg-gray-100 rounded-lg shadow-md hover:shadow-xl transition-all text-center">
                        <div class="text-4xl text-purple-600 mb-4">
                            <i class="fas fa-award"></i>
                        </div>
                        <h3 class="text-xl font-semibold mb-2">Chất lượng cao</h3>
                        <p class="text-gray-600">Sản phẩm chính hãng, được kiểm tra nghiêm ngặt, đảm bảo chất lượng hàng đầu.</p>
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
        <section class="py-12 bg-white">
            <div class="max-w-7xl mx-auto px-6">
                <h2 class="text-2xl font-bold mb-8 text-center text-gray-800 animate-fadeUp">
                    Bài viết & Media nổi bật
                </h2>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    <a href="#" class="bg-white rounded-xl shadow-md overflow-hidden flex flex-col hover:shadow-lg transition h-full">
                        <div class="h-48 w-full relative">
                            <video class="w-full h-full object-cover" autoplay muted loop>
                                <source src="/videos/banner.mp4" type="video/mp4">
                            </video>
                            <div class="absolute bottom-0 left-0 bg-purple-600 text-white text-sm font-semibold px-3 py-1">
                                <i class="fas fa-video mr-1"></i> Video
                            </div>
                        </div>
                        <div class="p-6 flex-1 flex flex-col justify-between">
                            <div>
                                <h3 class="text-lg font-bold text-gray-800 mb-2">Video Review iPhone 16 Pro Max</h3>
                                <p class="text-gray-600 mb-4">Đánh giá chi tiết những tính năng mới nhất trên iPhone 16 Pro Max sau 1 tháng sử dụng.</p>
                            </div>
                            <div class="flex items-center justify-between mt-4">
                                <span class="text-purple-600 font-semibold hover:underline">Xem chi tiết</span>
                                <span class="text-gray-400 text-sm">20/10/2024</span>
                            </div>
                        </div>
                    </a>

                    <a href="#" class="bg-white rounded-xl shadow-md overflow-hidden flex flex-col hover:shadow-lg transition h-full">
                        <div class="h-48 w-full relative">
                            <img src="/images/AirPods.jpg" alt="Sản phẩm mới" class="w-full h-full object-cover">
                            <div class="absolute bottom-0 left-0 bg-green-600 text-white text-sm font-semibold px-3 py-1">
                                <i class="fas fa-newspaper mr-1"></i> Bài viết
                            </div>
                        </div>
                        <div class="p-6 flex-1 flex flex-col justify-between">
                            <div>
                                <h3 class="text-lg font-bold text-gray-800 mb-2">AirPods Pro 3 - Nâng cấp đáng giá</h3>
                                <p class="text-gray-600 mb-4">Trải nghiệm âm thanh không gian và chống ồn chủ động cải tiến trên AirPods Pro thế hệ mới.</p>
                            </div>
                            <div class="flex items-center justify-between mt-4">
                                <span class="text-purple-600 font-semibold hover:underline">Xem chi tiết</span>
                                <span class="text-gray-400 text-sm">15/10/2023</span>
                            </div>
                        </div>
                    </a>

                    <a href="#" class="bg-white rounded-xl shadow-md overflow-hidden flex flex-col hover:shadow-lg transition h-full">
                        <div class="h-48 w-full relative">
                            <img src="/images/webcongnghe.jpg" alt="Phỏng vấn" class="w-full h-full object-cover">
                            <div class="absolute bottom-0 left-0 bg-blue-600 text-white text-sm font-semibold px-3 py-1">
                                <i class="fas fa-microphone-alt mr-1"></i> Podcast
                            </div>
                        </div>
                        <div class="p-6 flex-1 flex flex-col justify-between">
                            <div>
                                <h3 class="text-lg font-bold text-gray-800 mb-2">Xu hướng công nghệ 2024</h3>
                                <p class="text-gray-600 mb-4">Chuyên gia công nghệ chia sẻ về những xu hướng đáng chú ý trong năm tới.</p>
                            </div>
                            <div class="flex items-center justify-between mt-4">
                                <span class="text-purple-600 font-semibold hover:underline">Xem chi tiết</span>
                                <span class="text-gray-400 text-sm">10/10/2023</span>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </section>

        {{-- Newsletter --}}
        <section class="py-12 bg-purple-600 text-white">
            <div class="max-w-5xl mx-auto px-6 text-center">
                <h2 class="text-3xl font-bold mb-4 animate-fadeUp">Đăng ký nhận thông báo</h2>
                <p class="mb-8 max-w-2xl mx-auto">Nhận thông tin về sản phẩm mới, khuyến mãi đặc biệt và các sự kiện công nghệ độc quyền</p>
                
                <form class="newsletter-form flex flex-col sm:flex-row gap-4 max-w-md mx-auto">
                    <input type="email" placeholder="Email của bạn" class="flex-1 px-4 py-3 rounded-lg text-gray-800 focus:ring-2 focus:ring-white focus:ring-opacity-50" required>
                    <button type="submit" class="bg-white text-purple-600 font-semibold px-6 py-3 rounded-lg hover:bg-gray-100 transition-all animate-pulse-slow">
                        Đăng ký ngay
                    </button>
                </form>
                
                <p class="text-sm text-purple-200 mt-4">Chúng tôi tôn trọng quyền riêng tư của bạn và không chia sẻ thông tin với bên thứ ba.</p>
            </div>
        </section>
    </main>

    {{-- Back to top button --}}
    <button id="backToTop" class="back-to-top">
        <i class="fas fa-arrow-up"></i>
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

    {{-- Footer --}}
    @include('layouts.footer')
</body>

</html>