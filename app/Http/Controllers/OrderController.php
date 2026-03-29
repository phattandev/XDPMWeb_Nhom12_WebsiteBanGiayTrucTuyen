<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with('user')->orderBy('order_date', 'desc')->paginate(15);
        return view('admin.orders.index', compact('orders'));
    }

    public function show($id)
    {
        $order = Order::with(['user', 'details.variant.shoe'])->findOrFail($id);
        return view('admin.orders.showorder', compact('order'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate(['status' => 'required|in:Pending,Processing,Shipped,Delivered,Cancelled']);
        
        $order = Order::findOrFail($id);
        $order->status = $request->status;
        
        // Nếu hủy đơn thì có thể viết code cộng lại số lượng tồn kho ở đây
        
        $order->save();

        return back()->with('success', 'Đã cập nhật trạng thái đơn hàng thành: ' . $request->status);
    }

    public function myOrderDetails($id)
    {
        // Lấy đơn hàng, kèm theo chi tiết sản phẩm, biến thể, thông tin giày và hình ảnh
        // Quan trọng: Phải có where('user_id', Auth::id()) để bảo mật, tránh khách này xem đơn khách khác
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