<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ShoeController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentController;

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
    Route::get('/checkout', [OrderController::class, 'checkout'])->name('checkout');
    Route::post('/order/place', [OrderController::class, 'placeOrder'])->name('order.place');
    Route::get('/order/success/{id}', [OrderController::class, 'success'])->name('order.success');
    
    // Quản lý đơn hàng cá nhân
    Route::get('/my-orders', [OrderController::class, 'myOrders'])->name('my-orders');

    // Thanh toán online (Dành cho bảng Payments mới thêm)
    Route::get('/payment/process/{order_id}', [PaymentController::class, 'process'])->name('payment.process');
});

// ADMIN (Yêu cầu đăng nhập & Role là 'admin')
// Lưu ý: Bạn cần tạo một middleware 'admin' hoặc kiểm tra role trong constructor của Admin Controller
Route::prefix('admin')->middleware(['auth'])->group(function () {
    // Tạm thời comment lại hoặc tạo các controller này sau
    Route::get('/dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('admin.dashboard');
    // Route::resource('shoes', App\Http\Controllers\Admin\ShoeController::class);
    // Route::resource('categories', App\Http\Controllers\Admin\CategoryController::class);
    // Route::resource('orders', App\Http\Controllers\Admin\OrderController::class);
});