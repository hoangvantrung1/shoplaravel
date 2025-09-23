@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Pagination" class="flex items-center space-x-2">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <span class="px-3 py-1 rounded-full bg-gray-200 text-gray-400 cursor-not-allowed">&laquo;</span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" class="px-3 py-1 rounded-full bg-white border hover:bg-blue-500 hover:text-white">&laquo;</a>
        @endif

        {{-- Pagination Elements --}}
        @foreach ($elements as $element)
            @if (is_string($element))
                <span class="px-3 py-1">{{ $element }}</span>
            @endif

            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <span class="px-3 py-1 rounded-full bg-blue-600 text-white">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}" class="px-3 py-1 rounded-full bg-white border hover:bg-blue-500 hover:text-white">{{ $page }}</a>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" class="px-3 py-1 rounded-full bg-white border hover:bg-blue-500 hover:text-white">&raquo;</a>
        @else
            <span class="px-3 py-1 rounded-full bg-gray-200 text-gray-400 cursor-not-allowed">&raquo;</span>
        @endif
    </nav>
@endif
