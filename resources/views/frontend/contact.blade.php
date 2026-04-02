@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto mt-10 bg-white p-6 rounded-lg shadow">
    <h2 class="text-2xl font-bold mb-4 text-center">Liên hệ với chúng tôi</h2>

    @if(session('success'))
        <div class="bg-green-100 text-green-700 p-3 mb-4 rounded">
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="bg-red-100 text-red-700 p-3 mb-4 rounded">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>- {{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('contact.store') }}">
        @csrf

        <div class="mb-4">
            <label class="block mb-1">Tên</label>
            <input type="text" name="name" value="{{ old('name') }}"
            class="w-full border px-3 py-2 rounded @error('name') border-red-500 @enderror">
        </div>

        <div class="mb-4">
            <label class="block mb-1">Email</label>
            <input type="email" name="email" value="{{ old('email') }}"
            class="w-full border px-3 py-2 rounded @error('email') border-red-500 @enderror">
        </div>

        <div class="mb-4">
            <label class="block mb-1" for="subject">Chủ đề <span class="text-red-500">*</span></label>
            <input type="text" name="subject" id="subject" required placeholder="Ví dụ: Cần tư vấn mua giày" value="{{ old('subject') }}"
            class="w-full border px-3 py-2 rounded @error('subject') border-red-500 @enderror">
        </div>

        <div class="mb-4">
            <label class="block mb-1">Nội dung</label>
            <textarea name="message"
            class="w-full border px-3 py-2 rounded @error('message') border-red-500 @enderror">{{ old('message') }}</textarea>
        </div>

        <button type="submit" class="bg-orange-500 text-white px-4 py-2 rounded">
            Gửi liên hệ
        </button>
    </form>
</div>
@endsection