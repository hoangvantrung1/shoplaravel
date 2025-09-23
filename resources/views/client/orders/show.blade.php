@extends('layouts.client')

@section('title', 'Chi tiết đơn hàng #' . $order->id)

@section('content')
<div class="container mx-auto px-4 py-8 max-w-5xl space-y-8">

    <h1 class="text-3xl font-extrabold text-gray-900 mb-10 border-b border-gray-200 pb-4 mt-10">
        CHI TIẾT ĐƠN HÀNG 
    </h1>

    <div class="bg-white rounded-xl shadow-lg p-8 space-y-6">
        <h1 class="text-gray-500 font-semibold">Mã đơn hàng : <span class="text-indigo-600"> {{ $order->order_code }}</span></h1>
        {{-- Thông tin đơn hàng --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            
            <div>
                <p class="text-gray-500 font-semibold">Ngày tạo</p>
                <p class="mt-1 text-gray-900">{{ $order->created_at->format('d/m/Y H:i') }}</p>
            </div>
            <div class="mx-12">
                <p class="text-gray-500 font-semibold">Trạng thái</p>
                <span class="inline-block mt-1 px-3 py-1 rounded-full text-sm font-semibold 
                    @if($order->status_label == 'pending') bg-yellow-100 text-yellow-800
                    @elseif($order->status_label == 'completed') bg-green-100 text-green-800
                    @elseif($order->status_label == 'cancelled') bg-red-100 text-red-800
                    @else bg-gray-100 text-gray-800 @endif
                ">
                    {{ ucfirst($order->status_label) }}
                </span>
            </div>
        </div>

        {{-- Danh sách sản phẩm --}}
        <div>
            <h2 class="text-xl font-semibold mb-4 border-b border-gray-200 pb-2">Sản phẩm trong đơn hàng</h2>

            <div class="overflow-x-auto">
                <table class="min-w-full text-left border border-gray-300 rounded-lg">
                    <thead class="bg-indigo-50">
                        <tr>
                            <th class="px-6 py-3 border-b border-gray-300 font-medium text-indigo-700">Sản phẩm</th>
                            <th class="px-6 py-3 border-b border-gray-300 font-medium text-indigo-700">Giá</th>
                            <th class="px-6 py-3 border-b border-gray-300 font-medium text-indigo-700">Số lượng</th>
                            <th class="px-6 py-3 border-b border-gray-300 font-medium text-indigo-700">Tổng</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($order->orderItems as $item)
                        <tr class="hover:bg-indigo-50 transition-colors">
                            <td class="px-6 py-4 border-b border-gray-300 align-middle font-medium text-gray-800">
                                {{ $item->product->name }}
                            </td>
                            <td class="px-6 py-4 border-b border-gray-300 text-gray-600">
                                {{ number_format($item->price,0,',','.') }} đ
                            </td>
                            <td class="px-6 py-4 border-b border-gray-300 text-center text-gray-700 font-semibold">
                                {{ $item->quantity }}
                            </td>
                            <td class="px-6 py-4 border-b border-gray-300 text-right font-semibold text-indigo-600">
                                {{ number_format($item->price * $item->quantity,0,',','.') }} đ
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>
@endsection