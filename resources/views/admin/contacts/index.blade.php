@extends('layouts.admin')

@section('title', 'Quản lý Liên hệ')

@section('content')
<div class="bg-white rounded-2xl shadow-xl overflow-hidden">
    <!-- Header -->
    <div class="bg-gradient-to-r from-green-500 to-teal-600 px-8 py-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-white mb-2">Quản lý Liên hệ</h1>
                <p class="text-green-100 text-lg">Quản lý thông tin liên hệ từ khách hàng</p>
            </div>
            <div class="bg-white/20 backdrop-blur-sm rounded-xl p-3">
                <span class="material-icons text-white text-4xl">contact_mail</span>
            </div>
        </div>
    </div>

    <!-- Stats -->
    <div class="px-8 pt-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
            <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-2xl p-6 border border-blue-100 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-blue-600 text-sm font-medium">Tổng liên hệ</p>
                        <h3 class="text-3xl font-bold text-gray-800 mt-2">{{ $contacts->total() }}</h3>
                    </div>
                    <div class="bg-blue-100 p-3 rounded-xl">
                        <span class="material-icons text-blue-600">mail</span>
                    </div>
                </div>
            </div>
            
            <div class="bg-gradient-to-br from-green-50 to-emerald-50 rounded-2xl p-6 border border-green-100 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-green-600 text-sm font-medium">Liên hệ mới</p>
                        <h3 class="text-3xl font-bold text-gray-800 mt-2">
                            {{ $contacts->whereNull('read_at')->count() }}
                        </h3>
                    </div>
                    <div class="bg-green-100 p-3 rounded-xl">
                        <span class="material-icons text-green-600">mark_email_unread</span>
                    </div>
                </div>
            </div>
            
            <div class="bg-gradient-to-br from-gray-50 to-slate-50 rounded-2xl p-6 border border-gray-100 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm font-medium">Đã xử lý</p>
                        <h3 class="text-3xl font-bold text-gray-800 mt-2">
                            {{ $contacts->whereNotNull('read_at')->count() }}
                        </h3>
                    </div>
                    <div class="bg-gray-100 p-3 rounded-xl">
                        <span class="material-icons text-gray-600">mark_email_read</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Table -->
    <div class="p-8">
        @if($contacts->count() > 0)
        <div class="bg-gray-50 rounded-2xl border border-gray-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-gradient-to-r from-gray-100 to-slate-100 border-b border-gray-200">
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700 uppercase tracking-wider">
                                <div class="flex items-center">
                                    <span class="material-icons mr-2 text-gray-500 text-sm">tag</span>
                                    ID
                                </div>
                            </th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700 uppercase tracking-wider">
                                <div class="flex items-center">
                                    <span class="material-icons mr-2 text-gray-500 text-sm">person</span>
                                    Thông tin
                                </div>
                            </th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700 uppercase tracking-wider">
                                <div class="flex items-center">
                                    <span class="material-icons mr-2 text-gray-500 text-sm">subject</span>
                                    Tiêu đề
                                </div>
                            </th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700 uppercase tracking-wider">
                                <div class="flex items-center">
                                    <span class="material-icons mr-2 text-gray-500 text-sm">flag</span>
                                    Trạng thái
                                </div>
                            </th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700 uppercase tracking-wider">
                                <div class="flex items-center">
                                    <span class="material-icons mr-2 text-gray-500 text-sm">schedule</span>
                                    Thời gian
                                </div>
                            </th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700 uppercase tracking-wider">
                                <div class="flex items-center">
                                    <span class="material-icons mr-2 text-gray-500 text-sm">settings</span>
                                    Thao tác
                                </div>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($contacts as $contact)
                        <tr class="transition-all duration-200 hover:bg-white group 
                                  {{ is_null($contact->read_at) ? 'bg-blue-50/50 hover:bg-blue-50' : '' }}">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm font-mono text-gray-600">#{{ $contact->id }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex flex-col">
                                    <span class="font-semibold text-gray-900 group-hover:text-blue-600 transition-colors">
                                        {{ $contact->name }}
                                    </span>
                                    <span class="text-sm text-gray-500 mt-1">{{ $contact->email }}</span>
                                    @if($contact->phone)
                                    <span class="text-sm text-gray-400">{{ $contact->phone }}</span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="max-w-xs">
                                    <p class="text-gray-800 font-medium line-clamp-2">{{ $contact->subject }}</p>
                                    @if($contact->message)
                                    <p class="text-sm text-gray-500 mt-1 line-clamp-1">{{ Str::limit($contact->message, 60) }}</p>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                @if(is_null($contact->read_at))
                                <div class="flex items-center">
                                    <span class="flex w-3 h-3 bg-blue-500 rounded-full mr-2 animate-pulse"></span>
                                    <span class="px-3 py-1.5 text-xs font-medium rounded-full bg-blue-100 text-blue-800 border border-blue-200">
                                        MỚI
                                    </span>
                                </div>
                                @else
                                <span class="px-3 py-1.5 text-xs font-medium rounded-full bg-gray-100 text-gray-600 border border-gray-200">
                                    ĐÃ ĐỌC
                                </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex flex-col">
                                    <span class="text-sm text-gray-900">{{ $contact->created_at->format('d/m/Y') }}</span>
                                    <span class="text-xs text-gray-500">{{ $contact->created_at->format('H:i') }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center space-x-2">
                                    <a href="{{ route('admin.contacts.show', $contact) }}" 
                                       class="inline-flex items-center px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-all duration-200 shadow-sm hover:shadow-md group/btn">
                                        <span class="material-icons text-sm mr-1">visibility</span>
                                        <span class="text-sm font-medium">Xem</span>
                                    </a>
                                    <form action="{{ route('admin.contacts.destroy', $contact) }}" method="POST" 
                                          onsubmit="return confirm('Bạn có chắc muốn xóa liên hệ này?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="inline-flex items-center px-4 py-2 bg-red-50 text-red-600 rounded-lg hover:bg-red-100 transition-all duration-200 border border-red-200 hover:border-red-300">
                                            <span class="material-icons text-sm">delete</span>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        
        <!-- Pagination -->
        <div class="mt-6">
            {{ $contacts->links() }}
        </div>
        @else
        <!-- Empty State -->
        <div class="text-center py-16">
            <div class="bg-gradient-to-br from-gray-100 to-gray-50 w-24 h-24 rounded-2xl flex items-center justify-center mx-auto mb-6 shadow-inner">
                <span class="material-icons text-4xl text-gray-400">contact_mail</span>
            </div>
            <h3 class="text-xl font-semibold text-gray-600 mb-2">Chưa có liên hệ nào</h3>
            <p class="text-gray-500 max-w-md mx-auto">Khi khách hàng gửi liên hệ, thông tin sẽ xuất hiện tại đây.</p>
        </div>
        @endif
    </div>
</div>

<style>
.line-clamp-1 {
    overflow: hidden;
    display: -webkit-box;
    -webkit-box-orient: vertical;
    -webkit-line-clamp: 1;
}

.line-clamp-2 {
    overflow: hidden;
    display: -webkit-box;
    -webkit-box-orient: vertical;
    -webkit-line-clamp: 2;
}

.material-icons {
    font-size: 1.25rem;
}
</style>
@endsection