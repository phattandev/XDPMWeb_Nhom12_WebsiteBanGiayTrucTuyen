<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Chi tiết đơn hàng</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <div class="card shadow">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h4 class="mb-0">Chi tiết đơn hàng #{{ $order->id }}</h4>
            <a href="{{ route('admin.orders.index') }}" class="btn btn-light btn-sm">Quay lại</a>
        </div>
        <div class="card-body">
            <p><strong>Khách hàng ID:</strong> {{ $order->user_id }}</p>
            <p><strong>Địa chỉ giao:</strong> {{ $order->shipping_address }}</p>
            <p><strong>Tổng tiền:</strong> <span class="text-danger fw-bold">{{ number_format($order->total_amount) }}đ</span></p>
            <p><strong>Phương thức:</strong> {{ $order->payment_method }}</p>
            <p><strong>Trạng thái:</strong> {{ $order->status }}</p>
            <p><strong>Ngày đặt:</strong> {{ $order->order_date }}</p>
            
            <hr>
            <h5 class="mt-4">Danh sách sản phẩm</h5>
            <p class="text-muted">(Tính năng liệt kê giày khách mua sẽ được thêm vào sau khi bạn làm bảng order_details).</p>
        </div>
    </div>
</div>
</body>
</html>