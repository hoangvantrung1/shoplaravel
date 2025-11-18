@extends('layouts.client')

@section('title', 'Giỏ hàng')

@section('content')
<div class="min-h-screen bg-gray-50 pt-20 pb-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        
        {{-- Compact Header --}}
        <div class="mb-8">
            <nav class="mb-4" aria-label="Breadcrumb">
                <ol class="flex items-center space-x-2 text-sm text-gray-600">
                    <li>
                        <a href="/" class="hover:text-purple-600 transition-colors">
                            <i class="fas fa-home mr-1"></i>Trang chủ
                        </a>
                    </li>
                    <li><i class="fas fa-chevron-right text-gray-400 mx-2 text-xs"></i></li>
                    <li class="text-gray-900">Giỏ hàng</li>
                </ol>
            </nav>
            <div class="flex items-center justify-between">
                <h1 class="text-3xl font-bold text-gray-900">Giỏ hàng</h1>
                @if($cart && count($cart) > 0)
                    <span class="px-3 py-1 bg-purple-100 text-purple-700 rounded-full text-sm font-semibold">
                        {{ count($cart) }} sản phẩm
                    </span>
                @endif
            </div>
        </div>

        @if($cart && count($cart) > 0)
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                {{-- Cart Items --}}
                <div class="lg:col-span-2 space-y-4">
                    @php $grandTotal = 0; @endphp
                    @foreach($cart as $id => $item)
                        @php
                            $price = $item['price'] ?? 0;
                            $quantity = $item['quantity'] ?? 1;
                            $name = $item['name'] ?? 'Sản phẩm';
                            $total = $price * $quantity;
                            $grandTotal += $total;
                        @endphp
                        <div class="bg-white rounded-lg border border-gray-200 p-4 hover:border-purple-300 hover:shadow-md transition-all duration-300">
                            <div class="flex gap-4">
                                {{-- Product Image --}}
                                @if(isset($item['image']))
                                    <img src="{{ asset($item['image']) }}" alt="{{ $name }}" 
                                         class="w-20 h-20 object-cover rounded-lg flex-shrink-0 border border-gray-100">
                                @endif
                                
                                {{-- Product Info --}}
                                <div class="flex-1 min-w-0">
                                    <h3 class="font-semibold text-gray-900 mb-1 line-clamp-2">{{ $name }}</h3>
                                    <p class="text-sm text-gray-600 mb-3">{{ number_format($price, 0, ',', '.') }}₫</p>
                                    
                                    {{-- Quantity & Actions --}}
                                    <div class="flex items-center justify-between">
                                        <form action="{{ route('cart.update', $id) }}" method="POST" class="flex items-center gap-2">
                                            @csrf
                                            <div class="flex items-center border border-gray-300 rounded-lg">
                                                <button type="button" onclick="changeQuantity({{ $id }}, -1)"
                                                    class="w-8 h-8 flex items-center justify-center text-gray-600 hover:bg-gray-100 rounded-l-lg transition-colors">
                                                    <i class="fas fa-minus text-xs"></i>
                                                </button>
                                                <input type="number" name="quantity" id="qty-{{ $id }}" value="{{ $quantity }}" min="1"
                                                    class="w-14 text-center border-x border-gray-300 bg-white focus:ring-0 focus:border-gray-300 text-sm font-medium no-spinner"
                                                    onchange="updateCart({{ $id }})">
                                                <button type="button" onclick="changeQuantity({{ $id }}, 1)"
                                                    class="w-8 h-8 flex items-center justify-center text-gray-600 hover:bg-gray-100 rounded-r-lg transition-colors">
                                                    <i class="fas fa-plus text-xs"></i>
                                                </button>
                                            </div>
                                            <button type="submit" class="hidden" id="update-{{ $id }}"></button>
                                        </form>
                                        
                                        <div class="flex items-center gap-4">
                                            <span class="font-bold text-purple-600">{{ number_format($total, 0, ',', '.') }}₫</span>
                                            <form action="{{ route('cart.remove', $id) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" 
                                                    class="text-red-500 hover:text-red-700 p-1.5 transition-colors"
                                                    onclick="return confirm('Xóa sản phẩm này?')">
                                                    <i class="fas fa-trash text-sm"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Order Summary --}}
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-lg border border-gray-200 p-6 sticky top-24">
                        <h2 class="text-lg font-bold text-gray-900 mb-4">Tóm tắt đơn hàng</h2>
                        
                        @php
                            $discount = session('coupon.discount') ?? 0;
                            $payable = max(0, $grandTotal - $discount);
                        @endphp

                        <div class="space-y-3 mb-4 pb-4 border-b border-gray-200">
                            <div class="flex justify-between text-gray-700">
                                <span>Tạm tính</span>
                                <span class="font-semibold">{{ number_format($grandTotal, 0, ',', '.') }}₫</span>
                            </div>
                            
                            @if($discount > 0)
                            <div class="flex justify-between text-green-600">
                                <span>Giảm giá</span>
                                <span class="font-semibold">-{{ number_format($discount, 0, ',', '.') }}₫</span>
                            </div>
                            @endif
                            
                            <div class="flex justify-between items-center pt-2 border-t border-gray-200">
                                <span class="font-bold text-gray-900">Tổng cộng</span>
                                <span class="text-xl font-bold text-purple-600">{{ number_format($payable, 0, ',', '.') }}₫</span>
                            </div>
                        </div>

                        {{-- Coupon --}}
                        <div class="mb-4">
                            @if(session('coupon'))
                                <div class="bg-green-50 border border-green-200 rounded-lg p-3 mb-3">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <p class="text-sm font-semibold text-green-800">{{ session('coupon.code') }}</p>
                                            <p class="text-xs text-green-600">Giảm {{ number_format(session('coupon.discount'), 0, ',', '.') }}₫</p>
                                        </div>
                                        <form action="{{ route('cart.coupon.remove') }}" method="POST">
                                            @csrf
                                            <button type="submit" class="text-red-500 hover:text-red-700">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @else
                                <form action="{{ route('cart.coupon.apply') }}" method="POST" class="flex gap-2">
                                    @csrf
                                    <input type="text" name="code" placeholder="Mã giảm giá" 
                                        value="{{ old('code') }}" 
                                        class="flex-1 border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-purple-500 focus:border-purple-500 @error('code') border-red-400 @enderror"
                                        required>
                                    <button type="submit" class="px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white rounded-lg text-sm font-medium transition-colors">
                                        Áp dụng
                                    </button>
                                </form>
                                @error('code')
                                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            @endif
                        </div>

                        {{-- Checkout Button --}}
                        <div>
                            @if(!auth()->check())
                                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-3 text-center mb-3">
                                    <p class="text-sm text-yellow-800 mb-2">Vui lòng đăng nhập để thanh toán</p>
                                    <a href="{{ route('login') }}" 
                                       class="inline-block px-4 py-2 bg-yellow-500 hover:bg-yellow-600 text-white rounded-lg text-sm font-medium transition-colors">
                                        Đăng nhập
                                    </a>
                                </div>
                            @else
                                <a href="{{ route('checkout') }}"
                                    class="block w-full text-center bg-purple-600 hover:bg-purple-700 text-white font-semibold py-3 rounded-lg transition-colors">
                                    Thanh toán
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @else
            {{-- Empty Cart --}}
            <div class="text-center py-16 bg-white rounded-lg border border-gray-200">
                <div class="max-w-md mx-auto">
                    <div class="w-20 h-20 mx-auto mb-4 bg-gray-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-shopping-cart text-4xl text-gray-400"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-2">Giỏ hàng trống</h3>
                    <p class="text-gray-600 mb-6">Bạn chưa có sản phẩm nào trong giỏ hàng</p>
                    <a href="{{ route('products.index') }}"
                       class="inline-block px-6 py-3 bg-purple-600 hover:bg-purple-700 text-white font-semibold rounded-lg transition-colors">
                        Tiếp tục mua sắm
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>

@push('scripts')
<style>
    /* Ẩn spinner của input number */
    input[type=number].no-spinner::-webkit-inner-spin-button,
    input[type=number].no-spinner::-webkit-outer-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }
    input[type=number].no-spinner {
        -moz-appearance: textfield;
    }
</style>
<script>
    function changeQuantity(id, change) {
        const input = document.getElementById('qty-' + id);
        const newValue = parseInt(input.value) + change;
        if (newValue >= 1) {
            input.value = newValue;
            updateCart(id);
        }
    }

    function updateCart(id) {
        document.getElementById('update-' + id).click();
    }
</script>
@endpush
@endsection
