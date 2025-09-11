<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;

class OrderController extends Controller
{
    // Hiển thị checkout
    public function checkout()
    {
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
        ]);

        foreach ($cart as $id => $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $id, // $id ở đây chính là product_id
                'quantity' => $item['quantity'] ?? 1,
                'price' => $item['price'] ?? 0,
            ]);
        }

        session()->forget('cart');

        return redirect()->route('home')->with('success', 'Đặt hàng thành công!');
    }
}
