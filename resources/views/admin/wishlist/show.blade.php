@extends('layouts.admin')

@section('title', 'Chi tiết Wishlist - ' . $user->name)

@section('content')
    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <!-- Header -->
        <div class="bg-gradient-to-r from-green-500 to-teal-600 px-8 py-6">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-bold text-white mb-2">Wishlist của {{ $user->name }}</h1>
                    <p class="text-purple-100 text-lg">ID: {{ $user->id }} | Email: {{ $user->email }}</p>
                </div>
                <a href="{{ route('admin.wishlists.index') }}"
                    class="bg-white text-purple-600 px-4 py-2 rounded-lg font-medium hover:bg-purple-50 transition duration-200 flex items-center shadow-sm">
                    <span class="material-icons mr-2">arrow_back</span>
                    Quay lại
                </a>
            </div>
        </div>

        <div class="p-8">
            @if($wishlistProducts->count() > 0)
                <!-- Stats -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                    <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-2xl p-6 border border-blue-100">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-blue-600 text-sm font-medium">Tổng sản phẩm</p>
                                <h3 class="text-3xl font-bold text-gray-800 mt-2">{{ $wishlistProducts->total() }}</h3>
                            </div>
                            <div class="bg-blue-100 p-3 rounded-xl">
                                <span class="material-icons text-blue-600">shopping_bag</span>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gradient-to-br from-green-50 to-emerald-50 rounded-2xl p-6 border border-green-100">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-green-600 text-sm font-medium">Tổng giá trị</p>
                                <h3 class="text-3xl font-bold text-gray-800 mt-2">
                                    {{ number_format($wishlistProducts->sum('price')) }}₫
                                </h3>
                            </div>
                            <div class="bg-green-100 p-3 rounded-xl">
                                <span class="material-icons text-green-600">attach_money</span>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gradient-to-br from-purple-50 to-pink-50 rounded-2xl p-6 border border-purple-100">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-purple-600 text-sm font-medium">Sản phẩm còn hàng</p>
                                <h3 class="text-3xl font-bold text-gray-800 mt-2">
                                    {{ $wishlistProducts->where('stock', '>', 0)->count() }}
                                </h3>
                            </div>
                            <div class="bg-purple-100 p-3 rounded-xl">
                                <span class="material-icons text-purple-600">inventory_2</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Products Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                    @foreach($wishlistProducts as $product)
                        <div
                            class="bg-white rounded-2xl border border-gray-200 overflow-hidden hover:shadow-lg transition-all duration-300 hover:border-purple-200">
                            <!-- Product Image -->
                            <div class="relative">
                                @if($product->image)
                                    <div class="w-full h-48 bg-gray-50 rounded-t-2xl overflow-hidden flex items-center justify-center">
                                        <img src="{{ asset($product->image) }}" 
                                            alt="{{ $product->name }}"
                                            class="max-w-full max-h-full object-scale-down">
                                    </div>
                                @else
                                    <div class="w-full h-48 bg-gradient-to-br from-gray-50 to-gray-100 rounded-t-2xl flex flex-col items-center justify-center">
                                        <span class="material-icons text-gray-300 text-4xl mb-2">inventory_2</span>
                                        <p class="text-sm text-gray-400">Chưa có ảnh</p>
                                    </div>
                                @endif
                                <!-- Stock Badge -->
                                <div class="absolute top-3 right-3">
                                    @if($product->stock > 0)
                                        <span
                                            class="px-3 py-1 text-xs font-medium rounded-full bg-green-500 text-white shadow-lg border border-white/20">
                                            Còn {{ $product->stock }}
                                        </span>
                                    @else
                                        <span
                                            class="px-3 py-1 text-xs font-medium rounded-full bg-red-500 text-white shadow-lg border border-white/20">
                                            Hết hàng
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <!-- Product Info -->
                            <div class="p-6">
                                <h3 class="font-semibold text-lg text-gray-800 mb-3 line-clamp-2 leading-tight">
                                    {{ $product->name }}
                                </h3>

                                <!-- Price -->
                                <div class="flex items-center justify-between mb-4">
                                    <span class="text-2xl font-bold text-purple-600">{{ number_format($product->price) }}₫</span>
                                    <span class="text-sm text-gray-500 bg-gray-100 px-2 py-1 rounded">
                                        ID: {{ $product->id }}
                                    </span>
                                </div>

                                <!-- Meta Info -->
                                <div class="flex items-center justify-between text-sm text-gray-500 mb-4">
                                    <div class="flex items-center">
                                        <span class="material-icons text-gray-400 text-sm mr-1">category</span>
                                        {{ $product->category->name ?? 'N/A' }}
                                    </div>
                                    <div class="flex items-center">
                                        <span class="material-icons text-gray-400 text-sm mr-1">inventory_2</span>
                                        {{ $product->brand->name ?? 'N/A' }}
                                    </div>
                                </div>

                                <!-- Actions -->
                                <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                                    <div class="text-sm text-gray-500">
                                        <div class="flex items-center">
                                            <span class="material-icons text-sm mr-1 text-purple-500">favorite</span>
                                            Thêm: {{ $product->pivot->created_at->format('d/m/Y') }}
                                        </div>
                                    </div>
                                    <div class="flex space-x-2">
                                        <form
                                            action="{{ route('admin.wishlists.remove', ['userId' => $user->id, 'productId' => $product->id]) }}"
                                            method="POST" onsubmit="return confirm('Xóa sản phẩm khỏi wishlist của người dùng?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="inline-flex items-center px-3 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition-all duration-200 text-sm shadow-sm"
                                                title="Xóa khỏi wishlist">
                                                <span class="material-icons text-sm">delete</span>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-8 flex justify-between items-center">
                    <div class="text-sm text-gray-500">
                        Hiển thị {{ $wishlistProducts->firstItem() ?? 0 }} - {{ $wishlistProducts->lastItem() ?? 0 }}
                        của {{ $wishlistProducts->total() }} sản phẩm
                    </div>
                    <div class="flex space-x-2">
                        {{ $wishlistProducts->links() }}
                    </div>
                </div>
            @else
                <!-- Empty State -->
                <div class="text-center py-16">
                    <div
                        class="bg-gradient-to-br from-gray-50 to-gray-100 w-24 h-24 rounded-2xl flex items-center justify-center mx-auto mb-6 shadow-inner">
                        <span class="material-icons text-4xl text-gray-400">favorite_border</span>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-600 mb-2">Người dùng chưa có sản phẩm yêu thích</h3>
                    <p class="text-gray-500 max-w-md mx-auto mb-6">Khi {{ $user->name }} thêm sản phẩm vào wishlist, thông tin
                        sẽ xuất hiện tại đây.</p>
                    <a href="{{ route('admin.wishlists.index') }}"
                        class="inline-flex items-center px-6 py-3 bg-purple-500 text-white rounded-lg hover:bg-purple-600 transition duration-200 shadow-sm">
                        <span class="material-icons mr-2">arrow_back</span>
                        Quay lại danh sách
                    </a>
                </div>
            @endif
        </div>
    </div>

    <style>
        .line-clamp-2 {
            overflow: hidden;
            display: -webkit-box;
            -webkit-box-orient: vertical;
            -webkit-line-clamp: 2;
        }

        .material-icons {
            font-size: 1.25rem;
        }
    </style>
@endsection