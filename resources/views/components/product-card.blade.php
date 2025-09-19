<div class="bg-white rounded-lg shadow-md hover:shadow-xl overflow-hidden transition-shadow duration-300">
    <a href="{{ route('product.show', $product->slug) }}">
        <div class="w-full h-40 bg-gray-100 overflow-hidden">
            <img src="{{ asset($product->image) }}" alt="{{ $product->name }}"
                class="w-full h-full object-cover transition-transform duration-300 hover:scale-105">
        </div>
        <div class="p-4">
            <h3 class="text-gray-800 font-semibold mb-1 line-clamp-1">{{ $product->name }}</h3>
            <p class="text-purple-600 font-bold mb-2">{{ number_format($product->price, 0, ',', '.') }}₫</p>
            @isset($product->description)
                <p class="text-gray-500 text-sm line-clamp-2">{{ $product->description }}</p>
            @endisset
        </div>
    </a>
</div>
