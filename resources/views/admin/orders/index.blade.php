@extends('layouts.admin')

@section('content')
<h1 class="text-3xl font-bold mb-6">Danh Sách Đơn Hàng</h1>

<table class="w-full bg-white rounded shadow overflow-x-auto">
    <thead class="bg-green-100">
        <tr>
            <th class="p-3 text-left">ID</th>
            <th class="p-3 text-left">Khách hàng</th>
            <th class="p-3 text-left">Tổng tiền</th>
            <th class="p-3 text-left">Trạng thái</th>
            <th class="p-3 text-left">Ngày tạo</th>
            <th class="p-3 text-left">Hành động</th>
        </tr>
    </thead>
    <tbody>
        @foreach($orders as $order)
        <tr class="border-b hover:bg-gray-50">
            <td class="p-3">{{ $order->id }}</td>
            <td class="p-3">{{ $order->customer_name }}</td>
            <td class="p-3">{{ number_format($order->total) }} đ</td>
            <td class="p-3">{{ $order->status_label }}</td>
            <td class="p-3">{{ $order->created_at->format('d/m/Y H:i') }}</td>
            <td class="p-3">
                <a href="{{ route('admin.orders.show',$order->id) }}" class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded">Xem chi tiết</a>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

<div class="mt-4">
    {{ $orders->links() }}
</div>
@endsection
