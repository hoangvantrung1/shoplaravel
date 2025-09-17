<section class="relative bg-gradient-to-r from-purple-900 via-purple-800 to-fuchsia-800 py-20 overflow-hidden">
    <div class="max-w-7xl mx-auto px-6 lg:px-8 flex flex-col md:flex-row items-center justify-between gap-12">
        
        <!-- Left text -->
        <div class="text-center md:text-left space-y-6 max-w-lg">
            <h2 class="text-4xl md:text-5xl font-extrabold text-white leading-tight">
                Sắm <span class="text-pink-400">Đồ Điện Tử</span> Thông Minh
            </h2>
            <p class="text-gray-200 text-lg">
                Điện thoại, laptop, phụ kiện công nghệ chính hãng. Ưu đãi cực lớn, giao hàng nhanh chóng!
            </p>
            <a href="{{ route('products.index') }}"
               class="inline-block px-8 py-3 bg-pink-600 hover:bg-pink-700 text-white font-semibold rounded-full shadow-lg transition duration-300">
                Mua Ngay
            </a>
        </div>

        <!-- Right: Phones -->
        <div class="relative flex justify-center items-end gap-6">
            
            <!-- Phone 1 -->
            <div class="relative w-32 h-64 bg-black rounded-[2rem] shadow-2xl overflow-hidden border-[6px] border-gray-900 phone opacity-0 translate-y-20 transition-all duration-1000 ease-out">
                <div class="absolute inset-0 bg-gradient-to-br from-pink-500 via-fuchsia-600 to-purple-700"></div>
                <div class="absolute top-0 inset-x-0 h-5 bg-black rounded-b-lg"></div>
                <div class="absolute inset-0 flex flex-col items-center justify-center space-y-4">
                    <i class="fas fa-camera text-white text-3xl icon opacity-0 translate-y-6"></i>
                    <i class="fas fa-video text-white text-3xl icon opacity-0 translate-y-6"></i>
                </div>
            </div>

            <!-- Phone 2 -->
            <div class="relative w-40 h-80 bg-black rounded-[2rem] shadow-2xl overflow-hidden border-[6px] border-gray-900 phone opacity-0 translate-y-20 transition-all duration-1000 ease-out delay-300">
                <div class="absolute inset-0 bg-gradient-to-br from-fuchsia-500 via-pink-600 to-purple-700"></div>
                <div class="absolute top-0 inset-x-0 h-6 bg-black rounded-b-lg"></div>
                <div class="absolute inset-0 flex flex-col items-center justify-center space-y-4">
                    <i class="fas fa-shopping-cart text-white text-3xl icon opacity-0 translate-y-6"></i>
                    <i class="fas fa-credit-card text-white text-3xl icon opacity-0 translate-y-6"></i>
                </div>
            </div>

            <!-- Phone 3 -->
            <div class="relative w-32 h-64 bg-black rounded-[2rem] shadow-2xl overflow-hidden border-[6px] border-gray-900 phone opacity-0 translate-y-20 transition-all duration-1000 ease-out delay-600">
                <div class="absolute inset-0 bg-gradient-to-br from-purple-500 via-fuchsia-600 to-pink-700"></div>
                <div class="absolute top-0 inset-x-0 h-5 bg-black rounded-b-lg"></div>
                <div class="absolute inset-0 flex flex-col items-center justify-center space-y-4">
                    <i class="fas fa-truck text-white text-3xl icon opacity-0 translate-y-6"></i>
                    <i class="fas fa-gift text-white text-3xl icon opacity-0 translate-y-6"></i>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Font Awesome -->
<script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>

<style>
/* Animation lắc qua lại */
@keyframes shakeRotate {
    0%   { transform: rotate(0deg); }
    25%  { transform: rotate(-8deg); }
    50%  { transform: rotate(8deg); }
    75%  { transform: rotate(-6deg); }
    100% { transform: rotate(0deg); }
}
.icon.shake {
    animation: shakeRotate 1.8s ease-in-out infinite;
}
</style>

<script>
document.addEventListener("DOMContentLoaded", () => {
    const phones = document.querySelectorAll(".phone");

    phones.forEach((phone, i) => {
        setTimeout(() => {
            phone.classList.remove("opacity-0", "translate-y-20");

            const icons = phone.querySelectorAll(".icon");
            icons.forEach((icon, j) => {
                setTimeout(() => {
                    icon.classList.remove("opacity-0", "translate-y-6");
                    icon.classList.add("shake"); // lắc liên tục
                }, j * 500);
            });

        }, i * 800);
    });
});
</script>
