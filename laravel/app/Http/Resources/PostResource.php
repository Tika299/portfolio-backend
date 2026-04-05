<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{
    // Nếu có logic riêng cho Post thì thêm vào đây, nếu không thì kế thừa toàn bộ từ ProjectResource
    public function toArray(Request $request): array
    {
        $cover_image = $this->cover_image;

        // Tự nối link chuẩn của Supabase
        if ($cover_image && !str_starts_with($cover_image, 'http')) {
            $cover_image = "https://lpctoylpxvdqwjqcxeoy.storage.supabase.co/storage/v1/object/public/portfolio/" . $cover_image;
        }
        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'summary' => $this->summary,
            'content' => $this->content,
            // Sử dụng logic link sạch giống như Project
            'cover_image' => $cover_image,
            'views' => $this->views,
            'is_published' => $this->is_published,
            'published_at' => $this->published_at ? $this->published_at->format('d/m/Y') : null,
            'created_at' => $this->created_at ? $this->created_at->format('d/m/Y') : null,
        ];
    }
}
