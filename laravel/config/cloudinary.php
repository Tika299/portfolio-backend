<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Cloudinary Configuration
    |--------------------------------------------------------------------------
    */

    // Đây chính là cái "key" mà hệ thống đang báo thiếu
    'cloud' => [
        'cloud_name' => env('CLOUDINARY_CLOUD_NAME'),
        'api_key'    => env('CLOUDINARY_API_KEY'),
        'api_secret' => env('CLOUDINARY_API_SECRET'),
    ],

    'cloud_url' => env('CLOUDINARY_URL'),
];