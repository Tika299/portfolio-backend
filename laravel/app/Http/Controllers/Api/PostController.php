<?php

namespace App\Http\Controllers\Api;

use App\Filament\Resources\Posts\PostResource;
use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    //
    public function index()
    {
        return PostResource::collection(Post::where('status', true)->latest()->get());
    }

    public function show($slug)
    {
        $post = Post::where('slug', $slug)->where('status', true)->firstOrFail();
        return new PostResource($post);
    }
}
