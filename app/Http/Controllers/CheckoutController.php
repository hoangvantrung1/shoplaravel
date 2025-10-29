<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Coupon;
use Illuminate\Support\Facades\Mail;
use App\Mail\OrderConfirmed;

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

        $addresses = auth()->check() ? auth()->user()->addresses()->orderByDesc('is_default')->get() : collect();
        return view('checkout.index', compact('cart', 'grandTotal', 'addresses'));
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
        // Tính tổng tiền và áp dụng coupon nếu có
        $grandTotal = 0;
        foreach ($cart as $item) {
            $grandTotal += ($item['price'] ?? 0) * ($item['quantity'] ?? 1);
        }
        $couponSession = session('coupon');
        $discount = $couponSession['discount'] ?? 0;
        $payableTotal = max(0, $grandTotal - $discount);
        $orderCode = 'DH-' . strtoupper(uniqid());
        // Tạo đơn hàng
        $order = Order::create([
            'order_code' => $orderCode,
            'customer_name' => $request->customer_name,
            'customer_email' => $request->customer_email,
            'customer_phone' => $request->customer_phone,
            'customer_address' => $request->customer_address,
            'total' => $payableTotal,
            'status' => $request->payment_method === 'cod' ? 'pending' : 'unpaid',
            'payment_method' => $request->payment_method,
            'user_id' => auth()->check() ? auth()->id() : null,
        ]);

        // Tạo các mục đơn hàng và trừ tồn kho tạm thời
        foreach ($cart as $id => $item) {
            $quantity = $item['quantity'] ?? 1;
            $price = $item['price'] ?? 0;

            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $id,
                'quantity' => $quantity,
                'price' => $price,
            ]);

            $product = Product::find($id);
            if ($product && isset($product->stock)) {
                $newStock = max(0, (int) $product->stock - (int) $quantity);
                $product->update(['stock' => $newStock]);
            }
        }

        if ($request->payment_method === 'cod') {
            $order->update([
                'payment_date' => now('Asia/Ho_Chi_Minh'),
                'transaction_id' => 'COD-' . $order->id . '-' . date('YmdHis'),
                'bank_code' => 'COD',
                'payment_note' => 'Thanh toán khi nhận hàng'
            ]);
            session()->forget('cart');
            if ($couponSession && isset($couponSession['code'])) {
                $coupon = Coupon::where('code', $couponSession['code'])->first();
                if ($coupon) {
                    $coupon->increment('usage_count');
                }
                session()->forget('coupon');
            }

            // Gửi email xác nhận
            try {
                Mail::to($order->customer_email)->send(new OrderConfirmed($order));
            } catch (\Throwable $e) {
                \Log::error('Gửi email thất bại: ' . $e->getMessage());
            }

            return redirect()->route('home')->with('success', 'Đặt hàng thành công!');
        }
        // Nếu thanh toán online (VNPAY)
        $vnpUrl = $this->createVnpayUrl($order->id, $payableTotal, $request->ip());
        return redirect()->away($vnpUrl);
    }

    // Tạo URL VNPAY 
    protected function createVnpayUrl($orderId, $amount, $ipAddress)
    {
        $vnp_Url = env('VNP_URL', 'https://sandbox.vnpayment.vn/paymentv2/vpcpay.html');
        $vnp_ReturnUrl = route('checkout.vnpay.return');
        $vnp_TmnCode = env('VNP_TMNCODE');
        $vnp_HashSecret = env('VNP_HASHSECRET');


        if (!$vnp_TmnCode || !$vnp_HashSecret) {
            throw new \Exception('Cấu hình VNPay chưa đầy đủ. Vui lòng kiểm tra file .env');
        }

        $inputData = [
            'vnp_Version' => '2.1.0',
            'vnp_TmnCode' => $vnp_TmnCode,
            'vnp_Amount' => intval($amount * 100),  // số tiền * 100
            'vnp_Command' => 'pay',
            'vnp_CreateDate' => date('YmdHis'),
            'vnp_CurrCode' => 'VND',
            'vnp_IpAddr' => request()->ip(),
            'vnp_Locale' => 'vn',
            'vnp_OrderInfo' => "Thanh toán đơn hàng #$orderId",
            'vnp_OrderType' => 'other',
            'vnp_ReturnUrl' => route('checkout.vnpay.return'),
            'vnp_TxnRef' => $orderId, // mã đơn hàng duy nhất
        ];

        ksort($inputData);

        $query = "";
        $hashData = "";
        $i = 0;

        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashData .= '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $hashData .= urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
            $query .= urlencode($key) . "=" . urlencode($value) . '&';
        }

        $vnpSecureHash = hash_hmac('sha512', $hashData, $vnp_HashSecret);
        $vnp_Url .= "?" . $query . "vnp_SecureHash=" . $vnpSecureHash;

        return $vnp_Url;
    }

    // Callback VNPAY
    public function vnpayReturn(Request $request)
    {
        \Log::info('VNPay Return Data: ', $request->all());

        $vnp_HashSecret = env('VNP_HASHSECRET');

        if (!$vnp_HashSecret) {
            return redirect()->route('home')->with('error', 'Cấu hình VNPay không hợp lệ.');
        }

        $inputData = $request->all();
        $secureHash = $inputData['vnp_SecureHash'] ?? '';
        unset($inputData['vnp_SecureHash']);

        // Sắp xếp mảng dữ liệu và tạo chuỗi hash
        ksort($inputData);
        $hashData = "";
        $i = 0;

        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashData .= '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $hashData .= urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
        }
        $secureHashCheck = hash_hmac('sha512', $hashData, $vnp_HashSecret);
        
        // XỬ LÝ vnp_TxnRef cho cả thanh toán mới và thanh toán lại
        $vnpTxnRef = $request->vnp_TxnRef;
        
        // Nếu là thanh toán lại (có dạng "orderId_timestamp_random"), lấy orderId
        if (strpos($vnpTxnRef, '_') !== false) {
            $parts = explode('_', $vnpTxnRef);
            $orderId = $parts[0]; // Lấy phần đầu là orderId
        } else {
            // Nếu là thanh toán mới, dùng trực tiếp
            $orderId = $vnpTxnRef;
        }
        
        $order = Order::find($orderId);

        if (!$order) {
            \Log::error('Đơn hàng không tồn tại: ' . $orderId . ' - vnp_TxnRef: ' . $vnpTxnRef);
            return redirect()->route('home')->with('error', 'Đơn hàng không tồn tại.');
        }

        if ($secureHash === $secureHashCheck) {
            if ($request->vnp_ResponseCode == '00') {
                // Thanh toán thành công
                $order->update([
                    'status' => 'paid',
                    'payment_date' => now('Asia/Ho_Chi_Minh'),
                    'transaction_id' => $request->vnp_TransactionNo,
                    'bank_code' => $request->vnp_BankCode,
                    'payment_note' => 'Thanh toán thành công qua VNPay - Giao dịch: ' . $vnpTxnRef
                ]);

                // Xóa giỏ hàng và coupon (chỉ với thanh toán mới)
                if (strpos($vnpTxnRef, '_') === false) {
                    // Đây là thanh toán mới
                    session()->forget('cart');
                    $couponSession = session('coupon');
                    if ($couponSession && isset($couponSession['code'])) {
                        $coupon = Coupon::where('code', $couponSession['code'])->first();
                        if ($coupon) {
                            $coupon->increment('usage_count');
                        }
                        session()->forget('coupon');
                    }
                }

                // Gửi email xác nhận
                try {
                    Mail::to($order->customer_email)->send(new OrderConfirmed($order));
                } catch (\Throwable $e) {
                    \Log::error('Gửi email thất bại: ' . $e->getMessage());
                }

                // Redirect về trang chi tiết đơn hàng
                return redirect()->route('client.orders.show', $order->id)
                    ->with('success', 'Thanh toán thành công! Cảm ơn bạn đã mua hàng.');

            } else {
                $order->update([
                    'status' => 'failed',
                    'payment_note' => 'Thanh toán thất bại. Mã lỗi: ' . $request->vnp_ResponseCode . ' - Giao dịch: ' . $vnpTxnRef
                ]);

                // Nếu là thanh toán lại, redirect về trang đơn hàng
                if (strpos($vnpTxnRef, '_') !== false) {
                    return redirect()->route('client.orders.show', $order->id)
                        ->with('error', 'Thanh toán thất bại. Mã lỗi: ' . $request->vnp_ResponseCode);
                } else {
                    // Nếu là thanh toán mới, redirect về checkout
                    return redirect()->route('checkout.show')
                        ->with('error', 'Thanh toán thất bại. Mã lỗi: ' . $request->vnp_ResponseCode);
                }
            }
        } else {
            // Chữ ký không hợp lệ
            $order->update([
                'status' => 'failed',
                'payment_note' => 'Chữ ký không hợp lệ - Giao dịch: ' . $vnpTxnRef
            ]);

            \Log::error('Chữ ký VNPay không hợp lệ. Order ID: ' . $orderId . ' - vnp_TxnRef: ' . $vnpTxnRef);
            
            // Nếu là thanh toán lại, redirect về trang đơn hàng
            if (strpos($vnpTxnRef, '_') !== false) {
                return redirect()->route('client.orders.show', $order->id)
                    ->with('error', 'Lỗi bảo mật: Chữ ký không hợp lệ. Vui lòng thử lại.');
            } else {
                return redirect()->route('checkout.show')
                    ->with('error', 'Lỗi bảo mật: Chữ ký không hợp lệ. Vui lòng thử lại.');
            }
        }
    }

    // IPN URL cho VNPay (nếu cần)
    public function vnpayIpn(Request $request)
    {
        \Log::info('VNPay IPN Data: ', $request->all());

        // Xử lý IPN tại đây nếu cần
        return response()->json(['RspCode' => '00', 'Message' => 'Confirm Success']);
    }
}