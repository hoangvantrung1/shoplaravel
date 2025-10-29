@extends('layouts.admin')

@section('title', 'Thông tin Quản trị viên')

@section('content')
<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden max-w-full mx-auto">
    <!-- Header -->
    <div class="bg-gradient-to-r from-green-500 to-teal-600 px-6 py-5">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-white">Thông tin Quản trị viên</h1>
                <p class="text-green-100 text-sm mt-1">Quản lý thông tin tài khoản quản trị hệ thống</p>
            </div>
            <div class="flex items-center space-x-2 bg-white/20 px-3 py-1.5 rounded-lg">
                <span class="material-icons text-white text-lg">verified_user</span>
                <span class="text-white font-medium text-sm">Super Admin</span>
            </div>
        </div>
    </div>

    <div class="p-6">
        <!-- Profile Header -->
        <div class="flex items-center gap-6 mb-8 p-6 bg-gradient-to-r from-green-50 to-teal-50 rounded-xl border border-green-200">
            <div class="relative">
                <div class="h-20 w-20 rounded-xl bg-gradient-to-br from-green-500 to-teal-600 flex items-center justify-center shadow-md">
                    @php
                        $initials = '';
                        $nameParts = explode(' ', $admin->name);
                        foreach ($nameParts as $part) {
                            $initials .= strtoupper(substr($part, 0, 1));
                        }
                        $initials = substr($initials, 0, 2);
                    @endphp
                    <span class="text-white font-bold text-2xl">{{ $initials }}</span>
                </div>
                <div class="absolute -bottom-1 -right-1 bg-green-500 rounded-full p-1 shadow-md">
                    <span class="material-icons text-white text-xs">check</span>
                </div>
            </div>

            <div class="flex-1">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900">{{ $admin->name }}</h2>
                        <p class="text-gray-600 mt-1 flex items-center">
                            <span class="material-icons text-green-500 mr-2 text-base">email</span>
                            {{ $admin->email }}
                        </p>
                        <div class="flex flex-wrap gap-2 mt-3">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                <span class="material-icons mr-1 text-xs">security</span>
                                Quản trị viên
                            </span>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                <span class="material-icons mr-1 text-xs">circle</span>
                                Đang hoạt động
                            </span>
                        </div>
                    </div>
                    <div>
                        <a href="{{ route('admin.admins.edit') }}"
                            class="inline-flex items-center px-4 py-2 bg-white text-green-600 border border-green-300 rounded-lg font-medium hover:bg-green-50 hover:shadow-sm transition-all duration-200 group">
                            <span class="material-icons mr-2 text-base group-hover:scale-110 transition-transform">edit</span>
                            Chỉnh sửa
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content Grid -->
        <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
            <!-- Left Column -->
            <div class="xl:col-span-2 space-y-6">
                <!-- Personal Information -->
                <div class="bg-white rounded-lg border border-gray-200 shadow-sm">
                    <div class="px-5 py-4 border-b border-gray-200 bg-gray-50">
                        <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                            <span class="material-icons text-green-500 mr-2 text-xl">person</span>
                            Thông tin cá nhân
                        </h3>
                    </div>
                    <div class="p-5">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="space-y-1">
                                <label class="block text-sm font-medium text-gray-500">ID tài khoản</label>
                                <p class="text-base font-semibold text-gray-900">#{{ $admin->id }}</p>
                            </div>
                            <div class="space-y-1">
                                <label class="block text-sm font-medium text-gray-500">Tên đầy đủ</label>
                                <p class="text-base font-semibold text-gray-900">{{ $admin->name }}</p>
                            </div>
                            <div class="space-y-1">
                                <label class="block text-sm font-medium text-gray-500">Email</label>
                                <p class="text-base font-semibold text-gray-900">{{ $admin->email }}</p>
                            </div>
                            <div class="space-y-1">
                                <label class="block text-sm font-medium text-gray-500">Vai trò</label>
                                <p class="text-base font-semibold text-green-600">Super Administrator</p>
                            </div>
                            <div class="space-y-1">
                                <label class="block text-sm font-medium text-gray-500">Ngày tạo</label>
                                <p class="text-base font-semibold text-gray-900">{{ $admin->created_at->format('d/m/Y H:i') }}</p>
                            </div>
                            <div class="space-y-1">
                                <label class="block text-sm font-medium text-gray-500">Cập nhật lần cuối</label>
                                <p class="text-base font-semibold text-gray-900">{{ $admin->updated_at->format('d/m/Y H:i') }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- System Statistics -->
                <div class="bg-white rounded-lg border border-gray-200 shadow-sm">
                    <div class="px-5 py-4 border-b border-gray-200 bg-gray-50">
                        <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                            <span class="material-icons text-blue-500 mr-2 text-xl">analytics</span>
                            Thống kê hệ thống
                        </h3>
                    </div>
                    <div class="p-5">
                        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
                            <div class="text-center p-4 bg-blue-50 rounded-lg border border-blue-100">
                                <div class="bg-blue-100 w-10 h-10 rounded-full flex items-center justify-center mx-auto mb-2">
                                    <span class="material-icons text-blue-600 text-lg">people</span>
                                </div>
                                <p class="text-2xl font-bold text-blue-600">{{ \App\Models\User::count() }}</p>
                                <p class="text-xs text-gray-600 font-medium">Người dùng</p>
                            </div>
                            <div class="text-center p-4 bg-green-50 rounded-lg border border-green-100">
                                <div class="bg-green-100 w-10 h-10 rounded-full flex items-center justify-center mx-auto mb-2">
                                    <span class="material-icons text-green-600 text-lg">inventory_2</span>
                                </div>
                                <p class="text-2xl font-bold text-green-600">{{ \App\Models\Product::count() }}</p>
                                <p class="text-xs text-gray-600 font-medium">Sản phẩm</p>
                            </div>
                            <div class="text-center p-4 bg-orange-50 rounded-lg border border-orange-100">
                                <div class="bg-orange-100 w-10 h-10 rounded-full flex items-center justify-center mx-auto mb-2">
                                    <span class="material-icons text-orange-600 text-lg">shopping_cart</span>
                                </div>
                                <p class="text-2xl font-bold text-orange-600">{{ \App\Models\Order::count() }}</p>
                                <p class="text-xs text-gray-600 font-medium">Đơn hàng</p>
                            </div>
                            <div class="text-center p-4 bg-purple-50 rounded-lg border border-purple-100">
                                <div class="bg-purple-100 w-10 h-10 rounded-full flex items-center justify-center mx-auto mb-2">
                                    <span class="material-icons text-purple-600 text-lg">category</span>
                                </div>
                                <p class="text-2xl font-bold text-purple-600">{{ \App\Models\Category::count() }}</p>
                                <p class="text-xs text-gray-600 font-medium">Danh mục</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column -->
            <div class="space-y-6">
                <!-- Account Status -->
                <div class="bg-white rounded-lg border border-gray-200 shadow-sm">
                    <div class="px-5 py-4 border-b border-gray-200 bg-green-50">
                        <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                            <span class="material-icons text-green-500 mr-2 text-xl">verified</span>
                            Trạng thái tài khoản
                        </h3>
                    </div>
                    <div class="p-5">
                        <div class="space-y-3">
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">Trạng thái</span>
                                <span class="px-2.5 py-0.5 bg-green-100 text-green-800 rounded-full text-xs font-medium">
                                    Đang hoạt động
                                </span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">Xác thực</span>
                                <span class="px-2.5 py-0.5 bg-blue-100 text-blue-800 rounded-full text-xs font-medium">
                                    Đã xác thực
                                </span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">Quyền hạn</span>
                                <span class="px-2.5 py-0.5 bg-purple-100 text-purple-800 rounded-full text-xs font-medium">
                                    Toàn quyền
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="bg-white rounded-lg border border-gray-200 shadow-sm">
                    <div class="px-5 py-4 border-b border-gray-200 bg-blue-50">
                        <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                            <span class="material-icons text-blue-500 mr-2 text-xl">bolt</span>
                            Thao tác nhanh
                        </h3>
                    </div>
                    <div class="p-5">
                        <div class="space-y-2">
                            <a href="{{ route('admin.admins.edit') }}" 
                               class="flex items-center p-3 bg-green-50 hover:bg-green-100 rounded-lg transition-all duration-200 group border border-green-200">
                                <span class="material-icons text-green-600 mr-3 text-lg group-hover:scale-110 transition-transform">edit</span>
                                <span class="font-medium text-gray-800 text-sm">Chỉnh sửa thông tin</span>
                            </a>
                            <a href="{{ route('admin.dashboard') }}" 
                               class="flex items-center p-3 bg-blue-50 hover:bg-blue-100 rounded-lg transition-all duration-200 group border border-blue-200">
                                <span class="material-icons text-blue-600 mr-3 text-lg group-hover:scale-110 transition-transform">dashboard</span>
                                <span class="font-medium text-gray-800 text-sm">Về trang chủ</span>
                            </a>
                            <a href="{{ route('admin.users.index') }}" 
                               class="flex items-center p-3 bg-purple-50 hover:bg-purple-100 rounded-lg transition-all duration-200 group border border-purple-200">
                                <span class="material-icons text-purple-600 mr-3 text-lg group-hover:scale-110 transition-transform">manage_accounts</span>
                                <span class="font-medium text-gray-800 text-sm">Quản lý người dùng</span>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Security -->
                <div class="bg-white rounded-lg border border-gray-200 shadow-sm">
                    <div class="px-5 py-4 border-b border-gray-200 bg-red-50">
                        <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                            <span class="material-icons text-red-500 mr-2 text-xl">security</span>
                            Bảo mật
                        </h3>
                    </div>
                    <div class="p-5">
                        <div class="space-y-2 mb-4">
                            <div class="flex items-center text-sm text-gray-600">
                                <span class="material-icons text-green-500 mr-2 text-base">check_circle</span>
                                Mật khẩu mạnh
                            </div>
                            <div class="flex items-center text-sm text-gray-600">
                                <span class="material-icons text-green-500 mr-2 text-base">check_circle</span>
                                Phiên đăng nhập an toàn
                            </div>
                            <div class="flex items-center text-sm text-gray-600">
                                <span class="material-icons text-green-500 mr-2 text-base">check_circle</span>
                                Truy cập được mã hóa
                            </div>
                        </div>
                        <form action="{{ route('admin.logout') }}" method="POST" class="mt-4">
                            @csrf
                            <button type="submit" 
                                    class="w-full flex items-center justify-center px-4 py-2 bg-red-50 hover:bg-red-100 text-red-600 font-medium rounded-lg border border-red-200 transition-all duration-200 group">
                                <span class="material-icons mr-2 text-lg group-hover:scale-110 transition-transform">logout</span>
                                Đăng xuất
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .material-icons {
        font-family: 'Material Icons';
        font-weight: normal;
        font-style: normal;
        font-size: 24px;
        line-height: 1;
        letter-spacing: normal;
        text-transform: none;
        display: inline-block;
        white-space: nowrap;
        word-wrap: normal;
        direction: ltr;
        -webkit-font-feature-settings: 'liga';
        -webkit-font-smoothing: antialiased;
    }
</style>
@endsection