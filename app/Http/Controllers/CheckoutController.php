<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;

class CheckoutController extends Controller
{
    // Hiển thị trang checkout
    public function show()
    {
        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Giỏ hàng trống!');
        }

        $grandTotal = 0;
        foreach ($cart as $item) {
            $grandTotal += ($item['price'] ?? 0) * ($item['quantity'] ?? 1);
        }

        return view('layouts.checkout', compact('cart', 'grandTotal'));
    }


    // Xử lý lưu đơn hàng và thanh toán
    public function store(Request $request)
    {
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email|max:255',
            'customer_phone' => 'required|string|max:20',
            'customer_address' => 'required|string|max:500',
            'payment_method' => 'required|in:cod,vnpay',
        ]);

        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Giỏ hàng trống!');
        }

        // Tính tổng tiền
        $grandTotal = 0;
        foreach ($cart as $item) {
            $grandTotal += ($item['price'] ?? 0) * ($item['quantity'] ?? 1);
        }
        $orderCode = 'DH-' . strtoupper(uniqid());
        // Tạo đơn hàng
        $order = Order::create([
            'order_code' => $orderCode,
            'customer_name' => $request->customer_name,
            'customer_email' => $request->customer_email,
            'customer_phone' => $request->customer_phone,
            'customer_address' => $request->customer_address,
            'total' => $grandTotal,
            'status' => $request->payment_method === 'cod' ? 'pending' : 'unpaid',
            'payment_method' => $request->payment_method,
        ]);

        // Tạo các mục đơn hàng
        foreach ($cart as $id => $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $id,
                'quantity' => $item['quantity'] ?? 1,
                'price' => $item['price'] ?? 0,
            ]);
        }

        if ($request->payment_method === 'cod') {
            session()->forget('cart');
            return redirect()->route('home')->with('success', 'Đặt hàng thành công!');
        }

        // Nếu thanh toán online (VNPAY)
        $vnpUrl = $this->createVnpayUrl($order->id, $grandTotal);
        return redirect()->to($vnpUrl);
    }

    // Tạo URL VNPAY (mẫu)
    protected function createVnpayUrl($orderId, $amount)
    {
        $vnp_Url = "https://sandbox.vnpayment.vn/paymentv2/vpcpay.html";

        $vnp_TmnCode = env('VNP_TMNCODE');
        $vnp_HashSecret = env('VNP_HASHSECRET');

        $vnp_Params = [
            'vnp_Version' => '2.1.0',
            'vnp_Command' => 'pay',
            'vnp_TmnCode' => $vnp_TmnCode,
            'vnp_Amount' => intval($amount * 100),
            'vnp_CurrCode' => 'VND',
            'vnp_TxnRef' => $orderId,
            'vnp_OrderInfo' => "Thanh toán đơn hàng #$orderId",
            'vnp_OrderType' => 'other', // Thêm order type
            'vnp_Locale' => 'vn',
            'vnp_ReturnUrl' => route('checkout.vnpay.return'),
            'vnp_IpAddr' => request()->ip(), // Thêm IP address
            'vnp_CreateDate' => date('YmdHis'), // Thêm thời gian tạo
        ];

        // Sắp xếp tham số theo alphabet
        ksort($vnp_Params);

        // Tạo query string
        $query = http_build_query($vnp_Params);

        // Tạo hash
        $hashData = hash_hmac('sha512', $query, $vnp_HashSecret);

        return $vnp_Url . '?' . $query . '&vnp_SecureHash=' . $hashData;
    }

    // Callback VNPAY
    public function vnpayReturn(Request $request)
    {
        $inputData = $request->except(['vnp_SecureHash']);
        $vnp_HashSecret = env('VNP_HASHSECRET');
        $secureHash = $request->vnp_SecureHash;

        ksort($inputData);
        $hashData = http_build_query($inputData, '', '&');
        $secureHashCheck = hash_hmac('sha512', $hashData, $vnp_HashSecret);

        $orderId = $request->vnp_TxnRef;
        $order = Order::find($orderId);

        if (!$order) {
            return redirect()->route('home')->with('error', 'Đơn hàng không tồn tại.');
        }

        if ($secureHash == $secureHashCheck) {
            if ($request->vnp_ResponseCode == '00') {
                // Cập nhật trạng thái và các thông tin liên quan
                $order->update([
                    'status' => 'paid',
                    'payment_date' => now(),
                    'transaction_id' => $request->vnp_TransactionNo
                ]);

                // Chỉ xóa giỏ hàng khi thanh toán thành công
                session()->forget('cart');

                // Thông báo thành công và chuyển hướng về trang chủ
                return redirect()->route('home')->with('success', 'Thanh toán thành công!');

            } else {
                // Thanh toán thất bại, không xóa giỏ hàng
                $order->update(['status' => 'failed']);
                return redirect()->route('checkout.show')
                    ->with('error', 'Thanh toán thất bại: ' . $request->vnp_ResponseCode);
            }
        } else {
            // Chữ ký không hợp lệ, không xóa giỏ hàng
            $order->update(['status' => 'failed']);
            return redirect()->route('checkout.show')
                ->with('error', 'Chữ ký không hợp lệ! Có thể dữ liệu bị giả mạo.');
        }
    }
}
