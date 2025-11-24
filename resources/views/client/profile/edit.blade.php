@extends('layouts.client')

@section('title', 'Chỉnh sửa thông tin')

@section('content')
    <div class="container mx-auto px-4 py-10 max-w-4xl">
        <h1 class="text-3xl font-extrabold text-gray-800 text-center mb-8 mt-10">Chỉnh sửa thông tin cá nhân</h1>

        <div class="bg-white rounded-lg shadow-md p-6">
            @if ($errors->any() && !$errors->has('name') && !$errors->has('email') && !$errors->has('phone'))
                <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-lg mb-6 shadow-sm">
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

            <form action="{{ route('profile.update') }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-user text-purple-600 mr-1"></i>
                            Họ và tên <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <input type="text" 
                                   id="name" 
                                   name="name" 
                                   value="{{ old('name', $user->name) }}"
                                   class="w-full px-4 py-3 pl-11 border rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all @error('name') border-red-500 bg-red-50 @enderror"
                                   placeholder="Nhập họ và tên" 
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
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-envelope text-purple-600 mr-1"></i>
                            Địa chỉ email <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <input type="email" 
                                   id="email" 
                                   name="email" 
                                   value="{{ old('email', $user->email) }}"
                                   class="w-full px-4 py-3 pl-11 border rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all @error('email') border-red-500 bg-red-50 @enderror"
                                   placeholder="Nhập địa chỉ email" 
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
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-phone text-purple-600 mr-1"></i>
                            Số điện thoại
                        </label>
                        <div class="relative">
                            <input type="tel" 
                                   id="phone" 
                                   name="phone" 
                                   value="{{ old('phone', $user->phone) }}"
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
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Ngày tham gia
                        </label>
                        <input type="text" value="{{ $user->created_at->format('d/m/Y H:i') }}"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-50 text-gray-500" readonly>
                    </div>
                </div>

                {{-- Quản lý địa chỉ --}}
                <div class="bg-gray-50 rounded-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Quản lý địa chỉ</h3>

                    @if($addresses->count() > 0)
                        <div class="space-y-4 mb-6">
                            @foreach($addresses as $address)
                                <div
                                    class="bg-white p-4 rounded-lg border {{ $address->is_default ? 'border-purple-500' : 'border-gray-200' }}">
                                    <div class="flex justify-between items-start">
                                        <div class="flex-1">
                                            <div class="flex items-center mb-2">
                                                <h4 class="font-medium text-gray-800">{{ $address->name }}</h4>
                                                @if($address->is_default)
                                                    <span class="ml-2 px-2 py-1 bg-purple-100 text-purple-800 text-xs rounded-full">Mặc
                                                        định</span>
                                                @endif
                                            </div>
                                            <p class="text-sm text-gray-600">{{ $address->phone }}</p>
                                            <p class="text-sm text-gray-600">{{ $address->address_line }}</p>
                                            @if($address->province || $address->district || $address->ward)
                                                <p class="text-sm text-gray-500">
                                                    {{ $address->ward }}, {{ $address->district }}, {{ $address->province }}
                                                </p>
                                            @endif
                                        </div>
                                        <div class="flex space-x-2">
                                            <a href="{{ route('addresses.edit', $address->id) }}"
                                                class="text-blue-600 hover:text-blue-800 text-sm">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('addresses.destroy', $address->id) }}" method="POST"
                                                class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-800 text-sm"
                                                    onclick="return confirm('Bạn có chắc muốn xóa địa chỉ này?')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8 text-gray-500">
                            <i class="fas fa-map-marker-alt text-4xl mb-4"></i>
                            <p class="text-lg font-medium mb-2">Chưa có địa chỉ nào</p>
                            <p class="text-sm">Thêm địa chỉ để thuận tiện cho việc giao hàng</p>
                        </div>
                    @endif

                    {{-- Form thêm địa chỉ mới --}}

                </div>

                <div class="bg-gray-50 rounded-lg p-4">
                    <h3 class="text-lg font-semibold text-gray-800 mb-3">Thông tin bảo mật</h3>
                    <p class="text-sm text-gray-600 mb-4">
                        Để thay đổi mật khẩu, vui lòng sử dụng chức năng "Quên mật khẩu" trong trang đăng nhập.
                    </p>
                    <a href="{{ route('password.request') }}"
                        class="inline-flex items-center px-4 py-2 bg-yellow-600 hover:bg-yellow-700 text-white text-sm font-medium rounded-md transition-colors">
                        <i class="fas fa-key mr-2"></i>
                        Đổi mật khẩu
                    </a>
                </div>

                <div class="flex flex-col sm:flex-row gap-4 pt-6">
                    <button type="submit"
                        class="flex-1 bg-purple-600 hover:bg-purple-700 text-white py-3 px-6 rounded-lg text-center transition-colors">
                        <i class="fas fa-save mr-2"></i>Cập nhật thông tin
                    </button>
                    <a href="{{ route('profile.index') }}"
                        class="flex-1 bg-gray-500 hover:bg-gray-600 text-white py-3 px-6 rounded-lg text-center transition-colors">
                        <i class="fas fa-arrow-left mr-2"></i>Quay lại
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection