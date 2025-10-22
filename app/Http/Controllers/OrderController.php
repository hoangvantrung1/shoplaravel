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
        if ($order->status === 'completed') {
            return back()->with('error', 'Không thể hủy đơn hàng đã hoàn thành!');
        }
        if ($order->status === 'cancelled') {
            return back()->with('error', 'Đơn hàng đã được hủy trước đó!');
        }

        $allowedStatuses = ['pending', 'unpaid'];
        if (!in_array($order->status, $allowedStatuses)) {
            return redirect()->back()
                ->with('error', 'Không thể hủy đơn hàng. Đơn hàng đã được xử lý.');
        }


        $order->update(['status' => 'cancelled']);


        $orderItems = $order->items ?? [];

        // Hoàn tồn kho
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
}