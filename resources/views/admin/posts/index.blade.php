@extends('layouts.admin')

@section('title', 'Quản lý Bài viết')

@section('content')
<div class="bg-white rounded-lg shadow">
    <div class="p-6 border-b flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Quản lý Bài viết</h2>
            <p class="text-gray-600">Quản lý bài viết và tin tức</p>
        </div>
        <a href="{{ route('admin.posts.create') }}" 
           class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 flex items-center">
            <span class="material-icons mr-2 text-sm">add</span>
            Thêm Bài viết
        </a>
    </div>

    <div class="p-6">
        @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
        @endif

        @if($posts->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full table-auto">
                <thead>
                    <tr class="bg-gray-50">
                        <th class="px-4 py-3 text-left">ID</th>
                        <th class="px-4 py-3 text-left">Tiêu đề</th>
                        <th class="px-4 py-3 text-left">Tác giả</th>
                        <th class="px-4 py-3 text-left">Trạng thái</th>
                        <th class="px-4 py-3 text-left">Ngày tạo</th>
                        <th class="px-4 py-3 text-left">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($posts as $post)
                    <tr class="border-b hover:bg-gray-50">
                        <td class="px-4 py-3">{{ $post->id }}</td>
                        <td class="px-4 py-3">
                            <div class="font-medium">{{ Str::limit($post->title, 50) }}</div>
                        </td>
                        <td class="px-4 py-3">{{ $post->author->name ?? 'N/A' }}</td>
                        <td class="px-4 py-3">
                            <span class="px-2 py-1 text-xs rounded-full {{ $post->status ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                {{ $post->status ? 'Hiển thị' : 'Ẩn' }}
                            </span>
                        </td>
                        <td class="px-4 py-3">{{ $post->created_at->format('d/m/Y') }}</td>
                        <td class="px-4 py-3">
                            <div class="flex space-x-2">
                                <a href="{{ route('admin.posts.show', $post) }}" 
                                   class="text-blue-600 hover:text-blue-800" title="Xem">
                                    <span class="material-icons text-sm">visibility</span>
                                </a>
                                <a href="{{ route('admin.posts.edit', $post) }}" 
                                   class="text-green-600 hover:text-green-800" title="Sửa">
                                    <span class="material-icons text-sm">edit</span>
                                </a>
                                <form action="{{ route('admin.posts.destroy', $post) }}" method="POST" 
                                      onsubmit="return confirm('Bạn có chắc muốn xóa bài viết này?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800" title="Xóa">
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
            {{ $posts->links() }}
        </div>
        @else
        <div class="text-center py-8">
            <span class="material-icons text-6xl text-gray-400">article</span>
            <p class="mt-4 text-gray-500">Chưa có bài viết nào</p>
            <a href="{{ route('admin.posts.create') }}" 
               class="mt-4 bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 inline-flex items-center">
                <span class="material-icons mr-2 text-sm">add</span>
                Thêm Bài viết Đầu tiên
            </a>
        </div>
        @endif
    </div>
</div>
@endsection