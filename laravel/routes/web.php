<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

Route::get('/', function () {
    return view('welcome');
});
// routes/web.php
Route::get('/health', function () {
    return response()->json(['status' => 'ok', 'time' => now()]);
});

Route::get('/debug-disk', function () {
    return [
        'disk_exists' => array_key_exists('supabase', config('filesystems.disks')),
        'driver_type' => config('filesystems.disks.supabase.driver'),
        'all_disks' => array_keys(config('filesystems.disks')),
        'env_key_check' => env('SUPABASE_ACCESS_KEY') ? 'Đã nhận Key' : 'Chưa nhận Key',
    ];
});

Route::get('/test-upload', function () {
    try {
        $content = 'Dữ liệu test upload ' . now();
        $path = 'test-' . time() . '.txt';
        
        // Thử đẩy 1 file text nhỏ lên Supabase
        $result = Storage::disk('supabase')->put($path, $content);
        
        return [
            'success' => $result,
            'url' => Storage::disk('supabase')->url($path),
            'message' => 'Upload thành công! Hãy kiểm tra bucket trên Supabase.'
        ];
    } catch (\Exception $e) {
        return [
            'success' => false,
            'error_message' => $e->getMessage(),
            'trace' => 'Kiểm tra lại Key và Endpoint trong .env'
        ];
    }
});