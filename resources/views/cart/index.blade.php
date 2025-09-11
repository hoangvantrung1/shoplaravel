@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8 max-w-7xl">
    <h1 class="text-4xl font-extrabold mb-6 text-gray-900">Giỏ Hàng</h1>

    @if($cart && count($cart) > 0)
    <div class="bg-white p-6 rounded-lg shadow-lg">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="border-b-2 border-gray-200">
                    <tr>
                        <th class="p-3 text-left font-semibold text-gray-700">Sản phẩm</th>
                        <th class="p-3 text-left font-semibold text-gray-700">Giá</th>
                        <th class="p-3 text-left font-semibold text-gray-700">Số lượng</th>
                        <th class="p-3 text-left font-semibold text-gray-700">Tổng</th>
                        <th class="p-3 text-left font-semibold text-gray-700">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                @php $grandTotal = 0; @endphp
                @foreach($cart as $id => $item)
                    @php
                        $price = $item['price'] ?? 0;
                        $quantity = $item['quantity'] ?? 1;
                        $name = $item['name'] ?? 'Sản phẩm';
                        $total = $price * $quantity;
                        $grandTotal += $total;
                    @endphp
                    <tr class="border-b border-gray-100 hover:bg-gray-50">
                        <td class="p-3 flex items-center gap-4">
                            @if(isset($item['image']))
                                <img src="{{ $item['image'] }}" class="w-20 h-20 object-contain rounded-lg shadow-sm">
                            @endif
                            <div class="font-semibold text-gray-800">{{ $name }}</div>
                        </td>
                        <td class="p-3 text-gray-600">{{ number_format($price) }} đ</td>
                        <td class="p-3">
                            <form action="{{ route('cart.update',$id) }}" method="POST" class="flex items-center gap-2">
                                @csrf
                                <input type="number" name="quantity" value="{{ $quantity }}" min="1" class="w-20 border rounded-md px-2 py-1 text-center">
                                <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-md transition-colors duration-200">Cập nhật</button>
                            </form>
                        </td>
                        <td class="p-3 text-gray-800 font-bold">{{ number_format($total) }} đ</td>
                        <td class="p-3">
                            <a href="{{ route('cart.remove',$id) }}" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-md transition-colors duration-200">Xóa</a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="flex justify-between md:justify-end mt-8 items-center gap-6 p-4 bg-white rounded-lg shadow-lg">
        <div class="text-2xl font-bold text-gray-900">Tổng cộng: {{ number_format($grandTotal) }} đ</div>
        <a href="{{ route('checkout') }}" class="bg-green-600 hover:bg-green-700 text-white font-bold px-8 py-3 rounded-full text-lg transition-colors duration-300">
            Thanh toán
        </a>
    </div>

    @else
    <div class="text-center p-8 bg-white rounded-lg shadow-lg">
        <p class="text-gray-700 text-xl font-medium">Giỏ hàng của bạn đang trống.</p>
        <a href="{{ route('home') }}" class="mt-4 inline-block text-blue-600 hover:underline">Quay lại trang chủ để mua sắm</a>
    </div>
    @endif
</div>
@endsection