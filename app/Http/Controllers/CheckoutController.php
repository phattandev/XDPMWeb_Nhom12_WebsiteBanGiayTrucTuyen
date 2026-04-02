<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class CheckoutController extends Controller
{
    public function index()
    {
        if (!session()->has('cart') || count(session('cart')) == 0) {
            return redirect()->route('shoes.index')->with('error', 'Giỏ hàng của bạn đang trống!');
        }
        return view('frontend.checkout.index');
    }
    public function process(Request $request)
    {
        // 1. Validate form thanh toán
        $request->validate([
            'ten_nguoi_nhan' => 'required|string|max:255',
            'so_dien_thoai' => 'required|string|max:20',
            'dia_chi_chi_tiet' => 'required|string',
            'phuong_thuc_thanh_toan' => 'required|in:cod,vnpay'
        ]);

        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->route('home');
        }

        // 2. Tính tổng tiền thực tế từ Giỏ hàng
        $tongTien = 0;
        foreach ($cart as $item) {
            $tongTien += ($item['price'] * $item['quantity']);
        }

        $diaChiDayDu = $request->ten_nguoi_nhan . ' - ' . $request->so_dien_thoai . ' - ' . $request->dia_chi_chi_tiet;

        // Dùng Database Transaction: Đảm bảo nếu lỗi ở order_details thì không lưu order
        DB::beginTransaction();
        try {
            // 3. Lưu vào bảng orders
            $donHangId = DB::table('orders')->insertGetId([
                'user_id'          => Auth::id(), // Lấy ID khách hàng đang đăng nhập
                'total_amount'     => $tongTien,
                'shipping_address' => $diaChiDayDu, 
                'payment_method'   => $request->phuong_thuc_thanh_toan == 'cod' ? 'COD' : 'VNPay',
                'status'           => 'Pending', 
                'order_date'       => now(),
            ]);

            // 4. Lưu từng sản phẩm vào bảng order_details
            foreach ($cart as $variantId => $item) {
                DB::table('order_details')->insert([
                    'order_id' => $donHangId,
                    'shoe_variant_id' => $variantId,
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['price'],
                ]);

                // (Tùy chọn) Trừ số lượng tồn kho trong bảng shoe_variants
                DB::table('shoe_variants')->where('id', $variantId)->decrement('stock_quantity', $item['quantity']);
            }

            DB::commit(); // Xác nhận lưu thành công

            // 5. Xóa giỏ hàng sau khi đặt thành công
            session()->forget('cart');

            return view('frontend.checkout.success', ['orderId' => $donHangId]);

        } catch (\Exception $e) {
            DB::rollBack(); // Hủy bỏ thao tác nếu có lỗi
            return back()->with('error', 'Có lỗi xảy ra trong quá trình đặt hàng: ' . $e->getMessage());
        }
    }
}
