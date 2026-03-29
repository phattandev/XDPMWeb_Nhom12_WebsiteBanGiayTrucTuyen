<!-- Trang thống kê tổng quan -->
 @extends('layouts.admin')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
    <div class="bg-white p-6 rounded-xl shadow-sm border-l-4 border-orange-500">
        <p class="text-sm text-slate-500 font-medium mb-1">Tổng doanh thu</p>
        <h3 class="text-2xl font-bold text-slate-800">{{ number_format($revenue, 0, ',', '.') }}đ</h3>
    </div>
    <div class="bg-white p-6 rounded-xl shadow-sm border-l-4 border-blue-500">
        <p class="text-sm text-slate-500 font-medium mb-1">Đơn hàng chờ duyệt</p>
        <h3 class="text-2xl font-bold text-slate-800">{{ $newOrders }}</h3>
    </div>
    <div class="bg-white p-6 rounded-xl shadow-sm border-l-4 border-green-500">
        <p class="text-sm text-slate-500 font-medium mb-1">Tổng khách hàng</p>
        <h3 class="text-2xl font-bold text-slate-800">{{ $customers }}</h3>
    </div>
    <div class="bg-white p-6 rounded-xl shadow-sm border-l-4 border-purple-500">
        <p class="text-sm text-slate-500 font-medium mb-1">Tổng sản phẩm</p>
        <h3 class="text-2xl font-bold text-slate-800">{{ $products }}</h3>
    </div>
</div>

<div class="bg-white p-8 rounded-xl shadow-sm border border-slate-100">
    <h3 class="text-xl font-bold text-slate-800 mb-2">Chào mừng trở lại!</h3>
    <p class="text-slate-600">Từ bảng điều khiển này, bạn có thể dễ dàng quản lý toàn bộ hoạt động của cửa hàng Shoe Store. Hãy chọn các chức năng bên menu trái để bắt đầu làm việc nhé.</p>
</div>
@endsection