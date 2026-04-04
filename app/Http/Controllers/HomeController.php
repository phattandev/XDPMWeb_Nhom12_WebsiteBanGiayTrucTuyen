<?php

namespace App\Http\Controllers;
use App\Models\Category;
use Illuminate\Support\Str;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
       // Lấy 3 danh mục đầu tiên để hiển thị nổi bật ra trang chủ
        $featuredCategories = Category::withCount('shoes')
            ->take(3)
            ->get()
            ->map(function (Category $category) {
                $normalizedName = Str::lower($category->name);

                $category->featured_background_url = match (true) {
                    Str::contains($normalizedName, 'sneaker') => 'https://images.unsplash.com/photo-1542291026-7eec264c27ff?auto=format&fit=crop&w=1200&q=80',
                    Str::contains($normalizedName, 'boot') => 'https://images.unsplash.com/photo-1525966222134-fcfa99b8ae77?auto=format&fit=crop&w=1200&q=80',
                    Str::contains($normalizedName, 'running') => 'https://images.unsplash.com/photo-1542291026-7eec264c27ff?auto=format&fit=crop&w=1200&q=80',
                    default => 'https://images.unsplash.com/photo-1460353581641-37baddab0fa2?auto=format&fit=crop&w=1200&q=80',
                };

                return $category;
            });
        
        return view('frontend.home', compact('featuredCategories'));
    }
}
