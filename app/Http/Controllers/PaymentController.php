<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
// ... (Bên trong class PaymentController)

public function momoPayment(Request $request)
{
    $request->validate([
        'ten_nguoi_nhan' => 'required|string|max:255',
        'so_dien_thoai' => 'required|string|max:20',
        'dia_chi_chi_tiet' => 'required|string',
    ]);

    // --- BƯỚC 1: LẤY GIỎ HÀNG TỪ SESSION VÀ TÍNH TỔNG TIỀN ---
    $cart = session()->get('cart', []);
    if (empty($cart)) {
        return back()->with('error', 'Giỏ hàng đang trống!');
    }

    $totalAmount = 10000; 
    foreach($cart as $item) {
        $totalAmount += $item['price'] * $item['quantity'];
    }

    $shippingAddress = $request->ten_nguoi_nhan . ' - ' . $request->so_dien_thoai . ' - ' . $request->dia_chi_chi_tiet;

    // --- BƯỚC 2: LƯU VÀO DATABASE (orders, order_details, payments) ---
    DB::beginTransaction();
    try {
        // 2.1 Tạo Đơn hàng (Bảng orders)
        $orderId = DB::table('orders')->insertGetId([
            'user_id' => Auth::id() ?? 1, // Lấy ID user đang đăng nhập (hoặc mặc định là 1 nếu chưa đăng nhập)
            'total_amount' => $totalAmount,
            'shipping_address' => $shippingAddress,
            'payment_method' => 'Momo',
            'status' => 'Pending',
            'order_date' => now()
        ]);

        // 2.2 Tạo Chi tiết đơn hàng (Bảng order_details)
        foreach($cart as $variantId => $item) {
            DB::table('order_details')->insert([
                'order_id' => $orderId,
                'shoe_variant_id' => $variantId, // ID của biến thể giày
                'quantity' => $item['quantity'],
                'unit_price' => $item['price']
            ]);
        }

        // 2.3 Tạo dữ liệu Thanh toán (Bảng payments) - Trạng thái chờ
        DB::table('payments')->insert([
            'order_id' => $orderId,
            'transaction_id' => null, // Sẽ cập nhật khi MoMo trả về thành công
            'amount' => $totalAmount,
            'payment_method' => 'Momo',
            'payment_status' => 'Pending',
            'payment_date' => now()
        ]);

        DB::commit(); // Lưu thành công tất cả vào DB

        // Xóa giỏ hàng sau khi đã tạo đơn (Tùy chọn, ông có thể dời dòng này sang hàm xử lý khi thanh toán thành công)
        // session()->forget('cart'); 

    } catch (\Exception $e) {
        DB::rollBack();
        return back()->with('error', 'Lỗi lưu đơn hàng: ' . $e->getMessage());
    }


    // --- BƯỚC 3: GỌI API MOMO ĐỂ LẤY MÃ QR ---
    $endpoint = env('MOMO_ENDPOINT');
    $partnerCode = env('MOMO_PARTNER_CODE');
    $accessKey = env('MOMO_ACCESS_KEY');
    $secretKey = env('MOMO_SECRET_KEY');

    $orderInfo = "Thanh toán đơn hàng Shoe Store #" . $orderId;
    $amountStr = (string) $totalAmount; // Ép kiểu chuỗi
    $momoOrderId = $orderId . "_" . time() . "_" . rand(100, 999);
    
    $redirectUrl = url('/thanh-toan-thanh-cong'); // Đổi lại thành route thật của web ông
    $ipnUrl = url('/api/momo-ipn'); 
    $extraData = "";
    $requestId = time() . "";
    $requestType = "captureWallet";

    // Băm chữ ký bảo mật
    $rawHash = "accessKey=".$accessKey."&amount=".$amountStr."&extraData=".$extraData."&ipnUrl=".$ipnUrl."&orderId=".$momoOrderId."&orderInfo=".$orderInfo."&partnerCode=".$partnerCode."&redirectUrl=".$redirectUrl."&requestId=".$requestId."&requestType=".$requestType;
    $signature = hash_hmac("sha256", $rawHash, $secretKey);

    $data = [
        'partnerCode' => $partnerCode,
        'partnerName' => "Shoe Store",
        "storeId" => "ShoeStore",
        'requestId' => $requestId,
        'amount' => $amountStr,
        'orderId' => $momoOrderId,
        'orderInfo' => $orderInfo,
        'redirectUrl' => $redirectUrl,
        'ipnUrl' => $ipnUrl,
        'lang' => 'vi',
        'extraData' => $extraData,
        'requestType' => $requestType,
        'signature' => $signature
    ];

    $response = Http::post($endpoint, $data);
    $result = $response->json();

    if (isset($result['payUrl'])) {
        return redirect($result['payUrl']); // Chuyển sang trang quét mã
    }

    return back()->with('error', 'Lỗi khởi tạo MoMo. Vui lòng kiểm tra lại cấu hình Key.');
}
public function momoReturn(Request $request)
    {
        // MoMo sẽ trả về một đống tham số trên URL, quan trọng nhất là resultCode
        // resultCode == 0 nghĩa là giao dịch thành công
        if ($request->resultCode == 0) {
            // Xóa giỏ hàng vì đã mua xong
            session()->forget('cart');
            
            // Cập nhật trạng thái đơn hàng thành "Đã thanh toán"
            // (Lấy orderId từ MoMo trả về, bỏ cái đuôi _time() đi)
            $orderIdStr = explode('_', $request->orderId)[0];
            
            DB::table('payments')
                ->where('order_id', $orderIdStr)
                ->update([
                    'payment_status' => 'Completed',
                    'transaction_id' => $request->transId, // Lưu lại mã giao dịch MoMo
                ]);
                
            DB::table('orders')
                ->where('id', $orderIdStr)
                ->update(['status' => 'Processing']); // Chuyển đơn sang đang xử lý

            // Chuyển hướng về trang chủ hoặc trang lịch sử đơn hàng kèm thông báo
            return redirect('/')->with('success', 'Thanh toán MoMo thành công! Đơn hàng của bạn đang được xử lý.');
        } else {
            // resultCode khác 0 là thất bại hoặc khách bấn hủy
            return redirect('/cart')->with('error', 'Giao dịch MoMo bị hủy hoặc thất bại.');
        }
    }
}
