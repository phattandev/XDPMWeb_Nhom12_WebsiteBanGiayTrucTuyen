@extends('layouts.app')

@section('content')
<div class="bg-slate-50 min-h-screen py-10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <h1 class="text-3xl font-bold text-slate-900 mb-8">📦 Đơn hàng của tôi</h1>

        <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
            @if($orders->count() > 0)
                <table class="w-full text-left border-collapse">
                    <thead class="bg-slate-100 text-slate-600 text-sm uppercase">
                        <tr>
                            <th class="p-4 font-bold">Mã ĐH</th>
                            <th class="p-4 font-bold">Ngày đặt</th>
                            <th class="p-4 font-bold">Tổng tiền</th>
                            <th class="p-4 font-bold">Thanh toán</th>
                            <th class="p-4 font-bold text-center">Trạng thái</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200">
                        @foreach($orders as $order)
                        <tr class="hover:bg-slate-50 transition">
                            <td class="p-4 font-medium text-slate-800">
                                <a href="{{ route('my-orders.show', $order->id) }}" class="text-orange-600 hover:text-orange-700 hover:underline flex items-center gap-1">
                                    #{{ $order->id }}
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                                </a>
                            </td>
                            <td class="p-4 text-slate-600">{{ \Carbon\Carbon::parse($order->order_date)->format('d/m/Y H:i') }}</td>
                            <td class="p-4 font-bold text-orange-600">{{ number_format($order->total_amount, 0, ',', '.') }} ₫</td>
                            <td class="p-4 text-slate-600">{{ $order->payment_method }}</td>
                            <td class="p-4 text-center">
                                <span class="{{ $order->status_badge_class }} text-xs font-bold px-3 py-1 rounded-full border">{{ $order->status_label }}</span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="text-center py-16">
                    <p class="text-slate-500 text-lg mb-4">Bạn chưa có đơn hàng nào.</p>
                    <a href="{{ route('shoes.index') }}" class="bg-orange-600 text-white px-6 py-2.5 rounded-lg font-bold hover:bg-orange-700 transition">Tiếp tục mua sắm</a>
                </div>
            @endif
        </div>

    </div>
</div>
@endsection
