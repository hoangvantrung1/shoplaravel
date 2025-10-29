@extends('layouts.admin')

@section('title', 'Chi tiết Địa chỉ')

@section('content')
<div class="bg-white rounded-lg shadow">
    <div class="p-6 border-b flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Thông tin Địa chỉ #{{ $address->id }}</h2>
            <p class="text-gray-600">Chi tiết địa chỉ giao hàng của khách hàng</p>
        </div>
        <a href="{{ route('admin.addresses.index') }}" class="text-blue-600 hover:text-blue-800 flex items-center">
            <span class="material-icons mr-1">arrow_back</span>
            Quay lại
        </a>
    </div>

    <div class="p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Thông tin khách hàng -->
            <div class="space-y-4">
                <h3 class="text-lg font-semibold text-gray-800 border-b pb-2">Thông tin Khách hàng</h3>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700">Tên khách hàng</label>
                    <p class="mt-1 text-lg">
                        @if($address->user)
                            {{ $address->user->name }}
                        @else
                            <span class="text-red-500">User đã xóa</span>
                        @endif
                    </p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Email</label>
                    <p class="mt-1 text-lg">
                        @if($address->user)
                            {{ $address->user->email }}
                        @else
                            <span class="text-red-500">-</span>
                        @endif
                    </p>
                </div>
            </div>

            <!-- Thông tin địa chỉ -->
            <div class="space-y-4">
                <h3 class="text-lg font-semibold text-gray-800 border-b pb-2">Thông tin Địa chỉ</h3>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700">Người nhận</label>
                    <p class="mt-1 text-lg">{{ $address->name }}</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Điện thoại</label>
                    <p class="mt-1 text-lg">{{ $address->phone }}</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Email</label>
                    <p class="mt-1 text-lg">{{ $address->email }}</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Địa chỉ đầy đủ</label>
                    <p class="mt-1 p-3 bg-gray-50 rounded-lg">
                        {{ $address->address_line }},<br>
                        {{ $address->ward }}, {{ $address->district }}, {{ $address->province }}
                    </p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Loại địa chỉ</label>
                    <p class="mt-1">
                        @if($address->is_default)
                            <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm font-medium">Địa chỉ mặc định</span>
                        @else
                            <span class="px-3 py-1 bg-gray-100 text-gray-800 rounded-full text-sm font-medium">Địa chỉ thường</span>
                        @endif
                    </p>
                </div>
            </div>
        </div>

        <!-- Thông tin thời gian -->
        <div class="mt-6 pt-6 border-t">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Ngày tạo</label>
                    <p class="mt-1">{{ $address->created_at->format('d/m/Y H:i') }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Cập nhật cuối</label>
                    <p class="mt-1">{{ $address->updated_at->format('d/m/Y H:i') }}</p>
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="mt-6 pt-6 border-t">
            <form action="{{ route('admin.addresses.destroy', $address) }}" method="POST" 
                  onsubmit="return confirm('Bạn có chắc muốn xóa địa chỉ này?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700 flex items-center">
                    <span class="material-icons mr-2 text-sm">delete</span>
                    Xóa Địa chỉ
                </button>
            </form>
        </div>
    </div>
</div>
@endsection