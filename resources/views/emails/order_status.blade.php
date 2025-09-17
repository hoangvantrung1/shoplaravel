<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Cập nhật đơn hàng #{{ $order->id }}</title>
</head>
<body>
    <h2>Đơn hàng #{{ $order->id }} của bạn</h2>

    <p>Xin chào {{ $order->customer_name }},</p>

    <p>Trạng thái đơn hàng của bạn hiện tại: 
        <strong>{{ ucfirst($order->status) }}</strong>
    </p>

    <h3>Chi tiết đơn hàng:</h3>
    <table border="1" cellpadding="5" cellspacing="0">
        <thead>
            <tr>
                <th>Sản phẩm</th>
                <th>Số lượng</th>
                <th>Giá</th>
                <th>Thành tiền</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->items as $item)
            <tr>
                <td>{{ $item->product->name ?? 'Sản phẩm đã xóa' }}</td>
                <td>{{ $item->quantity }}</td>
                <td>{{ number_format($item->price, 0, ',', '.') }}₫</td>
                <td>{{ number_format($item->price * $item->quantity, 0, ',', '.') }}₫</td>
            </tr>
            @endforeach
            <tr>
                <td colspan="3"><strong>Tổng cộng</strong></td>
                <td><strong>{{ number_format($order->total, 0, ',', '.') }}₫</strong></td>
            </tr>
        </tbody>
    </table>

    <p>Cảm ơn bạn đã đặt hàng!</p>
    <p>Website của chúng tôi.</p>
</body>
</html>
