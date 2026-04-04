@extends('layouts.app')

@section('content')
<!-- Hero Banner -->
<div class="relative bg-slate-900 overflow-hidden">
    <div class="absolute inset-0">
        <img src="https://images.unsplash.com/photo-1556906781-9a412961c28c?auto=format&fit=crop&w=1920&q=80" alt="Hero background" class="w-full h-full object-cover opacity-40">
    </div>
    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24 lg:py-32 flex flex-col items-center text-center">
        <h1 class="text-4xl md:text-6xl font-black text-white tracking-tight mb-6">
            BƯỚC ĐI <span class="text-orange-500">ĐỘT PHÁ</span>
        </h1>
        <p class="mt-4 text-xl text-slate-300 max-w-2xl mb-10">
            Khám phá bộ sưu tập giày mới nhất. Thể hiện phong cách cá nhân với những mẫu thiết kế độc quyền chỉ có tại Shoe Store.
        </p>
        <div class="flex gap-4">
            <a href="{{ route('shoes.index') }}" class="bg-orange-600 hover:bg-orange-700 text-white font-bold py-3 px-8 rounded-full transition shadow-lg text-lg">
                Mua Sắm Ngay
            </a>
        </div>
    </div>
</div>

<!-- Featured Categories -->
<div class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-extrabold text-slate-900">Danh Mục Nổi Bật</h2>
            <div class="w-16 h-1 bg-orange-600 mx-auto mt-4"></div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            @foreach($featuredCategories as $category)
            <a href="{{ route('shoes.index', ['category' => [$category->id]]) }}" class="relative group overflow-hidden rounded-2xl shadow-sm border border-slate-100 aspect-4/3 bg-slate-100 flex items-center justify-center">
                <img src="{{ $category->featured_background_url }}" alt="{{ $category->name }}" class="absolute inset-0 w-full h-full object-cover group-hover:scale-105 transition duration-500">
                <div class="absolute inset-0 bg-slate-950/65 group-hover:bg-slate-950/45 transition duration-300"></div>
                <div class="relative z-10 text-center p-6">
                    <h3 class="text-2xl font-bold text-white mb-2">{{ $category->name }}</h3>
                    <p class="text-orange-400 font-medium mb-4">{{ $category->shoes_count }} Sản phẩm</p>
                    <span class="inline-block border-2 border-white text-white font-semibold px-6 py-2 rounded-full hover:bg-white hover:text-slate-900 transition">
                        Khám phá
                    </span>
                </div>
            </a>
            @endforeach
        </div>
    </div>
</div>

<!-- Call To Action -->
<div class="bg-orange-600 py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col md:flex-row items-center justify-between">
        <div class="text-white mb-8 md:mb-0 text-center md:text-left">
            <h2 class="text-3xl font-bold mb-2">Bạn cần tư vấn chọn giày?</h2>
            <p class="text-orange-100 text-lg">Đội ngũ của chúng tôi luôn sẵn sàng hỗ trợ bạn 24/7.</p>
        </div>
        <a href="{{ route('contact') }}" class="bg-white text-orange-600 font-bold py-3 px-8 rounded-lg hover:bg-slate-100 transition shadow-lg text-lg">
            Liên Hệ Ngay
        </a>
    </div>
</div>
@endsection
