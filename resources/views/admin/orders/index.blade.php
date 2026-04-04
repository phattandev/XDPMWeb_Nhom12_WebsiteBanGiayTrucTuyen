@extends('layouts.admin')

@section('content')
<div class="mb-6">
    <div class="mb-6 flex flex-col md:flex-row md:justify-between md:items-center gap-4">
        <h1 class="text-2xl font-bold text-slate-800">Quản lý Đơn hàng</h1>
        
        <form method="GET" action="{{ route('admin.orders.index') }}" class="flex items-center">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Nhập Mã ĐH hoặc Tên khách..." 
                class="rounded-l-lg border-slate-300 focus:ring-orange-500 focus:border-orange-500 text-sm py-2 px-3 w-64">
            <button type="submit" class="bg-slate-800 hover:bg-slate-900 text-white px-4 py-2 text-sm rounded-r-lg border border-slate-800 transition">
                Tìm
            </button>
        </form>
    </div>
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
            @forelse($orders as $order)
            <tr class="hover:bg-slate-50">
                <td class="px-6 py-4 text-sm font-medium text-slate-900">#{{ $order->id }}</td>
                <td class="px-6 py-4 text-sm text-slate-500">{{ $order->user?->name ?? 'Khách vãng lai' }}</td>
                <td class="px-6 py-4 text-sm text-slate-500">{{ \Carbon\Carbon::parse($order->order_date)->format('d/m/Y H:i') }}</td>
                <td class="px-6 py-4 text-sm font-bold text-orange-600">{{ number_format($order->total_amount, 0, ',', '.') }}đ</td>
                <td class="px-6 py-4 text-center">
                    <span class="px-2 py-1 text-xs font-semibold rounded-full border {{ $order->status_badge_class }}">
                        {{ $order->status_label }}
                    </span>
                </td>
                <td class="px-6 py-4 text-right text-sm font-medium">
                    <a href="{{ route('admin.orders.show', $order->id) }}" class="text-orange-600 hover:text-orange-900 bg-orange-50 px-3 py-1.5 rounded-md">Xem chi tiết</a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="px-6 py-8 text-center text-slate-500">Chưa có đơn hàng nào.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    
    @if($orders->hasPages())
        <div class="p-4 border-t border-slate-200">
            {{ $orders->links() }}
        </div>
    @endif
</div>
@endsection
