<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order; // Quan trọng: Phải gọi Model Order vào đây

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with('user');

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where('id', $search) // Tìm theo Mã đơn hàng chính xác
                  ->orWhereHas('user', function ($q) use ($search) {
                      $q->where('name', 'like', '%' . $search . '%'); // Hoặc tìm theo tên user
                  });
        }

        $orders = $query->orderBy('order_date', 'desc')->paginate(15)->appends($request->query());
        return view('admin.orders.index', compact('orders'));
    }

    // Xem chi tiết đơn hàng
    public function show($id)
    {
        $order = Order::with(['user', 'details.variant.shoe'])->findOrFail($id);
        return view('admin.orders.showorder', compact('order'));
    }

    // Cập nhật trạng thái đơn hàng
    public function updateStatus(Request $request, $id)
    {
        $request->validate(['status' => 'required|in:Pending,Processing,Shipped,Delivered,Cancelled']);
        
        $order = Order::findOrFail($id);
        $order->status = $request->status;
        
        // Nếu hủy đơn thì có thể viết code cộng lại số lượng tồn kho ở đây
        
        $order->save();
        
        return back()->with('success', 'Đã cập nhật trạng thái đơn hàng thành: ' . $request->status);
    }
}