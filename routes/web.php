<?php

use App\Http\Controllers\Admin\AdminProfileController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\Client\AuthController as ClientAuthController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\SocialController;
use App\Http\Controllers\CouponController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\WishlistController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\AddressController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\OrderAdminController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\BrandController as AdminBrandController;
use App\Http\Controllers\Admin\ReportController as AdminReportController;
use App\Http\Controllers\Admin\CouponController as AdminCouponController;

/*
|--------------------------------------------------------------------------
| Client Routes
|--------------------------------------------------------------------------
*/

Route::get('/', [ProductController::class, 'index'])->name('home');

// Products routes - FIXED: Đảm bảo route products.index xử lý tất cả các tham số
Route::get('/products', [ProductController::class, 'index'])->name('products.index');

// Search route - REMOVED: Không cần route riêng cho search vì đã tích hợp vào products.index
// Route::get('/search', [ProductController::class, 'index'])->name('search');

// Login / Logout
Route::get('/login', [ClientAuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [ClientAuthController::class, 'login'])->name('login.submit');
Route::post('/logout', [ClientAuthController::class, 'logout'])->name('client.logout');

// Register
Route::get('/register', [ClientAuthController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [ClientAuthController::class, 'register'])->name('register.submit');

// Password reset
Route::get('/forgot-password', [PasswordResetLinkController::class, 'create'])->name('password.request');
Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])->name('password.email');
Route::get('/reset-password/{token}', [NewPasswordController::class, 'create'])->name('password.reset');
Route::post('/reset-password', [NewPasswordController::class, 'store'])->name('password.update');

//chatbot
Route::post('/chat/send', [ChatController::class, 'store'])->name('chat.send');

// Social login
Route::get('/auth/google/redirect', [SocialController::class, 'redirectToGoogle'])->name('social.google.redirect');
Route::get('/auth/google/callback', [SocialController::class, 'handleGoogleCallback'])->name('social.google.callback');

// Cart
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add/{id}', [CartController::class, 'add'])->name('cart.add');
Route::post('/cart/update/{id}', [CartController::class, 'update'])->name('cart.update');
Route::post('/cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');
Route::post('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');
Route::post('/cart/coupon/apply', [CartController::class, 'applyCoupon'])->name('cart.coupon.apply');
Route::post('/cart/coupon/remove', [CartController::class, 'removeCoupon'])->name('cart.coupon.remove');

// Product detail
Route::get('/product/{id}', [ProductController::class, 'show'])->name('product.show');

Route::middleware('auth')->post('/product/{id}/reviews', [ReviewController::class, 'store'])->name('product.reviews.store');

// Checkout
Route::get('/checkout', [CheckoutController::class, 'show'])->name('checkout');
Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
Route::get('/checkout/vnpay/return', [CheckoutController::class, 'vnpayReturn'])->name('checkout.vnpay.return');
Route::post('/checkout/vnpay-ipn', [CheckoutController::class, 'vnpayIpn'])->name('checkout.vnpay.ipn');

Route::post('/cart/coupon/apply', [CouponController::class, 'apply'])->name('cart.coupon.apply');
Route::post('/cart/coupon/remove', [CouponController::class, 'remove'])->name('cart.coupon.remove');

// Trang Blog/Tin tức
Route::get('/blog', [PageController::class, 'blog'])->name('blog');
Route::get('/blog/{slug}', [PageController::class, 'blogDetail'])->name('blog.detail');

// Trang Liên hệ
Route::get('/contact', [PageController::class, 'contact'])->name('contact');
Route::post('/contact', [PageController::class, 'contactSubmit'])->name('contact.submit');


Route::middleware('auth')->group(function () {
    Route::get('/my-orders', [OrderController::class, 'index'])->name('client.orders.index');
    Route::get('/my-orders/{order}', [OrderController::class, 'show'])->name('client.orders.show');
    Route::put('/my-orders/{order}/cancel', [OrderController::class, 'cancel'])->name('client.orders.cancel');
    Route::post('/orders/{order}/repay', [OrderController::class, 'repay'])->name('client.orders.repay');

    Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist.index');
    Route::post('/wishlist/{product}/toggle', [WishlistController::class, 'toggle'])->name('wishlist.toggle');
    Route::post('/wishlist/{product}', [WishlistController::class, 'store'])->name('wishlist.store');
    Route::delete('/wishlist/{product}', [WishlistController::class, 'destroy'])->name('wishlist.destroy');
    // Profile
    Route::get('/profile', [\App\Http\Controllers\ProfileController::class, 'index'])->name('profile.index');
    Route::get('/profile/edit', [\App\Http\Controllers\ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile/update', [\App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');

    // Addresses
    Route::resource('addresses', AddressController::class)->except(['show']);
});

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/

Route::prefix('admin')->name('admin.')->group(function () {
    // Admin login/logout
    Route::get('/login', [AdminAuthController::class, 'showLoginForm'])->name('login'); // hiển thị form
    Route::post('/login', [AdminAuthController::class, 'login'])->name('login.post');  // xử lý đăng nhập
    Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');

    // Protected admin routes (guard 'admin')
    Route::middleware(['auth:admin', \App\Http\Middleware\AdminMiddleware::class])->group(function () {
        // Dashboard
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

        // Quản lý users
        Route::resource('users', \App\Http\Controllers\Admin\AdminUserController::class);

        //Quản lý admins
        Route::get('/admin-profile', [\App\Http\Controllers\Admin\AdminAdminController::class, 'show'])->name('admins.index');
        Route::get('/admin-profile/edit', [\App\Http\Controllers\Admin\AdminAdminController::class, 'edit'])->name('admins.edit');
        Route::put('/admin-profile/update', [\App\Http\Controllers\Admin\AdminAdminController::class, 'update'])->name('admins.update');

        // Quản lý sản phẩm
        Route::resource('products', AdminProductController::class);

        // Quản lý danh mục
        Route::resource('categories', AdminCategoryController::class);

        // Quản lý thương hiệu
        Route::resource('brands', AdminBrandController::class);

        Route::patch('/admin/orders/{order}/update-status', [OrderAdminController::class, 'updateStatus'])->name('admin.orders.update-status');

        // Quản lý đơn hàng
        Route::get('/orders', [OrderAdminController::class, 'index'])->name('orders.index');
        Route::get('/orders/{order}', [OrderAdminController::class, 'show'])->name('orders.show');
        Route::post('/orders/{order}/status', [OrderAdminController::class, 'updateStatus'])->name('orders.updateStatus');

        // Báo cáo
        Route::get('/reports/sales', [AdminReportController::class, 'sales'])->name('reports.sales');

        // Coupons
        Route::resource('coupons', AdminCouponController::class);

        // Admin profile routes - THÊM VÀO ĐÂY
        Route::get('/profile', [AdminProfileController::class, 'index'])->name('profile.index');
        Route::get('/profile/edit', [AdminProfileController::class, 'edit'])->name('profile.edit');
        Route::put('/profile/update', [AdminProfileController::class, 'update'])->name('profile.update');
    });
});