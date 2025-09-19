<?php

use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\Client\AuthController as ClientAuthController;
use App\Http\Controllers\OrderController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\OrderAdminController;

/*
|--------------------------------------------------------------------------
| Client Routes
|--------------------------------------------------------------------------
*/

Route::get('/', [ProductController::class, 'index'])->name('home');
// Alias /search -> products.index
Route::get('/search', [ProductController::class, 'index'])->name('search');

// Login / Logout
Route::get('/login', [ClientAuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [ClientAuthController::class, 'login'])->name('login.submit');
Route::post('/logout', [ClientAuthController::class, 'logout'])->name('client.logout');

// Register
Route::get('/register', [ClientAuthController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [ClientAuthController::class, 'register'])->name('register.submit');


// Cart
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add/{id}', [CartController::class, 'add'])->name('cart.add');
Route::post('/cart/update/{id}', [CartController::class, 'update'])->name('cart.update');
Route::post('/cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');
Route::post('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');

// Product detail
Route::get('/product/{id}', [ProductController::class, 'show'])->name('product.show');
Route::get('/products', [ProductController::class, 'index'])->name('products.index');


// Checkout
Route::get('/checkout', [CheckoutController::class, 'show'])->name('checkout');
Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
Route::get('/checkout/vnpay/return', [CheckoutController::class, 'vnpayReturn'])->name('checkout.vnpay.return');


Route::middleware('auth')->group(function () {
    Route::get('/my-orders', [OrderController::class, 'index'])->name('client.orders.index');
    Route::get('/my-orders/{order}', [OrderController::class, 'show'])->name('client.orders.show');
});
/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/

Route::prefix('admin')->name('admin.')->group(function () {
    // Admin login/logout
    Route::get('/login', [AdminAuthController::class, 'showLoginForm'])->name('admin.login.post');
    Route::post('/login', [AdminAuthController::class, 'login'])->name('login.post');
    Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');

    // Protected admin routes (guard 'admin')
    Route::middleware(['auth:admin', \App\Http\Middleware\AdminMiddleware::class])->group(function () {
        // Dashboard
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

        // Quản lý users
        Route::resource('users', \App\Http\Controllers\Admin\AdminUserController::class);

        // Quản lý sản phẩm
        Route::resource('products', AdminProductController::class);

        // Quản lý đơn hàng
        Route::get('/orders', [OrderAdminController::class, 'index'])->name('orders.index');
        Route::get('/orders/{order}', [OrderAdminController::class, 'show'])->name('orders.show');
        Route::post('/orders/{order}/status', [OrderAdminController::class, 'updateStatus'])->name('orders.updateStatus');
    });
});
