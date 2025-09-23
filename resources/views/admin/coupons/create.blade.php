@extends('layouts.admin')

@section('content')
<h1 class="text-2xl font-bold mb-6">Thêm mã giảm giá</h1>
<form method="POST" action="{{ route('admin.coupons.store') }}" class="bg-white p-6 rounded shadow grid grid-cols-1 md:grid-cols-2 gap-4">
    @csrf
    <div>
        <label class="block mb-1">Mã</label>
        <input name="code" class="w-full border rounded px-3 py-2" required>
    </div>
    <div>
        <label class="block mb-1">Loại</label>
        <select name="type" class="w-full border rounded px-3 py-2">
            <option value="percent">Phần trăm</option>
            <option value="fixed">Cố định</option>
        </select>
    </div>
    <div>
        <label class="block mb-1">Giá trị</label>
        <input type="number" step="0.01" name="value" class="w-full border rounded px-3 py-2" required>
    </div>
    <div>
        <label class="block mb-1">Giảm tối đa</label>
        <input type="number" step="0.01" name="max_discount" class="w-full border rounded px-3 py-2">
    </div>
    <div>
        <label class="block mb-1">Đơn tối thiểu</label>
        <input type="number" step="0.01" name="min_order_total" class="w-full border rounded px-3 py-2">
    </div>
    <div>
        <label class="block mb-1">Giới hạn lượt dùng</label>
        <input type="number" name="usage_limit" class="w-full border rounded px-3 py-2">
    </div>
    <div>
        <label class="block mb-1">Bắt đầu</label>
        <input type="datetime-local" name="starts_at" class="w-full border rounded px-3 py-2">
    </div>
    <div>
        <label class="block mb-1">Kết thúc</label>
        <input type="datetime-local" name="expires_at" class="w-full border rounded px-3 py-2">
    </div>
    <label class="inline-flex items-center gap-2">
        <input type="checkbox" name="is_active" value="1" checked> Kích hoạt
    </label>
    <div class="md:col-span-2 flex gap-2 mt-4">
        <a href="{{ route('admin.coupons.index') }}" class="bg-gray-200 px-4 py-2 rounded">Hủy</a>
        <button class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">Lưu</button>
    </div>
</form>
@endsection



