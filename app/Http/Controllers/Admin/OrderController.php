<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    // Hàm 1: Xem chi tiết đơn hàng
    public function show($id)
    {
        // Lấy thông tin của 1 đơn hàng dựa vào ID
        $order = DB::table('orders')->where('id', $id)->first();
        
        // Trả về giao diện chi tiết (mình sẽ tạo ở Bước 4)
        return view('admin.orders.show', compact('order'));
    }

    // Hàm 2: Duyệt đơn hàng (Đổi trạng thái)
    public function approve($id)
    {
        // Đổi trạng thái từ 'Pending' sang 'Shipped' (Đã giao hàng)
        DB::table('orders')->where('id', $id)->update([
            'status' => 'Shipped'
        ]);

        // Quay lại trang danh sách và báo thành công
        return redirect()->back()->with('success', 'Đã duyệt đơn hàng #'.$id.' thành công!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
