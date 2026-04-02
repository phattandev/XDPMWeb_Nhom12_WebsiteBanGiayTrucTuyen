<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Shoe;
use App\Models\Category;
use App\Models\Brand;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Shoe::with(['category', 'brand']);

        // Nếu có từ khóa tìm kiếm
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where('name', 'like', '%' . $search . '%');
        }

        // Lấy dữ liệu, sắp xếp mới nhất và giữ lại tham số tìm kiếm khi chuyển trang (appends)
        $shoes = $query->orderBy('created_at', 'desc')->paginate(6)->appends($request->query());
        
        return view('admin.products.index', compact('shoes'));
    }

    public function create()
    {
        $categories = Category::all();
        $brands = Brand::all();
        return view('admin.products.create', compact('categories', 'brands'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'category_id' => 'required|exists:categories,id',
            'brand_id' => 'required|exists:brands,id',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:10240', // Validate mảng hình ảnh
        ]);

        DB::beginTransaction();
        try {
            // 1. Tạo Giày mới
            $shoe = Shoe::create([
                'name' => $request->name,
                'category_id' => $request->category_id,
                'brand_id' => $request->brand_id,
                'price' => $request->price,
                'description' => $request->description,
            ]);

            // 2. Xử lý Upload Hình ảnh
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $index => $file) {
                    $filename = time() . '_' . $file->getClientOriginalName();
                    // Lưu vào thư mục public/images/shoes
                    $file->move(public_path('images/shoes'), $filename);
                    
                    DB::table('shoe_images')->insert([
                        'shoe_id' => $shoe->id,
                        'image_url' => 'shoes/' . $filename,
                        'is_primary' => $index === 0 ? true : false, // Ảnh đầu tiên là ảnh chính
                    ]);
                }
            }

            // 3. Xử lý các biến thể (Màu, Size, Số lượng)
            if ($request->has('variants')) {
                foreach ($request->variants as $variant) {
                    if (!empty($variant['color']) && !empty($variant['size'])) {
                        DB::table('shoe_variants')->insert([
                            'shoe_id' => $shoe->id,
                            'color' => $variant['color'],
                            'size' => $variant['size'],
                            'stock_quantity' => $variant['stock_quantity'] ?? 0,
                        ]);
                    }
                }
            }

            DB::commit();
            return redirect()->route('admin.products.index')->with('success', 'Thêm sản phẩm thành công!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Lỗi: ' . $e->getMessage())->withInput();
        }
    }

    public function destroy(string $id)
    {
        Shoe::findOrFail($id)->delete(); // Các ảnh và biến thể sẽ tự xóa nếu DB set cascadeOnDelete
        return back()->with('success', 'Đã xóa sản phẩm!');
    }
}