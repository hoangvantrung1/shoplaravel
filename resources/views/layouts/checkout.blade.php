<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thanh toán</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-50">
    <div class="container mx-auto px-4 py-8 max-w-5xl">
        <!-- Header -->
        <header class="flex justify-between items-center mb-8 p-4 bg-white rounded-lg shadow">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Thanh toán</h1>
                <p class="text-gray-600">Hoàn tất đơn hàng của bạn</p>
            </div>
            <div class="flex items-center">
                <div class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm font-semibold">
                    <i class="fas fa-shopping-cart mr-1"></i>
                    <span id="cart-count">{{ count($cart) }}</span> sản phẩm
                </div>
            </div>
        </header>

        <!-- Progress Steps -->
        <div class="mb-8">
            <div class="flex items-center justify-between mb-2">
                <div class="step flex items-center text-blue-600">
                    <div class="h-8 w-8 rounded-full bg-blue-600 text-white flex items-center justify-center">1</div>
                    <div class="ml-2 font-medium">Giỏ hàng</div>
                </div>
                <div class="flex-1 h-1 bg-blue-600 mx-2"></div>
                <div class="step flex items-center text-blue-600">
                    <div class="h-8 w-8 rounded-full bg-blue-600 text-white flex items-center justify-center">2</div>
                    <div class="ml-2 font-medium">Thông tin</div>
                </div>
                <div class="flex-1 h-1 bg-blue-600 mx-2"></div>
                <div class="step flex items-center text-blue-600">
                    <div class="h-8 w-8 rounded-full bg-blue-600 text-white flex items-center justify-center">3</div>
                    <div class="ml-2 font-medium">Thanh toán</div>
                </div>
            </div>
        </div>

        <div class="flex flex-col md:flex-row gap-6">
            <!-- Left Column - Form -->
            <div class="md:w-2/3">
                <form action="{{ route('checkout.store') }}" method="POST" id="checkout-form">
                    @csrf
                    
                    <div class="bg-white rounded-lg shadow mb-6 overflow-hidden">
                        <div class="bg-gray-50 px-6 py-4 border-b">
                            <h2 class="text-lg font-semibold text-gray-800">
                                <i class="fas fa-user-circle mr-2 text-blue-600"></i>
                                Thông tin khách hàng
                            </h2>
                        </div>
                        <div class="p-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="customer_name" class="block text-sm font-medium text-gray-700 mb-1">Họ và tên *</label>
                                    <input type="text" name="customer_name" id="customer_name" value="{{ old('customer_name') }}"
                                        class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                        required>
                                    @error('customer_name') 
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p> 
                                    @enderror
                                </div>

                                <div>
                                    <label for="customer_email" class="block text-sm font-medium text-gray-700 mb-1">Email *</label>
                                    <input type="email" name="customer_email" id="customer_email" value="{{ old('customer_email') }}"
                                        class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                        required>
                                    @error('customer_email') 
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p> 
                                    @enderror
                                </div>

                                <div>
                                    <label for="customer_phone" class="block text-sm font-medium text-gray-700 mb-1">Số điện thoại *</label>
                                    <input type="text" name="customer_phone" id="customer_phone" value="{{ old('customer_phone') }}"
                                        class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                        required>
                                    @error('customer_phone') 
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p> 
                                    @enderror
                                </div>
                            </div>

                            <div class="mt-4">
                                <label for="customer_address" class="block text-sm font-medium text-gray-700 mb-1">Địa chỉ giao hàng *</label>
                                <textarea name="customer_address" id="customer_address" rows="3"
                                    class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                    required>{{ old('customer_address') }}</textarea>
                                @error('customer_address') 
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p> 
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-lg shadow mb-6 overflow-hidden">
                        <div class="bg-gray-50 px-6 py-4 border-b">
                            <h2 class="text-lg font-semibold text-gray-800">
                                <i class="fas fa-credit-card mr-2 text-blue-600"></i>
                                Phương thức thanh toán
                            </h2>
                        </div>
                        <div class="p-6">
                            <div class="grid grid-cols-1 gap-4">
                                <div class="flex items-center p-4 border rounded-lg cursor-pointer payment-method" data-method="cod">
                                    <input type="radio" name="payment_method" id="cod" value="cod" class="h-5 w-5 text-blue-600" checked>
                                    <label for="cod" class="ml-3 flex items-center">
                                        <span class="block text-sm font-medium text-gray-700">
                                            <i class="fas fa-money-bill-wave mr-2 text-green-500"></i>
                                            Thanh toán khi nhận hàng (COD)
                                        </span>
                                    </label>
                                </div>

                                <div class="flex items-center p-4 border rounded-lg cursor-pointer payment-method" data-method="vnpay">
                                    <input type="radio" name="payment_method" id="vnpay" value="vnpay" class="h-5 w-5 text-blue-600">
                                    <label for="vnpay" class="ml-3 flex items-center">
                                        <span class="block text-sm font-medium text-gray-700">
                                            <i class="fas fa-qrcode mr-2 text-purple-500"></i>
                                            Thanh toán trực tuyến VNPAY
                                        </span>
                                    </label>
                                </div>
                            </div>
                            @error('payment_method') 
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p> 
                            @enderror
                            
                            <div id="vnpay-info" class="mt-4 p-4 bg-blue-50 rounded-lg hidden">
                                <p class="text-sm text-blue-700">
                                    <i class="fas fa-info-circle mr-1"></i>
                                    Bạn sẽ được chuyển đến cổng thanh toán VNPAY để hoàn tất giao dịch
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-lg shadow overflow-hidden">
                        <div class="bg-gray-50 px-6 py-4 border-b">
                            <h2 class="text-lg font-semibold text-gray-800">
                                <i class="fas fa-shopping-bag mr-2 text-blue-600"></i>
                                Đơn hàng của bạn
                            </h2>
                        </div>
                        <div class="p-6">
                            <div class="divide-y">
                                @foreach($cart as $item)
                                    <div class="py-4 flex">
                                        <div class="h-16 w-16 flex-shrink-0 overflow-hidden rounded-md border border-gray-200">
                                            <img src="{{ $item['image'] ?? 'https://via.placeholder.com/150' }}" 
                                                alt="{{ $item['name'] }}" 
                                                class="h-full w-full object-cover object-center">
                                        </div>
                                        <div class="ml-4 flex flex-1 flex-col">
                                            <div>
                                                <div class="flex justify-between text-base font-medium text-gray-900">
                                                    <h3>{{ $item['name'] }}</h3>
                                                    <p class="ml-4">{{ number_format($item['price'], 0, ',', '.') }}₫</p>
                                                </div>
                                            </div>
                                            <div class="flex flex-1 items-end justify-between text-sm">
                                                <p class="text-gray-500">Số lượng: {{ $item['quantity'] }}</p>
                                                <div class="flex">
                                                    <p class="font-medium text-blue-600">
                                                        {{ number_format(($item['price'] ?? 0) * ($item['quantity'] ?? 1), 0, ',', '.') }}₫
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <div class="border-t border-gray-200 mt-6 pt-6">
                                <div class="flex justify-between text-base font-medium text-gray-900 mb-3">
                                    <p>Tạm tính</p>
                                    <p>{{ number_format($grandTotal, 0, ',', '.') }}₫</p>
                                </div>
                                <div class="flex justify-between text-sm text-gray-600 mb-3">
                                    <p>Phí vận chuyển</p>
                                    <p>Miễn phí</p>
                                </div>
                                <div class="flex justify-between text-lg font-bold text-gray-900 pt-3 border-t border-gray-200">
                                    <p>Tổng cộng</p>
                                    <p class="text-blue-600">{{ number_format($grandTotal, 0, ',', '.') }}₫</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-6 flex flex-col sm:flex-row justify-between items-center">
                        <a href="{{ route('cart.index') }}" class="flex items-center text-blue-600 hover:text-blue-800 mb-4 sm:mb-0">
                            <i class="fas fa-arrow-left mr-2"></i>
                            Quay lại giỏ hàng
                        </a>
                        <button type="submit" id="submit-button" 
                            class="w-full sm:w-auto px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg shadow-md transition duration-300">
                            <i class="fas fa-check-circle mr-2"></i>
                            Hoàn tất đơn hàng
                        </button>
                    </div>
                </form>
            </div>

            <!-- Right Column - Order Summary -->
            <div class="md:w-1/3">
                <div class="bg-white rounded-lg shadow sticky top-4">
                    <div class="p-5 border-b">
                        <h3 class="text-lg font-semibold text-gray-800">Tóm tắt đơn hàng</h3>
                    </div>
                    <div class="p-5">
                        <div class="mb-4">
                            <div class="flex justify-between mb-2">
                                <span class="text-gray-600">Số sản phẩm:</span>
                                <span class="font-medium">{{ count($cart) }}</span>
                            </div>
                            <div class="flex justify-between mb-2">
                                <span class="text-gray-600">Tổng tiền:</span>
                                <span class="font-medium">{{ number_format($grandTotal, 0, ',', '.') }}₫</span>
                            </div>
                            <div class="flex justify-between mb-2">
                                <span class="text-gray-600">Phí vận chuyển:</span>
                                <span class="font-medium text-green-600">Miễn phí</span>
                            </div>
                            <div class="flex justify-between mt-3 pt-3 border-t border-gray-200">
                                <span class="text-lg font-bold text-gray-800">Thành tiền:</span>
                                <span class="text-lg font-bold text-blue-600">{{ number_format($grandTotal, 0, ',', '.') }}₫</span>
                            </div>
                        </div>

                        <div class="mt-6 bg-blue-50 p-4 rounded-lg">
                            <h4 class="font-medium text-blue-800 mb-2">
                                <i class="fas fa-shield-alt mr-2"></i>Bảo mật giao dịch
                            </h4>
                            <p class="text-xs text-blue-600">
                                Thông tin cá nhân của bạn sẽ được bảo mật và chỉ sử dụng cho mục đích giao dịch 
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const paymentMethods = document.querySelectorAll('.payment-method');
            const vnpayInfo = document.getElementById('vnpay-info');
            
            paymentMethods.forEach(method => {
                method.addEventListener('click', function() {
                    const selectedMethod = this.getAttribute('data-method');
                    const radio = this.querySelector('input[type="radio"]');
                    radio.checked = true;
                    
                    if (selectedMethod === 'vnpay') {
                        vnpayInfo.classList.remove('hidden');
                    } else {
                        vnpayInfo.classList.add('hidden');
                    }
                });
            });

            // Xác nhận trước khi gửi form
            const form = document.getElementById('checkout-form');
            form.addEventListener('submit', function(e) {
                const submitButton = document.getElementById('submit-button');
                submitButton.disabled = true;
                submitButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Đang xử lý...';
            });
        });
    </script>
</body>
</html>