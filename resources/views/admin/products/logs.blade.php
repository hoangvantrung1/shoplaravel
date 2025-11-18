@extends('layouts.admin')

@section('title', 'Lịch sử thay đổi sản phẩm')

@section('content')
    <div class="bg-white rounded-lg shadow-md overflow-hidden max-w-9xl mx-auto">
        <div class="bg-gradient-to-r from-green-500 to-teal-600 px-6 py-4">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-2xl font-bold text-white">Lịch sử thay đổi</h1>
                    <p class="text-green-100 text-sm mt-1">{{ $product->name }}</p>
                </div>
                <a href="{{ route('admin.products.index') }}"
                    class="bg-white text-green-600 px-4 py-2 rounded-lg font-medium hover:bg-gray-100 transition duration-200 flex items-center">
                    <i class="fas fa-arrow-left mr-2"></i> Quay lại
                </a>
            </div>
        </div>

        <div class="px-6 py-8">
            <!-- Thông tin sản phẩm -->
            <div class="bg-gray-50 rounded-lg p-4 mb-6 border border-gray-200">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <span class="text-sm text-gray-600">Giá hiện tại:</span>
                        <p class="text-lg font-semibold text-gray-800">{{ number_format($product->price, 0, ',', '.') }} đ</p>
                    </div>
                    <div>
                        <span class="text-sm text-gray-600">Tồn kho hiện tại:</span>
                        <p class="text-lg font-semibold text-gray-800">{{ $product->stock }} sản phẩm</p>
                    </div>
                    <div>
                        <span class="text-sm text-gray-600">Mã sản phẩm:</span>
                        <p class="text-lg font-semibold text-gray-800">#{{ $product->id }}</p>
                    </div>
                </div>
            </div>

            @if($logs->count() > 0)
                <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 rounded-lg">
                    <table class="min-w-full divide-y divide-gray-300">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Thời gian</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Loại thay đổi</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Giá trị cũ</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Giá trị mới</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Người thay đổi</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ghi chú</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($logs as $log)
                                <tr class="hover:bg-gray-50 transition duration-150">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="text-sm text-gray-700">{{ $log->created_at->format('d/m/Y H:i:s') }}</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 py-1 text-xs font-medium rounded-full {{ $log->field_changed == 'price' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800' }}">
                                            {{ $log->field_label }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="text-sm text-gray-700">
                                            @if($log->field_changed == 'price')
                                                {{ number_format($log->old_value ?? 0, 0, ',', '.') }} đ
                                            @else
                                                {{ number_format($log->old_value ?? 0, 0, ',', '.') }} sản phẩm
                                            @endif
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="text-sm font-semibold text-gray-800">
                                            @if($log->field_changed == 'price')
                                                {{ number_format($log->new_value, 0, ',', '.') }} đ
                                            @else
                                                {{ number_format($log->new_value, 0, ',', '.') }} sản phẩm
                                            @endif
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="text-sm text-gray-700">
                                            {{ $log->admin ? $log->admin->name : 'Hệ thống' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="text-sm text-gray-600">
                                            {{ $log->notes ?? '—' }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Phân trang -->
                <div class="mt-6">
                    {{ $logs->links() }}
                </div>
            @else
                <div class="text-center py-12 bg-gray-50 rounded-lg border border-gray-200">
                    <i class="fas fa-history text-5xl text-gray-400 mb-4"></i>
                    <p class="text-gray-600 text-lg font-medium">Chưa có lịch sử thay đổi</p>
                    <p class="text-gray-500 text-sm mt-2">Lịch sử thay đổi sẽ được hiển thị ở đây khi bạn cập nhật giá hoặc tồn kho</p>
                </div>
            @endif
        </div>
    </div>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
@endsection

