@extends('layouts.admin')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold">Mã giảm giá</h1>
    <a href="{{ route('admin.coupons.create') }}" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded">Thêm mã</a>
</div>

@if(session('success'))
    <div class="bg-green-100 text-green-700 p-3 rounded mb-4">{{ session('success') }}</div>
@endif

<table class="min-w-full bg-white rounded shadow overflow-hidden">
    <thead>
        <tr class="bg-gray-100">
            <th class="px-4 py-2 text-left">Code</th>
            <th class="px-4 py-2 text-left">Loại</th>
            <th class="px-4 py-2 text-left">Giá trị</th>
            <th class="px-4 py-2 text-left">Dùng/Limit</th>
            <th class="px-4 py-2 text-left">Hiệu lực</th>
            <th class="px-4 py-2 text-left">Hành động</th>
        </tr>
    </thead>
    <tbody>
        @foreach($coupons as $c)
        <tr class="border-b">
            <td class="px-4 py-2 font-mono">{{ $c->code }}</td>
            <td class="px-4 py-2">{{ $c->type }}</td>
            <td class="px-4 py-2">{{ $c->value }}</td>
            <td class="px-4 py-2">{{ $c->usage_count }} / {{ $c->usage_limit ?? '∞' }}</td>
            <td class="px-4 py-2">{{ $c->is_active ? 'Đang bật' : 'Tắt' }}</td>
            <td class="px-4 py-2 space-x-2">
                <a href="{{ route('admin.coupons.edit', $c) }}" class="bg-yellow-400 hover:bg-yellow-500 text-white px-3 py-1 rounded">Sửa</a>
                <form action="{{ route('admin.coupons.destroy', $c) }}" method="POST" class="inline-block" onsubmit="return confirm('Xóa mã này?')">
                    @csrf
                    @method('DELETE')
                    <button class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded">Xóa</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

<div class="mt-4">{{ $coupons->links() }}</div>
@endsection



