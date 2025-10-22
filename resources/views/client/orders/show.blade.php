@extends('layouts.client')

@section('title', 'Chi tiết đơn hàng #' . $order->id)

@section('content')
    <div class="container mx-auto px-4 py-8 max-w-5xl space-y-8">

        <h1 class="text-3xl font-extrabold text-gray-900 mb-10 border-b border-gray-200 pb-4 mt-10">
            CHI TIẾT ĐƠN HÀNG
        </h1>
        <div class="bg-white rounded-xl shadow-lg p-8 space-y-6">
            <h1 class="text-gray-500 font-semibold">Mã đơn hàng : <span class="text-indigo-600">
                    {{ $order->order_code }}</span></h1>

            {{-- Thông tin đơn hàng --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <p class="text-gray-500 font-semibold">Ngày tạo</p>
                    <p class="mt-1 text-gray-900">{{ $order->created_at->format('d/m/Y H:i') }}</p>
                </div>
                <div>
                    <p class="text-gray-500 font-semibold">Trạng thái</p>
                    <span class="inline-block mt-1 px-3 py-1 rounded-full text-sm font-semibold {{ $order->status_color }}">
                        {{ $order->status_label }}
                    </span>
                </div>
            </div>

            {{-- Thông tin khách hàng --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-4">
                <div>
                    <p class="text-gray-500 font-semibold">Họ tên</p>
                    <p class="mt-1 text-gray-900">{{ $order->customer_name }}</p>
                </div>
                <div>
                    <p class="text-gray-500 font-semibold">Email</p>
                    <p class="mt-1 text-gray-900">{{ $order->customer_email }}</p>
                </div>
                <div>
                    <p class="text-gray-500 font-semibold">Điện thoại</p>
                    <p class="mt-1 text-gray-900">{{ $order->customer_phone }}</p>
                </div>
                <div>
                    <p class="text-gray-500 font-semibold">Địa chỉ</p>
                    <p class="mt-1 text-gray-900">{{ $order->customer_address }}</p>
                </div>
            </div>

            {{-- Danh sách sản phẩm --}}
            <div>
                <h2 class="text-xl font-semibold mb-4 border-b border-gray-200 pb-2">Sản phẩm trong đơn hàng</h2>

                <div class="overflow-x-auto">
                    <table class="min-w-full text-left border border-gray-300 rounded-lg">
                        <thead class="bg-indigo-50">
                            <tr>
                                <th class="px-6 py-3 border-b border-gray-300 font-medium text-indigo-700">Sản phẩm</th>
                                <th class="px-6 py-3 border-b border-gray-300 font-medium text-indigo-700">Giá</th>
                                <th class="px-6 py-3 border-b border-gray-300 font-medium text-indigo-700">Số lượng</th>
                                <th class="px-6 py-3 border-b border-gray-300 font-medium text-indigo-700">Tổng</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order->orderItems as $item)
                                <tr class="hover:bg-indigo-50 transition-colors">
                                    <td class="px-6 py-4 border-b border-gray-300 align-middle">
                                        <div class="flex items-center gap-3">
                                            @if(!empty($item->product->image))
                                                <img src="{{ asset($item->product->image) }}"
                                                    alt="{{ $item->product->name ?? 'Không có ảnh' }}"
                                                    class="w-16 h-16 object-cover rounded-md border">
                                            @else
                                                <div class="w-16 h-16 bg-gray-100 flex items-center justify-center rounded-md border text-gray-400 text-sm">
                                                    N/A
                                                </div>
                                            @endif
                                            <span class="font-medium text-gray-800">{{ $item->product->name ?? 'Sản phẩm đã bị xóa' }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 border-b border-gray-300 text-gray-600">
                                        {{ number_format($item->price, 0, ',', '.') }} đ
                                    </td>
                                    <td class="px-6 py-4 border-b border-gray-300 text-center text-gray-700 font-semibold">
                                        {{ $item->quantity }}
                                    </td>
                                    <td class="px-6 py-4 border-b border-gray-300 text-right font-semibold text-indigo-600">
                                        {{ number_format($item->price * $item->quantity, 0, ',', '.') }} đ
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Tổng tiền --}}
            <div class="flex justify-end mt-6">
                <div class="bg-gray-50 p-6 rounded-lg w-full md:w-1/3 space-y-2">
                    <div class="flex justify-between text-lg border-t border-gray-200 pt-2 mt-2">
                        <span class="font-semibold text-gray-900">Tổng tiền:</span>
                        <span class="font-bold text-green-600">{{ number_format($order->total, 0, ',', '.') }} đ</span>
                    </div>
                </div>
            </div>
            {{-- Nút hành động --}}
            <div class="flex justify-end space-x-3 mt-6">
                <a href="{{ route('client.orders.index') }}"
                class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold py-2 px-4 rounded-lg transition duration-200 shadow-sm flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M12.707 15.707a1 1 0 01-1.414 0L6.586 11l4.707-4.707a1 1 0 111.414 1.414L9.414 11l3.293 3.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                    </svg>
                    Quay lại
                </a>

            @if(in_array($order->status, ['pending', 'processing']))
                <form action="{{ route('client.orders.cancel', $order->id) }}" method="POST" onsubmit="return confirm('Bạn có chắc muốn hủy đơn hàng này không?')">
                    @csrf
                    @method('PUT')
                    <button type="submit" class="btn btn-danger">Hủy đơn hàng</button>
                </form>
            @endif
            </div>
        </div>
    </div>

    <!-- Modal xác nhận hủy đơn hàng -->
    <div id="cancelModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-xl shadow-xl p-6 max-w-md w-full mx-4">
            <div class="flex items-center mb-4">
                <div class="bg-red-100 p-3 rounded-full mr-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-red-600" viewBox="0 0 20 20"
                        fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                            clip-rule="evenodd" />
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900">Xác nhận hủy đơn hàng</h3>
            </div>

            <p class="text-gray-600 mb-6">Bạn có chắc chắn muốn hủy đơn hàng <span
                    class="font-semibold">#{{ $order->order_code }}</span>? Hành động này không thể hoàn tác.</p>

            <div class="flex justify-end space-x-3">
                <button type="button" onclick="closeCancelModal()"
                    class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition duration-200">
                    Quay lại
                </button>
                <form id="cancelForm" action="{{ route('client.orders.cancel', $order) }}" method="POST">
                    @csrf
                    @method('POST')
                    <button type="submit"
                        class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition duration-200">
                        Xác nhận hủy
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script>
        function openCancelModal() {
            document.getElementById('cancelModal').classList.remove('hidden');
        }

        function closeCancelModal() {
            document.getElementById('cancelModal').classList.add('hidden');
        }

        // Đóng modal khi click bên ngoài
        document.getElementById('cancelModal').addEventListener('click', function (e) {
            if (e.target.id === 'cancelModal') {
                closeCancelModal();
            }
        });
    </script>
@endsection