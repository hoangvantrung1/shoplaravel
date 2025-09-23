<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class CartController extends Controller
{
    // Hiển thị giỏ hàng
    public function index()
    {
        $cart = session()->get('cart', []);
        return view('cart.index', compact('cart'));
    }

    // Thêm sản phẩm vào giỏ
    public function add(Request $request)
    {
        $product = Product::findOrFail($request->id);
        $cart = session()->get('cart', []);
        $quantity = max(1, (int) $request->quantity);

        if (isset($cart[$request->id])) {
            $cart[$request->id]['quantity'] += $quantity;
        } else {
            $cart[$request->id] = [
                "id" => $product->id,
                "name" => $product->name,
                "quantity" => $quantity,
                "price" => $product->price,
                "image" => $product->image
            ];
        }
        if (isset($product->stock)) {
            $cart[$request->id]['quantity'] = min($cart[$request->id]['quantity'], max(0, (int) $product->stock));
        }

        session()->put('cart', $cart);
        session()->forget('coupon');
        return redirect()->back()->with('success', 'Sản phẩm đã được thêm vào giỏ hàng!');
    }

    // Cập nhật số lượng sản phẩm
    public function update(Request $request, $id)
    {
        $quantity = $request->quantity ?? 1;
        $cart = session()->get('cart', []);

        if (isset($cart[$id])) {
            $product = Product::find($id);
            $newQuantity = max(1, (int) $quantity);
            if ($product && isset($product->stock)) {
                $newQuantity = min($newQuantity, max(0, (int) $product->stock));
            }
            $cart[$id]['quantity'] = $newQuantity;
            session(['cart' => $cart]);
        }
        session()->forget('coupon');
        return redirect()->back()->with('success', 'Cập nhật giỏ hàng thành công!');
    }

    public function remove($id)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$id])) {
            unset($cart[$id]);
            session(['cart' => $cart]);
        }
        session()->forget('coupon');
        return redirect()->back()->with('success', 'Đã xóa sản phẩm!');
    }

    // Áp dụng mã giảm giá
    public function applyCoupon(Request $request)
    {
        $request->validate([
            'code' => 'required|string'
        ]);

        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Giỏ hàng trống!');
        }

        $grandTotal = 0;
        foreach ($cart as $item) {
            $grandTotal += ($item['price'] ?? 0) * ($item['quantity'] ?? 1);
        }
        \Log::info('Apply Coupon Debug', [
            'input_code' => $request->code,
            'grand_total' => $grandTotal,
            'cart_items' => count($cart)
        ]);
        $coupon = \App\Models\Coupon::where('code', strtoupper($request->code))->first();
        if (!$coupon || !$coupon->isValidForTotal($grandTotal)) {
            return back()->with('error', 'Mã giảm giá không hợp lệ hoặc không áp dụng được.');

        }

        $discount = $coupon->calculateDiscount($grandTotal);
        session([
            'coupon' => [
                'code' => $coupon->code,
                'discount' => $discount,
                'type' => $coupon->type,
                'value' => $coupon->value,
            ]
        ]);

        return back()->with('success', 'Áp dụng mã giảm giá thành công!');
    }
    public function removeCoupon()
    {
        session()->forget('coupon');
        return back()->with('success', 'Đã gỡ mã giảm giá.');
    }
}
