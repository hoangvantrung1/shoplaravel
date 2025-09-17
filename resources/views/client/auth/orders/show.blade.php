@extends('layouts.client')

@section('title', 'Chi tiết đơn hàng #' . $order->id)

@section('content')
<div class="container mx-auto px-4 py-8 max-w-7xl space-y-6">

    <h1 class="text-2xl font-bold text-gray-800 mb-6">Chi tiết đơn hàng #{{ $order->id }}</h1>

    <div class="bg-white shadow-md rounded-lg p-6 space-y-4">
        <p><span class="font-semibold">Ngày tạo:</span> {{ $order->created_at->format('d/m/Y H:i') }}</p>
        <p><span class="font-semibold">Trạng thái:</span> 
            <span class="{{ $order->status == 'pending' ? 'text-yellow-500' : ($order->status == 'completed' ? 'text-green-500' : 'text-gray-500') }}">
                {{ ucfirst($order->status) }}
            </span>
        </p>

        <h2 class="font-semibold mt-4">Sản phẩm trong đơn hàng:</h2>
        <div class="overflow-x-auto">
            <table class="w-full text-left border border-gray-200 rounded-lg">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="px-4 py-2 border-b">Sản phẩm</th>
                        <th class="px-4 py-2 border-b">Giá</th>
                        <th class="px-4 py-2 border-b">Số lượng</th>
                        <th class="px-4 py-2 border-b">Tổng</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->orderItems as $item)
                    <tr>
                        <td class="px-4 py-2 border-b">{{ $item->product->name }}</td>
                        <td class="px-4 py-2 border-b">{{ number_format($item->price,0,',','.') }} đ</td>
                        <td class="px-4 py-2 border-b">{{ $item->quantity }}</td>
                        <td class="px-4 py-2 border-b font-semibold">{{ number_format($item->price * $item->quantity,0,',','.') }} đ</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <p class="text-right font-bold text-lg mt-4">Tổng đơn hàng: {{ number_format($order->total,0,',','.') }} đ</p>
    </div>

</div>
@endsection
