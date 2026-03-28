<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
// routes/web.php
Route::get('/health', function () {
    return response()->json(['status' => 'ok', 'time' => now()]);
});
