<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Về Chúng Tôi - Shoe Store</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-50 font-sans antialiased">

    <!-- Header / Navigation đơn giản -->
    <nav class="bg-white shadow-md sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                <div class="flex-shrink-0 flex items-center">
                    <span class="text-2xl font-black text-orange-600 tracking-tighter italic">SHOE<span class="text-slate-800">STORE</span></span>
                </div>
                <div class="hidden md:block">
                    <div class="ml-10 flex items-baseline space-x-4">
                        <a href="/" class="text-gray-600 hover:text-orange-600 px-3 py-2 font-medium">Trang chủ</a>
                        <a href="/about" class="text-orange-600 px-3 py-2 font-bold border-b-2 border-orange-600">Giới thiệu</a>
                        <a href="#" class="text-gray-600 hover:text-orange-600 px-3 py-2 font-medium">Sản phẩm</a>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main>
        <!-- Section 1: Hero -->
        <section class="relative bg-white py-16 lg:py-24 overflow-hidden">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="relative z-10 lg:grid lg:grid-cols-2 lg:gap-8 items-center">
                    <div class="mb-12 lg:mb-0">
                        <h1 class="text-4xl sm:text-5xl font-extrabold text-slate-900 mb-6 leading-tight">
                            Bước đi tự tin, <br>
                            <span class="text-orange-600">Khẳng định phong cách.</span>
                        </h1>
                        <p class="text-lg text-slate-600 mb-8 max-w-lg">
                            Chào mừng bạn đến với Shoe Store! Chúng tôi không chỉ bán những đôi giày, chúng tôi mang đến cho bạn trải nghiệm tuyệt vời nhất trên mỗi bước đi. Từ những mẫu Sneaker năng động đến giày Tây lịch lãm.
                        </p>
                        <div class="flex space-x-4">
                            <button class="bg-slate-900 text-white px-8 py-3 rounded-lg font-semibold hover:bg-orange-600 transition shadow-lg">Xem bộ sưu tập</button>
                            <button class="border-2 border-slate-200 text-slate-700 px-8 py-3 rounded-lg font-semibold hover:bg-slate-50 transition">Liên hệ</button>
                        </div>
                    </div>
                    <div class="relative">
                        <div class="absolute -top-10 -left-10 w-64 h-64 bg-orange-100 rounded-full mix-blend-multiply filter blur-3xl opacity-70 animate-pulse"></div>
                        <img class="relative rounded-2xl shadow-2xl w-full object-cover transform hover:-rotate-2 transition duration-500" 
                             src="https://images.unsplash.com/photo-1549298916-b41d501d3772?auto=format&fit=crop&w=1000&q=80" 
                             alt="Premium Shoes">
                    </div>
                </div>
            </div>
        </section>

        <!-- Section 2: Stats (Những con số) -->
        <section class="bg-slate-900 py-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center text-white">
                    <div>
                        <div class="text-4xl font-bold text-orange-500 mb-1">10k+</div>
                        <div class="text-slate-400 text-sm">Khách hàng hài lòng</div>
                    </div>
                    <div>
                        <div class="text-4xl font-bold text-orange-500 mb-1">500+</div>
                        <div class="text-slate-400 text-sm">Mẫu mã đa dạng</div>
                    </div>
                    <div>
                        <div class="text-4xl font-bold text-orange-500 mb-1">15+</div>
                        <div class="text-slate-400 text-sm">Chi nhánh toàn quốc</div>
                    </div>
                    <div>
                        <div class="text-4xl font-bold text-orange-500 mb-1">24/7</div>
                        <div class="text-slate-400 text-sm">Hỗ trợ tận tâm</div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Section 3: Cam kết -->
        <section class="py-20 bg-slate-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center mb-16">
                <h2 class="text-3xl font-bold text-slate-900">Cam kết của chúng tôi</h2>
                <div class="w-20 h-1 bg-orange-600 mx-auto mt-4"></div>
            </div>
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Card 1 -->
                <div class="bg-white p-8 rounded-xl shadow-sm hover:shadow-xl transition-shadow border-t-4 border-orange-500">
                    <div class="text-orange-600 mb-4">
                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold mb-3 text-slate-800">Chính hãng 100%</h3>
                    <p class="text-slate-600 text-sm leading-relaxed">Mọi sản phẩm tại cửa hàng đều được nhập khẩu chính ngạch, đảm bảo nguồn gốc và chất lượng cao nhất.</p>
                </div>
                <!-- Card 2 -->
                <div class="bg-white p-8 rounded-xl shadow-sm hover:shadow-xl transition-shadow border-t-4 border-slate-900">
                    <div class="text-slate-900 mb-4">
                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold mb-3 text-slate-800">Đổi trả 30 ngày</h3>
                    <p class="text-slate-600 text-sm leading-relaxed">Bạn không vừa ý? Bạn muốn đổi size? Chúng tôi hỗ trợ đổi trả linh hoạt trong vòng 30 ngày hoàn toàn miễn phí.</p>
                </div>
                <!-- Card 3 -->
                <div class="bg-white p-8 rounded-xl shadow-sm hover:shadow-xl transition-shadow border-t-4 border-orange-500">
                    <div class="text-orange-600 mb-4">
                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold mb-3 text-slate-800">Giao hàng siêu tốc</h3>
                    <p class="text-slate-600 text-sm leading-relaxed">Nhận hàng ngay trong ngày tại nội thành. Miễn phí vận chuyển cho đơn hàng từ 1.000.000đ.</p>
                </div>
            </div>
        </section>
    </main>

    <footer class="bg-slate-100 py-10 border-t border-slate-200">
        <div class="max-w-7xl mx-auto px-4 text-center">
            <p class="text-slate-500 text-sm">© 2024 Shoe Store Laravel Project. Designed for testing purposes.</p>
        </div>
    </footer>

</body>
</html>