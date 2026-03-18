<?php

use Illuminate\Support\Facades\Route;
use App\Models\User;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;

/*TRANG CÔNG KHAI (Public Routes): Bất kỳ ai cũng có thể truy cập*/

// Trang chủ (Banner, danh mục nổi bật)
Route::get('/', [HomeController::class, 'index'])->name('home');

// Trang liên hệ (Form, Google Map, Chính sách)
Route::get('/contact', [ContactController::class, 'index'])->name('contact.index');
Route::post('/contact', [ContactController::class, 'send'])->name('contact.send');

// Trang danh sách sản phẩm (Bộ lọc, sắp xếp)
Route::get('/products', [ProductController::class, 'index'])->name('products.index');

// Trang chi tiết sản phẩm (Hình ảnh, giá, đánh giá, sp liên quan)
// Sử dụng 'slug' để URL thân thiện với SEO thay vì dùng ID
Route::get('/products/{slug}', [ProductController::class, 'show'])->name('products.show');

// Trang Giỏ hàng (Session/Cookie - Không cần đăng nhập vẫn thêm được vào giỏ)
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::put('/cart/update/{id}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');


/*XÁC THỰC NGƯỜI DÙNG (Authentication Routes): Đăng nhập, Đăng ký, Quên mật khẩu*/
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'processLogin']);
    
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'processRegister']);
    
    Route::get('/forgot-password', [AuthController::class, 'showForgotPassword'])->name('password.request');
});
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');


/*TÀI KHOẢN KHÁCH & THANH TOÁN (Customer & Checkout Routes): Phải đăng nhập mới được truy cập (Middleware: auth)*/
Route::middleware('auth')->group(function () {
    
    // Trang Tài khoản
    Route::prefix('account')->name('account.')->group(function () {
        Route::get('/', [AccountController::class, 'profile'])->name('profile'); // Thông tin cá nhân
        Route::put('/update', [AccountController::class, 'updateProfile'])->name('profile.update');
        
        Route::get('/addresses', [AccountController::class, 'addresses'])->name('addresses'); // Địa chỉ giao hàng
        Route::get('/orders', [AccountController::class, 'orders'])->name('orders'); // Lịch sử đơn hàng
        Route::put('/password', [AccountController::class, 'updatePassword'])->name('password'); // Đổi mật khẩu
    });

    // Trang Thanh toán & Xác nhận đơn hàng
    Route::prefix('checkout')->name('checkout.')->group(function () {
        Route::get('/', [CheckoutController::class, 'index'])->name('index'); // View thông tin mua hàng, chọn PT thanh toán
        Route::post('/process', [CheckoutController::class, 'process'])->name('process'); // Xử lý tạo đơn
        Route::get('/success/{order}', [CheckoutController::class, 'success'])->name('success'); // Trang báo thành công
    });
});


/*Admin Routes: Đăng nhập VÀ có quyền 'admin' (Middleware custom: admin)*/
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    
    // Dashboard tổng quan
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    
    // Quản lý Sản phẩm (Sử dụng Resource Route để tự động tạo các route CRUD)
    Route::resource('products', AdminProductController::class);
    
    // Quản lý Đơn hàng
    Route::resource('orders', AdminOrderController::class);
    
    // Quản lý Khách hàng
    Route::resource('users', \App\Http\Controllers\Admin\UserController::class);
    
    // Quản lý Khuyến mãi
    Route::resource('coupons', \App\Http\Controllers\Admin\CouponController::class);
});