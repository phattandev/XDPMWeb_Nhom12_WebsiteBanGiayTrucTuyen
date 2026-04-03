@extends('layouts.app')

@section('content')
<div class="bg-slate-50 min-h-screen py-10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <h1 class="text-3xl font-bold mb-8 text-slate-800">
            🛒 Giỏ hàng của bạn
        </h1>

        @if(session('cart') && count(session('cart')) > 0)

        <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
            
            <table class="w-full text-left">
                <thead class="bg-slate-100 text-slate-600 text-sm uppercase">
                    <tr>
                        <th class="p-4">Sản phẩm</th>
                        <th class="p-4">Giá</th>
                        <th class="p-4 text-center">Số lượng</th>
                        <th class="p-4 text-right">Thành tiền</th>
                        <th class="p-4 text-center">Xóa</th>
                    </tr>
                </thead>

                <tbody>
                    @php $total = 0; @endphp

                    @foreach(session('cart') as $id => $item)
                        @php
                            $subtotal = $item['price'] * $item['quantity'];
                            $total += $subtotal;
                        @endphp

                        <tr class="border-t border-slate-200">
                            
                            {{-- Sản phẩm --}}
                            <td class="p-4 flex items-center gap-4">
                                <img src="{{ $item['image'] }}" class="w-16 h-16 object-cover rounded-lg border">

                                <a href="{{ route('shoes.show', $id) }}" 
                                   class="font-semibold text-slate-800 hover:text-orange-600">
                                    {{ $item['name'] }}
                                </a>
                            </td>

                            {{-- Giá --}}
                            <td class="p-4 font-medium text-slate-700">
                                {{ number_format($item['price'], 0, ',', '.') }} ₫
                            </td>

                            {{-- Số lượng --}}
                            <td class="p-4 text-center">
                                <div class="flex justify-center items-center gap-2">

                                    {{-- Giảm --}}
                                    <form action="{{ route('cart.update') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="id" value="{{ $id }}">
                                        <input type="hidden" name="quantity" value="{{ $item['quantity'] - 1 }}">
                                        <button class="px-3 py-1 border rounded hover:bg-slate-100">-</button>
                                    </form>

                                    <span class="px-4 font-bold">
                                        {{ $item['quantity'] }}
                                    </span>

                                    {{-- Tăng --}}
                                    <form action="{{ route('cart.update') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="id" value="{{ $id }}">
                                        <input type="hidden" name="quantity" value="{{ $item['quantity'] + 1 }}">
                                        <button class="px-3 py-1 border rounded hover:bg-slate-100">+</button>
                                    </form>

                                </div>
                            </td>

                            {{-- Thành tiền --}}
                            <td class="p-4 text-right font-bold text-orange-600">
                                {{ number_format($subtotal, 0, ',', '.') }} ₫
                            </td>

                            {{-- Xóa --}}
                            <td class="p-4 text-center">
                                <form action="{{ route('cart.remove') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="id" value="{{ $id }}">
                                    <button class="text-red-500 hover:underline">
                                        Xóa
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach

                </tbody>
            </table>
        </div>

        {{-- Tổng tiền --}}
        <div class="flex justify-end mt-6">
            <div class="bg-white p-6 rounded-xl shadow-sm border border-slate-200 w-full max-w-md">
                <div class="flex justify-between text-lg font-bold">
                    <span>Tổng tiền:</span>
                    <span class="text-orange-600">
                        {{ number_format($total, 0, ',', '.') }} ₫
                    </span>
                </div>

                <form action="{{ route('momo.payment') }}" method="POST">
    @csrf
    <button type="submit" style="background-color: #ae2070; color: white;" class="w-full mt-4 font-bold py-3 px-4 rounded-lg shadow-md hover:opacity-80 transition-opacity flex items-center justify-center gap-2">
    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" viewBox="0 0 16 16">
        <path d="M4 10a1 1 0 0 1 2 0v2a1 1 0 0 1-2 0v-2zm3 0a1 1 0 0 1 2 0v2a1 1 0 0 1-2 0v-2zm3 0a1 1 0 0 1 2 0v2a1 1 0 0 1-2 0v-2z"/>
        <path d="M5.5 7a.5.5 0 0 0 0 1h5a.5.5 0 0 0 0-1h-5zM2 1a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V3a2 2 0 0 0-2-2H2zm0 1h12a1 1 0 0 1 1 1v10a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1V3a1 1 0 0 1 1-1z"/>
    </svg>
    Thanh toán qua MoMo
</button>
</form>
            </div>
        </div>

        @else

        <div class="text-center py-20 bg-white rounded-xl border">
            <p class="text-slate-500 text-lg">Giỏ hàng của bạn đang trống !</p>
            <a href="{{ route('shoes.index') }}" 
               class="inline-block mt-4 bg-orange-600 text-white px-6 py-3 rounded-lg hover:bg-orange-700">
                Mua sắm ngay
            </a>
        </div>

        @endif

    </div>
</div>
@endsection