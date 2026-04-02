<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
        $orders = DB::table('orders')
            ->where('user_id', Auth::id())
            ->orderBy('order_date', 'desc')
            ->get();
            
        return view('frontend.account.orders', compact('orders'));
    }
}