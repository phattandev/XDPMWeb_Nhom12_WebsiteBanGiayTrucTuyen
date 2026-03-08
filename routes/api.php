<?php
use App\Models\User;
use Illuminate\Support\Facades\Route;

// BASE_API/users -> Lấy danh sách tất cả users (Chỉ id và name)
Route::get('/users', function () {
    $users = User::select('id', 'name')->get();
    return response()->json($users);
});

// BASE_API/users/{id} -> Lấy user cụ thể theo id
Route::get('/users/{id}', function ($id) {
    // Tìm user theo id, nếu không thấy sẽ tự động trả về lỗi 404
    $user = User::select('id', 'name')->findOrFail($id);
    return response()->json($user);
});