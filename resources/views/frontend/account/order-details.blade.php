@extends('layouts.app')

@section('content')
<div class="bg-slate-50 min-h-screen py-10">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="flex items-center justify-between mb-8">
            <div>
                <a href="{{ route('my-orders') }}" class="text-slate-500 hover:text-orange-600 font-medium transition flex items-center gap-2 mb-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Quay lại danh sách
                </a>
                <h1 class="text-3xl font-bold text-slate-900 flex items-center gap-3">
                    Chi tiết đơn hàng #{{ $order->id }}
                    @if($order->status == 'Pending')
                        <span class="bg-yellow-100 text-yellow-800 text-sm font-bold px-3 py-1 rounded-full border border-yellow-200">Đang chờ duyệt</span>
                    @elseif($order->status == 'Processing')
                        <span class="bg-blue-100 text-blue-800 text-sm font-bold px-3 py-1 rounded-full border border-blue-200">Đang xử lý</span>
                    @elseif($order->status == 'Shipped')
                        <span class="bg-indigo-100 text-indigo-800 text-sm font-bold px-3 py-1 rounded-full border border-indigo-200">Đang giao hàng</span>
                    @elseif($order->status == 'Delivered')
                        <span class="bg-green-100 text-green-800 text-sm font-bold px-3 py-1 rounded-full border border-green-200">Hoàn thành</span>
                    @else
                        <span class="bg-red-100 text-red-800 text-sm font-bold px-3 py-1 rounded-full border border-red-200">{{ $order->status }}</span>
                    @endif
                </h1>
            </div>
            <div class="text-right text-slate-500">
                <p>Ngày đặt hàng</p>
                <p class="font-bold text-slate-800">{{ \Carbon\Carbon::parse($order->order_date)->format('d/m/Y H:i') }}</p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
            <div class="md:col-span-2 bg-white rounded-xl shadow-sm border border-slate-200 p-6">
                <h3 class="font-bold text-lg text-slate-800 mb-4 flex items-center gap-2 border-b border-slate-100 pb-2">
                    <svg class="w-5 h-5 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    Địa chỉ nhận hàng
                </h3>
                <p class="text-slate-800 font-medium mb-1">{{ Auth::user()->name }}</p>
                <p class="text-slate-600 leading-relaxed">{{ $order->shipping_address }}</p>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
                <h3 class="font-bold text-lg text-slate-800 mb-4 flex items-center gap-2 border-b border-slate-100 pb-2">
                    <svg class="w-5 h-5 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                    Thanh toán
                </h3>
                <p class="text-slate-800 font-medium mb-1">{{ $order->payment_method == 'COD' ? 'Thanh toán khi nhận hàng (COD)' : $order->payment_method }}</p>
                <p class="text-sm text-slate-500 mt-2">Tổng cộng: <span class="font-bold text-orange-600 text-lg">{{ number_format($order->total_amount, 0, ',', '.') }} ₫</span></p>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
            <h3 class="font-bold text-lg text-slate-800 p-6 border-b border-slate-100 bg-slate-50">Sản phẩm đã mua</h3>
            
            <table class="w-full text-left border-collapse">
                <thead class="text-slate-500 text-sm border-b border-slate-200">
                    <tr>
                        <th class="p-4 font-medium">Sản phẩm</th>
                        <th class="p-4 font-medium text-center">Đơn giá</th>
                        <th class="p-4 font-medium text-center">Số lượng</th>
                        <th class="p-4 font-medium text-right">Thành tiền</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($order->details as $item)
                        @php
                            $shoe = $item->variant->shoe;
                            $primaryImage = $shoe->images->where('is_primary', true)->first() ?? $shoe->images->first();
                            $image = $primaryImage 
                                ? (str_starts_with($primaryImage->image_url, 'http') ? $primaryImage->image_url : asset('images/' . $primaryImage->image_url))
                                : 'https://via.placeholder.com/100';
                        @endphp
                    <tr class="hover:bg-slate-50 transition">
                        <td class="p-4 flex items-center gap-4">
                            <img src="{{ $image }}" class="w-20 h-20 object-cover rounded-lg border border-slate-200">
                            <div>
                                <a href="{{ route('shoes.show', $shoe->id) }}" class="font-bold text-slate-800 hover:text-orange-600 block mb-1">
                                    {{ $shoe->name }}
                                </a>
                                <span class="text-xs text-slate-500 bg-slate-100 px-2 py-1 rounded border border-slate-200">
                                    Size: {{ $item->variant->size }} | Màu: {{ $item->variant->color }}
                                </span>
                            </div>
                        </td>
                        <td class="p-4 text-center text-slate-600">
                            {{ number_format($item->unit_price, 0, ',', '.') }} ₫
                        </td>
                        <td class="p-4 text-center font-medium text-slate-800">
                            x{{ $item->quantity }}
                        </td>
                        <td class="p-4 text-right font-bold text-orange-600">
                            {{ number_format($item->unit_price * $item->quantity, 0, ',', '.') }} ₫
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

    </div>
</div>
@endsection