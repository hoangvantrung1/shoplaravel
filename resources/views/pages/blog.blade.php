@extends('layouts.client')

@section('title', 'Blog & Tin tức')

@section('content')
    <!-- Header Section với decorative elements -->
    <section class="relative bg-gradient-to-br from-purple-600 via-purple-700 to-purple-800 text-white py-16 md:py-20 overflow-hidden">
        <!-- Decorative background elements -->
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <div class="absolute top-0 -right-20 w-96 h-96 bg-purple-500/20 rounded-full blur-3xl animate-pulse-slow"></div>
            <div class="absolute bottom-0 -left-20 w-96 h-96 bg-purple-400/20 rounded-full blur-3xl animate-pulse-slow" style="animation-delay: 1s;"></div>
            <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-64 h-64 bg-white/5 rounded-full blur-2xl"></div>
        </div>

        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 z-10">
            <div class="text-center animate-fadeUp">
                <!-- Icon với glow effect -->
                <div class="inline-flex items-center justify-center w-20 h-20 bg-white/20 backdrop-blur-md rounded-2xl mb-6 shadow-lg border border-white/30 relative group">
                    <i class="fas fa-newspaper text-3xl"></i>
                    <div class="absolute inset-0 bg-white/10 rounded-2xl opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                </div>
                
                <!-- Title với gradient -->
                <h1 class="text-5xl md:text-6xl lg:text-7xl font-bold mb-6 bg-gradient-to-r from-white via-purple-100 to-white bg-clip-text text-transparent animate-gradient">
                    Blog & Tin tức
                </h1>
                
                <!-- Description -->
                <p class="text-xl md:text-2xl text-purple-100 max-w-3xl mx-auto leading-relaxed mb-8">
                    Cập nhật những tin tức mới nhất về công nghệ, đánh giá sản phẩm và mẹo sử dụng hữu ích
                </p>

                <!-- Decorative line -->
                <div class="flex items-center justify-center gap-4 mb-8">
                    <div class="h-px w-16 bg-gradient-to-r from-transparent to-white/50"></div>
                    <div class="w-2 h-2 bg-white rounded-full"></div>
                    <div class="h-px w-16 bg-gradient-to-l from-transparent to-white/50"></div>
                </div>
            </div>
        </div>
    </section>

    <!-- Breadcrumb -->
    <nav class="bg-white border-b border-gray-200 py-3">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <ol class="flex items-center space-x-2 text-sm">
                <li>
                    <a href="{{ route('home') }}" class="text-gray-500 hover:text-purple-600 transition">
                        <i class="fas fa-home mr-1"></i>Trang chủ
                    </a>
                </li>
                <li class="text-gray-400">
                    <i class="fas fa-chevron-right text-xs"></i>
                </li>
                <li class="text-gray-800 font-medium">Blog</li>
            </ol>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 md:py-16">
        @if($posts->count() > 0)
            <!-- Featured Post (First post nổi bật - chỉ hiển thị nếu có nhiều hơn 1 post) -->
            @if($posts->count() > 1 && $posts->first())
                @php $featuredPost = $posts->first(); @endphp
                <article class="mb-16 bg-gradient-to-br from-white to-purple-50/30 rounded-2xl shadow-xl overflow-hidden border border-purple-100/50 group featured-post hover:shadow-2xl transition-all duration-500">
                    <div class="grid md:grid-cols-2 gap-0">
                        <!-- Image -->
                        <a href="{{ route('blog.detail', $featuredPost->slug) }}" class="block relative overflow-hidden h-64 md:h-auto">
                            <div class="relative w-full h-full aspect-[16/9] md:aspect-auto overflow-hidden bg-gradient-to-br from-purple-100 to-purple-200">
                                <img src="{{ $featuredPost->featured_image_url }}" 
                                     alt="{{ $featuredPost->title }}"
                                     class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700"
                                     onerror="this.onerror=null; this.src='https://via.placeholder.com/1200x675/9ca3af/ffffff?text=No+Image'">
                                <!-- Gradient overlay -->
                                <div class="absolute inset-0 bg-gradient-to-t from-black/40 via-black/10 to-transparent"></div>
                                <!-- Shine effect -->
                                <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/10 to-transparent -translate-x-full group-hover:translate-x-full transition-transform duration-1000"></div>
                                <!-- Featured badge -->
                                <div class="absolute top-4 left-4 z-10">
                                    <span class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-purple-600 to-purple-700 text-white rounded-full text-sm font-bold shadow-lg animate-pulse-slow">
                                        <i class="fas fa-star mr-2 text-yellow-300"></i>
                                        Nổi bật
                                    </span>
                                </div>
                            </div>
                        </a>

                        <!-- Content -->
                        <div class="p-8 md:p-10 flex flex-col justify-center bg-gradient-to-br from-white to-purple-50/20">
                            <div class="mb-4">
                                <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-semibold bg-gradient-to-r from-purple-100 to-purple-50 text-purple-700 border border-purple-200/50 shadow-sm">
                                    <i class="fas fa-tag mr-1.5"></i>
                                    Công nghệ
                                </span>
                            </div>
                            <h2 class="text-3xl md:text-4xl font-bold mb-4 text-gray-800 group-hover:text-purple-600 transition-colors duration-300 line-clamp-3">
                                <a href="{{ route('blog.detail', $featuredPost->slug) }}" class="hover:underline decoration-2 underline-offset-2">{{ $featuredPost->title }}</a>
                            </h2>
                            <p class="text-gray-600 mb-6 leading-relaxed line-clamp-3 text-base">
                                {{ Str::limit(strip_tags($featuredPost->excerpt ?: $featuredPost->content), 200) }}
                            </p>
                            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                                <div class="flex items-center space-x-4 text-sm text-gray-500">
                                    <span class="flex items-center hover:text-purple-600 transition">
                                        <i class="far fa-clock mr-1.5"></i>
                                        {{ $featuredPost->published_at ? $featuredPost->published_at->format('d/m/Y') : '' }}
                                    </span>
                                    <span class="flex items-center hover:text-purple-600 transition">
                                        <i class="far fa-eye mr-1.5"></i>
                                        {{ $featuredPost->view_count ?? 0 }} lượt xem
                                    </span>
                                </div>
                                <a href="{{ route('blog.detail', $featuredPost->slug) }}" 
                                   class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-purple-600 to-purple-700 text-white rounded-xl font-semibold hover:from-purple-700 hover:to-purple-800 shadow-lg hover:shadow-xl transition-all group/btn transform hover:scale-105">
                                    Đọc ngay
                                    <i class="fas fa-arrow-right ml-2 group-hover/btn:translate-x-1 transition-transform"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </article>
            @endif

            <!-- Blog Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 md:gap-8 mb-12">
                @foreach ($posts->skip($posts->count() > 1 ? 1 : 0) as $index => $post)
                    <article class="bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-500 overflow-hidden group border border-gray-100 blog-card" style="animation-delay: {{ $index * 0.1 }}s;">
                        <!-- Image với effects đẹp hơn -->
                        <a href="{{ route('blog.detail', $post->slug) }}" class="block relative overflow-hidden">
                            <div class="relative w-full aspect-[16/9] overflow-hidden bg-gradient-to-br from-purple-100 via-gray-100 to-purple-50">
                                <img src="{{ $post->featured_image_url }}" 
                                     alt="{{ $post->title }}"
                                     class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700"
                                     onerror="this.onerror=null; this.src='https://via.placeholder.com/800x450/9ca3af/ffffff?text=No+Image'">
                                <!-- Gradient overlay -->
                                <div class="absolute inset-0 bg-gradient-to-t from-black/30 via-black/5 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                                <!-- Shine effect -->
                                <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/20 to-transparent -translate-x-full group-hover:translate-x-full transition-transform duration-1000"></div>
                                <!-- Corner accent -->
                                <div class="absolute top-0 right-0 w-20 h-20 bg-gradient-to-br from-purple-600/20 to-transparent rounded-bl-full opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                            </div>
                        </a>

                        <!-- Content -->
                        <div class="p-6 relative">
                            <!-- Decorative dot -->
                            <div class="absolute top-6 right-6 w-2 h-2 bg-purple-400 rounded-full opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                            
                            <!-- Category Badge -->
                            <div class="mb-4">
                                <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-semibold bg-gradient-to-r from-purple-100 to-purple-50 text-purple-700 border border-purple-200/50 shadow-sm">
                                    <i class="fas fa-tag mr-1.5 text-xs"></i>
                                    Công nghệ
                                </span>
                            </div>

                            <!-- Title -->
                            <h2 class="font-bold text-xl mb-3 text-gray-800 line-clamp-2 group-hover:text-purple-600 transition-colors duration-300">
                                <a href="{{ route('blog.detail', $post->slug) }}" class="hover:underline decoration-2 underline-offset-2">{{ $post->title }}</a>
                            </h2>

                            <!-- Excerpt -->
                            <p class="text-gray-600 text-sm line-clamp-3 mb-5 leading-relaxed">
                                {{ Str::limit(strip_tags($post->excerpt ?: $post->content), 120) }}
                            </p>

                            <!-- Meta Info -->
                            <div class="flex items-center justify-between pt-5 border-t border-gray-100">
                                <div class="flex items-center space-x-4 text-xs text-gray-500">
                                    <span class="flex items-center hover:text-purple-600 transition">
                                        <i class="far fa-clock mr-1.5"></i>
                                        {{ $post->published_at ? $post->published_at->format('d/m/Y') : '' }}
                                    </span>
                                    <span class="flex items-center hover:text-purple-600 transition">
                                        <i class="far fa-eye mr-1.5"></i>
                                        {{ $post->view_count ?? 0 }}
                                    </span>
                                </div>
                                <a href="{{ route('blog.detail', $post->slug) }}" 
                                   class="text-purple-600 hover:text-purple-800 font-bold text-sm inline-flex items-center transition group/link relative">
                                    <span class="relative z-10">Đọc tiếp</span>
                                    <i class="fas fa-arrow-right ml-2 text-xs group-hover/link:translate-x-1 transition-transform relative z-10"></i>
                                    <span class="absolute inset-0 bg-purple-50 rounded-lg opacity-0 group-hover/link:opacity-100 transition-opacity duration-300 -z-0"></span>
                                </a>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="flex justify-center mt-8">
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-2">
                    {{ $posts->links() }}
                </div>
            </div>
        @else
            <!-- Empty State -->
            <div class="text-center py-16">
                <div class="inline-flex items-center justify-center w-20 h-20 bg-gray-100 rounded-full mb-4">
                    <i class="fas fa-newspaper text-3xl text-gray-400"></i>
                </div>
                <h3 class="text-2xl font-bold text-gray-800 mb-2">Chưa có bài viết nào</h3>
                <p class="text-gray-600">Các bài viết sẽ được hiển thị ở đây khi có nội dung mới.</p>
            </div>
        @endif
    </div>

<style>
    /* Animations */
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes gradient {
        0%, 100% {
            background-position: 0% 50%;
        }
        50% {
            background-position: 100% 50%;
        }
    }

    @keyframes pulse-slow {
        0%, 100% {
            opacity: 0.3;
        }
        50% {
            opacity: 0.6;
        }
    }

    .animate-fadeUp {
        animation: fadeInUp 0.8s ease-out forwards;
    }

    .animate-gradient {
        background-size: 200% auto;
        animation: gradient 3s ease infinite;
    }

    .animate-pulse-slow {
        animation: pulse-slow 4s ease-in-out infinite;
    }

    /* Blog card animations */
    .blog-card {
        opacity: 0;
        animation: fadeInUp 0.6s ease-out forwards;
        transform: translateY(20px);
    }

    .blog-card:hover {
        transform: translateY(-8px);
    }

    .featured-post {
        opacity: 0;
        animation: fadeInUp 0.8s ease-out forwards;
    }

    /* Fallback cho aspect-ratio nếu Tailwind không hỗ trợ */
    .aspect-\[16\/9\] {
        aspect-ratio: 16 / 9;
    }
    
    @supports not (aspect-ratio: 16 / 9) {
        .aspect-\[16\/9\] {
            position: relative;
            padding-bottom: 56.25%; /* 16:9 ratio */
        }
        
        .aspect-\[16\/9\] > img {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
    }

    /* Custom pagination styles */
    .pagination {
        display: flex;
        list-style: none;
        padding: 0;
        margin: 0;
        gap: 0.5rem;
    }

    .pagination li {
        display: inline-block;
    }

    .pagination a,
    .pagination span {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 2.5rem;
        height: 2.5rem;
        padding: 0 0.75rem;
        border-radius: 0.5rem;
        font-size: 0.875rem;
        font-weight: 500;
        transition: all 0.2s;
    }

    .pagination a {
        color: #6b7280;
        background: white;
        border: 1px solid #e5e7eb;
    }

    .pagination a:hover {
        color: #9333ea;
        background: #faf5ff;
        border-color: #9333ea;
    }

    .pagination .active span {
        color: white;
        background: linear-gradient(to right, #9333ea, #a855f7);
        border: 1px solid #9333ea;
    }

    .pagination .disabled span {
        color: #d1d5db;
        background: #f9fafb;
        border: 1px solid #e5e7eb;
        cursor: not-allowed;
    }
</style>
@endsection
