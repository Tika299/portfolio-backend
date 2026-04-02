<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
// routes/web.php
Route::get('/health', function () {
    return response()->json(['status' => 'ok', 'time' => now()]);
});

Route::get('/fix-db-postgres', function () {
    try {
        // Lệnh này xóa bỏ ràng buộc kiểm tra status cũ của Postgres
        DB::statement('ALTER TABLE projects DROP CONSTRAINT IF EXISTS projects_status_check');
        return "Đã xóa ràng buộc thành công! Hãy thử lưu lại dự án.";
    } catch (\Exception $e) {
        return "Lỗi: " . $e->getMessage();
    }
});

Route::get('/debug-cloudinary', function () {
    return [
        'cloudinary_config' => config('cloudinary'),
        'env_cloud_name' => env('CLOUDINARY_CLOUD_NAME'),
    ];
});