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

        if (isset($cart[$request->id])) {
            $cart[$request->id]['quantity']++;
        } else {
            $cart[$request->id] = [
                "id" => $product->id,
                "name" => $product->name,
                "quantity" => 1,
                "price" => $product->price,
                "image" => $product->image
            ];
        }

        session()->put('cart', $cart);
        return redirect()->back()->with('success', 'Sản phẩm đã được thêm vào giỏ hàng!');
    }

    // Cập nhật số lượng sản phẩm
    public function update(Request $request, $id)
    {
        $quantity = $request->quantity ?? 1;
        $cart = session()->get('cart', []);

        if (isset($cart[$id])) {
            $cart[$id]['quantity'] = max(1, $quantity);
            session(['cart' => $cart]);
        }

        return redirect()->back()->with('success', 'Cập nhật giỏ hàng thành công!');
    }

    // Xóa sản phẩm khỏi giỏ
    public function remove($id)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$id])) {
            unset($cart[$id]);
            session(['cart' => $cart]);
        }

        return redirect()->back()->with('success', 'Đã xóa sản phẩm!');
    }
}
