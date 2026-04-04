<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{
    // Nếu có logic riêng cho Post thì thêm vào đây, nếu không thì kế thừa toàn bộ từ ProjectResource
    public function toArray(Request $request): array
    {
        $thumbnail = $this->thumbnail;

        // Tự nối link chuẩn của Supabase
        if ($thumbnail && !str_starts_with($thumbnail, 'http')) {
            $thumbnail = "https://lpctoylpxvdqwjqcxeoy.storage.supabase.co/storage/v1/object/public/portfolio/" . $thumbnail;
        }
        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'summary' => $this->summary,
            'content' => $this->content,
            // Sử dụng logic link sạch giống như Project
            'thumbnail' => $thumbnail,
            'published_at' => $this->published_at ? $this->published_at->format('d/m/Y') : null,
            'is_published' => $this->is_published,
        ];
    }
}
