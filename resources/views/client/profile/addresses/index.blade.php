@extends('layouts.client')

@section('content')
<div class="min-h-screen bg-gray-50 pt-20 pb-12">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        
        {{-- Breadcrumb --}}
        <nav class="flex px-6 py-4 text-gray-700 border border-gray-200 rounded-2xl bg-white shadow-sm mb-8" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="{{ route('home') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-purple-600 transition-colors">
                        <i class="fa-solid fa-house mr-2"></i>
                        Trang chủ
                    </a>
                </li>
                <li>
                    <div class="flex items-center">
                        <i class="fa-solid fa-chevron-right text-gray-400 mx-2"></i>
                        <a href="{{ route('profile.index') }}" class="text-sm font-medium text-gray-700 hover:text-purple-600 transition-colors">Thông tin cá nhân</a>
                    </div>
                </li>
                <li aria-current="page">
                    <div class="flex items-center">
                        <i class="fa-solid fa-chevron-right text-gray-400 mx-2"></i>
                        <span class="text-sm font-medium text-purple-600">Địa chỉ giao hàng</span>
                    </div>
                </li>
            </ol>
        </nav>

        <div class="bg-gradient-to-r from-purple-600/10 via-purple-500/5 to-blue-500/10 rounded-3xl border border-purple-100 shadow-2xl shadow-purple-100/80 p-8 mb-10">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
            <div>
                <p class="text-xs uppercase tracking-[0.4em] text-purple-500 mb-2">Address book</p>
                <h1 class="text-3xl lg:text-4xl font-semibold text-gray-900 leading-tight">Địa chỉ giao hàng</h1>
                <p class="text-gray-600 mt-3 text-sm sm:text-base max-w-2xl">
                    Lưu trữ nhiều địa chỉ để thanh toán nhanh hơn. Hệ thống hỗ trợ đa thiết bị,
                    tự động chọn địa chỉ mặc định khi bạn đặt hàng.
                </p>
            </div>
            <div class="flex items-center gap-4">
                <div class="bg-white/80 rounded-2xl px-5 py-3 text-center border border-purple-100">
                    <p class="text-2xl font-semibold text-purple-600">{{ $addresses->count() }}</p>
                    <p class="text-xs text-gray-500">Địa chỉ đã lưu</p>
                </div>
                <a href="{{ route('addresses.create') }}"
                   class="inline-flex items-center gap-2 px-5 py-3 rounded-2xl bg-gradient-to-r from-purple-600 to-blue-500 text-white font-semibold shadow-lg shadow-purple-300/50 hover:-translate-y-0.5 transition-all">
                    <i class="fas fa-plus text-sm"></i>
                    Thêm địa chỉ
                </a>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-8 rounded-2xl border border-emerald-200 bg-emerald-50 text-emerald-700 px-5 py-4 flex items-center gap-3 text-sm shadow-sm">
            <i class="fas fa-check-circle text-emerald-500 text-lg"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if($addresses->isEmpty())
        <div class="bg-white rounded-3xl border border-dashed border-gray-200 py-16 px-6 text-center shadow-inner" role="status" aria-live="polite">
            <div class="mx-auto w-20 h-20 rounded-full bg-purple-50 text-purple-500 flex items-center justify-center mb-4">
                <i class="fas fa-map-marked-alt text-2xl" aria-hidden="true"></i>
            </div>
            <h3 class="text-xl font-semibold text-gray-900 mb-2">Chưa có địa chỉ nào</h3>
            <p class="text-gray-500 mb-6">Thêm địa chỉ mới để thanh toán thuận tiện hơn ở các đơn hàng tiếp theo.</p>
            <a href="{{ route('addresses.create') }}"
               class="inline-flex items-center gap-2 px-5 py-3 rounded-2xl bg-purple-600 text-white font-semibold shadow-lg hover:bg-purple-700 transition"
               aria-label="Thêm địa chỉ giao hàng đầu tiên">
                <i class="fas fa-plus" aria-hidden="true"></i> Thêm địa chỉ đầu tiên
            </a>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @foreach($addresses as $address)
                <div class="group relative rounded-3xl border {{ $address->is_default ? 'border-purple-300 bg-gradient-to-br from-purple-50 to-white shadow-xl' : 'border-gray-100 bg-white shadow-lg' }} p-6 transition hover:-translate-y-1 hover:shadow-2xl">
                    @if($address->is_default)
                        <span class="absolute -top-3 right-8 text-xs font-semibold bg-indigo-600 text-white px-3 py-1 rounded-full shadow-md flex items-center gap-1">
                            <i class="fas fa-star text-[10px]"></i> Mặc định
                        </span>
                    @endif

                    <div class="flex items-start justify-between gap-3 mb-4">
                        <div>
                            <p class="text-lg font-semibold text-gray-900">{{ $address->name }}</p>
                            <p class="text-sm text-gray-500">{{ $address->phone }} • {{ $address->email ?? 'No email' }}</p>
                        </div>
                        <div class="flex gap-2">
                            <a href="{{ route('addresses.edit', $address) }}"
                               class="inline-flex items-center justify-center w-10 h-10 rounded-full border border-gray-200 text-gray-500 hover:text-purple-600 hover:border-purple-200 transition bg-white">
                                <i class="fas fa-edit text-sm"></i>
                            </a>
                            <form action="{{ route('addresses.destroy', $address) }}" method="POST"
                                  onsubmit="return confirm('Bạn có chắc chắn muốn xóa địa chỉ này?')">
                                @csrf
                                @method('DELETE')
                                <button
                                    class="inline-flex items-center justify-center w-10 h-10 rounded-full border border-gray-200 text-gray-500 hover:text-red-600 hover:border-red-200 transition bg-white">
                                    <i class="fas fa-trash-alt text-sm"></i>
                                </button>
                            </form>
                        </div>
                    </div>

                    <div class="space-y-3 text-sm">
                        <div class="flex items-start gap-3">
                            <div class="w-10 h-10 rounded-2xl bg-purple-100 text-purple-600 flex items-center justify-center">
                                <i class="fas fa-map-pin text-sm"></i>
                            </div>
                            <div class="flex-1 text-gray-700 leading-relaxed">
                                {{ $address->address_line }}
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                            <div class="rounded-2xl bg-gray-50 border border-gray-100 p-3">
                                <p class="text-[11px] uppercase tracking-wide text-gray-400">Phường/Xã</p>
                                <p class="text-sm font-medium text-gray-900 mt-1">{{ $address->ward ?: '---' }}</p>
                            </div>
                            <div class="rounded-2xl bg-gray-50 border border-gray-100 p-3">
                                <p class="text-[11px] uppercase tracking-wide text-gray-400">Quận/Huyện</p>
                                <p class="text-sm font-medium text-gray-900 mt-1">{{ $address->district ?: '---' }}</p>
                            </div>
                            <div class="rounded-2xl bg-gray-50 border border-gray-100 p-3">
                                <p class="text-[11px] uppercase tracking-wide text-gray-400">Tỉnh/Thành</p>
                                <p class="text-sm font-medium text-gray-900 mt-1">{{ $address->province ?: '---' }}</p>
                            </div>
                        </div>

                        <div class="rounded-2xl border border-dashed border-gray-200 bg-gray-50/80 px-4 py-3 text-xs text-gray-500">
                            <p>Ghi chú: Địa chỉ này sẽ được hiển thị trong bước thanh toán. Bạn có thể chỉnh sửa hoặc đặt mặc định bất kỳ lúc nào.</p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
    </div>
</div>
@endsection
