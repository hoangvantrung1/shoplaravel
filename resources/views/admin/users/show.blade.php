@extends('layouts.admin')

@section('title', 'Chi tiết người dùng')

@section('content')
    <div class="bg-white rounded-lg shadow-md overflow-hidden w-full mx-auto">
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
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
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
            <div class="mb-8">
                <h3 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-map-marker-alt mr-2 text-green-600"></i> Địa chỉ
                </h3>

                @if($user->addresses && $user->addresses->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($user->addresses as $address)
                            <div class="bg-white border border-gray-200 rounded-lg p-4 hover:shadow-md transition duration-200 
                                {{ $address->is_default ? 'ring-2 ring-green-500' : '' }}">
                                <div class="flex justify-between items-start mb-2">
                                    <h4 class="font-semibold text-gray-800">{{ $address->address_name ?? 'Địa chỉ chính' }}</h4>
                                    @if($address->is_default)
                                        <span class="bg-green-100 text-green-800 text-xs px-2 py-1 rounded-full font-medium">
                                            Mặc định
                                        </span>
                                    @endif
                                </div>
                                <div class="text-sm text-gray-600 space-y-1">
                                    <p><strong>Điện thoại:</strong> {{ $address->phone }}</p>
                                    <p><strong>Địa chỉ:</strong> {{ $address->address_line }}</p>
                                    @if($address->ward)
                                        <p><strong>Phường/Xã:</strong> {{ $address->ward }}</p>
                                    @endif
                                    @if($address->district)
                                        <p><strong>Quận/Huyện:</strong> {{ $address->district }}</p>
                                    @endif
                                    @if($address->province)
                                        <p><strong>Tỉnh/Thành phố:</strong> {{ $address->province }}</p>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="bg-gray-50 border border-gray-200 rounded-lg p-6 text-center">
                        <i class="fas fa-map-marker-alt text-gray-400 text-3xl mb-3"></i>
                        <p class="text-gray-600">Người dùng chưa có địa chỉ nào</p>
                    </div>
                @endif
            </div>

            <!-- Danh sách đơn hàng -->
            <div class="mb-8">
                <h3 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-shopping-bag mr-2 text-green-600"></i> 
                    Đơn hàng ({{ $orders->total() }})
                </h3>

                @if($orders->count() > 0)
                    <div class="overflow-x-auto shadow ring-1 ring-black ring-opacity-5 rounded-lg">
                        <table class="min-w-full divide-y divide-gray-300">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mã đơn</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ngày đặt</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tổng tiền</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Trạng thái</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Thao tác</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($orders as $order)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="font-medium text-gray-900">#{{ $order->id }}</span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="text-gray-700">{{ $order->created_at->format('d/m/Y H:i') }}</span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @php
                                                $totalAmount = $order->total ?? $order->total_amount ?? $order->amount ?? $order->grand_total;
                                            @endphp
                                            @if($totalAmount !== null)
                                                <span class="font-medium text-green-600">
                                                    {{ number_format($totalAmount, 0, ',', '.') }}₫
                                                </span>
                                            @else
                                                <span class="text-gray-400 text-sm">Chưa có tổng tiền</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @php
                                                $statusColors = [
                                                    'unpaid' => 'bg-orange-100 text-orange-800',
                                                    'paid' => 'bg-blue-100 text-blue-800',
                                                    'pending' => 'bg-yellow-100 text-yellow-800',
                                                    'confirmed' => 'bg-blue-100 text-blue-800',
                                                    'processing' => 'bg-indigo-100 text-indigo-800',
                                                    'shipping' => 'bg-purple-100 text-purple-800',
                                                    'delivered' => 'bg-green-100 text-green-800',
                                                    'completed' => 'bg-green-100 text-green-800',
                                                    'cancelled' => 'bg-red-100 text-red-800',
                                                    'failed' => 'bg-red-100 text-red-800'
                                                ];
                                                $statusText = [
                                                    'unpaid' => 'Chưa thanh toán',
                                                    'paid' => 'Đã thanh toán',
                                                    'pending' => 'Chờ xác nhận',
                                                    'confirmed' => 'Đã xác nhận',
                                                    'processing' => 'Đang xử lý',
                                                    'shipping' => 'Đang giao',
                                                    'delivered' => 'Đã giao hàng',
                                                    'completed' => 'Hoàn thành',
                                                    'cancelled' => 'Đã hủy',
                                                    'failed' => 'Thất bại'
                                                ];
                                            @endphp
                                            <span class="px-2 py-1 text-xs font-medium rounded-full {{ $statusColors[$order->status] ?? 'bg-gray-100 text-gray-800' }}">
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

                    <!-- Phân trang -->
                    <div class="mt-6 flex flex-col sm:flex-row items-center justify-between gap-4">
                        <p class="text-sm text-gray-600">
                            Hiển thị <span class="font-semibold text-blue-600">{{ $orders->firstItem() ?: 0 }}-{{ $orders->lastItem() ?: 0 }}</span> 
                            của <span class="font-semibold text-blue-600">{{ $orders->total() }}</span> đơn hàng
                        </p>
                        
                        <div class="flex items-center space-x-2">
                            <!-- Nút Previous -->
                            @if ($orders->onFirstPage())
                                <span class="flex items-center px-3 py-2 bg-gray-100 text-gray-400 rounded-lg text-sm font-medium cursor-not-allowed">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                                    </svg>
                                    Trước
                                </span>
                            @else
                                <a href="{{ $orders->previousPageUrl() }}" 
                                class="flex items-center px-3 py-2 bg-white border border-gray-200 rounded-lg text-sm font-medium text-gray-700 hover:bg-blue-50 hover:border-blue-300 hover:text-blue-700 transition-all duration-200 shadow-sm">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                                    </svg>
                                    Trước
                                </a>
                            @endif

                            <!-- Số trang -->
                            <div class="flex items-center space-x-1">
                                @php
                                    $start = max(1, $orders->currentPage() - 1);
                                    $end = min($orders->lastPage(), $start + 2);
                                @endphp
                                
                                @foreach (range($start, $end) as $page)
                                    @if ($page == $orders->currentPage())
                                        <span class="flex items-center justify-center w-8 h-8 bg-gradient-to-r from-green-500 to-teal-600 text-white rounded-lg text-sm font-semibold shadow-md">
                                            {{ $page }}
                                        </span>
                                    @else
                                        <a href="{{ $orders->url($page) }}" 
                                        class="flex items-center justify-center w-8 h-8 bg-white border border-gray-200 text-gray-700 rounded-lg text-sm font-medium hover:bg-green-50 hover:border-green-300 hover:text-green-700 transition-all duration-200 shadow-sm">
                                            {{ $page }}
                                        </a>
                                    @endif
                                @endforeach
                            </div>

                            <!-- Nút Next -->
                            @if ($orders->hasMorePages())
                                <a href="{{ $orders->nextPageUrl() }}" 
                                class="flex items-center px-3 py-2 bg-white border border-gray-200 rounded-lg text-sm font-medium text-gray-700 hover:bg-blue-50 hover:border-blue-300 hover:text-blue-700 transition-all duration-200 shadow-sm">
                                    Sau
                                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </a>
                            @else
                                <span class="flex items-center px-3 py-2 bg-gray-100 text-gray-400 rounded-lg text-sm font-medium cursor-not-allowed">
                                    Sau
                                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </span>
                            @endif
                        </div>
                    </div>

                @else
                    <div class="bg-gray-50 border border-gray-200 rounded-lg p-6 text-center">
                        <i class="fas fa-shopping-cart text-gray-400 text-3xl mb-3"></i>
                        <p class="text-gray-600">Người dùng chưa có đơn hàng nào</p>
                    </div>
                @endif
            </div>
            
            <!-- Tổng tiền mua -->
            <div class="bg-gray-50 p-4 rounded-lg mb-6">
                <label class="block text-xl font-medium text-gray-700 mb-2">Tổng tiền mua (khi đã hoàn thành)</label>
                <p class="text-red-900 font-medium text-2xl">
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