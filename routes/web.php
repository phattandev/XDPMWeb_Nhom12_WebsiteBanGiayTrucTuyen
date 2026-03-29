<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ShoeController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\ContactController;

// CÁC TRANG CÔNG KHAI (Ai cũng xem được)
Route::get('/', [HomeController::class, 'index'])->name('home');

// Danh sách giày và Chi tiết giày (Dùng ID thay cho Slug)
Route::get('/shoes', [ShoeController::class, 'index'])->name('shoes.index');
Route::get('/shoes/{id}', [ShoeController::class, 'show'])->name('shoes.show');

// XÁC THỰC TÀI KHOẢN (Đăng nhập/Đăng ký)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'processLogin']);
    
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'processRegister']);
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// KHÁCH HÀNG (Yêu cầu phải đăng nhập)

Route::middleware('auth')->group(function () {
    // Giỏ hàng
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
    Route::post('/cart/update', [CartController::class, 'update'])->name('cart.update');
    Route::post('/cart/remove', [CartController::class, 'remove'])->name('cart.remove');

    // Đặt hàng & Thanh toán
    // đơn hàng của customer
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    // Route hứng dữ liệu form POST lên
    Route::post('/checkout/process', [CheckoutController::class, 'process'])->name('checkout.process');
    Route::post('/order/place', [OrderController::class, 'placeOrder'])->name('order.place');
    Route::get('/order/success/{id}', [OrderController::class, 'success'])->name('order.success');
    
    // Quản lý đơn hàng cá nhân
    Route::get('/my-orders', [OrderController::class, 'myOrders'])->name('my-orders');
    Route::get('/my-orders/{id}', [OrderController::class, 'myOrderDetails'])->name('my-orders.show');
    // Thanh toán online (Dành cho bảng Payments mới thêm)
    Route::get('/payment/process/{order_id}', [PaymentController::class, 'process'])->name('payment.process');

    Route::get('/profile', [App\Http\Controllers\ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
    
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
    Route::post('/cart/update', [CartController::class, 'update'])->name('cart.update');
    Route::post('/cart/remove', [CartController::class, 'remove'])->name('cart.remove');

    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout/process', [CheckoutController::class, 'process'])->name('checkout.process');
    Route::get('/my-orders', [OrderController::class, 'myOrders'])->name('my-orders');
});

// ADMIN ROUTES (Đã chuẩn hóa)
Route::prefix('admin')->middleware(['auth'])->name('admin.')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
    
    // Quản lý Sản phẩm
    Route::resource('products', App\Http\Controllers\Admin\ProductController::class);
    
    // Quản lý Đơn hàng
    Route::get('/orders', [App\Http\Controllers\Admin\OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{id}', [App\Http\Controllers\Admin\OrderController::class, 'show'])->name('orders.show');
    Route::post('/orders/{id}/status', [App\Http\Controllers\Admin\OrderController::class, 'updateStatus'])->name('orders.updateStatus');

    Route::get('/contacts', [App\Http\Controllers\ContactController::class, 'index'])->name('contacts');
    Route::post('/contacts/{id}/read', [App\Http\Controllers\ContactController::class, 'markAsRead'])->name('contacts.read');

    // Quản lý Tài khoản (Thay thế cho đoạn customers cũ)
    Route::get('/users', [App\Http\Controllers\Admin\UserController::class, 'index'])->name('users.index');
    Route::delete('/users/{id}', [App\Http\Controllers\Admin\UserController::class, 'destroy'])->name('users.destroy');
});