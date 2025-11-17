@extends('layouts.admin')

@section('title', 'Dashboard Admin')

<script src="https://cdn.tailwindcss.com"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<script>
    tailwind.config = {
        theme: {
            extend: {
                colors: {
                    primary: '#4361ee',
                    secondary: '#3f37c9',
                    success: '#4cc9f0',
                    info: '#4895ef',
                    warning: '#f72585',
                    danger: '#e63946',
                    dark: '#2b2d42',
                    light: '#f8f9fa',
                }
            }
        }
    }
</script>
<style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');

    body {
        font-family: 'Inter', sans-serif;
        background-color: #f5f7fb;
    }

    .card {
        transition: all 0.3s ease;
        border-radius: 12px;
        overflow: hidden;
    }

    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 20px -10px rgba(0, 0, 0, 0.15);
    }

    .stats-card {
        position: relative;
    }

    .stats-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #4361ee, #4cc9f0);
    }

    .progress-bar {
        transition: width 1s ease-in-out;
    }

    .chart-container {
        position: relative;
        height: 300px;
    }
</style>

@section('content')
    <div class="">
        <main class="max-w-9xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <!-- Thống kê tổng quan -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <div class="card stats-card bg-white p-6 rounded-lg shadow">
                    <div class="flex items-center">
                        <div class="p-3 rounded-lg bg-blue-100 text-primary">
                            <i class="fas fa-shopping-cart text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Tổng đơn hàng</p>
                            <h3 class="text-2xl font-bold text-gray-900">{{ $totalOrders }}</h3>
                        </div>
                    </div>
                    <div class="mt-4">
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-green-600 font-medium"><i class="fas fa-arrow-up"></i> 12%</span>
                            <span class="text-gray-500">So với tháng trước</span>
                        </div>
                    </div>
                </div>

                <div class="card stats-card bg-white p-6 rounded-lg shadow">
                    <div class="flex items-center">
                        <div class="p-3 rounded-lg bg-green-100 text-green-600">
                            <i class="fas fa-dollar-sign text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Tổng doanh thu</p>
                            <h3 class="text-2xl font-bold text-gray-900">{{ number_format($totalRevenue) }} đ</h3>
                        </div>
                    </div>
                    <div class="mt-4">
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-green-600 font-medium"><i class="fas fa-arrow-up"></i> 23%</span>
                            <span class="text-gray-500">So với tháng trước</span>
                        </div>
                    </div>
                </div>

                <div class="card stats-card bg-white p-6 rounded-lg shadow">
                    <div class="flex items-center">
                        <div class="p-3 rounded-lg bg-yellow-100 text-yellow-600">
                            <i class="fas fa-clock text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Đơn chờ xử lý</p>
                            <h3 class="text-2xl font-bold text-gray-900">{{ $statusCounts['pending'] }}</h3>
                        </div>
                    </div>
                    <div class="mt-4">
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-red-600 font-medium"><i class="fas fa-arrow-down"></i> 5%</span>
                            <span class="text-gray-500">So với tháng trước</span>
                        </div>
                    </div>
                </div>

                <div class="card stats-card bg-white p-6 rounded-lg shadow">
                    <div class="flex items-center">
                        <div class="p-3 rounded-lg bg-purple-100 text-purple-600">
                            <i class="fas fa-cog text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Đang xử lý</p>
                            <h3 class="text-2xl font-bold text-gray-900">{{ $statusCounts['processing'] }}</h3>
                        </div>
                    </div>
                    <div class="mt-4">
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-green-600 font-medium"><i class="fas fa-arrow-up"></i> 7%</span>
                            <span class="text-gray-500">So với tháng trước</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Biểu đồ và thống kê -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                <div class="card bg-white p-6 rounded-lg shadow">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
                        <h2 class="text-xl font-semibold text-gray-900">Doanh thu & số đơn</h2>
                        <div class="flex flex-col sm:flex-row gap-3 sm:items-center">
                            <div class="relative">
                                <select id="chartRange"
                                    class="block appearance-none w-full bg-gray-100 border border-gray-300 text-gray-700 py-2 px-4 pr-10 rounded-lg leading-tight focus:outline-none focus:bg-white focus:border-primary text-sm">
                                    <option value="daily">7 ngày gần nhất</option>
                                    <option value="weekly">8 tuần gần nhất</option>
                                    <option value="monthly" selected>12 tháng gần nhất</option>
                                </select>
                                <div
                                    class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                                    <i class="fas fa-chevron-down text-xs"></i>
                                </div>
                            </div>
                            <button id="exportCsv"
                                class="inline-flex items-center justify-center px-4 py-2 text-sm font-semibold text-white bg-primary rounded-lg shadow hover:bg-secondary transition">
                                <i class="fas fa-file-export mr-2"></i>Xuất CSV
                            </button>
                        </div>
                    </div>
                    <div class="chart-container">
                        <canvas id="revenueChart"></canvas>
                    </div>
                </div>
                <div class="card bg-white p-6 rounded-lg shadow">
                    <h2 class="text-xl font-semibold text-gray-900 mb-6">Thống kê trạng thái đơn hàng</h2>
                    <div class="chart-container">
                        <canvas id="orderStatusChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Bảng dữ liệu -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                <!-- Doanh thu theo tháng -->
                <div class="card bg-white p-6 rounded-lg shadow">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Doanh thu theo tháng</h2>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead>
                                <tr>
                                    <th
                                        class="px-4 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Tháng</th>
                                    <th
                                        class="px-4 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Doanh thu (VNĐ)</th>
                                    <th
                                        class="px-4 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Tỷ lệ</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @php
                                    $monthlyLabels = $chartData['labels'];
                                    $monthlyRevenue = $chartData['revenue'];
                                    $maxRevenue = max($monthlyRevenue) > 0 ? max($monthlyRevenue) : 1;
                                @endphp

                                @foreach($monthlyRevenue as $i => $revenue)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-4 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                            {{ $monthlyLabels[$i] ?? 'N/A' }}</td>
                                        <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ number_format($revenue) }} đ</td>
                                        <td class="px-4 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="w-full bg-gray-200 rounded-full h-2 mr-2">
                                                    @php
                                                        $width = ($revenue / $maxRevenue) * 100;
                                                    @endphp
                                                    <div class="bg-primary h-2 rounded-full progress-bar"
                                                        style="width: {{ $width }}%"></div>
                                                </div>
                                                <span
                                                    class="text-xs text-gray-500 whitespace-nowrap">{{ number_format($width, 1) }}%</span>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Top sản phẩm bán chạy -->
                <div class="card bg-white p-6 rounded-lg shadow">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Top 5 sản phẩm bán chạy</h2>
                    @if($topProducts->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead>
                                    <tr>
                                        <th
                                            class="px-4 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Sản phẩm</th>
                                        <th
                                            class="px-4 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Số lượng bán</th>
                                        <th
                                            class="px-4 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Tỷ lệ</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @php
                                        $maxSold = $topProducts->max('total_sold') > 0 ? $topProducts->max('total_sold') : 1;
                                    @endphp

                                    @foreach($topProducts as $item)
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-4 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    <div
                                                        class="flex-shrink-0 h-10 w-10 bg-gray-200 rounded-md flex items-center justify-center">
                                                        <i class="fas fa-box text-gray-500"></i>
                                                    </div>
                                                    <div class="ml-4">
                                                        <div class="text-sm font-medium text-gray-900">
                                                            {{ $item->product->name ?? 'Sản phẩm đã xóa' }}</div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-4 py-4 whitespace-nowrap">
                                                <span
                                                    class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                    {{ $item->total_sold }} sản phẩm
                                                </span>
                                            </td>
                                            <td class="px-4 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    <div class="w-full bg-gray-200 rounded-full h-2 mr-2">
                                                        @php
                                                            $width = ($item->total_sold / $maxSold) * 100;
                                                        @endphp
                                                        <div class="bg-success h-2 rounded-full progress-bar"
                                                            style="width: {{ $width }}%"></div>
                                                    </div>
                                                    <span
                                                        class="text-xs text-gray-500 whitespace-nowrap">{{ number_format($width, 1) }}%</span>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-8">
                            <i class="fas fa-box-open text-4xl text-gray-300 mb-3"></i>
                            <p class="text-gray-500">Chưa có dữ liệu sản phẩm bán chạy.</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Chi tiết trạng thái đơn hàng -->
            <div class="card bg-white p-6 rounded-lg shadow mb-8">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Chi tiết trạng thái đơn hàng</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div class="bg-blue-50 p-4 rounded-lg border border-blue-100">
                        <div class="flex items-center">
                            <div class="p-2 rounded-lg bg-blue-100 text-primary mr-3">
                                <i class="fas fa-clock"></i>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-blue-800">{{ $statusCounts['pending'] }}</h3>
                                <p class="text-sm text-blue-600">Chờ xử lý</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-purple-50 p-4 rounded-lg border border-purple-100">
                        <div class="flex items-center">
                            <div class="p-2 rounded-lg bg-purple-100 text-purple-600 mr-3">
                                <i class="fas fa-cog"></i>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-purple-800">{{ $statusCounts['processing'] }}</h3>
                                <p class="text-sm text-purple-600">Đang xử lý</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-green-50 p-4 rounded-lg border border-green-100">
                        <div class="flex items-center">
                            <div class="p-2 rounded-lg bg-green-100 text-green-600 mr-3">
                                <i class="fas fa-check-circle"></i>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-green-800">{{ $statusCounts['completed'] }}</h3>
                                <p class="text-sm text-green-600">Hoàn thành</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-red-50 p-4 rounded-lg border border-red-100">
                        <div class="flex items-center">
                            <div class="p-2 rounded-lg bg-red-100 text-red-600 mr-3">
                                <i class="fas fa-times-circle"></i>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-red-800">{{ $statusCounts['cancelled'] }}</h3>
                                <p class="text-sm text-red-600">Đã hủy</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const salesDataUrl = "{{ route('admin.reports.sales.data') }}";
            const exportUrl = "{{ route('admin.reports.sales.export') }}";
            const initialChartData = @json($chartData);
            const rangeSelect = document.getElementById('chartRange');
            const exportButton = document.getElementById('exportCsv');
            let revenueChart;

            function initRevenueChart(dataset) {
                const ctxRevenue = document.getElementById('revenueChart');
                if (!ctxRevenue) return;

                revenueChart = new Chart(ctxRevenue, {
                    data: {
                        labels: dataset.labels,
                        datasets: [
                            {
                                type: 'line',
                                label: 'Doanh thu (VNĐ)',
                                data: dataset.revenue,
                                backgroundColor: 'rgba(67, 97, 238, 0.15)',
                                borderColor: '#4361ee',
                                borderWidth: 3,
                                fill: true,
                                tension: 0.4,
                                yAxisID: 'y',
                                pointBackgroundColor: '#4361ee',
                                pointRadius: 4,
                                pointHoverRadius: 6
                            },
                            {
                                type: 'bar',
                                label: 'Số đơn',
                                data: dataset.orders,
                                backgroundColor: '#4cc9f0',
                                borderColor: '#4cc9f0',
                                borderWidth: 1,
                                borderRadius: 6,
                                yAxisID: 'y1',
                                barThickness: 24
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: true
                            },
                            tooltip: {
                                callbacks: {
                                    label: function (ctx) {
                                        if (ctx.dataset.label.includes('Doanh thu')) {
                                            return ctx.dataset.label + ': ' + ctx.parsed.y.toLocaleString('vi-VN') + ' đ';
                                        }
                                        return ctx.dataset.label + ': ' + ctx.parsed.y;
                                    }
                                }
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                position: 'left',
                                grid: {
                                    drawBorder: false
                                },
                                ticks: {
                                    callback: function (value) {
                                        return value.toLocaleString('vi-VN') + ' đ';
                                    }
                                }
                            },
                            y1: {
                                beginAtZero: true,
                                position: 'right',
                                grid: {
                                    drawOnChartArea: false
                                }
                            },
                            x: {
                                grid: {
                                    display: false
                                }
                            }
                        }
                    }
                });
            }

            async function loadChartData(range) {
                try {
                    const res = await fetch(`${salesDataUrl}?range=${range}`);
                    if (!res.ok) throw new Error('Không thể tải dữ liệu');
                    const data = await res.json();
                    updateChart(data);
                } catch (error) {
                    console.error(error);
                }
            }

            function updateChart(dataset) {
                if (!revenueChart) return;
                revenueChart.data.labels = dataset.labels;
                revenueChart.data.datasets[0].data = dataset.revenue;
                revenueChart.data.datasets[1].data = dataset.orders;
                revenueChart.update();
            }

            initRevenueChart(initialChartData);

            if (rangeSelect) {
                rangeSelect.addEventListener('change', function () {
                    loadChartData(this.value);
                });
            }

            if (exportButton) {
                exportButton.addEventListener('click', function () {
                    const range = rangeSelect ? rangeSelect.value : 'monthly';
                    window.location.href = `${exportUrl}?range=${range}`;
                });
            }

            // Biểu đồ trạng thái đơn hàng
            const ctxOrderStatus = document.getElementById('orderStatusChart');
            if (ctxOrderStatus) {
                new Chart(ctxOrderStatus, {
                    type: 'doughnut',
                    data: {
                        labels: ['Chờ xử lý', 'Đang xử lý', 'Hoàn thành', 'Đã hủy'],
                        datasets: [{
                            data: [
                                    {{ $statusCounts['pending'] }},
                                    {{ $statusCounts['processing'] }},
                                    {{ $statusCounts['completed'] }},
                                {{ $statusCounts['cancelled'] }}
                            ],
                            backgroundColor: [
                                '#33CC00',
                                '#4361ee',
                                '#48cae4',
                                '#e63946'
                            ],
                            borderWidth: 0,
                            hoverOffset: 10
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        cutout: '70%',
                        plugins: {
                            legend: {
                                position: 'bottom',
                                labels: {
                                    boxWidth: 12,
                                    padding: 20
                                }
                            }
                        }
                    }
                });
            }
            setTimeout(function () {
                document.querySelectorAll('.progress-bar').forEach(bar => {
                    bar.style.transition = 'width 1.5s ease-in-out';
                });
            }, 100);
        });
    </script>
@endsection