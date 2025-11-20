@extends('layouts.admin')

@section('title', 'Quản lý Deal sản phẩm')

@section('content')
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="bg-gradient-to-r from-red-500 to-orange-500 px-6 py-5 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-white flex items-center gap-3">
                    <i class="fas fa-bolt text-yellow-300"></i>
                    Deal khuyến mãi
                </h1>
                <p class="text-white/80 mt-1 text-sm md:text-base">
                    Theo dõi các sản phẩm đang chạy deal, sắp diễn ra hoặc đã kết thúc để chủ động cập nhật.
                </p>
            </div>
            <div class="flex flex-wrap items-center gap-3">
                <a href="{{ route('admin.products.create') }}"
                    class="inline-flex items-center px-4 py-2 bg-white text-red-500 font-semibold rounded-lg shadow hover:bg-red-50 transition">
                    <i class="fas fa-plus mr-2"></i> Thêm sản phẩm
                </a>
                <a href="{{ route('admin.products.index') }}"
                    class="inline-flex items-center px-4 py-2 bg-white/20 text-white font-semibold rounded-lg hover:bg-white/30 transition">
                    <i class="fas fa-box mr-2"></i> Quản lý sản phẩm
                </a>
            </div>
        </div>

        @php
            $tabs = [
                'active' => 'Đang chạy',
                'upcoming' => 'Sắp diễn ra',
                'expired' => 'Đã kết thúc',
                'all' => 'Tất cả',
            ];
        @endphp

        <div class="px-6 py-4 border-b border-gray-100 flex flex-wrap gap-3">
            @foreach ($tabs as $key => $label)
                @php $isActiveTab = $status === $key; @endphp
                <a href="{{ route('admin.products.deals', ['status' => $key]) }}"
                    class="inline-flex items-center gap-2 px-4 py-2 rounded-full border transition @if($isActiveTab) bg-red-500 text-white border-red-500 shadow @else text-gray-600 border-gray-200 hover:border-red-300 hover:text-red-500 @endif">
                    <span class="font-semibold">{{ $label }}</span>
                    <span class="px-2 py-0.5 rounded-full text-xs font-bold @if($isActiveTab) bg-white/20 text-white @else bg-gray-100 text-gray-600 @endif">
                        {{ $stats[$key] ?? 0 }}
                    </span>
                </a>
            @endforeach
        </div>

        <div class="px-6 py-6">
            @if($products->isEmpty())
                <div class="text-center py-16 border-2 border-dashed border-gray-200 rounded-2xl">
                    <div class="text-5xl mb-4 text-gray-300">
                        <i class="fas fa-tags"></i>
                    </div>
                    <p class="text-gray-600 text-lg font-semibold mb-2">Chưa có sản phẩm nào trong danh sách này</p>
                    <p class="text-gray-500">Hãy tạo deal mới hoặc điều chỉnh thời gian cho các sản phẩm hiện có.</p>
                </div>
            @else
                <div class="overflow-x-auto rounded-xl border border-gray-100 shadow-sm">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Sản phẩm</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Giá</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Deal</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Thời gian</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Trạng thái</th>
                                <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Hành động</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-100">
                            @foreach($products as $product)
                                @php
                                    $discount = round((1 - ($product->sale_price / $product->price)) * 100);
                                    $hasStart = $product->deal_start_date !== null;
                                    $hasEnd = $product->deal_end_date !== null;
                                    $statusLabel = 'Đang chạy';
                                    $statusClass = 'bg-green-100 text-green-700';

                                    if ($hasStart && $product->deal_start_date->isFuture()) {
                                        $statusLabel = 'Sắp diễn ra';
                                        $statusClass = 'bg-blue-100 text-blue-700';
                                    } elseif ($hasEnd && $product->deal_end_date->isPast()) {
                                        $statusLabel = 'Đã kết thúc';
                                        $statusClass = 'bg-gray-100 text-gray-600';
                                    }
                                @endphp
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-4 py-4">
                                        <div class="flex items-center space-x-4">
                                            <div class="w-16 h-16 rounded-xl bg-gray-50 overflow-hidden flex items-center justify-center">
                                                <img src="{{ asset($product->image) }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                                            </div>
                                            <div>
                                                <p class="text-sm font-semibold text-gray-900">{{ $product->name }}</p>
                                                <p class="text-xs text-gray-500 mt-1 flex items-center gap-2">
                                                    <span><i class="fas fa-box mr-1"></i>{{ $product->category->name ?? '—' }}</span>
                                                    <span>•</span>
                                                    <span><i class="fas fa-tag mr-1"></i>{{ $product->brand->name ?? '—' }}</span>
                                                </p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-4 py-4 whitespace-nowrap">
                                        <div class="text-sm font-semibold text-gray-800">{{ number_format($product->price, 0, ',', '.') }}₫</div>
                                    </td>
                                    <td class="px-4 py-4 whitespace-nowrap">
                                        <div class="text-sm font-semibold text-red-600">{{ number_format($product->sale_price, 0, ',', '.') }}₫</div>
                                        <div class="text-xs text-red-500 mt-1">Giảm {{ $discount }}%</div>
                                    </td>
                                    <td class="px-4 py-4">
                                        <div class="text-xs text-gray-600 space-y-1">
                                            <div>
                                                <span class="font-semibold text-gray-800">Bắt đầu:</span>
                                                {{ $hasStart ? $product->deal_start_date->format('d/m/Y H:i') : 'Ngay lập tức' }}
                                            </div>
                                            <div>
                                                <span class="font-semibold text-gray-800">Kết thúc:</span>
                                                {{ $hasEnd ? $product->deal_end_date->format('d/m/Y H:i') : 'Không giới hạn' }}
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-4 py-4">
                                        <span class="inline-flex items-center px-3 py-1 text-xs font-semibold rounded-full {{ $statusClass }}">
                                            {{ $statusLabel }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-4 whitespace-nowrap text-center">
                                        <div class="flex items-center justify-center gap-3 text-sm font-medium">
                                            <a href="{{ route('admin.products.edit', $product->id) }}" class="text-blue-600 hover:text-blue-800">
                                                <i class="fas fa-edit mr-1"></i> Sửa
                                            </a>
                                            <span class="text-gray-300">|</span>
                                            <a href="{{ route('admin.products.logs', $product->id) }}" class="text-gray-600 hover:text-gray-900">
                                                <i class="fas fa-history mr-1"></i> Log
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-6">
                    {{ $products->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection

