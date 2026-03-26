<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'content',
        'thumbnail',
        'demo_url',
        'github_url',
        'status',
    ];

    protected $casts = [
        'status' => 'string',   // hoặc bạn có thể dùng Enum nếu muốn
    ];

    /**
     * Tự động tạo slug từ title khi chưa có
     */
    protected static function booted()
    {
        static::creating(function ($project) {
            if (empty($project->slug)) {
                $project->slug = Str::slug($project->title);
            }
        });

        static::updating(function ($project) {
            if ($project->isDirty('title') && empty($project->slug)) {
                $project->slug = Str::slug($project->title);
            }
        });
    }

    public function technologies()
    {
        return $this->belongsToMany(Technology::class);
    }
}
