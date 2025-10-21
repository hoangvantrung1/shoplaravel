{{-- Skeleton Loading Component --}}
<div class="animate-pulse">
    @if($type === 'blog')
        {{-- Blog Card Skeleton --}}
        <div class="bg-white rounded-xl shadow-md overflow-hidden">
            <div class="h-52 bg-gray-200"></div>
            <div class="p-5">
                <div class="flex items-center gap-3 mb-2">
                    <div class="h-4 bg-gray-200 rounded w-16"></div>
                    <div class="h-4 bg-gray-200 rounded w-20"></div>
                </div>
                <div class="h-6 bg-gray-200 rounded mb-2"></div>
                <div class="h-4 bg-gray-200 rounded w-3/4 mb-4"></div>
                <div class="h-4 bg-gray-200 rounded w-1/2"></div>
            </div>
        </div>
    @elseif($type === 'product')
        {{-- Product Card Skeleton --}}
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="h-48 bg-gray-200"></div>
            <div class="p-4">
                <div class="h-5 bg-gray-200 rounded mb-2"></div>
                <div class="h-4 bg-gray-200 rounded w-2/3 mb-2"></div>
                <div class="h-6 bg-gray-200 rounded w-1/2"></div>
            </div>
        </div>
    @elseif($type === 'list')
        {{-- List Item Skeleton --}}
        <div class="flex items-center space-x-4 p-4">
            <div class="w-12 h-12 bg-gray-200 rounded"></div>
            <div class="flex-1">
                <div class="h-4 bg-gray-200 rounded w-3/4 mb-2"></div>
                <div class="h-3 bg-gray-200 rounded w-1/2"></div>
            </div>
        </div>
    @else
        {{-- Default Card Skeleton --}}
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="h-6 bg-gray-200 rounded mb-4"></div>
            <div class="space-y-2">
                <div class="h-4 bg-gray-200 rounded"></div>
                <div class="h-4 bg-gray-200 rounded w-5/6"></div>
                <div class="h-4 bg-gray-200 rounded w-4/6"></div>
            </div>
        </div>
    @endif
</div>
