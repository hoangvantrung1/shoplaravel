@extends('layouts.admin')

@section('title', 'Dashboard Admin')

@section('content')
    <h1 class="text-3xl font-bold mb-6">Dashboard Admin</h1>

    <!-- Thống kê tổng quan -->
    <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white p-6 rounded-lg shadow text-center">
            <div class="text-gray-500 mb-2">Tổng đơn hàng</div>
            <div class="text-2xl font-bold text-blue-600">{{ $totalOrders }}</div>
        </div>
        <div class="bg-white p-6 rounded-lg shadow text-center">
            <div class="text-gray-500 mb-2">Tổng doanh thu</div>
            <div class="text-2xl font-bold text-green-600">{{ number_format($totalRevenue) }} đ</div>
        </div>
        <div class="bg-white p-6 rounded-lg shadow text-center">
            <div class="text-gray-500 mb-2">Đơn chờ xử lý</div>
            <div class="text-2xl font-bold text-yellow-600">{{ $statusCounts['pending'] }}</div>
        </div>
        <div class="bg-white p-6 rounded-lg shadow text-center">
            <div class="text-gray-500 mb-2">Đang xử lý</div>
            <div class="text-2xl font-bold text-blue-600">{{ $statusCounts['processing'] }}</div>
        </div>
    </div>
    <!-- Danh sách trạng thái đơn hàng -->


    <!-- Biểu đồ -->
    <div class="grid md:grid-cols-2 gap-6 mb-8">
        <div class="bg-white p-6 rounded-lg shadow mb-8">
            <h2 class="text-xl font-semibold mb-4">Thống kê trạng thái đơn hàng</h2>
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Trạng thái</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Số lượng</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <tr>
                        <td class="px-6 py-4">Chờ xử lý</td>
                        <td class="px-6 py-4">{{ $statusCounts['pending'] }}</td>
                    </tr>
                    <tr>
                        <td class="px-6 py-4">Đang xử lý</td>
                        <td class="px-6 py-4">{{ $statusCounts['processing'] }}</td>
                    </tr>
                    <tr>
                        <td class="px-6 py-4">Hoàn thành</td>
                        <td class="px-6 py-4">{{ $statusCounts['completed'] }}</td>
                    </tr>
                    <tr>
                        <td class="px-6 py-4">Đã hủy</td>
                        <td class="px-6 py-4">{{ $statusCounts['cancelled'] }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Doanh thu theo tháng -->
        <div class="bg-white p-6 rounded-lg shadow mb-8">
            <h2 class="text-xl font-semibold mb-4">Doanh thu theo tháng</h2>
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tháng</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Doanh thu (VNĐ)</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($monthlyRevenueFull as $i => $revenue)
                        <tr>
                            <td class="px-6 py-4">Tháng {{ $i + 1 }}</td>
                            <td class="px-6 py-4">{{ number_format($revenue) }} đ</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

    </div>

    <!-- Top sản phẩm bán chạy -->
    <div class="bg-white p-6 rounded-lg shadow">
        <h2 class="text-xl font-semibold mb-4">Top 5 sản phẩm bán chạy</h2>
        @if($topProducts->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Sản phẩm</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Số lượng bán</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($topProducts as $item)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    {{ $item->product->name ?? 'Sản phẩm đã xóa' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-sm">
                                        {{ $item->total_sold }} sản phẩm
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

        @else
            <p class="text-gray-500">Chưa có dữ liệu sản phẩm bán chạy.</p>
        @endif
    </div>

@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Biểu đồ trạng thái đơn hàng
            const ctxOrder = document.getElementById('orderChart');
            if (ctxOrder) {
                new Chart(ctxOrder, {
                    type: 'bar',
                    data: {
                        labels: ['Chờ xử lý', 'Đang xử lý', 'Hoàn thành', 'Đã hủy'],
                        datasets: [{
                            label: 'Số lượng đơn',
                            data: [
                                {{ $statusCounts['pending'] }},
                                {{ $statusCounts['processing'] }},
                                {{ $statusCounts['completed'] }},
                                {{ $statusCounts['cancelled'] }}
                            ],
                            backgroundColor: [
                                'rgba(255, 206, 86, 0.7)',
                                'rgba(54, 162, 235, 0.7)',
                                'rgba(75, 192, 192, 0.7)',
                                'rgba(255, 99, 132, 0.7)'
                            ],
                            borderColor: [
                                'rgba(255, 206, 86, 1)',
                                'rgba(54, 162, 235, 1)',
                                'rgba(75, 192, 192, 1)',
                                'rgba(255, 99, 132, 1)'
                            ],
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        scales: {
                            y: {
                                beginAtZero: true,
                                stepSize: 1
                            }
                        }
                    }
                });
            }

            // Biểu đồ doanh thu theo tháng
            const ctxRevenue = document.getElementById('revenueChart');
            if (ctxRevenue) {
                new Chart(ctxRevenue, {
                    type: 'line',
                    data: {
                        labels: ['T1', 'T2', 'T3', 'T4', 'T5', 'T6', 'T7', 'T8', 'T9', 'T10', 'T11', 'T12'],
                        datasets: [{
                            label: 'Doanh thu (VNĐ)',
                            data: [{{ implode(', ', $monthlyRevenueFull) }}],
                            backgroundColor: 'rgba(54, 162, 235, 0.2)',
                            borderColor: 'rgba(54, 162, 235, 1)',
                            borderWidth: 2,
                            fill: true,
                            tension: 0.3
                        }]
                    },
                    options: {
                        responsive: true,
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    callback: function (value) {
                                        return value.toLocaleString('vi-VN') + ' đ';
                                    }
                                }
                            }
                        }
                    }
                });
            }
        });
    </script>
@endsection