<h2>Đơn hàng {{ $order->order_code }} đã được xác nhận</h2>
<p>Xin chào {{ $order->customer_name }},</p>
<p>Cảm ơn bạn đã đặt hàng. Tổng thanh toán: <strong>{{ number_format($order->total) }} đ</strong>.</p>
<p>Chúng tôi sẽ xử lý đơn hàng của bạn trong thời gian sớm nhất.</p>


