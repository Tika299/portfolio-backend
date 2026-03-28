<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'subject',
        'message',
        'is_read',
    ];

    protected $casts = [
        'is_read' => 'boolean',
    ];

    // Tự động đánh dấu là chưa đọc khi tạo mới
    protected static function booted()
    {
        static::creating(function ($contact) {
            $contact->is_read = false;
        });
    }
}