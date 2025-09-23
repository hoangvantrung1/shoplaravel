@extends('layouts.client')

@section('title', 'Đơn hàng của tôi')

@section('content')
<div class="container mx-auto px-4 py-10 max-w-7xl">
    <h1 class="text-3xl font-extrabold text-gray-800 text-center mb-8 mt-10">Đơn hàng của tôi</h1>

    @if($orders->count() > 0)
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white rounded-lg shadow divide-y divide-gray-200">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mã đơn</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ngày đặt</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tổng tiền</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Trạng thái</th>
                        <th class="px-6 py-3"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($orders as $order)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-semibold">{{ $order->order_code }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $order->created_at->format('d/m/Y H:i') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-blue-600">
                            {{ number_format($order->total, 0, ',', '.') }}₫
                        </td>
                        <td class="p-3">{{ $order->status_label }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <a href="{{ route('client.orders.show', $order->id) }}"
                                class="inline-block bg-gradient-to-r from-blue-600 to-blue-500 text-white px-3 py-1 rounded-lg shadow hover:from-blue-700 hover:to-blue-600 transition-all text-sm font-medium">
                                Xem chi tiết
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="text-center py-12 bg-gray-50 rounded-xl">
            <p class="text-gray-500 text-lg">Bạn chưa có đơn hàng nào.</p>
        </div>
    @endif

</div>
@endsection