@extends('layouts.admin')

@section('title', 'Danh sách đơn hàng')

@section('content')
    <div class="bg-white rounded-lg shadow-md overflow-hidden max-w-9xl mx-auto">
        <div class="bg-gradient-to-r from-green-500 to-teal-600 px-6 py-4">
            <div class="flex justify-between items-center">
                <h1 class="text-2xl font-bold text-white">Quản lý đơn hàng</h1>
            </div>
        </div>

        <div class="px-6 py-8">
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                    {{ session('success') }}
                </div>
            @endif

            <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 rounded-lg">
                <table class="min-w-full divide-y divide-gray-300">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Khách hàng</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tổng tiền</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Trạng thái</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Hình thức</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ngày tạo</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Hành động</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($orders as $order)
                            <tr class="hover:bg-gray-50 transition duration-150">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-sm font-medium text-gray-900">#{{ $order->id }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $order->customer_name }}</div>
                                    @if($order->customer_email)
                                        <div class="text-sm text-gray-500">{{ $order->customer_email }}</div>
                                    @endif
                                    @if($order->customer_phone)
                                        <div class="text-sm text-gray-500">{{ $order->customer_phone }}</div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-sm font-semibold text-green-600">{{ number_format($order->total, 0, ',', '.') }}₫</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                        // ĐẦY ĐỦ các trạng thái tiếng Anh có thể có
                                        $statusColors = [
                                            'pending' => 'bg-yellow-100 text-yellow-800',
                                            'confirmed' => 'bg-blue-100 text-blue-800',
                                            'shipping' => 'bg-purple-100 text-purple-800',
                                            'completed' => 'bg-green-100 text-green-800',
                                            'cancelled' => 'bg-red-100 text-red-800',
                                            'delivered' => 'bg-green-100 text-green-800',
                                            'processing' => 'bg-blue-100 text-blue-800',
                                            'failed' => 'bg-red-100 text-red-800',
                                            'success' => 'bg-green-100 text-green-800',
                                            'paid' => 'bg-green-100 text-green-800',
                                            'unpaid' => 'bg-yellow-100 text-yellow-800',
                                            'error' => 'bg-red-100 text-red-800',
                                            'total' => 'bg-green-100 text-green-800'
                                        ];

                                        $statusLabels = [
                                            'pending' => 'Chờ xác nhận',
                                            'confirmed' => 'Đã xác nhận',
                                            'shipping' => 'Đang giao',
                                            'completed' => 'Hoàn thành',
                                            'cancelled' => 'Đã hủy',
                                            'delivered' => 'Đã giao',
                                            'processing' => 'Đang xử lý',
                                            'failed' => 'Thất bại',
                                            'success' => 'Thành công',
                                            'paid' => 'Đã thanh toán',
                                            'unpaid' => 'Chưa thanh toán',
                                            'error' => 'Lỗi',
                                            'total' => 'Hoàn thành'
                                        ];

                                        $currentStatus = strtolower($order->status);
                                        $statusText = $statusLabels[$currentStatus] ?? ucfirst($currentStatus);
                                        $statusColor = $statusColors[$currentStatus] ?? 'bg-gray-100 text-gray-800';
                                    @endphp
                                    <span class="px-2 py-1 text-xs font-medium rounded-full {{ $statusColor }}">
                                        {{ $statusText }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                        $paymentMethods = [
                                            'cod' => 'Thanh toán khi nhận hàng',
                                            'banking' => 'Chuyển khoản',
                                            'momo' => 'Ví MoMo',
                                            'credit_card' => 'Thẻ tín dụng',
                                            'cash' => 'Tiền mặt'
                                        ];
                                    @endphp
                                    <span class="text-sm text-gray-700">
                                        {{ $paymentMethods[$order->payment_method] ?? $order->payment_method }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-sm text-gray-700">{{ $order->created_at->format('d/m/Y H:i') }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-center">
                                    <div class="flex justify-center space-x-2">
                                        <a href="{{ route('admin.orders.show', $order->id) }}"
                                            class="text-green-600 hover:text-green-900 transition duration-200 flex items-center">
                                            <i class="fas fa-eye mr-1"></i> Xem
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Phân trang -->
            <div class="mt-6">
                {{ $orders->links() }}
            </div>
        </div>
    </div>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
@endsection