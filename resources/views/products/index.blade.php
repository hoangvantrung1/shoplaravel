@extends('layouts.app')

@section('content')
<!-- Slider demo -->
<div class="relative w-full h-64 mb-8 rounded-lg overflow-hidden">
    <video autoplay loop muted class="absolute w-full h-full object-cover">
        <source src="{{ asset('videos/banner.mp4') }}" type="video/mp4">
        Trình duyệt của bạn không hỗ trợ video.
    </video>
</div>

<h1 class="text-3xl font-bold mb-6 text-gray-800">Sản phẩm nổi bật</h1>

<div class="grid sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8">
    @foreach($products as $product)
    <div class="bg-white rounded-lg shadow hover:shadow-lg transition duration-300 overflow-hidden flex flex-col">
        <a href="{{ route('product.show',$product->slug) }}" class="block relative">
            <div class="w-full aspect-w-1 aspect-h-1 overflow-hidden">
                <img src="{{ asset($product->image) }}" class="w-full h-full object-cover" alt="{{ $product->name }}">
            </div>
            @if($product->is_hot)
            <span class="absolute top-2 left-2 bg-red-500 text-white px-2 py-1 text-xs rounded">Hot</span>
            @endif
        </a>
        <div class="p-4 flex flex-col flex-1">
            <h2 class="text-lg font-semibold mb-2 hover:text-green-600 transition">{{ $product->name }}</h2>
            <p class="text-gray-700 text-xl font-bold mb-3">{{ number_format($product->price) }} đ</p>
            <form action="{{ route('cart.add',$product->id) }}" method="POST" class="mt-auto">
                @csrf
                <button class="w-full bg-green-500 hover:bg-green-600 text-white py-2 rounded transition">Thêm vào giỏ</button>
            </form>
        </div>
    </div>
    @endforeach
</div>

<div class="mt-8">
    {{ $products->links() }}
</div>

@endsection
