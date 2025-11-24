@extends('layouts.client')

@section('title', 'Thanh toán')

@section('content')
<div class="min-h-screen bg-gray-50 pt-20 pb-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        
        {{-- Breadcrumb --}}
        <nav class="flex px-6 py-4 text-gray-700 border border-gray-200 rounded-2xl bg-white shadow-sm mb-8" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="{{ route('home') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-purple-600 transition-colors">
                        <i class="fa-solid fa-house mr-2"></i>
                        Trang chủ
                    </a>
                </li>
                <li>
                    <div class="flex items-center">
                        <i class="fa-solid fa-chevron-right text-gray-400 mx-2"></i>
                        <a href="{{ route('cart.index') }}" class="text-sm font-medium text-gray-700 hover:text-purple-600 transition-colors">Giỏ hàng</a>
                    </div>
                </li>
                <li aria-current="page">
                    <div class="flex items-center">
                        <i class="fa-solid fa-chevron-right text-gray-400 mx-2"></i>
                        <span class="text-sm font-medium text-purple-600">Thanh toán</span>
                    </div>
                </li>
            </ol>
        </nav>

        {{-- Header --}}
        <div class="mb-8">
            <div class="flex items-center justify-between flex-wrap gap-4">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Thanh toán</h1>
                    <p class="text-gray-600 mt-2">Hoàn tất đơn hàng của bạn</p>
                </div>
                <div class="flex items-center gap-2 bg-purple-100 text-purple-700 px-4 py-2 rounded-full text-sm font-semibold">
                    <i class="fas fa-shopping-cart"></i>
                    <span id="cart-count">{{ count($cart) }}</span> sản phẩm
                </div>
            </div>
        </div>

        {{-- Progress Steps --}}
        <div class="mb-8 bg-white rounded-xl shadow-sm p-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center flex-1">
                    <div class="flex items-center">
                        <div class="h-10 w-10 rounded-full bg-purple-600 text-white flex items-center justify-center font-semibold">
                            <i class="fas fa-check text-sm"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-semibold text-gray-900">Giỏ hàng</p>
                            <p class="text-xs text-gray-500">Đã hoàn thành</p>
                        </div>
                    </div>
                </div>
                <div class="flex-1 h-1 bg-purple-600 mx-4"></div>
                <div class="flex items-center flex-1">
                    <div class="flex items-center">
                        <div class="h-10 w-10 rounded-full bg-purple-600 text-white flex items-center justify-center font-semibold">
                            2
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-semibold text-gray-900">Thông tin</p>
                            <p class="text-xs text-gray-500">Đang xử lý</p>
                        </div>
                    </div>
                </div>
                <div class="flex-1 h-1 bg-gray-200 mx-4"></div>
                <div class="flex items-center flex-1">
                    <div class="flex items-center">
                        <div class="h-10 w-10 rounded-full bg-gray-200 text-gray-500 flex items-center justify-center font-semibold">
                            3
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-semibold text-gray-500">Thanh toán</p>
                            <p class="text-xs text-gray-400">Chưa bắt đầu</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Left Column - Form --}}
            <div class="lg:col-span-2 space-y-6">
                <form action="{{ route('checkout.store') }}" method="POST" id="checkout-form">
                    @csrf

                    {{-- Thông tin khách hàng --}}
                    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                        <div class="bg-gradient-to-r from-purple-50 to-indigo-50 px-6 py-4 border-b border-gray-200">
                            <h2 class="text-lg font-semibold text-gray-800 flex items-center">
                                <i class="fas fa-user-circle mr-2 text-purple-600"></i>
                                Thông tin khách hàng
                            </h2>
                        </div>
                        <div class="p-6">
                            @if(isset($addresses) && $addresses->count() > 0)
                                <div class="mb-6">
                                    <label for="saved-address" class="block text-sm font-medium text-gray-700 mb-2">
                                        <i class="fas fa-map-marker-alt mr-1 text-purple-500"></i>
                                        Chọn địa chỉ đã lưu
                                    </label>
                                    <select id="saved-address" 
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all">
                                        <option value="">-- Chọn địa chỉ --</option>
                                        @foreach($addresses as $addr)
                                            <option value="{{ $addr->id }}" 
                                                    data-name="{{ $addr->name }}"
                                                    data-phone="{{ $addr->phone }}"
                                                    data-email="{{ $addr->email ?? '' }}"
                                                    data-address="{{ $addr->address_line }}">
                                                {{ $addr->name }} - {{ $addr->phone }} | {{ Str::limit($addr->address_line, 50) }}
                                                {{ $addr->is_default ? '(Mặc định)' : '' }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <p class="text-xs text-gray-500 mt-2">
                                        <i class="fas fa-info-circle mr-1"></i>
                                        Chọn địa chỉ sẽ tự động điền thông tin bên dưới
                                    </p>
                                </div>
                            @endif

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="customer_name" class="block text-sm font-medium text-gray-700 mb-2">
                                        Họ và tên <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" 
                                           name="customer_name" 
                                           id="customer_name"
                                           value="{{ old('customer_name', auth()->user()->name ?? '') }}"
                                           class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all @error('customer_name') border-red-500 @enderror"
                                           required>
                                    @error('customer_name')
                                        <p class="mt-1 text-sm text-red-600 flex items-center">
                                            <i class="fas fa-exclamation-circle mr-1"></i>
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="customer_email" class="block text-sm font-medium text-gray-700 mb-2">
                                        Email <span class="text-red-500">*</span>
                                    </label>
                                    <input type="email" 
                                           name="customer_email" 
                                           id="customer_email"
                                           value="{{ old('customer_email', auth()->user()->email ?? '') }}"
                                           class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all @error('customer_email') border-red-500 @enderror"
                                           required>
                                    @error('customer_email')
                                        <p class="mt-1 text-sm text-red-600 flex items-center">
                                            <i class="fas fa-exclamation-circle mr-1"></i>
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="customer_phone" class="block text-sm font-medium text-gray-700 mb-2">
                                        Số điện thoại <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" 
                                           name="customer_phone" 
                                           id="customer_phone"
                                           value="{{ old('customer_phone') }}"
                                           class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all @error('customer_phone') border-red-500 @enderror"
                                           required>
                                    @error('customer_phone')
                                        <p class="mt-1 text-sm text-red-600 flex items-center">
                                            <i class="fas fa-exclamation-circle mr-1"></i>
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>
                            </div>

                            <div class="mt-4">
                                <label for="customer_address" class="block text-sm font-medium text-gray-700 mb-2">
                                    Địa chỉ giao hàng <span class="text-red-500">*</span>
                                </label>
                                <textarea name="customer_address" 
                                          id="customer_address" 
                                          rows="3"
                                          class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all @error('customer_address') border-red-500 @enderror"
                                          required>{{ old('customer_address') }}</textarea>
                                @error('customer_address')
                                    <p class="mt-1 text-sm text-red-600 flex items-center">
                                        <i class="fas fa-exclamation-circle mr-1"></i>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    {{-- Phương thức thanh toán --}}
                    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                        <div class="bg-gradient-to-r from-purple-50 to-indigo-50 px-6 py-4 border-b border-gray-200">
                            <h2 class="text-lg font-semibold text-gray-800 flex items-center">
                                <i class="fas fa-credit-card mr-2 text-purple-600"></i>
                                Phương thức thanh toán
                            </h2>
                        </div>
                        <div class="p-6">
                            <div class="space-y-3">
                                <label class="flex items-center p-4 border-2 rounded-lg cursor-pointer transition-all hover:border-purple-300 hover:bg-purple-50 payment-method @error('payment_method') border-red-500 @enderror"
                                       data-method="cod">
                                    <input type="radio" 
                                           name="payment_method" 
                                           id="cod" 
                                           value="cod"
                                           class="h-5 w-5 text-purple-600 focus:ring-purple-500" 
                                           checked>
                                    <div class="ml-3 flex-1">
                                        <div class="flex items-center">
                                            <i class="fas fa-money-bill-wave text-green-500 mr-2"></i>
                                            <span class="font-medium text-gray-900">Thanh toán khi nhận hàng (COD)</span>
                                        </div>
                                        <p class="text-sm text-gray-500 mt-1">Thanh toán bằng tiền mặt khi nhận hàng</p>
                                    </div>
                                </label>

                                <label class="flex items-center p-4 border-2 rounded-lg cursor-pointer transition-all hover:border-purple-300 hover:bg-purple-50 payment-method @error('payment_method') border-red-500 @enderror"
                                       data-method="vnpay">
                                    <input type="radio" 
                                           name="payment_method" 
                                           id="vnpay" 
                                           value="vnpay"
                                           class="h-5 w-5 text-purple-600 focus:ring-purple-500">
                                    <div class="ml-3 flex-1">
                                        <div class="flex items-center">
                                            <i class="fas fa-qrcode text-purple-500 mr-2"></i>
                                            <span class="font-medium text-gray-900">Thanh toán trực tuyến VNPAY</span>
                                        </div>
                                        <p class="text-sm text-gray-500 mt-1">Thanh toán qua thẻ ngân hàng, ví điện tử</p>
                                    </div>
                                </label>
                            </div>
                            
                            @error('payment_method')
                                <p class="mt-3 text-sm text-red-600 flex items-center">
                                    <i class="fas fa-exclamation-circle mr-1"></i>
                                    {{ $message }}
                                </p>
                            @enderror

                            <div id="vnpay-info" class="mt-4 p-4 bg-purple-50 border border-purple-200 rounded-lg hidden">
                                <p class="text-sm text-purple-700 flex items-center">
                                    <i class="fas fa-info-circle mr-2"></i>
                                    Bạn sẽ được chuyển đến cổng thanh toán VNPAY để hoàn tất giao dịch
                                </p>
                            </div>
                        </div>
                    </div>

                    {{-- Đơn hàng của bạn --}}
                    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                        <div class="bg-gradient-to-r from-purple-50 to-indigo-50 px-6 py-4 border-b border-gray-200">
                            <h2 class="text-lg font-semibold text-gray-800 flex items-center">
                                <i class="fas fa-shopping-bag mr-2 text-purple-600"></i>
                                Đơn hàng của bạn
                            </h2>
                        </div>
                        <div class="p-6">
                            <div class="divide-y divide-gray-200">
                                @foreach($cart as $item)
                                    <div class="py-4 flex items-center gap-4">
                                        <div class="h-20 w-20 flex-shrink-0 overflow-hidden rounded-lg border border-gray-200 bg-gray-50">
                                            <img src="{{ asset($item['image'] ?? 'https://via.placeholder.com/150') }}"
                                                 alt="{{ $item['name'] }}" 
                                                 class="h-full w-full object-cover object-center"
                                                 loading="lazy">
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <h3 class="text-base font-semibold text-gray-900 line-clamp-2">{{ $item['name'] }}</h3>
                                            <div class="mt-2 flex items-center justify-between">
                                                <p class="text-sm text-gray-500">Số lượng: <span class="font-medium text-gray-700">{{ $item['quantity'] }}</span></p>
                                                <p class="text-base font-semibold text-purple-600">
                                                    {{ number_format(($item['price'] ?? 0) * ($item['quantity'] ?? 1), 0, ',', '.') }}₫
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <div class="border-t border-gray-200 mt-6 pt-6 space-y-2">
                                <div class="flex justify-between text-base font-medium text-gray-900">
                                    <span>Tạm tính</span>
                                    <span>{{ number_format($grandTotal, 0, ',', '.') }}₫</span>
                                </div>

                                @if(session('coupon'))
                                    <div class="flex justify-between text-green-600">
                                        <span class="flex items-center">
                                            <i class="fas fa-tag mr-1"></i>
                                            Giảm giá ({{ session('coupon.code') }})
                                        </span>
                                        <span class="font-semibold">-{{ number_format(session('coupon.discount'), 0, ',', '.') }}₫</span>
                                    </div>
                                @endif

                                <div class="flex justify-between text-gray-600">
                                    <span>Phí vận chuyển:</span>
                                    <span class="text-green-600 font-medium">
                                        <i class="fas fa-check-circle mr-1"></i>
                                        Miễn phí
                                    </span>
                                </div>

                                <div class="flex justify-between font-bold text-lg border-t border-gray-200 pt-3 mt-3">
                                    <span class="text-gray-900">Thành tiền:</span>
                                    <span class="text-purple-600 text-xl">
                                        {{ number_format($grandTotal - (session('coupon.discount') ?? 0), 0, ',', '.') }}₫
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Buttons --}}
                    <div class="flex flex-col sm:flex-row justify-between items-center gap-4 pt-6">
                        <a href="{{ route('cart.index') }}"
                           class="flex items-center text-purple-600 hover:text-purple-700 font-medium transition-colors">
                            <i class="fas fa-arrow-left mr-2"></i>
                            Quay lại giỏ hàng
                        </a>
                        <button type="submit" 
                                id="submit-button"
                                class="w-full sm:w-auto px-8 py-3 bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-700 hover:to-indigo-700 text-white font-semibold rounded-lg shadow-lg transition-all duration-300 transform hover:scale-105 flex items-center justify-center">
                            <i class="fas fa-check-circle mr-2"></i>
                            Hoàn tất đơn hàng
                        </button>
                    </div>
                </form>
            </div>

            {{-- Right Column - Order Summary --}}
            <div class="lg:col-span-1">
                <div class="bg-white rounded-xl shadow-sm sticky top-24">
                    <div class="bg-gradient-to-r from-purple-50 to-indigo-50 px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                            <i class="fas fa-receipt mr-2 text-purple-600"></i>
                            Tóm tắt đơn hàng
                        </h3>
                    </div>
                    <div class="p-6 space-y-4">
                        <div class="space-y-3">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Số sản phẩm:</span>
                                <span class="font-medium text-gray-900">{{ count($cart) }}</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Tổng tiền:</span>
                                <span class="font-medium text-gray-900">{{ number_format($grandTotal, 0, ',', '.') }}₫</span>
                            </div>
                            @if(session('coupon'))
                                <div class="flex justify-between text-sm text-green-600">
                                    <span>Giảm giá:</span>
                                    <span class="font-medium">-{{ number_format(session('coupon.discount'), 0, ',', '.') }}₫</span>
                                </div>
                            @endif
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Phí vận chuyển:</span>
                                <span class="font-medium text-green-600">Miễn phí</span>
                            </div>
                            <div class="border-t border-gray-200 pt-3 mt-3">
                                <div class="flex justify-between">
                                    <span class="text-base font-bold text-gray-900">Thành tiền:</span>
                                    <span class="text-lg font-bold text-purple-600">
                                        {{ number_format($grandTotal - (session('coupon.discount') ?? 0), 0, ',', '.') }}₫
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="mt-6 bg-purple-50 border border-purple-200 p-4 rounded-lg">
                            <h4 class="font-semibold text-purple-800 mb-2 flex items-center">
                                <i class="fas fa-shield-alt mr-2"></i>
                                Bảo mật giao dịch
                            </h4>
                            <p class="text-xs text-purple-700 leading-relaxed">
                                Thông tin cá nhân của bạn sẽ được bảo mật và chỉ sử dụng cho mục đích giao dịch
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Payment method selection
        const paymentMethods = document.querySelectorAll('.payment-method');
        const vnpayInfo = document.getElementById('vnpay-info');

        paymentMethods.forEach(method => {
            method.addEventListener('click', function () {
                const selectedMethod = this.getAttribute('data-method');
                const radio = this.querySelector('input[type="radio"]');
                radio.checked = true;

                // Update border color
                paymentMethods.forEach(m => {
                    m.classList.remove('border-purple-500', 'bg-purple-50');
                    m.classList.add('border-gray-200');
                });
                this.classList.remove('border-gray-200');
                this.classList.add('border-purple-500', 'bg-purple-50');

                // Show/hide VNPay info
                if (selectedMethod === 'vnpay') {
                    vnpayInfo.classList.remove('hidden');
                } else {
                    vnpayInfo.classList.add('hidden');
                }
            });
        });

        // Set initial border for checked payment method
        const checkedMethod = document.querySelector('input[name="payment_method"]:checked');
        if (checkedMethod) {
            const parent = checkedMethod.closest('.payment-method');
            if (parent) {
                parent.classList.remove('border-gray-200');
                parent.classList.add('border-purple-500', 'bg-purple-50');
            }
        }

        // Form submission
        const form = document.getElementById('checkout-form');
        form.addEventListener('submit', function (e) {
            const submitButton = document.getElementById('submit-button');
            submitButton.disabled = true;
            submitButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Đang xử lý...';
        });

        // Auto fill from saved address
        const savedSelect = document.getElementById('saved-address');
        if (savedSelect) {
            savedSelect.addEventListener('change', function () {
                const option = this.options[this.selectedIndex];
                if (option.value) {
                    const name = option.getAttribute('data-name') || '';
                    const phone = option.getAttribute('data-phone') || '';
                    const email = option.getAttribute('data-email') || '';
                    const address = option.getAttribute('data-address') || '';

                    if (name) document.getElementById('customer_name').value = name;
                    if (phone) document.getElementById('customer_phone').value = phone;
                    if (email) document.getElementById('customer_email').value = email;
                    if (address) document.getElementById('customer_address').value = address;
                }
            });
        }
    });
</script>
@endpush
@endsection
