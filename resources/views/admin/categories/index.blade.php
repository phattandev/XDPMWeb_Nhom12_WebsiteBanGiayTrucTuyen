@extends('layouts.admin')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-slate-800">Quản lý Danh mục Giày</h1>
</div>

@if(session('success'))
    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-r-lg">
        <p>{{ session('success') }}</p>
    </div>
@endif

@if($errors->any())
    <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-r-lg">
        {{ $errors->first() }}
    </div>
@endif

<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    <div class="bg-white p-6 rounded-xl shadow-sm border border-slate-200 h-fit">
        <h3 class="text-lg font-bold text-slate-800 mb-4 pb-2 border-b">Thêm Danh Mục Mới</h3>
        <form action="{{ route('admin.categories.store') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label class="block text-sm font-medium text-slate-700 mb-2">Tên danh mục <span class="text-red-500">*</span></label>
                <input type="text" name="name" required class="w-full rounded-lg border-slate-300 focus:ring-orange-500 py-2 px-3" placeholder="VD: Giày Nam, Giày Nữ...">
            </div>
            <button type="submit" class="w-full bg-orange-600 text-white font-bold py-2 px-4 rounded-lg hover:bg-orange-700 transition">
                + Lưu Danh Mục
            </button>
        </form>
    </div>

    <div class="md:col-span-2 bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <table class="min-w-full divide-y divide-slate-200">
            <thead class="bg-slate-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase">ID</th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase">Tên Danh Mục</th>
                    <th class="px-6 py-3 text-right text-xs font-bold text-slate-500 uppercase">Thao tác</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200">
                @forelse($categories as $cat)
                <tr class="hover:bg-slate-50">
                    <td class="px-6 py-4 text-sm font-medium text-slate-900">#{{ $cat->id }}</td>
                    <td class="px-6 py-4 text-sm font-bold text-slate-800">{{ $cat->name }}</td>
                    <td class="px-6 py-4 text-right text-sm">
                        <form action="{{ route('admin.categories.destroy', $cat->id) }}" method="POST" onsubmit="return confirm('Xóa danh mục này có thể ảnh hưởng đến các đôi giày đang dùng nó. Bạn chắc chắn chứ?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-900 bg-red-50 px-3 py-1 rounded-md">Xóa</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="3" class="px-6 py-8 text-center text-slate-500">Chưa có danh mục nào.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
        <div class="px-6 py-4 border-t border-slate-200">{{ $categories->links() }}</div>
    </div>
</div>
@endsection