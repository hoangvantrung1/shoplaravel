@extends('layouts.admin')

@section('title', 'Chi tiết Đánh giá')

@section('content')
<div class="bg-white rounded-xl shadow-lg overflow-hidden">
    <!-- Header -->
    <div class="bg-gradient-to-r from-blue-500 to-blue-600 px-6 py-4">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-white">Chi tiết Đánh giá</h1>
                <p class="text-blue-100 mt-1">Thông tin chi tiết đánh giá #{{ $review->id }}</p>
            </div>
            <a href="{{ route('admin.reviews.index') }}" 
               class="bg-white text-blue-600 px-4 py-2 rounded-lg font-medium hover:bg-blue-50 transition duration-200 flex items-center shadow-sm">
                <span class="material-icons mr-2">arrow_back</span>
                Quay lại danh sách
            </a>
        </div>
    </div>

    <div class="p-6">
        <!-- Thông tin chính -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
            <!-- Card Sản phẩm -->
            <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-xl p-5 border border-blue-100">
                <div class="flex items-center mb-4">
                    <div class="bg-blue-100 p-3 rounded-lg">
                        <span class="material-icons text-blue-600">shopping_bag</span>
                    </div>
                    <h3 class="ml-3 font-semibold text-gray-800">Sản phẩm</h3>
                </div>
                @if($review->product)
                <p class="text-lg font-medium text-gray-900">{{ $review->product->name }}</p>
                <div class="mt-2 flex items-center text-sm text-gray-600">
                    <span class="material-icons text-sm mr-1">inventory_2</span>
                    ID: {{ $review->product->id }}
                </div>
                @else
                <div class="flex items-center text-red-600">
                    <span class="material-icons mr-2">error</span>
                    <span class="font-medium">Sản phẩm đã bị xóa</span>
                </div>
                @endif
            </div>

            <!-- Card Người dùng -->
            <div class="bg-gradient-to-br from-green-50 to-emerald-50 rounded-xl p-5 border border-green-100">
                <div class="flex items-center mb-4">
                    <div class="bg-green-100 p-3 rounded-lg">
                        <span class="material-icons text-green-600">person</span>
                    </div>
                    <h3 class="ml-3 font-semibold text-gray-800">Người dùng</h3>
                </div>
                @if($review->user)
                <p class="text-lg font-medium text-gray-900">{{ $review->user->name }}</p>
                <p class="text-sm text-gray-600 mt-1">{{ $review->user->email }}</p>
                @else
                <div class="flex items-center text-red-600">
                    <span class="material-icons mr-2">error</span>
                    <span class="font-medium">Người dùng đã bị xóa</span>
                </div>
                @endif
            </div>

            <!-- Card Đánh giá sao -->
            <div class="bg-gradient-to-br from-yellow-50 to-amber-50 rounded-xl p-5 border border-yellow-100">
                <div class="flex items-center mb-4">
                    <div class="bg-yellow-100 p-3 rounded-lg">
                        <span class="material-icons text-yellow-600">star</span>
                    </div>
                    <h3 class="ml-3 font-semibold text-gray-800">Đánh giá</h3>
                </div>
                <div class="flex items-center">
                    <div class="flex text-yellow-400 mr-3">
                        @for($i = 1; $i <= 5; $i++)
                            @if($i <= $review->rating)
                            <span class="material-icons text-2xl">star</span>
                            @else
                            <span class="material-icons text-2xl">star_border</span>
                            @endif
                        @endfor
                    </div>
                    <span class="text-xl font-bold text-gray-800 bg-yellow-100 px-3 py-1 rounded-full">
                        {{ $review->rating }}/5
                    </span>
                </div>
            </div>
        </div>

        <!-- Bình luận và Thông tin bổ sung -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Card Bình luận -->
            <div class="bg-gray-50 rounded-xl p-5 border border-gray-200">
                <div class="flex items-center mb-4">
                    <div class="bg-purple-100 p-3 rounded-lg">
                        <span class="material-icons text-purple-600">chat_bubble</span>
                    </div>
                    <h3 class="ml-3 font-semibold text-gray-800">Bình luận</h3>
                </div>
                @if($review->comment)
                <div class="bg-white rounded-lg p-4 border border-gray-200">
                    <p class="text-gray-700 leading-relaxed">{{ $review->comment }}</p>
                </div>
                @else
                <div class="text-center py-6 text-gray-500">
                    <span class="material-icons text-4xl mb-3 text-gray-400">chat_bubble_outline</span>
                    <p class="font-medium">Không có bình luận</p>
                </div>
                @endif
            </div>

            <!-- Card Thông tin thời gian -->
            <div class="bg-gray-50 rounded-xl p-5 border border-gray-200">
                <div class="flex items-center mb-4">
                    <div class="bg-gray-100 p-3 rounded-lg">
                        <span class="material-icons text-gray-600">schedule</span>
                    </div>
                    <h3 class="ml-3 font-semibold text-gray-800">Thông tin thời gian</h3>
                </div>
                <div class="space-y-4">
                    <div class="flex justify-between items-center p-3 bg-white rounded-lg border border-gray-200">
                        <div class="flex items-center">
                            <span class="material-icons text-green-500 mr-3">create</span>
                            <span class="text-gray-700">Ngày tạo</span>
                        </div>
                        <span class="font-medium text-gray-900">{{ $review->created_at->format('d/m/Y H:i') }}</span>
                    </div>
                    <div class="flex justify-between items-center p-3 bg-white rounded-lg border border-gray-200">
                        <div class="flex items-center">
                            <span class="material-icons text-blue-500 mr-3">update</span>
                            <span class="text-gray-700">Cập nhật cuối</span>
                        </div>
                        <span class="font-medium text-gray-900">{{ $review->updated_at->format('d/m/Y H:i') }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="mt-8 pt-6 border-t border-gray-200">
            <div class="flex justify-between items-center">
                <div class="text-sm text-gray-500">
                    ID: {{ $review->id }}
                </div>
                <div class="flex space-x-3">
                    <form action="{{ route('admin.reviews.destroy', $review) }}" method="POST" 
                          onsubmit="return confirm('Bạn có chắc muốn xóa đánh giá này?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="bg-red-600 text-white px-5 py-2.5 rounded-lg font-medium hover:bg-red-700 transition duration-200 flex items-center shadow-sm">
                            <span class="material-icons mr-2 text-sm">delete</span>
                            Xóa Đánh giá
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.material-icons {
    font-size: 1.25rem;
}
</style>
@endsection