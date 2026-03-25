<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Shoe;
use App\Models\Category;
use App\Models\Brand;
// Thêm model ShoeVariant để lấy danh sách màu và size
use App\Models\ShoeVariant;

class ShoeController extends Controller
{
    // Hiển thị danh sách tất cả các loại giày (Trang Cửa hàng)
    public function index(Request $request)
    {
        // Khởi tạo query lấy giày kèm hình ảnh và thương hiệu
        $query = Shoe::with(['images', 'brand', 'category']);

        // (Tương lai bạn có thể viết thêm code lọc theo giá, màu sắc, size ở đây)
        // Lọc theo Danh mục (Category)
        if ($request->filled('category')) {
            $query->whereIn('category_id', (array) $request->category);
        }

        // Lọc theo Thương hiệu (Brand)
        if ($request->filled('brand')) {
            $query->whereIn('brand_id', (array) $request->brand);
        }

        // Lọc theo Màu sắc (Color) - Chọc vào bảng variants
        if ($request->filled('color')) {
            $query->whereHas('variants', function ($q) use ($request) {
                $q->whereIn('color', (array) $request->color);
            });
        }

        // Lọc theo Kích thước (Size) - Chọc vào bảng variants
        if ($request->filled('size')) {
            $query->whereHas('variants', function ($q) use ($request) {
                $q->whereIn('size', (array) $request->size);
            });
        }

        // Phân trang: mỗi trang hiển thị 12 đôi giày
        $shoes = $query->paginate(12)->appends($request->query());
        
        // Lấy danh sách danh mục và thương hiệu để hiển thị ở bộ lọc (Sidebar)
        $categories = Category::all();
        $brands = Brand::all();

        // Lấy danh sách màu và size duy nhất từ CSDL để hiển thị ra form lọc
        $colors = ShoeVariant::select('color')->distinct()->pluck('color');
        $sizes = ShoeVariant::select('size')->distinct()->orderBy('size')->pluck('size');

        // Trả về view (Lưu ý: thư mục view cũ của bạn vẫn tên là 'products')
        return view('frontend.products.index', compact('shoes', 'categories', 'brands', 'colors', 'sizes'));
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
