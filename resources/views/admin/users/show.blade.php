@extends('layouts.admin')

@section('title', 'Chi tiết người dùng')

@section('content')
    <div class="bg-white rounded-lg shadow-md overflow-hidden max-w-6xl mx-auto">
        <div class="bg-gradient-to-r from-green-500 to-teal-600 px-6 py-4">
            <div class="flex justify-between items-center">
                <h1 class="text-2xl font-bold text-white">Chi tiết người dùng</h1>
                <a href="{{ route('admin.users.index') }}"
                    class="bg-white text-green-600 px-4 py-2 rounded-lg font-medium hover:bg-gray-100 transition duration-200 flex items-center">
                    <i class="fas fa-arrow-left mr-2"></i> Quay lại
                </a>
            </div>
        </div>

        <div class="px-6 py-8">
            <!-- Thông tin cơ bản -->
            <div class="flex items-center mb-8">
                <div class="h-20 w-20 rounded-full bg-green-100 flex items-center justify-center mr-6">
                    @php
                        $initials = '';
                        $nameParts = explode(' ', $user->name);
                        foreach ($nameParts as $part) {
                            $initials .= strtoupper(substr($part, 0, 1));
                        }
                        $initials = substr($initials, 0, 2);
                    @endphp
                    <span class="text-green-600 font-bold text-2xl">{{ $initials }}</span>
                </div>
                <div>
                    <h2 class="text-3xl font-bold text-gray-900">{{ $user->name }}</h2>
                    <p class="text-gray-600 text-lg">
                        @if($user->is_admin)
                            Quản trị viên
                        @else
                            Người dùng
                        @endif
                    </p>
                </div>
            </div>

            <!-- Thông tin chi tiết -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div class="bg-gray-50 p-4 rounded-lg">
                    <label class="block text-sm font-medium text-gray-700 mb-2">ID người dùng</label>
                    <p class="text-gray-900 font-semibold">#{{ $user->id }}</p>
                </div>
                <div class="bg-gray-50 p-4 rounded-lg">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                    <p class="text-gray-900 font-semibold">{{ $user->email }}</p>
                </div>
                <div class="bg-gray-50 p-4 rounded-lg">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Trạng thái</label>
                    @if($user->is_active)
                        <span
                            class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                            Đang hoạt động
                        </span>
                    @else
                        <span
                            class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                            Đã khóa
                        </span>
                    @endif
                </div>
                <div class="bg-gray-50 p-4 rounded-lg">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Ngày tạo</label>
                    <p class="text-gray-900 font-semibold">{{ $user->created_at->format('d/m/Y H:i') }}</p>
                </div>
            </div>

            <!-- Địa chỉ người dùng -->
            <!-- Danh sách đơn hàng -->
            <div class="mb-8">
                <h3 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-shopping-bag mr-2 text-green-600"></i> Đơn hàng ({{ $user->orders->count() }})
                </h3>

                @if($user->orders && $user->orders->count() > 0)
                    <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 rounded-lg">
                        <table class="min-w-full divide-y divide-gray-300">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Mã đơn</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Ngày đặt</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Tổng tiền</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Trạng thái</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Thao tác</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($user->orders as $order)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="font-medium text-gray-900">#{{ $order->id }}</span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="text-gray-700">{{ $order->created_at->format('d/m/Y H:i') }}</span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <!-- Kiểm tra và hiển thị tổng tiền theo nhiều trường hợp -->
                                            @php
                                                $totalAmount = null;

                                                // Thử các trường có thể chứa tổng tiền
                                                if (isset($order->total_amount) && $order->total_amount !== null) {
                                                    $totalAmount = $order->total_amount;
                                                } elseif (isset($order->total) && $order->total !== null) {
                                                    $totalAmount = $order->total;
                                                } elseif (isset($order->amount) && $order->amount !== null) {
                                                    $totalAmount = $order->amount;
                                                } elseif (isset($order->grand_total) && $order->grand_total !== null) {
                                                    $totalAmount = $order->grand_total;
                                                }
                                            @endphp

                                            @if($totalAmount !== null)
                                                <span
                                                    class="font-medium text-green-600">{{ number_format($totalAmount, 0, ',', '.') }}₫</span>
                                            @else
                                                <span class="text-gray-400 text-sm">Chưa có tổng tiền</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @php
                                                $statusColors = [
                                                    'pending' => 'bg-yellow-100 text-yellow-800',
                                                    'confirmed' => 'bg-blue-100 text-blue-800',
                                                    'shipping' => 'bg-purple-100 text-purple-800',
                                                    'completed' => 'bg-green-100 text-green-800',
                                                    'cancelled' => 'bg-red-100 text-red-800'
                                                ];
                                                $statusText = [
                                                    'pending' => 'Chờ xác nhận',
                                                    'confirmed' => 'Đã xác nhận',
                                                    'shipping' => 'Đang giao',
                                                    'completed' => 'Hoàn thành',
                                                    'cancelled' => 'Đã hủy'
                                                ];
                                            @endphp
                                            <span
                                                class="px-2 py-1 text-xs font-medium rounded-full {{ $statusColors[$order->status] ?? 'bg-gray-100 text-gray-800' }}">
                                                {{ $statusText[$order->status] ?? $order->status }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <a href="{{ route('admin.orders.show', $order->id) }}"
                                                class="text-green-600 hover:text-green-900 mr-3">
                                                <i class="fas fa-eye"></i> Xem
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="bg-gray-50 border border-gray-200 rounded-lg p-6 text-center">
                        <i class="fas fa-shopping-cart text-gray-400 text-3xl mb-3"></i>
                        <p class="text-gray-600">Người dùng chưa có đơn hàng nào</p>
                    </div>
                @endif
            </div>
            
            <div class="bg-gray-50 p-4 rounded-lg ">
                <label class="block text-xl font-medium text-gray-700 mb-2">Tổng tiền mua ( khi đã hoàn thành)</label>
                <p class="text-red-900 font-medium">
                    {{ number_format($totalSpent, 0, ',', '.') }}₫
                </p>
            </div>
            <!-- Nút hành động -->
            <div class="flex justify-end space-x-3 mt-8">
                <a href="{{ route('admin.users.edit', $user->id) }}"
                    class="bg-yellow-500 text-white px-6 py-2 rounded-lg font-medium hover:bg-yellow-600 transition duration-200 flex items-center">
                    <i class="fas fa-edit mr-2"></i> Chỉnh sửa
                </a>
                <a href="{{ route('admin.users.index') }}"
                    class="bg-gray-500 text-white px-6 py-2 rounded-lg font-medium hover:bg-gray-600 transition duration-200">
                    Đóng
                </a>
            </div>
        </div>
    </div>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
@endsection