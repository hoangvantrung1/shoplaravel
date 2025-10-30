@extends('layouts.admin')

@section('title', 'Chỉnh sửa Bài viết')

@section('content')
<div class="bg-white rounded-xl shadow-lg overflow-hidden">
    <!-- Header -->
    <div class="bg-gradient-to-r from-green-500 to-green-600 px-6 py-4">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-white">Chỉnh sửa Bài viết</h1>
                <p class="text-green-100 mt-1">Cập nhật thông tin bài viết #{{ $post->id }}</p>
            </div>
            <a href="{{ route('admin.posts.index') }}" 
               class="bg-white text-green-600 px-4 py-2 rounded-lg font-medium hover:bg-green-50 transition duration-200 flex items-center shadow-sm">
                <span class="material-icons mr-2">arrow_back</span>
                Quay lại
            </a>
        </div>
    </div>

    <div class="p-6">
        <form action="{{ route('admin.posts.update', $post) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
                <!-- Thông tin chính -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Tiêu đề -->
                    <div>
                        <label for="title" class="block text-sm font-medium text-gray-700 mb-2">Tiêu đề *</label>
                        <input type="text" 
                               id="title"
                               name="title" 
                               value="{{ old('title', $post->title) }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition duration-200"
                               placeholder="Nhập tiêu đề bài viết"
                               required>
                        @error('title')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Slug -->
                    <div>
                        <label for="slug" class="block text-sm font-medium text-gray-700 mb-2">Slug *</label>
                        <input type="text" 
                               id="slug"
                               name="slug" 
                               value="{{ old('slug', $post->slug) }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition duration-200"
                               placeholder="slug-bai-viet"
                               required>
                        @error('slug')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Mô tả ngắn -->
                    <div>
                        <label for="excerpt" class="block text-sm font-medium text-gray-700 mb-2">Mô tả ngắn</label>
                        <textarea 
                            id="excerpt"
                            name="excerpt"
                            rows="3"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition duration-200"
                            placeholder="Mô tả ngắn về bài viết">{{ old('excerpt', $post->excerpt) }}</textarea>
                        @error('excerpt')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Nội dung -->
                    <div>
                        <label for="content" class="block text-sm font-medium text-gray-700 mb-2">Nội dung *</label>
                        <textarea 
                            id="content"
                            name="content"
                            rows="12"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition duration-200"
                            placeholder="Nhập nội dung bài viết"
                            required>{{ old('content', $post->content) }}</textarea>
                        @error('content')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="space-y-6">
                    <!-- Trạng thái -->
                    <div class="bg-gray-50 rounded-xl p-5 border border-gray-200">
                        <h3 class="font-semibold text-gray-800 mb-4">Trạng thái</h3>
                        <select name="status" 
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition duration-200">
                            <option value="draft" {{ old('status', $post->status) === 'draft' ? 'selected' : '' }}>Bản nháp</option>
                            <option value="published" {{ old('status', $post->status) === 'published' ? 'selected' : '' }}>Đã xuất bản</option>
                        </select>
                    </div>

                    <!-- Hình ảnh -->
                    <div class="bg-gray-50 rounded-xl p-5 border border-gray-200">
                        <h3 class="font-semibold text-gray-800 mb-4">Hình ảnh đại diện</h3>
                        
                        <!-- Hiển thị hình ảnh hiện tại -->
                        <div id="current-image-container" class="mb-4">
                            @if($post->featured_image && file_exists(public_path($post->featured_image)))
                                <div class="relative">
                                    <img src="{{ asset($post->featured_image) }}" 
                                         alt="Featured image" 
                                         class="w-full h-48 object-cover rounded-lg border border-gray-200"
                                         id="current-image">
                                    <button type="button" 
                                            onclick="removeCurrentImage()"
                                            class="absolute top-2 right-2 bg-red-500 text-white p-1 rounded-full hover:bg-red-600 transition duration-200">
                                        <span class="material-icons text-sm">close</span>
                                    </button>
                                </div>
                                <input type="hidden" name="current_image" value="{{ $post->featured_image }}" id="current-image-input">
                                <div class="mt-2 text-xs text-gray-500 text-center">
                                    <p>Đường dẫn: {{ $post->featured_image }}</p>
                                </div>
                            @else
                                <div class="text-center py-8 border-2 border-dashed border-gray-300 rounded-lg bg-gray-50">
                                    <span class="material-icons text-gray-400 text-4xl mb-2">image</span>
                                    <p class="text-gray-500 text-sm">Chưa có hình ảnh</p>
                                    @if($post->featured_image)
                                        <p class="text-red-400 text-xs mt-1">File không tồn tại: {{ $post->featured_image }}</p>
                                    @endif
                                </div>
                            @endif
                        </div>

                        <!-- Upload hình ảnh mới -->
                        <div id="image-upload-container">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Tải lên hình ảnh mới</label>
                            <div class="border-2 border-dashed border-gray-300 rounded-lg p-4 text-center hover:border-green-500 transition duration-200 cursor-pointer"
                                 onclick="document.getElementById('featured_image').click()">
                                <span class="material-icons text-gray-400 text-3xl mb-2">cloud_upload</span>
                                <p class="text-gray-500 text-sm mb-1">Click để chọn hình ảnh</p>
                                <p class="text-gray-400 text-xs">JPG, PNG, GIF (Tối đa 2MB)</p>
                            </div>
                            <input type="file" 
                                   id="featured_image"
                                   name="featured_image"
                                   accept="image/*"
                                   class="hidden"
                                   onchange="previewImage(this)">
                            <div id="image-preview" class="mt-3 hidden">
                                <img id="preview" class="w-full h-32 object-cover rounded-lg border border-gray-200">
                                <button type="button" 
                                        onclick="removePreview()"
                                        class="mt-2 text-red-600 hover:text-red-800 text-sm flex items-center">
                                    <span class="material-icons text-sm mr-1">delete</span>
                                    Xóa ảnh preview
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Thông tin bổ sung -->
                    <div class="bg-gray-50 rounded-xl p-5 border border-gray-200">
                        <h3 class="font-semibold text-gray-800 mb-4">Thông tin</h3>
                        <div class="space-y-3 text-sm">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Ngày tạo:</span>
                                <span class="font-medium">{{ $post->created_at->format('d/m/Y H:i') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Cập nhật:</span>
                                <span class="font-medium">{{ $post->updated_at->format('d/m/Y H:i') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">ID:</span>
                                <span class="font-medium">{{ $post->id }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="flex justify-between items-center pt-6 border-t border-gray-200">
                <div>
                    <a href="{{ route('admin.posts.show', $post) }}" 
                       class="text-green-600 hover:text-green-800 flex items-center font-medium">
                        <span class="material-icons mr-2">visibility</span>
                        Xem chi tiết
                    </a>
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('admin.posts.index') }}" 
                       class="bg-gray-300 text-gray-700 px-6 py-2.5 rounded-lg font-medium hover:bg-gray-400 transition duration-200 flex items-center">
                        <span class="material-icons mr-2 text-sm">cancel</span>
                        Hủy bỏ
                    </a>
                    <button type="submit" 
                            class="bg-green-600 text-white px-6 py-2.5 rounded-lg font-medium hover:bg-green-700 transition duration-200 flex items-center shadow-sm">
                        <span class="material-icons mr-2 text-sm">save</span>
                        Cập nhật Bài viết
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<style>
.material-icons {
    font-size: 1.25rem;
}
</style>

<script>
// Auto generate slug from title
document.getElementById('title').addEventListener('input', function() {
    const title = this.value;
    const slug = title
        .toLowerCase()
        .normalize('NFD')
        .replace(/[\u0300-\u036f]/g, '')
        .replace(/[^a-z0-9 ]/g, '')
        .replace(/\s+/g, '-')
        .replace(/-+/g, '-')
        .replace(/^-|-$/g, '');
    
    document.getElementById('slug').value = slug;
});

// Preview image khi chọn file mới
function previewImage(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        
        reader.onload = function(e) {
            document.getElementById('preview').src = e.target.result;
            document.getElementById('image-preview').classList.remove('hidden');
        }
        
        reader.readAsDataURL(input.files[0]);
    }
}

// Xóa preview image
function removePreview() {
    document.getElementById('featured_image').value = '';
    document.getElementById('image-preview').classList.add('hidden');
    document.getElementById('preview').src = '';
}

// Xóa hình ảnh hiện tại
function removeCurrentImage() {
    if (confirm('Bạn có chắc muốn xóa hình ảnh hiện tại?')) {
        document.getElementById('current-image-container').innerHTML = `
            <div class="text-center py-8 border-2 border-dashed border-gray-300 rounded-lg bg-gray-50">
                <span class="material-icons text-gray-400 text-4xl mb-2">image</span>
                <p class="text-gray-500 text-sm">Đã xóa hình ảnh</p>
            </div>
            <input type="hidden" name="remove_image" value="1">
        `;
    }
}
</script>
@endsection