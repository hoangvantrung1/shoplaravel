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
            <div class="lg:w-1/2 space-y-3 sm:space-y-4 w-full">
                <div class="relative bg-gray-50 rounded-xl sm:rounded-2xl p-2 sm:p-4 w-full overflow-hidden">
                    <div class="relative w-full flex items-center justify-center min-h-[200px] sm:min-h-[300px] md:min-h-[400px] lg:min-h-[500px]">
                        <img id="main-image" src="{{ asset($product->image) }}" alt="{{ $product->name }}"
                            class="w-full h-auto max-w-full max-h-[70vh] sm:max-h-[500px] object-contain rounded-lg sm:rounded-xl transition-transform duration-300"
                            style="max-width: 100%; height: auto; display: block;">
                    </div>
                    
                    {{-- Badges --}}
                    @if($product->discount > 0)
                        <span class="absolute top-2 left-2 sm:top-4 sm:left-4 bg-gradient-to-r from-red-500 to-pink-600 text-white text-xs sm:text-sm font-bold px-2 sm:px-4 py-1 sm:py-2 rounded-full shadow-lg">
                            -{{ $product->discount }}%
                        </span>
                    @endif
                    @if($product->is_hot)
                        <span class="absolute top-2 right-2 sm:top-4 sm:right-4 bg-gradient-to-r from-orange-500 to-red-500 text-white text-xs sm:text-sm font-bold px-2 sm:px-4 py-1 sm:py-2 rounded-full shadow-lg">
                            HOT
                        </span>
                    @endif
                </div>

                {{-- Gallery --}}
                @if(isset($product->gallery) && count($product->gallery) > 0)
                    <div class="grid grid-cols-4 gap-2 sm:gap-3 w-full">
                        @foreach($product->gallery as $index => $img)
                            <div class="relative w-full aspect-square overflow-hidden rounded-lg sm:rounded-xl cursor-pointer border-2 border-transparent hover:border-purple-500 transition-all duration-200 {{ $index === 0 ? 'border-purple-500' : '' }}"
                                 onclick="changeImage('{{ asset($img) }}', this)">
                                <img src="{{ asset($img) }}" 
                                     alt="Gallery image {{ $index + 1 }}"
                                     class="w-full h-full object-contain bg-gray-50">
                            </div>
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
                                            @auth
                                                @if(auth()->id() === $review->user_id)
                                                    <div class="relative">
                                                        <button type="button"
                                                                class="p-2 rounded-full hover:bg-gray-100 text-gray-500 transition-colors"
                                                                onclick="toggleReviewMenu({{ $review->id }})">
                                                            <i class="fas fa-ellipsis-v"></i>
                                                        </button>
                                                        <div id="review-menu-{{ $review->id }}" class="hidden absolute right-0 mt-2 w-44 bg-white border border-gray-200 rounded-xl shadow-lg z-10">
                                                            <button type="button"
                                                                    class="w-full px-4 py-2 text-left text-sm text-gray-700 hover:bg-gray-100 rounded-t-xl"
                                                                    onclick="toggleEditForm({{ $review->id }})">
                                                                <i class="fas fa-edit mr-2 text-purple-500"></i>Chỉnh sửa
                                                            </button>
                                                            <form action="{{ route('product.reviews.destroy', $review) }}" method="POST"
                                                                  onsubmit="return confirm('Bạn chắc chắn muốn xóa đánh giá này?')">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit"
                                                                        class="w-full px-4 py-2 text-left text-sm text-red-600 hover:bg-red-50 rounded-b-xl">
                                                                    <i class="fas fa-trash-alt mr-2"></i>Xóa
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                @endif
                                            @endauth
                                        </div>
                                        
                                        {{-- Comment --}}
                                        @if($review->comment)
                                            <p class="text-gray-700 leading-relaxed mb-4">{{ $review->comment }}</p>
                                        @endif

                                        {{-- Images Gallery --}}
                                        @if($review->images && count($review->images) > 0)
                                            <div class="mb-4">
                                                <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                                                    @foreach($review->images_urls as $imageUrl)
                                                        <div class="relative group cursor-pointer">
                                                            <img src="{{ $imageUrl }}" alt="Review image" 
                                                                 class="w-full h-32 object-cover rounded-lg border-2 border-gray-200 hover:border-purple-500 transition-all duration-300"
                                                                 onclick="openImageModal('{{ $imageUrl }}')">
                                                            <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-20 rounded-lg transition-all duration-300 flex items-center justify-center">
                                                                <i class="fas fa-search-plus text-white opacity-0 group-hover:opacity-100 transition-opacity"></i>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endif

                                        {{-- Video --}}
                                        @if($review->video_url)
                                            <div class="mb-4">
                                                <video controls class="w-full rounded-lg border-2 border-gray-200" preload="metadata">
                                                    <source src="{{ $review->video_url }}" type="video/mp4">
                                                    Trình duyệt của bạn không hỗ trợ video.
                                                </video>
                                            </div>
                                        @endif

                                        @auth
                                            @if(auth()->id() === $review->user_id)
                                                <div id="edit-form-{{ $review->id }}" class="hidden mt-5">
                                                    <div class="rounded-2xl border border-purple-100 bg-gradient-to-br from-white to-purple-50/40 shadow-inner p-5">
                                                        <div class="flex items-center justify-between mb-4">
                                                            <div class="flex items-center gap-3">
                                                                <div class="w-10 h-10 rounded-full bg-purple-600 text-white flex items-center justify-center">
                                                                    <i class="fas fa-pen"></i>
                                                                </div>
                                                                <div>
                                                                    <p class="text-sm font-semibold text-gray-900">Chỉnh sửa đánh giá</p>
                                                                    <p class="text-xs text-gray-500">Cập nhật lại trải nghiệm của bạn</p>
                                                                </div>
                                                            </div>
                                                            <button type="button" onclick="toggleEditForm({{ $review->id }})"
                                                                    class="text-gray-500 hover:text-gray-700">
                                                                <i class="fas fa-times"></i>
                                                            </button>
                                                        </div>
                                                        <form action="{{ route('product.reviews.update', $review) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                                                            @csrf
                                                            @method('PUT')
                                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                                                <div>
                                                                    <label class="text-xs font-semibold text-gray-600">Đánh giá (1-5 sao)</label>
                                                                    <div class="mt-2 bg-white border border-gray-200 rounded-xl px-3 py-2 flex items-center gap-2">
                                                                        <i class="fas fa-star text-amber-400"></i>
                                                                        <select name="rating" class="flex-1 bg-transparent border-none focus:ring-0 text-sm">
                                                                            @for($i = 5; $i >= 1; $i--)
                                                                                <option value="{{ $i }}" {{ $review->rating == $i ? 'selected' : '' }}>{{ $i }} sao</option>
                                                                            @endfor
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <div>
                                                                    <label class="text-xs font-semibold text-gray-600">Quản lý media</label>
                                                                    <label class="mt-2 flex items-center gap-2 text-xs text-gray-600 bg-white border border-gray-200 rounded-xl px-3 py-2 cursor-pointer hover:border-purple-300 transition">
                                                                        <input type="checkbox" name="clear_media" value="1" class="rounded text-purple-600 focus:ring-purple-500">
                                                                        Xóa toàn bộ ảnh/video hiện tại
                                                                    </label>
                                                                </div>
                                                            </div>
                                                            <div>
                                                                <label class="text-xs font-semibold text-gray-600">Nội dung</label>
                                                                <textarea name="comment" rows="3" class="mt-2 w-full border border-gray-200 rounded-2xl px-4 py-3 text-sm focus:ring-2 focus:ring-purple-500 focus:border-transparent bg-white shadow-sm">{{ $review->comment }}</textarea>
                                                            </div>
                                                            <div>
                                                                <label class="text-xs font-semibold text-gray-600">Thay thế ảnh / video (tối đa 5 ảnh + 1 video)</label>
                                                                <label class="mt-2 flex flex-col sm:flex-row items-center gap-3 w-full border-2 border-dashed border-purple-200 rounded-2xl px-4 py-4 bg-white hover:border-purple-400 transition cursor-pointer text-sm text-gray-600">
                                                                    <div class="flex items-center gap-3 text-purple-600 font-semibold">
                                                                        <i class="fas fa-cloud-upload-alt text-lg"></i>
                                                                        Chọn file mới
                                                                    </div>
                                                                    <span class="text-xs text-gray-400">Nếu không chọn file, hệ thống giữ nguyên nội dung cũ</span>
                                                                    <input type="file" name="media[]" multiple accept="image/*,video/*" class="hidden">
                                                                </label>
                                                            </div>
                                                            <div class="flex flex-wrap items-center justify-end gap-3">
                                                                <button type="button"
                                                                        class="px-4 py-2.5 text-sm font-medium text-gray-600 bg-white border border-gray-200 rounded-xl hover:border-gray-400"
                                                                        onclick="toggleEditForm({{ $review->id }})">
                                                                    Hủy
                                                                </button>
                                                                <button type="submit"
                                                                        class="px-5 py-2.5 text-sm font-semibold text-white bg-gradient-to-r from-purple-600 to-blue-500 rounded-xl hover:shadow-lg hover:translate-y-0.5 transition-all">
                                                                    Lưu thay đổi
                                                                </button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            @endif
                                        @endauth
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
                            <div class="bg-gradient-to-br from-purple-50 to-blue-50 rounded-2xl p-6 border border-purple-100">
                                {{-- Overall Rating --}}
                                <div class="text-center mb-6">
                                    <div class="text-5xl font-bold text-purple-600 mb-2">
                                        {{ number_format($averageRating ?? 0, 1) }}
                                        <span class="text-2xl text-gray-600">/5</span>
                                    </div>
                                    <div class="flex justify-center mb-3 text-xl text-amber-400">
                                        @for ($i = 1; $i <= 5; $i++)
                                            @if ($i <= floor($averageRating ?? 0))
                                                <i class="fas fa-star"></i>
                                            @elseif ($i - ($averageRating ?? 0) < 1)
                                                <i class="fas fa-star-half-alt"></i>
                                            @else
                                                <i class="far fa-star"></i>
                                            @endif
                                        @endfor
                                    </div>
                                    <p class="text-gray-600 font-medium">{{ $reviewsCount ?? 0 }} đánh giá</p>
                                </div>

                                {{-- Rating Breakdown --}}
                                @if($reviewsCount > 0 && isset($ratingBreakdown))
                                    <div class="space-y-2 pt-4 border-t border-purple-200">
                                        @for($star = 5; $star >= 1; $star--)
                                            <div class="flex items-center gap-2">
                                                <div class="flex items-center gap-1 w-16">
                                                    <span class="text-sm font-semibold text-gray-700">{{ $star }}</span>
                                                    <i class="fas fa-star text-amber-400 text-xs"></i>
                                                </div>
                                                <div class="flex-1 bg-gray-200 rounded-full h-2 overflow-hidden">
                                                    <div class="bg-gradient-to-r from-amber-400 to-amber-500 h-full rounded-full transition-all duration-500" 
                                                         style="width: {{ $reviewsCount > 0 ? ($ratingBreakdown[$star] / $reviewsCount * 100) : 0 }}%"></div>
                                                </div>
                                                <span class="text-sm text-gray-600 w-8 text-right">{{ $ratingBreakdown[$star] }}</span>
                                            </div>
                                        @endfor
                                    </div>
                                @endif
                            </div>

                            {{-- Review Form --}}
                            @auth
                                <div class="bg-white border border-gray-200 rounded-2xl p-6">
                                    <h4 class="text-lg font-semibold text-gray-900 mb-4">Viết đánh giá của bạn</h4>
                                    <form method="POST" action="{{ route('product.reviews.store', $product->id) }}" enctype="multipart/form-data" class="space-y-4">
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
                                                      placeholder="Chia sẻ trải nghiệm của bạn về sản phẩm..."></textarea>
                                        </div>

                                        {{-- Upload Media (Images + Video) --}}
                                        <div>
                                            <label class="flex items-center gap-2 text-sm font-semibold text-gray-700 mb-2">
                                                <i class="fas fa-folder-plus text-purple-600"></i>
                                                Ảnh / Video trải nghiệm của bạn
                                            </label>

                                            <div class="w-full border-2 border-dashed border-gray-300 rounded-2xl p-5 bg-gray-50 hover:border-purple-400 transition-colors">
                                                <div class="flex flex-wrap items-center gap-4">
                                                    <button type="button"
                                                            onclick="document.getElementById('review-media').click()"
                                                            class="inline-flex items-center gap-2 px-5 py-2.5 bg-white border border-purple-200 text-purple-600 rounded-xl font-semibold shadow-sm hover:bg-purple-50 transition-colors">
                                                        <i class="fas fa-plus-circle text-lg"></i>
                                                        Tải ảnh / video
                                                    </button>
                                                    <p class="text-xs text-gray-500">
                                                        Hỗ trợ tối đa 5 ảnh (≤5MB/ảnh) và 1 video (≤20MB)
                                                    </p>
                                                </div>
                                            </div>

                                            <input type="file" name="media[]" id="review-media" multiple accept="image/*,video/*" class="hidden">

                                            <div class="mt-4 space-y-3">
                                                <div>
                                                    <p class="text-sm font-semibold text-gray-700 mb-2">Ảnh đã chọn</p>
                                                    <div id="image-preview" class="grid grid-cols-3 gap-2"></div>
                                                </div>
                                                <div>
                                                    <p class="text-sm font-semibold text-gray-700 mb-2">Video đã chọn</p>
                                                    <div id="video-preview" class="mt-2"></div>
                                                </div>
                                            </div>
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

        {{-- Related Products - Carousel như newProduct --}}
        @if(isset($relatedProducts) && count($relatedProducts) > 0)
            <section class="mb-8">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-2xl font-bold text-gray-900">Sản phẩm liên quan</h2>
                    <a href="{{ route('products.index', ['category' => $product->category_id]) }}" 
                       class="text-purple-600 hover:text-purple-700 font-semibold flex items-center gap-2">
                        Xem tất cả <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
                
                {{-- Carousel Container --}}
                <div class="relative group">
                    {{-- Navigation Buttons --}}
                    <button id="relatedPrevBtn"
                        class="absolute left-0 top-1/2 transform -translate-y-1/2 z-10 bg-white/90 backdrop-blur-sm rounded-full shadow-lg p-3 hover:bg-white hover:scale-110 transition-all duration-300 opacity-0 group-hover:opacity-100 -translate-x-2 group-hover:translate-x-0 hidden md:flex items-center justify-center">
                        <i class="fas fa-chevron-left text-purple-600 text-sm"></i>
                    </button>
                    <button id="relatedNextBtn"
                        class="absolute right-0 top-1/2 transform -translate-y-1/2 z-10 bg-white/90 backdrop-blur-sm rounded-full shadow-lg p-3 hover:bg-white hover:scale-110 transition-all duration-300 opacity-0 group-hover:opacity-100 translate-x-2 group-hover:translate-x-0 hidden md:flex items-center justify-center">
                        <i class="fas fa-chevron-right text-purple-600 text-sm"></i>
                    </button>

                    {{-- Carousel Track --}}
                    <div class="overflow-hidden py-4">
                        <div id="relatedCarouselTrack" class="flex transition-transform duration-500 ease-out gap-3 sm:gap-4 md:gap-6">
                            @foreach($relatedProducts as $item)
                                @php
                                    $hasDiscount = ($item->discount ?? 0) > 0;
                                    $finalPrice = $hasDiscount ? 
                                        $item->price - ($item->price * $item->discount / 100) : 
                                        $item->price;
                                @endphp
                                
                                <div class="flex-shrink-0 w-1/2 sm:w-80 md:w-72 px-1 sm:px-0">
                                    <div class="product-card bg-white rounded-lg sm:rounded-xl md:rounded-2xl shadow-sm hover:shadow-xl md:hover:shadow-2xl overflow-hidden flex flex-col h-full group">
                                        <a href="{{ route('product.show', $item->id) }}" class="block relative">
                                            <div class="relative w-full aspect-[4/3] bg-gray-100 overflow-hidden">
                                                <img src="{{ asset($item->image) }}" alt="{{ $item->name }}"
                                                    class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                                                
                                                {{-- Badges --}}
                                                <div class="absolute top-1.5 left-1.5 sm:top-2 sm:left-2 md:top-3 md:left-3 flex flex-col space-y-1">
                                                    @if($item->is_hot)
                                                        <span class="bg-orange-500 text-white text-[10px] sm:text-xs font-bold px-1.5 sm:px-2 py-0.5 sm:py-1 rounded-full shadow-lg">
                                                            HOT
                                                        </span>
                                                    @endif
                                                    @if($hasDiscount)
                                                        <span class="bg-red-500 text-white text-[10px] sm:text-xs font-bold px-1.5 sm:px-2 py-0.5 sm:py-1 rounded-full shadow-lg">
                                                            -{{ $item->discount }}%
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                        </a>
                                        
                                        <div class="p-2.5 sm:p-3 md:p-4 lg:p-5 flex-1 flex flex-col">
                                            {{-- Title --}}
                                            <a href="{{ route('product.show', $item->id) }}" class="block group">
                                                <h3 class="text-gray-900 font-semibold mb-1 sm:mb-2 line-clamp-2 group-hover:text-purple-600 transition-colors duration-300 text-xs sm:text-sm md:text-base leading-tight">
                                                    {{ $item->name }}
                                                </h3>
                                            </a>

                                            {{-- Price --}}
                                            <div class="flex items-center space-x-1 sm:space-x-2 mb-2 sm:mb-3 mt-auto">
                                                <span class="text-sm sm:text-base md:text-lg lg:text-xl font-bold text-purple-600">
                                                    {{ number_format($finalPrice, 0, ',', '.') }}₫
                                                </span>
                                                @if($hasDiscount && $item->original_price > $item->price)
                                                    <span class="text-gray-400 line-through text-[10px] sm:text-xs">
                                                        {{ number_format($item->original_price, 0, ',', '.') }}₫
                                                    </span>
                                                @endif
                                            </div>
                                            
                                            {{-- Action Button --}}
                                            <form action="{{ route('cart.add', $item->id) }}" method="POST" class="w-full">
                                                @csrf
                                                <button type="submit" 
                                                        class="w-full bg-gradient-to-r from-purple-600 to-blue-500 hover:from-purple-700 hover:to-blue-600 text-white py-2 sm:py-2.5 md:py-3 px-3 sm:px-4 rounded-lg sm:rounded-xl transition-all duration-300 transform hover:scale-105 shadow-md hover:shadow-lg font-semibold text-xs sm:text-sm md:text-base flex items-center justify-center gap-1.5 sm:gap-2">
                                                    <i class="fas fa-shopping-cart text-xs sm:text-sm"></i> 
                                                    <span class="hidden sm:inline">Thêm vào giỏ</span>
                                                    <span class="sm:hidden">Mua</span>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- Dots Indicator --}}
                    <div class="flex justify-center mt-4 sm:mt-6 space-x-2">
                        @for($i = 0; $i < ceil(count($relatedProducts) / 2); $i++)
                            <button
                                class="related-carousel-dot w-2 h-2 sm:w-3 sm:h-3 rounded-full bg-gray-300 hover:bg-purple-400 transition-all duration-300 {{ $i === 0 ? 'bg-gradient-to-r from-purple-600 to-blue-500 w-6 sm:w-8' : '' }}"
                                data-slide="{{ $i }}"></button>
                        @endfor
                    </div>
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

    // Media upload preview (images + video)
    let currentMediaFiles = [];

    document.addEventListener('DOMContentLoaded', function() {
        const mediaInput = document.getElementById('review-media');
        const imagePreview = document.getElementById('image-preview');
        const videoPreview = document.getElementById('video-preview');

        if (mediaInput) {
            mediaInput.addEventListener('change', function(event) {
                const files = Array.from(event.target.files);
                let imagesCount = 0;
                let hasVideo = false;
                currentMediaFiles = [];

                files.forEach(file => {
                    if (file.type.startsWith('image/')) {
                        if (imagesCount >= 5) {
                            showToast('Chỉ được chọn tối đa 5 ảnh', 'warning');
                            return;
                        }

                        if (file.size > 5 * 1024 * 1024) {
                            showToast(`Ảnh ${file.name} vượt quá 5MB`, 'warning');
                            return;
                        }

                        imagesCount++;
                        currentMediaFiles.push(file);
                    } else if (file.type.startsWith('video/')) {
                        if (hasVideo) {
                            showToast('Chỉ được chọn tối đa 1 video', 'warning');
                            return;
                        }

                        if (file.size > 20 * 1024 * 1024) {
                            showToast('Video vượt quá 20MB', 'warning');
                            return;
                        }

                        hasVideo = true;
                        currentMediaFiles.push(file);
                    } else {
                        showToast(`Định dạng file "${file.name}" không được hỗ trợ`, 'warning');
                    }
                });

                syncMediaInput(mediaInput);
                renderMediaPreviews(imagePreview, videoPreview);
            });
        }
    });

    function syncMediaInput(mediaInput) {
        const dt = new DataTransfer();
        currentMediaFiles.forEach(file => dt.items.add(file));
        mediaInput.files = dt.files;
    }

    function renderMediaPreviews(imagePreview, videoPreview) {
        imagePreview.innerHTML = '';
        videoPreview.innerHTML = '';

        currentMediaFiles.forEach((file, index) => {
            if (file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const wrapper = document.createElement('div');
                    wrapper.className = 'relative group';
                    wrapper.innerHTML = `
                        <img src="${e.target.result}" alt="Ảnh đã chọn" class="w-full h-24 object-cover rounded-lg border-2 border-gray-200">
                        <button type="button" onclick="removeMediaFile(${index})"
                            class="absolute top-1 right-1 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs hover:bg-red-600 transition-colors">
                            <i class="fas fa-times"></i>
                        </button>
                    `;
                    imagePreview.appendChild(wrapper);
                };
                reader.readAsDataURL(file);
            } else if (file.type.startsWith('video/')) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const wrapper = document.createElement('div');
                    wrapper.className = 'relative';
                    wrapper.innerHTML = `
                        <video src="${e.target.result}" controls class="w-full rounded-lg border-2 border-gray-200 max-h-64"></video>
                        <button type="button" onclick="removeMediaFile(${index})"
                            class="absolute top-2 right-2 bg-red-500 text-white rounded-full w-8 h-8 flex items-center justify-center text-sm hover:bg-red-600 transition-colors">
                            <i class="fas fa-times"></i>
                        </button>
                    `;
                    videoPreview.appendChild(wrapper);
                };
                reader.readAsDataURL(file);
            }
        });
    }

    function removeMediaFile(index) {
        currentMediaFiles.splice(index, 1);
        const mediaInput = document.getElementById('review-media');
        const imagePreview = document.getElementById('image-preview');
        const videoPreview = document.getElementById('video-preview');
        syncMediaInput(mediaInput);
        renderMediaPreviews(imagePreview, videoPreview);
    }

    // Toggle review menu & edit form
    function toggleReviewMenu(id) {
        document.querySelectorAll('[id^="review-menu-"]').forEach(menu => {
            if (menu.id !== `review-menu-${id}`) {
                menu.classList.add('hidden');
            }
        });

        const target = document.getElementById(`review-menu-${id}`);
        if (target) {
            target.classList.toggle('hidden');
        }
    }

    function toggleEditForm(id) {
        const form = document.getElementById(`edit-form-${id}`);
        const menu = document.getElementById(`review-menu-${id}`);
        if (menu) menu.classList.add('hidden');
        if (form) form.classList.toggle('hidden');
    }

    window.addEventListener('click', function(event) {
        const button = event.target.closest('[onclick^="toggleReviewMenu"]');
        if (!button) {
            document.querySelectorAll('[id^="review-menu-"]').forEach(menu => menu.classList.add('hidden'));
        }
    });

    // Image Modal for viewing full-size images
    function openImageModal(imageUrl) {
        const modal = document.createElement('div');
        modal.className = 'fixed inset-0 bg-black bg-opacity-90 z-50 flex items-center justify-center p-4';
        modal.innerHTML = `
            <div class="relative max-w-5xl max-h-full">
                <img src="${imageUrl}" alt="Full size" class="max-w-full max-h-[90vh] object-contain rounded-lg">
                <button onclick="this.closest('.fixed').remove()" 
                        class="absolute top-4 right-4 bg-white text-gray-900 rounded-full w-10 h-10 flex items-center justify-center hover:bg-gray-200 transition-colors">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        `;
        document.body.appendChild(modal);
        
        // Close on click outside
        modal.addEventListener('click', function(e) {
            if (e.target === modal) {
                modal.remove();
            }
        });
    }

    // Related Products Carousel
    document.addEventListener('DOMContentLoaded', function() {
        const relatedTrack = document.getElementById('relatedCarouselTrack');
        const relatedPrevBtn = document.getElementById('relatedPrevBtn');
        const relatedNextBtn = document.getElementById('relatedNextBtn');
        const relatedDots = document.querySelectorAll('.related-carousel-dot');
        const relatedCarouselContainer = document.querySelector('.relative.group');

        if (!relatedTrack) return;

        let currentSlide = 0;
        let autoPlayInterval;
        
        function getItemsPerSlide() {
            if (window.innerWidth < 640) return 2; // Mobile: 2 items
            if (window.innerWidth < 768) return 2; // sm: 2 items
            if (window.innerWidth < 1024) return 3; // md: 3 items
            return 4; // lg+: 4 items
        }
        
        function getGap() {
            if (window.innerWidth < 640) return 12; // Mobile: 12px
            if (window.innerWidth < 768) return 16; // sm: 16px
            return 24; // md+: 24px
        }

        const totalSlides = Math.ceil({{ count($relatedProducts ?? []) }} / getItemsPerSlide());

        function updateRelatedCarousel() {
            const itemsPerSlide = getItemsPerSlide();
            const gap = getGap();
            const itemWidth = window.innerWidth < 640 ? 
                (window.innerWidth / 2 - gap) : 
                (window.innerWidth < 768 ? 320 : 288);
            const translateX = -currentSlide * (itemWidth + gap) * itemsPerSlide;
            
            relatedTrack.style.transform = `translateX(${translateX}px)`;

            // Update dots
            relatedDots.forEach((dot, index) => {
                const isActive = index === currentSlide;
                dot.classList.toggle('bg-gradient-to-r', isActive);
                dot.classList.toggle('from-purple-600', isActive);
                dot.classList.toggle('to-blue-500', isActive);
                dot.classList.toggle('bg-gray-300', !isActive);
                dot.classList.toggle('w-6', isActive && window.innerWidth < 640);
                dot.classList.toggle('w-8', isActive && window.innerWidth >= 640);
                dot.classList.toggle('w-2', !isActive && window.innerWidth < 640);
                dot.classList.toggle('w-3', !isActive && window.innerWidth >= 640);
            });
        }

        function nextRelatedSlide() {
            currentSlide = currentSlide < totalSlides - 1 ? currentSlide + 1 : 0;
            updateRelatedCarousel();
        }

        function prevRelatedSlide() {
            currentSlide = currentSlide > 0 ? currentSlide - 1 : totalSlides - 1;
            updateRelatedCarousel();
        }

        // Auto-play functionality
        function startAutoPlay() {
            autoPlayInterval = setInterval(() => {
                nextRelatedSlide();
            }, 3000); // Chuyển slide mỗi 5 giây
        }

        function resetAutoPlay() {
            clearInterval(autoPlayInterval);
            startAutoPlay();
        }

        if (relatedPrevBtn) {
            relatedPrevBtn.addEventListener('click', () => {
                prevRelatedSlide();
                resetAutoPlay();
            });
        }

        if (relatedNextBtn) {
            relatedNextBtn.addEventListener('click', () => {
                nextRelatedSlide();
                resetAutoPlay();
            });
        }

        // Dot navigation
        relatedDots.forEach((dot, index) => {
            dot.addEventListener('click', () => {
                currentSlide = index;
                updateRelatedCarousel();
                resetAutoPlay();
            });
        });

        // Pause auto-play on hover (desktop only)
        if (relatedCarouselContainer) {
            relatedCarouselContainer.addEventListener('mouseenter', () => {
                clearInterval(autoPlayInterval);
            });

            relatedCarouselContainer.addEventListener('mouseleave', () => {
                startAutoPlay();
            });
        }

        // Handle resize
        let resizeTimeout;
        window.addEventListener('resize', () => {
            clearTimeout(resizeTimeout);
            resizeTimeout = setTimeout(() => {
                currentSlide = 0;
                updateRelatedCarousel();
                resetAutoPlay();
            }, 250);
        });

        // Touch/swipe support for mobile
        let touchStartX = 0;
        let touchEndX = 0;

        if (relatedTrack) {
            relatedTrack.addEventListener('touchstart', (e) => {
                touchStartX = e.changedTouches[0].screenX;
                clearInterval(autoPlayInterval); // Pause khi touch
            });

            relatedTrack.addEventListener('touchend', (e) => {
                touchEndX = e.changedTouches[0].screenX;
                handleSwipe();
                startAutoPlay(); // Resume sau khi swipe
            });

            function handleSwipe() {
                const swipeThreshold = 50;
                const diff = touchStartX - touchEndX;

                if (Math.abs(diff) > swipeThreshold) {
                    if (diff > 0) {
                        nextRelatedSlide();
                    } else {
                        prevRelatedSlide();
                    }
                }
            }
        }

        // Initialize
        updateRelatedCarousel();
        startAutoPlay(); // Bắt đầu auto-play
    });
</script>
@endsection