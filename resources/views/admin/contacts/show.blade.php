@extends('layouts.admin')

@section('title', 'Chi tiết Liên hệ')

@section('content')
<div class="bg-white rounded-xl shadow-lg overflow-hidden">
    <!-- Header -->
    <div class="bg-gradient-to-r from-green-500 to-teal-600 px-6 py-4">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-white">Chi tiết Liên hệ</h1>
                <p class="text-blue-100 mt-1">Thông tin liên hệ #{{ $contact->id }}</p>
            </div>
            <a href="{{ route('admin.contacts.index') }}" 
               class="bg-white text-blue-600 px-4 py-2 rounded-lg font-medium hover:bg-blue-50 transition duration-200 flex items-center shadow-sm">
                <span class="material-icons mr-2">arrow_back</span>
                Quay lại
            </a>
        </div>
    </div>

    <div class="p-6">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <!-- Thông tin người gửi -->
            <div class="bg-gradient-to-br from-green-50 to-teal-50 rounded-xl p-5 border border-green-100">
                <div class="flex items-center mb-4">
                    <div class="bg-green-100 p-3 rounded-lg">
                        <span class="material-icons text-green-600">person</span>
                    </div>
                    <h3 class="ml-3 font-semibold text-gray-800">Thông tin người gửi</h3>
                </div>
                <div class="space-y-3">
                    <div>
                        <label class="block text-sm font-medium text-gray-600">Họ và tên</label>
                        <p class="mt-1 text-lg font-semibold text-gray-900">{{ $contact->name }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-600">Email</label>
                        <p class="mt-1 text-gray-700">{{ $contact->email }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-600">Số điện thoại</label>
                        <p class="mt-1 text-gray-700">{{ $contact->phone }}</p>
                    </div>
                </div>
            </div>

            <!-- Thông tin liên hệ -->
            <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-xl p-5 border border-blue-100">
                <div class="flex items-center mb-4">
                    <div class="bg-blue-100 p-3 rounded-lg">
                        <span class="material-icons text-blue-600">contact_mail</span>
                    </div>
                    <h3 class="ml-3 font-semibold text-gray-800">Thông tin liên hệ</h3>
                </div>
                <div class="space-y-3">
                    <div>
                        <label class="block text-sm font-medium text-gray-600">Chủ đề</label>
                        <span class="mt-1 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                            {{ $contact->subject }}
                        </span>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-600">Trạng thái</label>
                        <span class="mt-1 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                            {{ $contact->read_at ? 'bg-blue-100 text-blue-800' : 'bg-yellow-100 text-yellow-800' }}">
                            {{ $contact->read_at ? 'Đã đọc' : 'Chưa đọc' }}
                        </span>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-600">Ngày gửi</label>
                        <p class="mt-1 text-gray-700">{{ $contact->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Nội dung tin nhắn -->
        <div class="bg-gray-50 rounded-xl p-5 border border-gray-200 mb-6">
            <div class="flex items-center mb-4">
                <div class="bg-gray-100 p-3 rounded-lg">
                    <span class="material-icons text-gray-600">message</span>
                </div>
                <h3 class="ml-3 font-semibold text-gray-800">Nội dung tin nhắn</h3>
            </div>
            <div class="bg-white rounded-lg p-6 border border-gray-200">
                <p class="text-gray-700 whitespace-pre-line">{{ $contact->message }}</p>
            </div>
        </div>

        <!-- Thông tin kỹ thuật -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div class="bg-gray-50 rounded-xl p-5 border border-gray-200">
                <div class="flex items-center mb-4">
                    <div class="bg-purple-100 p-3 rounded-lg">
                        <span class="material-icons text-purple-600">computer</span>
                    </div>
                    <h3 class="ml-3 font-semibold text-gray-800">Thông tin kỹ thuật</h3>
                </div>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-600">IP Address:</span>
                        <span class="font-mono text-gray-800">{{ $contact->ip_address ?? 'N/A' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">User Agent:</span>
                        <span class="font-mono text-gray-800 text-xs">{{ Str::limit($contact->user_agent ?? 'N/A', 50) }}</span>
                    </div>
                </div>
            </div>

            <div class="bg-gray-50 rounded-xl p-5 border border-gray-200">
                <div class="flex items-center mb-4">
                    <div class="bg-orange-100 p-3 rounded-lg">
                        <span class="material-icons text-orange-600">schedule</span>
                    </div>
                    <h3 class="ml-3 font-semibold text-gray-800">Thời gian</h3>
                </div>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Ngày gửi:</span>
                        <span class="font-medium text-gray-800">{{ $contact->created_at->format('d/m/Y H:i') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Cập nhật:</span>
                        <span class="font-medium text-gray-800">{{ $contact->updated_at->format('d/m/Y H:i') }}</span>
                    </div>
                    @if($contact->read_at)
                    <div class="flex justify-between">
                        <span class="text-gray-600">Đã đọc lúc:</span>
                        <span class="font-medium text-gray-800">{{ $contact->read_at->format('d/m/Y H:i') }}</span>
                    </div>
                    @endif
                </div>
            </div>
        </div>

<!-- Actions -->
<div class="mt-8 pt-6 border-t border-gray-200">
    <div class="flex justify-between items-center">
        <div class="text-sm text-gray-500">
            ID: {{ $contact->id }}
        </div>
        <div class="flex space-x-3">
            @if(!$contact->read_at)
            <form action="{{ route('admin.contacts.mark-read', $contact) }}" method="POST">
                @csrf
                <button type="submit" 
                        class="bg-green-600 text-white px-5 py-2.5 rounded-lg font-medium hover:bg-green-700 transition duration-200 flex items-center shadow-sm">
                    <span class="material-icons mr-2 text-sm">mark_email_read</span>
                    Đánh dấu đã đọc
                </button>
            </form>
            @else
            <!-- Hiển thị trạng thái đã đọc (không có nút mark-unread) -->
            <div class="bg-green-100 text-green-800 px-4 py-2.5 rounded-lg font-medium flex items-center">
                <span class="material-icons mr-2 text-sm">check_circle</span>
                Đã đọc
            </div>
            @endif
            
            <form action="{{ route('admin.contacts.destroy', $contact) }}" method="POST" 
                  onsubmit="return confirm('Bạn có chắc muốn xóa liên hệ này?')">
                @csrf
                @method('DELETE')
                <button type="submit" 
                        class="bg-red-600 text-white px-5 py-2.5 rounded-lg font-medium hover:bg-red-700 transition duration-200 flex items-center shadow-sm">
                    <span class="material-icons mr-2 text-sm">delete</span>
                    Xóa Liên hệ
                </button>
            </form>
        </div>
    </div>
</div>
    </div>
</div>

<style>
.material-icons {
    font-size: 1.25rem;
}
</style>
@endsection