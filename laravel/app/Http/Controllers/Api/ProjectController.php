<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Http\Resources\ProjectResource;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    // Lấy danh sách dự án
    public function index()
    {
        $projects = Project::where('status', true)
            ->with('technologies') // Eager loading để tối ưu câu query (tránh N+1)
            ->latest()
            ->get();

        return ProjectResource::collection($projects);
    }

    // Lấy chi tiết 1 dự án bằng SLUG
    public function show($slug)
    {
        $project = Project::where('slug', $slug)
            ->where('status', true)
            ->with('technologies')
            ->firstOrFail();

        return new ProjectResource($project);
    }
}
