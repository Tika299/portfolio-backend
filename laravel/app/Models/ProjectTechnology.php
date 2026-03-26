<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class ProjectTechnology extends Pivot
{
    // Không cần fillable vì là bảng trung gian
    public $timestamps = true;   // nếu bạn có created_at, updated_at trong bảng pivot
}