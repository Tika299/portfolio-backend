<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class ProjectResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $imageUrl = $this->thumbnail;

        // Nếu thumbnail đã là link full (do bạn dán link github vào chẳng hạn)
        if (!str_starts_with($imageUrl, 'http')) {
            // Nếu là file upload (chỉ có tên file), lấy link từ Supabase
            $imageUrl = Storage::disk('supabase')->url($this->thumbnail);
        }
        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'description' => $this->description,
            'content' => $this->content,
            // Chuyển URL ảnh thành đường dẫn tuyệt đối
            'thumbnail' => $imageUrl,
            'demo_url' => $this->demo_url,
            'github_url' => $this->github_url,
            // Load danh sách công nghệ đi kèm
            'technologies' => TechnologyResource::collection($this->whenLoaded('technologies')),
            'created_at' => $this->created_at->format('d/m/Y'),
        ];
    }
}
