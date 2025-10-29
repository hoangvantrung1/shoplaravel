@extends('layouts.admin')

@section('title', 'Quản lý Liên hệ')

@section('content')
<div class="bg-white rounded-lg shadow">
    <div class="p-6 border-b">
        <h2 class="text-2xl font-bold text-gray-800">Quản lý Liên hệ</h2>
        <p class="text-gray-600">Quản lý thông tin liên hệ từ khách hàng</p>
    </div>

    <div class="p-6">
        @if($contacts->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full table-auto">
                <thead>
                    <tr class="bg-gray-50">
                        <th class="px-4 py-3 text-left">ID</th>
                        <th class="px-4 py-3 text-left">Họ tên</th>
                        <th class="px-4 py-3 text-left">Email</th>
                        <th class="px-4 py-3 text-left">Tiêu đề</th>
                        <th class="px-4 py-3 text-left">Trạng thái</th>
                        <th class="px-4 py-3 text-left">Ngày gửi</th>
                        <th class="px-4 py-3 text-left">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($contacts as $contact)
                    <tr class="border-b hover:bg-gray-50 {{ is_null($contact->read_at) ? 'bg-blue-50' : '' }}">
                        <td class="px-4 py-3">{{ $contact->id }}</td>
                        <td class="px-4 py-3 font-medium">{{ $contact->name }}</td>
                        <td class="px-4 py-3">{{ $contact->email }}</td>
                        <td class="px-4 py-3">
                            <div class="max-w-xs truncate">{{ $contact->subject }}</div>
                        </td>
                        <td class="px-4 py-3">
                            @if(is_null($contact->read_at))
                            <span class="px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-800">Mới</span>
                            @else
                            <span class="px-2 py-1 text-xs rounded-full bg-gray-100 text-gray-800">Đã đọc</span>
                            @endif
                        </td>
                        <td class="px-4 py-3">{{ $contact->created_at->format('d/m/Y H:i') }}</td>
                        <td class="px-4 py-3">
                            <div class="flex space-x-2">
                                <a href="{{ route('admin.contacts.show', $contact) }}" 
                                   class="text-blue-600 hover:text-blue-800">
                                    <span class="material-icons text-sm">visibility</span>
                                </a>
                                <form action="{{ route('admin.contacts.destroy', $contact) }}" method="POST" 
                                      onsubmit="return confirm('Bạn có chắc muốn xóa liên hệ này?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800">
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
        
        <div class="mt-4">
            {{ $contacts->links() }}
        </div>
        @else
        <div class="text-center py-8">
            <span class="material-icons text-6xl text-gray-400">contact_mail</span>
            <p class="mt-4 text-gray-500">Chưa có liên hệ nào</p>
        </div>
        @endif
    </div>
</div>
@endsection