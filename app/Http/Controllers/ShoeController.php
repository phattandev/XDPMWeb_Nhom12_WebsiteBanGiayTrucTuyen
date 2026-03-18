<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Shoe;
use App\Models\Category;
use App\Models\Brand;

class ShoeController extends Controller
{
    // Hiển thị danh sách tất cả các loại giày (Trang Cửa hàng)
    public function index(Request $request)
    {
        // Khởi tạo query lấy giày kèm hình ảnh và thương hiệu
        $query = Shoe::with(['images', 'brand', 'category']);

        // (Tương lai bạn có thể viết thêm code lọc theo giá, màu sắc, size ở đây)

        // Phân trang: mỗi trang hiển thị 12 đôi giày
        $shoes = $query->paginate(12); 
        
        // Lấy danh sách danh mục và thương hiệu để hiển thị ở bộ lọc (Sidebar)
        $categories = Category::all();
        $brands = Brand::all();

        // Trả về view (Lưu ý: thư mục view cũ của bạn vẫn tên là 'products')
        return view('frontend.products.index', compact('shoes', 'categories', 'brands'));
    }

    // Hiển thị chi tiết một đôi giày
    public function show($id)
    {
        // Lấy thông tin giày kèm theo toàn bộ dữ liệu liên quan: ảnh, biến thể, đánh giá, danh mục...
        $shoe = Shoe::with(['images', 'variants', 'reviews.user', 'brand', 'category'])->findOrFail($id);
        
        // Lấy 4 đôi giày khác cùng danh mục để làm mục "Sản phẩm liên quan"
        $relatedShoes = Shoe::with(['images'])
            ->where('category_id', $shoe->category_id)
            ->where('id', '!=', $id)
            ->take(4)
            ->get();

        // Trả về view chi tiết
        return view('frontend.products.show', compact('shoe', 'relatedShoes'));
    }
}
