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
use Throwable;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Shoe::with(['category', 'brand']);

        // Nếu có từ khóa tìm kiếm
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->whereLike('name', '%' . $search . '%');
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
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:10240', // Validate mảng hình ảnh
        ]);

        $uploadedImages = [];
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
                $uploadedImages = $this->uploadImages($request->file('images'));
                $this->insertShoeImages($shoe->id, $uploadedImages);
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
        } catch (Throwable $e) {
            DB::rollBack();
            $this->deleteCloudinaryImages($uploadedImages);
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
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:10240',
        ]);

        $uploadedImages = [];
        $oldImages = [];
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
                $uploadedImages = $this->uploadImages($request->file('images'));
                $oldImages = DB::table('shoe_images')->where('shoe_id', $shoe->id)->get()->all();
                DB::table('shoe_images')->where('shoe_id', $shoe->id)->delete();
                $this->insertShoeImages($shoe->id, $uploadedImages);
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
            $this->deleteCloudinaryImages($oldImages);
            return redirect()->route('admin.products.index')->with('success', 'Cập nhật sản phẩm thành công!');
        } catch (Throwable $e) {
            DB::rollBack();
            $this->deleteCloudinaryImages($uploadedImages);
            return back()->with('error', 'Lỗi: ' . $e->getMessage())->withInput();
        }
    }

    public function destroy(string $id)
    {
        Shoe::findOrFail($id)->delete(); // Các ảnh và biến thể sẽ tự xóa nếu DB set cascadeOnDelete
        return back()->with('success', 'Đã xóa sản phẩm!');
    }

    private function uploadImages(array $files): array
    {
        $uploadedImages = [];

        foreach (array_values($files) as $index => $file) {
            if (!$file->isValid()) {
                throw new \RuntimeException('Ảnh tải lên không hợp lệ: ' . $file->getClientOriginalName());
            }

            $cloudinaryImage = Cloudinary::uploadApi()->upload($file->getRealPath(), [
                'folder' => 'shoes_store',
            ]);

            $imageUrl = $cloudinaryImage['secure_url'] ?? $cloudinaryImage['url'] ?? null;
            $publicId = $cloudinaryImage['public_id'] ?? null;

            if (empty($imageUrl) || empty($publicId)) {
                throw new \RuntimeException('Cloudinary không trả về đủ thông tin ảnh sau khi tải lên.');
            }

            $uploadedImages[] = [
                'image_url' => $imageUrl,
                'public_id' => $publicId,
                'is_primary' => $index === 0,
            ];
        }

        return $uploadedImages;
    }

    private function insertShoeImages(int $shoeId, array $uploadedImages): void
    {
        foreach ($uploadedImages as $image) {
            DB::table('shoe_images')->insert([
                'shoe_id' => $shoeId,
                'image_url' => $image['image_url'],
                'public_id' => $image['public_id'],
                'is_primary' => $image['is_primary'],
            ]);
        }
    }

    private function deleteCloudinaryImages(iterable $images): void
    {
        foreach ($images as $image) {
            $publicId = is_array($image) ? ($image['public_id'] ?? null) : ($image->public_id ?? null);

            if (empty($publicId)) {
                continue;
            }

            try {
                Cloudinary::uploadApi()->destroy($publicId);
            } catch (Throwable $cloudinaryException) {
                report($cloudinaryException);
            }
        }
    }
}
