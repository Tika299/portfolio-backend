<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Kiểm tra nếu chưa có user này thì mới tạo
        if (!User::where('email', 'vukofa9120@gmail.com')->exists()) {
            User::create([
                'name' => 'Admin Vũ',
                'email' => 'vukofa9120@gmail.com', // Thay bằng email của bạn
                'password' => Hash::make('290905'), // Thay bằng mật khẩu của bạn
            ]);
        }
    }
}
