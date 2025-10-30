@extends('layouts.admin')

@section('title', 'Báo cáo doanh thu')

@section('content')
<div class="bg-white rounded-lg shadow-md overflow-hidden max-w-9xl mx-auto">
    <div class="bg-gradient-to-r from-green-500 to-teal-600 px-6 py-4">
        <div class="flex justify-between items-center">
            <h1 class="text-2xl font-bold text-white">Báo cáo doanh thu</h1>
            <div class="text-white flex items-center">
                <i class="fas fa-calendar-alt mr-2"></i>
                {{ \Carbon\Carbon::now()->format('d/m/Y') }}
            </div>
        </div>
    </div>

    <div class="px-6 py-8">
        <!-- Filter Section -->
        <div class="bg-gray-50 rounded-lg p-6 mb-6">
            <h2 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                <i class="fas fa-filter mr-2 text-green-600"></i> Lọc dữ liệu
            </h2>
            <form method="GET" class="flex flex-col md:flex-row gap-4 items-end">
                <div class="flex-1 grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Từ ngày</label>
                        <input type="date" name="from" value="{{ $from }}" 
                               class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 focus:border-transparent">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Đến ngày</label>
                        <input type="date" name="to" value="{{ $to }}" 
                               class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 focus:border-transparent">
                    </div>
                </div>
                <div class="flex gap-2">
                    <button type="submit" 
                            class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg font-medium transition duration-200 flex items-center">
                        <i class="fas fa-filter mr-2"></i>
                        Lọc dữ liệu
                    </button>
                    <a href="{{ route('admin.reports.sales') }}" 
                       class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg font-medium transition duration-200 flex items-center">
                        <i class="fas fa-times mr-2"></i>
                        Xóa lọc
                    </a>
                </div>
            </form>
        </div>

        <!-- Revenue Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
            <div class="bg-gradient-to-r from-green-500 to-teal-600 rounded-lg shadow-lg p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-green-100">Tổng doanh thu</p>
                        <h3 class="text-2xl font-bold mt-2">{{ number_format($totalRevenue, 0, ',', '.') }}₫</h3>
                        <p class="text-green-100 text-sm mt-1">Khi giao thành công</p>
                    </div>
                    <div class="bg-green-400 bg-opacity-20 p-3 rounded-full">
                        <i class="fas fa-chart-line text-2xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600">Tổng đơn hàng</p>
                        <h3 class="text-2xl font-bold text-gray-900 mt-2">{{ $orders->count() }}</h3>
                        <p class="text-gray-500 text-sm mt-1">Trong khoảng thời gian</p>
                    </div>
                    <div class="bg-blue-100 p-3 rounded-full">
                        <i class="fas fa-shopping-bag text-2xl text-blue-600"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600">Sản phẩm bán chạy</p>
                        <h3 class="text-2xl font-bold text-gray-900 mt-2">{{ $topProducts->count() }}</h3>
                        <p class="text-gray-500 text-sm mt-1">Top sản phẩm</p>
                    </div>
                    <div class="bg-purple-100 p-3 rounded-full">
                        <i class="fas fa-star text-2xl text-purple-600"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600">Thời gian</p>
                        <h3 class="text-lg font-bold text-gray-900 mt-2">
                            {{ \Carbon\Carbon::parse($from)->format('d/m') }} - {{ \Carbon\Carbon::parse($to)->format('d/m/Y') }}
                        </h3>
                        <p class="text-gray-500 text-sm mt-1">Khoảng thời gian</p>
                    </div>
                    <div class="bg-orange-100 p-3 rounded-full">
                        <i class="fas fa-calendar text-2xl text-orange-600"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Top Products Section -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden mb-6">
            <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                <h2 class="text-xl font-semibold text-gray-800 flex items-center">
                    <i class="fas fa-trophy mr-2 text-yellow-500"></i>
                    Top sản phẩm bán chạy
                </h2>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sản phẩm</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Số lượng</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Doanh thu</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tỷ lệ</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($topProducts as $index => $row)
                        <tr class="hover:bg-gray-50 transition duration-150">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center gap-3">
                                    <span class="flex items-center justify-center w-8 h-8 bg-green-100 text-green-600 rounded-full text-sm font-bold">
                                        {{ $index + 1 }}
                                    </span>
                                    <div>
                                        <div class="font-medium text-gray-900">{{ optional($row->product)->name ?? 'N/A' }}</div>
                                        <div class="text-sm text-gray-500">Mã: {{ optional($row->product)->id ?? 'N/A' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800">
                                    {{ $row->qty }} sản phẩm
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap font-semibold text-gray-900">
                                {{ number_format($row->revenue, 0, ',', '.') }}₫
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    $percentage = $totalRevenue > 0 ? ($row->revenue / $totalRevenue) * 100 : 0;
                                @endphp
                                <div class="flex items-center gap-2">
                                    <div class="w-20 bg-gray-200 rounded-full h-2">
                                        <div class="bg-green-600 h-2 rounded-full" style="width: {{ $percentage }}%"></div>
                                    </div>
                                    <span class="text-sm text-gray-600 mx-7">{{ number_format($percentage, 1) }}%</span>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Orders Section -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden mb-6">
            <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                <h2 class="text-xl font-semibold text-gray-800 flex items-center">
                    <i class="fas fa-receipt mr-2 text-blue-500"></i>
                    Danh sách đơn hàng
                    <span class="ml-2 bg-blue-100 text-blue-600 text-sm px-2 py-1 rounded-full">{{ $orders->count() }}</span>
                </h2>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mã đơn</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Khách hàng</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tổng tiền</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Trạng thái</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ngày đặt</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($orders as $order)
                        <tr class="hover:bg-gray-50 transition duration-150">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="font-medium text-gray-900">#{{ $order->order_code ?? $order->id }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-medium text-gray-900">{{ $order->customer_name }}</div>
                                <div class="text-sm text-gray-500">{{ $order->customer_phone ?? 'N/A' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap font-semibold text-green-600">
                                {{ number_format($order->total, 0, ',', '.') }}₫
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    $statusColors = [
                                        'pending' => 'bg-yellow-100 text-yellow-800',
                                        'confirmed' => 'bg-blue-100 text-blue-800',
                                        'shipping' => 'bg-purple-100 text-purple-800',
                                        'completed' => 'bg-green-100 text-green-800',
                                        'cancelled' => 'bg-red-100 text-red-800',
                                        'paid' => 'bg-green-100 text-green-800' // Thêm trạng thái paid
                                    ];
                                    $statusColor = $statusColors[$order->status] ?? 'bg-gray-100 text-gray-800';
                                    
                                    $statusText = [
                                        'pending' => 'Chờ xử lý',
                                        'confirmed' => 'Đã xác nhận',
                                        'shipping' => 'Đang giao hàng',
                                        'completed' => 'Hoàn thành',
                                        'cancelled' => 'Đã hủy',
                                        'paid' => 'Đã thanh toán' // Thêm text cho paid
                                    ];
                                    $displayStatus = $statusText[$order->status] ?? ucfirst($order->status);
                                @endphp
                                <span class="px-2 py-1 text-xs font-medium rounded-full {{ $statusColor }}">
                                    {{ $displayStatus }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                {{ \Carbon\Carbon::parse($order->created_at)->format('d/m/Y H:i') }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            @if($orders->hasPages())
            <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
                {{ $orders->links() }}
            </div>
            @endif
        </div>

        <!-- Export Section -->
        <div class="bg-gray-50 rounded-lg p-6">
            <div class="flex flex-col md:flex-row justify-between items-center">
                <div class="mb-4 md:mb-0">
                    <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                        <i class="fas fa-download mr-2 text-green-600"></i> Xuất báo cáo
                    </h3>
                    <p class="text-gray-600">Tải báo cáo dưới dạng file Excel hoặc PDF</p>
                </div>
                <div class="flex gap-3">
                    <button class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-medium transition duration-200 flex items-center">
                        <i class="fas fa-file-excel mr-2"></i>
                        Excel
                    </button>
                    <button class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg font-medium transition duration-200 flex items-center">
                        <i class="fas fa-file-pdf mr-2"></i>
                        PDF
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
@endsection