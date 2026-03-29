@extends('layouts.admin')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <h1 class="text-2xl font-bold text-slate-800">Quản lý Tài khoản</h1>
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
                <td class="px-6 py-4 whitespace-nowrap">
                    @if($user->role === 'admin')
                        <span class="bg-purple-100 text-purple-800 text-xs font-bold px-3 py-1 rounded-full border border-purple-200">Quản trị viên</span>
                    @else
                        <span class="bg-slate-100 text-slate-600 text-xs font-bold px-3 py-1 rounded-full border border-slate-200">Khách hàng</span>
                    @endif
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500">
                    {{ $user->created_at->format('d/m/Y') }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                    <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa tài khoản này?');">
                        @csrf
                        @method('DELETE')
                        @if(Auth::id() !== $user->id)
                            <button type="submit" class="text-red-600 hover:text-red-900 bg-red-50 hover:bg-red-100 px-3 py-1.5 rounded-md transition">Khóa</button>
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