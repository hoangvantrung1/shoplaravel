@extends('layouts.client')

@section('content')
<div class="container mx-auto max-w-5xl py-10">
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-extrabold text-purple-800 mt-10"> Địa chỉ giao hàng</h1>
        <a href="{{ route('addresses.create') }}"
           class="bg-gradient-to-r from-purple-500 to-purple-600 hover:from-purple-600 hover:to-purple-700 text-white px-5 py-2.5 rounded-lg shadow-md transition-all duration-200 transform hover:scale-105 mt-10">
            + Thêm địa chỉ
        </a>
    </div>

    @if(session('success'))
        <div class="mb-6 p-4 rounded-lg bg-purple-50 border border-purple-200 text-purple-700 shadow-sm">
            ✅ {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <table class="w-full border-collapse">
            <thead>
                <tr class="bg-gray-100 text-gray-700 uppercase text-sm">
                    <th class="p-4 border">Họ tên</th>
                    <th class="p-4 border">Số điện thoại</th>
                    <th class="p-4 border">Địa chỉ</th>
                    <th class="p-4 border">Tỉnh/TP</th>
                    <th class="p-4 border">Quận/Huyện</th>
                    <th class="p-4 border">Phường/Xã</th>
                    <th class="p-4 border text-center">Mặc định</th>
                    <th class="p-4 border text-center">Hành động</th>
                </tr>
            </thead>
            <tbody class="text-sm">
                @forelse($addresses as $address)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="p-4 border font-medium text-gray-900">{{ $address->name }}</td>
                        <td class="p-4 border">{{ $address->phone }}</td>
                        <td class="p-4 border">{{ $address->address_line }}</td>
                        <td class="p-4 border">{{ $address->province }}</td>
                        <td class="p-4 border">{{ $address->district }}</td>
                        <td class="p-4 border">{{ $address->ward }}</td>
                        <td class="p-4 border text-center">
                            @if($address->is_default)
                                <span class="text-xs font-semibold text-green-800 bg-green-100 px-3 py-1 rounded-full">
                                    ✓ Mặc định
                                </span>
                            @else
                                <span class="text-xs text-gray-500">---</span>
                            @endif
                        </td>
                        <td class="p-4 border text-center space-x-2">
                            <a href="{{ route('addresses.edit', $address) }}"
                               class="inline-block bg-yellow-400 hover:bg-yellow-500 text-white px-3 py-1 rounded-md shadow transition">
                                ✏️ Sửa
                            </a>
                            <form action="{{ route('addresses.destroy', $address) }}" method="POST" class="inline-block"
                                  onsubmit="return confirm('Bạn có chắc chắn muốn xóa địa chỉ này?')">
                                @csrf
                                @method('DELETE')
                                <button class="inline-block bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded-md shadow transition mt-1 sm:mt-0">
                                    🗑️ Xóa
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="p-8 text-center text-gray-500">
                            Chưa có địa chỉ nào được thêm.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
