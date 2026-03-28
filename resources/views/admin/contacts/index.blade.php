@extends('layouts.app')

@section('content')
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản lý liên hệ - Admin</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<div class="max-w-5xl mx-auto mt-10 bg-white p-6 rounded-lg shadow">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="text-primary">Danh sách liên hệ</h2><a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary">Về trang chủ</a>
</div>

    <table class="w-full border">
        <thead>
            <tr class="bg-gray-100">
                <th class="border px-3 py-2">ID</th>
                <th class="border px-3 py-2">Tên</th>
                <th class="border px-3 py-2">Email</th>
                <th class="border px-3 py-2">Nội dung</th>
                <th class="border px-3 py-2">Ngày</th>
                <th class="border px-0.5 py-2">Trạng thái</th>
                <th class="border px-3 py-2">Hành động</th>
                <th class="border px-3 py-2">Phản hồi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($contacts as $contact)
                <tr>
                    <td class="border px-3 py-2 text-center">{{ $contact->id }}</td>
                    <td class="border px-3 py-2">{{ $contact->name }}</td>
                    <td class="border px-3 py-2">{{ $contact->email }}</td>
                    <td class="border px-3 py-2">{{ $contact->message }}</td>
                    <td class="border px-3 py-2 text-center">{{ $contact->created_at }}</td>
                    <td class="border px-3 py-2 text-center">
                        @if($contact->is_read)
                            <span class="text-red-600 font-bold" style="font-size: 20px;">X</span>
                        @else
                            <span class=""></span>
                        @endif
                    </td>

                    <td class="border px-3 py-2 text-center">
                        <form action="{{ route('admin.contacts.read', $contact->id) }}" method="POST">
                            @csrf

                            <button class="w-18 text-center 
                                {{ $contact->is_read ? 'bg-gray-500' : 'bg-blue-500' }} 
                                text-white px-2 py-1 rounded">
        
                                {{ $contact->is_read ? 'Bỏ xem' : 'Đã xem' }}
                            </button>
                        </form>
                    </td>
                    <td class="border px-3 py-2 text-center">
                        <a href="https://mail.google.com/mail/?view=cm&fs=1&to={{ $contact->email }}"
                            target="_blank"
                            class="btn btn-sm btn-outline-primary">
                            Phản hồi
                        </a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection