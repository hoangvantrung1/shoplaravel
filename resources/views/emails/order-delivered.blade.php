@component('mail::message')
# Đơn hàng của bạn đã được giao thành công

Xin chào **{{ $order->customer_name }}**,

Đơn hàng **#{{ $order->order_code }}** đã được giao thành công. Cảm ơn bạn đã tin tưởng và mua sắm tại cửa hàng chúng tôi.

@component('mail::panel')
**Thông tin đơn hàng**

- Ngày đặt: {{ $order->created_at->format('d/m/Y H:i') }}
- Tổng tiền: {{ number_format($order->total, 0, ',', '.') }} đ
- Trạng thái hiện tại: **Đã giao thành công**
@endcomponent

Nếu có bất kỳ thắc mắc hay phản hồi, bạn vui lòng liên hệ đội ngũ hỗ trợ để được giúp đỡ nhanh nhất.

Trân trọng,<br>
{{ config('app.name') }}
@endcomponent

