@extends('layouts.client')

@section('title', $post->title . ' - ShopLaravel')

@section('content')
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-12 mt-10">
        <!-- Breadcrumb -->
        <nav class="flex mb-10" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="{{ route('home') }}"
                        class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-purple-600">
                        <i class="fas fa-home mr-2"></i>
                        Trang chủ
                    </a>
                </li>
                <li>
                    <div class="flex items-center">
                        <i class="fas fa-chevron-right text-gray-400 text-xs"></i>
                        <a href="{{ route('blog') }}"
                            class="ml-3 text-sm font-medium text-gray-700 hover:text-purple-600">Blog</a>
                    </div>
                </li>
                <li aria-current="page">
                    <div class="flex items-center">
                        <i class="fas fa-chevron-right text-gray-400 text-xs"></i>
                        <span class="ml-3 text-sm font-medium text-gray-500 truncate max-w-xs">{{ $post->title }}</span>
                    </div>
                </li>
            </ol>
        </nav>

        <!-- Bài viết chi tiết -->
        <article class="bg-white rounded-lg shadow-lg overflow-hidden">
            <!-- Ảnh cover -->
            <div class="aspect-w-16 aspect-h-9 overflow-hidden">
                <img src="{{ $post->featured_image ?: '/images/default-blog.jpg' }}" alt="{{ $post->title }}"
                    class="w-full h-50 object-cover items-center mx-auto">
            </div>

            <!-- Nội dung bài viết -->
            <div class="p-8">
                <!-- Meta info -->
                <div class="flex flex-wrap items-center justify-between mb-6">
                    <div class="flex items-center space-x-4 mb-4 md:mb-0">
                        @php
                            $category = 'Công nghệ';
                            $categoryColor = 'purple';
                            if (str_contains($post->slug, 'airpods') || str_contains($post->title, 'AirPods')) {
                                $category = 'Âm thanh';
                                $categoryColor = 'green';
                            } elseif (str_contains($post->slug, 'meo') || str_contains($post->title, 'mẹo')) {
                                $category = 'Mẹo hay';
                                $categoryColor = 'blue';
                            }
                        @endphp
                        <span
                            class="bg-{{ $categoryColor }}-100 text-{{ $categoryColor }}-600 px-3 py-1 rounded-full text-sm font-medium">
                            {{ $category }}
                        </span>
                        <span class="text-gray-500"><i
                                class="far fa-clock mr-1"></i>{{ $post->published_at->diffForHumans() }}</span>
                        <span class="text-gray-500"><i class="far fa-eye mr-1"></i>{{ $post->view_count }} lượt xem</span>
                    </div>
                    <div class="flex items-center space-x-2">
                        <span class="text-gray-500">Chia sẻ:</span>
                        <a href="#" class="text-gray-400 hover:text-blue-600">
                            <i class="fab fa-facebook text-lg"></i>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-blue-400">
                            <i class="fab fa-twitter text-lg"></i>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-red-600">
                            <i class="fab fa-pinterest text-lg"></i>
                        </a>
                    </div>
                </div>

                <!-- Tiêu đề -->
                <h1 class="text-3xl md:text-4xl font-bold text-gray-800 mb-6">{{ $post->title }}</h1>

                <!-- Excerpt -->
                @if($post->excerpt)
                    <div class="bg-purple-50 border-l-4 border-purple-500 p-4 mb-8">
                        <p class="text-lg text-gray-700 italic">{{ $post->excerpt }}</p>
                    </div>
                @endif

                <!-- Nội dung -->
                <div class="prose max-w-none text-gray-700 text-lg leading-relaxed">
                    {!! $post->content !!}
                </div>

                <!-- Tags -->
                <div class="mt-8 pt-6 border-t border-gray-200">
                    <div class="flex flex-wrap items-center gap-2">
                        <span class="text-gray-600 font-medium">Tags:</span>
                        @php
                            $tags = [];
                            if (str_contains($post->title, 'iPhone')) {
                                $tags = ['iPhone', 'Apple', 'Review'];
                            } elseif (str_contains($post->title, 'AirPods')) {
                                $tags = ['AirPods', 'Apple', 'Âm thanh'];
                            } elseif (str_contains($post->title, 'laptop')) {
                                $tags = ['Laptop', 'Bảo vệ', 'Mẹo hay'];
                            }
                        @endphp

                        @foreach($tags as $tag)
                            <a href="#"
                                class="bg-gray-100 text-gray-700 px-3 py-1 rounded-full text-sm hover:bg-gray-200 transition">
                                #{{ $tag }}
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </article>

        <!-- Bài viết liên quan -->
        <section class="mt-12">
            <h2 class="text-2xl font-bold text-gray-800 mb-6">Bài viết liên quan</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @php
                    $relatedPosts = App\Models\Post::where('status', 'published')
                        ->where('id', '!=', $post->id)
                        ->orderBy('published_at', 'desc')
                        ->limit(2)
                        ->get();
                @endphp

                @foreach($relatedPosts as $relatedPost)
                    <article class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition">
                        <div class="h-48 overflow-hidden">
                            <img src="{{ $relatedPost->featured_image ?: '/images/default-blog.jpg' }}"
                                alt="{{ $relatedPost->title }}"
                                class="w-full h-full object-cover hover:scale-105 transition duration-300">
                        </div>
                        <div class="p-4">
                            <h3 class="text-lg font-bold text-gray-800 mb-2 hover:text-purple-600 transition">
                                <a href="{{ route('blog.detail', $relatedPost->slug) }}">{{ $relatedPost->title }}</a>
                            </h3>
                            <p class="text-gray-600 text-sm mb-3">
                                {{ Str::limit(strip_tags($relatedPost->excerpt ?: $relatedPost->content), 100) }}</p>
                            <a href="{{ route('blog.detail', $relatedPost->slug) }}"
                                class="text-purple-600 text-sm font-semibold hover:text-purple-800 flex items-center">
                                Đọc tiếp <i class="fas fa-arrow-right ml-2 text-xs"></i>
                            </a>
                        </div>
                    </article>
                @endforeach
            </div>
        </section>

        <!-- Newsletter Section -->
        <div class="bg-purple-50 rounded-lg p-8 text-center mt-12">
            <h3 class="text-2xl font-bold text-gray-800 mb-4">Đăng ký nhận tin mới</h3>
            <p class="text-gray-600 mb-6">Nhận thông báo khi có bài viết mới và các tips công nghệ hữu ích</p>
            <form class="max-w-md mx-auto flex gap-2">
                <input type="email" placeholder="Email của bạn"
                    class="flex-1 px-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-purple-500">
                <button type="submit" class="bg-purple-600 text-white px-6 py-2 rounded-lg hover:bg-purple-700 transition">
                    Đăng ký
                </button>
            </form>
        </div>
    </div>

    <style>
        .prose {
            line-height: 1.8;
        }

        .prose h2 {
            font-size: 1.5em;
            font-weight: bold;
            margin-top: 1.5em;
            margin-bottom: 0.5em;
            color: #1f2937;
        }

        .prose h3 {
            font-size: 1.25em;
            font-weight: bold;
            margin-top: 1.25em;
            margin-bottom: 0.5em;
            color: #374151;
        }

        .prose p {
            margin-bottom: 1em;
        }

        .prose ul,
        .prose ol {
            margin-bottom: 1em;
            padding-left: 1.5em;
        }

        .prose li {
            margin-bottom: 0.5em;
        }

        .prose img {
            max-width: 100%;
            height: auto;
            border-radius: 8px;
            margin: 1em 0;
        }
    </style>
@endsection