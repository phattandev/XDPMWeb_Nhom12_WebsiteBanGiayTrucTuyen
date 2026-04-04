@extends('layouts.app')

@section('content')
<div class="bg-slate-50 text-slate-900 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        
        <h1 class="text-3xl font-bold text-slate-800 mb-8">Tất cả sản phẩm</h1>

        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
            
            <div class="col-span-1">
                <form id="filterForm" action="{{ route('shoes.index') }}" method="GET" class="space-y-6 sticky top-20">
                    
                    <div class="flex gap-2 mb-6">
                        <button type="submit" class="flex-1 bg-orange-600 text-white font-bold py-2.5 rounded-lg hover:bg-orange-700 transition shadow-sm">
                            Áp dụng
                        </button>
                        <a href="{{ route('shoes.index') }}" class="px-4 py-2.5 bg-slate-200 text-slate-700 font-medium rounded-lg hover:bg-slate-300 transition text-center">
                            Xóa
                        </a>
                    </div>

                    <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm">
                        <h3 class="font-bold text-lg mb-4 text-slate-900 border-b border-slate-100 pb-2">Khoảng giá (₫)</h3>
                        <div class="flex items-center gap-2">
                            <input type="number" name="min_price" value="{{ request('min_price') }}" placeholder="TỪ" class="w-full rounded-lg border-slate-300 text-sm focus:ring-orange-500 py-2 px-3">
                            <span class="text-slate-500 font-bold">-</span>
                            <input type="number" name="max_price" value="{{ request('max_price') }}" placeholder="ĐẾN" class="w-full rounded-lg border-slate-300 text-sm focus:ring-orange-500 py-2 px-3">
                        </div>
                    </div>

                    <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm">
                        <h3 class="font-bold text-lg mb-4 text-slate-900 border-b border-slate-100 pb-2">Danh mục</h3>
                        <div class="space-y-3">
                            @foreach($categories as $category)
                                <label class="flex items-center space-x-3 cursor-pointer group">
                                    <input type="checkbox" name="category[]" value="{{ $category->id }}"
                                        {{ in_array($category->id, $selectedCategories ?? [], true) ? 'checked' : '' }}
                                        class="w-5 h-5 rounded border-slate-300 text-orange-600 focus:ring-orange-500 cursor-pointer">
                                    <span class="text-slate-600 group-hover:text-orange-600 transition-colors">{{ $category->name }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm">
                        <h3 class="font-bold text-lg mb-4 text-slate-900 border-b border-slate-100 pb-2">Thương hiệu</h3>
                        <div class="space-y-3">
                            @foreach($brands as $brand)
                                <label class="flex items-center space-x-3 cursor-pointer group">
                                    <input type="checkbox" name="brand[]" value="{{ $brand->id }}"
                                        {{ in_array($brand->id, $selectedBrands ?? [], true) ? 'checked' : '' }}
                                        class="w-5 h-5 rounded border-slate-300 text-orange-600 focus:ring-orange-500 cursor-pointer">
                                    <span class="text-slate-600 group-hover:text-orange-600 transition-colors">{{ $brand->name }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm">
                        <h3 class="font-bold text-lg mb-4 text-slate-900 border-b border-slate-100 pb-2">Màu sắc</h3>
                        <div class="flex flex-wrap gap-2">
                            @foreach($colors as $color)
                                <label class="cursor-pointer">
                                    <input type="checkbox" name="color[]" value="{{ $color }}" {{ in_array($color, $selectedColors ?? [], true) ? 'checked' : '' }} class="peer sr-only">
                                    <div class="px-3 py-1.5 rounded-lg border border-slate-200 text-sm font-medium text-slate-600 peer-checked:bg-orange-600 peer-checked:text-white hover:border-orange-400 transition-all">
                                        {{ $color }}
                                    </div>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm">
                        <h3 class="font-bold text-lg mb-4 text-slate-900 border-b border-slate-100 pb-2">Kích thước</h3>
                        <div class="grid grid-cols-4 gap-2">
                            @foreach($sizes as $size)
                                <label class="cursor-pointer">
                                    <input type="checkbox" name="size[]" value="{{ $size }}" {{ in_array($size, $selectedSizes ?? [], true) ? 'checked' : '' }} class="peer sr-only">
                                    <div class="text-center py-1.5 rounded-lg border border-slate-200 text-sm font-medium text-slate-600 peer-checked:bg-slate-900 peer-checked:text-white hover:border-slate-400 transition-all">
                                        {{ $size }}
                                    </div>
                                </label>
                            @endforeach
                        </div>
                    </div>
                </form>
            </div>

            <div class="col-span-1 lg:col-span-3">
                
                <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm flex flex-col sm:flex-row justify-between items-center mb-6">
                    <p class="text-slate-500 mb-4 sm:mb-0">
                        Hiển thị <span class="font-bold text-slate-800">{{ $shoes->count() }}</span> sản phẩm
                    </p>
                    <div class="flex items-center gap-3">
                        <label for="sort" class="font-medium text-slate-700 whitespace-nowrap">Sắp xếp theo:</label>
                        <select name="sort" id="sort" form="filterForm" onchange="document.getElementById('filterForm').submit()" 
                                class="bg-slate-50 border border-slate-300 text-slate-900 text-sm rounded-lg focus:ring-orange-500 focus:border-orange-500 block w-full p-2.5 cursor-pointer outline-none">
                            <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Hàng mới nhất</option>
                            <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Giá: Thấp đến Cao</option>
                            <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Giá: Cao đến Thấp</option>
                            <option value="best_selling" {{ request('sort') == 'best_selling' ? 'selected' : '' }}>Bán chạy nhất</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @forelse($shoes as $shoe)
                        <a href="{{ route('shoes.show', $shoe->id) }}" class="block bg-white rounded-xl shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 border border-slate-200">
                            @php
                                $primaryImage = $shoe->images->where('is_primary', true)->first();
                            @endphp
                            @if($primaryImage)
                                @php
                                    $imgSrc = str_starts_with($primaryImage->image_url, 'http') 
                                              ? $primaryImage->image_url 
                                              : asset('images/' . $primaryImage->image_url);
                                @endphp
                                <img src="{{ $imgSrc }}" alt="{{ $shoe->name }}" class="w-full aspect-square object-cover rounded-t-xl">
                            @else
                                <div class="w-full aspect-square bg-slate-200 rounded-t-xl flex items-center justify-center text-slate-500">
                                    <span class="text-sm">Không có ảnh</span>
                                </div>
                            @endif

                            <div class="p-4 flex flex-col">
                                <span class="text-sm text-slate-500 uppercase font-medium mb-1">{{ $shoe->brand->name ?? 'BRAND' }}</span>
                                <h2 class="font-bold text-lg text-slate-800 truncate mb-2">{{ $shoe->name }}</h2>
                                <div class="mt-2">
                                    <span class="text-orange-600 font-black text-xl">{{ number_format($shoe->price, 0, ',', '.') . ' ₫' }}</span>
                                </div>
                            </div>
                        </a>
                    @empty
                        <div class="col-span-full py-12 text-center text-slate-500 bg-white rounded-xl border border-slate-200">
                            <svg class="w-16 h-16 mx-auto mb-4 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            <p class="text-lg">Không tìm thấy sản phẩm nào phù hợp với bộ lọc.</p>
                        </div>
                    @endforelse
                </div>

                <div class="mt-8">
                    {{ $shoes->links() }}
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
