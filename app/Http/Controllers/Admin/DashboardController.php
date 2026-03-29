<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Tính tổng doanh thu (Bỏ qua các đơn bị hủy)
        $revenue = DB::table('orders')->where('status', '!=', 'Cancelled')->sum('total_amount');
        
        // Đếm đơn hàng mới (Pending)
        $newOrders = DB::table('orders')->where('status', 'Pending')->count();
        
        // Đếm số khách hàng
        $customers = DB::table('users')->where('role', 'customer')->count();
        
        // Đếm số sản phẩm (Giày)
        $products = DB::table('shoes')->count();

        return view('admin.dashboard', compact('revenue', 'newOrders', 'customers', 'products'));
    }
}
