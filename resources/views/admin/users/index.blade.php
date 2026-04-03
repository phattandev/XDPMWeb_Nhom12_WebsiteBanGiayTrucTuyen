@extends('layouts.admin')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div class="mb-6 flex flex-col md:flex-row md:justify-between md:items-center gap-4">
        <h1 class="text-2xl font-bold text-slate-800">Quản lý Tài khoản</h1>
        
        <form method="GET" action="{{ route('admin.users.index') }}" class="flex items-center">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Tên, Email hoặc SĐT..." 
                class="rounded-l-lg border-slate-300 focus:ring-orange-500 focus:border-orange-500 text-sm py-2 px-3 w-64">
            <button type="submit" class="bg-slate-800 hover:bg-slate-900 text-white px-4 py-2 text-sm rounded-r-lg border border-slate-800 transition">
                Tìm
            </button>
        </form>
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
                <th class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Khách hàng</th>
                <th class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Vai trò</th>
                <th class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Ngày tham gia</th>
                <th class="px-6 py-3 text-right text-xs font-bold text-slate-500 uppercase tracking-wider">Thao tác</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-slate-200">
            @foreach($users as $user)
            <tr class="hover:bg-slate-50 transition">
                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500">#{{ $user->id }}</td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="font-bold text-slate-900">{{ $user->name }}</div>
                    <div class="text-sm text-slate-500">{{ $user->email }}</div>
                    <div class="text-sm text-slate-500">{{ $user->phone ?? 'Chưa có SĐT' }}</div>
                </td>
                <!-- <td class="px-6 py-4 whitespace-nowrap">
                    @if($user->role === 'admin')
                        <span class="bg-purple-100 text-purple-800 text-xs font-bold px-3 py-1 rounded-full border border-purple-200">Quản trị viên</span>
                    @else
                        <span class="bg-slate-100 text-slate-600 text-xs font-bold px-3 py-1 rounded-full border border-slate-200">Khách hàng</span>
                    @endif
                </td> -->
                <td class="px-6 py-4 whitespace-nowrap">
                    @if($user->role === 'admin')
                        <span class="bg-purple-100 text-purple-800 text-xs font-bold px-3 py-1 rounded-full border border-purple-200">Quản trị viên</span>
                    @else
                        @if($user->is_locked)
                            <span class="bg-red-100 text-red-800 text-xs font-bold px-3 py-1 rounded-full border border-red-200">Bị khóa</span>
                        @else
                            <span class="bg-green-100 text-green-800 text-xs font-bold px-3 py-1 rounded-full border border-green-200">Hoạt động</span>
                        @endif
                    @endif
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500">
                    {{ $user->created_at->format('d/m/Y') }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                    <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn thay đổi trạng thái tài khoản này?');">
                        @csrf
                        @method('DELETE')
                        @if(Auth::id() !== $user->id)
                            <button type="submit" class="{{ $user->is_locked ? 'text-green-600 bg-green-50 hover:bg-green-100' : 'text-red-600 bg-red-50 hover:bg-red-100' }} px-3 py-1.5 rounded-md transition font-medium">
                                {{ $user->is_locked ? 'Mở khóa' : 'Khóa' }}
                            </button>
                        @endif
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    
    <div class="px-6 py-4 border-t border-slate-200">
        {{ $users->links() }}
    </div>
</div>
@endsection