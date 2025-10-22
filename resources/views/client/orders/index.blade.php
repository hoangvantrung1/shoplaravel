@extends('layouts.client')

@section('title', 'Đơn hàng của tôi')

@section('content')
<div class="container mx-auto px-4 py-10 max-w-7xl">
    <h1 class="text-3xl font-extrabold text-gray-800 text-center mb-8 mt-10">Đơn hàng của tôi</h1>

    <!-- 🔘 Tabs trạng thái -->
    <div class="flex flex-wrap justify-center gap-3 mb-8">
        @php
            $statusTabs = [
                '' => 'Tất cả',
                'pending' => 'Chờ xác nhận',
                'processing' => 'Đang xử lý',
                'shipping' => 'Đang giao',
                'completed' => 'Hoàn thành',
                'cancelled' => 'Đã hủy',
            ];
        @endphp

        @foreach ($statusTabs as $key => $label)
            <a href="{{ route('client.orders.index', array_merge(request()->except('page'), ['status' => $key ?: null])) }}"
                class="px-4 py-2 rounded-full border text-sm font-medium transition-all
                {{ request('status') === $key || (request('status') === null && $key === '') 
                    ? 'bg-blue-600 text-white border-blue-600 shadow' 
                    : 'bg-white text-gray-600 border-gray-300 hover:bg-blue-50 hover:text-blue-600' }}">
                {{ $label }}
            </a>
        @endforeach
    </div>

    <!-- 📅 Lọc theo ngày/tháng -->
    <form method="GET" action="{{ route('client.orders.index') }}" class="flex flex-wrap gap-4 items-end justify-center mb-6">
        <input type="hidden" name="status" value="{{ request('status') }}">

        <label class="flex flex-col text-sm font-medium text-gray-700">
            Tháng
            <input type="month" name="month" value="{{ request('month') }}"
                class="mt-1 rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
        </label>

        <label class="flex flex-col text-sm font-medium text-gray-700">
            Ngày
            <input type="date" name="date" value="{{ request('date') }}"
                class="mt-1 rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
        </label>

        <button type="submit"
            class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-4 py-2 rounded-lg shadow">
            Lọc
        </button>
    </form>

    <!-- 🛒 Danh sách đơn hàng -->
    @if($orders->count() > 0)
        <div class="space-y-6">
            @foreach($orders as $order)
            <div class="bg-white rounded-lg shadow p-5 border border-gray-200 hover:shadow-md transition-all">

                <!-- Header đơn -->
                <div class="flex justify-between items-center border-b pb-3 mb-3">
                    <div class="flex flex-col">
                        <span class="text-sm text-gray-500">
                            Mã đơn: <span class="font-semibold text-gray-800">{{ $order->order_code }}</span>
                        </span>
                        <span class="text-sm text-gray-500">
                            Ngày đặt: {{ $order->created_at->format('d/m/Y H:i') }}
                        </span>
                    </div>
                    <span class="px-3 py-1 rounded-full text-sm font-semibold 
                        {{ $order->status === 'pending' ? 'bg-yellow-100 text-yellow-700' :
                           ($order->status === 'processing' ? 'bg-blue-100 text-blue-700' :
                           ($order->status === 'shipping' ? 'bg-indigo-100 text-indigo-700' :
                           ($order->status === 'completed' ? 'bg-green-100 text-green-700' :
                           'bg-red-100 text-red-700'))) }}">
                        {{ $order->status_label }}
                    </span>
                </div>

                <!-- Danh sách sản phẩm -->
                <div class="divide-y">
                    @foreach($order->orderItems as $item)
                    <div class="flex items-center justify-between py-3">
                        <div class="flex items-center gap-3">
                            <img src="{{ asset($item->product->image) }}" 
                                alt="{{ $item->product->name }}"
                                class="w-16 h-16 object-cover rounded-md border">
                            <div>
                                <p class="text-gray-800 font-medium">{{ $item->product->name }}</p>
                                <p class="text-sm text-gray-500">Số lượng: x{{ $item->quantity }}</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-blue-600 font-semibold">
                                {{ number_format($item->price * $item->quantity, 0, ',', '.') }}₫
                            </p>
                        </div>
                    </div>
                    @endforeach
                </div>

                <!-- Footer tổng tiền -->
                <div class="flex justify-between items-center mt-4 border-t pt-3">
                    <div>
                        <span class="text-gray-500 text-sm">Tổng tiền:</span>
                        <span class="text-blue-600 font-bold text-lg">
                            {{ number_format($order->total, 0, ',', '.') }}₫
                        </span>
                    </div>
                    <a href="{{ route('client.orders.show', $order->id) }}"
                        class="bg-gradient-to-r from-blue-600 to-blue-500 text-white px-4 py-2 rounded-lg shadow hover:from-blue-700 hover:to-blue-600 transition-all text-sm font-medium">
                        Xem chi tiết
                    </a>
                </div>
            </div>
            @endforeach
        </div>

        <div class="mt-6">
            {{ $orders->links() }}
        </div>
    @else
        <div class="text-center py-12 bg-gray-50 rounded-xl">
            <p class="text-gray-500 text-lg">Không có đơn hàng nào phù hợp.</p>
        </div>
    @endif
</div>
@endsection
