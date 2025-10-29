<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;

class OrderController extends Controller
{
    // Hiển thị checkout
    public function index(Request $request)
    {

        $query = auth()->user()->orders()->with(['orderItems.product']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('month')) {
            [$year, $month] = explode('-', $request->month);
            $query->whereYear('created_at', $year)->whereMonth('created_at', $month);
        }

        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->date);
        }
        $orders = $query->latest()->paginate(6)->appends($request->query());

        return view('client.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        // Chỉ cho user xem đơn của chính họ
        if ($order->user_id != auth()->id()) {
            abort(403);
        }
        return view('client.orders.show', compact('order'));
    }

    // Hủy đơn hàng (chỉ khi đang unpaid/pending/processing tùy quy định)
        public function cancel(Order $order)
        {
            if ($order->user_id != auth()->id()) {
                abort(403);
            }
            // CHO PHÉP hủy cả đơn hàng chưa thanh toán
            if (in_array($order->status, ['unpaid', 'failed'])) {
                // Cập nhật trạng thái đơn hàng
                $order->update(['status' => 'cancelled']);
                
                return redirect()->route('client.orders.show', $order)
                    ->with('success', 'Đơn hàng chưa thanh toán đã được hủy thành công.');
            }

            if ($order->status === 'completed') {
                return back()->with('error', 'Không thể hủy đơn hàng đã hoàn thành!');
            }

            if ($order->status === 'cancelled') {
                return back()->with('error', 'Đơn hàng đã được hủy trước đó!');
            }

            // Cho phép hủy ở 2 trạng thái: pending và processing
            $allowedStatuses = ['pending', 'processing'];
            if (!in_array($order->status, $allowedStatuses)) {
                return redirect()->back()
                    ->with('error', 'Không thể hủy đơn hàng. Đơn hàng đã được xử lý hoặc thanh toán.');
            }

            // Cập nhật trạng thái đơn hàng
            $order->update(['status' => 'cancelled']);

            // Lấy danh sách sản phẩm trong đơn (chú ý đúng tên quan hệ)
            $orderItems = $order->orderItems ?? [];

            // Hoàn lại tồn kho
            foreach ($orderItems as $item) {
                $product = Product::find($item->product_id);
                if ($product && isset($product->stock)) {
                    $product->increment('stock', (int) $item->quantity);
                }
            }

            return redirect()->route('client.orders.show', $order)
                ->with('success', 'Đơn hàng đã được hủy thành công.');
        }
    public function checkout()
    {
        // Kiểm tra xem user đã đăng nhập chưa
        if (!Auth::check()) {
            return redirect()->route('client.login')->with('error', 'Vui lòng đăng nhập để thanh toán!');
        }

        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Giỏ hàng trống!');
        }

        $grandTotal = 0;
        foreach ($cart as $item) {
            $grandTotal += ($item['price'] ?? 0) * ($item['quantity'] ?? 1);
        }

        return view('checkout.index', compact('cart', 'grandTotal'));
    }

    // Xử lý lưu đơn hàng
    public function store(Request $request)
    {
        // Kiểm tra authentication
        if (!Auth::check()) {
            return redirect()->route('client.login')->with('error', 'Vui lòng đăng nhập để thanh toán!');
        }

        $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email|max:255',
            'customer_phone' => 'required|string|max:20',
            'customer_address' => 'required|string|max:500',
        ]);

        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Giỏ hàng trống!');
        }

        $grandTotal = 0;
        foreach ($cart as $item) {
            $grandTotal += ($item['price'] ?? 0) * ($item['quantity'] ?? 1);
        }

        $order = Order::create([
            'customer_name' => $request->customer_name,
            'customer_email' => $request->customer_email,
            'customer_phone' => $request->customer_phone,
            'customer_address' => $request->customer_address,
            'total' => $grandTotal,
            'status' => 'pending',
            'user_id' => Auth::id(), // Lưu ID của user đã đăng nhập
        ]);

        foreach ($cart as $id => $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $id,
                'quantity' => $item['quantity'] ?? 1,
                'price' => $item['price'] ?? 0,
            ]);
        }

        session()->forget('cart');

        return redirect()->route('home')->with('success', 'Đặt hàng thành công!');
    }
    public function repay(Request $request, Order $order)
    {
        // Kiểm tra quyền truy cập
        if (auth()->check() && $order->user_id !== auth()->id()) {
            abort(403, 'Bạn không có quyền truy cập đơn hàng này.');
        }

        // Chỉ cho phép thanh toán lại với các trạng thái chưa thanh toán
        if (!in_array($order->status, ['unpaid', 'failed'])) {
            return redirect()->back()->with('error', 'Đơn hàng này không thể thanh toán lại.');
        }

        try {
            // Sử dụng trực tiếp logic VNPay
            $vnpUrl = $this->createVnpayUrl($order->id, $order->total, $request->ip());
            
            // Cập nhật ghi chú thanh toán (KHÔNG cập nhật status vì đã là unpaid/failed)
            $order->update([
                'payment_note' => 'Đang chờ thanh toán lại - ' . now()->format('H:i d/m/Y')
            ]);

            return redirect()->away($vnpUrl);

        } catch (\Exception $e) {
            \Log::error('Lỗi thanh toán lại: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Có lỗi xảy ra khi tạo giao dịch thanh toán: ' . $e->getMessage());
        }
    }
        protected function createVnpayUrl($orderId, $amount, $ipAddress)
    {
        $vnp_Url = env('VNP_URL', 'https://sandbox.vnpayment.vn/paymentv2/vpcpay.html');
        $vnp_TmnCode = env('VNP_TMNCODE');
        $vnp_HashSecret = env('VNP_HASHSECRET');

        if (!$vnp_TmnCode || !$vnp_HashSecret) {
            throw new \Exception('Cấu hình VNPay chưa đầy đủ. Vui lòng kiểm tra file .env');
        }

        // TẠO MÃ GIAO DỊCH DUY NHẤT cho lần thanh toán lại
        $uniqueTransactionRef = $orderId . '_' . time() . '_' . rand(1000, 9999);

        $inputData = [
            'vnp_Version' => '2.1.0',
            'vnp_TmnCode' => $vnp_TmnCode,
            'vnp_Amount' => intval($amount * 100),
            'vnp_Command' => 'pay',
            'vnp_CreateDate' => date('YmdHis'),
            'vnp_CurrCode' => 'VND',
            'vnp_IpAddr' => $ipAddress,
            'vnp_Locale' => 'vn',
            'vnp_OrderInfo' => "Thanh toán lại đơn hàng #$orderId",
            'vnp_OrderType' => 'other',
            'vnp_ReturnUrl' => route('checkout.vnpay.return'),
            'vnp_TxnRef' => $uniqueTransactionRef, // Sử dụng mã duy nhất
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
}