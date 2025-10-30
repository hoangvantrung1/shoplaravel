@extends('layouts.admin')

@section('title', 'Chi tiết Bài viết')

@section('content')
<div class="bg-white rounded-xl shadow-lg overflow-hidden">
    <!-- Header -->
    <div class="bg-gradient-to-r from-green-500 to-teal-600 px-6 py-4">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-white">Chi tiết Bài viết</h1>
                <p class="text-green-100 mt-1">Thông tin chi tiết bài viết #{{ $post->id }}</p>
            </div>
            <a href="{{ route('admin.posts.index') }}" 
               class="bg-white text-green-600 px-4 py-2 rounded-lg font-medium hover:bg-green-50 transition duration-200 flex items-center shadow-sm">
                <span class="material-icons mr-2">arrow_back</span>
                Quay lại
            </a>
        </div>
    </div>
    <div class="p-6">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
            <!-- Card Thông tin chính -->
            <div class="bg-gradient-to-br from-green-50 to-emerald-50 rounded-xl p-5 border border-green-100">
                <div class="flex items-center mb-4">
                    <div class="bg-green-100 p-3 rounded-lg">
                        <span class="material-icons text-green-600">article</span>
                    </div>
                    <h3 class="ml-3 font-semibold text-gray-800">Thông tin chính</h3>
                </div>
                <div class="space-y-3">
                    <div>
                        <label class="block text-sm font-medium text-gray-600">Tiêu đề</label>
                        <p class="mt-1 text-lg font-semibold text-gray-900">{{ $post->title }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-600">Slug</label>
                        <p class="mt-1 text-gray-700">{{ $post->slug }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-600">Trạng thái</label>
                        <span class="mt-1 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                            {{ $post->status === 'published' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                            {{ $post->status === 'published' ? 'Đã xuất bản' : 'Bản nháp' }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Card Hình ảnh -->
            <div class="bg-gradient-to-br from-purple-50 to-violet-50 rounded-xl p-5 border border-purple-100">
                <div class="flex items-center mb-4">
                    <div class="bg-purple-100 p-3 rounded-lg">
                        <span class="material-icons text-purple-600">image</span>
                    </div>
                    <h3 class="ml-3 font-semibold text-gray-800">Hình ảnh đại diện</h3>
                </div>
                <div class="space-y-3">
                    @if($post->featured_image && file_exists(public_path($post->featured_image)))
                        <div class="bg-white rounded-lg p-3 border border-gray-200">
                            <img src="{{ asset($post->featured_image) }}" 
                                 alt="{{ $post->title }}"
                                 class="w-full h-48 object-cover rounded-lg">
                            <div class="mt-2 text-xs text-gray-500 text-center">
                                <p>Đường dẫn: {{ $post->featured_image }}</p>
                            </div>
                        </div>
                    @else
                        <div class="text-center py-8 bg-white rounded-lg border-2 border-dashed border-gray-300">
                            <span class="material-icons text-gray-400 text-4xl mb-2">image_not_supported</span>
                            <p class="text-gray-500 text-sm">Chưa có hình ảnh đại diện</p>
                            @if($post->featured_image)
                                <p class="text-red-400 text-xs mt-1">File không tồn tại: {{ $post->featured_image }}</p>
                            @endif
                        </div>
                    @endif
                </div>
            </div>

            <!-- Card Thời gian -->
            <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-xl p-5 border border-blue-100">
                <div class="flex items-center mb-4">
                    <div class="bg-blue-100 p-3 rounded-lg">
                        <span class="material-icons text-blue-600">schedule</span>
                    </div>
                    <h3 class="ml-3 font-semibold text-gray-800">Thời gian</h3>
                </div>
                <div class="space-y-3">
                    <div>
                        <label class="block text-sm font-medium text-gray-600">Ngày tạo</label>
                        <p class="mt-1 text-gray-900">{{ $post->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-600">Cập nhật</label>
                        <p class="mt-1 text-gray-900">{{ $post->updated_at->format('d/m/Y H:i') }}</p>
                    </div>
                    @if($post->published_at)
                    <div>
                        <label class="block text-sm font-medium text-gray-600">Xuất bản</label>
                        <p class="mt-1 text-gray-900">{{ $post->published_at->format('d/m/Y H:i') }}</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Card Tác giả & Mô tả -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
            <!-- Card Tác giả -->
            <div class="bg-gradient-to-br from-orange-50 to-amber-50 rounded-xl p-5 border border-orange-100">
                <div class="flex items-center mb-4">
                    <div class="bg-orange-100 p-3 rounded-lg">
                        <span class="material-icons text-orange-600">person</span>
                    </div>
                    <h3 class="ml-3 font-semibold text-gray-800">Tác giả</h3>
                </div>
                <div class="space-y-3">
                    <div>
                        <label class="block text-sm font-medium text-gray-600">Người tạo</label>
                        <p class="mt-1 text-gray-900">{{ $post->user->name ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-600">Email</label>
                        <p class="mt-1 text-gray-700">{{ $post->user->email ?? 'N/A' }}</p>
                    </div>
                </div>
            </div>

            <!-- Mô tả -->
            @if($post->excerpt)
            <div class="bg-gradient-to-br from-teal-50 to-cyan-50 rounded-xl p-5 border border-teal-100">
                <div class="flex items-center mb-4">
                    <div class="bg-teal-100 p-3 rounded-lg">
                        <span class="material-icons text-teal-600">short_text</span>
                    </div>
                    <h3 class="ml-3 font-semibold text-gray-800">Mô tả ngắn</h3>
                </div>
                <div class="bg-white rounded-lg p-4 border border-gray-200">
                    <p class="text-gray-700">{{ $post->excerpt }}</p>
                </div>
            </div>
            @endif
        </div>

        <!-- Nội dung -->
        <div class="bg-gray-50 rounded-xl p-5 border border-gray-200 mb-6">
            <div class="flex items-center mb-4">
                <div class="bg-gray-100 p-3 rounded-lg">
                    <span class="material-icons text-gray-600">description</span>
                </div>
                <h3 class="ml-3 font-semibold text-gray-800">Nội dung</h3>
            </div>
            <div class="bg-white rounded-lg p-6 border border-gray-200">
                <div class="prose max-w-none">
                    {!! $post->content !!}
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="mt-8 pt-6 border-t border-gray-200">
            <div class="flex justify-between items-center">
                <div class="text-sm text-gray-500">
                    ID: {{ $post->id }}
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('admin.posts.edit', $post) }}" 
                       class="bg-blue-600 text-white px-5 py-2.5 rounded-lg font-medium hover:bg-blue-700 transition duration-200 flex items-center shadow-sm">
                        <span class="material-icons mr-2 text-sm">edit</span>
                        Chỉnh sửa
                    </a>
                    <form action="{{ route('admin.posts.destroy', $post) }}" method="POST" 
                          onsubmit="return confirm('Bạn có chắc muốn xóa bài viết này?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="bg-red-600 text-white px-5 py-2.5 rounded-lg font-medium hover:bg-red-700 transition duration-200 flex items-center shadow-sm">
                            <span class="material-icons mr-2 text-sm">delete</span>
                            Xóa Bài viết
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
.prose {
    max-width: none !important;
}
</style>
@endsection