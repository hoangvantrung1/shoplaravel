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
                        // ĐẦY ĐỦ các trạng thái
                        $statuses = [
                            'unpaid' => ['label' => 'Chưa thanh toán', 'color' => 'bg-yellow-100 text-yellow-800'],
                            'paid' => ['label' => 'Đã thanh toán', 'color' => 'bg-green-100 text-green-800'],
                            'pending' => ['label' => 'Chờ xác nhận', 'color' => 'bg-yellow-100 text-yellow-800'],
                            'confirmed' => ['label' => 'Đã xác nhận', 'color' => 'bg-blue-100 text-blue-800'],
                            'processing' => ['label' => 'Đang xử lý', 'color' => 'bg-indigo-100 text-indigo-800'],
                            'shipping' => ['label' => 'Đang giao hàng', 'color' => 'bg-purple-100 text-purple-800'],
                            'delivered' => ['label' => 'Đã giao hàng', 'color' => 'bg-green-100 text-green-800'],
                            'completed' => ['label' => 'Hoàn thành', 'color' => 'bg-emerald-100 text-emerald-800'],
                            'cancelled' => ['label' => 'Đã hủy', 'color' => 'bg-red-100 text-red-800'],
                        ];

                        // Đảm bảo không bị lỗi với bất kỳ trạng thái nào
                        $currentStatus = strtolower($order->status);
                        $statusColor = $statuses[$currentStatus]['color'] ?? 'bg-gray-100 text-gray-800';
                        $statusLabel = $statuses[$currentStatus]['label'] ?? ucfirst($currentStatus);
                        
                        // LOGIC chuyển đổi trạng thái ĐẦY ĐỦ
                        $allowedStatuses = [];
                        
                        switch($currentStatus) {
                            case 'unpaid':
                                // Chưa thanh toán -> Xác nhận hoặc Hủy
                                $allowedStatuses = ['confirmed', 'cancelled'];
                                break;
                            case 'paid':
                                $allowedStatuses = ['pending', 'confirmed', 'cancelled'];
                                break;
                            case 'pending':
                                // Chờ xác nhận -> Đã xác nhận, Đang xử lý hoặc Hủy
                                $allowedStatuses = ['confirmed', 'processing', 'cancelled'];
                                break;
                            case 'confirmed':
                                // Đã xác nhận -> Đang xử lý, Đang giao hàng hoặc Hủy
                                $allowedStatuses = ['processing', 'shipping', 'cancelled'];
                                break;
                            case 'processing':
                                // Đang xử lý -> Đang giao hàng, Đã giao hàng, Hoàn thành hoặc Hủy
                                $allowedStatuses = ['shipping', 'delivered', 'completed', 'cancelled'];
                                break;          
                            case 'shipping':
                                // Đang giao hàng -> Đã giao hàng, Hoàn thành hoặc Hủy
                                $allowedStatuses = ['delivered', 'completed', 'cancelled']; 
                                break;
                            case 'delivered':
                                // Đã giao hàng -> Hoàn thành
                                $allowedStatuses = ['completed']; 
                                break;
                                
                            // CÁC TRẠNG THÁI CUỐI CÙNG - KHÔNG THỂ THAY ĐỔI
                            case 'completed':
                            case 'cancelled':
                                $allowedStatuses = [];
                                break;
                            default:
                                // Mặc định cho các trạng thái khác
                                $allowedStatuses = ['confirmed', 'processing', 'cancelled'];
                        }
                        
                        // Xác định trạng thái nào là trạng thái cuối cùng
                        $finalStatuses = ['completed', 'cancelled'];
                        $isFinalStatus = in_array($currentStatus, $finalStatuses);
                    @endphp
                    
                    <div class="mb-4">
                        <span class="text-sm font-medium text-gray-500">Trạng thái hiện tại:</span>
                        <span class="ml-2 px-3 py-1 rounded-full text-sm font-medium {{ $statusColor }}">
                            {{ $statusLabel }}
                        </span>
                    </div>
                    
                    @if(!empty($allowedStatuses) && !$isFinalStatus)
                        <form action="{{ route('admin.orders.updateStatus', $order->id) }}" method="POST">
                            @csrf
                            <div class="mb-4">
                                <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Thay đổi trạng thái:</label>
                                <select name="status" id="status" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    @foreach($allowedStatuses as $status)
                                        <option value="{{ $status }}">
                                            {{ $statuses[$status]['label'] }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Ghi chú nội bộ -->
                            <div class="mb-4">
                                <label for="note" class="block text-sm font-medium text-gray-700 mb-2">Ghi chú nội bộ (tùy chọn):</label>
                                <textarea name="note" id="note" rows="3" 
                                    placeholder="Nhập ghi chú về thay đổi trạng thái..." 
                                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"></textarea>
                                <p class="text-xs text-gray-500 mt-1">Ghi chú này sẽ được lưu trong timeline của đơn hàng</p>
                            </div>
                            
                            @if($currentStatus === 'unpaid')
                            <div class="mb-4 p-3 bg-yellow-50 border border-yellow-200 rounded-lg">
                                <p class="text-sm text-yellow-700 flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                    </svg>
                                    <strong>Lưu ý:</strong> Đơn hàng chưa thanh toán. Có thể xác nhận đơn hoặc hủy đơn.
                                </p>
                            </div>
                            @endif
                            
                            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg transition duration-200 flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                </svg>
                                Cập nhật trạng thái
                            </button>
                        </form>
                    @else
                        <div class="bg-gray-100 border border-gray-200 rounded-lg p-4 text-center">
                            <p class="text-gray-600 font-medium">
                                @if($isFinalStatus)
                                    Đơn hàng đã kết thúc không thể thay đổi trạng thái
                                @else
                                    Không có trạng thái nào để chuyển đổi
                                @endif
                            </p>
                            <p class="text-sm text-gray-500 mt-1">
                                Trạng thái "{{ $statusLabel }}" {{ $isFinalStatus ? 'là trạng thái cuối cùng' : 'không thể chuyển trạng thái' }}
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
                        <span class="text-gray-600">Trạng thái thanh toán:</span>
                        <span class="font-medium {{ $order->status === 'unpaid' ? 'text-yellow-600' : 'text-green-600' }}">
                            {{ $order->status === 'unpaid' ? 'Chưa thanh toán' : 'Đã thanh toán' }}
                        </span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Hình thức thanh toán:</span>
                        <span class="font-medium 
                            {{ $order->payment_method === 'cod' ? 'text-orange-600' : 'text-blue-600' }}">
                            @if($order->payment_method === 'cod')
                                Thanh toán khi nhận hàng (COD)
                            @elseif($order->payment_method === 'vnpay')
                                Thanh toán VNPay
                            @else
                                {{ $order->payment_method ?? 'Chưa xác định' }}
                            @endif
                        </span>
                    </div>
                    @if($order->transaction_id)
                    <div class="flex justify-between">
                        <span class="text-gray-600">Mã giao dịch:</span>
                        <span class="font-medium text-gray-800 font-mono text-sm">
                            {{ $order->transaction_id }}
                        </span>
                    </div>
                    @endif
                    @if($order->bank_code && $order->bank_code !== 'COD')
                    <div class="flex justify-between">
                        <span class="text-gray-600">Ngân hàng:</span>
                        <span class="font-medium text-gray-800">
                            {{ $order->bank_code }}
                        </span>
                    </div>
                    @endif
                    @if($order->payment_date)
                    <div class="flex justify-between">
                        <span class="text-gray-600">Ngày thanh toán:</span>
                        <span class="font-medium text-gray-800">
                            {{ \Carbon\Carbon::parse($order->payment_date)->format('d/m/Y H:i') }}
                        </span>
                    </div>
                    @endif
                </div>
            </div>
            
            <!-- Timeline và Ghi chú -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 bg-gradient-to-r from-blue-50 to-indigo-50 border-b">
                    <h2 class="text-xl font-semibold text-gray-800">Timeline & Ghi chú</h2>
                </div>
                <div class="p-6">
                    <!-- Form thêm ghi chú -->
                    <form action="{{ route('admin.orders.addNote', $order->id) }}" method="POST" class="mb-6 pb-6 border-b border-gray-200">
                        @csrf
                        <div class="mb-4">
                            <label for="new_note" class="block text-sm font-medium text-gray-700 mb-2">Thêm ghi chú mới:</label>
                            <textarea name="note" id="new_note" rows="3" required
                                placeholder="Nhập ghi chú nội bộ..." 
                                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"></textarea>
                        </div>
                        <div class="mb-4">
                            <label class="flex items-center">
                                <input type="checkbox" name="is_internal" value="1" checked class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                <span class="ml-2 text-sm text-gray-700">Ghi chú nội bộ (chỉ admin mới thấy)</span>
                            </label>
                        </div>
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg transition duration-200">
                            <i class="fas fa-plus mr-2"></i> Thêm ghi chú
                        </button>
                    </form>

                    <!-- Timeline -->
                    <div class="space-y-4">
                        @forelse($order->notes as $note)
                            <div class="flex items-start space-x-4 p-4 rounded-lg {{ $note->is_internal ? 'bg-yellow-50 border border-yellow-200' : 'bg-gray-50 border border-gray-200' }}">
                                <div class="flex-shrink-0">
                                    <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center">
                                        <i class="fas fa-{{ $note->is_internal ? 'lock' : 'comment' }} text-blue-600"></i>
                                    </div>
                                </div>
                                <div class="flex-1">
                                    <div class="flex items-center justify-between mb-1">
                                        <div class="flex items-center space-x-2">
                                            <span class="text-sm font-medium text-gray-800">
                                                {{ $note->admin ? $note->admin->name : 'Hệ thống' }}
                                            </span>
                                            @if($note->is_internal)
                                                <span class="px-2 py-0.5 text-xs rounded-full bg-yellow-200 text-yellow-800">
                                                    Nội bộ
                                                </span>
                                            @endif
                                            @if($note->status)
                                                <span class="px-2 py-0.5 text-xs rounded-full bg-blue-100 text-blue-800">
                                                    {{ $statuses[strtolower($note->status)]['label'] ?? $note->status }}
                                                </span>
                                            @endif
                                        </div>
                                        <span class="text-xs text-gray-500">
                                            {{ $note->created_at->format('d/m/Y H:i') }}
                                        </span>
                                    </div>
                                    <p class="text-sm text-gray-700 whitespace-pre-wrap">{{ $note->note }}</p>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-8 text-gray-500">
                                <i class="fas fa-comments text-4xl mb-2"></i>
                                <p>Chưa có ghi chú nào</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <a href="{{ route('admin.orders.index') }}" 
                    class="inline-flex items-center px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg transition duration-200 mt-6">
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