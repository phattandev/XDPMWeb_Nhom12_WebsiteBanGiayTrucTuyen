@extends('layouts.admin')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div class="mb-6 flex flex-col md:flex-row md:justify-between md:items-center gap-4">
        <h1 class="text-2xl font-bold text-slate-800">Quản lý Sản phẩm</h1><br>
        <div class="flex items-center gap-4">
            <!-- Form tìm kiếm -->
            <form method="GET" action="{{ route('admin.products.index') }}" class="flex items-center">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Tìm tên sản phẩm..." 
                    class="rounded-l-lg border-slate-300 focus:ring-orange-500 focus:border-orange-500 text-sm py-2 px-3 w-64">
                <button type="submit" class="bg-slate-800 hover:bg-slate-900 text-white px-4 py-2 text-sm rounded-r-lg border border-slate-800 transition">
                    Tìm
                </button>
            </form>

            <a href="{{ route('admin.products.create') }}" class="bg-orange-600 hover:bg-orange-700 text-white font-medium py-2 px-4 rounded-lg transition shadow-sm whitespace-nowrap">
                + Thêm giày mới
            </a>
        </div>
    </div>
</div>

@if(session('success'))
    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-r-lg">
        <p>{{ session('success') }}</p>
    </div>
@endif

<div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
    <table class="min-w-full divide-y divide-slate-200">
        <thead class="bg-slate-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">ID</th>
                <th class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Tên sản phẩm</th>
                <th class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Danh mục/Hãng</th>
                <th class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Giá bán</th>
                <th class="px-6 py-3 text-right text-xs font-bold text-slate-500 uppercase tracking-wider">Thao tác</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-slate-200">
            @forelse($shoes as $shoe)
            <tr class="hover:bg-slate-50 transition">
                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500">#{{ $shoe->id }}</td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="font-medium text-slate-900">{{ $shoe->name }}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500">
                    {{ $shoe->category->name ?? 'N/A' }} <br>
                    <span class="text-xs text-orange-600 font-semibold">{{ $shoe->brand->name ?? 'N/A' }}</span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap font-bold text-slate-800">
                    {{ number_format($shoe->price, 0, ',', '.') }} ₫
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                    <div class="flex justify-end gap-2">
                        <a href="{{ route('admin.products.edit', $shoe->id) }}" class="text-blue-600 hover:text-blue-900 bg-blue-50 hover:bg-blue-100 px-3 py-1 rounded-md transition">
                            Sửa
                        </a>

                        <form action="{{ route('admin.products.destroy', $shoe->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Bạn có chắc chắn muốn xóa sản phẩm này?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-900 bg-red-50 hover:bg-red-100 px-3 py-1 rounded-md transition">Xóa</button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="px-6 py-10 text-center text-slate-500">Chưa có sản phẩm nào.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    
    <div class="px-6 py-4 border-t border-slate-200">
        {{ $shoes->links() }}
    </div>
</div>
@endsection