@extends('layouts.client')

@section('content')
<div class="border-t pt-6 mt-10 max-w-3xl mx-auto">
    <h1 class="text-3xl font-semibold text-gray-1000 mb-6 mt-10 text-center">Thêm địa chỉ mới</h1>

    <form action="{{ route('addresses.store') }}" method="POST" class="space-y-5">
        @csrf

        {{-- Tên & Số điện thoại --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-1">
                    Tên người nhận <span class="text-red-500">*</span>
                </label>
                <input type="text" name="name" id="name" value="{{ old('name') }}"
                    placeholder="Nhập tên người nhận"
                    class="w-full px-3 py-2 border rounded-md focus:ring-purple-500 focus:border-purple-500 @error('name') border-red-500 @enderror"
                    required>
                @error('name')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">
                    Số điện thoại <span class="text-red-500">*</span>
                </label>
                <input type="tel" name="phone" id="phone" value="{{ old('phone') }}"
                    placeholder="Nhập số điện thoại"
                    class="w-full px-3 py-2 border rounded-md focus:ring-purple-500 focus:border-purple-500 @error('phone') border-red-500 @enderror"
                    required>
                @error('phone')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        {{-- Email --}}
        <div>
            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">
                Email <span class="text-red-500">*</span>
            </label>
            <input type="email" name="email" id="email" value="{{ old('email') }}"
                placeholder="Nhập email người nhận"
                class="w-full px-3 py-2 border rounded-md focus:ring-purple-500 focus:border-purple-500 @error('email') border-red-500 @enderror"
                required>
            @error('email')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        {{-- Địa chỉ chi tiết --}}
        <div>
            <label for="address_line" class="block text-sm font-medium text-gray-700 mb-1">
                Địa chỉ chi tiết <span class="text-red-500">*</span>
            </label>
            <textarea name="address_line" id="address_line" rows="3"
                placeholder="Số nhà, tên đường, phường/xã"
                class="w-full px-3 py-2 border rounded-md focus:ring-purple-500 focus:border-purple-500 @error('address_line') border-red-500 @enderror"
                required>{{ old('address_line') }}</textarea>
            @error('address_line')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        {{-- Tỉnh/Thành phố, Quận/Huyện, Phường/Xã --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label for="province" class="block text-sm font-medium text-gray-700 mb-1">Tỉnh/Thành phố</label>
                <input type="text" name="province" id="province" value="{{ old('province') }}"
                    placeholder="Ví dụ: Hà Nội"
                    class="w-full px-3 py-2 border rounded-md focus:ring-purple-500 focus:border-purple-500">
            </div>
            <div>
                <label for="district" class="block text-sm font-medium text-gray-700 mb-1">Quận/Huyện</label>
                <input type="text" name="district" id="district" value="{{ old('district') }}"
                    placeholder="Ví dụ: Cầu Giấy"
                    class="w-full px-3 py-2 border rounded-md focus:ring-purple-500 focus:border-purple-500">
            </div>
            <div>
                <label for="ward" class="block text-sm font-medium text-gray-700 mb-1">Phường/Xã</label>
                <input type="text" name="ward" id="ward" value="{{ old('ward') }}"
                    placeholder="Ví dụ: Dịch Vọng"
                    class="w-full px-3 py-2 border rounded-md focus:ring-purple-500 focus:border-purple-500">
            </div>
        </div>

        {{-- Nút submit --}}
        <div class="flex justify-end">
            <button type="submit"
                class="px-6 py-2 bg-purple-600 hover:bg-purple-700 text-white rounded-md transition-colors flex items-center">
                <i class="fas fa-plus mr-2"></i>Thêm địa chỉ
            </button>
        </div>
    </form>
</div>
@endsection
