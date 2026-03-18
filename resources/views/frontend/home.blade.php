<!-- Trang chủ -->
 @extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="bg-white rounded-2xl shadow-sm p-8 text-center border-t-4 border-orange-500">
        <h1 class="text-3xl font-extrabold text-slate-800 mb-4">
            Chào mừng đến với <span class="text-orange-600 italic">Shoe Store</span>!
        </h1>
        <p class="text-slate-600 mb-8 text-lg">
            Bạn đã đăng nhập thành công. Hãy bắt đầu khám phá những đôi giày tuyệt vời nhất.
        </p>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-8">
            <div class="p-6 bg-slate-50 rounded-xl border border-slate-100 hover:shadow-md transition cursor-pointer">
                <div class="text-4xl mb-3">👟</div>
                <h3 class="font-bold text-lg text-slate-800 mb-2">Giày Mới Về</h3>
                <p class="text-sm text-slate-500">Khám phá bộ sưu tập giày thể thao và giày tây mới nhất.</p>
            </div>
            
            <div class="p-6 bg-slate-50 rounded-xl border border-slate-100 hover:shadow-md transition cursor-pointer">
                <div class="text-4xl mb-3">🔥</div>
                <h3 class="font-bold text-lg text-slate-800 mb-2">Khuyến Mãi</h3>
                <p class="text-sm text-slate-500">Săn ngay các mã giảm giá và ưu đãi lên đến 50%.</p>
            </div>
            
            <div class="p-6 bg-slate-50 rounded-xl border border-slate-100 hover:shadow-md transition cursor-pointer">
                <div class="text-4xl mb-3">📦</div>
                <h3 class="font-bold text-lg text-slate-800 mb-2">Đơn Hàng</h3>
                <p class="text-sm text-slate-500">Theo dõi trạng thái giao hàng các sản phẩm của bạn.</p>
            </div>
        </div>
    </div>
</div>
@endsection