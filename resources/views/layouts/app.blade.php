<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shoe Store - Nhóm 12</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-slate-50 font-sans antialiased text-slate-900 flex flex-col min-h-screen">
    
    <nav class="bg-white shadow-sm border-b border-slate-200 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                <div class="flex-shrink-0 flex items-center gap-6">
                    <a href="{{ route('home') }}" class="text-2xl font-black text-orange-600 tracking-tighter italic">SHOE<span class="text-slate-800">STORE</span></a>
                    <!-- <a href="{{ route('shoes.index') }}" class="hidden md:block text-slate-600 hover:text-orange-600 font-medium transition">Sản phẩm</a> -->
                     <div class="hidden md:flex items-center space-x-6 mt-1">
                        <a href="{{ route('home') }}" class="font-medium transition {{ request()->routeIs('home') ? 'text-orange-600' : 'text-slate-600 hover:text-orange-600' }}">
                            Trang chủ
                        </a>
                        <a href="{{ route('shoes.index') }}" class="font-medium transition {{ request()->routeIs('shoes.*') ? 'text-orange-600' : 'text-slate-600 hover:text-orange-600' }}">
                            Sản phẩm
                        </a>
                        <a href="{{ route('about') }}" class="font-medium transition {{ request()->routeIs('about') ? 'text-orange-600' : 'text-slate-600 hover:text-orange-600' }}">
                            Giới thiệu
                        </a>
                        <a href="{{ route('contact') }}" class="font-medium transition {{ request()->routeIs('contact') ? 'text-orange-600' : 'text-slate-600 hover:text-orange-600' }}">
                            Liên hệ
                        </a>
                    </div>
                </div>
                
                <div class="flex items-center space-x-6">
                    <a href="{{ route('cart.index') }}" class="relative text-slate-600 hover:text-orange-600 transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        @if(session('cart') && count(session('cart')) > 0)
                            <span class="absolute -top-2 -right-2 bg-orange-600 text-white text-xs font-bold px-1.5 py-0.5 rounded-full">{{ count(session('cart')) }}</span>
                        @endif
                    </a>

                    @auth
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" @click.away="open = false" class="flex items-center gap-1 text-sm font-medium text-slate-600 hover:text-orange-600 transition focus:outline-none">
                                Chào, <span class="text-orange-600 font-bold ml-1">{{ Auth::user()->name }}</span>
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </button>
                            
                            <div x-show="open" class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 ring-1 ring-black ring-opacity-5 z-50" style="display: none;">
                                @if(Auth::user()->role === 'admin')
                                    <a href="{{ route('admin.dashboard') }}" class="block px-4 py-2 text-sm text-slate-700 hover:bg-slate-100">Vào trang Quản trị</a>
                                @endif
                                <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-slate-700 hover:bg-slate-100">Thông tin cá nhân</a>
                                <a href="{{ route('my-orders') }}" class="block px-4 py-2 text-sm text-slate-700 hover:bg-slate-100">Đơn hàng của tôi</a>
                                <form action="{{ route('logout') }}" method="POST" class="m-0">
                                    @csrf
                                    <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-slate-100 font-medium">Đăng xuất</button>
                                </form>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="text-slate-600 hover:text-orange-600 font-medium text-sm transition">Đăng nhập</a>
                        <a href="{{ route('register') }}" class="bg-orange-600 text-white px-5 py-2 rounded-lg font-medium text-sm hover:bg-orange-700 transition shadow-sm">Đăng ký</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <main class="flex-grow">
        @yield('content')
    </main>

    <footer class="bg-slate-900 text-slate-300 py-12 border-t border-slate-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid grid-cols-1 md:grid-cols-3 gap-8">
            <div>
                <span class="text-2xl font-black text-orange-500 tracking-tighter italic mb-4 block">SHOE<span class="text-white">STORE</span></span>
                <p class="text-sm text-slate-400 mb-4">Hệ thống cửa hàng giày thể thao uy tín hàng đầu. Cam kết chính hãng, giao hàng siêu tốc, hỗ trợ đổi trả 30 ngày.</p>
            </div>
            <div>
                <h3 class="text-white font-bold text-lg mb-4">Chính sách hỗ trợ</h3>
                <ul class="space-y-2 text-sm">
                    <li><a href="#" class="hover:text-orange-500 transition">Hướng dẫn mua hàng</a></li>
                    <li><a href="#" class="hover:text-orange-500 transition">Chính sách bảo hành</a></li>
                    <li><a href="#" class="hover:text-orange-500 transition">Chính sách đổi trả</a></li>
                </ul>
            </div>
            <div>
                <h3 class="text-white font-bold text-lg mb-4">Liên hệ (Nhóm 12)</h3>
                <ul class="space-y-2 text-sm text-slate-400">
                    <li>📍 180 Cao Lỗ, Phường 4, Quận 8, TP.HCM</li>
                    <li>📞 090 123 4567</li>
                    <li>✉️ hotro@shoestore.com</li>
                </ul>
            </div>
        </div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-12 pt-8 border-t border-slate-800 text-center text-sm text-slate-500">
            © 2024 Shoe Store. Thực hiện bởi Nhóm 12 - Lớp D22_TH02.
        </div>
    </footer>

</body>
</html>