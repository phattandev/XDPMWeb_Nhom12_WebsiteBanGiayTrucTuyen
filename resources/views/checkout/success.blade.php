<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đặt hàng thành công</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        body { background-color: #f8f9fa; display: flex; align-items: center; justify-content: center; height: 100vh; }
        .success-card { background: white; padding: 50px; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); text-align: center; max-width: 500px; }
        .icon-circle { width: 80px; height: 80px; background: #28a745; color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 40px; margin: 0 auto 20px; }
    </style>
</head>
<body>

<div class="success-card">
    <div class="icon-circle">
        <i class="bi bi-check-lg"></i>
    </div>
    <h1 class="text-success fw-bold">Thành Công!</h1>
    <p class="fs-5 text-muted">Cảm ơn bạn đã mua sắm tại Shoe Store.</p>
    <div class="alert alert-light border my-4">
        Mã đơn hàng của bạn là: <strong>#{{ $orderId }}</strong>
    </div>
    <p class="small text-secondary">Chúng tôi sẽ sớm liên hệ với bạn để xác nhận đơn hàng.</p>
    <hr>
    <a href="/" class="btn btn-primary btn-lg px-5 mt-2">Quay lại trang chủ</a>
</div>

</body>
</html>