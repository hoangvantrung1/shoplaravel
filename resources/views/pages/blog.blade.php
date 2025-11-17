@extends('layouts.client')

@section('title', 'Tin tức')

@section('content')
<div class="container py-5">
    <h1 class="text-2xl font-bold mb-4">Tin tức mới nhất</h1>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        @foreach ($posts as $post)
            <div class="border rounded-lg overflow-hidden shadow hover:shadow-lg transition">
                <a href="{{ route('blog.detail', $post->slug) }}">
                    <img src="{{ $post->image_url ?? '/images/default.jpg' }}" class="w-full h-48 object-cover" alt="{{ $post->title }}">
                </a>
                <div class="p-4">
                    <h2 class="font-semibold text-lg mb-2">{{ $post->title }}</h2>
                    <p class="text-gray-600 text-sm line-clamp-3">{{ Str::limit($post->content, 100) }}</p>
                    <a href="{{ route('blog.detail', $post->slug) }}" class="text-blue-600 mt-2 inline-block">Đọc tiếp →</a>
                </div>
            </div>
        @endforeach
    </div>

    <div class="mt-6">
        {{ $posts->links() }}
    </div>
</div>
@endsection
