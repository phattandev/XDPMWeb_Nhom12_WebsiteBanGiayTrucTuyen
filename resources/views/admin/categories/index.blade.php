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
    
    <!-- KHU VỰC FORM (TRÁI) -->
    <div class="bg-white p-6 rounded-xl shadow-sm border border-slate-200 h-fit transition-all duration-300" id="form-box">
        <h3 class="text-lg font-bold text-slate-800 mb-1" id="form-title">Thêm Danh Mục Mới</h3>
        <p class="text-xs text-slate-500 mb-4 pb-3 border-b border-slate-100 italic" id="form-hint">Nhấn "Sửa" ở danh sách bên phải để cập nhật.</p>
        
        <form id="category-form" action="{{ route('admin.categories.store') }}" method="POST">
            @csrf
            
            <!-- Vùng chứa thẻ @method('PUT') sẽ được JS tự động chèn vào khi bấm Sửa -->
            <div id="method-container"></div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-slate-700 mb-2">Tên danh mục <span class="text-red-500">*</span></label>
                <input type="text" id="category-name" name="name" required class="w-full rounded-lg border-slate-300 focus:ring-orange-500 focus:border-orange-500 py-2 px-3 transition-colors" placeholder="VD: Giày Nam, Giày Nữ...">
            </div>
            
            <div class="flex gap-2">
                <button type="submit" id="submit-btn" class="w-full bg-orange-600 text-white font-bold py-2 px-4 rounded-lg hover:bg-orange-700 transition">
                    + Thêm Mới
                </button>
                
                <!-- Nút Hủy (Mặc định bị ẩn đi) -->
                <button type="button" id="cancel-btn" onclick="resetForm()" class="hidden w-1/3 bg-slate-200 text-slate-700 font-bold py-2 px-4 rounded-lg hover:bg-slate-300 transition">
                    Hủy
                </button>
            </div>
        </form>
    </div>

    <!-- KHU VỰC BẢNG (PHẢI) -->
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
                <tr class="hover:bg-slate-50 transition">
                    <td class="px-6 py-4 text-sm font-medium text-slate-900">#{{ $cat->id }}</td>
                    <td class="px-6 py-4 text-sm font-bold text-slate-800">{{ $cat->name }}</td>
                    <td class="px-6 py-4 text-right text-sm">
                        <div class="flex justify-end gap-2">
                            
                            <!-- NÚT SỬA: Gọi thẳng hàm JS và truyền ID + Tên vào -->
                            <button type="button" onclick="editCategory({{ $cat->id }}, '{{ addslashes($cat->name) }}')" class="text-blue-600 hover:text-blue-900 bg-blue-50 px-3 py-1 rounded-md transition">
                                Sửa
                            </button>
                            
                            <form action="{{ route('admin.categories.destroy', $cat->id) }}" method="POST" onsubmit="return confirm('Bạn chắc chắn chứ?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900 bg-red-50 px-3 py-1 rounded-md transition">Xóa</button>
                            </form>
                        </div>
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

<!-- ĐOẠN SCRIPT ĐIỀU KHIỂN FORM -->
<script>
    function editCategory(id, name) {
        // 1. Đổi tiêu đề và chú thích
        document.getElementById('form-title').innerText = 'Sửa Danh Mục';
        document.getElementById('form-title').classList.replace('text-slate-800', 'text-blue-600');
        document.getElementById('form-hint').innerHTML = 'Đang chỉnh sửa: <span class="font-bold text-slate-800">' + name + '</span>';
        
        // Tạo hiệu ứng viền xanh nổi bật cho khu vực Form
        document.getElementById('form-box').classList.add('border-blue-400', 'ring-4', 'ring-blue-50');
        
        // 2. Điền tên danh mục vào ô input và tự động nháy chuột vào đó
        let inputName = document.getElementById('category-name');
        inputName.value = name;
        inputName.focus();
        
        // 3. Cập nhật action của form chĩa về hàm Update
        let updateUrl = "{{ url('admin/categories') }}/" + id;
        document.getElementById('category-form').action = updateUrl;
        
        // 4. Giả lập phương thức PUT của Laravel
        document.getElementById('method-container').innerHTML = '<input type="hidden" name="_method" value="PUT">';
        
        // 5. Đổi màu nút lưu thành Xanh và hiện nút Hủy
        let submitBtn = document.getElementById('submit-btn');
        submitBtn.innerText = 'Cập nhật';
        submitBtn.classList.replace('bg-orange-600', 'bg-blue-600');
        submitBtn.classList.replace('hover:bg-orange-700', 'hover:bg-blue-700');
        
        document.getElementById('cancel-btn').classList.remove('hidden');
    }

    function resetForm() {
        // Hàm này khôi phục Form về trạng thái Thêm Mới ban đầu
        document.getElementById('form-title').innerText = 'Thêm Danh Mục Mới';
        document.getElementById('form-title').classList.replace('text-blue-600', 'text-slate-800');
        document.getElementById('form-hint').innerText = 'Nhấn "Sửa" ở danh sách bên phải để cập nhật.';
        
        document.getElementById('form-box').classList.remove('border-blue-400', 'ring-4', 'ring-blue-50');
        document.getElementById('category-name').value = '';
        
        document.getElementById('category-form').action = "{{ route('admin.categories.store') }}";
        document.getElementById('method-container').innerHTML = '';
        
        let submitBtn = document.getElementById('submit-btn');
        submitBtn.innerText = '+ Thêm Mới';
        submitBtn.classList.replace('bg-blue-600', 'bg-orange-600');
        submitBtn.classList.replace('hover:bg-blue-700', 'hover:bg-orange-700');
        
        document.getElementById('cancel-btn').classList.add('hidden');
    }
</script>
@endsection