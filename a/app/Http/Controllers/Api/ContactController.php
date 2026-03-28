<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
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
        $webhookUrl = "https://discord.com/api/webhooks/1487281506450673775/Sr-oUFfgZhpjVYBUR5-ZH6bv4Cso0FeQeRcdIyN6DUGbiGlJV-R_DTs9gc5vwn96Hg6R";

        Http::post($webhookUrl, [
            "content" => "🚀 **Có tin nhắn mới từ Portfolio!**",
            "embeds" => [
                [
                    "title" => "Chi tiết liên hệ",
                    "color" => 3447003, // Màu xanh dương
                    "fields" => [
                        ["name" => "👤 Tên", "value" => $data['name'], "inline" => true],
                        ["name" => "📧 Email", "value" => $data['email'], "inline" => true],
                        ["name" => "📝 Tiêu đề", "value" => $data['subject']],
                        ["name" => "💬 Nội dung", "value" => $data['message']],
                    ],
                    "footer" => ["text" => "Gửi từ portfolio-vuxuan.dev"]
                ]
            ]
        ]);
        return response()->json(['message' => 'Tin nhắn của bạn đã được gửi thành công!'], 201);
    }
}
