<!-- Khung giao diện cho trang quản trị Admin (chứa Sidebar, Topbar). -->
 <!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Shoe Store</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-100 font-sans flex min-h-screen">
    
    <aside class="w-64 bg-slate-900 text-white flex flex-col shadow-xl z-10">
        <div class="p-6 text-2xl font-black text-orange-500 italic border-b border-slate-800">
            SHOE<span class="text-white">STORE</span> <span class="text-sm font-normal not-italic text-slate-400">ADMIN</span>
        </div>
        <nav class="flex-1 p-4 space-y-2">
            <a href="{{ route('admin.dashboard') }}" class="block px-4 py-2.5 bg-orange-600 rounded-lg text-white font-medium shadow-md">Bảng điều khiển</a>
            <a href="#" class="block px-4 py-2.5 hover:bg-slate-800 rounded-lg text-slate-300 transition">Quản lý Sản phẩm</a>
            <a href="#" class="block px-4 py-2.5 hover:bg-slate-800 rounded-lg text-slate-300 transition">Quản lý Đơn hàng</a>
            <a href="#" class="block px-4 py-2.5 hover:bg-slate-800 rounded-lg text-slate-300 transition">Quản lý Khách hàng</a>
        </nav>
    </aside>

    <div class="flex-1 flex flex-col">
        <header class="bg-white shadow-sm h-16 flex items-center justify-between px-8 border-b border-slate-200">
            <h2 class="font-bold text-xl text-slate-800">Trang chủ Quản trị</h2>
            <div class="flex items-center gap-6">
                <span class="text-sm font-medium text-slate-600">
                    Xin chào, <span class="text-orange-600">{{ Auth::user()->name }}</span>
                </span>
                <form action="{{ route('logout') }}" method="POST" class="m-0">
                    @csrf
                    <button type="submit" class="text-sm bg-red-50 text-red-600 px-4 py-2 rounded-lg hover:bg-red-100 font-medium transition">Đăng xuất</button>
                </form>
            </div>
        </header>

        <main class="p-8 flex-1 overflow-y-auto">
            @yield('content')
        </main>
    </div>

</body>
</html>