@extends('layouts.client')

@section('title', '500 - Lỗi máy chủ')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 via-red-50/30 to-orange-50/30 pt-20 pb-12 flex items-center">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <div class="animate-fadeUp">
            {{-- Icon và số lỗi --}}
            <div class="mb-8">
                <div class="inline-flex items-center justify-center w-32 h-32 sm:w-40 sm:h-40 bg-gradient-to-br from-red-100 to-orange-100 rounded-full mb-6">
                    <i class="fas fa-server text-5xl sm:text-6xl text-red-600"></i>
                </div>
                <h1 class="text-8xl sm:text-9xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-red-600 to-orange-600 mb-4">
                    500
                </h1>
            </div>

            {{-- Thông báo lỗi --}}
            <div class="mb-8">
                <h2 class="text-3xl sm:text-4xl font-bold text-gray-900 mb-4">
                    Lỗi máy chủ
                </h2>
                <p class="text-lg sm:text-xl text-gray-600 mb-2 max-w-2xl mx-auto">
                    Xin lỗi, đã xảy ra lỗi trong quá trình xử lý yêu cầu của bạn.
                </p>
                <p class="text-base text-gray-500 max-w-xl mx-auto">
                    Chúng tôi đã được thông báo về lỗi này và đang khắc phục. Vui lòng thử lại sau vài phút.
                </p>
            </div>

            {{-- Các nút hành động --}}
            <div class="flex flex-col sm:flex-row items-center justify-center gap-4 mb-8">
                <a href="{{ route('home') }}" 
                   class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-red-600 to-orange-600 hover:from-red-700 hover:to-orange-700 text-white font-semibold rounded-lg shadow-lg transition-all duration-300 transform hover:scale-105">
                    <i class="fas fa-home mr-2"></i>
                    Về trang chủ
                </a>
                <button onclick="window.location.reload()" 
                        class="inline-flex items-center px-6 py-3 bg-white border-2 border-red-600 text-red-600 hover:bg-red-50 font-semibold rounded-lg shadow-md transition-all duration-300">
                    <i class="fas fa-redo mr-2"></i>
                    Tải lại trang
                </button>
            </div>

            {{-- Thông tin hỗ trợ --}}
            <div class="bg-white rounded-xl shadow-sm p-6 max-w-2xl mx-auto">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center justify-center">
                    <i class="fas fa-headset text-blue-500 mr-2"></i>
                    Cần hỗ trợ?
                </h3>
                <p class="text-gray-600 mb-4">
                    Nếu lỗi vẫn tiếp tục xảy ra, vui lòng liên hệ với chúng tôi để được hỗ trợ.
                </p>
                <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                    <a href="{{ route('contact') }}" 
                       class="inline-flex items-center px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors">
                        <i class="fas fa-envelope mr-2"></i>
                        Liên hệ hỗ trợ
                    </a>
                    <a href="mailto:support@example.com" 
                       class="inline-flex items-center px-5 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium rounded-lg transition-colors">
                        <i class="fas fa-at mr-2"></i>
                        Gửi email
                    </a>
                </div>
            </div>

            {{-- Thông tin kỹ thuật (chỉ hiện trong development) --}}
            @if(config('app.debug'))
            <div class="mt-8 bg-yellow-50 border border-yellow-200 rounded-lg p-4 max-w-2xl mx-auto text-left">
                <h4 class="font-semibold text-yellow-800 mb-2 flex items-center">
                    <i class="fas fa-bug mr-2"></i>
                    Thông tin lỗi (Debug Mode)
                </h4>
                @if(isset($exception))
                    <p class="text-sm text-yellow-700 font-mono break-all">
                        {{ $exception->getMessage() }}
                    </p>
                @endif
            </div>
            @endif
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

