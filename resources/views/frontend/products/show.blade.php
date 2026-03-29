@extends('layouts.app')

@section('content')
<div class="bg-slate-50 min-h-screen pb-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        
        <a href="{{ route('shoes.index') }}" class="inline-flex items-center text-slate-500 hover:text-orange-600 font-medium mb-6 transition">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Quay lại cửa hàng
        </a>

        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 sm:p-8 grid grid-cols-1 lg:grid-cols-2 gap-12">
            
            <div>
                @php 
                    $primaryImage = $shoe->images->where('is_primary', true)->first() ?? $shoe->images->first();
                    $mainImgSrc = 'https://via.placeholder.com/600x600?text=No+Image';
                    if($primaryImage) {
                        $mainImgSrc = str_starts_with($primaryImage->image_url, 'http') ? $primaryImage->image_url : asset('images/' . $primaryImage->image_url);
                    }
                @endphp
                <img id="mainImage" src="{{ $mainImgSrc }}" alt="{{ $shoe->name }}" class="w-full aspect-square object-cover rounded-xl mb-4 border border-slate-100 shadow-sm transition-all duration-300">
                
                @if($shoe->images->count() > 1)
                <div class="flex gap-4 overflow-x-auto pb-2">
                    @foreach($shoe->images as $img)
                        @php 
                            $thumbSrc = str_starts_with($img->image_url, 'http') ? $img->image_url : asset('images/' . $img->image_url); 
                        @endphp
                        <img src="{{ $thumbSrc }}" alt="Thumbnail" onclick="document.getElementById('mainImage').src = this.src" class="w-20 h-20 aspect-square object-cover rounded-lg border-2 border-transparent hover:border-orange-500 cursor-pointer transition">
                    @endforeach
                </div>
                @endif
            </div>

            <div class="flex flex-col">
                <h1 class="text-3xl sm:text-4xl font-extrabold text-slate-900 mb-2">{{ $shoe->name }}</h1>
                <p class="text-sm text-slate-500 mb-4">
                    Danh mục: <span class="font-medium text-slate-700">{{ $shoe->category->name ?? 'Đang cập nhật' }}</span> | 
                    Thương hiệu: <span class="font-medium text-slate-700 uppercase">{{ $shoe->brand->name ?? 'Đang cập nhật' }}</span>
                </p>
                
                <p class="text-4xl text-orange-600 font-black mb-6">{{ number_format($shoe->price, 0, ',', '.') }} ₫</p>
                @php 
                    $totalStock = $shoe->variants->sum('stock_quantity'); 
                @endphp
                <div class="mb-6">
                    @if($totalStock > 0)
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-bold bg-green-100 text-green-700 border border-green-200">
                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            Còn sẵn {{ $totalStock }} sản phẩm
                        </span>
                    @else
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-bold bg-red-100 text-red-700 border border-red-200">
                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            Tạm thời hết hàng
                        </span>
                    @endif
                </div>
                
                <div class="prose text-slate-600 mb-8 leading-relaxed">
                    <p>{{ $shoe->description }}</p>
                </div>

                <form action="{{ route('cart.add') }}" method="POST" class="bg-slate-50 p-6 rounded-xl border border-slate-200 mt-auto">
                    @csrf
                    <input type="hidden" name="shoe_id" value="{{ $shoe->id }}">
                    
                    <label class="block text-sm font-bold text-slate-700 mb-2">Chọn Size & Màu sắc:</label>
                    <select name="shoe_variant_id" required class="w-full border-slate-300 rounded-lg p-3 mb-6 focus:ring-orange-500 focus:border-orange-500 bg-white">
                        <option value="" disabled selected>-- Vui lòng chọn phân loại --</option>
                        @foreach($shoe->variants as $variant)
                            <option value="{{ $variant->id }}" {{ $variant->stock_quantity <= 0 ? 'disabled' : '' }}>
                                Size: {{ $variant->size }} - Màu: {{ $variant->color }} 
                                @if($variant->stock_quantity > 0)
                                    (Còn {{ $variant->stock_quantity }} sản phẩm)
                                @else
                                    (Đã hết hàng)
                                @endif
                            </option>
                        @endforeach
                    </select>
                    
                    <div class="flex flex-col sm:flex-row gap-4">
                        <div class="flex items-center bg-white border border-slate-300 rounded-lg overflow-hidden">
                            <span class="px-4 text-slate-500 font-medium">SL:</span>
                            <input type="number" name="quantity" value="1" min="1" class="w-20 border-0 p-3 text-center font-bold focus:ring-0">
                        </div>
                        <button type="submit" class="flex-1 bg-orange-600 text-white py-3 rounded-lg font-bold hover:bg-orange-700 transition flex items-center justify-center gap-2 shadow-sm">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                            Thêm vào giỏ hàng
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="mt-16 bg-white rounded-2xl shadow-sm border border-slate-200 p-6 sm:p-8">
            <h2 class="text-2xl font-bold text-slate-900 mb-6 border-b border-slate-100 pb-4">Đánh giá từ khách hàng</h2>
            
            <div class="space-y-6">
                @forelse($shoe->reviews as $review)
                    <div class="bg-slate-50 p-5 rounded-xl border border-slate-100">
                        <div class="flex items-center justify-between mb-3">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-orange-100 text-orange-600 rounded-full flex items-center justify-center font-bold">
                                    {{ substr($review->user->name ?? 'K', 0, 1) }}
                                </div>
                                <div>
                                    <h4 class="font-bold text-slate-800">{{ $review->user->name ?? 'Khách hàng ẩn danh' }}</h4>
                                    <span class="text-xs text-slate-500">{{ $review->created_at->format('d/m/Y') }}</span>
                                </div>
                            </div>
                            <div class="flex text-yellow-400">
                                @for($i = 1; $i <= 5; $i++)
                                    <svg class="w-5 h-5 {{ $i <= $review->rating ? 'text-yellow-400' : 'text-slate-300' }}" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                                @endfor
                            </div>
                        </div>
                        <p class="text-slate-700">{{ $review->comment }}</p>
                    </div>
                @empty
                    <div class="text-center py-8">
                        <p class="text-slate-500">Chưa có đánh giá nào cho sản phẩm này. Hãy là người đầu tiên sở hữu và đánh giá nhé!</p>
                    </div>
                @endforelse
            </div>
        </div>

        @if($relatedShoes->count() > 0)
        <div class="mt-16">
            <div class="flex items-center justify-between mb-8">
                <h2 class="text-2xl font-bold text-slate-900">Có thể bạn sẽ thích</h2>
                <a href="{{ route('shoes.index', ['category' => $shoe->category_id]) }}" class="text-orange-600 hover:text-orange-700 font-medium">Xem thêm &rarr;</a>
            </div>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($relatedShoes as $related)
                    <a href="{{ route('shoes.show', $related->id) }}" class="block bg-white rounded-xl shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 border border-slate-200">
                        @php
                            $relImage = $related->images->where('is_primary', true)->first();
                            $relImgSrc = 'https://via.placeholder.com/300x300?text=No+Image';
                            if($relImage) {
                                $relImgSrc = str_starts_with($relImage->image_url, 'http') ? $relImage->image_url : asset('images/' . $relImage->image_url);
                            }
                        @endphp
                        <img src="{{ $relImgSrc }}" alt="{{ $related->name }}" class="w-full aspect-square object-cover rounded-t-xl">
                        
                        <div class="p-4 flex flex-col">
                            <span class="text-sm text-slate-500 uppercase font-medium mb-1">{{ $related->brand->name ?? 'BRAND' }}</span>
                            <h3 class="font-bold text-lg text-slate-800 truncate mb-2">{{ $related->name }}</h3>
                            <div class="mt-2">
                                <span class="text-orange-600 font-black">{{ number_format($related->price, 0, ',', '.') }} ₫</span>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
        @endif

    </div>
</div>
@endsection