@extends('layouts.app')

@section('content')
<h1 class="text-3xl font-bold mb-6">Dashboard Admin Nâng Cao</h1>

<div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6">
    <div class="bg-white p-6 rounded shadow text-center">
        <div class="text-gray-500">Tổng đơn hàng</div>
        <div class="text-2xl font-bold mt-2">{{ $totalOrders }}</div>
    </div>
    <div class="bg-white p-6 rounded shadow text-center">
        <div class="text-gray-500">Tổng doanh thu</div>
        <div class="text-2xl font-bold mt-2">{{ number_format($totalRevenue) }} đ</div>
    </div>
    <div class="bg-white p-6 rounded shadow text-center">
        <div class="text-gray-500">Đơn pending</div>
        <div class="text-2xl font-bold mt-2">{{ $statusCounts['pending'] }}</div>
    </div>
    <div class="bg-white p-6 rounded shadow text-center">
        <div class="text-gray-500">Đơn processing</div>
        <div class="text-2xl font-bold mt-2">{{ $statusCounts['processing'] }}</div>
    </div>
</div>

<div class="grid md:grid-cols-2 gap-6 mt-6">
    <!-- Biểu đồ trạng thái đơn hàng -->
    <div class="bg-white p-6 rounded shadow">
        <h2 class="text-xl font-semibold mb-4">Biểu đồ trạng thái đơn hàng</h2>
        <canvas id="orderChart" width="400" height="150"></canvas>
    </div>

    <!-- Biểu đồ doanh thu theo tháng -->
    <div class="bg-white p-6 rounded shadow">
        <h2 class="text-xl font-semibold mb-4">Doanh thu theo tháng (VNĐ)</h2>
        <canvas id="revenueChart" width="400" height="150"></canvas>
    </div>
</div>

<!-- Top sản phẩm bán chạy -->
<div class="bg-white p-6 rounded shadow mt-6">
    <h2 class="text-xl font-semibold mb-4">Top 5 sản phẩm bán chạy</h2>
    <ul class="list-disc pl-6">
        @foreach($topProducts as $item)
            <li>{{ $item->product->name ?? 'Sản phẩm' }} - {{ $item->total_sold }} bán</li>
        @endforeach
    </ul>
</div>

@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const ctxOrder = document.getElementById('orderChart').getContext('2d');
new Chart(ctxOrder, {
    type: 'bar',
    data: {
        labels: ['Pending','Processing','Completed','Cancelled'],
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
    options: { responsive:true, scales:{y:{beginAtZero:true, stepSize:1}} }
});

const ctxRevenue = document.getElementById('revenueChart').getContext('2d');
new Chart(ctxRevenue, {
    type: 'line',
    data: {
        labels: ['Tháng 1','Tháng 2','Tháng 3','Tháng 4','Tháng 5','Tháng 6','Tháng 7','Tháng 8','Tháng 9','Tháng 10','Tháng 11','Tháng 12'],
        datasets: [{
            label: 'Doanh thu (VNĐ)',
            data: [
                @foreach($monthlyRevenueFull as $rev) {{ $rev }}, @endforeach
            ],
            backgroundColor:'rgba(54,162,235,0.2)',
            borderColor:'rgba(54,162,235,1)',
            borderWidth:2,
            fill:true,
            tension:0.3
        }]
    },
    options: { responsive:true, scales:{y:{beginAtZero:true}} }
});
</script>
@endsection
