<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Shoe;
use App\Models\Category;
use App\Models\Brand;
use App\Models\ShoeVariant;
use App\Models\ShoeImage;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class ShoeController extends Controller
{
    public function store(Request $request)
{
    // 1. Lưu thông tin chung của giày trước (code cũ của bạn)
    $shoe = new Shoe();
    $shoe->name = $request->name;
    // ...
    $shoe->save(); 

    // 2. Xử lý lưu HÌNH ẢNH lên Cloudinary
    if ($request->hasFile('images')) {
        foreach ($request->file('images') as $index => $image) {
            
            // Đẩy file lên Cloudinary, gom vào thư mục 'shoes_store' cho gọn
            $cloudinaryImage = Cloudinary::upload($image->getRealPath(), [
                'folder' => 'shoes_store'
            ]);

            // Lấy URL và Public ID từ Cloudinary trả về
            $imageUrl = $cloudinaryImage->getSecurePath();
            $publicId = $cloudinaryImage->getPublicId();

            // Lưu thông tin vào bảng shoe_images
            ShoeImage::create([
                'shoe_id' => $shoe->id,
                'image_url' => $imageUrl,
                'public_id' => $publicId,
                'is_primary' => $index === 0 ? true : false, // Ảnh đầu tiên sẽ làm ảnh bìa
            ]);
        }
    }

    return redirect()->route('admin.shoes.index')->with('success', 'Thêm giày và upload ảnh thành công!');
}
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

        // Lọc theo Giá (Mức giá thấp nhất - cao nhất)
        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }
        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        // Sắp xếp sản phẩm (Sort)
        if ($request->filled('sort')) {
            switch ($request->sort) {
                case 'price_asc':
                    $query->orderBy('price', 'asc'); // Giá thấp đến cao
                    break;
                case 'price_desc':
                    $query->orderBy('price', 'desc'); // Giá cao đến thấp
                    break;
                case 'best_selling':
                    // Yêu cầu DB có cột sold_quantity hoặc join bảng orders. Tạm fallback theo id.
                    $query->orderBy('id', 'asc'); 
                    break;
                case 'newest':
                default:
                    $query->orderBy('created_at', 'desc'); // Mới nhất
                    break;
            }
        } else {
            // Mặc định load vào là xem hàng mới nhất
            $query->orderBy('created_at', 'desc'); 
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
