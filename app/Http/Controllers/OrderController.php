<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    // Hàm hiển thị danh sách
    public function index()
    {
        $orders = DB::table('orders')->orderBy('order_date', 'desc')->get();
        return view('admin.orders.index', compact('orders'));
    }

    // Hàm Xem chi tiết phải nằm ngang hàng với index
    public function show($id)
    {
        $order = DB::table('orders')->where('id', $id)->first();
        return view('admin.orders.showorder', compact('order'));
    }

    // Hàm Duyệt đơn cũng phải nằm ngang hàng, bên TRONG class
    public function approve($id)
    {
        DB::table('orders')->where('id', $id)->update([
            'status' => 'Shipped'
        ]);
        return redirect()->back()->with('success', 'Đã duyệt đơn hàng #'.$id.' thành công!');
    }
}