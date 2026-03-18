<!-- Chi tiết 1 sản phẩm -->
 @extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-8 grid grid-cols-1 md:grid-cols-2 gap-12">
        <!-- Ảnh sản phẩm -->
        <div>
            @php $primaryImage = $shoe->images->where('is_primary', true)->first(); @endphp
            <img src="{{ $primaryImage ? asset('images/' . $primaryImage->image_url) : 'https://via.placeholder.com/600x500' }}" 
                 alt="{{ $shoe->name }}" class="w-full h-96 object-cover rounded-xl mb-4">
        </div>

        <!-- Thông tin và Form đặt hàng -->
        <div>
            <h1 class="text-3xl font-extrabold text-slate-900 mb-2">{{ $shoe->name }}</h1>
            <p class="text-sm text-slate-500 mb-4">Danh mục: <span class="font-medium">{{ $shoe->category->name ?? 'N/A' }}</span> | Thương hiệu: <span class="font-medium">{{ $shoe->brand->name ?? 'N/A' }}</span></p>
            
            <p class="text-4xl text-orange-600 font-black mb-6">{{ number_format($shoe->price, 0, ',', '.') }} đ</p>
            
            <div class="prose text-slate-600 mb-8">
                <p>{{ $shoe->description }}</p>
            </div>

            <form action="{{ route('cart.add') }}" method="POST" class="bg-slate-50 p-6 rounded-xl border border-slate-200">
                @csrf
                <input type="hidden" name="shoe_id" value="{{ $shoe->id }}">
                
                <label class="block text-sm font-bold text-slate-700 mb-2">Chọn Size & Màu sắc:</label>
                <select name="shoe_variant_id" required class="w-full border-slate-300 rounded-lg p-3 mb-6 focus:ring-orange-500 focus:border-orange-500">
                    <option value="" disabled selected>-- Vui lòng chọn --</option>
                    @foreach($shoe->variants as $variant)
                        <option value="{{ $variant->id }}" {{ $variant->stock_quantity <= 0 ? 'disabled' : '' }}>
                            Size: {{ $variant->size }} - Màu: {{ $variant->color }} 
                            @if($variant->stock_quantity > 0)
                                (Còn {{ $variant->stock_quantity }} đôi)
                            @else
                                (Đã hết hàng)
                            @endif
                        </option>
                    @endforeach
                </select>
                
                <div class="flex gap-4">
                    <input type="number" name="quantity" value="1" min="1" class="w-24 border-slate-300 rounded-lg p-3 text-center font-bold focus:ring-orange-500 focus:border-orange-500">
                    <button type="submit" class="flex-1 bg-orange-600 text-white py-3 rounded-lg font-bold hover:bg-orange-700 transition flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        Thêm vào giỏ
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection