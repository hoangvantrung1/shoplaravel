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
                'confirmed' => 'Đã xác nhận',
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
                        {{ match($order->status) {
                            'pending' => 'bg-yellow-100 text-yellow-700',
                            'processing' => 'bg-blue-100 text-blue-700',
                            'shipping' => 'bg-indigo-100 text-indigo-700',
                            'confirmed' => 'bg-indigo-100 text-indigo-700', 
                            'completed', 'paid' => 'bg-green-100 text-green-700',
                            'unpaid' => 'bg-orange-100 text-orange-700',
                            'failed', 'cancelled' => 'bg-red-100 text-red-700',
                            default => 'bg-gray-100 text-gray-700'
                        } }}">
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
        <!-- Phân trang hiện đại -->
        <div class="mt-8 flex flex-col sm:flex-row items-center justify-between gap-4">
            <p class="text-sm text-gray-600">
                Đang hiển thị <span class="font-semibold text-blue-600">{{ $orders->firstItem() ?: 0 }}-{{ $orders->lastItem() ?: 0 }}</span> 
                trong tổng số <span class="font-semibold text-blue-600">{{ $orders->total() }}</span> đơn hàng
            </p>
            
            <div class="flex items-center space-x-1">
                <!-- Nút Previous -->
                @if ($orders->onFirstPage())
                    <span class="flex items-center px-4 py-2 bg-gray-100 text-gray-400 rounded-lg text-sm font-medium cursor-not-allowed">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                        </svg>
                        Trước
                    </span>
                @else
                    <a href="{{ $orders->previousPageUrl() }}" 
                    class="flex items-center px-4 py-2 bg-white border border-gray-200 rounded-lg text-sm font-medium text-gray-700 hover:bg-blue-50 hover:border-blue-300 hover:text-blue-700 transition-all duration-200 shadow-sm">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                        </svg>
                        Trước
                    </a>
                @endif

                <!-- Số trang -->
                <div class="flex items-center space-x-1 mx-2">
                    @foreach ($orders->getUrlRange(max(1, $orders->currentPage() - 2), min($orders->lastPage(), $orders->currentPage() + 2)) as $page => $url)
                        @if ($page == $orders->currentPage())
                            <span class="flex items-center justify-center w-10 h-10 bg-gradient-to-r from-blue-600 to-blue-500 text-white rounded-lg text-sm font-semibold shadow-md">
                                {{ $page }}
                            </span>
                        @else
                            <a href="{{ $url }}" 
                            class="flex items-center justify-center w-10 h-10 bg-white border border-gray-200 rounded-lg text-sm font-medium text-gray-700 hover:bg-blue-50 hover:border-blue-300 hover:text-blue-700 transition-all duration-200 shadow-sm">
                                {{ $page }}
                            </a>
                        @endif
                    @endforeach
                </div>

                <!-- Nút Next -->
                @if ($orders->hasMorePages())
                    <a href="{{ $orders->nextPageUrl() }}" 
                    class="flex items-center px-4 py-2 bg-white border border-gray-200 rounded-lg text-sm font-medium text-gray-700 hover:bg-blue-50 hover:border-blue-300 hover:text-blue-700 transition-all duration-200 shadow-sm">
                        Sau
                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>
                @else
                    <span class="flex items-center px-4 py-2 bg-gray-100 text-gray-400 rounded-lg text-sm font-medium cursor-not-allowed">
                        Sau
                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </span>
                @endif
            </div>
        </div>
    @else
        <div class="text-center py-12 bg-gray-50 rounded-xl">
            <p class="text-gray-500 text-lg">Không có đơn hàng nào phù hợp.</p>
        </div>
    @endif
</div>
@endsection
