@extends('layouts.admin')

@section('content')
    <div class="mb-6 flex justify-between items-center">
        <h1 class="text-3xl font-bold text-gray-800">Chi Tiết Đơn Hàng #{{ $order->id }}</h1>
                    @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                    {{ session('success') }}
                </div>
            @endif

    </div>

    <div class="grid lg:grid-cols-3 gap-6">
        <!-- Thông tin khách hàng -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 bg-gradient-to-r from-blue-50 to-indigo-50 border-b">
                    <h2 class="text-xl font-semibold text-gray-800">Thông tin khách hàng</h2>
                </div>
                <div class="p-6 space-y-4">
                    <div class="grid md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Họ tên</label>
                            <p class="text-gray-800 font-medium">{{ $order->customer_name }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Email</label>
                            <p class="text-gray-800">{{ $order->customer_email }}</p>
                        </div>
                    </div>
                    <div class="grid md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Số điện thoại</label>
                            <p class="text-gray-800">{{ $order->customer_phone }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Mã đơn hàng</label>
                            <p class="text-gray-800 font-mono">{{ $order->order_code }}</p>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">Địa chỉ giao hàng</label>
                        <p class="text-gray-800">{{ $order->customer_address }}</p>
                    </div>
                </div>
            </div>

            <!-- Danh sách sản phẩm -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 bg-gradient-to-r from-blue-50 to-indigo-50 border-b">
                    <h2 class="text-xl font-semibold text-gray-800">Sản phẩm đã đặt</h2>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        @forelse($order->orderItems as $item)
                            <div class="flex items-start justify-between p-4 rounded-lg border border-gray-100 hover:bg-gray-50 transition duration-200">
                                <div class="flex items-start space-x-4">
                                    <div class="w-16 h-16 bg-gray-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                        @if($item->product && $item->product->image)
                                            <!-- Kiểm tra và hiển thị ảnh đúng cách -->
                                            @php
                                                $imagePath = $item->product->image;
                                                // Kiểm tra xem đường dẫn ảnh có hợp lệ không
                                                if (strpos($imagePath, 'http') === 0 || file_exists(public_path($imagePath)) || file_exists(storage_path('app/public/' . $imagePath))) {
                                                    $imageUrl = strpos($imagePath, 'http') === 0 ? $imagePath : (file_exists(public_path($imagePath)) ? asset($imagePath) : asset('storage/' . $imagePath));
                                                } else {
                                                    $imageUrl = null;
                                                }
                                            @endphp
                                            
                                            @if($imageUrl)
                                                <img src="{{ $imageUrl }}" alt="{{ $item->product->name }}" 
                                                     class="w-full h-full object-cover rounded-lg">
                                            @else
                                                <div class="w-full h-full bg-gray-200 rounded-lg flex items-center justify-center">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                    </svg>
                                                </div>
                                            @endif
                                        @else
                                            <div class="w-full h-full bg-gray-200 rounded-lg flex items-center justify-center">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                </svg>
                                            </div>
                                        @endif
                                    </div>
                                    <div>
                                        <h3 class="font-medium text-gray-800">{{ $item->product->name ?? 'Sản phẩm đã xóa' }}</h3>
                                        <p class="text-sm text-gray-500 mt-1 line-clamp-2">{{ $item->product->description ?? '' }}</p>
                                        <div class="flex items-center mt-2 text-sm text-gray-600">
                                            <span class="mr-4">Số lượng: {{ $item->quantity }}</span>
                                            <span>Đơn giá: {{ number_format($item->price, 0, ',', '.') }} đ</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="font-semibold text-gray-800">{{ number_format($item->price * $item->quantity, 0, ',', '.') }} đ</p>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-8">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                </svg>
                                <p class="mt-2 text-gray-500">Không có sản phẩm nào trong đơn hàng.</p>
                            </div>
                        @endforelse
                    </div>
                    
                    <div class="mt-6 pt-4 border-t border-gray-200">
                        <div class="flex justify-between items-center text-lg font-semibold text-gray-800">
                            <span>Tổng cộng:</span>
                            <span class="text-xl">{{ number_format($order->total, 0, ',', '.') }} đ</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Cập nhật trạng thái & Thông tin bổ sung -->
        <div class="space-y-6">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 bg-gradient-to-r from-blue-50 to-indigo-50 border-b">
            <h2 class="text-xl font-semibold text-gray-800">Cập nhật trạng thái</h2>
        </div>
        <div class="p-6">
            @php
                $statuses = [
                    'pending' => ['label' => 'Chờ xử lý', 'color' => 'bg-yellow-100 text-yellow-800'],
                    'processing' => ['label' => 'Đang xử lý', 'color' => 'bg-blue-100 text-blue-800'],
                    'completed' => ['label' => 'Đã hoàn thành', 'color' => 'bg-green-100 text-green-800'],
                    'cancelled' => ['label' => 'Đã hủy', 'color' => 'bg-red-100 text-red-800'],
                ];

                // Xác định trạng thái có thể chuyển đổi
                $allowedStatuses = [];
                
                switch($order->status) {
                    case 'pending':
                        $allowedStatuses = ['processing', 'cancelled'];
                        break;
                    case 'processing':
                        $allowedStatuses = ['completed', 'cancelled'];
                        break;
                    case 'completed':
                        $allowedStatuses = [];
                        break;
                    case 'cancelled':
                        $allowedStatuses = [];
                        break;
                    default:
                        $allowedStatuses = ['processing', 'cancelled'];
                }
            @endphp
            
            <div class="mb-4">
                <span class="text-sm font-medium text-gray-500">Trạng thái hiện tại:</span>
                <span class="ml-2 px-3 py-1 rounded-full text-sm font-medium {{ $statuses[$order->status]['color'] }}">
                    {{ $statuses[$order->status]['label'] }}
                </span>
            </div>
            
            @if(!empty($allowedStatuses) && in_array($order->status, ['pending', 'processing']))
                <form action="{{ route('admin.orders.updateStatus', $order->id) }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Thay đổi trạng thái:</label>
                        <select name="status" id="status" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="{{ $order->status }}" selected disabled>
                                {{ $statuses[$order->status]['label'] }} (hiện tại)
                            </option>
                            @foreach($allowedStatuses as $status)
                                <option value="{{ $status }}">
                                    {{ $statuses[$status]['label'] }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg transition duration-200 flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                        </svg>
                        Cập nhật trạng thái
                    </button>
                </form>
            @else
                <div class="bg-gray-100 border border-gray-200 rounded-lg p-4 text-center">
                    <p class="text-gray-600 font-medium">Đơn hàng đã kết thúc không thể thay đổi trạng thái</p>
                    <p class="text-sm text-gray-500 mt-1">
                        Trạng thái "{{ $statuses[$order->status]['label'] }}" là trạng thái cuối cùng
                    </p>
                </div>
            @endif
        </div>
    </div>

            <!-- Thông tin bổ sung -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 bg-gradient-to-r from-blue-50 to-indigo-50 border-b">
                    <h2 class="text-xl font-semibold text-gray-800">Thông tin đơn hàng</h2>
                </div>
                <div class="p-6 space-y-3">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Ngày tạo:</span>
                        <span class="font-medium">{{ $order->created_at->format('d/m/Y H:i') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Số sản phẩm:</span>
                        <span class="font-medium">{{ $order->orderItems->count() }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Phương thức thanh toán:</span>
                        <span class="font-medium">Thanh toán khi nhận hàng</span>
                    </div>
                </div>
            </div>
            <a href="{{ route('admin.orders.index') }}" 
                    class="inline-flex items-center px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg transition duration-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M9.707 14.707a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 1.414L7.414 9H15a1 1 0 110 2H7.414l2.293 2.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                        </svg>
                        Quay lại danh sách
            </a>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
    </style>
@endpush