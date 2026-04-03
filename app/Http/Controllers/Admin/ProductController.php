<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Shoe;
use App\Models\Category;
use App\Models\Brand;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

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
                    
                    // Đẩy file lên Cloudinary, gom vào thư mục 'shoes_store'
                    $cloudinaryImage = Cloudinary::upload($file->getRealPath(), [
                        'folder' => 'shoes_store'
                    ]);

                    // Lấy URL và Public ID từ Cloudinary trả về
                    $imageUrl = $cloudinaryImage->getSecurePath();
                    $publicId = $cloudinaryImage->getPublicId();
                    
                    DB::table('shoe_images')->insert([
                        'shoe_id' => $shoe->id,
                        'image_url' => $imageUrl,
                        'public_id' => $publicId, // Lưu public_id để sau này xóa ảnh trên Cloudinary nếu cần
                        'is_primary' => $index === 0 ? true : false,
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

    public function edit($id)
    {
        $shoe = Shoe::with(['images', 'variants'])->findOrFail($id);
        $categories = Category::all();
        $brands = Brand::all();
        
        return view('admin.products.edit', compact('shoe', 'categories', 'brands'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'category_id' => 'required|exists:categories,id',
            'brand_id' => 'required|exists:brands,id',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:10240',
        ]);

        DB::beginTransaction();
        try {
            $shoe = Shoe::findOrFail($id);
            
            // 1. Cập nhật thông tin cơ bản
            $shoe->update([
                'name' => $request->name,
                'category_id' => $request->category_id,
                'brand_id' => $request->brand_id,
                'price' => $request->price,
                'description' => $request->description,
            ]);

            // 2. Xử lý up thêm ảnh mới (nếu có)
            if ($request->hasFile('images')) {
                // TÙY CHỌN: Xóa ảnh cũ trên Database (và cả trên Cloudinary nếu cần)
                // Nếu bạn muốn giữ ảnh cũ thì bỏ qua bước này.
                DB::table('shoe_images')->where('shoe_id', $shoe->id)->delete();

                foreach ($request->file('images') as $index => $file) {
                    $cloudinaryImage = Cloudinary::upload($file->getRealPath(), [
                        'folder' => 'shoes_store'
                    ]);

                    DB::table('shoe_images')->insert([
                        'shoe_id' => $shoe->id,
                        'image_url' => $cloudinaryImage->getSecurePath(),
                        'public_id' => $cloudinaryImage->getPublicId(),
                        // Cài đặt ảnh đầu tiên tải lên làm ảnh chính
                        'is_primary' => $index === 0 ? true : false, 
                    ]);
                }
            }

            // 3. Xử lý các biến thể (Cập nhật cái cũ & Thêm cái mới)
            if ($request->has('variants')) {
                foreach ($request->variants as $variant) {
                    if (!empty($variant['color']) && !empty($variant['size'])) {
                        
                        if (isset($variant['id'])) {
                            // Nếu có ID -> Đây là biến thể cũ -> Update số lượng/màu/size
                            DB::table('shoe_variants')->where('id', $variant['id'])->update([
                                'color' => $variant['color'],
                                'size' => $variant['size'],
                                'stock_quantity' => $variant['stock_quantity'] ?? 0,
                            ]);
                        } else {
                            // Nếu không có ID -> Admin vừa bấm nút "+ Thêm dòng" -> Tạo mới
                            DB::table('shoe_variants')->insert([
                                'shoe_id' => $shoe->id,
                                'color' => $variant['color'],
                                'size' => $variant['size'],
                                'stock_quantity' => $variant['stock_quantity'] ?? 0,
                            ]);
                        }
                    }
                }
            }

            DB::commit();
            return redirect()->route('admin.products.index')->with('success', 'Cập nhật sản phẩm thành công!');
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