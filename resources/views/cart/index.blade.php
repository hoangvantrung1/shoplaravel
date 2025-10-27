@extends('layouts.client')

@section('title', 'Giỏ hàng')

@section('content')
<div class="min-h-screen bg-gray-50 pt-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        
        {{-- Breadcrumb --}}
        <nav class="flex px-6 py-4 text-gray-700 border border-gray-200 rounded-2xl bg-white shadow-sm mb-8" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="/" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-purple-600 transition-colors">
                        <i class="fa-solid fa-house mr-2"></i>
                        Trang chủ
                    </a>
                </li>
                <li aria-current="page">
                    <div class="flex items-center">
                        <i class="fa-solid fa-chevron-right text-gray-400 mx-2"></i>
                        <span class="text-sm font-medium text-purple-600">Giỏ hàng</span>
                    </div>
                </li>
            </ol>
        </nav>

        <h1 class="text-4xl font-extrabold mb-8 text-gray-900 text-center">Giỏ Hàng</h1>

        @if($cart && count($cart) > 0)
            <div class="bg-white rounded-2xl shadow-xl overflow-hidden mb-8">
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
                                            <img src="{{ asset($item['image']) }}" alt="{{ $name }}" 
                                                 class="w-20 h-20 object-cover rounded-lg shadow-md">
                                        @endif
                                        <div class="font-medium text-gray-800">{{ $name }}</div>
                                    </td>
                                    <td class="p-4 text-center text-gray-600">{{ number_format($price) }}₫</td>
                                    <td class="p-4">
                                        <form action="{{ route('cart.update', $id) }}" method="POST"
                                            class="flex items-center justify-center gap-2">
                                            @csrf
                                            <input type="number" name="quantity" value="{{ $quantity }}" min="1"
                                                class="w-20 border rounded-md px-2 py-1 text-center shadow-sm focus:ring-2 focus:ring-purple-300 focus:border-transparent">
                                            <button type="submit"
                                                class="bg-purple-600 hover:bg-purple-700 text-white px-3 py-2 rounded-md text-sm transition-all duration-300">
                                                Cập nhật
                                            </button>
                                        </form>
                                    </td>
                                    <td class="p-4 text-center text-gray-800 font-bold">{{ number_format($total) }}₫</td>
                                    <td class="p-4 text-center">
                                        <form action="{{ route('cart.remove', $id) }}" method="POST" class="inline-block">
                                            @csrf
                                            <button type="submit"
                                                class="bg-red-500 hover:bg-red-600 text-white px-3 py-2 rounded-md text-sm transition-all duration-300 shadow">
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

            {{-- Mã giảm giá + Tổng cộng & nút thanh toán --}}
            <div class="flex flex-col lg:flex-row gap-8">
                {{-- Mã giảm giá --}}
                <div class="lg:w-1/2">
                    <div class="bg-white rounded-2xl shadow-xl p-6">
                        <h3 class="text-xl font-semibold text-gray-800 mb-4">Mã giảm giá</h3>
                        <form action="{{ route('cart.coupon.apply') }}" method="POST" class="flex gap-3">
                            @csrf
                            <input type="text" name="code" placeholder="Nhập mã giảm giá" 
                                value="{{ old('code') }}" 
                                class="flex-1 border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-purple-500 focus:border-transparent @error('code') border-red-500 @enderror"
                                required>
                            <button class="bg-gradient-to-r from-purple-600 to-blue-500 hover:from-purple-700 hover:to-blue-600 text-white px-6 py-3 rounded-xl transition-all duration-300 font-semibold">
                                Áp dụng
                            </button>
                        </form>
                        
                        @error('code')
                            <div class="mt-3 text-red-600 text-sm bg-red-50 border border-red-200 rounded-lg p-3">
                                {{ $message }}
                            </div>
                        @enderror
                        
                        @if(session('coupon'))
                            <div class="mt-4 p-4 bg-gradient-to-r from-purple-50 to-blue-50 border border-purple-200 rounded-xl">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center space-x-3">
                                        <i class="fas fa-tag text-purple-600"></i>
                                        <div>
                                            <span class="font-semibold text-purple-700">{{ session('coupon.code') }}</span>
                                            <span class="text-purple-600 ml-2">(-{{ number_format(session('coupon.discount')) }}₫)</span>
                                        </div>
                                    </div>
                                    <form action="{{ route('cart.coupon.remove') }}" method="POST" class="inline-block">
                                        @csrf
                                        <button type="submit" class="text-red-500 hover:text-red-700 transition-colors text-sm font-medium">
                                            <i class="fas fa-times mr-1"></i>Gỡ mã
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Tổng thanh toán --}}
                <div class="lg:w-1/2">
                    <div class="bg-white rounded-2xl shadow-xl p-6">
                        <h3 class="text-xl font-semibold text-gray-800 mb-6">Tóm tắt đơn hàng</h3>

                        @php
                            $discount = session('coupon.discount') ?? 0;
                            $payable = max(0, $grandTotal - $discount);
                        @endphp

                        <div class="space-y-4">
                            <div class="flex justify-between items-center py-2">
                                <span class="text-gray-600">Tạm tính:</span>
                                <span class="font-semibold text-gray-800">{{ number_format($grandTotal, 0, ',', '.') }}₫</span>
                            </div>
                            
                            @if($discount > 0)
                            <div class="flex justify-between items-center py-2 border-t border-gray-200">
                                <span class="text-green-600">Giảm giá:</span>
                                <span class="font-semibold text-green-600">-{{ number_format($discount, 0, ',', '.') }}₫</span>
                            </div>
                            @endif
                            
                            <div class="flex justify-between items-center py-4 border-t border-gray-300">
                                <span class="text-lg font-bold text-gray-900">Tổng thanh toán:</span>
                                <span class="text-2xl font-bold text-purple-600">{{ number_format($payable, 0, ',', '.') }}₫</span>
                            </div>
                        </div>

                        <div class="mt-6 pt-6 border-t border-gray-200">
                            @if(!auth()->check())
                                <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-4 text-center">
                                    <i class="fas fa-exclamation-triangle text-yellow-500 text-lg mb-2"></i>
                                    <p class="text-yellow-800 mb-3">Vui lòng đăng nhập để tiếp tục thanh toán</p>
                                    <a href="{{ route('login') }}" 
                                       class="inline-flex items-center px-4 py-2 bg-yellow-500 hover:bg-yellow-600 text-white font-semibold rounded-lg transition-colors">
                                        <i class="fas fa-sign-in-alt mr-2"></i>
                                        Đăng nhập ngay
                                    </a>
                                </div>
                            @else
                                <a href="{{ route('checkout') }}"
                                    class="block w-full text-center bg-gradient-to-r from-purple-600 to-blue-500 hover:from-purple-700 hover:to-blue-600 text-white font-semibold px-6 py-4 rounded-xl text-lg shadow-lg transition-all duration-300 transform hover:scale-105">
                                    <i class="fas fa-credit-card mr-2"></i>
                                    Thanh toán ngay
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @else
            {{-- Giỏ hàng trống --}}
            <div class="text-center py-16 bg-white rounded-2xl shadow-xl">
                <div class="max-w-md mx-auto">
                    <div class="w-24 h-24 mx-auto mb-6 bg-gradient-to-r from-purple-100 to-blue-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-shopping-cart text-3xl text-purple-500"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-3">Giỏ hàng trống</h3>
                    <p class="text-gray-600 mb-8 leading-relaxed">
                        Bạn chưa có sản phẩm nào trong giỏ hàng. 
                        Hãy khám phá và thêm sản phẩm vào giỏ hàng của bạn!
                    </p>
                    <a href="{{ route('products.index') }}"
                       class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-purple-600 to-blue-500 text-white font-semibold rounded-xl hover:from-purple-700 hover:to-blue-600 transition-all duration-300 transform hover:scale-105">
                        <i class="fas fa-shopping-bag mr-2"></i>
                        Khám phá sản phẩm
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection