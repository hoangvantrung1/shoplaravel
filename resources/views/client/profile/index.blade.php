@extends('layouts.client')

@section('content')
<div class="min-h-screen bg-gray-50 pt-20 pb-12">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-4xl">
        
        {{-- Breadcrumb --}}
        <nav class="flex px-6 py-4 text-gray-700 border border-gray-200 rounded-2xl bg-white shadow-sm mb-8" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="{{ route('home') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-purple-600 transition-colors">
                        <i class="fa-solid fa-house mr-2"></i>
                        Trang chủ
                    </a>
                </li>
                <li aria-current="page">
                    <div class="flex items-center">
                        <i class="fa-solid fa-chevron-right text-gray-400 mx-2"></i>
                        <span class="text-sm font-medium text-purple-600">Thông tin cá nhân</span>
                    </div>
                </li>
            </ol>
        </nav>

        <h1 class="text-3xl font-extrabold text-gray-800 text-center mb-8">Thông tin cá nhân</h1>

    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center space-x-6 mb-6">
            <div class="w-20 h-20 rounded-full bg-gradient-to-r from-purple-600 to-blue-500 flex items-center justify-center text-white font-bold text-2xl">
                {{ substr($user->name, 0, 1) }}
            </div>
            <div>
                <h2 class="text-2xl font-bold text-gray-800">{{ $user->name }}</h2>
                <p class="text-gray-600">{{ $user->email }}</p>
                <p class="text-sm text-gray-500">Thành viên từ {{ $user->created_at->format('d/m/Y') }}</p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="bg-gray-50 rounded-lg p-4">
                <h3 class="text-lg font-semibold text-gray-800 mb-3">Thông tin cơ bản</h3>
                <div class="space-y-2">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Họ tên:</span>
                        <span class="font-medium">{{ $user->name }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Email:</span>
                        <span class="font-medium">{{ $user->email }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Số điện thoại:</span>
                        <span class="font-medium">{{ $user->phone ?? 'Chưa cập nhật' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Ngày tham gia:</span>
                        <span class="font-medium">{{ $user->created_at->format('d/m/Y') }}</span>
                    </div>
                </div>
            </div>

            <div class="bg-gray-50 rounded-lg p-4">
                <h3 class="text-lg font-semibold text-gray-800 mb-3">Thống kê đơn hàng</h3>
                <div class="space-y-2">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Tổng đơn hàng:</span>
                        <span class="font-medium">{{ $user->orders()->count() }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Đơn đã hoàn thành:</span>
                        <span class="font-medium text-green-600">{{ $user->orders()->where('status', 'completed')->count() }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Đơn đang xử lý:</span>
                        <span class="font-medium text-blue-600">{{ $user->orders()->whereIn('status', ['pending', 'processing'])->count() }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Đơn đã hủy:</span>
                        <span class="font-medium text-red-600">{{ $user->orders()->where('status', 'cancelled')->count() }}</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-6 flex flex-col sm:flex-row gap-4">
            <a href="{{ route('client.orders.index') }}" 
               class="flex-1 bg-purple-600 hover:bg-purple-700 text-white py-3 px-6 rounded-lg text-center transition-colors">
                <i class="fas fa-shopping-bag mr-2"></i>Xem đơn hàng
            </a>
            <a href="{{ route('profile.edit') }}" 
               class="flex-1 bg-gray-600 hover:bg-gray-700 text-white py-3 px-6 rounded-lg text-center transition-colors">
                <i class="fas fa-edit mr-2"></i>Chỉnh sửa thông tin
            </a>
        </div>

        {{-- ✅ Thêm phần địa chỉ --}}
        <div class="mt-10 bg-gray-50 rounded-lg p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Địa chỉ của tôi</h3>

            @if($addresses->count() > 0)
                <div class="space-y-4">
                    @foreach($addresses as $address)
                        <div class="border rounded-lg p-4 @if($address->is_default) border-purple-500 bg-purple-50 @endif">
                            <div class="flex justify-between items-center">
                                <div>
                                    <p class="font-medium text-gray-800">{{ $address->name }} ({{ $address->phone }})</p>
                                    <p class="text-gray-600 text-sm">
                                        {{ $address->address_line }},
                                        {{ $address->ward }},
                                        {{ $address->district }},
                                        {{ $address->province }}
                                    </p>
                                </div>
                                @if($address->is_default)
                                    <span class="text-xs bg-purple-600 text-white px-2 py-1 rounded">Mặc định</span>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-gray-500">Bạn chưa có địa chỉ nào.</p>
            @endif
        </div>
        {{-- ✅ Kết thúc phần địa chỉ --}}
    </div>
    </div>
</div>
@endsection
