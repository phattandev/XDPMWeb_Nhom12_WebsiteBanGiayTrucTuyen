<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // Trả về giao diện trang chủ
        return view('frontend.home');
    }
}
