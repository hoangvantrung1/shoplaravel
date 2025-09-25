@extends('layouts.client')
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" />

@section('title', $product->name)
<style>
    .quantity-control {
        display: flex;
        align-items: center;
        border: 2px solid #e5e7eb;
        border-radius: 8px;
        overflow: hidden;
        width: fit-content;
    }

    .quantity-btn {
        background: #f9fafb;
        border: none;
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.2s ease;
        font-size: 18px;
        font-weight: 300;
        color: #4b5563;
    }

    .quantity-btn:hover {
        background: #f3f4f6;
        color: #7c3aed;
    }

    .quantity-btn:active {
        background: #e5e7eb;
    }

    .quantity-input {
        width: 60px;
        height: 40px;
        border: none;
        text-align: center;
        font-size: 16px;
        font-weight: 600;
        color: #111827;
        background: white;
        outline: none;
    }

    /* Ẩn mũi tên trên các trình duyệt */
    .quantity-input::-webkit-outer-spin-button,
    .quantity-input::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }

    .quantity-input[type=number] {
        -moz-appearance: textfield;
    }
</style>
@section('content')
    <div class="container mx-auto px-4 py-8 max-w-7xl space-y-12">
        <nav class="flex px-5 py-3 text-gray-700 border border-gray-200 rounded-lg bg-gray-50" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="/" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-primary-600">
                        <i class="fa-solid fa-house mr-2"></i>
                        Trang chủ
                    </a>
                </li>
                <li>
                    <div class="flex items-center" <i class="fa-solid fa-chevron-right text-gray-400"></i>
                        <a href="/products"
                            class="ml-1 text-sm font-medium text-gray-700 hover:text-primary-600 md:ml-2">Sản
                            phẩm</a>
                    </div>
                </li>
                <li aria-current="page">
                    <div class="flex items-center">
                        <i class="fa-solid fa-chevron-right text-gray-400"></i>
                        <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2">{{ $product->name }}</span>
                    </div>
                </li>
            </ol>
        </nav>
        {{-- Product Top --}}
        <div class="bg-gray-50 rounded-lg shadow-lg p-8 flex flex-col lg:flex-row gap-8">

            {{-- Left: Images --}}
            <div class="lg:w-1/2 space-y-4">
                <img src="{{ asset($product->image) }}" alt="{{ $product->name }}"
                    class="w-full h-auto max-h-[500px] object-contain rounded-lg shadow-md transition-transform duration-300 hover:scale-105">

                {{-- Small gallery --}}
                @if(isset($product->gallery) && count($product->gallery))
                    <div class="grid grid-cols-4 gap-2 mt-2">
                        @foreach($product->gallery as $img)
                            <img src="{{ asset($img) }}"
                                class="w-full h-20 object-cover rounded cursor-pointer hover:ring-2 hover:ring-purple-600 transition-all">
                        @endforeach
                    </div>
                @endif

            </div>

            {{-- Right: Details --}}
            <div class="lg:w-1/2 flex flex-col justify-between space-y-6">
                <div>
                    <h1 class="text-4xl font-extrabold text-gray-900">{{ $product->name }}</h1>
                    <div class="mt-2 flex flex-col sm:flex-row sm:items-center sm:space-x-6">

                        {{-- Rating --}}
                        <div class="flex items-center space-x-2">
                            <div class="star-rating flex text-yellow-400">
                                @for($i = 1; $i <= 5; $i++)
                                    <i
                                        class="{{ $i <= floor($averageRating ?? 0) ? 'fas fa-star' : ($i - ($averageRating ?? 0) < 1 && $i - ($averageRating ?? 0) > 0 ? 'fas fa-star-half-alt' : 'far fa-star') }}"></i>
                                @endfor
                            </div>
                            <span class="text-gray-600 text-sm">({{ $reviewsCount ?? 0 }} đánh giá)</span>
                        </div>

                        {{-- Stock --}}
                        <div class="flex items-center space-x-2 mt-2 sm:mt-0">
                            @if($product->stock > 10)
                                <i class="fas fa-check-circle text-1xl text-green-600"></i>
                                <span class="text-1lg font-semibold text-green-600">Còn hàng</span>
                                <span class="text-gray-600">({{ $product->stock }} sản phẩm)</span>
                            @elseif($product->stock > 0)
                                <i class="fas fa-exclamation-triangle text-1xl text-yellow-600"></i>
                                <span class="text-1lg font-semibold text-yellow-600">Sắp hết hàng</span>
                                <span class="text-gray-600">(Chỉ còn {{ $product->stock }} sản phẩm)</span>
                            @else
                                <i class="fas fa-times-circle text-1xl text-red-600"></i>
                                <span class="text-1lg font-semibold text-red-600">Tạm hết hàng</span>
                                <span class="text-gray-600">(Liên hệ để đặt trước)</span>
                            @endif
                        </div>

                    </div>

                    <div class="mt-1">
                        <span class="text-3xl font-bold text-primary-600">{{ number_format($product->price, 0, ',', '.') }}
                            đ</span>
                        @if($product->original_price > $product->price)
                            <span
                                class="ml-2 text-lg text-gray-500 line-through">{{ number_format($product->original_price, 0, ',', '.') }}
                                đ</span>
                            <span class="ml-2 bg-red-100 text-red-800 text-sm font-semibold px-2 py-1 rounded">
                                -{{ number_format(($product->original_price - $product->price) / $product->original_price * 100, 0) }}%
                            </span>
                        @endif
                    </div>
                    <div class="mt-2">
                        <ul class="space-y-2">
                            @if($product->slug)
                                <li class="flex items-center">
                                    <span class="text-gray-600 font-medium w-32">Mã sản phẩm:</span>
                                    <span class="text-gray-900">{{ $product->slug }}</span>
                                </li>
                            @endif
                            <li class="flex items-center">
                                <span class="text-gray-600 font-medium w-32">Danh mục:</span>
                                <a href="{{ $product->category->categories_id }}"
                                    class="text-primary-600 hover:text-primary-700 hover:underline">{{ $product->category->name ?? 'N/A' }}</a>
                            </li>
                            <li class="flex items-center">
                                <span class="text-gray-600 font-medium w-32">Thương hiệu:</span>
                                <span class="text-gray-900">{{ $product->brand?->name ?? 'No brand' }}</span>
                            </li>

                        </ul>
                    </div>
                    <div class="mt-6">
                        <h2 class="text-lg font-semibold text-gray-800 border-b border-gray-200 pb-2 mb-2">Mô tả sản phẩm
                        </h2>
                        <p class="text-gray-700 leading-relaxed">{{ $product->description ?? 'Không có mô tả.' }}</p>
                    </div>

                </div>

                {{-- Add to Cart --}}
                <form action="{{ route('cart.add', $product->id) }}" method="POST" class="flex items-center gap-4">
                    @csrf
                    <div class="quantity-control">
                        <button type="button" onclick="decreaseQuantity()" class="quantity-btn">
                            <span class="font-thin">−</span>
                        </button>

                        <input type="number" name="quantity" id="form-quantity" value="1" min="1"
                            max="{{ $product->stock }}" class="quantity-input">

                        <button type="button" onclick="increaseQuantity()" class="quantity-btn">
                            <span class="font-thin">+</span>
                        </button>
                    </div>

                    <button type="submit"
                        class="bg-purple-600 hover:bg-purple-700 text-white font-bold py-3 px-8 rounded-lg transition-colors flex items-center gap-2">
                        <i class="fas fa-shopping-cart"></i>
                        Thêm vào giỏ hàng
                    </button>
                </form>
            </div>
        </div>

        {{-- Tabs: Mô tả / Thông số / Đánh giá --}}
        <div class="bg-white rounded-xl shadow-md overflow-hidden">
            <div class="border-b border-gray-200">
                <nav class="flex -mb-px">
                    <button onclick="switchTab('description')"
                        class="tab-button py-4 px-6 text-center font-medium text-sm border-b-2 border-transparent hover:text-primary-600 hover:border-primary-600 transition active"
                        data-tab="description">
                        <i class="fa-solid fa-file-lines mr-2"></i>Mô tả sản phẩm
                    </button>
                    <button onclick="switchTab('specs')"
                        class="tab-button py-4 px-6 text-center font-medium text-sm border-b-2 border-transparent hover:text-primary-600 hover:border-primary-600 transition"
                        data-tab="specs">
                        <i class="fa-solid fa-list mr-2"></i>Thông số kỹ thuật
                    </button>
                    <button onclick="switchTab('reviews')"
                        class="tab-button py-4 px-6 text-center font-medium text-sm border-b-2 border-transparent hover:text-primary-600 hover:border-primary-600 transition"
                        data-tab="reviews">
                        <i class="fa-solid fa-star mr-2"></i>Đánh giá ({{ $reviewsCount ?? 0 }})
                    </button>
                </nav>
            </div>

            <div class="p-6">
                <div id="description" class="tab-content active">
                    <div class="prose max-w-none">
                        {!! $product->full_description ?? '
                                                                                                                                                        <p>Sản phẩm này được thiết kế với chất lượng cao, bền bỉ và sang trọng. Thích hợp cho mọi nhu cầu sử dụng hàng ngày và chuyên nghiệp.</p>
                                                                                                                                                        <h3>Đặc điểm nổi bật:</h3>
                                                                                                                                                        <ul>
                                                                                                                                                            <li>Chất liệu: Cao cấp, bền bỉ với thời gian</li>
                                                                                                                                                            <li>Màu sắc: Nhiều lựa chọn phong phú</li>
                                                                                                                                                            <li>Thiết kế: Hiện đại, tinh tế</li>
                                                                                                                                                            <li>Thời gian bảo hành: 12 tháng</li>
                                                                                                                                                            <li>Xuất xứ: Việt Nam</li>
                                                                                                                                                        </ul>
                                                                                                                                                        <h3>Hướng dẫn sử dụng:</h3>
                                                                                                                                                        <p>Vệ sinh thường xuyên bằng khăn mềm, tránh tiếp xúc với hóa chất mạnh. Bảo quản nơi khô ráo, thoáng mát.</p>' !!}
                    </div>
                </div>

                <div id="specs" class="tab-content">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <tbody class="bg-white divide-y divide-gray-200">
                                {!! $product->specs ?? '
                                                                                                                                                                <tr>
                                                                                                                                                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 w-1/3">Kích thước</td>
                                                                                                                                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">25 x 15 x 10 cm</td>
                                                                                                                                                                </tr>
                                                                                                                                                                <tr>
                                                                                                                                                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">Trọng lượng</td>
                                                                                                                                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">500g</td>
                                                                                                                                                                </tr>
                                                                                                                                                                <tr>
                                                                                                                                                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">Chất liệu</td>
                                                                                                                                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">Nhựa ABS cao cấp + Kim loại</td>
                                                                                                                                                                </tr>
                                                                                                                                                                <tr>
                                                                                                                                                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">Màu sắc</td>
                                                                                                                                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">Đen, Trắng, Xám</td>
                                                                                                                                                                </tr>
                                                                                                                                                                <tr>
                                                                                                                                                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">Xuất xứ</td>
                                                                                                                                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">Việt Nam</td>
                                                                                                                                                                </tr>' !!}
                            </tbody>
                        </table>
                    </div>
                </div>

                <div id="reviews" class="tab-content">
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                        <div class="lg:col-span-2">
                            <h3 class="text-xl font-semibold mb-6">Đánh giá từ khách hàng</h3>

                            <div class="space-y-6">
                                @forelse(($product->reviews ?? []) as $review)
                                    <div class="border-b border-gray-200 pb-6">
                                        <div class="flex items-center mb-2">
                                            <div class="star-rating flex mr-2">
                                                @for($i = 1; $i <= 5; $i++)
                                                    @if($i <= ($review->rating ?? 0))
                                                        <i class="fas fa-star text-yellow-400"></i>
                                                    @else
                                                        <i class="far fa-star text-gray-300"></i>
                                                    @endif
                                                @endfor
                                            </div>
                                            <span class="font-semibold">{{ $review->user->name ?? 'Khách' }}</span>
                                            <span
                                                class="text-gray-500 text-sm ml-4">{{ $review->created_at->format('d/m/Y') }}</span>
                                        </div>
                                        <p class="text-gray-700">{{ $review->comment }}</p>
                                    </div>
                                @empty
                                    <p class="text-gray-600">Chưa có đánh giá nào.</p>
                                @endforelse
                            </div>
                        </div>

                        <div class="bg-gray-50 p-6 rounded-lg">
                            <div class="text-center">
                                {{-- Điểm trung bình --}}
                                <div class="text-5xl font-extrabold text-yellow-500">
                                    {{ number_format($averageRating, 1) }}
                                    <span class="text-2xl text-gray-600">/5</span>
                                </div>

                                {{-- Hiển thị sao --}}
                                <div class="flex justify-center my-3 text-2xl text-yellow-400">
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

                                {{-- Tổng số đánh giá --}}
                                <p class="text-gray-600">{{ $reviewsCount }} đánh giá từ khách hàng</p>
                            </div>
                            @auth
                                <form method="POST" action="{{ route('product.reviews.store', $product->id) }}"
                                    class="space-y-4">
                                    @csrf
                                    <div class="mb-4">
                                        <label class="block text-sm font-medium mb-1">Số sao</label>
                                        <div id="star-rating" class="flex space-x-1 text-2xl cursor-pointer">
                                            @for ($i = 1; $i <= 5; $i++)
                                                <span data-value="{{ $i }}" class="star text-gray-400">★</span>
                                            @endfor
                                        </div>
                                        <!-- input ẩn để gửi rating -->
                                        <input type="hidden" name="rating" id="rating-value" value="0">
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium mb-1">Đánh giá</label>
                                        <textarea name="comment" rows="4" class="w-full border rounded px-3 py-2"
                                            placeholder="Chia sẻ trải nghiệm của bạn..."></textarea>
                                    </div>
                                    <button type="submit"
                                        class="flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                                        <span class="material-symbols-outlined mr-1">send</span>
                                        Gửi đánh giá
                                    </button>

                                </form>
                            @else
                                <p class="text-sm text-gray-600">Vui lòng <a href="{{ route('login') }}"
                                        class="text-primary-600 underline">đăng nhập</a> để viết đánh giá.</p>
                            @endauth
                        </div>
                    </div>
                </div>
            </div>
        </div>


        {{-- Related Products --}}
        @if(isset($relatedProducts) && count($relatedProducts))
            <section class="space-y-6">
                <h2 class="text-2xl font-bold text-gray-800 mb-4">Sản phẩm liên quan</h2>
                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-8">
                    @foreach($relatedProducts as $item)
                        <div
                            class="bg-gray-50 rounded-lg shadow-md hover:shadow-lg overflow-hidden transition transform hover:-translate-y-1">
                            <a href="{{ route('product.show', $item->id) }}">
                                <div class="relative w-full h-48 overflow-hidden">
                                    <img src="{{ asset($item->image) }}" alt="{{ $item->name }}"
                                        class="w-full h-full object-cover transition-transform duration-300 hover:scale-105">
                                    @if($item->is_hot)
                                        <span
                                            class="absolute top-2 left-2 bg-red-500 text-white text-xs font-bold px-2 py-1 rounded">Hot</span>
                                    @endif
                                </div>
                                <div
                                    class="p-4 flex flex-col justify-between h-40 bg-white rounded-lg shadow hover:shadow-md transition">
                                    <h3 class="text-gray-800 font-semibold text-sm sm:text-base truncate">
                                        {{ $item->name }}
                                    </h3>
                                    <p class="text-purple-600 font-bold text-base mt-2">
                                        {{ number_format($item->price, 0, ',', '.') }} đ
                                    </p>
                                    <div class="flex items-center mt-2">
                                        <div class="flex text-yellow-400 text-sm">
                                            @for($i = 1; $i <= 5; $i++)
                                                @if($i <= round($product->reviews_avg_rating ?? 0))
                                                    <i class="fas fa-star"></i>
                                                @else
                                                    <i class="far fa-star"></i>
                                                @endif
                                            @endfor
                                        </div>
                                        <span class="text-gray-500 text-xs ml-2">
                                            ({{ $item->reviews_count ?? 0 }})
                                        </span>
                                    </div>
                                    <button
                                        class="mt-3 w-full bg-purple-600 hover:bg-purple-700 text-white font-semibold py-2 rounded-lg transition">
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
    <script>
        // Change main image when clicking on thumbnails
        function changeImage(src, element) {
            document.getElementById('main-image').src = src;

            // Remove active class from all thumbnails
            document.querySelectorAll('.gallery-thumb').forEach(thumb => {
                thumb.classList.remove('active');
                thumb.classList.remove('border-primary-600');
                thumb.classList.add('border-transparent');
            });

            // Add active class to clicked thumbnail
            if (element) {
                element.classList.add('active', 'border-primary-600');
                element.classList.remove('border-transparent');
            } else {
                // This is for the first thumbnail (main image)
                document.querySelector('.gallery-thumb').classList.add('active', 'border-primary-600');
                document.querySelector('.gallery-thumb').classList.remove('border-transparent');
            }
        }

        // Switch tabs
        function switchTab(tabId) {
            // Hide all tab contents
            document.querySelectorAll('.tab-content').forEach(tab => {
                tab.classList.remove('active');
            });

            // Show selected tab content
            document.getElementById(tabId).classList.add('active');

            // Update active tab button
            document.querySelectorAll('.tab-button').forEach(button => {
                button.classList.remove('active', 'border-primary-600', 'text-primary-600');
            });

            document.querySelector(`[data-tab="${tabId}"]`).classList.add('active', 'border-primary-600', 'text-primary-600');
        }

        // Quantity control
        function increaseQuantity() {
            const quantityInput = document.getElementById('form-quantity');
            let quantity = parseInt(quantityInput.value);
            const maxStock = parseInt(quantityInput.getAttribute('max'));

            if (quantity < maxStock) {
                quantityInput.value = quantity + 1;
            } else {
                // Hiển thị thông báo nếu vượt quá stock
                showStockMessage('Số lượng tối đa là ' + maxStock);
            }
        }

        function decreaseQuantity() {
            const quantityInput = document.getElementById('form-quantity');
            let quantity = parseInt(quantityInput.value);

            if (quantity > 1) {
                quantityInput.value = quantity - 1;
            }
        }

        // Validate input khi người dùng nhập trực tiếp
        document.getElementById('form-quantity').addEventListener('change', function () {
            let quantity = parseInt(this.value);
            const maxStock = parseInt(this.getAttribute('max'));
            const minStock = parseInt(this.getAttribute('min'));

            if (isNaN(quantity) || quantity < minStock) {
                this.value = minStock;
            } else if (quantity > maxStock) {
                this.value = maxStock;
                showStockMessage('Số lượng tối đa là ' + maxStock);
            }
        });

        function showStockMessage(message) {
            const toast = document.createElement('div');
            toast.className = 'fixed top-4 right-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded shadow-lg z-50';
            toast.innerHTML = `
                    <div class="flex items-center">
                        <i class="fas fa-exclamation-triangle mr-2"></i>
                        <span>${message}</span>
                    </div>
                `;

            document.body.appendChild(toast);

            setTimeout(() => {
                toast.remove();
            }, 3000);
        }
    </script>
@endsection