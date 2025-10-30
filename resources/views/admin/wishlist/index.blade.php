@extends('layouts.admin')

@section('title', 'Quản lý Wishlist')

@section('content')
<div class="bg-white rounded-xl shadow-lg overflow-hidden">
    <!-- Header -->
    <div class="bg-gradient-to-r from-green-500 to-teal-600 px-8 py-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-white mb-2">Quản lý Wishlist</h1>
                <p class="text-green-100 text-lg">Danh sách người dùng và sản phẩm yêu thích</p>
            </div>
            <div class="bg-white/20 backdrop-blur-sm rounded-xl p-3">
                <span class="material-icons text-white text-4xl">favorite</span>
            </div>
        </div>
    </div>

    <!-- Stats -->
    <div class="px-8 pt-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div class="bg-gradient-to-br from-green-50 to-white-50 rounded-2xl p-6 border border-green-100 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-green-600 text-sm font-medium">Tổng người dùng có wishlist</p>
                        <h3 class="text-3xl font-bold text-gray-800 mt-2">{{ $users->count() }}</h3>
                    </div>
                    <div class="bg-green-100 p-3 rounded-xl">
                        <span class="material-icons text-green-600">people</span>
                    </div>
                </div>
            </div>
            
            <div class="bg-gradient-to-br from-blue-50 to-cyan-50 rounded-2xl p-6 border border-blue-100 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-blue-600 text-sm font-medium">Tổng sản phẩm yêu thích</p>
                        <h3 class="text-3xl font-bold text-gray-800 mt-2">
                            {{ $users->sum('wishlist_products_count') }}
                        </h3>
                    </div>
                    <div class="bg-blue-100 p-3 rounded-xl">
                        <span class="material-icons text-blue-600">shopping_bag</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Table -->
    <div class="p-8">
        @if($users->count() > 0)
        <div class="bg-gray-50 rounded-2xl border border-gray-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-gradient-to-r from-gray-100 to-slate-100 border-b border-gray-200">
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700 uppercase tracking-wider">
                                Người dùng
                            </th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700 uppercase tracking-wider">
                                Email
                            </th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700 uppercase tracking-wider">
                                Số sản phẩm yêu thích
                            </th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700 uppercase tracking-wider">
                                Ngày tham gia
                            </th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700 uppercase tracking-wider">
                                Thao tác
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($users as $user)
                        <tr class="transition-all duration-200 hover:bg-white group">
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <div class="bg-gradient-to-br from-green-100 to-pink-100 w-10 h-10 rounded-full flex items-center justify-center mr-3">
                                        <span class="material-icons text-green-600 text-sm">person</span>
                                    </div>
                                    <div>
                                        <span class="font-semibold text-gray-900 group-hover:text-green-600 transition-colors">
                                            {{ $user->name }}
                                        </span>
                                        <p class="text-sm text-gray-500">ID: {{ $user->id }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-gray-700">{{ $user->email }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                    <span class="material-icons text-xs mr-1">favorite</span>
                                    {{ $user->wishlist_products_count }} sản phẩm
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-sm text-gray-600">{{ $user->created_at->format('d/m/Y') }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex space-x-2">
                                    <a href="{{ route('admin.wishlists.show', $user->id) }}" 
                                       class="inline-flex items-center px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-all duration-200 shadow-sm hover:shadow-md">
                                        <span class="material-icons text-sm mr-1">visibility</span>
                                        Xem chi tiết
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @else
        <!-- Empty State -->
        <div class="text-center py-16">
            <div class="bg-gradient-to-br from-gray-100 to-gray-50 w-24 h-24 rounded-2xl flex items-center justify-center mx-auto mb-6 shadow-inner">
                <span class="material-icons text-4xl text-gray-400">favorite</span>
            </div>
            <h3 class="text-xl font-semibold text-gray-600 mb-2">Chưa có wishlist nào</h3>
            <p class="text-gray-500 max-w-md mx-auto">Khi người dùng thêm sản phẩm vào wishlist, thông tin sẽ xuất hiện tại đây.</p>
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