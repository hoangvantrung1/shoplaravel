@extends('layouts.client')

@section('title', 'Giỏ hàng')

@section('content')
<div class="container mx-auto px-4 py-10 max-w-7xl">
    <h1 class="text-4xl font-extrabold mb-8 text-gray-900 text-center mt-4"> Giỏ Hàng</h1>
    @if($cart && count($cart) > 0)
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full border-collapse">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="p-4 text-left font-semibold text-gray-700">Sản phẩm</th>
                            <th class="p-4 text-center font-semibold text-gray-700">Giá</th>
                            <th class="p-4 text-center font-semibold text-gray-700">Số lượng</th>
                            <th class="p-4 text-center font-semibold text-gray-700">Tổng</th>
                            <th class="p-4 text-center font-semibold text-gray-700">Thao tác</th>
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
                            <tr class="border-b hover:bg-gray-50 transition">
                                <td class="p-4 flex items-center gap-4">
                                    @if(isset($item['image']))
                                        <img src="{{ asset($item['image']) }}" 
                                            class="w-20 h-20 object-cover rounded-lg shadow-md">
                                    @endif
                                    <div class="font-medium text-gray-800">{{ $name }}</div>
                                </td>
                                <td class="p-4 text-center text-gray-600">{{ number_format($price) }} đ</td>
                                <td class="p-4">
                                    <form action="{{ route('cart.update', $id) }}" method="POST" class="flex items-center justify-center gap-2">
                                        @csrf
                                        <input type="number" name="quantity" value="{{ $quantity }}" min="1"
                                            class="w-20 border rounded-md px-2 py-1 text-center shadow-sm focus:ring focus:ring-purple-300">
                                        <button type="submit"
                                            class="bg-purple-600 hover:bg-purple-700 text-white px-3 py-2 rounded-md text-sm transition">
                                            Cập nhật
                                        </button>
                                    </form>
                                </td>
                                <td class="p-4 text-center text-gray-800 font-bold">{{ number_format($total) }} đ</td>
                                <td class="p-4 text-center">
                                    <form action="{{ route('cart.remove', $id) }}" method="POST" class="inline-block">
                                        @csrf
                                        <button type="submit"
                                            class="bg-red-500 hover:bg-red-600 text-white px-3 py-2 rounded-md text-sm transition shadow">
                                            Xóa
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Tổng cộng & nút thanh toán -->
        <div class="flex flex-col md:flex-row justify-between mt-10 items-center gap-6 p-6 bg-white rounded-2xl shadow-xl">
            <div class="text-2xl font-bold text-gray-900">
                Tổng cộng: <span class="text-purple-600">{{ number_format($grandTotal) }} đ</span>
            </div>

            @if(!auth()->check())
                <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded-lg w-full md:w-auto">
                    <p class="text-sm text-yellow-700">
                        Vui lòng 
                        <a href="{{ route('client.login') }}" class="font-semibold underline text-yellow-700 hover:text-yellow-600">
                            đăng nhập
                        </a> 
                        để tiếp tục thanh toán.
                    </p>
                </div>
            @else
                <a href="{{ route('checkout') }}"
                    class="bg-purple-600 hover:bg-purple-700 text-white font-bold px-8 py-3 rounded-full text-lg transition shadow">
                    Thanh toán ngay
                </a>
            @endif
        </div>
    @else
        <div class="text-center p-12 bg-white rounded-2xl shadow-xl">
            <p class="text-gray-700 text-xl font-medium">Giỏ hàng của bạn đang trống.</p>
            <a href="{{ route('home') }}" 
                class="mt-6 inline-block bg-purple-600 hover:bg-purple-700 text-white px-6 py-3 rounded-full font-semibold transition shadow">
                Tiếp tục mua sắm
            </a>
        </div>
    @endif
</div>
@endsection
