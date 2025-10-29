@extends('layouts.admin')

@section('title', 'Quản lý Tin nhắn')

@section('content')
<div class="bg-white rounded-lg shadow">
    <div class="p-6 border-b">
        <h2 class="text-2xl font-bold text-gray-800">Quản lý Tin nhắn</h2>
        <p class="text-gray-600">Quản lý tin nhắn từ khách hàng</p>
    </div>

    <div class="p-6">
        @if($chatMessages->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full table-auto">
                <thead>
                    <tr class="bg-gray-50">
                        <th class="px-4 py-3 text-left">ID</th>
                        <th class="px-4 py-3 text-left">Người gửi</th>
                        <th class="px-4 py-3 text-left">Tin nhắn</th>
                        <th class="px-4 py-3 text-left">Loại</th>
                        <th class="px-4 py-3 text-left">Ngày gửi</th>
                        <th class="px-4 py-3 text-left">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($chatMessages as $message)
                    <tr class="border-b hover:bg-gray-50">
                        <td class="px-4 py-3">{{ $message->id }}</td>
                        <td class="px-4 py-3">
                            @if($message->user)
                            {{ $message->user->name }}
                            @else
                            <span class="text-red-500">Đã xóa</span>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            <div class="max-w-xs truncate">{{ $message->message }}</div>
                        </td>
                        <td class="px-4 py-3">
                            <span class="px-2 py-1 text-xs rounded-full 
                                {{ $message->type == 'user' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800' }}">
                                {{ $message->type == 'user' ? 'Khách hàng' : 'Admin' }}
                            </span>
                        </td>
                        <td class="px-4 py-3">{{ $message->created_at->format('d/m/Y H:i') }}</td>
                        <td class="px-4 py-3">
                            <form action="{{ route('admin.chat-messages.destroy', $message) }}" method="POST" 
                                  onsubmit="return confirm('Bạn có chắc muốn xóa tin nhắn này?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-800">
                                    <span class="material-icons text-sm">delete</span>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <div class="mt-4">
            {{ $chatMessages->links() }}
        </div>
        @else
        <div class="text-center py-8">
            <span class="material-icons text-6xl text-gray-400">chat</span>
            <p class="mt-4 text-gray-500">Chưa có tin nhắn nào</p>
        </div>
        @endif
    </div>
</div>
@endsection