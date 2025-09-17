@extends('layouts.client')

@section('title', 'Đơn hàng của tôi')

@section('content')
<div class="container mx-auto px-4 py-8 max-w-7xl space-y-6">

    <h1 class="text-3xl font-bold mb-6 text-gray-800">Đơn hàng của tôi</h1>

    @if($orders->count() > 0)
        <div class="space-y-4">
            @foreach($orders as $order)
            <div class="bg-white rounded-lg shadow-md p-4 flex justify-between items-center">
                <div>
                    <p><span class="font-semibold">Mã đơn:</span> #{{ $order->id }}</p>
                    <p><span class="font-semibold">Ngày đặt:</span> {{ $order->created_at->format('d/m/Y H:i') }}</p>
                    <p><span class="font-semibold">Tổng tiền:</span> {{ number_format($order->total, 0, ',', '.') }}₫</p>
                    <p><span class="font-semibold">Trạng thái:</span> 
                        <span class="{{ $order->status == 'pending' ? 'text-yellow-500' : ($order->status == 'completed' ? 'text-green-500' : 'text-red-500') }}">
                            {{ ucfirst($order->status) }}
                        </span>
                    </p>
                </div>
                <a href="{{ route('client.orders.show', $order->id) }}"
                    class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition-colors">
                    Xem chi tiết
                </a>
            </div>
            @endforeach
        </div>
    @else
        <p class="text-gray-600">Bạn chưa có đơn hàng nào.</p>
    @endif

</div>
@endsection
