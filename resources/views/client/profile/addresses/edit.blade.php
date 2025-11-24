@extends('layouts.client')

@section('content')
<div class="min-h-screen bg-gray-50 pt-20 pb-12">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        
        {{-- Breadcrumb --}}
        <nav class="flex px-6 py-4 text-gray-700 border border-gray-200 rounded-2xl bg-white shadow-sm mb-8" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="{{ route('home') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-purple-600 transition-colors">
                        <i class="fa-solid fa-house mr-2"></i>
                        Trang chủ
                    </a>
                </li>
                <li>
                    <div class="flex items-center">
                        <i class="fa-solid fa-chevron-right text-gray-400 mx-2"></i>
                        <a href="{{ route('profile.index') }}" class="text-sm font-medium text-gray-700 hover:text-purple-600 transition-colors">Thông tin cá nhân</a>
                    </div>
                </li>
                <li>
                    <div class="flex items-center">
                        <i class="fa-solid fa-chevron-right text-gray-400 mx-2"></i>
                        <a href="{{ route('addresses.index') }}" class="text-sm font-medium text-gray-700 hover:text-purple-600 transition-colors">Địa chỉ</a>
                    </div>
                </li>
                <li aria-current="page">
                    <div class="flex items-center">
                        <i class="fa-solid fa-chevron-right text-gray-400 mx-2"></i>
                        <span class="text-sm font-medium text-purple-600">Sửa địa chỉ</span>
                    </div>
                </li>
            </ol>
        </nav>

        <div class="bg-white rounded-xl shadow-lg p-6 md:p-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-8 flex items-center">
                <i class="fas fa-edit text-purple-600 mr-3"></i>
                Sửa địa chỉ
            </h1>
            
            <form method="POST" action="{{ route('addresses.update', $address) }}" class="space-y-6">
                @csrf
                @method('PUT')

                {{-- Tên & Số điện thoại --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-user text-purple-600 mr-1"></i>
                            Tên người nhận <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <input type="text" 
                                   name="name" 
                                   id="name" 
                                   value="{{ old('name', $address->name) }}"
                                   placeholder="Nhập tên người nhận"
                                   class="w-full px-4 py-3 pl-11 border rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all @error('name') border-red-500 bg-red-50 @enderror"
                                   required>
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
                        <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-phone text-purple-600 mr-1"></i>
                            Số điện thoại <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <input type="tel" 
                                   name="phone" 
                                   id="phone" 
                                   value="{{ old('phone', $address->phone) }}"
                                   placeholder="Nhập số điện thoại"
                                   class="w-full px-4 py-3 pl-11 border rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all @error('phone') border-red-500 bg-red-50 @enderror"
                                   required>
                            <i class="fas fa-phone absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 @error('phone') text-red-500 @enderror"></i>
                        </div>
                        @error('phone')
                            <p class="mt-2 text-sm text-red-600 flex items-center">
                                <i class="fas fa-exclamation-circle mr-2"></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>
                </div>

                {{-- Email --}}
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-envelope text-purple-600 mr-1"></i>
                        Email <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <input type="email" 
                               name="email" 
                               id="email" 
                               value="{{ old('email', $address->email) }}"
                               placeholder="Nhập email người nhận"
                               class="w-full px-4 py-3 pl-11 border rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all @error('email') border-red-500 bg-red-50 @enderror"
                               required>
                        <i class="fas fa-envelope absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 @error('email') text-red-500 @enderror"></i>
                    </div>
                    @error('email')
                        <p class="mt-2 text-sm text-red-600 flex items-center">
                            <i class="fas fa-exclamation-circle mr-2"></i>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Địa chỉ chi tiết --}}
                <div>
                    <label for="address_line" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-map-marker-alt text-purple-600 mr-1"></i>
                        Địa chỉ chi tiết <span class="text-red-500">*</span>
                    </label>
                    <textarea name="address_line" 
                              id="address_line" 
                              rows="3"
                              placeholder="Số nhà, tên đường, phường/xã"
                              class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all @error('address_line') border-red-500 bg-red-50 @enderror"
                              required>{{ old('address_line', $address->address_line) }}</textarea>
                    @error('address_line')
                        <p class="mt-2 text-sm text-red-600 flex items-center">
                            <i class="fas fa-exclamation-circle mr-2"></i>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Tỉnh/Thành phố, Quận/Huyện, Phường/Xã --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label for="province" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-building text-purple-600 mr-1"></i>
                            Tỉnh/Thành phố
                        </label>
                        <input type="text" 
                               name="province" 
                               id="province" 
                               value="{{ old('province', $address->province ?? '') }}"
                               placeholder="Ví dụ: Hà Nội"
                               class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all">
                    </div>
                    <div>
                        <label for="district" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-map text-purple-600 mr-1"></i>
                            Quận/Huyện
                        </label>
                        <input type="text" 
                               name="district" 
                               id="district" 
                               value="{{ old('district', $address->district ?? '') }}"
                               placeholder="Ví dụ: Cầu Giấy"
                               class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all">
                    </div>
                    <div>
                        <label for="ward" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-location-dot text-purple-600 mr-1"></i>
                            Phường/Xã
                        </label>
                        <input type="text" 
                               name="ward" 
                               id="ward" 
                               value="{{ old('ward', $address->ward ?? '') }}"
                               placeholder="Ví dụ: Dịch Vọng"
                               class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all">
                    </div>
                </div>

                {{-- Đặt làm mặc định --}}
                <div class="flex items-center p-4 bg-purple-50 rounded-lg border border-purple-200">
                    <input type="checkbox" 
                           name="is_default" 
                           id="is_default" 
                           value="1" 
                           {{ old('is_default', $address->is_default) ? 'checked' : '' }}
                           class="h-5 w-5 text-purple-600 focus:ring-purple-500 border-gray-300 rounded">
                    <label for="is_default" class="ml-3 block text-sm font-medium text-gray-700">
                        <i class="fas fa-star text-yellow-500 mr-1"></i>
                        Đặt làm địa chỉ mặc định
                    </label>
                </div>

                {{-- Buttons --}}
                <div class="flex flex-col sm:flex-row gap-4 pt-6 border-t border-gray-200">
                    <button type="submit"
                            class="flex-1 bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-700 hover:to-indigo-700 text-white py-3 px-6 rounded-lg font-semibold transition-all duration-300 transform hover:scale-105 flex items-center justify-center">
                        <i class="fas fa-save mr-2"></i>
                        Cập nhật địa chỉ
                    </button>
                    <a href="{{ route('addresses.index') }}" 
                       class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-700 py-3 px-6 rounded-lg font-semibold transition-colors flex items-center justify-center">
                        <i class="fas fa-times mr-2"></i>
                        Hủy
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
