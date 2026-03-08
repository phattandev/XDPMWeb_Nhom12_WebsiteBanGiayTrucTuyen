<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Vẫn giữ điều kiện kiểm tra rỗng để tránh Duplicate
        if (User::count() === 0) {
            
            User::create([
                'name' => 'Vòng Kiên Hưng - DH52200752',
                'email' => 'hung.nhom12@example.com',
                'password' => Hash::make('123456'),
            ]);

            User::create([
                'name' => 'Ngô Anh Kiệt - DH52200948',
                'email' => 'kiet.nhom12@example.com',
                'password' => Hash::make('123456'),
            ]);

            User::create([
                'name' => 'Huỳnh Nguyễn Tấn Phát - DH52201181',
                'email' => 'tv3.nhom12@example.com',
                'password' => Hash::make('123456'),
            ]);

            User::create([
                'name' => 'Phạm Trí Thành - DH52201466',
                'email' => 'thanh.nhom12@example.com',
                'password' => Hash::make('123456'),
            ]);

            User::create([
                'name' => 'Lê Duyên Thắng - DH52201435',
                'email' => 'thang.nhom12@example.com',
                'password' => Hash::make('123456'),
            ]);

            User::create([
                'name' => 'user1',
                'email' => 'user1.nhom12@example.com',
                'password' => Hash::make('123456'),
            ]);

            User::create([
                'name' => 'user2',
                'email' => 'user2.nhom12@example.com',
                'password' => Hash::make('123456'),
            ]);

            User::create([
                'name' => 'user3',
                'email' => 'user3.nhom12@example.com',
                'password' => Hash::make('123456'),
            ]);
        }
    }
}