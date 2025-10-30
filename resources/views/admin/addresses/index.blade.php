@extends('layouts.admin')

@section('title', 'Quản lý Địa chỉ')

@section('content')
<div class="bg-white rounded-2xl shadow-xl overflow-hidden">
    <!-- Header -->
    <div class="bg-gradient-to-r from-green-500 to-teal-600 px-8 py-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-white mb-2">Quản lý Địa chỉ</h1>
                <p class="text-green-100 text-lg">Quản lý địa chỉ giao hàng của khách hàng</p>
            </div>
            <div class="bg-white/20 backdrop-blur-sm rounded-xl p-3">
                <span class="material-icons text-white text-4xl">location_on</span>
            </div>
        </div>
    </div>

    <!-- Stats -->
    <div class="px-8 pt-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
            <div class="bg-gradient-to-br from-green-50 to-emerald-50 rounded-2xl p-6 border border-green-100 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-green-600 text-sm font-medium">Tổng địa chỉ</p>
                        <h3 class="text-3xl font-bold text-gray-800 mt-2">{{ $addresses->total() }}</h3>
                    </div>
                    <div class="bg-green-100 p-3 rounded-xl">
                        <span class="material-icons text-green-600">location_on</span>
                    </div>
                </div>
            </div>
            
            <div class="bg-gradient-to-br from-blue-50 to-cyan-50 rounded-2xl p-6 border border-blue-100 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-blue-600 text-sm font-medium">Địa chỉ mặc định</p>
                        <h3 class="text-3xl font-bold text-gray-800 mt-2">
                            {{ $addresses->where('is_default', true)->count() }}
                        </h3>
                    </div>
                    <div class="bg-blue-100 p-3 rounded-xl">
                        <span class="material-icons text-blue-600">star</span>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-br from-purple-50 to-pink-50 rounded-2xl p-6 border border-purple-100 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-purple-600 text-sm font-medium">Khách hàng có địa chỉ</p>
                        <h3 class="text-3xl font-bold text-gray-800 mt-2">
                            {{ $addresses->groupBy('user_id')->count() }}
                        </h3>
                    </div>
                    <div class="bg-purple-100 p-3 rounded-xl">
                        <span class="material-icons text-purple-600">people</span>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-br from-orange-50 to-amber-50 rounded-2xl p-6 border border-orange-100 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-orange-600 text-sm font-medium">Địa chỉ mới (7 ngày)</p>
                        <h3 class="text-3xl font-bold text-gray-800 mt-2">
                            {{ $addresses->where('created_at', '>=', now()->subDays(7))->count() }}
                        </h3>
                    </div>
                    <div class="bg-orange-100 p-3 rounded-xl">
                        <span class="material-icons text-orange-600">schedule</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="p-8">
        @if(session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-lg mb-6 shadow-sm">
                <div class="flex items-center">
                    <span class="material-icons text-green-500 mr-2">check_circle</span>
                    {{ session('success') }}
                </div>
            </div>
        @endif

        @if($addresses->count() > 0)
        <div class="bg-gray-50 rounded-2xl border border-gray-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-gradient-to-r from-gray-100 to-slate-100 border-b border-gray-200">
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700 uppercase tracking-wider">
                                <div class="flex items-center">
                                    <span class="material-icons mr-2 text-gray-500 text-sm">tag</span>
                                    ID
                                </div>
                            </th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700 uppercase tracking-wider">
                                <div class="flex items-center">
                                    <span class="material-icons mr-2 text-gray-500 text-sm">person</span>
                                    Khách hàng
                                </div>
                            </th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700 uppercase tracking-wider">
                                <div class="flex items-center">
                                    <span class="material-icons mr-2 text-gray-500 text-sm">contact_phone</span>
                                    Liên hệ
                                </div>
                            </th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700 uppercase tracking-wider">
                                <div class="flex items-center">
                                    <span class="material-icons mr-2 text-gray-500 text-sm">place</span>
                                    Địa chỉ
                                </div>
                            </th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700 uppercase tracking-wider">
                                <div class="flex items-center">
                                    <span class="material-icons mr-2 text-gray-500 text-sm">star</span>
                                    Loại
                                </div>
                            </th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700 uppercase tracking-wider">
                                <div class="flex items-center">
                                    <span class="material-icons mr-2 text-gray-500 text-sm">schedule</span>
                                    Ngày tạo
                                </div>
                            </th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700 uppercase tracking-wider">
                                <div class="flex items-center">
                                    <span class="material-icons mr-2 text-gray-500 text-sm">settings</span>
                                    Thao tác
                                </div>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($addresses as $address)
                        <tr class="transition-all duration-200 hover:bg-white group">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm font-mono text-gray-600">#{{ $address->id }}</span>
                            </td>
                            <td class="px-6 py-4">
                                @if($address->user)
                                <div class="flex items-center">
                                    <div class="bg-gradient-to-br from-green-100 to-teal-100 w-10 h-10 rounded-full flex items-center justify-center mr-3">
                                        <span class="material-icons text-green-600 text-sm">person</span>
                                    </div>
                                    <div>
                                        <span class="font-semibold text-gray-900 group-hover:text-green-600 transition-colors">
                                            {{ $address->user->name }}
                                        </span>
                                        <p class="text-sm text-gray-500">{{ $address->user->email }}</p>
                                    </div>
                                </div>
                                @else
                                <div class="flex items-center text-red-500">
                                    <span class="material-icons text-sm mr-2">warning</span>
                                    User đã xóa
                                </div>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="space-y-1">
                                    <div class="font-medium text-gray-900">{{ $address->name }}</div>
                                    <div class="text-sm text-gray-600">{{ $address->phone }}</div>
                                    @if($address->email)
                                    <div class="text-sm text-gray-500">{{ $address->email }}</div>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="max-w-xs">
                                    <div class="text-gray-800 font-medium">{{ $address->address_line }}</div>
                                    <div class="text-sm text-gray-600 mt-1">
                                        {{ $address->ward }}, {{ $address->district }}, {{ $address->province }}
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                @if($address->is_default)
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 border border-green-200">
                                    <span class="material-icons text-xs mr-1">star</span>
                                    Mặc định
                                </span>
                                @else
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-600 border border-gray-200">
                                    <span class="material-icons text-xs mr-1">location_on</span>
                                    Thường
                                </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex flex-col">
                                    <span class="text-sm text-gray-900">{{ $address->created_at->format('d/m/Y') }}</span>
                                    <span class="text-xs text-gray-500">{{ $address->created_at->format('H:i') }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center space-x-2">
                                    <a href="{{ route('admin.addresses.show', $address) }}" 
                                       class="inline-flex items-center px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-all duration-200 shadow-sm hover:shadow-md group/btn">
                                        <span class="material-icons text-sm mr-1">visibility</span>
                                        <span class="text-sm font-medium">Xem</span>
                                    </a>
                                    <form action="{{ route('admin.addresses.destroy', $address) }}" method="POST" 
                                          onsubmit="return confirm('Bạn có chắc muốn xóa địa chỉ này?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="inline-flex items-center px-4 py-2 bg-red-50 text-red-600 rounded-lg hover:bg-red-100 transition-all duration-200 border border-red-200 hover:border-red-300">
                                            <span class="material-icons text-sm">delete</span>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        
        <!-- Pagination -->
        <div class="mt-6 flex justify-between items-center">
            <div class="text-sm text-gray-500">
                Hiển thị {{ $addresses->firstItem() ?? 0 }} - {{ $addresses->lastItem() ?? 0 }} 
                của {{ $addresses->total() }} địa chỉ
            </div>
            <div>
                {{ $addresses->links() }}
            </div>
        </div>
        @else
        <!-- Empty State -->
        <div class="text-center py-16">
            <div class="bg-gradient-to-br from-gray-100 to-gray-50 w-24 h-24 rounded-2xl flex items-center justify-center mx-auto mb-6 shadow-inner">
                <span class="material-icons text-4xl text-gray-400">location_off</span>
            </div>
            <h3 class="text-xl font-semibold text-gray-600 mb-2">Chưa có địa chỉ nào</h3>
            <p class="text-gray-500 max-w-md mx-auto">Khi khách hàng thêm địa chỉ giao hàng, thông tin sẽ xuất hiện tại đây.</p>
        </div>
        @endif
    </div>
</div>

<style>
.material-icons {
    font-size: 1.25rem;
}
</style>
@endsection