@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8 max-w-7xl">
    <div class="bg-white p-8 rounded-lg shadow-lg flex flex-col lg:flex-row gap-8">
        {{-- Product Image --}}
        <div class="lg:w-1/2 flex items-center justify-center">
            <img src="{{ asset($product->image) }}" class="w-full h-auto max-h-[500px] object-contain rounded-lg" alt="{{ $product->name }}">
        </div>

        {{-- Product Details --}}
        <div class="lg:w-1/2 flex flex-col justify-center">
            <h1 class="text-4xl font-extrabold text-gray-900 mb-2">{{ $product->name }}</h1>
            <p class="text-3xl font-bold text-green-600 mb-6">{{ number_format($product->price) }} đ</p>

            <div class="mb-6 space-y-4">
                <h2 class="text-lg font-semibold text-gray-800 border-b pb-2">Mô tả sản phẩm</h2>
                <p class="text-gray-700 leading-relaxed">{{ $product->description ?? 'Không có mô tả cho sản phẩm này.' }}</p>
            </div>

            {{-- Additional fields and add to cart form --}}
            <ul class="mb-6 space-y-2 text-gray-700">
                @if($product->sku)
                <li class="flex items-center space-x-2">
                    <span class="font-semibold text-gray-900">SKU:</span>
                    <span>{{ $product->sku }}</span>
                </li>
                @endif
                @if(isset($product->stock))
                <li class="flex items-center space-x-2">
                    <span class="font-semibold text-gray-900">Số lượng còn:</span>
                    <span class="{{ $product->stock > 0 ? 'text-green-500' : 'text-red-500' }}">{{ $product->stock }}</span>
                </li>
                @endif
                @if($product->category)
                <li class="flex items-center space-x-2">
                    <span class="font-semibold text-gray-900">Loại sản phẩm:</span>
                    <a href="#" class="text-blue-600 hover:underline">{{ $product->category->name }}</a>
                </li>
                @endif
                @if($product->status)
                <li class="flex items-center space-x-2">
                    <span class="font-semibold text-gray-900">Trạng thái:</span>
                    <span class="bg-gray-200 text-gray-800 text-xs font-medium px-2.5 py-0.5 rounded-full">{{ $product->status }}</span>
                </li>
                @endif
            </ul>

            {{-- Add to cart button --}}
            <form action="{{ route('cart.add', $product->id) }}" method="POST">
                @csrf
                <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-6 rounded-full transition-colors duration-300 transform">
                    Thêm vào giỏ hàng
                </button>
            </form>
        </div>
    </div>
</div>
@endsection