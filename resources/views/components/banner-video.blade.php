<section class="relative min-h-[60vh] flex items-center justify-center overflow-hidden bg-gradient-to-br from-gray-900 via-purple-900 to-violet-900 py-16">
    <!-- Animated Background Elements -->
    <div class="absolute inset-0">
        <!-- Gradient orbs -->
        <div class="absolute -top-20 -right-20 w-60 h-60 bg-pink-500 rounded-full mix-blend-multiply filter blur-3xl opacity-0 animate-fade-in-slow"></div>
        <div class="absolute -bottom-20 -left-20 w-60 h-60 bg-purple-500 rounded-full mix-blend-multiply filter blur-3xl opacity-0 animate-fade-in-slow" style="animation-delay: 0.5s;"></div>
    </div>

    <div class="relative max-w-7xl mx-auto px-6 lg:px-8 flex flex-col lg:flex-row items-center justify-between gap-8 z-10">
        
        <!-- Left text content -->
        <div class="text-center lg:text-left space-y-6 max-w-xl">
            <!-- Main heading -->
            <div class="space-y-3">
                <h1 class="text-4xl lg:text-5xl font-black text-white leading-tight opacity-0 animate-slide-up" style="animation-delay: 0.3s;">
                    <span class="bg-gradient-to-r from-pink-400 via-purple-400 to-indigo-400 bg-clip-text text-transparent">
                        Nâng Tầm
                    </span>
                    <br>
                    <span class="text-white">Trải Nghiệm Số</span>
                </h1>
                
                <!-- Subtitle -->
                <p class="text-lg text-gray-200 font-light opacity-0 animate-slide-up" style="animation-delay: 0.6s;">
                    iPhone 15 Pro Max • MacBook M3 • AirPods Pro 2 • Apple Watch
                </p>
            </div>

            <!-- Description -->
            <p class="text-gray-300 leading-relaxed opacity-0 animate-slide-up" style="animation-delay: 0.9s;">
                Khám phá thế giới công nghệ với những sản phẩm đỉnh cao. 
                <span class="text-pink-300 font-semibold">Giảm giá lên đến 30%</span> 
                cho đơn hàng đầu tiên!
            </p>

            <!-- CTA Buttons -->
            <div class="flex flex-col sm:flex-row gap-3 pt-2 opacity-0 animate-slide-up" style="animation-delay: 1.2s;">
                <a href="{{ route('products.index') }}"
                   class="group relative px-6 py-3 bg-gradient-to-r from-pink-500 to-purple-600 text-white font-bold rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-105 overflow-hidden">
                    <span class="relative z-10 flex items-center justify-center gap-2">
                        <i class="fas fa-bolt"></i>
                        MUA NGAY
                        <i class="fas fa-arrow-right group-hover:translate-x-1 transition-transform"></i>
                    </span>
                </a>
                
                <a href="#features"
                   class="group px-6 py-3 border border-white/30 text-white font-semibold rounded-xl hover:bg-white/10 transition-all duration-300 backdrop-blur-sm">
                    <span class="flex items-center justify-center gap-2">
                        <i class="fas fa-play-circle"></i>
                        XEM DEMO
                    </span>
                </a>
            </div>

            <!-- Stats -->
            <div class="flex flex-wrap gap-4 pt-4 justify-center lg:justify-start opacity-0 animate-slide-up" style="animation-delay: 1.5s;">
                <div class="text-center" data-count="50">
                    <div class="text-lg font-bold text-white count-value">0</div>
                    <div class="text-gray-400 text-xs">Sản phẩm</div>
                </div>
                <div class="text-center" data-count="4.9">
                    <div class="text-lg font-bold text-white count-value">0</div>
                    <div class="text-gray-400 text-xs">Đánh giá</div>
                </div>
                <div class="text-center" data-count="24">
                    <div class="text-lg font-bold text-white count-value">0</div>
                    <div class="text-gray-400 text-xs">Hỗ trợ</div>
                </div>
            </div>
        </div>

        <!-- Right: Compact Product Showcase -->
        <div class="relative lg:w-2/5 mt-8 lg:mt-0">
            <!-- Main device -->
            <div class="relative w-64 h-64 mx-auto lg:mx-0 opacity-0 animate-zoom-in" style="animation-delay: 1s;">
                <div class="absolute inset-0 device-3d">
                    <div class="w-full h-full bg-gradient-to-br from-gray-800 to-gray-900 rounded-2xl shadow-xl border border-gray-700/50 transform-style-3d transition-transform duration-1000">
                        <!-- Screen content -->
                        <div class="absolute inset-3 bg-gradient-to-br from-purple-900 to-pink-800 rounded-xl overflow-hidden">
                            <!-- App icons -->
                            <div class="absolute top-4 left-4 w-8 h-8 bg-white/20 rounded-xl"></div>
                            <div class="absolute top-4 right-4 w-8 h-8 bg-white/20 rounded-xl"></div>
                            <div class="absolute bottom-4 left-4 w-8 h-8 bg-white/20 rounded-xl"></div>
                            <div class="absolute bottom-4 right-4 w-8 h-8 bg-white/20 rounded-xl"></div>
                            
                            <!-- Central logo -->
                            <div class="absolute inset-0 flex items-center justify-center">
                                <div class="text-white text-center">
                                    <div class="text-2xl font-bold mb-1">SOP</div>
                                    <div class="text-xs opacity-80">Innovation</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Floating accessories -->
            <div class="absolute -top-2 -right-2 w-12 h-12 bg-gradient-to-r from-blue-500 to-cyan-400 rounded-xl shadow-lg transform rotate-12 opacity-0 animate-bounce-in" style="animation-delay: 1.8s;"></div>
            <div class="absolute -bottom-2 -left-2 w-10 h-10 bg-gradient-to-r from-green-400 to-emerald-500 rounded-xl shadow-lg transform -rotate-12 opacity-0 animate-bounce-in" style="animation-delay: 2s;"></div>
        </div>
    </div>
</section>

<style>
/* 3D Device animation */
.device-3d {
    animation: float-3d 6s ease-in-out infinite;
}

@keyframes float-3d {
    0%, 100% { transform: translateY(0px) rotateX(5deg) rotateY(5deg); }
    50% { transform: translateY(-10px) rotateX(-5deg) rotateY(-5deg); }
}

/* Floating animation */
@keyframes float {
    0%, 100% { transform: translateY(0) rotate(12deg); }
    50% { transform: translateY(-10px) rotate(12deg); }
}

.animate-float {
    animation: float 3s ease-in-out infinite;
}

/* Slow pulse */
@keyframes pulse-slow {
    0%, 100% { opacity: 0.2; }
    50% { opacity: 0.3; }
}

.animate-pulse-slow {
    animation: pulse-slow 4s cubic-bezier(0.4, 0, 0.6, 1) infinite;
}

/* 3D transform */
.transform-style-3d {
    transform-style: preserve-3d;
}

/* === HIỆU ỨNG XUẤT HIỆN MỚI === */

/* Fade in từ từ */
@keyframes fade-in-slow {
    from {
        opacity: 0;
    }
    to {
        opacity: 0.2;
    }
}

.animate-fade-in-slow {
    animation: fade-in-slow 2s ease-out forwards;
}

/* Trượt từ trên xuống */
@keyframes slide-down {
    from {
        opacity: 0;
        transform: translateY(-30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.animate-slide-down {
    animation: slide-down 0.8s ease-out forwards;
}

/* Trượt từ dưới lên */
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
    animation: slide-up 0.8s ease-out forwards;
}

/* Zoom in */
@keyframes zoom-in {
    from {
        opacity: 0;
        transform: scale(0.8);
    }
    to {
        opacity: 1;
        transform: scale(1);
    }
}

.animate-zoom-in {
    animation: zoom-in 1s ease-out forwards;
}

/* Bounce in */
@keyframes bounce-in {
    0% {
        opacity: 0;
        transform: scale(0.3) rotate(12deg);
    }
    50% {
        opacity: 1;
        transform: scale(1.1) rotate(12deg);
    }
    70% {
        transform: scale(0.9) rotate(12deg);
    }
    100% {
        opacity: 1;
        transform: scale(1) rotate(12deg);
    }
}

.animate-bounce-in {
    animation: bounce-in 0.8s ease-out forwards;
    animation-iteration-count: 1;
}

/* Fade in đơn giản */
@keyframes fade-in {
    from {
        opacity: 0;
    }
    to {
        opacity: 1;
    }
}

.animate-fade-in {
    animation: fade-in 1s ease-out forwards;
}

/* Responsive adjustments */
@media (max-width: 1024px) {
    .min-h-[70vh] {
        min-height: 60vh;
    }
}

@media (max-width: 768px) {
    .min-h-[70vh] {
        min-height: 50vh;
    }
    
    /* Hiệu ứng nhanh hơn trên mobile */
    .animate-slide-up,
    .animate-slide-down,
    .animate-zoom-in {
        animation-duration: 0.6s;
    }
}
</style>

<script>
document.addEventListener("DOMContentLoaded", function() {
    // Interactive 3D device rotation
    const device = document.querySelector('.device-3d');
    
    if (device) {
        let isInteracting = false;
        
        device.addEventListener('mouseenter', () => {
            isInteracting = true;
        });
        
        device.addEventListener('mouseleave', () => {
            isInteracting = false;
            device.style.transform = '';
        });
        
        document.addEventListener('mousemove', (e) => {
            if (!isInteracting) return;
            
            const x = (window.innerWidth / 2 - e.pageX) / 30;
            const y = (window.innerHeight / 2 - e.pageY) / 30;
            
            device.style.transform = `translateY(-10px) rotateY(${x}deg) rotateX(${y}deg)`;
        });
    }

    // Counter animation - chỉ chạy sau khi hiệu ứng xuất hiện hoàn tất
    setTimeout(() => {
        function animateCounter(element, target, suffix = '') {
            let current = 0;
            const increment = target / 30;
            const timer = setInterval(() => {
                current += increment;
                if (current >= target) {
                    element.textContent = target + suffix;
                    clearInterval(timer);
                } else {
                    if (target % 1 !== 0) {
                        element.textContent = current.toFixed(1) + suffix;
                    } else {
                        element.textContent = Math.floor(current) + suffix;
                    }
                }
            }, 50);
        }

        const counters = document.querySelectorAll('[data-count]');
        counters.forEach(counter => {
            const countValue = counter.querySelector('.count-value');
            const target = parseFloat(counter.getAttribute('data-count'));
            
            if (countValue && !isNaN(target)) {
                animateCounter(countValue, target);
            }
        });
    }, 2000); // Chờ 2 giây để các hiệu ứng xuất hiện hoàn tất
});
</script>