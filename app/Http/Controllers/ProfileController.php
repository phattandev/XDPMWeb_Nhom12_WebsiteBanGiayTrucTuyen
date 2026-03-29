<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth; // Thêm dòng này để gọi Auth
use Illuminate\Support\Facades\Hash; // Thêm dòng này để mã hóa mật khẩu

use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function edit()
    {
        $user = Auth::user();
        return view('frontend.account.profile', compact('user'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20', 
            'address' => 'nullable|string',
            'password' => 'nullable|min:6|confirmed', // Nếu khách muốn đổi mật khẩu
        ]);

        $user = Auth::user();
        $user->name = $request->name;
        
        if ($request->has('phone')) $user->phone = $request->phone;
        if ($request->has('address')) $user->address = $request->address;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return back()->with('success', 'Đã cập nhật thông tin cá nhân thành công!');
    }
}
