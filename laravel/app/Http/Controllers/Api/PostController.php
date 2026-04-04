<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PostResource;
use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    //
    public function index()
    {
        // SỬA: Đổi 'status' thành 'is_published' cho đúng với Model của Vũ
        $posts = Post::where('is_published', true)
            ->latest('published_at')
            ->get();

        return PostResource::collection($posts);
    }

    public function show($slug)
    {
        // SỬA: Đổi 'status' thành 'is_published'
        $post = Post::where('slug', $slug)
            ->where('is_published', true)
            ->firstOrFail();

        return new PostResource($post);
    }
}
