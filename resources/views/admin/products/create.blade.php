@extends('layouts.admin')

@section('content')
<div class="mb-6">
    <a href="{{ route('admin.products.index') }}" class="text-slate-500 hover:text-orange-600 transition mb-2 inline-block">← Quay lại danh sách</a>
    <h1 class="text-2xl font-bold text-slate-800">Thêm Sản phẩm mới</h1>
</div>

@if($errors->any())
    <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-r-lg">
        <ul class="list-disc pl-5">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 md:p-8">
    @csrf
    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
        <div class="md:col-span-2">
            <label class="block text-sm font-medium text-slate-700 mb-2">Tên giày <span class="text-red-500">*</span></label>
            <input type="text" name="name" required class="w-full rounded-lg border-slate-300 focus:ring-orange-500 focus:border-orange-500 py-2.5 px-4" placeholder="Ví dụ: Nike Air Max 270">
        </div>

        <div>
            <label class="block text-sm font-medium text-slate-700 mb-2">Danh mục <span class="text-red-500">*</span></label>
            <select name="category_id" required class="w-full rounded-lg border-slate-300 focus:ring-orange-500 focus:border-orange-500 py-2.5 px-4 bg-white">
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium text-slate-700 mb-2">Thương hiệu <span class="text-red-500">*</span></label>
            <select name="brand_id" required class="w-full rounded-lg border-slate-300 focus:ring-orange-500 focus:border-orange-500 py-2.5 px-4 bg-white">
                @foreach($brands as $brand)
                    <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium text-slate-700 mb-2">Giá bán (VNĐ) <span class="text-red-500">*</span></label>
            <input type="number" name="price" required min="0" class="w-full rounded-lg border-slate-300 focus:ring-orange-500 focus:border-orange-500 py-2.5 px-4" placeholder="Ví dụ: 2500000">
        </div>
        <div>
            <label class="block text-sm font-medium text-slate-700 mb-2">Hình ảnh sản phẩm (Chọn nhiều)</label>
            <input type="file" name="images[]" multiple accept="image/*" class="w-full rounded-lg border border-slate-300 bg-slate-50 py-2 px-4 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-orange-50 file:text-orange-700 hover:file:bg-orange-100">
            <p class="text-xs text-slate-500 mt-1">Ảnh đầu tiên sẽ được chọn làm ảnh đại diện.</p>
        </div>
        
        <div class="md:col-span-2">
            <label class="block text-sm font-medium text-slate-700 mb-2">Mô tả chi tiết</label>
            <textarea name="description" rows="4" class="w-full rounded-lg border-slate-300 focus:ring-orange-500 focus:border-orange-500 py-2.5 px-4"></textarea>
        </div>
    </div>

    <div class="border-t border-slate-200 pt-6 mb-6">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-bold text-slate-800">Phân loại (Màu sắc & Size)</h3>
            <button type="button" onclick="addVariantRow()" class="text-sm bg-slate-100 hover:bg-slate-200 text-slate-700 font-medium py-1.5 px-3 rounded transition">
                + Thêm dòng
            </button>
        </div>
        
        <div id="variants-container" class="space-y-3">
            <div class="flex gap-4 items-start variant-row bg-slate-50 p-3 rounded-lg border border-slate-100">
                <div class="flex-1">
                    <input type="text" name="variants[0][color]" placeholder="Màu sắc (VD: Đen)" class="w-full text-sm rounded border-slate-300 py-2">
                </div>
                <div class="flex-1">
                    <input type="number" name="variants[0][size]" placeholder="Size (VD: 40)" class="w-full text-sm rounded border-slate-300 py-2">
                </div>
                <div class="flex-1">
                    <input type="number" name="variants[0][stock_quantity]" placeholder="Số lượng kho" class="w-full text-sm rounded border-slate-300 py-2">
                </div>
            </div>
        </div>
    </div>

    <div class="flex justify-end pt-4 border-t border-slate-200">
        <button type="submit" class="bg-orange-600 text-white font-bold py-3 px-8 rounded-lg hover:bg-orange-700 transition shadow-md">
            Lưu Sản Phẩm
        </button>
    </div>
</form>

<script>
    let variantCount = 1;
    function addVariantRow() {
        const container = document.getElementById('variants-container');
        const row = document.createElement('div');
        row.className = 'flex gap-4 items-start variant-row bg-slate-50 p-3 rounded-lg border border-slate-100 mt-3';
        row.innerHTML = `
            <div class="flex-1"><input type="text" name="variants[${variantCount}][color]" placeholder="Màu sắc" class="w-full text-sm rounded border-slate-300 py-2"></div>
            <div class="flex-1"><input type="number" name="variants[${variantCount}][size]" placeholder="Size" class="w-full text-sm rounded border-slate-300 py-2"></div>
            <div class="flex-1"><input type="number" name="variants[${variantCount}][stock_quantity]" placeholder="Số lượng kho" class="w-full text-sm rounded border-slate-300 py-2"></div>
            <button type="button" onclick="this.parentElement.remove()" class="text-red-500 hover:text-red-700 font-bold px-2 py-2">X</button>
        `;
        container.appendChild(row);
        variantCount++;
    }
</script>
@endsection