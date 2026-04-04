@extends('layouts.app')

@section('content')
@php
    $user = $user ?? auth()->user();
    $cart = $cart ?? session()->get('cart', []);
    $subtotal = $subtotal ?? collect($cart)->sum(fn ($item) => ($item['price'] ?? 0) * ($item['quantity'] ?? 0));
    $totalQuantity = $totalQuantity ?? collect($cart)->sum(fn ($item) => $item['quantity'] ?? 0);
    $selectedPaymentMethod = old('phuong_thuc_thanh_toan', 'cod');
    $prefillName = old('ten_nguoi_nhan', $user->name ?? '');
    $prefillPhone = old('so_dien_thoai', $user->phone ?? '');
    $prefillAddress = old('dia_chi_chi_tiet', $user->address ?? '');
@endphp

<div class="bg-slate-50 min-h-screen py-10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center gap-3 mb-8">
            <a href="{{ route('cart.index') }}" class="text-slate-500 hover:text-orange-600 transition">← Quay lại giỏ hàng</a>
            <span class="text-slate-300">/</span>
            <span class="text-slate-800 font-semibold">Thanh toán</span>
        </div>

        <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">
            <div class="xl:col-span-2">
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                    <div class="px-6 md:px-8 py-6 border-b border-slate-100 bg-linear-to-r from-slate-900 to-slate-800">
                        <p class="text-orange-400 text-sm font-semibold uppercase tracking-[0.2em]">Checkout</p>
                        <h1 class="text-3xl font-black text-white mt-2">Thông tin đơn hàng</h1>
                        <p class="text-slate-300 mt-2">Thông tin cơ bản được điền sẵn từ tài khoản của bạn. Nếu muốn giao đến nơi khác, bạn chỉ cần sửa lại trước khi xác nhận.</p>
                    </div>

                    <form action="{{ route('checkout.process') }}" method="POST" class="p-6 md:p-8 space-y-8">
                        @csrf

                        @if($errors->any())
                            <div class="rounded-xl border border-red-200 bg-red-50 p-4 text-red-700">
                                <p class="font-bold mb-2">Không thể tiếp tục thanh toán</p>
                                <ul class="space-y-1 text-sm list-disc pl-5">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        @if(session('error'))
                            <div class="rounded-xl border border-red-200 bg-red-50 p-4 text-red-700">
                                <p class="font-bold">Lỗi hệ thống</p>
                                <p class="text-sm mt-1">{{ session('error') }}</p>
                            </div>
                        @endif

                        @if(blank($user->phone) || blank($user->address))
                            <div class="rounded-xl border border-amber-200 bg-amber-50 p-4 text-amber-800">
                                <p class="font-bold">Thông tin mặc định của tài khoản chưa đầy đủ</p>
                                <p class="text-sm mt-1">Bạn vẫn có thể nhập tay bên dưới, hoặc cập nhật trong hồ sơ để lần sau thanh toán nhanh hơn.</p>
                                <a href="{{ route('profile.edit') }}" class="inline-flex mt-3 text-sm font-semibold text-orange-600 hover:text-orange-700">Cập nhật hồ sơ</a>
                            </div>
                        @endif

                        <section class="space-y-5">
                            <div class="flex items-center justify-between gap-4">
                                <div>
                                    <h2 class="text-xl font-bold text-slate-900">Thông tin giao hàng</h2>
                                    <p class="text-sm text-slate-500 mt-1">Bạn có thể sửa nhanh nếu muốn nhận hàng ở địa chỉ khác.</p>
                                </div>
                                <a href="{{ route('profile.edit') }}" class="text-sm font-semibold text-orange-600 hover:text-orange-700">Sửa thông tin mặc định</a>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 mb-2">Tên người nhận</label>
                                    <input type="text" name="ten_nguoi_nhan" value="{{ $prefillName }}" class="w-full rounded-xl border-slate-300 focus:ring-orange-500 focus:border-orange-500 py-3 px-4" placeholder="Nhập họ và tên người nhận" required>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-slate-700 mb-2">Số điện thoại</label>
                                    <input type="text" name="so_dien_thoai" value="{{ $prefillPhone }}" class="w-full rounded-xl border-slate-300 focus:ring-orange-500 focus:border-orange-500 py-3 px-4" placeholder="090..." required>
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-2">Địa chỉ giao hàng</label>
                                <textarea name="dia_chi_chi_tiet" rows="4" class="w-full rounded-xl border-slate-300 focus:ring-orange-500 focus:border-orange-500 py-3 px-4" placeholder="Số nhà, tên đường, phường/xã, quận/huyện, tỉnh/thành..." required>{{ $prefillAddress }}</textarea>
                                <p class="text-xs text-slate-500 mt-2">Hệ thống đang lấy địa chỉ mặc định từ tài khoản của bạn. Bạn có thể sửa trực tiếp tại đây nếu muốn giao đến nơi khác.</p>
                            </div>
                        </section>

                        <section class="space-y-4 border-t border-slate-100 pt-8">
                            <div>
                                <h2 class="text-xl font-bold text-slate-900">Phương thức thanh toán</h2>
                                <p class="text-sm text-slate-500 mt-1">Chọn cách thanh toán phù hợp. Nếu bạn không thay đổi, hệ thống sẽ dùng COD.</p>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <label class="block cursor-pointer">
                                    <input type="radio" name="phuong_thuc_thanh_toan" value="cod" class="peer sr-only" {{ $selectedPaymentMethod === 'cod' ? 'checked' : '' }}>
                                    <div class="rounded-2xl border border-slate-200 bg-white p-5 peer-checked:border-orange-500 peer-checked:ring-2 peer-checked:ring-orange-200 hover:border-orange-300 transition">
                                        <div class="flex items-start justify-between gap-4">
                                            <div>
                                                <p class="font-bold text-slate-900">Thanh toán khi nhận hàng</p>
                                                <p class="text-sm text-slate-500 mt-1">Phù hợp nếu bạn muốn kiểm tra hàng trước khi thanh toán.</p>
                                            </div>
                                            <span class="inline-flex h-10 w-10 items-center justify-center rounded-full bg-orange-50 text-orange-600 font-black">COD</span>
                                        </div>
                                    </div>
                                </label>

                                <label class="block cursor-pointer">
                                    <input type="radio" name="phuong_thuc_thanh_toan" value="momo" class="peer sr-only" {{ $selectedPaymentMethod === 'momo' ? 'checked' : '' }}>
                                    <div class="rounded-2xl border border-slate-200 bg-white p-5 peer-checked:border-pink-500 peer-checked:ring-2 peer-checked:ring-pink-200 hover:border-pink-300 transition">
                                        <div class="flex items-start justify-between gap-4">
                                            <div>
                                                <p class="font-bold text-slate-900">Ví điện tử MoMo</p>
                                                <p class="text-sm text-slate-500 mt-1">Chuyển tới MoMo để quét mã và thanh toán ngay.</p>
                                            </div>
                                            <span class="inline-flex h-10 w-10 items-center justify-center rounded-full bg-pink-50 text-pink-600 font-black">M</span>
                                        </div>
                                    </div>
                                </label>
                            </div>
                        </section>

                        <div class="flex flex-col sm:flex-row gap-3 pt-2">
                            <button type="submit" class="flex-1 bg-orange-600 hover:bg-orange-700 text-white font-bold py-3.5 px-6 rounded-xl transition shadow-md">
                                Xác nhận đặt hàng
                            </button>
                            <a href="{{ route('cart.index') }}" class="sm:w-auto text-center bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold py-3.5 px-6 rounded-xl transition">
                                Quay lại giỏ hàng
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <div class="xl:col-span-1">
                <div class="sticky top-24 space-y-5">
                    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                        <div class="px-5 py-4 border-b border-slate-100">
                            <h2 class="text-lg font-bold text-slate-900">Tóm tắt đơn hàng</h2>
                            <p class="text-sm text-slate-500 mt-1">{{ $totalQuantity }} sản phẩm trong giỏ</p>
                        </div>

                        <div class="p-5 space-y-4 max-h-105 overflow-auto">
                            @forelse($cart as $item)
                                <div class="flex gap-4">
                                    <img src="{{ $item['image'] ?? '' }}" alt="{{ $item['name'] ?? 'Sản phẩm' }}" class="w-20 h-20 rounded-xl object-cover border border-slate-200">
                                    <div class="min-w-0 flex-1">
                                        <p class="font-semibold text-slate-900 line-clamp-2">{{ $item['name'] ?? 'Sản phẩm' }}</p>
                                        <p class="text-sm text-slate-500 mt-1">Size {{ $item['size'] ?? '-' }} | {{ $item['color'] ?? '-' }}</p>
                                        <div class="flex items-center justify-between mt-2 text-sm">
                                            <span class="text-slate-500">x{{ $item['quantity'] ?? 0 }}</span>
                                            <span class="font-bold text-orange-600">{{ number_format(($item['price'] ?? 0) * ($item['quantity'] ?? 0), 0, ',', '.') }} ₫</span>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <p class="text-sm text-slate-500">Giỏ hàng của bạn đang trống.</p>
                            @endforelse
                        </div>

                        <div class="px-5 py-4 border-t border-slate-100 bg-slate-50 space-y-3">
                            <div class="flex items-center justify-between text-sm text-slate-600">
                                <span>Tạm tính</span>
                                <span>{{ number_format($subtotal, 0, ',', '.') }} ₫</span>
                            </div>
                            <div class="flex items-center justify-between text-sm text-slate-600">
                                <span>Phí vận chuyển</span>
                                <span>Miễn phí</span>
                            </div>
                            <div class="flex items-center justify-between text-lg font-black text-slate-900 pt-2 border-t border-slate-200">
                                <span>Tổng cộng</span>
                                <span class="text-orange-600">{{ number_format($subtotal, 0, ',', '.') }} ₫</span>
                            </div>
                        </div>
                    </div>

                    <div class="rounded-2xl border border-slate-200 bg-white p-5">
                        <h3 class="font-bold text-slate-900">Mẹo nhanh</h3>
                        <ul class="mt-3 space-y-2 text-sm text-slate-600">
                            <li>- Nếu giao tới địa chỉ quen thuộc, bạn chỉ cần kiểm tra lại rồi xác nhận.</li>
                            <li>- Nếu chọn MoMo, hệ thống sẽ chuyển bạn sang bước thanh toán ngay sau khi tạo đơn.</li>
                            <li>- Bạn có thể thay đổi địa chỉ giao hàng mặc định ở trang hồ sơ bất kỳ lúc nào.</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
