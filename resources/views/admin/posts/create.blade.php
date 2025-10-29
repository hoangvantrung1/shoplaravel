@extends('layouts.admin')

@section('title', 'Thêm Bài viết Mới')

@section('content')
<div class="bg-white rounded-lg shadow">
    <div class="p-6 border-b">
        <h2 class="text-2xl font-bold text-gray-800">Thêm Bài viết Mới</h2>
        <p class="text-gray-600">Tạo bài viết mới cho website</p>
    </div>

    <div class="p-6">
        <form action="{{ route('admin.posts.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="grid grid-cols-1 gap-6">
                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700">Tiêu đề *</label>
                    <input type="text" name="title" id="title" required
                           class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2">
                </div>

                <div>
                    <label for="content" class="block text-sm font-medium text-gray-700">Nội dung *</label>
                    <textarea name="content" id="content" rows="10" required
                              class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2"></textarea>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="image" class="block text-sm font-medium text-gray-700">Hình ảnh</label>
                        <input type="file" name="image" id="image" 
                               class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2">
                    </div>

                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700">Trạng thái</label>
                        <select name="status" id="status" 
                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2">
                            <option value="1">Hiển thị</option>
                            <option value="0">Ẩn</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="mt-6 flex justify-end space-x-3">
                <a href="{{ route('admin.posts.index') }}" 
                   class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">
                    Hủy
                </a>
                <button type="submit" 
                        class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 flex items-center">
                    <span class="material-icons mr-2 text-sm">save</span>
                    Lưu Bài viết
                </button>
            </div>
        </form>
    </div>
</div>
@endsection