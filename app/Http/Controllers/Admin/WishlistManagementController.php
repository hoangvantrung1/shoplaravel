<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;

class WishlistManagementController extends Controller
{
    /**
     * Hiển thị danh sách sản phẩm yêu thích của người dùng
     */
    public function index()
    {
        // Lấy tất cả người dùng có sản phẩm yêu thích
        $users = User::whereHas('wishlistProducts')->withCount('wishlistProducts')->get();
        
        return view('admin.wishlist.index', compact('users'));
    }

    /**
     * Hiển thị chi tiết wishlist của một người dùng
     */
    public function show($userId)
    {
        $user = User::with('wishlistProducts')->findOrFail($userId);
        $wishlistProducts = $user->wishlistProducts()->paginate(10);

        return view('admin.wishlist.show', compact('user', 'wishlistProducts'));
    }

    /**
     * Xóa sản phẩm khỏi wishlist của người dùng
     */
    public function removeFromWishlist($userId, $productId)
    {
        $user = User::findOrFail($userId);
        $user->wishlistProducts()->detach($productId);

        return back()->with('success', 'Đã xóa sản phẩm khỏi wishlist!');
    }

    /**
     * Thống kê wishlist
     */
    public function statistics()
    {
        // Sản phẩm được yêu thích nhất
        $mostWishlistedProducts = Product::withCount('wishlistedBy')
                                        ->orderBy('wishlisted_by_count', 'desc')
                                        ->take(10)
                                        ->get();

        // Người dùng có nhiều sản phẩm yêu thích nhất
        $mostActiveUsers = User::withCount('wishlistProducts')
                              ->orderBy('wishlist_products_count', 'desc')
                              ->take(10)
                              ->get();

        return view('admin.wishlist.statistics', compact('mostWishlistedProducts', 'mostActiveUsers'));
    }
}