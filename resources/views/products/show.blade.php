@extends('layouts.client')

@section('title', $product->name)

@section('content')
<div class="min-h-screen bg-gray-50 pt-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        
        {{-- Breadcrumb --}}
        <nav class="flex px-6 py-4 text-gray-700 border border-gray-200 rounded-2xl bg-white shadow-sm mb-8" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="/" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-purple-600 transition-colors">
                        <i class="fa-solid fa-house mr-2"></i>
                        Trang chủ
                    </a>
                </li>
                <li>
                    <div class="flex items-center">
                        <i class="fa-solid fa-chevron-right text-gray-400 mx-2"></i>
                        <a href="/products" class="text-sm font-medium text-gray-700 hover:text-purple-600 transition-colors">Sản phẩm</a>
                    </div>
                </li>
                <li aria-current="page">
                    <div class="flex items-center">
                        <i class="fa-solid fa-chevron-right text-gray-400 mx-2"></i>
                        <span class="text-sm font-medium text-purple-600">{{ Str::limit($product->name, 50) }}</span>
                    </div>
                </li>
            </ol>
        </nav>

        {{-- Product Top Section --}}
        <div class="bg-white rounded-2xl shadow-pro p-8 flex flex-col lg:flex-row gap-8 mb-8">
            {{-- Left: Images --}}
            <div class="lg:w-1/2 space-y-4">
                <div class="relative bg-gray-50 rounded-2xl p-4">
                    <img id="main-image" src="{{ asset($product->image) }}" alt="{{ $product->name }}"
                        class="w-full h-auto max-h-[500px] object-contain rounded-xl transition-transform duration-300">
                    
                    {{-- Badges --}}
                    @if($product->discount > 0)
                        <span class="absolute top-4 left-4 bg-gradient-to-r from-red-500 to-pink-600 text-white text-sm font-bold px-4 py-2 rounded-full shadow-lg">
                            -{{ $product->discount }}%
                        </span>
                    @endif
                    @if($product->is_hot)
                        <span class="absolute top-4 right-4 bg-gradient-to-r from-orange-500 to-red-500 text-white text-sm font-bold px-4 py-2 rounded-full shadow-lg">
                            HOT
                        </span>
                    @endif
                </div>

                {{-- Gallery --}}
                @if(isset($product->gallery) && count($product->gallery) > 0)
                    <div class="grid grid-cols-4 gap-3">
                        @foreach($product->gallery as $index => $img)
                            <img src="{{ asset($img) }}" 
                                 onclick="changeImage('{{ asset($img) }}', this)"
                                 class="w-full h-20 object-cover rounded-xl cursor-pointer border-2 border-transparent hover:border-purple-500 transition-all duration-200 {{ $index === 0 ? 'border-purple-500' : '' }}">
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- Right: Product Details --}}
            <div class="lg:w-1/2 flex flex-col justify-between space-y-6">
                <div class="space-y-4">
                    <h1 class="text-3xl lg:text-4xl font-bold text-gray-900 leading-tight">{{ $product->name }}</h1>
                    
                    {{-- Rating and Stock --}}
                    <div class="flex flex-col sm:flex-row sm:items-center sm:space-x-6 space-y-2 sm:space-y-0">
                        <div class="flex items-center space-x-2">
                            <div class="flex text-amber-400 text-sm">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="{{ $i <= floor($averageRating ?? 0) ? 'fas fa-star' : ($i - ($averageRating ?? 0) < 1 && $i - ($averageRating ?? 0) > 0 ? 'fas fa-star-half-alt' : 'far fa-star') }}"></i>
                                @endfor
                            </div>
                            <span class="text-gray-600 text-sm">({{ $reviewsCount ?? 0 }} đánh giá)</span>
                        </div>

                        <div class="flex items-center space-x-2">
                            @if($product->stock > 10)
                                <i class="fas fa-check-circle text-green-600"></i>
                                <span class="text-green-600 font-semibold">Còn hàng</span>
                                <span class="text-gray-600 text-sm">({{ $product->stock }} sản phẩm)</span>
                            @elseif($product->stock > 0)
                                <i class="fas fa-exclamation-triangle text-yellow-600"></i>
                                <span class="text-yellow-600 font-semibold">Sắp hết hàng</span>
                                <span class="text-gray-600 text-sm">(Chỉ còn {{ $product->stock }} sản phẩm)</span>
                            @else
                                <i class="fas fa-times-circle text-red-600"></i>
                                <span class="text-red-600 font-semibold">Tạm hết hàng</span>
                            @endif
                        </div>
                    </div>

                    {{-- Price --}}
                    <div class="flex items-center space-x-4">
                        <span class="text-3xl font-bold text-purple-600">
                            {{ number_format($product->price, 0, ',', '.') }}₫
                        </span>
                        @if($product->original_price > $product->price)
                            <span class="text-xl text-gray-500 line-through">
                                {{ number_format($product->original_price, 0, ',', '.') }}₫
                            </span>
                            <span class="bg-red-100 text-red-800 text-sm font-semibold px-3 py-1 rounded-full">
                                -{{ number_format(($product->original_price - $product->price) / $product->original_price * 100, 0) }}%
                            </span>
                        @endif
                    </div>

                    {{-- Product Info --}}
                    <div class="space-y-3">
                        @if($product->slug)
                            <div class="flex items-center">
                                <span class="text-gray-600 font-medium w-32">Mã SP:</span>
                                <span class="text-gray-900 font-mono bg-gray-100 px-2 py-1 rounded">{{ $product->slug }}</span>
                            </div>
                        @endif
                        @if($product->category)
                            <div class="flex items-center">
                                <span class="text-gray-600 font-medium w-32">Danh mục:</span>
                                <a href="{{ route('products.index', ['category' => $product->category->id]) }}" 
                                   class="text-purple-600 hover:text-purple-700 hover:underline font-medium">
                                    {{ $product->category->name }}
                                </a>
                            </div>
                        @endif
                        @if($product->brand)
                            <div class="flex items-center">
                                <span class="text-gray-600 font-medium w-32">Thương hiệu:</span>
                                <span class="text-gray-900 font-medium">{{ $product->brand->name }}</span>
                            </div>
                        @endif
                    </div>

                    {{-- Short Description --}}
                    <div class="mt-4">
                        <p class="text-gray-700 leading-relaxed text-lg">{{ $product->description ?? 'Sản phẩm chất lượng cao, thiết kế hiện đại.' }}</p>
                    </div>
                </div>

                {{-- Add to Cart Section --}}
                <div class="border-t border-gray-200 pt-6">
                    <form action="{{ route('cart.add', $product->id) }}" method="POST" class="flex flex-col sm:flex-row items-start sm:items-center gap-4">
                        @csrf
                        
                        {{-- Quantity Control --}}
                        <div class="flex items-center space-x-4">
                            <span class="text-gray-700 font-medium">Số lượng:</span>
                            <div class="quantity-control">
                                <button type="button" onclick="decreaseQuantity()" class="quantity-btn">
                                    <i class="fas fa-minus text-sm"></i>
                                </button>
                                <input type="number" name="quantity" id="form-quantity" value="1" min="1" 
                                       max="{{ $product->stock }}" class="quantity-input">
                                <button type="button" onclick="increaseQuantity()" class="quantity-btn">
                                    <i class="fas fa-plus text-sm"></i>
                                </button>
                            </div>
                        </div>

                        {{-- Add to Cart Button --}}
                        <button type="submit" 
                                class="flex-1 bg-gradient-to-r from-purple-600 to-blue-500 hover:from-purple-700 hover:to-blue-600 text-white font-bold py-4 px-8 rounded-xl transition-all duration-300 transform hover:scale-105 shadow-lg shadow-purple-500/25 flex items-center justify-center gap-3 disabled:opacity-50 disabled:cursor-not-allowed"
                                {{ $product->stock <= 0 ? 'disabled' : '' }}>
                            <i class="fas fa-shopping-cart"></i>
                            {{ $product->stock > 0 ? 'Thêm vào giỏ hàng' : 'Hết hàng' }}
                        </button>
                    </form>
                </div>
            </div>
        </div>

        {{-- Tabs Section --}}
        <div class="bg-white rounded-2xl shadow-pro overflow-hidden mb-8">
            <div class="border-b border-gray-200">
                <nav class="flex -mb-px">
                    <button onclick="switchTab('description')"
                        class="tab-button py-5 px-8 text-center font-semibold text-base border-b-2 border-purple-600 text-purple-600 transition-all"
                        data-tab="description">
                        <i class="fas fa-file-lines mr-3"></i>Mô tả sản phẩm
                    </button>
                    <button onclick="switchTab('specs')"
                        class="tab-button py-5 px-8 text-center font-semibold text-base border-b-2 border-transparent text-gray-600 hover:text-purple-600 hover:border-purple-600 transition-all"
                        data-tab="specs">
                        <i class="fas fa-list mr-3"></i>Thông số kỹ thuật
                    </button>
                    <button onclick="switchTab('reviews')"
                        class="tab-button py-5 px-8 text-center font-semibold text-base border-b-2 border-transparent text-gray-600 hover:text-purple-600 hover:border-purple-600 transition-all"
                        data-tab="reviews">
                        <i class="fas fa-star mr-3"></i>Đánh giá ({{ $reviewsCount ?? 0 }})
                    </button>
                </nav>
            </div>

            <div class="p-8">
                {{-- Description Tab --}}
                <div id="description" class="tab-content active">
                    <div class="prose max-w-none text-gray-700">
                        {!! $product->full_description ?? '
                        <div class="space-y-4">
                            <h3 class="text-2xl font-bold text-gray-900">Thông tin chi tiết</h3>
                            <p class="text-lg">Sản phẩm được thiết kế với chất lượng cao, bền bỉ và sang trọng. Thích hợp cho mọi nhu cầu sử dụng hàng ngày và chuyên nghiệp.</p>
                            
                            <h4 class="text-xl font-semibold text-gray-900">Đặc điểm nổi bật:</h4>
                            <ul class="list-disc list-inside space-y-2 text-lg">
                                <li>Chất liệu: Cao cấp, bền bỉ với thời gian</li>
                                <li>Màu sắc: Nhiều lựa chọn phong phú</li>
                                <li>Thiết kế: Hiện đại, tinh tế</li>
                                <li>Thời gian bảo hành: 12 tháng</li>
                                <li>Xuất xứ: Việt Nam</li>
                            </ul>
                            
                            <h4 class="text-xl font-semibold text-gray-900">Hướng dẫn sử dụng:</h4>
                            <p class="text-lg">Vệ sinh thường xuyên bằng khăn mềm, tránh tiếp xúc với hóa chất mạnh. Bảo quản nơi khô ráo, thoáng mát.</p>
                        </div>' !!}
                    </div>
                </div>

                {{-- Specifications Tab --}}
                <div id="specs" class="tab-content">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <tbody class="divide-y divide-gray-200">
                                {!! $product->specs ?? '
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900 w-1/3">Kích thước</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">25 x 15 x 10 cm</td>
                                </tr>
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">Trọng lượng</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">500g</td>
                                </tr>
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">Chất liệu</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">Nhựa ABS cao cấp + Kim loại</td>
                                </tr>
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">Màu sắc</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">Đen, Trắng, Xám</td>
                                </tr>
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">Xuất xứ</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">Việt Nam</td>
                                </tr>' !!}
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Reviews Tab --}}
                <div id="reviews" class="tab-content">
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                        {{-- Reviews List --}}
                        <div class="lg:col-span-2">
                            <h3 class="text-2xl font-bold text-gray-900 mb-6">Đánh giá từ khách hàng</h3>

                            <div class="space-y-6">
                                @forelse($product->reviews as $review)
                                    <div class="bg-gray-50 rounded-2xl p-6 hover:shadow-md transition-shadow">
                                        <div class="flex items-start justify-between mb-3">
                                            <div class="flex items-center space-x-3">
                                                <div class="w-12 h-12 bg-gradient-to-r from-purple-500 to-blue-500 rounded-full flex items-center justify-center text-white font-semibold">
                                                    {{ strtoupper(substr($review->user->name ?? 'G', 0, 1)) }}
                                                </div>
                                                <div>
                                                    <span class="font-semibold text-gray-900">{{ $review->user->name ?? 'Khách hàng' }}</span>
                                                    <div class="flex items-center space-x-1 mt-1">
                                                        <div class="flex text-amber-400 text-sm">
                                                            @for($i = 1; $i <= 5; $i++)
                                                                <i class="{{ $i <= $review->rating ? 'fas fa-star' : 'far fa-star' }}"></i>
                                                            @endfor
                                                        </div>
                                                        <span class="text-gray-500 text-sm">{{ $review->created_at->format('d/m/Y') }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <p class="text-gray-700 leading-relaxed">{{ $review->comment }}</p>
                                    </div>
                                @empty
                                    <div class="text-center py-12">
                                        <i class="fas fa-comment-slash text-4xl text-gray-400 mb-4"></i>
                                        <p class="text-gray-600 text-lg">Chưa có đánh giá nào cho sản phẩm này.</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>

                        {{-- Review Form & Stats --}}
                        <div class="space-y-6">
                            {{-- Rating Stats --}}
                            <div class="bg-gradient-to-br from-purple-50 to-blue-50 rounded-2xl p-6 text-center border border-purple-100">
                                <div class="text-5xl font-bold text-purple-600 mb-2">
                                    {{ number_format($averageRating, 1) }}
                                    <span class="text-2xl text-gray-600">/5</span>
                                </div>
                                <div class="flex justify-center mb-3 text-xl text-amber-400">
                                    @for ($i = 1; $i <= 5; $i++)
                                        @if ($i <= floor($averageRating))
                                            <i class="fas fa-star"></i>
                                        @elseif ($i - $averageRating < 1)
                                            <i class="fas fa-star-half-alt"></i>
                                        @else
                                            <i class="far fa-star"></i>
                                        @endif
                                    @endfor
                                </div>
                                <p class="text-gray-600 font-medium">{{ $reviewsCount }} đánh giá</p>
                            </div>

                            {{-- Review Form --}}
                            @auth
                                <div class="bg-white border border-gray-200 rounded-2xl p-6">
                                    <h4 class="text-lg font-semibold text-gray-900 mb-4">Viết đánh giá của bạn</h4>
                                    <form method="POST" action="{{ route('product.reviews.store', $product->id) }}" class="space-y-4">
                                        @csrf
                                        
                                        {{-- Star Rating --}}
                                        <div>
                                            <label class="block text-sm font-semibold text-gray-700 mb-2">Đánh giá của bạn</label>
                                            <div id="star-rating" class="flex space-x-1 text-2xl cursor-pointer">
                                                @for ($i = 1; $i <= 5; $i++)
                                                    <span data-value="{{ $i }}" class="star text-gray-300 hover:text-amber-400 transition-colors">★</span>
                                                @endfor
                                            </div>
                                            <input type="hidden" name="rating" id="rating-value" value="5" required>
                                        </div>

                                        {{-- Comment --}}
                                        <div>
                                            <label class="block text-sm font-semibold text-gray-700 mb-2">Nhận xét</label>
                                            <textarea name="comment" rows="4" 
                                                      class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-purple-500 focus:border-transparent transition"
                                                      placeholder="Chia sẻ trải nghiệm của bạn về sản phẩm..." required></textarea>
                                        </div>

                                        <button type="submit"
                                                class="w-full bg-gradient-to-r from-purple-600 to-blue-500 hover:from-purple-700 hover:to-blue-600 text-white font-semibold py-3 px-6 rounded-xl transition-all duration-300 transform hover:scale-105 flex items-center justify-center gap-2">
                                            <i class="fas fa-paper-plane"></i>
                                            Gửi đánh giá
                                        </button>
                                    </form>
                                </div>
                            @else
                                <div class="bg-yellow-50 border border-yellow-200 rounded-2xl p-6 text-center">
                                    <i class="fas fa-user-circle text-3xl text-yellow-500 mb-3"></i>
                                    <p class="text-yellow-800 mb-3">Vui lòng đăng nhập để viết đánh giá</p>
                                    <a href="{{ route('login') }}" 
                                       class="inline-flex items-center px-4 py-2 bg-yellow-500 hover:bg-yellow-600 text-white font-semibold rounded-lg transition-colors">
                                        <i class="fas fa-sign-in-alt mr-2"></i>
                                        Đăng nhập ngay
                                    </a>
                                </div>
                            @endauth
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Related Products --}}
        @if(isset($relatedProducts) && count($relatedProducts) > 0)
            <section class="mb-8">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-2xl font-bold text-gray-900">Sản phẩm liên quan</h2>
                    <a href="{{ route('products.index', ['category' => $product->category_id]) }}" 
                       class="text-purple-600 hover:text-purple-700 font-semibold flex items-center gap-2">
                        Xem tất cả <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    @foreach($relatedProducts as $item)
                        <div class="product-card bg-white rounded-2xl shadow-pro overflow-hidden group">
                            <a href="{{ route('product.show', $item->id) }}" class="block">
                                <div class="relative w-full aspect-[4/3] bg-gray-100 overflow-hidden">
                                    <img src="{{ asset($item->image) }}" alt="{{ $item->name }}"
                                        class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                                    
                                    {{-- Badges --}}
                                    @if($item->is_hot)
                                        <span class="absolute top-3 left-3 bg-gradient-to-r from-orange-500 to-red-500 text-white text-xs font-bold px-3 py-1.5 rounded-full shadow-lg">
                                            HOT
                                        </span>
                                    @endif
                                    @if($item->discount > 0)
                                        <span class="absolute top-3 right-3 bg-gradient-to-r from-red-500 to-pink-600 text-white text-xs font-bold px-3 py-1.5 rounded-full shadow-lg">
                                            -{{ $item->discount }}%
                                        </span>
                                    @endif
                                </div>
                                
                                <div class="p-4">
                                    <h3 class="font-semibold text-gray-900 mb-2 line-clamp-2 group-hover:text-purple-600 transition-colors leading-tight">
                                        {{ $item->name }}
                                    </h3>
                                    
                                    <div class="flex items-center justify-between mb-3">
                                        <div class="flex items-center space-x-2">
                                            <span class="text-lg font-bold text-purple-600">
                                                {{ number_format($item->price, 0, ',', '.') }}₫
                                            </span>
                                            @if($item->original_price > $item->price)
                                                <span class="text-sm text-gray-500 line-through">
                                                    {{ number_format($item->original_price, 0, ',', '.') }}₫
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    
                                    <button class="w-full bg-gray-100 hover:bg-purple-600 hover:text-white text-gray-700 font-semibold py-2.5 rounded-xl transition-all duration-300 group-hover:scale-105">
                                        <i class="fas fa-shopping-cart mr-2"></i>
                                        Mua ngay
                                    </button>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
            </section>
        @endif
    </div>
</div>

<style>
    .quantity-control {
        display: flex;
        align-items: center;
        border: 2px solid #e5e7eb;
        border-radius: 12px;
        overflow: hidden;
        width: fit-content;
        background: white;
    }

    .quantity-btn {
        background: #f9fafb;
        border: none;
        width: 44px;
        height: 44px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.2s ease;
        color: #6b7280;
    }

    .quantity-btn:hover {
        background: #f3f4f6;
        color: #7c3aed;
    }

    .quantity-input {
        width: 70px;
        height: 44px;
        border: none;
        text-align: center;
        font-size: 16px;
        font-weight: 600;
        color: #111827;
        background: white;
        outline: none;
    }

    .quantity-input::-webkit-outer-spin-button,
    .quantity-input::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }

    .quantity-input[type=number] {
        -moz-appearance: textfield;
    }

    .tab-content {
        display: none;
    }

    .tab-content.active {
        display: block;
        animation: fadeIn 0.5s ease-in-out;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>

<script>
    // Image Gallery
    function changeImage(src, element) {
        document.getElementById('main-image').src = src;
        document.querySelectorAll('.gallery-thumb').forEach(thumb => {
            thumb.classList.remove('border-purple-500');
        });
        element.classList.add('border-purple-500');
    }

    // Tab Switching
    function switchTab(tabId) {
        // Hide all tab contents
        document.querySelectorAll('.tab-content').forEach(tab => {
            tab.classList.remove('active');
        });

        // Show selected tab content
        document.getElementById(tabId).classList.add('active');

        // Update active tab button
        document.querySelectorAll('.tab-button').forEach(button => {
            button.classList.remove('border-purple-600', 'text-purple-600');
            button.classList.add('border-transparent', 'text-gray-600');
        });

        const activeButton = document.querySelector(`[data-tab="${tabId}"]`);
        activeButton.classList.add('border-purple-600', 'text-purple-600');
        activeButton.classList.remove('border-transparent', 'text-gray-600');
    }

    // Quantity Control
    function increaseQuantity() {
        const quantityInput = document.getElementById('form-quantity');
        let quantity = parseInt(quantityInput.value);
        const maxStock = parseInt(quantityInput.getAttribute('max'));

        if (quantity < maxStock) {
            quantityInput.value = quantity + 1;
        } else {
            showToast('Số lượng tối đa là ' + maxStock, 'warning');
        }
    }

    function decreaseQuantity() {
        const quantityInput = document.getElementById('form-quantity');
        let quantity = parseInt(quantityInput.value);

        if (quantity > 1) {
            quantityInput.value = quantity - 1;
        }
    }

    // Input validation
    document.getElementById('form-quantity').addEventListener('change', function() {
        let quantity = parseInt(this.value);
        const maxStock = parseInt(this.getAttribute('max'));
        const minStock = parseInt(this.getAttribute('min'));

        if (isNaN(quantity) || quantity < minStock) {
            this.value = minStock;
        } else if (quantity > maxStock) {
            this.value = maxStock;
            showToast('Số lượng tối đa là ' + maxStock, 'warning');
        }
    });

    // Star Rating for Review Form
    document.addEventListener('DOMContentLoaded', function() {
        const stars = document.querySelectorAll('#star-rating .star');
        const ratingInput = document.getElementById('rating-value');

        stars.forEach(star => {
            star.addEventListener('click', function() {
                const value = parseInt(this.getAttribute('data-value'));
                ratingInput.value = value;
                
                stars.forEach(s => {
                    const starValue = parseInt(s.getAttribute('data-value'));
                    if (starValue <= value) {
                        s.classList.add('text-amber-400');
                        s.classList.remove('text-gray-300');
                    } else {
                        s.classList.add('text-gray-300');
                        s.classList.remove('text-amber-400');
                    }
                });
            });

            star.addEventListener('mouseover', function() {
                const value = parseInt(this.getAttribute('data-value'));
                stars.forEach(s => {
                    const starValue = parseInt(s.getAttribute('data-value'));
                    if (starValue <= value) {
                        s.classList.add('text-amber-400');
                    } else {
                        s.classList.add('text-gray-300');
                    }
                });
            });

            star.addEventListener('mouseout', function() {
                const currentRating = parseInt(ratingInput.value);
                stars.forEach(s => {
                    const starValue = parseInt(s.getAttribute('data-value'));
                    if (starValue <= currentRating) {
                        s.classList.add('text-amber-400');
                        s.classList.remove('text-gray-300');
                    } else {
                        s.classList.add('text-gray-300');
                        s.classList.remove('text-amber-400');
                    }
                });
            });
        });

        // Initialize with 5 stars
        stars.forEach(star => {
            const starValue = parseInt(star.getAttribute('data-value'));
            if (starValue <= 5) {
                star.classList.add('text-amber-400');
                star.classList.remove('text-gray-300');
            }
        });
    });

    // Toast Notification
    function showToast(message, type = 'info') {
        const toast = document.createElement('div');
        toast.className = `fixed top-4 right-4 px-6 py-4 rounded-xl shadow-lg z-50 transform transition-all duration-300 ${
            type === 'warning' ? 'bg-orange-100 border border-orange-300 text-orange-800' : 'bg-blue-100 border border-blue-300 text-blue-800'
        }`;
        
        toast.innerHTML = `
            <div class="flex items-center space-x-3">
                <i class="fas fa-${type === 'warning' ? 'exclamation-triangle' : 'info-circle'}"></i>
                <span class="font-medium">${message}</span>
            </div>
        `;

        document.body.appendChild(toast);

        setTimeout(() => {
            toast.style.transform = 'translateX(100%)';
            setTimeout(() => toast.remove(), 300);
        }, 3000);
    }
</script>
@endsection