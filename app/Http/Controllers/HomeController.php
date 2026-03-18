<?php

namespace App\Http\Controllers;
use App\Models\Category;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
       // Lấy 3 danh mục đầu tiên để hiển thị nổi bật ra trang chủ
        $featuredCategories = Category::withCount('shoes')->take(3)->get();
        
        return view('frontend.home', compact('featuredCategories'));
    }
}
