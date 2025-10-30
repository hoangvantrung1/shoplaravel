@extends('layouts.admin')

@section('title', 'Chi tiết Địa chỉ #' . $address->id)

@section('content')
<div class="bg-white rounded-2xl shadow-xl overflow-hidden">
    <!-- Header -->
    <div class="bg-gradient-to-r from-green-500 to-teal-600 px-8 py-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-white mb-2">Chi tiết Địa chỉ</h1>
                <p class="text-blue-100 text-lg">ID: #{{ $address->id }}</p>
            </div>
            <a href="{{ route('admin.addresses.index') }}" 
               class="bg-white text-blue-600 px-4 py-2 rounded-lg font-medium hover:bg-blue-50 transition duration-200 flex items-center shadow-sm">
                <span class="material-icons mr-2">arrow_back</span>
                Quay lại
            </a>
        </div>
    </div>

    <div class="p-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
            <!-- Thông tin khách hàng -->
            <div class="bg-gradient-to-br from-green-50 to-emerald-50 rounded-2xl p-6 border border-green-100">
                <div class="flex items-center mb-4">
                    <div class="bg-green-100 p-3 rounded-lg">
                        <span class="material-icons text-green-600">person</span>
                    </div>
                    <h3 class="ml-3 font-semibold text-gray-800">Thông tin Khách hàng</h3>
                </div>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-600">Tên khách hàng</label>
                        <p class="mt-1 text-lg font-semibold text-gray-900">
                            @if($address->user)
                                {{ $address->user->name }}
                            @else
                                <span class="text-red-500">User đã xóa</span>
                            @endif
                        </p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-600">Email</label>
                        <p class="mt-1 text-gray-700">
                            @if($address->user)
                                {{ $address->user->email }}
                            @else
                                <span class="text-red-500">-</span>
                            @endif
                        </p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-600">ID Khách hàng</label>
                        <p class="mt-1 font-mono text-gray-700">
                            @if($address->user)
                                #{{ $address->user->id }}
                            @else
                                <span class="text-red-500">-</span>
                            @endif
                        </p>
                    </div>
                </div>
            </div>

            <!-- Thông tin địa chỉ -->
            <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-2xl p-6 border border-blue-100">
                <div class="flex items-center mb-4">
                    <div class="bg-blue-100 p-3 rounded-lg">
                        <span class="material-icons text-blue-600">location_on</span>
                    </div>
                    <h3 class="ml-3 font-semibold text-gray-800">Thông tin Địa chỉ</h3>
                </div>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-600">Người nhận</label>
                        <p class="mt-1 text-lg font-semibold text-gray-900">{{ $address->name }}</p>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-600">Điện thoại</label>
                            <p class="mt-1 text-gray-700">{{ $address->phone }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-600">Email</label>
                            <p class="mt-1 text-gray-700">{{ $address->email ?? '-' }}</p>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-600">Địa chỉ đầy đủ</label>
                        <div class="mt-1 p-4 bg-white rounded-lg border border-gray-200 shadow-sm">
                            <div class="flex items-start">
                                <span class="material-icons text-blue-500 mr-2 mt-1">place</span>
                                <div>
                                    <p class="text-gray-800 font-medium">{{ $address->address_line }}</p>
                                    <p class="text-gray-600 mt-1">
                                        {{ $address->ward }}, {{ $address->district }}, {{ $address->province }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-600">Loại địa chỉ</label>
                        <div class="mt-2">
                            @if($address->is_default)
                                <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium bg-green-100 text-green-800 border border-green-200">
                                    <span class="material-icons text-sm mr-2">star</span>
                                    Địa chỉ mặc định
                                </span>
                            @else
                                <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium bg-gray-100 text-gray-600 border border-gray-200">
                                    <span class="material-icons text-sm mr-2">location_on</span>
                                    Địa chỉ thường
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Thông tin thời gian -->
        <div class="bg-gradient-to-br from-gray-50 to-slate-50 rounded-2xl p-6 border border-gray-200">
            <div class="flex items-center mb-4">
                <div class="bg-gray-100 p-3 rounded-lg">
                    <span class="material-icons text-gray-600">schedule</span>
                </div>
                <h3 class="ml-3 font-semibold text-gray-800">Thông tin Thời gian</h3>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="flex items-center justify-between p-4 bg-white rounded-lg border border-gray-200">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Ngày tạo</p>
                        <p class="text-lg font-semibold text-gray-900">{{ $address->created_at->format('d/m/Y') }}</p>
                        <p class="text-sm text-gray-500">{{ $address->created_at->format('H:i:s') }}</p>
                    </div>
                    <div class="bg-green-100 p-3 rounded-lg">
                        <span class="material-icons text-green-600">event_available</span>
                    </div>
                </div>
                <div class="flex items-center justify-between p-4 bg-white rounded-lg border border-gray-200">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Cập nhật cuối</p>
                        <p class="text-lg font-semibold text-gray-900">{{ $address->updated_at->format('d/m/Y') }}</p>
                        <p class="text-sm text-gray-500">{{ $address->updated_at->format('H:i:s') }}</p>
                    </div>
                    <div class="bg-blue-100 p-3 rounded-lg">
                        <span class="material-icons text-blue-600">update</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="mt-8 pt-6 border-t border-gray-200">
            <div class="flex justify-between items-center">
                <div class="text-sm text-gray-500">
                    ID: {{ $address->id }}
                </div>
                <form action="{{ route('admin.addresses.destroy', $address) }}" method="POST" 
                      onsubmit="return confirm('Bạn có chắc muốn xóa địa chỉ này?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" 
                            class="bg-red-600 text-white px-6 py-3 rounded-lg font-medium hover:bg-red-700 transition duration-200 flex items-center shadow-sm">
                        <span class="material-icons mr-2 text-sm">delete</span>
                        Xóa Địa chỉ
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
.material-icons {
    font-size: 1.25rem;
}
</style>
@endsection