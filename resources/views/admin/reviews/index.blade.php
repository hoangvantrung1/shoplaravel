@extends('layouts.admin')

@section('title', 'Quản lý Đánh giá')

@section('content')
<div class="bg-white rounded-lg shadow">
    <div class="bg-gradient-to-r from-green-500 to-green-700 px-6 py-4 rounded-t-lg shadow-md">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-white">Quản lý Đánh giá</h1>
                <p class="text-green-100 mt-1">Quản lý và xem đánh giá từ khách hàng</p>
            </div>
        </div>
    </div>

    <div class="p-6">
        @if($reviews->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full table-auto">
                <thead>
                    <tr class="bg-gray-50">
                        <th class="px-4 py-3 text-left">ID</th>
                        <th class="px-4 py-3 text-left">Sản phẩm</th>
                        <th class="px-4 py-3 text-left">Người dùng</th>
                        <th class="px-4 py-3 text-left">Đánh giá</th>
                        <th class="px-4 py-3 text-left">Sao</th>
                        <th class="px-4 py-3 text-left">Ngày tạo</th>
                        <th class="px-4 py-3 text-left">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($reviews as $review)
                    <tr class="border-b hover:bg-gray-50">
                        <td class="px-4 py-3">{{ $review->id }}</td>
                        <td class="px-4 py-3">
                            @if($review->product)
                            {{ $review->product->name }}
                            @else
                            <span class="text-red-500">Đã xóa</span>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            @if($review->user)
                            {{ $review->user->name }}
                            @else
                            <span class="text-red-500">Đã xóa</span>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            <div class="max-w-xs truncate">{{ $review->comment }}</div>
                            <div class="flex items-center gap-2 mt-1">
                                @if($review->images && count($review->images) > 0)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800">
                                        <i class="fas fa-images mr-1 text-xs"></i>{{ count($review->images) }} ảnh
                                    </span>
                                @endif
                                @if($review->video)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-purple-100 text-purple-800">
                                        <i class="fas fa-video mr-1 text-xs"></i>Video
                                    </span>
                                @endif
                            </div>
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex text-yellow-400">
                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <= $review->rating)
                                    <span class="material-icons text-sm">star</span>
                                    @else
                                    <span class="material-icons text-sm">star_border</span>
                                    @endif
                                @endfor
                            </div>
                        </td>
                        <td class="px-4 py-3">{{ $review->created_at->format('d/m/Y') }}</td>
                        <td class="px-4 py-3">
                            <div class="flex space-x-2">
                                <a href="{{ route('admin.reviews.show', $review) }}" 
                                   class="text-blue-600 hover:text-blue-800">
                                    <span class="material-icons text-sm">visibility</span>
                                </a>
                                <form action="{{ route('admin.reviews.destroy', $review) }}" method="POST" 
                                      onsubmit="return confirm('Bạn có chắc muốn xóa đánh giá này?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800">
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
        
        <div class="mt-4">
            {{ $reviews->links() }}
        </div>
        @else
        <div class="text-center py-8">
            <span class="material-icons text-6xl text-gray-400">reviews</span>
            <p class="mt-4 text-gray-500">Chưa có đánh giá nào</p>
        </div>
        @endif
    </div>
</div>
@endsection