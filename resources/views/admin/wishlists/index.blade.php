@extends('layouts.admin')

@section('title', 'Sản phẩm Yêu thích')

@section('content')
<div class="bg-white rounded-lg shadow">
    <div class="p-6 border-b">
        <h2 class="text-2xl font-bold text-gray-800">Sản phẩm Yêu thích</h2>
        <p class="text-gray-600">Quản lý danh sách sản phẩm yêu thích của khách hàng</p>
    </div>

    <div class="p-6">
        @if($wishlists->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full table-auto">
                <thead>
                    <tr class="bg-gray-50">
                        <th class="px-4 py-3 text-left">ID</th>
                        <th class="px-4 py-3 text-left">Người dùng</th>
                        <th class="px-4 py-3 text-left">Sản phẩm</th>
                        <th class="px-4 py-3 text-left">Giá</th>
                        <th class="px-4 py-3 text-left">Ngày thêm</th>
                        <th class="px-4 py-3 text-left">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($wishlists as $wishlist)
                    <tr class="border-b hover:bg-gray-50">
                        <td class="px-4 py-3">{{ $wishlist->id }}</td>
                        <td class="px-4 py-3">
                            @if($wishlist->user)
                            {{ $wishlist->user->name }}
                            @else
                            <span class="text-red-500">Đã xóa</span>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            @if($wishlist->product)
                            {{ $wishlist->product->name }}
                            @else
                            <span class="text-red-500">Đã xóa</span>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            @if($wishlist->product)
                            {{ number_format($wishlist->product->price) }}₫
                            @else
                            <span class="text-red-500">-</span>
                            @endif
                        </td>
                        <td class="px-4 py-3">{{ $wishlist->created_at->format('d/m/Y H:i') }}</td>
                        <td class="px-4 py-3">
                            <form action="{{ route('admin.wishlists.destroy', $wishlist) }}" method="POST" 
                                  onsubmit="return confirm('Bạn có chắc muốn xóa khỏi danh sách yêu thích?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-800">
                                    <span class="material-icons text-sm">delete</span>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <div class="mt-4">
            {{ $wishlists->links() }}
        </div>
        @else
        <div class="text-center py-8">
            <span class="material-icons text-6xl text-gray-400">favorite</span>
            <p class="mt-4 text-gray-500">Chưa có sản phẩm yêu thích nào</p>
        </div>
        @endif
    </div>
</div>
@endsection