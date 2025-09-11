@extends('layouts.app')

@section('content')
<h1 class="text-3xl font-bold mb-6 text-gray-800">Thanh Toán</h1>

<div class="grid md:grid-cols-2 gap-8">
    <!-- Form thông tin khách hàng -->
    <div>
        <form action="{{ route('checkout.store') }}" method="POST" class="bg-white p-6 rounded shadow space-y-4">
            @csrf
            <h2 class="text-xl font-semibold mb-4">Thông tin khách hàng</h2>
            <input type="text" name="customer_name" placeholder="Họ và tên" class="w-full border rounded px-3 py-2" required>
            <input type="email" name="customer_email" placeholder="Email" class="w-full border rounded px-3 py-2" required>
            <input type="text" name="customer_phone" placeholder="Số điện thoại" class="w-full border rounded px-3 py-2" required>
            <textarea name="customer_address" placeholder="Địa chỉ" class="w-full border rounded px-3 py-2" rows="3" required></textarea>
            <button type="submit" class="bg-green-500 hover:bg-green-600 text-white px-6 py-3 rounded transition w-full">Đặt Hàng</button>
        </form>
    </div>

    <!-- Giỏ hàng -->
    <div class="bg-white p-6 rounded shadow">
        <h2 class="text-xl font-semibold mb-4">Đơn hàng của bạn</h2>
        <div class="space-y-4">
            @php $grandTotalDisplay = 0; @endphp
            @foreach($cart as $id => $item)
                @php
                    $name = $item['name'] ?? 'Sản phẩm';
                    $price = $item['price'] ?? 0;
                    $quantity = $item['quantity'] ?? 1;
                    $total = $price * $quantity;
                    $grandTotalDisplay += $total;
                @endphp
                <div class="flex justify-between items-center border-b pb-2">
                    <div>{{ $name }} x{{ $quantity }}</div>
                    <div>{{ number_format($total) }} đ</div>
                </div>
            @endforeach
        </div>
        <div class="text-right font-bold text-lg mt-4">Tổng: {{ number_format($grandTotalDisplay) }} đ</div>
    </div>
</div>
@endsection
