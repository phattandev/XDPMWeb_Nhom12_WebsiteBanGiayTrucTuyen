@extends('layouts.admin')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div>
        <a href="{{ route('admin.orders.index') }}" class="text-slate-500 hover:text-orange-600 transition mb-2 inline-block">← Quay lại</a>
        <h1 class="text-2xl font-bold text-slate-800">Chi tiết đơn hàng #{{ $order->id }}</h1>
    </div>
    
    <form action="{{ route('admin.orders.updateStatus', $order->id) }}" method="POST" class="flex gap-2">
        @csrf
        <select name="status" class="rounded-lg border-slate-300 text-sm focus:ring-orange-500">
            <option value="Pending" {{ $order->status == 'Pending' ? 'selected' : '' }}>Chờ duyệt (Pending)</option>
            <option value="Processing" {{ $order->status == 'Processing' ? 'selected' : '' }}>Đang xử lý (Processing)</option>
            <option value="Shipped" {{ $order->status == 'Shipped' ? 'selected' : '' }}>Đang giao (Shipped)</option>
            <option value="Delivered" {{ $order->status == 'Delivered' ? 'selected' : '' }}>Hoàn thành (Delivered)</option>
            <option value="Cancelled" {{ $order->status == 'Cancelled' ? 'selected' : '' }}>Đã hủy (Cancelled)</option>
        </select>
        <button type="submit" class="bg-orange-600 text-white px-4 py-2 rounded-lg text-sm font-bold hover:bg-orange-700">Cập nhật</button>
    </form>
</div>

@if(session('success'))
    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-r-lg">
        <p>{{ session('success') }}</p>
    </div>
@endif

<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    <div class="bg-white p-6 rounded-xl shadow-sm border border-slate-200">
        <h3 class="font-bold text-lg border-b pb-2 mb-4">Thông tin giao hàng</h3>
        <p class="mb-2"><span class="text-slate-500">Tên KH:</span> {{ $order->user?->name ?? 'Khách vãng lai' }}</p>
        <p class="mb-2"><span class="text-slate-500">Địa chỉ:</span> {{ $order->shipping_address }}</p>
        <p class="mb-2"><span class="text-slate-500">Ngày đặt:</span> {{ \Carbon\Carbon::parse($order->order_date)->format('d/m/Y H:i') }}</p>
        <p><span class="text-slate-500">Thanh toán:</span> <strong class="text-orange-600">{{ $order->payment_method }}</strong></p>
    </div>

    <div class="md:col-span-2 bg-white p-6 rounded-xl shadow-sm border border-slate-200">
        <h3 class="font-bold text-lg border-b pb-2 mb-4">Sản phẩm đã đặt</h3>
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-slate-50 text-slate-500 text-sm">
                    <tr>
                        <th class="p-3">Sản phẩm</th>
                        <th class="p-3 text-center">Phân loại</th>
                        <th class="p-3 text-center">SL</th>
                        <th class="p-3 text-right">Đơn giá</th>
                        <th class="p-3 text-right">Thành tiền</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($order->details as $item)
                    <tr>
                        <td class="p-3 font-medium">{{ $item->variant->shoe->name ?? 'Sản phẩm đã bị xóa' }}</td>
                        <td class="p-3 text-center text-sm text-slate-500">Size {{ $item->variant->size ?? 'N/A' }} | {{ $item->variant->color ?? 'N/A' }}</td>
                        <td class="p-3 text-center">{{ $item->quantity }}</td>
                        <td class="p-3 text-right">{{ number_format($item->unit_price, 0, ',', '.') }}đ</td>
                        <td class="p-3 text-right font-bold text-orange-600">{{ number_format($item->quantity * $item->unit_price, 0, ',', '.') }}đ</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-6 text-right text-xl">
            Tổng cộng: <span class="font-black text-orange-600 text-2xl">{{ number_format($order->total_amount, 0, ',', '.') }} VNĐ</span>
        </div>
    </div>
</div>
@endsection