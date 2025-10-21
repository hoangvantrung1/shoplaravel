@extends('layouts.client')

@section('title', 'Blog & Tin tức - Mẹo công nghệ, review sản phẩm')

@section('meta')
    <meta name="description" content="Blog công nghệ: mẹo sử dụng, review sản phẩm, tin tức mới nhất. Cập nhật mỗi ngày tại ShopLaravel.">
    <meta property="og:title" content="Blog & Tin tức - ShopLaravel">
    <meta property="og:description" content="Mẹo công nghệ, review sản phẩm, cập nhật xu hướng mới nhất.">
    <meta property="og:type" content="website">
@endsection

@section('content')
    {{-- Section tiêu đề --}}
    <section class="bg-gradient-to-r from-purple-50 to-blue-50 rounded-2xl shadow-sm p-6 md:p-10 mb-10 mt-10 text-center md:text-left"
             data-aos="fade-down">
        <div class="max-w-4xl mx-auto">
            <h1 class="text-4xl font-extrabold text-gray-800 mb-2">Blog & Tin tức</h1>
            <p class="text-gray-600 text-lg">Cập nhật xu hướng và mẹo công nghệ mới nhất mỗi ngày</p>
        </div>
    </section>

    {{-- Danh sách bài viết --}}
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse ($posts as $post)
                <article class="bg-white rounded-xl shadow-md overflow-hidden transform transition duration-500 hover:-translate-y-2 hover:shadow-2xl"
                         data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}">
                    <a href="{{ route('blog.detail', $post->slug) }}" class="block group">
                        <div class="relative h-52 overflow-hidden">
                            <img src="{{ $post->featured_image ?: '/images/default-blog.jpg' }}"
                                 alt="{{ $post->title }}"
                                 class="w-full h-full object-cover group-hover:scale-110 transition duration-500"
                                 loading="lazy" decoding="async">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/30 to-transparent"></div>
                        </div>
                    </a>
                    <div class="p-5">
                        <div class="text-xs text-gray-500 mb-2 flex items-center gap-3">
                            <span><i class="far fa-clock mr-1"></i>{{ optional($post->published_at)->format('d/m/Y') }}</span>
                            <span>•</span>
                            <span><i class="far fa-eye mr-1"></i>{{ (int) ($post->view_count ?? 0) }} lượt xem</span>
                        </div>

                        <h2 class="text-lg font-semibold text-gray-900 mb-2 line-clamp-2">
                            <a href="{{ route('blog.detail', $post->slug) }}" class="hover:text-purple-600 transition">
                                {{ $post->title }}
                            </a>
                        </h2>

                        <p class="text-gray-600 text-sm line-clamp-3">
                            {{ Str::limit(strip_tags($post->excerpt ?: $post->content), 130) }}
                        </p>

                        <a href="{{ route('blog.detail', $post->slug) }}"
                           class="inline-flex items-center mt-4 text-purple-600 font-medium hover:text-purple-800 transition">
                            Đọc tiếp <i class="fas fa-arrow-right ml-2 text-xs"></i>
                        </a>
                    </div>
                </article>
            @empty
                <div class="col-span-3 text-center text-gray-500 py-10" data-aos="fade-up">
                    Chưa có bài viết nào.
                </div>
            @endforelse
        </div>

        {{-- Pagination --}}
        <div class="mt-12 text-center" data-aos="fade-up" data-aos-delay="300">
            {{ $posts->links() }}
        </div>
    </section>

    {{-- Script hiệu ứng AOS --}}
    @push('scripts')
        <link href="https://unpkg.com/aos@2.3.4/dist/aos.css" rel="stylesheet">
        <script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>
        <script>
            const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
            AOS.init({
                duration: prefersReducedMotion ? 0 : 800,
                once: true,
                offset: prefersReducedMotion ? 0 : 100,
                disable: prefersReducedMotion
            });

            // Back to top button
            (function(){
                const btn = document.createElement('button');
                btn.id = 'backToTop';
                btn.setAttribute('aria-label', 'Lên đầu trang');
                btn.className = 'fixed bottom-6 right-6 z-50 bg-purple-600 text-white w-10 h-10 rounded-full shadow-lg hover:bg-purple-700 transition hidden md:flex items-center justify-center';
                btn.innerHTML = '<i class="fas fa-arrow-up"></i>';
                document.body.appendChild(btn);

                const toggle = () => {
                    if (window.scrollY > 400) {
                        btn.classList.remove('hidden');
                    } else {
                        btn.classList.add('hidden');
                    }
                };
                window.addEventListener('scroll', toggle, { passive: true });
                toggle();

                btn.addEventListener('click', () => {
                    window.scrollTo({ top: 0, behavior: prefersReducedMotion ? 'auto' : 'smooth' });
                });
            })();
        </script>
    @endpush
@endsection
