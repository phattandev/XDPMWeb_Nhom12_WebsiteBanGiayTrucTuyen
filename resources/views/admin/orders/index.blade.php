<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản lý đơn hàng - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="text-primary">Danh sách đơn hàng</h2>
        <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary">Về trang chủ</a>
    </div>

    <div class="card shadow">
        <div class="card-body">
            <table class="table table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Khách hàng (ID)</th>
                        <th>Tổng tiền</th>
                        <th>Địa chỉ</th>
                        <th>Thanh toán</th>
                        <th>Trạng thái</th>
                        <th>Ngày đặt</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orders as $order)
                    <tr>
                        <td>#{{ $order->id }}</td>
                        <td>User #{{ $order->user_id }}</td>
                        <td class="fw-bold text-danger">{{ number_format($order->total_amount) }}đ</td>
                        <td>{{ $order->shipping_address }}</td>
                        <td><span class="badge bg-info">{{ $order->payment_method }}</span></td>
                        <td>
                            <span class="badge {{ $order->status == 'Pending' ? 'bg-warning' : 'bg-success' }}">
                                {{ $order->status }}
                            </span>
                        </td>
                        <td>{{ $order->order_date }}</td>
                    <td>
                    <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-sm btn-primary">Xem</a>
                    @if($order->status == 'Pending')
                        <form action="{{ route('admin.orders.approve', $order->id) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-success" onclick="return confirm('Bạn có chắc muốn duyệt đơn này?')">Duyệt</button>
                        </form>
                    @else
                        <button class="btn btn-sm btn-secondary" disabled>Đã duyệt</button>
                    @endif
                </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
</body>
</html>