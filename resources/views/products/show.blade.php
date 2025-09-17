@extends('layouts.client')

@section('title', $product->name)

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
                    <div class="mt-1">
                        <div class="star-rating flex mr-2">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star-half-alt"></i>
                        </div>
                        <span class="text-gray-600 text-sm">(42 đánh giá)</span>
                        <span class="mx-2 text-gray-300">|</span>
                        <span class="text-green-600 text-sm font-medium"><i class="fa-solid fa-check mr-1"></i>Còn
                            hàng</span>
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
                            @if($product->sku)
                            <li class="flex items-center">
                                <span class="text-gray-600 font-medium w-32">Mã sản phẩm:</span>
                                <span class="text-gray-900">{{ $product->sku }}</span>
                            </li>
                            @endif
                            <li class="flex items-center">
                                <span class="text-gray-600 font-medium w-32">Danh mục:</span>
                                <a href="{{ $product->category->categories_id }}" class="text-primary-600 hover:text-primary-700 hover:underline">{{ $product->category->name ?? 'N/A' }}</a>
                            </li>
                            <li class="flex items-center">
                                <span class="text-gray-600 font-medium w-32">Thương hiệu:</span>
                                <span class="text-gray-900">{{ $product->brand ?? 'No brand' }}</span>
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
                <form action="{{ route('cart.add', $product->id) }}" method="POST">
                    @csrf
                    <button type="submit"
                        class="w-full bg-purple-600 hover:bg-purple-700 text-white font-bold py-3 px-6 rounded-full transition-colors">
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
                        <i class="fa-solid fa-star mr-2"></i>Đánh giá (42)
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
                                <div class="border-b border-gray-200 pb-6">
                                    <div class="flex items-center mb-2">
                                        <div class="star-rating flex mr-2">
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                        </div>
                                        <span class="font-semibold">Nguyễn Văn A</span>
                                        <span class="text-gray-500 text-sm ml-4">20/10/2023</span>
                                    </div>
                                    <p class="text-gray-700">Sản phẩm tuyệt vời, chất lượng đúng như mô tả. Giao hàng nhanh
                                        chóng và đóng gói cẩn thận. Tôi rất hài lòng với trải nghiệm mua sắm này!</p>
                                </div>

                                <div class="border-b border-gray-200 pb-6">
                                    <div class="flex items-center mb-2">
                                        <div class="star-rating flex mr-2">
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="far fa-star"></i>
                                        </div>
                                        <span class="font-semibold">Trần Thị B</span>
                                        <span class="text-gray-500 text-sm ml-4">18/10/2023</span>
                                    </div>
                                    <p class="text-gray-700">Chất lượng sản phẩm tốt, giá cả hợp lý. Tuy nhiên màu sắc hơi
                                        khác so với hình ảnh trên website một chút. Nhưng nhìn chung là hài lòng.</p>
                                </div>

                                <button
                                    class="bg-primary-50 hover:bg-primary-100 text-primary-700 font-medium py-2 px-4 rounded-lg transition">
                                    Xem thêm đánh giá
                                </button>
                            </div>
                        </div>

                        <div class="bg-gray-50 p-6 rounded-lg">
                            <h3 class="text-xl font-semibold mb-4">Đánh giá sản phẩm</h3>
                            <div class="text-center mb-4">
                                <div class="text-5xl font-bold text-primary-600">4.8<span
                                        class="text-3xl text-gray-600">/5</span></div>
                                <div class="star-rating flex justify-center my-2 text-xl">
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star-half-alt"></i>
                                </div>
                                <p class="text-gray-600">Dựa trên 42 đánh giá</p>
                            </div>

                            <button
                                class="w-full bg-primary-600 hover:bg-primary-700 text-white font-medium py-3 px-4 rounded-lg transition flex items-center justify-center">
                                <i class="fa-solid fa-pen mr-2"></i>Viết đánh giá
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        {{-- Related Products --}}
        @if(isset($relatedProducts) && count($relatedProducts))
            <section class="space-y-6">
                <h2 class="text-2xl font-bold text-gray-800 mb-4">Sản phẩm liên quan</h2>
                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-6">
                    @foreach($relatedProducts as $item)
                        <div
                            class="bg-gray-50 rounded-lg shadow-md hover:shadow-lg overflow-hidden transition transform hover:-translate-y-1">
                            <a href="{{ route('product.show', $item->slug) }}">
                                <div class="relative w-full h-48 overflow-hidden">
                                    <img src="{{ asset($item->image) }}" alt="{{ $item->name }}"
                                        class="w-full h-full object-cover transition-transform duration-300 hover:scale-105">
                                    @if($item->is_hot)
                                        <span
                                            class="absolute top-2 left-2 bg-red-500 text-white text-xs font-bold px-2 py-1 rounded">Hot</span>
                                    @endif
                                </div>
                                <div class="p-4 flex flex-col justify-between h-28">
                                    <h3 class="text-gray-800 font-semibold text-sm sm:text-base truncate">{{ $item->name }}</h3>
                                    <p class="text-purple-600 font-bold text-sm sm:text-base">
                                        {{ number_format($item->price, 0, ',', '.') }} đ
                                    </p>
                                    <button
                                        class="mt-2 w-full bg-purple-600 hover:bg-purple-700 text-white font-semibold py-1 rounded transition">Mua
                                        ngay</button>
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
            const quantityInput = document.getElementById('quantity');
            const formQuantityInput = document.getElementById('form-quantity');
            let quantity = parseInt(quantityInput.value);
            quantityInput.value = quantity + 1;
            formQuantityInput.value = quantity + 1;
        }

        function decreaseQuantity() {
            const quantityInput = document.getElementById('quantity');
            const formQuantityInput = document.getElementById('form-quantity');
            let quantity = parseInt(quantityInput.value);
            if (quantity > 1) {
                quantityInput.value = quantity - 1;
                formQuantityInput.value = quantity - 1;
            }
        }

        // Initialize page
        document.addEventListener('DOMContentLoaded', function () {
            // Initialize first thumbnail as active
            changeImage('{{ asset($product->image) }}');
        });
    </script>
@endsection