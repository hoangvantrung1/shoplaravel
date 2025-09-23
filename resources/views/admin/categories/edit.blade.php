@extends('layouts.admin')

@section('content')
<h1 class="text-2xl font-bold mb-6">Sửa danh mục</h1>

@if ($errors->any())
    <div class="bg-red-100 text-red-700 p-3 rounded mb-4">
        <ul class="list-disc list-inside">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form action="{{ route('admin.categories.update', $category->id) }}" method="POST" class="bg-white p-6 rounded shadow max-w-xl">
    @csrf
    @method('PUT')
    <div class="mb-4">
        <label class="block text-sm font-medium mb-1">Tên danh mục</label>
        <input type="text" name="name" value="{{ old('name', $category->name) }}" class="w-full border rounded px-3 py-2" required>
    </div>
    <div class="flex gap-2">
        <a href="{{ route('admin.categories.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded">Hủy</a>
        <button class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">Cập nhật</button>
    </div>
</form>
@endsection


