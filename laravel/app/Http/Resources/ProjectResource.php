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
        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'content' => $this->content,

            // CÁCH SỬA CHI TIẾT:
            // Storage::url() sẽ tự động kiểm tra: 
            // nếu disk là cloudinary, nó trả về link https://res.cloudinary.com/...
            'thumbnail' => $this->thumbnail ? Storage::url('cloudinary/' . $this->thumbnail) : null,

            'demo_url' => $this->demo_url,
            'github_url' => $this->github_url,
            'technologies' => TechnologyResource::collection($this->whenLoaded('technologies')),
            'created_at' => $this->created_at->format('d/m/Y'),
        ];
    }
}
