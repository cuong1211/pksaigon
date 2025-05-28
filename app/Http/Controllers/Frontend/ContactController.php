<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function index()
    {
        return view('frontend.views.contact');
    }

    public function store(Request $request)
    {
        // Xử lý form liên hệ
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'nullable|string|max:20',
            'subject' => 'required|string|max:255',
            'message' => 'required|string'
        ]);

        // Lưu thông tin liên hệ vào database hoặc gửi email
        // Contact::create($request->all());

        return response()->json([
            'type' => 'success',
            'title' => 'Thành công',
            'content' => 'Cảm ơn bạn đã liên hệ! Chúng tôi sẽ phản hồi sớm nhất có thể.'
        ]);
    }
}