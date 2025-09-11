<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\OrderAdminController;

// Trang chủ
Route::get('/', [ProductController::class, 'index'])->name('home');

// Chi tiết sản phẩm
Route::get('/product/{slug}', [App\Http\Controllers\ProductController::class, 'show'])->name('product.show');


// Giỏ hàng
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add/{id}', [CartController::class, 'add'])->name('cart.add');
Route::post('/cart/update/{id}', [CartController::class, 'update'])->name('cart.update');
Route::get('/cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');

// Checkout
Route::get('/checkout', [OrderController::class, 'checkout'])->name('checkout');
Route::post('/checkout', [OrderController::class, 'store'])->name('checkout.store');


//Backend
Route::prefix('admin')->name('admin.')->group(function () {

    // Trang mặc định khi vào /admin
    Route::get('/', function () {
        return redirect()->route('admin.products.index');
    })->name('dashboard');

    // CRUD sản phẩm
    Route::resource('products', App\Http\Controllers\Admin\ProductController::class);

    // Quản lý đơn hàng
    Route::get('/orders', [App\Http\Controllers\Admin\OrderAdminController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [App\Http\Controllers\Admin\OrderAdminController::class, 'show'])->name('orders.show');
    Route::post('/orders/{order}/status', [App\Http\Controllers\Admin\OrderAdminController::class, 'updateStatus'])->name('orders.updateStatus');
});


// Auth routes
require __DIR__ . '/auth.php';
