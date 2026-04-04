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
    <div class="bg-white p-6 rounded-xl shadow-sm border border-slate-200 h-fit transition-all duration-300" id="form-box">
        <h3 class="text-lg font-bold text-slate-800 mb-1" id="form-title">Thêm Thương Hiệu Mới</h3>
        <p class="text-xs text-slate-500 mb-4 pb-3 border-b border-slate-100 italic" id="form-hint">Nhấn "Sửa" ở danh sách bên phải để cập nhật.</p>

        <form id="brand-form" action="{{ route('admin.brands.store') }}" method="POST">
            @csrf

            <div id="method-container"></div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-slate-700 mb-2">Tên thương hiệu <span class="text-red-500">*</span></label>
                <input type="text" id="brand-name" name="name" required class="w-full rounded-lg border-slate-300 focus:ring-orange-500 focus:border-orange-500 py-2 px-3 transition-colors" placeholder="VD: Nike, Adidas, Puma...">
            </div>

            <div class="flex gap-2">
                <button type="submit" id="submit-btn" class="w-full bg-orange-600 text-white font-bold py-2 px-4 rounded-lg hover:bg-orange-700 transition">
                    + Thêm Mới
                </button>

                <button type="button" id="cancel-btn" onclick="resetForm()" class="hidden w-1/3 bg-slate-200 text-slate-700 font-bold py-2 px-4 rounded-lg hover:bg-slate-300 transition">
                    Hủy
                </button>
            </div>
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
                <tr class="hover:bg-slate-50 transition">
                    <td class="px-6 py-4 text-sm font-medium text-slate-900">#{{ $brand->id }}</td>
                    <td class="px-6 py-4 text-sm font-bold text-slate-800">{{ $brand->name }}</td>
                    <td class="px-6 py-4 text-right text-sm">
                        <div class="flex justify-end gap-2">
                            <button
                                type="button"
                                data-brand-id="{{ $brand->id }}"
                                data-brand-name="{{ $brand->name }}"
                                onclick="editBrandFromButton(this)"
                                class="text-blue-600 hover:text-blue-900 bg-blue-50 px-3 py-1 rounded-md transition"
                            >
                                Sửa
                            </button>

                            <form action="{{ route('admin.brands.destroy', $brand->id) }}" method="POST" onsubmit="return confirm('Bạn chắc chắn muốn xóa thương hiệu này?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900 bg-red-50 px-3 py-1 rounded-md transition">Xóa</button>
                            </form>
                        </div>
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

<script>
    function editBrandFromButton(button) {
        editBrand(
            button.getAttribute('data-brand-id'),
            button.getAttribute('data-brand-name')
        );
    }

    function editBrand(id, name) {
        document.getElementById('form-title').innerText = 'Sửa Thương Hiệu';
        document.getElementById('form-title').classList.replace('text-slate-800', 'text-blue-600');
        document.getElementById('form-hint').innerHTML = 'Đang chỉnh sửa: <span class="font-bold text-slate-800">' + name + '</span>';

        document.getElementById('form-box').classList.add('border-blue-400', 'ring-4', 'ring-blue-50');

        const inputName = document.getElementById('brand-name');
        inputName.value = name;
        inputName.focus();

        document.getElementById('brand-form').action = "{{ url('admin/brands') }}/" + id;
        document.getElementById('method-container').innerHTML = '<input type="hidden" name="_method" value="PUT">';

        const submitBtn = document.getElementById('submit-btn');
        submitBtn.innerText = 'Cập nhật';
        submitBtn.classList.replace('bg-orange-600', 'bg-blue-600');
        submitBtn.classList.replace('hover:bg-orange-700', 'hover:bg-blue-700');

        document.getElementById('cancel-btn').classList.remove('hidden');
    }

    function resetForm() {
        document.getElementById('form-title').innerText = 'Thêm Thương Hiệu Mới';
        document.getElementById('form-title').classList.replace('text-blue-600', 'text-slate-800');
        document.getElementById('form-hint').innerText = 'Nhấn "Sửa" ở danh sách bên phải để cập nhật.';

        document.getElementById('form-box').classList.remove('border-blue-400', 'ring-4', 'ring-blue-50');
        document.getElementById('brand-name').value = '';

        document.getElementById('brand-form').action = "{{ route('admin.brands.store') }}";
        document.getElementById('method-container').innerHTML = '';

        const submitBtn = document.getElementById('submit-btn');
        submitBtn.innerText = '+ Thêm Mới';
        submitBtn.classList.replace('bg-blue-600', 'bg-orange-600');
        submitBtn.classList.replace('hover:bg-blue-700', 'hover:bg-orange-700');

        document.getElementById('cancel-btn').classList.add('hidden');
    }
</script>
@endsection
