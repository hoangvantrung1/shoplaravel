<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CouponController extends Controller
{
    public function apply(Request $request)
    {
        $request->validate([
            'code' => 'required|string|max:50'
        ]);

        $code = strtoupper(trim($request->input('code')));
        $coupon = Coupon::where('code', $code)->first();

        // Lấy tổng giá trị giỏ hàng
        $cart = Session::get('cart', []);
        $subtotal = 0;
        foreach ($cart as $item) {
            $subtotal += ($item['price'] ?? 0) * ($item['quantity'] ?? 1);
        }

        if (!$coupon) {
            return back()->with('error', 'Mã giảm giá "' . $code . '" không tồn tại.');
        }

        // Kiểm tra các điều kiện theo thứ tự
        if (!$coupon->is_active) {
            return back()->with('error', 'Mã giảm giá không hợp lệ hoặc không áp dụng được (mã không hoạt động)');
        }

        $now = now();
        if ($coupon->starts_at && $now->lt($coupon->starts_at)) {
            $startDate = $coupon->starts_at->format('d/m/Y');
            return back()->with('error', 'Mã giảm giá không hợp lệ hoặc không áp dụng được (mã có hiệu lực từ ' . $startDate . ')');
        }

        if ($coupon->expires_at && $now->gt($coupon->expires_at)) {
            $expireDate = $coupon->expires_at->format('d/m/Y');
            return back()->with('error', 'Mã giảm giá không hợp lệ hoặc không áp dụng được (mã đã hết hạn từ ' . $expireDate . ')');
        }

        if (!is_null($coupon->usage_limit) && $coupon->usage_count >= $coupon->usage_limit) {
            return back()->with('error', 'Mã giảm giá không hợp lệ hoặc không áp dụng được (mã đã hết số lần sử dụng)');
        }

        if (!is_null($coupon->min_order_total) && $subtotal < $coupon->min_order_total) {
            $minOrder = number_format($coupon->min_order_total);
            return back()->with('error', 'Mã giảm giá không hợp lệ hoặc không áp dụng được (đơn hàng tối thiểu ' . $minOrder . ' đ)');
        }

        // Tính toán discount
        $discount = $coupon->calculateDiscount($subtotal);

        if ($discount <= 0) {
            return back()->with('error', 'Mã giảm giá không thể áp dụng cho đơn hàng hiện tại.');
        }

        // Lưu thông tin coupon vào session
        Session::put('coupon', [
            'id' => $coupon->id,
            'code' => $coupon->code,
            'discount' => $discount,
            'type' => $coupon->type,
            'value' => $coupon->value,
            'max_discount' => $coupon->max_discount
        ]);

        // Hiển thị thông báo chi tiết
        $message = 'Áp dụng mã "' . $coupon->code . '" thành công! ';
        if ($coupon->type === 'percent') {
            $message .= 'Giảm ' . $coupon->value . '%';
            if ($coupon->max_discount) {
                $message .= ' (tối đa ' . number_format($coupon->max_discount) . ' đ)';
            }
        } else {
            $message .= 'Giảm ' . number_format($coupon->value) . ' đ';
        }
        $message .= ' - Tiết kiệm: ' . number_format($discount) . ' đ';

        return back()->with('success', $message);
    }

    public function remove()
    {
        Session::forget('coupon');
        return back()->with('success', 'Đã xóa mã giảm giá.');
    }
}