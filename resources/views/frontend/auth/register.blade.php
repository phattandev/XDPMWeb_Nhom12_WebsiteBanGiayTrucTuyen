@extends('layouts.app')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-slate-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8 bg-white p-8 rounded-2xl shadow-xl border-t-4 border-orange-500">
        <div>
            <h2 class="mt-2 text-center text-3xl font-extrabold text-slate-900">
                Tạo tài khoản mới
            </h2>
            <p class="mt-2 text-center text-sm text-slate-600">
                Đã có tài khoản?
                <a href="{{ route('login') }}" class="font-semibold text-orange-600 hover:text-orange-500 transition">
                    Đăng nhập tại đây
                </a>
            </p>
        </div>

        <form class="mt-8 space-y-6" action="{{ url('/register') }}" method="POST">
            @csrf
            <div class="rounded-md space-y-4">
                <!-- Họ và tên -->
                <div>
                    <label for="name" class="block text-sm font-medium text-slate-700 mb-1">Họ và tên <span class="text-red-500">*</span></label>
                    <input id="name" name="name" type="text" required value="{{ old('name') }}"
                        class="appearance-none relative block w-full px-4 py-3 border border-slate-300 placeholder-slate-400 text-slate-900 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500 focus:z-10 sm:text-sm transition"
                        placeholder="Nhập họ và tên của bạn">
                    @error('name')
                        <p class="text-red-500 text-xs mt-1.5 font-medium">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium text-slate-700 mb-1">Địa chỉ Email <span class="text-red-500">*</span></label>
                    <input id="email" name="email" type="email" autocomplete="email" required value="{{ old('email') }}"
                        class="appearance-none relative block w-full px-4 py-3 border border-slate-300 placeholder-slate-400 text-slate-900 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500 focus:z-10 sm:text-sm transition"
                        placeholder="Nhập địa chỉ Email hợp lệ">
                    @error('email')
                        <p class="text-red-500 text-xs mt-1.5 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Số điện thoại (Thêm mới) -->
                <div>
                    <label for="phone" class="block text-sm font-medium text-slate-700 mb-1">Số điện thoại</label>
                    <input id="phone" name="phone" type="tel" value="{{ old('phone') }}"
                        class="appearance-none relative block w-full px-4 py-3 border border-slate-300 placeholder-slate-400 text-slate-900 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500 focus:z-10 sm:text-sm transition"
                        placeholder="Nhập số điện thoại (Không bắt buộc)">
                    @error('phone')
                        <p class="text-red-500 text-xs mt-1.5 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Địa chỉ (Thêm mới) -->
                <div>
                    <label for="address" class="block text-sm font-medium text-slate-700 mb-1">Địa chỉ giao hàng</label>
                    <input id="address" name="address" type="text" value="{{ old('address') }}"
                        class="appearance-none relative block w-full px-4 py-3 border border-slate-300 placeholder-slate-400 text-slate-900 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500 focus:z-10 sm:text-sm transition"
                        placeholder="Nhập địa chỉ chi tiết (Không bắt buộc)">
                    @error('address')
                        <p class="text-red-500 text-xs mt-1.5 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Mật khẩu -->
                <div>
                    <label for="password" class="block text-sm font-medium text-slate-700 mb-1">Mật khẩu <span class="text-red-500">*</span></label>
                    <input id="password" name="password" type="password" required
                        class="appearance-none relative block w-full px-4 py-3 border border-slate-300 placeholder-slate-400 text-slate-900 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500 focus:z-10 sm:text-sm transition"
                        placeholder="Tạo mật khẩu (Ít nhất 8 ký tự)">
                    @error('password')
                        <p class="text-red-500 text-xs mt-1.5 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Xác nhận mật khẩu -->
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-slate-700 mb-1">Xác nhận mật khẩu <span class="text-red-500">*</span></label>
                    <input id="password_confirmation" name="password_confirmation" type="password" required
                        class="appearance-none relative block w-full px-4 py-3 border border-slate-300 placeholder-slate-400 text-slate-900 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500 focus:z-10 sm:text-sm transition"
                        placeholder="Nhập lại mật khẩu vừa tạo">
                </div>
            </div>

            <div>
                <button type="submit"
                    class="group relative w-full flex justify-center py-3 px-4 border border-transparent text-sm font-bold rounded-lg text-white bg-orange-600 hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 transition-colors shadow-md">
                    Hoàn tất đăng ký
                </button>
            </div>
        </form>
    </div>
</div>
@endsection