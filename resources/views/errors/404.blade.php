@extends('layouts.client')

@section('title', '404 - Trang không tìm thấy')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 via-purple-50/30 to-indigo-50/30 pt-20 pb-12 flex items-center">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <div class="animate-fadeUp">
            {{-- Icon và số lỗi --}}
            <div class="mb-8">
                <div class="inline-flex items-center justify-center w-32 h-32 sm:w-40 sm:h-40 bg-gradient-to-br from-purple-100 to-indigo-100 rounded-full mb-6 animate-bounce">
                    <i class="fas fa-exclamation-triangle text-5xl sm:text-6xl text-purple-600"></i>
                </div>
                <h1 class="text-8xl sm:text-9xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-purple-600 to-indigo-600 mb-4">
                    404
                </h1>
            </div>

            {{-- Thông báo lỗi --}}
            <div class="mb-8">
                <h2 class="text-3xl sm:text-4xl font-bold text-gray-900 mb-4">
                    Trang không tìm thấy
                </h2>
                <p class="text-lg sm:text-xl text-gray-600 mb-2 max-w-2xl mx-auto">
                    Xin lỗi, trang bạn đang tìm kiếm không tồn tại hoặc đã bị di chuyển.
                </p>
                <p class="text-base text-gray-500 max-w-xl mx-auto">
                    Có thể URL không đúng hoặc trang đã bị xóa. Vui lòng kiểm tra lại địa chỉ hoặc quay về trang chủ.
                </p>
            </div>

            {{-- Các nút hành động --}}
            <div class="flex flex-col sm:flex-row items-center justify-center gap-4 mb-8">
                <a href="{{ route('home') }}" 
                   class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-700 hover:to-indigo-700 text-white font-semibold rounded-lg shadow-lg transition-all duration-300 transform hover:scale-105">
                    <i class="fas fa-home mr-2"></i>
                    Về trang chủ
                </a>
                <button onclick="window.history.back()" 
                        class="inline-flex items-center px-6 py-3 bg-white border-2 border-purple-600 text-purple-600 hover:bg-purple-50 font-semibold rounded-lg shadow-md transition-all duration-300">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Quay lại
                </button>
            </div>

            {{-- Gợi ý --}}
            <div class="bg-white rounded-xl shadow-sm p-6 max-w-2xl mx-auto">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center justify-center">
                    <i class="fas fa-lightbulb text-yellow-500 mr-2"></i>
                    Bạn có thể thử:
                </h3>
                <ul class="text-left space-y-2 text-gray-600">
                    <li class="flex items-start">
                        <i class="fas fa-check-circle text-green-500 mr-2 mt-1"></i>
                        <span>Kiểm tra lại URL trong thanh địa chỉ</span>
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-check-circle text-green-500 mr-2 mt-1"></i>
                        <span>Sử dụng thanh tìm kiếm để tìm sản phẩm bạn cần</span>
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-check-circle text-green-500 mr-2 mt-1"></i>
                        <span>Quay về <a href="{{ route('home') }}" class="text-purple-600 hover:underline font-medium">trang chủ</a> và điều hướng từ đó</span>
                    </li>
                </ul>
            </div>

            {{-- Liên kết nhanh --}}
            <div class="mt-8 grid grid-cols-2 sm:grid-cols-4 gap-4 max-w-2xl mx-auto">
                <a href="{{ route('products.index') }}" 
                   class="flex flex-col items-center p-4 bg-white rounded-lg shadow-sm hover:shadow-md transition-all hover:bg-purple-50 group">
                    <i class="fas fa-shopping-bag text-2xl text-purple-600 mb-2 group-hover:scale-110 transition-transform"></i>
                    <span class="text-sm font-medium text-gray-700">Sản phẩm</span>
                </a>
                <a href="{{ route('blog') }}" 
                   class="flex flex-col items-center p-4 bg-white rounded-lg shadow-sm hover:shadow-md transition-all hover:bg-purple-50 group">
                    <i class="fas fa-blog text-2xl text-purple-600 mb-2 group-hover:scale-110 transition-transform"></i>
                    <span class="text-sm font-medium text-gray-700">Blog</span>
                </a>
                <a href="{{ route('contact') }}" 
                   class="flex flex-col items-center p-4 bg-white rounded-lg shadow-sm hover:shadow-md transition-all hover:bg-purple-50 group">
                    <i class="fas fa-envelope text-2xl text-purple-600 mb-2 group-hover:scale-110 transition-transform"></i>
                    <span class="text-sm font-medium text-gray-700">Liên hệ</span>
                </a>
                @auth
                <a href="{{ route('client.orders.index') }}" 
                   class="flex flex-col items-center p-4 bg-white rounded-lg shadow-sm hover:shadow-md transition-all hover:bg-purple-50 group">
                    <i class="fas fa-shopping-cart text-2xl text-purple-600 mb-2 group-hover:scale-110 transition-transform"></i>
                    <span class="text-sm font-medium text-gray-700">Đơn hàng</span>
                </a>
                @else
                <a href="{{ route('login') }}" 
                   class="flex flex-col items-center p-4 bg-white rounded-lg shadow-sm hover:shadow-md transition-all hover:bg-purple-50 group">
                    <i class="fas fa-sign-in-alt text-2xl text-purple-600 mb-2 group-hover:scale-110 transition-transform"></i>
                    <span class="text-sm font-medium text-gray-700">Đăng nhập</span>
                </a>
                @endauth
            </div>
        </div>
    </div>
</div>

<style>
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

.animate-fadeUp {
    animation: fadeUp 0.8s ease-out;
}

@keyframes bounce {
    0%, 100% {
        transform: translateY(0);
    }
    50% {
        transform: translateY(-10px);
    }
}

.animate-bounce {
    animation: bounce 2s infinite;
}
</style>
@endsection

