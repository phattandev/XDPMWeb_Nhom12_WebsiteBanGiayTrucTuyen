<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    // Dành cho Customer xem chi tiết đơn hàng của mình
    public function myOrderDetails($id)
    {
        $order = Order::with(['details.variant.shoe.images'])
            ->where('user_id', Auth::id())
            ->findOrFail($id);
            
        return view('frontend.account.order-details', compact('order'));
    }

    // Dành cho Customer xem lịch sử mua hàng
    public function myOrders()
    {
        $orders = Order::query()
            ->where('user_id', Auth::id())
            ->orderBy('order_date', 'desc')
            ->get();
            
        return view('frontend.account.orders', compact('orders'));
    }
    public function index()
    {
    // Thêm with('payment') vào để nó lấy được trạng thái MoMo
    $orders = Order::with(['user', 'payment'])->orderBy('id', 'desc')->get();
    
    
    return view('admin.orders.showorder', compact('orders')); 
    }

    public function show($id)
{
    // Bắt buộc phải có with('payment')
    $order = Order::with(['details.variant.shoe', 'user', 'payment'])->findOrFail($id); 
    return view('admin.orders.showorder', compact('order'));
}
}
