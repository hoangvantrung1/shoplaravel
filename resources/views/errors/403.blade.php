@extends('layouts.client')

@section('title', '403 - Không có quyền truy cập')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 via-yellow-50/30 to-orange-50/30 pt-20 pb-12 flex items-center">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <div class="animate-fadeUp">
            {{-- Icon và số lỗi --}}
            <div class="mb-8">
                <div class="inline-flex items-center justify-center w-32 h-32 sm:w-40 sm:h-40 bg-gradient-to-br from-yellow-100 to-orange-100 rounded-full mb-6">
                    <i class="fas fa-lock text-5xl sm:text-6xl text-yellow-600"></i>
                </div>
                <h1 class="text-8xl sm:text-9xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-yellow-600 to-orange-600 mb-4">
                    403
                </h1>
            </div>

            {{-- Thông báo lỗi --}}
            <div class="mb-8">
                <h2 class="text-3xl sm:text-4xl font-bold text-gray-900 mb-4">
                    Không có quyền truy cập
                </h2>
                <p class="text-lg sm:text-xl text-gray-600 mb-2 max-w-2xl mx-auto">
                    Bạn không có quyền truy cập vào trang này.
                </p>
                <p class="text-base text-gray-500 max-w-xl mx-auto">
                    Trang này yêu cầu quyền truy cập đặc biệt. Vui lòng đăng nhập với tài khoản có quyền hoặc liên hệ quản trị viên.
                </p>
            </div>

            {{-- Các nút hành động --}}
            <div class="flex flex-col sm:flex-row items-center justify-center gap-4 mb-8">
                <a href="{{ route('home') }}" 
                   class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-yellow-600 to-orange-600 hover:from-yellow-700 hover:to-orange-700 text-white font-semibold rounded-lg shadow-lg transition-all duration-300 transform hover:scale-105">
                    <i class="fas fa-home mr-2"></i>
                    Về trang chủ
                </a>
                @guest
                <a href="{{ route('login') }}" 
                   class="inline-flex items-center px-6 py-3 bg-white border-2 border-yellow-600 text-yellow-600 hover:bg-yellow-50 font-semibold rounded-lg shadow-md transition-all duration-300">
                    <i class="fas fa-sign-in-alt mr-2"></i>
                    Đăng nhập
                </a>
                @else
                <button onclick="window.history.back()" 
                        class="inline-flex items-center px-6 py-3 bg-white border-2 border-yellow-600 text-yellow-600 hover:bg-yellow-50 font-semibold rounded-lg shadow-md transition-all duration-300">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Quay lại
                </button>
                @endguest
            </div>

            {{-- Thông tin --}}
            <div class="bg-white rounded-xl shadow-sm p-6 max-w-2xl mx-auto">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center justify-center">
                    <i class="fas fa-info-circle text-blue-500 mr-2"></i>
                    Bạn có thể:
                </h3>
                <ul class="text-left space-y-2 text-gray-600">
                    <li class="flex items-start">
                        <i class="fas fa-check-circle text-green-500 mr-2 mt-1"></i>
                        <span>Đăng nhập với tài khoản có quyền truy cập</span>
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-check-circle text-green-500 mr-2 mt-1"></i>
                        <span>Liên hệ quản trị viên để được cấp quyền</span>
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-check-circle text-green-500 mr-2 mt-1"></i>
                        <span>Quay về <a href="{{ route('home') }}" class="text-yellow-600 hover:underline font-medium">trang chủ</a> và điều hướng từ đó</span>
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

