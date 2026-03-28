<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; // Thêm thư viện này ở đầu file

class CheckoutController extends Controller
{
   public function index()
    {
        return view('checkout.index'); 
    }
    public function process(Request $request)
    {
        // 1. Lấy thông tin từ form
        $tenNguoiNhan = $request->input('ten_nguoi_nhan');
        $sdt = $request->input('so_dien_thoai');
        $diaChi = $request->input('dia_chi_chi_tiet');
        $phuongThuc = $request->input('phuong_thuc_thanh_toan');

        // (Tạm thời giả lập Tổng tiền và ID người dùng đăng nhập)
        $tongTien = 500000; 
        $nguoiDungId = 2; // Ví dụ id=2 là tài khoản maleeha@gmail.com

        // 2. Lưu vào bảng orders theo đúng cấu trúc ảnh phpMyAdmin
        $donHangId = DB::table('orders')->insertGetId([
            'user_id'          => $nguoiDungId,
            'total_amount'     => $tongTien,      // Đúng tên cột số 3
            'shipping_address' => $diaChi,        // Đúng tên cột số 4 (Lấy từ form)
            'payment_method'   => $phuongThuc == 'cod' ? 'COD' : 'Bank Transfer', // Khớp với enum trong ảnh
            'status'           => 'Pending',      // Khớp với mặc định "Pending" trong ảnh
            'order_date'       => now(),          // Đúng tên cột số 7
        ]);

        // trả về trang đặt hàng thành công với mã đơn hàng
return view('checkout.success', ['orderId' => $donHangId]);

        // 3. Lưu địa chỉ giao hàng
        DB::table('diachigiaohang')->insert([
            'nguoi_dung_id' => $nguoiDungId,
            'ten_nguoi_nhan' => $tenNguoiNhan,
            'so_dien_thoai' => $sdt,
            'dia_chi_chi_tiet' => $diaChi,
        ]);

        // (Phần lưu Chi tiết đơn hàng mình sẽ làm sau khi bạn xử lý xong Giỏ hàng)

        return "Đặt hàng thành công! Mã đơn hàng của bạn là: " . $donHangId;
    }
}
