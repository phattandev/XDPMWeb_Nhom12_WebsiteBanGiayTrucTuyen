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

    // Thanh toán online (Dành cho bảng Payments mới thêm)
    Route::get('/payment/process/{order_id}', [PaymentController::class, 'process'])->name('payment.process');
    
});

// ADMIN (Yêu cầu đăng nhập & Role là 'admin')
// Lưu ý: Bạn cần tạo một middleware 'admin' hoặc kiểm tra role trong constructor của Admin Controller
Route::prefix('admin')->middleware(['auth'])->group(function () {
    // Tạm thời comment lại hoặc tạo các controller này sau
    Route::get('/dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('admin.dashboard');
    // Route quản lý đơn hàng
    Route::get('/orders', [App\Http\Controllers\OrderController::class, 'index'])->name('admin.orders.index');
    // Route xem chi tiết đơn hàng
    Route::get('/orders/{id}', [App\Http\Controllers\OrderController::class, 'show'])->name('admin.orders.show');
   // Route bấm nút Duyệt đơn hàng (Dùng POST để bảo mật)
    Route::post('/orders/{id}/approve', [App\Http\Controllers\OrderController::class, 'approve'])->name('admin.orders.approve');
    // Route::resource('shoes', App\Http\Controllers\Admin\ShoeController::class);
    // Route::resource('categories', App\Http\Controllers\Admin\CategoryController::class);
    // Route::resource('orders', App\Http\Controllers\Admin\OrderController::class);
    
    // Route contact liên hệ (Admin xem danh sách contact)
    Route::get('/contacts', [App\Http\Controllers\ContactController::class, 'index'])->name('admin.contacts');
});

// Trang liên hệ
Route::get('/contact', function () {
    return view('contact');
})->name('contact');
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');

// Admin - Đánh dấu đã đọc
Route::post('/contacts/{id}/read', [App\Http\Controllers\ContactController::class, 'markAsRead'])->name('admin.contacts.read');