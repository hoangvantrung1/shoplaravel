@extends('layouts.client')

@section('title', 'Giỏ hàng')

@section('content')
    <div class="container mx-auto px-4 py-10 max-w-7xl">
        <h1 class="text-4xl font-extrabold mb-8 text-gray-900 text-center mt-10">Giỏ Hàng</h1>

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
                                            <img src="{{ asset($item['image']) }}" class="w-20 h-20 object-cover rounded-lg shadow-md">
                                        @endif
                                        <div class="font-medium text-gray-800">{{ $name }}</div>
                                    </td>
                                    <td class="p-4 text-center text-gray-600">{{ number_format($price) }} đ</td>
                                    <td class="p-4">
                                        <form action="{{ route('cart.update', $id) }}" method="POST"
                                            class="flex items-center justify-center gap-2">
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

            <!-- Mã giảm giá + Tổng cộng & nút thanh toán -->
            <div class="flex flex-col md:flex-row justify-between mt-10 items-start gap-6 p-6 bg-white rounded-2xl shadow-xl">
                <div class="w-full md:w-1/2">
                    <form action="{{ route('cart.coupon.apply') }}" method="POST" class="flex gap-2">
                        @csrf
                        <input type="text" name="code" placeholder="Nhập mã giảm giá" 
                            value="{{ old('code') }}" 
                            class="flex-1 border rounded px-3 py-2 @error('code') border-red-500 @enderror"
                            required>
                        <button class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded">Áp dụng</button>
                    </form>
                    
                    @error('code')
                        <div class="mt-2 text-red-600 text-sm">{{ $message }}</div>
                    @enderror
                    
                    @if(session('coupon'))
                        <div class="mt-3 p-3 bg-purple-50 border border-purple-200 rounded-lg">
                            <div class="flex items-center justify-between">
                                <div>
                                    <span class="font-semibold text-purple-700">Mã áp dụng: {{ session('coupon.code') }}</span>
                                    <span class="text-purple-600 ml-2">(-{{ number_format(session('coupon.discount')) }} đ)</span>
                                </div>
                                <form action="{{ route('cart.coupon.remove') }}" method="POST" class="inline-block">
                                    @csrf
                                    <button type="submit" class="text-red-600 hover:underline text-sm">Gỡ mã</button>
                                </form>
                            </div>
                        </div>
                    @endif
                </div>

                @php
                    $discount = session('coupon.discount') ?? 0;
                    $payable = max(0, $grandTotal - $discount);
                @endphp
                <div class="bg-white rounded-xl shadow-md p-6 space-y-4">
                    <div class="text-lg font-semibold text-gray-700">Tóm tắt đơn hàng</div>

                    <div class="space-y-2 text-gray-600">
                        <div class="flex justify-between">
                            <span>Tạm tính:</span>
                            <span class="font-medium">{{ number_format($grandTotal, 0, ',', '.') }} đ</span>
                        </div>
                        @if($discount > 0)
                        <div class="flex justify-between text-green-600">
                            <span>Giảm giá:</span>
                            <span>-{{ number_format($discount, 0, ',', '.') }} đ</span>
                        </div>
                        @endif
                        <div class="flex justify-between text-lg font-bold text-purple-600 border-t pt-2">
                            <span>Tổng thanh toán : {{ number_format($payable, 0, ',', '.') }} đ</span>
                        </div>
                    </div>

                    <div class="pt-4">
                        @if(!auth()->check())
                            <div class="bg-yellow-50 border border-yellow-300 text-yellow-800 rounded-lg p-4 text-sm">
                                Vui lòng
                                <a href="{{ route('login') }}" class="font-semibold underline hover:text-yellow-600">
                                    đăng nhập
                                </a>
                                để tiếp tục thanh toán.
                            </div>
                        @else
                            <a href="{{ route('checkout') }}"
                                class="block w-full text-center bg-gradient-to-r from-purple-600 to-purple-500 hover:from-purple-700 hover:to-purple-600 text-white font-semibold px-6 py-3 rounded-lg text-lg shadow transition">
                                Thanh toán ngay
                            </a>
                        @endif
                    </div>
                </div>
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