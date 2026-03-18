<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Users
        DB::table('users')->insert([
            ['name' => 'Admin', 'email' => 'admin@store.com', 'password' => Hash::make('hashed_pw'), 'phone' => '0901234567', 'address' => 'Store Address', 'role' => 'admin'],
            ['name' => 'Maleeha', 'email' => 'maleeha@gmail.com', 'password' => Hash::make('hashed_pw'), 'phone' => '03004248975', 'address' => 'Karachi', 'role' => 'customer'],
        ]);

        // 2. Categories
        DB::table('categories')->insert([
            ['name' => 'Sneakers', 'description' => null],
            ['name' => 'Boots', 'description' => null],
            ['name' => 'Running Shoes', 'description' => null],
        ]);

        // 3. Brands
        DB::table('brands')->insert([
            ['name' => 'Nike'],
            ['name' => 'Adidas'],
            ['name' => 'Converse'],
        ]);

        // 4. Shoes
        DB::table('shoes')->insert([
            ['category_id' => 1, 'brand_id' => 1, 'name' => 'Nike Air Max', 'description' => 'Classic running sneaker', 'price' => 2500],
            ['category_id' => 1, 'brand_id' => 2, 'name' => 'Adidas Ultraboost', 'description' => 'Comfortable running shoe', 'price' => 3000],
        ]);

        // 5. Shoe Variants
        DB::table('shoe_variants')->insert([
            ['shoe_id' => 1, 'color' => 'Red', 'size' => 41, 'stock_quantity' => 15],
            ['shoe_id' => 1, 'color' => 'Red', 'size' => 42, 'stock_quantity' => 20],
            ['shoe_id' => 1, 'color' => 'White', 'size' => 41, 'stock_quantity' => 10],
            ['shoe_id' => 2, 'color' => 'Black', 'size' => 40, 'stock_quantity' => 5],
            ['shoe_id' => 2, 'color' => 'Black', 'size' => 41, 'stock_quantity' => 8],
        ]);

        // 6. Shoe Images
        DB::table('shoe_images')->insert([
            ['shoe_id' => 1, 'image_url' => 'nike_air_max_red.jpg', 'is_primary' => true],
            ['shoe_id' => 1, 'image_url' => 'nike_air_max_side.jpg', 'is_primary' => false],
            ['shoe_id' => 2, 'image_url' => 'adidas_ultra_black.jpg', 'is_primary' => true],
        ]);

        // 7. Orders
        DB::table('orders')->insert([
            ['user_id' => 2, 'total_amount' => 5500, 'shipping_address' => 'Karachi', 'payment_method' => 'COD'],
        ]);

        // 8. Order Details
        DB::table('order_details')->insert([
            ['order_id' => 1, 'shoe_variant_id' => 1, 'quantity' => 1, 'unit_price' => 2500],
            ['order_id' => 1, 'shoe_variant_id' => 4, 'quantity' => 1, 'unit_price' => 3000],
        ]);
    }
}