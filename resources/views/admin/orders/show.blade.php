@extends('layouts.app')

@section('content')
<h1 class="text-3xl font-bold mb-6">Chi Tiết Đơn Hàng #{{ $order->id }}</h1>

<div class="grid md:grid-cols-2 gap-8">
    <div class="bg-white p-6 rounded shadow space-y-2">
        <h2 class="text-xl font-semibold mb-2">Thông tin khách hàng</h2>
        <p><strong>Tên:</strong> {{ $order->customer_name }}</p>
        <p><strong>Email:</strong> {{ $order->customer_email }}</p>
        <p><strong>Điện thoại:</strong> {{ $order->customer_phone }}</p>
        <p><strong>Địa chỉ:</strong> {{ $order->customer_address }}</p>

        @php
            $statuses = [
                'pending' => 'Chờ xử lý',
                'processing' => 'Đang xử lý',
                'completed' => 'Đã hoàn thành',
                'cancelled' => 'Đã hủy'
            ];
        @endphp

        <form action="{{ route('admin.orders.updateStatus', $order->id) }}" method="POST" class="mt-4">
            @csrf
            <label class="block mb-2 font-semibold">Trạng thái:</label>
            <select name="status" class="border rounded px-3 py-2">
                @foreach($statuses as $value => $label)
                    <option value="{{ $value }}" @if($order->status == $value) selected @endif>{{ $label }}</option>
                @endforeach
            </select>
            <button type="submit" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded ml-2">Cập nhật</button>
        </form>
    </div>

    <div class="bg-white p-6 rounded shadow">
        <h2 class="text-xl font-semibold mb-4">Sản phẩm</h2>
        <div class="space-y-4">
            @foreach($order->items as $item)
                <div class="flex justify-between items-center border-b pb-2">
                    <div>{{ $item->product->name ?? 'Sản phẩm' }} x{{ $item->quantity }}</div>
                    <div>{{ number_format($item->price * $item->quantity) }} đ</div>
                </div>
            @endforeach
        </div>
        <div class="text-right font-bold text-lg mt-4">Tổng: {{ number_format($order->total) }} đ</div>
    </div>
</div>

<a href="{{ route('admin.orders.index') }}" class="inline-block mt-6 bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded">Quay lại danh sách</a>
@endsection