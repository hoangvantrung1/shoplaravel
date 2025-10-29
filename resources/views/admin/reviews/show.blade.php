@extends('layouts.admin')

@section('title', 'Chi tiết Đánh giá')

@section('content')
<div class="bg-white rounded-lg shadow">
    <div class="p-6 border-b flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Chi tiết Đánh giá</h2>
            <p class="text-gray-600">Thông tin chi tiết đánh giá #{{ $review->id }}</p>
        </div>
        <a href="{{ route('admin.reviews.index') }}" class="text-blue-600 hover:text-blue-800 flex items-center">
            <span class="material-icons mr-1">arrow_back</span>
            Quay lại
        </a>
    </div>

    <div class="p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Sản phẩm</label>
                    <p class="mt-1 text-lg">
                        @if($review->product)
                        {{ $review->product->name }}
                        @else
                        <span class="text-red-500">Sản phẩm đã bị xóa</span>
                        @endif
                    </p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Người dùng</label>
                    <p class="mt-1 text-lg">
                        @if($review->user)
                        {{ $review->user->name }} ({{ $review->user->email }})
                        @else
                        <span class="text-red-500">Người dùng đã bị xóa</span>
                        @endif
                    </p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Đánh giá sao</label>
                    <div class="flex text-yellow-400 mt-1">
                        @for($i = 1; $i <= 5; $i++)
                            @if($i <= $review->rating)
                            <span class="material-icons">star</span>
                            @else
                            <span class="material-icons">star_border</span>
                            @endif
                        @endfor
                        <span class="ml-2 text-gray-700">({{ $review->rating }}/5)</span>
                    </div>
                </div>
            </div>

            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Bình luận</label>
                    <div class="mt-1 p-4 bg-gray-50 rounded-lg">
                        {{ $review->comment ?? 'Không có bình luận' }}
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Ngày tạo</label>
                        <p class="mt-1">{{ $review->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Cập nhật</label>
                        <p class="mt-1">{{ $review->updated_at->format('d/m/Y H:i') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-6 pt-6 border-t">
            <form action="{{ route('admin.reviews.destroy', $review) }}" method="POST" 
                  onsubmit="return confirm('Bạn có chắc muốn xóa đánh giá này?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700 flex items-center">
                    <span class="material-icons mr-2 text-sm">delete</span>
                    Xóa Đánh giá
                </button>
            </form>
        </div>
    </div>
</div>
@endsection