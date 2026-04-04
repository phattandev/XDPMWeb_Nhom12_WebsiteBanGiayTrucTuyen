@extends('layouts.admin')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <h1 class="text-2xl font-bold text-slate-800">Quản lý Liên hệ</h1>
</div>

<div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden relative">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-slate-200">
            <thead class="bg-slate-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Khách hàng</th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Nội dung tóm tắt</th>
                    <th class="px-6 py-3 text-center text-xs font-bold text-slate-500 uppercase tracking-wider">Ngày gửi</th>
                    <th class="px-6 py-3 text-center text-xs font-bold text-slate-500 uppercase tracking-wider">Trạng thái</th>
                    <th class="px-6 py-3 text-right text-xs font-bold text-slate-500 uppercase tracking-wider">Thao tác</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-slate-200">
                @forelse($contacts as $contact)
                <tr class="transition {{ $contact->is_read ? 'bg-white opacity-70 hover:opacity-100' : 'bg-orange-50/50' }}">
                    <td class="px-6 py-4">
                        <div class="font-bold text-slate-900">{{ $contact->name }}</div>
                        <div class="text-sm text-slate-500">{{ $contact->email }}</div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-sm text-slate-700 max-w-xs line-clamp-2">{{ $contact->message }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-slate-500">
                        {{ $contact->created_at->format('d/m/Y H:i') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-center">
                        @if($contact->is_read)
                            <span class="bg-slate-100 text-slate-600 text-xs font-bold px-3 py-1 rounded-full border border-slate-200">Đã xem</span>
                        @else
                            <span class="bg-orange-100 text-orange-800 text-xs font-bold px-3 py-1 rounded-full border border-orange-200">Chưa xem</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <div class="flex justify-end gap-2">
                            <button type="button" 
                                data-name="{{ $contact->name }}"
                                data-email="{{ $contact->email }}"
                                data-time="{{ $contact->created_at->format('d/m/Y H:i') }}"
                                data-message="{{ $contact->message }}"
                                onclick="showContactModal(this)"
                                class="text-indigo-600 bg-indigo-50 hover:bg-indigo-100 px-3 py-1.5 rounded-md transition">
                                Xem chi tiết
                            </button>

                            <!-- Nút Đánh dấu -->
                            <form action="{{ route('admin.contacts.read', $contact->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="{{ $contact->is_read ? 'text-slate-600 bg-slate-100 hover:bg-slate-200' : 'text-blue-600 bg-blue-50 hover:bg-blue-100' }} px-3 py-1.5 rounded-md transition">
                                    {{ $contact->is_read ? 'Đánh dấu chưa xem' : 'Đánh dấu đã xem' }}
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-10 text-center text-slate-500">Chưa có liên hệ nào.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div id="contactModal" class="fixed inset-0 z-50 hidden bg-slate-900/50 backdrop-blur-sm transition-opacity">
    <div class="flex min-h-full items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-xl w-full max-w-2xl overflow-hidden transform transition-all">
        <div class="flex justify-between items-center p-4 border-b border-slate-200 bg-slate-50">
            <h3 class="font-bold text-lg text-slate-800 flex items-center gap-2">
                <svg class="w-5 h-5 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                Chi tiết lời nhắn
            </h3>
            <button type="button" onclick="closeContactModal()" class="text-slate-400 hover:text-red-500 font-bold text-2xl leading-none outline-none">&times;</button>
        </div>
        
        <div class="p-6">
            <div class="grid grid-cols-2 gap-4 mb-4 pb-4 border-b border-slate-100">
                <div>
                    <p class="text-xs text-slate-500 uppercase font-bold mb-1">Người gửi</p>
                    <p id="modalName" class="font-bold text-slate-900 text-lg"></p>
                    <p id="modalEmail" class="text-sm text-slate-600"></p>
                </div>
                <div class="text-right">
                    <p class="text-xs text-slate-500 uppercase font-bold mb-1">Thời gian gửi</p>
                    <p id="modalTime" class="text-sm text-slate-800 font-medium"></p>
                </div>
            </div>
            
            <div>
                <p class="text-xs text-slate-500 uppercase font-bold mb-2">Nội dung</p>
                <!-- Dùng whitespace-pre-wrap để giữ nguyên các đoạn xuống dòng của khách -->
                <div id="modalMessage" class="bg-slate-50 p-4 rounded-lg border border-slate-200 text-slate-700 whitespace-pre-wrap text-sm leading-relaxed max-h-64 overflow-y-auto"></div>
            </div>
        </div>
        
        <div class="p-4 border-t border-slate-200 bg-slate-50 flex justify-end gap-3">
            <button type="button" onclick="closeContactModal()" class="px-5 py-2.5 bg-white border border-slate-300 hover:bg-slate-50 text-slate-700 rounded-lg transition font-bold shadow-sm">
                Đóng
            </button>
            <a id="modalReplyBtn" href="#" target="_blank" class="px-5 py-2.5 bg-orange-600 hover:bg-orange-700 text-white rounded-lg transition font-bold shadow-sm flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"></path></svg>
                Phản hồi qua Email
            </a>
        </div>
    </div>
    </div>
</div>

<script>
    function showContactModal(button) {
        const name = button.getAttribute('data-name');
        const email = button.getAttribute('data-email');
        const time = button.getAttribute('data-time');
        const message = button.getAttribute('data-message');

        document.getElementById('modalName').innerText = name;
        document.getElementById('modalEmail').innerText = email;
        document.getElementById('modalTime').innerText = time;
        document.getElementById('modalMessage').innerText = message;
        
        document.getElementById('modalReplyBtn').href = "https://mail.google.com/mail/?view=cm&fs=1&to=" + encodeURIComponent(email);

        document.getElementById('contactModal').classList.remove('hidden');
    }

    function closeContactModal() {
        document.getElementById('contactModal').classList.add('hidden');
    }
</script>
@endsection
