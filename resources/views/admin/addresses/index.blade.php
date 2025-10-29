@extends('layouts.admin')

@section('title', 'Quản lý Địa chỉ')

@section('content')
<div class="bg-white rounded-lg shadow">
    <div class="p-6 border-b">
        <h2 class="text-2xl font-bold text-gray-800">Danh sách Địa chỉ</h2>
        <p class="text-gray-600">Quản lý địa chỉ giao hàng của khách hàng</p>
    </div>

    <div class="p-6">
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        @if($addresses->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full table-auto">
                <thead>
                    <tr class="bg-gray-50">
                        <th class="px-4 py-3 text-left">ID</th>
                        <th class="px-4 py-3 text-left">Khách hàng</th>
                        <th class="px-4 py-3 text-left">Họ tên</th>
                        <th class="px-4 py-3 text-left">Điện thoại</th>
                        <th class="px-4 py-3 text-left">Địa chỉ</th>
                        <th class="px-4 py-3 text-left">Mặc định</th>
                        <th class="px-4 py-3 text-left">Ngày tạo</th>
                        <th class="px-4 py-3 text-left">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($addresses as $address)
                    <tr class="border-b hover:bg-gray-50">
                        <td class="px-4 py-3">{{ $address->id }}</td>
                        <td class="px-4 py-3">
                            @if($address->user)
                                <div class="font-medium">{{ $address->user->name }}</div>
                                <div class="text-sm text-gray-500">{{ $address->user->email }}</div>
                            @else
                                <span class="text-red-500">User đã xóa</span>
                            @endif
                        </td>
                        <td class="px-4 py-3">{{ $address->name }}</td>
                        <td class="px-4 py-3">{{ $address->phone }}</td>
                        <td class="px-4 py-3">
                            <div class="max-w-xs">
                                {{ $address->address_line }}, 
                                {{ $address->ward }}, 
                                {{ $address->district }}, 
                                {{ $address->province }}
                            </div>
                        </td>
                        <td class="px-4 py-3">
                            @if($address->is_default)
                                <span class="px-2 py-1 text-xs bg-green-100 text-green-800 rounded-full">Mặc định</span>
                            @else
                                <span class="px-2 py-1 text-xs bg-gray-100 text-gray-800 rounded-full">Thường</span>
                            @endif
                        </td>
                        <td class="px-4 py-3">{{ $address->created_at->format('d/m/Y') }}</td>
                        <td class="px-4 py-3">
                            <div class="flex space-x-2">
                                <a href="{{ route('admin.addresses.show', $address) }}" 
                                   class="text-blue-600 hover:text-blue-800" title="Xem chi tiết">
                                    <span class="material-icons text-sm">visibility</span>
                                </a>
                                <form action="{{ route('admin.addresses.destroy', $address) }}" method="POST" 
                                      onsubmit="return confirm('Bạn có chắc muốn xóa địa chỉ này?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800" title="Xóa">
                                        <span class="material-icons text-sm">delete</span>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <div class="mt-4">
            {{ $addresses->links() }}
        </div>
        @else
        <div class="text-center py-8">
            <span class="material-icons text-6xl text-gray-400">location_on</span>
            <p class="mt-4 text-gray-500">Chưa có địa chỉ nào</p>
        </div>
        @endif
    </div>
</div>
@endsection