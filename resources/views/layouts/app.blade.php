<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shoe Store - Nhóm 12</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-50 font-sans antialiased text-slate-900">
    
    <nav class="bg-white shadow-sm border-b border-slate-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                <div class="flex-shrink-0">
                    <a href="{{ route('home') }}" class="text-2xl font-black text-orange-600 tracking-tighter italic">SHOE<span class="text-slate-800">STORE</span></a>
                </div>
                
                <div class="flex items-center space-x-6">
                    @auth
                        <span class="text-sm font-medium text-slate-600">
                            Chào, <span class="text-orange-600">{{ Auth::user()->name }}</span>
                        </span>
                        
                        <form action="{{ route('logout') }}" method="POST" class="inline m-0">
                            @csrf
                            <button type="submit" class="text-slate-500 hover:text-orange-600 font-medium text-sm transition">
                                Đăng xuất
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="text-slate-600 hover:text-orange-600 font-medium text-sm transition">Đăng nhập</a>
                        <a href="{{ route('register') }}" class="bg-orange-600 text-white px-5 py-2 rounded-lg font-medium text-sm hover:bg-orange-700 transition shadow-sm">Đăng ký</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <main>
        @yield('content')
    </main>

</body>
</html>