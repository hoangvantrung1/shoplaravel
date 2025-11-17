@extends('layouts.client')

@section('title', 'Liên hệ')

@section('content')
<div class="container py-5">
    <h1 class="text-2xl font-bold mb-4">Liên hệ với chúng tôi</h1>

    @if(session('success'))
        <div class="alert alert-success mb-3">{{ session('success') }}</div>
    @endif

    <form action="{{ route('contact.submit') }}" method="POST" class="space-y-4">
        @csrf
        <div>
            <label class="block font-semibold">Họ và tên</label>
            <input type="text" name="name" class="border rounded w-full p-2" value="{{ old('name') }}" required>
        </div>
        <div>
            <label class="block font-semibold">Email</label>
            <input type="email" name="email" class="border rounded w-full p-2" value="{{ old('email') }}" required>
        </div>
        <div>
            <label class="block font-semibold">Số điện thoại</label>
            <input type="text" name="phone" class="border rounded w-full p-2" value="{{ old('phone') }}" required>
        </div>
        <div>
            <label class="block font-semibold">Chủ đề</label>
            <input type="text" name="subject" class="border rounded w-full p-2" value="{{ old('subject') }}" required>
        </div>
        <div>
            <label class="block font-semibold">Nội dung</label>
            <textarea name="message" rows="4" class="border rounded w-full p-2" required>{{ old('message') }}</textarea>
        </div>
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Gửi liên hệ</button>
    </form>
</div>
@endsection
