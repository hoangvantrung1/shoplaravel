@extends('layouts.client')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
        <div>
            <div class="mx-auto h-12 w-12 flex items-center justify-center rounded-full bg-gradient-to-r from-purple-600 to-blue-500">
                <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
            </div>
            <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
                Tạo tài khoản mới
            </h2>
            <p class="mt-2 text-center text-sm text-gray-600">
                Hoặc
                <a href="{{ route('login') }}" class="font-medium text-purple-600 hover:text-purple-500">
                    đăng nhập vào tài khoản có sẵn
                </a>
            </p>
        </div>
        
        <form class="mt-8 space-y-6" action="{{ route('register.submit') }}" method="POST">
            @csrf
            
            @if ($errors->any() && !$errors->has('name') && !$errors->has('email') && !$errors->has('phone') && !$errors->has('password') && !$errors->has('password_confirmation'))
                <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-lg shadow-sm">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <i class="fas fa-exclamation-circle text-red-500 text-xl"></i>
                        </div>
                        <div class="ml-3 flex-1">
                            <h3 class="text-sm font-semibold text-red-800 mb-1">
                                Có lỗi xảy ra
                            </h3>
                            <div class="text-sm text-red-700">
                                <ul class="list-disc list-inside space-y-1">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <div class="space-y-4">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-user text-purple-600 mr-1"></i>
                        Họ và tên <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <input id="name" 
                               name="name" 
                               type="text" 
                               autocomplete="name" 
                               required
                               value="{{ old('name') }}"
                               class="w-full px-4 py-3 pl-11 border rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all @error('name') border-red-500 bg-red-50 @enderror"
                               placeholder="Nhập họ và tên">
                        <i class="fas fa-user absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 @error('name') text-red-500 @enderror"></i>
                    </div>
                    @error('name')
                        <p class="mt-2 text-sm text-red-600 flex items-center">
                            <i class="fas fa-exclamation-circle mr-2"></i>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-envelope text-purple-600 mr-1"></i>
                        Địa chỉ email <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <input id="email" 
                               name="email" 
                               type="email" 
                               autocomplete="email" 
                               required
                               value="{{ old('email') }}"
                               class="w-full px-4 py-3 pl-11 border rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all @error('email') border-red-500 bg-red-50 @enderror"
                               placeholder="Nhập địa chỉ email">
                        <i class="fas fa-envelope absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 @error('email') text-red-500 @enderror"></i>
                    </div>
                    @error('email')
                        <p class="mt-2 text-sm text-red-600 flex items-center">
                            <i class="fas fa-exclamation-circle mr-2"></i>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <div>
                    <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-phone text-purple-600 mr-1"></i>
                        Số điện thoại <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <input id="phone" 
                               name="phone" 
                               type="tel" 
                               autocomplete="tel" 
                               required
                               value="{{ old('phone') }}"
                               class="w-full px-4 py-3 pl-11 border rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all @error('phone') border-red-500 bg-red-50 @enderror"
                               placeholder="Nhập số điện thoại">
                        <i class="fas fa-phone absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 @error('phone') text-red-500 @enderror"></i>
                    </div>
                    @error('phone')
                        <p class="mt-2 text-sm text-red-600 flex items-center">
                            <i class="fas fa-exclamation-circle mr-2"></i>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-lock text-purple-600 mr-1"></i>
                        Mật khẩu <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <input id="password" 
                               name="password" 
                               type="password" 
                               autocomplete="new-password" 
                               required
                               class="w-full px-4 py-3 pl-11 border rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all @error('password') border-red-500 bg-red-50 @enderror"
                               placeholder="Nhập mật khẩu (tối thiểu 6 ký tự)">
                        <i class="fas fa-lock absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 @error('password') text-red-500 @enderror"></i>
                    </div>
                    @error('password')
                        <p class="mt-2 text-sm text-red-600 flex items-center">
                            <i class="fas fa-exclamation-circle mr-2"></i>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-lock text-purple-600 mr-1"></i>
                        Xác nhận mật khẩu <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <input id="password_confirmation" 
                               name="password_confirmation" 
                               type="password" 
                               autocomplete="new-password" 
                               required
                               class="w-full px-4 py-3 pl-11 border rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all @error('password_confirmation') border-red-500 bg-red-50 @enderror"
                               placeholder="Nhập lại mật khẩu">
                        <i class="fas fa-lock absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 @error('password_confirmation') text-red-500 @enderror"></i>
                    </div>
                    @error('password_confirmation')
                        <p class="mt-2 text-sm text-red-600 flex items-center">
                            <i class="fas fa-exclamation-circle mr-2"></i>
                            {{ $message }}
                        </p>
                    @enderror
                </div>
            </div>

            <div>
                <button type="submit"
                        class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-gradient-to-r from-purple-600 to-blue-500 hover:from-purple-700 hover:to-blue-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 transition-all duration-200 transform hover:scale-105">
                    <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                        <svg class="h-5 w-5 text-purple-500 group-hover:text-purple-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
                        </svg>
                    </span>
                    Tạo tài khoản
                </button>
            </div>

            <div class="text-center">
                <p class="text-sm text-gray-600">
                    Bằng cách tạo tài khoản, bạn đồng ý với 
                    <a href="#" class="font-medium text-purple-600 hover:text-purple-500">Điều khoản sử dụng</a>
                    và 
                    <a href="#" class="font-medium text-purple-600 hover:text-purple-500">Chính sách bảo mật</a>
                    của chúng tôi.
                </p>
            </div>
        </form>
    </div>
</div>
@endsection
