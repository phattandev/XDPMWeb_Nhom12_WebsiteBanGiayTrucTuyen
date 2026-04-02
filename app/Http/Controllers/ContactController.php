<?php

namespace App\Http\Controllers; 

use App\Http\Controllers\Controller; 
use Illuminate\Http\Request;
use App\Models\Contact; 

class ContactController extends Controller
{
    // Hiển thị form liên hệ cho khách
    public function create()
    {
        return view('frontend.contact');
    }

    // Xử lý khi khách bấm gửi liên hệ
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        Contact::create($request->all());

        return redirect()->route('contact')->with('success', 'Cảm ơn bạn đã liên hệ. Chúng tôi sẽ phản hồi sớm nhất!');
    }
}
