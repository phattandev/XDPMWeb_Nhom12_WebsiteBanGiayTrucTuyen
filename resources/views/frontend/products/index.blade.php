<!-- Danh sách sản phẩm, bộ lọc -->
 @extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 flex gap-8">
    <!-- Sidebar Bộ lọc -->
    <aside class="w-1/4 hidden md:block bg-white p-6 rounded-2xl shadow-sm border border-slate-100 h-fit">
        <h3 class="font-bold text-lg mb-4 text-slate-800">Danh Mục</h3>
        <ul class="space-y-2 mb-8 text-slate-600">
            @foreach($categories as $cat)
                <li><a href="#" class="hover:text-orange-600 transition">{{ $cat->name }}</a></li>
            @endforeach
        </ul>

        <h3 class="font-bold text-lg mb-4 text-slate-800">Thương Hiệu</h3>
        <ul class="space-y-2 text-slate-600">
            @foreach($brands as $brand)
                <li><a href="#" class="hover:text-orange-600 transition">{{ $brand->name }}</a></li>
            @endforeach
        </ul>
    </aside>

    <!-- Danh sách sản phẩm -->
    <main class="flex-1">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($shoes as $shoe)
                <div class="bg-white p-4 rounded-xl shadow-sm border border-slate-100 hover:shadow-md transition">
                    @php $img = $shoe->images->where('is_primary', true)->first(); @endphp
                    <img src="{{ $img ? asset('images/' . $img->image_url) : 'https://via.placeholder.com/400x300' }}" 
                         alt="{{ $shoe->name }}" class="w-full h-48 object-cover rounded-lg mb-4">
                    
                    <h3 class="font-bold text-lg text-slate-800">{{ $shoe->name }}</h3>
                    <p class="text-orange-600 font-bold text-lg my-2">{{ number_format($shoe->price, 0, ',', '.') }} đ</p>
                    
                    <a href="{{ route('shoes.show', $shoe->id) }}" class="block w-full text-center bg-slate-100 text-slate-800 py-2 rounded-lg hover:bg-slate-200 transition font-medium">Chi tiết</a>
                </div>
            @endforeach
        </div>

        <!-- Thanh phân trang -->
        <div class="mt-8">
            {{ $shoes->links() }}
        </div>
    </main>
</div>
@endsection