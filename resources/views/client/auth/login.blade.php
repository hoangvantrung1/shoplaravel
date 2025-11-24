@extends('layouts.client')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
        <div>
            <div class="mx-auto h-12 w-12 flex items-center justify-center rounded-full bg-gradient-to-r from-purple-600 to-blue-500">
                <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                </svg>
            </div>
            <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
                Đăng nhập tài khoản
            </h2>
            <p class="mt-2 text-center text-sm text-gray-600">
                Hoặc
                <a href="{{ route('register') }}" class="font-medium text-purple-600 hover:text-purple-500">
                    tạo tài khoản mới
                </a>
            </p>
        </div>

        {{-- Form đăng nhập --}}
        <form class="mt-8 space-y-6" action="{{ route('login.submit') }}" method="POST">
            @csrf

            {{-- Lỗi đăng nhập tổng --}}
            @if ($errors->has('email') || $errors->has('password'))
                <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-lg shadow-sm">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <i class="fas fa-exclamation-circle text-red-500 text-xl"></i>
                        </div>
                        <div class="ml-3 flex-1">
                            <h3 class="text-sm font-semibold text-red-800 mb-1">
                                Đăng nhập thất bại
                            </h3>
                            <div class="text-sm text-red-700">
                                {{ $errors->first('email') ?: $errors->first('password') }}
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <div class="space-y-4">
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-envelope text-purple-600 mr-1"></i>
                        Địa chỉ email <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <input id="email" 
                               name="email" 
                               type="email" 
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
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-lock text-purple-600 mr-1"></i>
                        Mật khẩu <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <input id="password" 
                               name="password" 
                               type="password" 
                               required
                               class="w-full px-4 py-3 pl-11 border rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all @error('password') border-red-500 bg-red-50 @enderror"
                               placeholder="Nhập mật khẩu">
                        <i class="fas fa-lock absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 @error('password') text-red-500 @enderror"></i>
                    </div>
                    @error('password')
                        <p class="mt-2 text-sm text-red-600 flex items-center">
                            <i class="fas fa-exclamation-circle mr-2"></i>
                            {{ $message }}
                        </p>
                    @enderror
                </div>
            </div>

            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <input id="remember-me" name="remember-me" type="checkbox"
                           class="h-4 w-4 text-purple-600 focus:ring-purple-500 border-gray-300 rounded">
                    <label for="remember-me" class="ml-2 block text-sm text-gray-900">Ghi nhớ đăng nhập</label>
                </div>

                <div class="text-sm">
                    <a href="{{ route('password.request') }}" class="font-medium text-purple-600 hover:text-purple-500">
                        Quên mật khẩu?
                    </a>
                </div>
            </div>

            <div>
                <button type="submit"
                        class="w-full flex justify-center py-2 px-4 rounded-md text-white bg-gradient-to-r from-purple-600 to-blue-500 hover:from-purple-700 hover:to-blue-600 transition-all duration-200">
                    Đăng nhập
                </button>
            </div>
        </form>

        {{-- Nút đăng nhập bằng Google --}}
        <div class="mt-6">
            <a href="{{ route('social.google.redirect') }}"
               class="w-full flex items-center justify-center px-4 py-2 border border-gray-300 rounded-md shadow-sm bg-white text-gray-700 hover:bg-gray-50">
                <img src="https://www.svgrepo.com/show/355037/google.svg" class="h-5 w-5 mr-2" alt="Google Logo">
                Đăng nhập với Google
            </a>
        </div>

        {{-- Link đăng ký --}}
        <p class="mt-4 text-center text-sm text-gray-600">
            Chưa có tài khoản?
            <a href="{{ route('register') }}" class="font-medium text-purple-600 hover:text-purple-500">
                Đăng ký ngay
            </a>
        </p>
    </div>
</div>
@endsection
