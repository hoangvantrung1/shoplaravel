@extends('layouts.client')

@section('content')
    <div class="container mx-auto max-w-3xl py-10">
        <h1 class="text-2xl font-bold mb-6 mt-10">Sửa địa chỉ</h1>
        <form method="POST" action="{{ route('addresses.update', $address) }}"
            class="bg-white p-6 rounded shadow space-y-4">
            @csrf
            @method('PUT')
            <div>
                <label class="block mb-1">Họ tên</label>
                <input name="name" value="{{ old('name', $address->name) }}" class="w-full border rounded px-3 py-2"
                    required>
            </div>
            <div>
                <label class="block mb-1">Số điện thoại</label>
                <input name="phone" value="{{ old('phone', $address->phone) }}" class="w-full border rounded px-3 py-2"
                    required>
            </div>
            <div>
                <label class="block mb-1">Email</label>
                <input name="email" value="{{ old('email', $address->email) }}" class="w-full border rounded px-3 py-2"
                    required>
            </div>
            <div>
                <label class="block mb-1">Địa chỉ</label>
                <input name="address_line" value="{{ old('address_line', $address->address_line) }}"
                    class="w-full border rounded px-3 py-2" required>
            </div>
            <div>
                <label class="block mb-1">Tỉnh/TP</label>
                <input name="province" value="{{ old('province', $address->province ?? '') }}"
                    class="w-full border rounded px-3 py-2" required>
            </div>
            <div>
                <label class="block mb-1">Quận/Huyện</label>
                <input name="district" value="{{ old('district', $address->district ?? '') }}"
                    class="w-full border rounded px-3 py-2" required>
            </div>

            <div>
                <label class="block mb-1">Phường/Xã</label>
                <input name="ward" value="{{ old('ward', $address->ward ?? '') }}" class="w-full border rounded px-3 py-2"
                    required>
            </div>

            <label class="inline-flex items-center gap-2">
                <input type="checkbox" name="is_default" value="1" {{ $address->is_default ? 'checked' : '' }}> Đặt làm mặc
                định
            </label>
            <div class="flex gap-2">
                <a href="{{ route('addresses.index') }}" class="bg-gray-200 px-4 py-2 rounded">Hủy</a>
                <button class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">Cập nhật</button>
            </div>
        </form>
    </div>
@endsection
