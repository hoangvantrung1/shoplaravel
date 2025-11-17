<section class="relative min-h-[60vh] sm:min-h-[65vh] md:min-h-[70vh] lg:min-h-[75vh] flex items-center justify-center overflow-hidden bg-gray-50 banner-section">
    <!-- Elegant Background decorative elements -->
    <div class="absolute inset-0 overflow-hidden pointer-events-none">
        <!-- Subtle gradient orbs -->
        <div class="absolute top-0 -right-40 w-[500px] h-[500px] bg-purple-50 rounded-full blur-3xl opacity-20"></div>
        <div class="absolute bottom-0 -left-40 w-[500px] h-[500px] bg-purple-100/50 rounded-full blur-3xl opacity-25"></div>
        <!-- Subtle grid pattern -->
        <div class="absolute inset-0 opacity-[0.015]" style="background-image: linear-gradient(rgba(0,0,0,0.08) 1px, transparent 1px), linear-gradient(90deg, rgba(0,0,0,0.08) 1px, transparent 1px); background-size: 60px 60px;"></div>
    </div>
    
    <div class="relative w-full max-w-7xl mx-auto px-3 sm:px-4 md:px-6 lg:px-8 py-8 sm:py-12 md:py-16 lg:py-20 z-10">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 sm:gap-8 md:gap-10 lg:gap-12 items-center">
            
            <!-- Left: Text Content - Tạo sự tò mò -->
            <div class="text-center lg:text-left space-y-4 sm:space-y-5 md:space-y-6 opacity-0 animate-slide-up" style="animation-delay: 0.1s;">
                <!-- Elegant Badge -->
                <div class="inline-flex items-center gap-2.5 px-5 py-2.5 bg-white/90 backdrop-blur-md border border-gray-200/80 rounded-full shadow-sm opacity-0 animate-fade-in" style="animation-delay: 0.2s;">
                    <span class="w-2 h-2 bg-emerald-500 rounded-full"></span>
                    <span class="text-xs sm:text-sm text-gray-700 font-medium tracking-wide">Hơn 1000+ khách hàng tin dùng</span>
                </div>
                
                <!-- Câu hỏi gợi mở -->
                <div class="space-y-3 sm:space-y-4">
                    <p class="text-xs sm:text-sm md:text-base text-gray-500 font-light tracking-wider uppercase">
                        Bạn đã sẵn sàng?
                    </p>
                    <h1 class="text-4xl sm:text-5xl md:text-6xl lg:text-7xl xl:text-8xl font-light leading-[1.08] tracking-tight" style="font-family: 'Playfair Display', serif;">
                        <span class="block text-gray-900">Trải nghiệm</span>
                        <span class="block font-normal bg-gradient-to-r from-purple-600 via-purple-700 to-purple-600 bg-clip-text text-transparent animate-gradient">
                            Công nghệ
                        </span>
                        <span class="block text-gray-500">Đỉnh cao</span>
                    </h1>
                </div>
                
                <!-- Mô tả ngắn gọn, gợi mở -->
                <p class="text-base sm:text-lg md:text-xl text-gray-600 font-light leading-relaxed max-w-xl mx-auto lg:mx-0 px-2 sm:px-0 tracking-wide">
                    Khám phá những gì bạn chưa biết về công nghệ hiện đại. 
                    <span class="text-gray-500 italic">Điều gì đang chờ đợi bạn?</span>
                </p>
                
                <!-- Elegant Stats -->
                <div class="flex flex-wrap gap-6 sm:gap-8 md:gap-10 justify-center lg:justify-start pt-2 opacity-0 animate-fade-in" style="animation-delay: 0.4s;">
                    <div class="text-center">
                        <div class="text-2xl sm:text-3xl md:text-4xl font-light text-gray-900 tracking-tight">50+</div>
                        <div class="text-xs sm:text-sm text-gray-500 font-light mt-1 tracking-wide">Sản phẩm</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl sm:text-3xl md:text-4xl font-light text-gray-900 tracking-tight">4.9</div>
                        <div class="text-xs sm:text-sm text-gray-500 font-light mt-1 tracking-wide">Đánh giá</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl sm:text-3xl md:text-4xl font-light text-gray-900 tracking-tight">24/7</div>
                        <div class="text-xs sm:text-sm text-gray-500 font-light mt-1 tracking-wide">Hỗ trợ</div>
                    </div>
                </div>
                
                <!-- Elegant CTA Buttons -->
                <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start pt-2">
                    <a href="{{ route('products.index') }}" 
                       class="group relative px-8 sm:px-10 md:px-12 py-3.5 sm:py-4 md:py-4.5 bg-gradient-to-r from-purple-600 to-purple-700 text-white text-sm sm:text-base font-medium rounded-xl hover:from-purple-700 hover:to-purple-800 transition-all duration-300 inline-flex items-center justify-center gap-2.5 overflow-hidden w-full sm:w-auto shadow-xl hover:shadow-2xl hover:-translate-y-0.5">
                        <span class="relative z-10 tracking-wide">Khám phá ngay</span>
                        <i class="fas fa-arrow-right text-xs sm:text-sm relative z-10 group-hover:translate-x-1 transition-transform duration-300"></i>
                        <!-- Elegant shine effect -->
                        <span class="absolute inset-0 bg-gradient-to-r from-transparent via-white/25 to-transparent -translate-x-full group-hover:translate-x-full transition-transform duration-1000"></span>
                    </a>
                    <a href="#about" 
                       class="px-8 sm:px-10 md:px-12 py-3.5 sm:py-4 md:py-4.5 border-2 border-gray-300 text-gray-700 text-sm sm:text-base font-medium rounded-xl hover:bg-white hover:border-purple-400 hover:text-purple-700 transition-all duration-300 inline-flex items-center justify-center w-full sm:w-auto hover:shadow-lg hover:-translate-y-0.5">
                        <span class="tracking-wide">Tìm hiểu thêm</span>
                    </a>
                </div>
            </div>
            
            <!-- Right: Product Image - Sang trọng và tinh tế -->
            <div class="relative opacity-0 animate-fade-in mt-6 lg:mt-0" style="animation-delay: 0.3s;">
                <div class="relative w-full aspect-square max-w-[280px] sm:max-w-sm md:max-w-md lg:max-w-lg mx-auto lg:mx-0">
                    <!-- Subtle glow effect -->
                    <div class="absolute inset-0 bg-gradient-to-br from-purple-200/30 via-purple-100/20 to-transparent rounded-3xl blur-2xl -z-10"></div>
                    
                    <!-- Main image với elegant frame -->
                    <div class="relative w-full h-full rounded-2xl sm:rounded-3xl overflow-hidden group cursor-pointer shadow-2xl">
                        <!-- Elegant border -->
                        <div class="absolute inset-0 rounded-2xl sm:rounded-3xl border-2 border-white/80 shadow-inner"></div>
                        
                        <img src="https://images.unsplash.com/photo-1511707171634-5f897ff02aa9?w=800&q=80" 
                             alt="Premium Technology" 
                             class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-[1.02]">
                        
                        <!-- Subtle gradient overlay -->
                        <div class="absolute inset-0 bg-gradient-to-t from-gray-900/20 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                        
                        <!-- Elegant shine effect -->
                        <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/5 to-transparent -translate-x-full group-hover:translate-x-full transition-transform duration-1000"></div>
                    </div>
                    
                    <!-- Elegant frame decoration -->
                    <div class="absolute -inset-1 rounded-2xl bg-gradient-to-br from-purple-100/50 via-transparent to-purple-50/50 opacity-60 -z-10 blur-sm"></div>
                    
                    <!-- Subtle decorative elements -->
                    <div class="absolute -bottom-6 -left-6 w-32 h-32 bg-purple-50 rounded-full blur-3xl opacity-30 -z-10"></div>
                    <div class="absolute -top-6 -right-6 w-28 h-28 bg-purple-100 rounded-full blur-3xl opacity-20 -z-10"></div>
                </div>
            </div>
        </div>
        
        <!-- Scroll hint - Tạo sự tò mò về nội dung bên dưới -->
        <div class="absolute bottom-2 sm:bottom-4 md:bottom-6 left-1/2 -translate-x-1/2 flex flex-col items-center gap-1 sm:gap-1.5 text-gray-400 opacity-0 animate-fade-in cursor-pointer group" 
             style="animation-delay: 0.8s;"
             onclick="window.scrollTo({top: window.innerHeight, behavior: 'smooth'})">
            <span class="text-[10px] sm:text-xs font-light">Cuộn để khám phá</span>
            <div class="w-5 h-8 sm:w-6 sm:h-10 border-2 border-gray-300 rounded-full flex justify-center pt-1.5 sm:pt-2 group-hover:border-purple-400 transition-colors">
                <div class="w-1 h-1 sm:w-1.5 sm:h-1.5 bg-purple-400 rounded-full animate-bounce"></div>
            </div>
        </div>
    </div>
    
    <!-- Elegant Divider - Ngăn cách với phần khác -->
    <div class="absolute bottom-0 left-0 right-0 z-20">
        <!-- Main divider line -->
        <div class="h-[1px] bg-gradient-to-r from-transparent via-gray-300/60 to-transparent"></div>
        <!-- Subtle shadow for depth -->
        <div class="h-[1px] bg-white/80 mt-[-1px]"></div>
    </div>
</section>

<style>
/* === IMPORT FONT === */
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Playfair+Display:wght@400;500;600;700&display=swap');

/* === BANNER FONT === */
.banner-section {
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
}

.banner-section h1,
.banner-section h2,
.banner-section h3 {
    font-family: 'Playfair Display', serif;
}

.banner-section .font-light {
    font-weight: 300;
}

.banner-section .font-normal {
    font-weight: 400;
}

.banner-section .font-medium {
    font-weight: 500;
}
/* === FADE IN === */
@keyframes fade-in {
    from {
        opacity: 0;
    }
    to {
        opacity: 1;
    }
}

.animate-fade-in {
    animation: fade-in 0.8s ease-out forwards;
}

/* === SLIDE UP === */
@keyframes slide-up {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.animate-slide-up {
    animation: slide-up 0.7s ease-out forwards;
    will-change: opacity, transform;
}

/* === BOUNCE === */
@keyframes bounce {
    0%, 100% {
        transform: translateY(0);
    }
    50% {
        transform: translateY(6px);
    }
}

.animate-bounce {
    animation: bounce 2s ease-in-out infinite;
}

/* === GRADIENT ANIMATION === */
@keyframes gradient {
    0%, 100% {
        background-position: 0% 50%;
    }
    50% {
        background-position: 100% 50%;
    }
}

.animate-gradient {
    background-size: 200% 200%;
    animation: gradient 3s ease infinite;
}

/* === PULSE SLOW === */
@keyframes pulse-slow {
    0%, 100% {
        opacity: 0.3;
        transform: scale(1);
    }
    50% {
        opacity: 0.5;
        transform: scale(1.05);
    }
}

.animate-pulse-slow {
    animation: pulse-slow 3s ease-in-out infinite;
}

/* === BANNER SEPARATOR === */
.banner-section::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    height: 1px;
    background: linear-gradient(to right, transparent, rgba(156, 163, 175, 0.3), transparent);
}

/* === RESPONSIVE ADJUSTMENTS === */

/* Extra Small Mobile (< 375px) */
@media (max-width: 374px) {
    section {
        min-height: 55vh;
    }
    
    .animate-slide-up,
    .animate-fade-in {
        animation-duration: 0.6s;
    }
}

/* Small Mobile (375px - 639px) */
@media (min-width: 375px) and (max-width: 639px) {
    section {
        min-height: 60vh;
    }
    
    .animate-slide-up,
    .animate-fade-in {
        animation-duration: 0.7s;
    }
}

/* Tablet (640px - 1023px) */
@media (min-width: 640px) and (max-width: 1023px) {
    section {
        min-height: 65vh;
    }
    
    .animate-slide-up,
    .animate-fade-in {
        animation-duration: 0.8s;
    }
}

/* Desktop (1024px+) */
@media (min-width: 1024px) {
    section {
        min-height: 70vh;
    }
}

/* Large Desktop (1280px+) */
@media (min-width: 1280px) {
    section {
        min-height: 75vh;
    }
}

/* === PERFORMANCE OPTIMIZATION === */
@media (prefers-reduced-motion: reduce) {
    *,
    *::before,
    *::after {
        animation-duration: 0.01ms !important;
        animation-iteration-count: 1 !important;
        transition-duration: 0.01ms !important;
    }
}
</style>
