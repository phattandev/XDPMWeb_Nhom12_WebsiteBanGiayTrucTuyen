@extends('layouts.admin')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-slate-800">Quản lý Đơn hàng</h1>
</div>

<div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
    <table class="min-w-full divide-y divide-slate-200">
        <thead class="bg-slate-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase">Mã ĐH</th>
                <th class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase">Khách hàng</th>
                <th class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase">Ngày đặt</th>
                <th class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase">Tổng tiền</th>
                <th class="px-6 py-3 text-center text-xs font-bold text-slate-500 uppercase">Trạng thái</th>
                <th class="px-6 py-3 text-right text-xs font-bold text-slate-500 uppercase">Thao tác</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-200">
            @foreach($orders as $order)
            <tr class="hover:bg-slate-50">
                <td class="px-6 py-4 text-sm font-medium text-slate-900">#{{ $order->id }}</td>
                <td class="px-6 py-4 text-sm text-slate-500">{{ $order->user->name ?? 'Khách vãng lai' }}</td>
                <td class="px-6 py-4 text-sm text-slate-500">{{ \Carbon\Carbon::parse($order->order_date)->format('d/m/Y H:i') }}</td>
                <td class="px-6 py-4 text-sm font-bold text-orange-600">{{ number_format($order->total_amount, 0, ',', '.') }}đ</td>
                <td class="px-6 py-4 text-center">
                    <span class="px-2 py-1 text-xs font-semibold rounded-full border 
                        {{ $order->status == 'Pending' ? 'bg-yellow-100 text-yellow-800 border-yellow-200' : '' }}
                        {{ $order->status == 'Processing' ? 'bg-blue-100 text-blue-800 border-blue-200' : '' }}
                        {{ $order->status == 'Shipped' ? 'bg-indigo-100 text-indigo-800 border-indigo-200' : '' }}
                        {{ $order->status == 'Delivered' ? 'bg-green-100 text-green-800 border-green-200' : '' }}
                        {{ $order->status == 'Cancelled' ? 'bg-red-100 text-red-800 border-red-200' : '' }}">
                        {{ $order->status }}
                    </span>
                </td>
                <td class="px-6 py-4 text-right text-sm font-medium">
                    <a href="{{ route('admin.orders.show', $order->id) }}" class="text-orange-600 hover:text-orange-900 bg-orange-50 px-3 py-1.5 rounded-md">Xem chi tiết</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    
    @if($orders->hasPages())
        <div class="p-4 border-t border-slate-200">
            {{ $orders->links() }}
        </div>
    @endif
</div>
@endsection