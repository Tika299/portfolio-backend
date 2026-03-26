<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    //
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        \App\Models\Contact::create($data);

        // Gợi ý: Bạn có thể thêm code gửi Email hoặc Telegram thông báo ở đây

        return response()->json(['message' => 'Tin nhắn của bạn đã được gửi thành công!'], 201);
    }
}
