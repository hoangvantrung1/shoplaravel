<?php
// app/Http/Controllers/WishlistController.php

namespace App\Http\Controllers;

use App\Models\Wishlist;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    public function index()
    {
        $wishlistItems = Auth::user()->wishlistProducts()
            ->with(['category', 'brand'])
            ->withCount('reviews')
            ->withAvg('reviews', 'rating')
            ->orderBy('wishlists.created_at', 'desc')
            ->paginate(12);

        return view('wishlist.index', compact('wishlistItems'));
    }

    public function store(Request $request, Product $product)
    {
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'Vui lòng đăng nhập để thêm sản phẩm yêu thích'
            ], 401);
        }

        // Kiểm tra xem sản phẩm đã có trong wishlist chưa
        $existingWishlist = Wishlist::where('user_id', Auth::id())
            ->where('product_id', $product->id)
            ->first();

        if ($existingWishlist) {
            $existingWishlist->delete();
            
            return response()->json([
                'success' => true,
                'action' => 'removed',
                'message' => 'Đã xóa khỏi danh sách yêu thích',
                'wishlist_count' => Auth::user()->wishlist_count
            ]);
        }

        // Thêm vào wishlist
        Wishlist::create([
            'user_id' => Auth::id(),
            'product_id' => $product->id
        ]);

        return response()->json([
            'success' => true,
            'action' => 'added',
            'message' => 'Đã thêm vào danh sách yêu thích',
            'wishlist_count' => Auth::user()->wishlist_count
        ]);
    }

    public function destroy(Product $product)
    {
        Wishlist::where('user_id', Auth::id())
            ->where('product_id', $product->id)
            ->delete();

        return response()->json([
            'success' => true,
            'message' => 'Đã xóa khỏi danh sách yêu thích',
            'wishlist_count' => Auth::user()->wishlist_count
        ]);
    }

    public function toggle(Product $product)
    {
        return $this->store(request(), $product);
    }
}