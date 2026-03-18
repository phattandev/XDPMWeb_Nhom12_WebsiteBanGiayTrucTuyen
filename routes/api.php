<?php
use App\Models\User;
use Illuminate\Support\Facades\Route;

// BASE_API/users -> Lấy danh sách tất cả users ( id và name )
Route::get('/users', function () {
    $users = User::select('id', 'name')->get();
    return response()->json($users);
});

// BASE_API/users/{id} -> Lấy user cụ thể theo id
Route::get('/users/{id}', function ($id) {
    $user = User::select('id', 'name')->findOrFail($id);
    return response()->json($user);
});