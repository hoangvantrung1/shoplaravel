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

            {{-- lỗi đăng nhập --}}
            @if ($errors->any())
                <div class="bg-red-50 border-l-4 border-red-400 p-4 rounded">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd"
                                      d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 
                                      1.414L8.586 10l-1.293 1.293a1 1 0 101.414 
                                      1.414L10 11.414l1.293 1.293a1 1 0 
                                      001.414-1.414L11.414 10l1.293-1.293a1 
                                      1 0 00-1.414-1.414L10 8.586 
                                      8.707 7.293z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-red-800">
                                Đăng nhập thất bại
                            </h3>
                            <div class="mt-2 text-sm text-red-700">
                                {{ $errors->first() }}
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <div class="space-y-4">
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">Địa chỉ email</label>
                    <input id="email" name="email" type="email" required value="{{ old('email') }}"
                           class="w-full px-3 py-2 border rounded-md focus:ring-purple-500 focus:border-purple-500 sm:text-sm"
                           placeholder="Nhập địa chỉ email">
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700">Mật khẩu</label>
                    <input id="password" name="password" type="password" required
                           class="w-full px-3 py-2 border rounded-md focus:ring-purple-500 focus:border-purple-500 sm:text-sm"
                           placeholder="Nhập mật khẩu">
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
