@extends('layouts.app')

@section('content')
<div class="bg-slate-50 min-h-screen py-10">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-3xl font-bold text-slate-900 mb-8">👤 Hồ sơ cá nhân</h1>

        @if(session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-r-lg">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 md:p-8">
            <form action="{{ route('profile.update') }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="space-y-6">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Họ và Tên</label>
                        <input type="text" name="name" value="{{ $user->name }}" required class="w-full rounded-lg border-slate-300 focus:ring-orange-500 py-2.5 px-4">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Email (Không thể thay đổi)</label>
                        <input type="email" value="{{ $user->email }}" disabled class="w-full rounded-lg border-slate-200 bg-slate-100 text-slate-500 py-2.5 px-4">
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Số điện thoại</label>
                            <input type="text" name="phone" value="{{ $user->phone ?? '' }}" class="w-full rounded-lg border-slate-300 focus:ring-orange-500 py-2.5 px-4">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Địa chỉ giao hàng mặc định</label>
                            <input type="text" name="address" value="{{ $user->address ?? '' }}" class="w-full rounded-lg border-slate-300 focus:ring-orange-500 py-2.5 px-4">
                        </div>
                    </div>

                    <div class="border-t border-slate-200 pt-6 mt-6">
                        <h3 class="text-lg font-bold text-slate-800 mb-4">Đổi mật khẩu (Bỏ trống nếu không đổi)</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-2">Mật khẩu mới</label>
                                <input type="password" name="password" class="w-full rounded-lg border-slate-300 focus:ring-orange-500 py-2.5 px-4">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-2">Nhập lại mật khẩu mới</label>
                                <input type="password" name="password_confirmation" class="w-full rounded-lg border-slate-300 focus:ring-orange-500 py-2.5 px-4">
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end pt-4">
                        <button type="submit" class="bg-orange-600 text-white font-bold py-3 px-8 rounded-lg hover:bg-orange-700 transition shadow-md">
                            Lưu Thay Đổi
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection