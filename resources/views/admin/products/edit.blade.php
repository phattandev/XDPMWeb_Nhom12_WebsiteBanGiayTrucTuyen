@extends('layouts.admin')

@section('content')
<div class="mb-6">
    <a href="{{ route('admin.products.index') }}" class="text-slate-500 hover:text-orange-600 transition mb-2 inline-block">← Quay lại danh sách</a>
    <h1 class="text-2xl font-bold text-slate-800">Cập nhật Sản phẩm: <span class="text-orange-600">{{ $shoe->name }}</span></h1>
</div>

@if(session('error'))
    <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-r-lg">
        <p class="font-bold">LỖI HỆ THỐNG: {{ session('error') }}</p>
    </div>
@endif

@if($errors->any())
    <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-r-lg">
        <ul class="list-disc pl-5">
            @foreach($errors->all() as $error) <li>{{ $error }}</li> @endforeach
        </ul>
    </div>
@endif

<form action="{{ route('admin.products.update', $shoe->id) }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 md:p-8">
    @csrf
    @method('PUT')
    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
        <div class="md:col-span-2">
            <label class="block text-sm font-medium text-slate-700 mb-2">Tên giày <span class="text-red-500">*</span></label>
            <input type="text" name="name" value="{{ old('name', $shoe->name) }}" required class="w-full rounded-lg border-slate-300 focus:ring-orange-500 py-2.5 px-4">
        </div>

        <div>
            <label class="block text-sm font-medium text-slate-700 mb-2">Danh mục <span class="text-red-500">*</span></label>
            <select name="category_id" required class="w-full rounded-lg border-slate-300 focus:ring-orange-500 py-2.5 px-4 bg-white">
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ (int) old('category_id', $shoe->category_id) === $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                @endforeach
            </select>
        </div>
        
        <div>
            <label class="block text-sm font-medium text-slate-700 mb-2">Thương hiệu <span class="text-red-500">*</span></label>
            <select name="brand_id" required class="w-full rounded-lg border-slate-300 focus:ring-orange-500 py-2.5 px-4 bg-white">
                @foreach($brands as $brand)
                    <option value="{{ $brand->id }}" {{ (int) old('brand_id', $shoe->brand_id) === $brand->id ? 'selected' : '' }}>{{ $brand->name }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium text-slate-700 mb-2">Giá bán (VNĐ) <span class="text-red-500">*</span></label>
            <input type="number" name="price" value="{{ old('price', $shoe->price) }}" required min="0" class="w-full rounded-lg border-slate-300 focus:ring-orange-500 py-2.5 px-4">
        </div>
        
        <div class="md:col-span-2">
            <label class="block text-sm font-medium text-slate-700 mb-2">Hình ảnh hiện tại</label>
            @if($shoe->images->count() > 0)
                <div class="flex gap-4 flex-wrap mb-4">
                    @foreach($shoe->images as $img)
                        <div class="relative">
                            <img src="{{ str_starts_with($img->image_url, 'http') ? $img->image_url : asset('images/' . $img->image_url) }}" class="w-24 h-24 object-cover border border-slate-200 rounded-lg shadow-sm">
                            @if($img->is_primary)
                                <span class="absolute top-0 left-0 bg-orange-600 text-white text-[10px] font-bold px-1.5 py-0.5 rounded-br-lg rounded-tl-lg">Ảnh chính</span>
                            @endif
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-sm text-slate-500 mb-4">Chưa có hình ảnh nào.</p>
            @endif
        </div>

        <div class="md:col-span-2">
            <label class="block text-sm font-medium text-slate-700 mb-2">Tải lên hình ảnh mới (Lưu ý: Sẽ thay thế toàn bộ ảnh cũ ở trên)</label>
            <input type="file" name="images[]" multiple accept="image/*" class="w-full rounded-lg border border-slate-300 bg-slate-50 py-2 px-4 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-orange-50 file:text-orange-700">
            <p class="text-xs text-slate-500 mt-1">Để trống nếu bạn chỉ muốn sửa tên, giá hoặc mô tả. Ảnh cũ chỉ bị thay khi ảnh mới tải lên thành công.</p>
        </div>
        
        <div class="md:col-span-2">
            <label class="block text-sm font-medium text-slate-700 mb-2">Mô tả chi tiết</label>
            <textarea name="description" rows="4" class="w-full rounded-lg border-slate-300 focus:ring-orange-500 py-2.5 px-4">{{ old('description', $shoe->description) }}</textarea>
        </div>
    </div>

    <!-- Phân loại (Màu sắc & Size) -->
    <div class="border-t border-slate-200 pt-6 mb-6">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-bold text-slate-800">Phân loại (Màu sắc & Size)</h3>
            <button type="button" onclick="addVariantRow()" class="text-sm bg-slate-100 hover:bg-slate-200 text-slate-700 font-medium py-1.5 px-3 rounded transition">
                + Thêm dòng mới
            </button>
        </div>
        
        <div id="variants-container" class="space-y-3">
            @foreach($shoe->variants as $index => $variant)
            <div class="flex gap-4 items-start variant-row bg-slate-50 p-3 rounded-lg border border-slate-100">
                <!-- Chứa ID cũ để Controller biết là đang Update -->
                <input type="hidden" name="variants[{{ $index }}][id]" value="{{ $variant->id }}">
                
                <div class="flex-1">
                    <label class="text-xs text-slate-500 font-bold">Màu sắc</label>
                    <input type="text" name="variants[{{ $index }}][color]" value="{{ $variant->color }}" class="w-full text-sm rounded border-slate-300 py-2 mt-1">
                </div>
                <div class="flex-1">
                    <label class="text-xs text-slate-500 font-bold">Kích cỡ</label>
                    <input type="number" name="variants[{{ $index }}][size]" value="{{ $variant->size }}" class="w-full text-sm rounded border-slate-300 py-2 mt-1">
                </div>
                <div class="flex-1">
                    <label class="text-xs text-slate-500 font-bold">Kho hàng</label>
                    <input type="number" name="variants[{{ $index }}][stock_quantity]" value="{{ $variant->stock_quantity }}" class="w-full text-sm rounded border-slate-300 py-2 mt-1">
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <div class="flex justify-end gap-3 pt-4 border-t border-slate-200">
        <a href="{{ route('admin.products.index') }}" class="bg-slate-100 text-slate-700 font-bold py-3 px-6 rounded-lg hover:bg-slate-200 transition">Hủy</a>
        <button type="submit" class="bg-blue-600 text-white font-bold py-3 px-8 rounded-lg hover:bg-blue-700 transition shadow-md">
            Lưu Cập Nhật
        </button>
    </div>
</form>

<script>
    // Bọc trong nháy kép và dùng parseInt để ép về kiểu số
    let variantCount = parseInt("{{ count($shoe->variants) }}", 10) || 0;
    
    function addVariantRow() {
        const container = document.getElementById('variants-container');
        const row = document.createElement('div');
        row.className = 'flex gap-4 items-end variant-row bg-green-50 p-3 rounded-lg border border-green-100 mt-3';
        row.innerHTML = `
            <div class="flex-1"><label class="text-xs text-green-600 font-bold">Màu sắc (Mới)</label><input type="text" name="variants[${variantCount}][color]" placeholder="Màu sắc" class="w-full text-sm rounded border-slate-300 py-2 mt-1"></div>
            <div class="flex-1"><label class="text-xs text-green-600 font-bold">Kích cỡ (Mới)</label><input type="number" name="variants[${variantCount}][size]" placeholder="Size" class="w-full text-sm rounded border-slate-300 py-2 mt-1"></div>
            <div class="flex-1"><label class="text-xs text-green-600 font-bold">Kho hàng (Mới)</label><input type="number" name="variants[${variantCount}][stock_quantity]" placeholder="Số lượng kho" class="w-full text-sm rounded border-slate-300 py-2 mt-1"></div>
            <button type="button" onclick="this.parentElement.remove()" class="text-red-500 hover:text-red-700 font-bold px-2 py-2 mb-1">X</button>
        `;
        container.appendChild(row);
        variantCount++;
    }
</script>
@endsection
