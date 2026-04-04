<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class ProductionSeeder extends Seeder
{
    public function run(): void
    {
        $adminEmail = env('ADMIN_EMAIL');
        $adminPassword = env('ADMIN_PASSWORD');

        if (blank($adminEmail) || blank($adminPassword)) {
            return;
        }

        User::updateOrCreate(
            ['email' => $adminEmail],
            [
                'name' => env('ADMIN_NAME', 'Admin Store'),
                'password' => Hash::make($adminPassword),
                'phone' => env('ADMIN_PHONE'),
                'address' => env('ADMIN_ADDRESS'),
                'role' => 'admin',
                'is_locked' => false,
            ]
        );
    }
}
