@extends('layouts.app') 

@section('content')
<div class="min-h-screen flex items-center justify-center bg-slate-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8 bg-white p-8 rounded-2xl shadow-xl border-t-4 border-orange-500">
        <div>
            <h2 class="mt-2 text-center text-3xl font-extrabold text-slate-900">
                Đăng nhập <span class="text-orange-600 italic">ShoeStore</span>
            </h2>
            <p class="mt-2 text-center text-sm text-slate-600">
                Hoặc
                <a href="{{ route('register') }}" class="font-semibold text-orange-600 hover:text-orange-500 transition">
                    tạo tài khoản mới
                </a>
            </p>
        </div>

        <form class="mt-8 space-y-6" action="{{ url('/login') }}" method="POST">
            @csrf
            <div class="rounded-md space-y-4">
                <div>
                    <label for="email" class="block text-sm font-medium text-slate-700 mb-1">Địa chỉ Email</label>
                    <input id="email" name="email" type="email" autocomplete="email" required value="{{ old('email') }}"
                        class="appearance-none relative block w-full px-4 py-3 border border-slate-300 placeholder-slate-400 text-slate-900 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500 focus:z-10 sm:text-sm transition"
                        placeholder="Ví dụ: khach@shop.com">
                    @error('email')
                        <p class="text-red-500 text-xs mt-1.5 font-medium">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="password" class="block text-sm font-medium text-slate-700 mb-1">Mật khẩu</label>
                    <input id="password" name="password" type="password" autocomplete="current-password" required
                        class="appearance-none relative block w-full px-4 py-3 border border-slate-300 placeholder-slate-400 text-slate-900 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500 focus:z-10 sm:text-sm transition"
                        placeholder="Nhập mật khẩu của bạn">
                    @error('password')
                        <p class="text-red-500 text-xs mt-1.5 font-medium">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <input id="remember" name="remember" type="checkbox"
                        class="h-4 w-4 text-orange-600 focus:ring-orange-500 border-slate-300 rounded cursor-pointer">
                    <label for="remember" class="ml-2 block text-sm text-slate-700 cursor-pointer">
                        Ghi nhớ đăng nhập
                    </label>
                </div>

                <div class="text-sm">
                    <a href="{{ route('password.request') }}" class="font-semibold text-orange-600 hover:text-orange-500 transition">
                        Quên mật khẩu?
                    </a>
                </div>
            </div>

            <div>
                <button type="submit"
                    class="group relative w-full flex justify-center py-3 px-4 border border-transparent text-sm font-bold rounded-lg text-white bg-orange-600 hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 transition-colors shadow-md">
                    Đăng nhập hệ thống
                </button>
            </div>
        </form>
    </div>
</div>
@endsection