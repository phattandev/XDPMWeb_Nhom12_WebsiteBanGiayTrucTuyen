@extends('layouts.app')

@section('content')
<div class="bg-slate-50 min-h-screen py-16">
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-3xl shadow-sm border border-slate-200 p-8 md:p-12 text-center">
            <div class="w-20 h-20 rounded-full bg-green-100 text-green-600 flex items-center justify-center mx-auto text-4xl font-black">
                ✓
            </div>

            <p class="mt-6 text-sm font-semibold tracking-[0.2em] uppercase text-orange-600">Đặt hàng thành công</p>
            <h1 class="mt-3 text-3xl md:text-4xl font-black text-slate-900">Cảm ơn bạn đã mua sắm tại Shoe Store</h1>
            <p class="mt-4 text-slate-500 leading-relaxed">
                Đơn hàng của bạn đã được ghi nhận. Chúng tôi sẽ sớm liên hệ để xác nhận và chuẩn bị giao hàng.
            </p>

            <div class="mt-8 rounded-2xl border border-slate-200 bg-slate-50 px-6 py-5">
                <p class="text-sm text-slate-500">Mã đơn hàng của bạn</p>
                <p class="text-3xl font-black text-orange-600 mt-2">#{{ $orderId }}</p>
            </div>

            <div class="mt-8 flex flex-col sm:flex-row justify-center gap-3">
                <a href="{{ route('my-orders.show', $orderId) }}" class="bg-orange-600 hover:bg-orange-700 text-white font-bold py-3 px-6 rounded-xl transition">
                    Xem chi tiết đơn hàng
                </a>
                <a href="{{ route('home') }}" class="bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold py-3 px-6 rounded-xl transition">
                    Quay lại trang chủ
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
