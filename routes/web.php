<?php

use Illuminate\Support\Facades\Route;
use App\Models\User;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/about', function () {
    return view('about');
});

// BASE_FE/users -> Hiển thị danh sách users trên giao diện HTML
Route::get('/users', function () {
    $users = User::select('id', 'name')->get();
    return view('users', compact('users'));
});