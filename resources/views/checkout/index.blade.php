<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thanh Toán Đơn Hàng</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; }
        .checkout-form { max-width: 600px; margin: 50px auto; background: #fff; padding: 30px; border-radius: 10px; box-shadow: 0 0 15px rgba(0,0,0,0.1); }
    </style>
</head>
<body>

<div class="container">
    <div class="checkout-form">
        <h2 class="text-center mb-4 text-primary">Thông tin giao hàng</h2>
        
        <form action="{{ route('checkout.process') }}" method="POST">
            @csrf
            
            <div class="mb-3">
                <label class="form-label fw-bold">Tên người nhận</label>
                <input type="text" name="ten_nguoi_nhan" class="form-control" placeholder="Nhập họ và tên" required>
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold">Số điện thoại</label>
                <input type="text" name="so_dien_thoai" class="form-control" placeholder="090..." required>
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold">Địa chỉ chi tiết</label>
                <textarea name="dia_chi_chi_tiet" class="form-control" rows="3" placeholder="Số nhà, tên đường, phường/xã..." required></textarea>
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold">Phương thức thanh toán</label>
                <select name="phuong_thuc_thanh_toan" class="form-select">
                    <option value="cod">Thanh toán khi nhận hàng (COD)</option>
                    <option value="vnpay">Thanh toán qua VNPay</option>
                </select>
            </div>

            <hr class="my-4">

            <button type="submit" class="btn btn-primary btn-lg w-100">Xác nhận đặt hàng</button>
        </form>
        
        <div class="text-center mt-3">
            <a href="/" class="text-decoration-none text-muted">← Quay lại trang chủ</a>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>