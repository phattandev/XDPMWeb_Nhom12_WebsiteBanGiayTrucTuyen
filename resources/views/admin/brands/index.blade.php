@extends('layouts.admin')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-slate-800">Quản lý Thương hiệu</h1>
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
        <h3 class="text-lg font-bold text-slate-800 mb-4 pb-2 border-b">Thêm Thương Hiệu</h3>
        <form action="{{ route('admin.brands.store') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label class="block text-sm font-medium text-slate-700 mb-2">Tên thương hiệu <span class="text-red-500">*</span></label>
                <input type="text" name="name" required class="w-full rounded-lg border-slate-300 focus:ring-orange-500 py-2 px-3" placeholder="VD: Nike, Adidas, Puma...">
            </div>
            <button type="submit" class="w-full bg-orange-600 text-white font-bold py-2 px-4 rounded-lg hover:bg-orange-700 transition">
                + Lưu Thương Hiệu
            </button>
        </form>
    </div>

    <div class="md:col-span-2 bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <table class="min-w-full divide-y divide-slate-200">
            <thead class="bg-slate-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase">ID</th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase">Tên Thương Hiệu</th>
                    <th class="px-6 py-3 text-right text-xs font-bold text-slate-500 uppercase">Thao tác</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200">
                @forelse($brands as $brand)
                <tr class="hover:bg-slate-50">
                    <td class="px-6 py-4 text-sm font-medium text-slate-900">#{{ $brand->id }}</td>
                    <td class="px-6 py-4 text-sm font-bold text-slate-800">{{ $brand->name }}</td>
                    <td class="px-6 py-4 text-right text-sm">
                        <form action="{{ route('admin.brands.destroy', $brand->id) }}" method="POST" onsubmit="return confirm('Bạn chắc chắn muốn xóa thương hiệu này?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-900 bg-red-50 px-3 py-1 rounded-md">Xóa</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="3" class="px-6 py-8 text-center text-slate-500">Chưa có thương hiệu nào.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
        <div class="px-6 py-4 border-t border-slate-200">{{ $brands->links() }}</div>
    </div>
</div>
@endsection