@extends('layouts.client')

@section('title', '419 - Phiên làm việc hết hạn')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 via-purple-50/30 to-pink-50/30 pt-20 pb-12 flex items-center">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <div class="animate-fadeUp">
            {{-- Icon và số lỗi --}}
            <div class="mb-8">
                <div class="inline-flex items-center justify-center w-32 h-32 sm:w-40 sm:h-40 bg-gradient-to-br from-purple-100 to-pink-100 rounded-full mb-6">
                    <i class="fas fa-clock text-5xl sm:text-6xl text-purple-600"></i>
                </div>
                <h1 class="text-8xl sm:text-9xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-purple-600 to-pink-600 mb-4">
                    419
                </h1>
            </div>

            {{-- Thông báo lỗi --}}
            <div class="mb-8">
                <h2 class="text-3xl sm:text-4xl font-bold text-gray-900 mb-4">
                    Phiên làm việc hết hạn
                </h2>
                <p class="text-lg sm:text-xl text-gray-600 mb-2 max-w-2xl mx-auto">
                    Phiên làm việc của bạn đã hết hạn do không hoạt động trong thời gian dài.
                </p>
                <p class="text-base text-gray-500 max-w-xl mx-auto">
                    Vui lòng tải lại trang và thử lại. Điều này giúp bảo vệ thông tin của bạn.
                </p>
            </div>

            {{-- Các nút hành động --}}
            <div class="flex flex-col sm:flex-row items-center justify-center gap-4 mb-8">
                <button onclick="window.location.reload()" 
                        class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 text-white font-semibold rounded-lg shadow-lg transition-all duration-300 transform hover:scale-105">
                    <i class="fas fa-redo mr-2"></i>
                    Tải lại trang
                </button>
                <a href="{{ route('home') }}" 
                   class="inline-flex items-center px-6 py-3 bg-white border-2 border-purple-600 text-purple-600 hover:bg-purple-50 font-semibold rounded-lg shadow-md transition-all duration-300">
                    <i class="fas fa-home mr-2"></i>
                    Về trang chủ
                </a>
            </div>

            {{-- Thông tin --}}
            <div class="bg-white rounded-xl shadow-sm p-6 max-w-2xl mx-auto">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center justify-center">
                    <i class="fas fa-shield-alt text-green-500 mr-2"></i>
                    Tại sao điều này xảy ra?
                </h3>
                <p class="text-gray-600 mb-4 text-left">
                    Đây là một tính năng bảo mật. Khi bạn không hoạt động trên trang web trong một khoảng thời gian, phiên làm việc sẽ tự động hết hạn để bảo vệ thông tin của bạn.
                </p>
                <ul class="text-left space-y-2 text-gray-600">
                    <li class="flex items-start">
                        <i class="fas fa-check-circle text-green-500 mr-2 mt-1"></i>
                        <span>Tải lại trang để tạo phiên làm việc mới</span>
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-check-circle text-green-500 mr-2 mt-1"></i>
                        <span>Đảm bảo bạn đã đăng nhập trước khi thực hiện các thao tác quan trọng</span>
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-check-circle text-green-500 mr-2 mt-1"></i>
                        <span>Nếu vấn đề vẫn tiếp tục, vui lòng <a href="{{ route('contact') }}" class="text-purple-600 hover:underline font-medium">liên hệ hỗ trợ</a></span>
                    </li>
                </ul>
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
</style>
@endsection

