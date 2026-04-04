<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class LocalDemoSeeder extends Seeder
{
    public function run(): void
    {
        $faker = fake('vi_VN');

        $userIds = [];

        DB::table('users')->insertGetId([
            'name' => 'Admin Store',
            'email' => 'admin@store.com',
            'password' => Hash::make('12345678'),
            'phone' => '0901234567',
            'address' => 'Hà Nội',
            'role' => 'admin',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $mainCustomerId = DB::table('users')->insertGetId([
            'name' => 'Khách Hàng',
            'email' => 'khachhang@store.com',
            'password' => Hash::make('password'),
            'phone' => '0987654321',
            'address' => 'TP.HCM',
            'role' => 'customer',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        $userIds[] = $mainCustomerId;

        for ($i = 0; $i < 50; $i++) {
            $userIds[] = DB::table('users')->insertGetId([
                'name' => $faker->name(),
                'email' => $faker->unique()->safeEmail(),
                'password' => Hash::make('password'),
                'phone' => $faker->phoneNumber(),
                'address' => $faker->address(),
                'role' => 'customer',
                'created_at' => $faker->dateTimeBetween('-1 year', 'now'),
                'updated_at' => now(),
            ]);
        }

        $categories = ['Sneakers', 'Boots', 'Running Shoes', 'Slip-on', 'Oxford', 'Basketball', 'Training'];
        $categoryIds = [];
        foreach ($categories as $cat) {
            $categoryIds[] = DB::table('categories')->insertGetId([
                'name' => $cat,
                'description' => 'Danh mục giày ' . $cat,
            ]);
        }

        $brands = ['Nike', 'Adidas', 'Converse', 'Vans', 'Puma', 'New Balance', 'Asics', 'Balenciaga', 'Gucci', 'MLB'];
        $brandIds = [];
        $brandNames = [];
        foreach ($brands as $brand) {
            $id = DB::table('brands')->insertGetId(['name' => $brand]);
            $brandIds[] = $id;
            $brandNames[$id] = $brand;
        }

        $allColors = ['Trắng', 'Đen', 'Xám', 'Đỏ', 'Xanh Navy', 'Vàng', 'Xanh lá', 'Hồng', 'Be', 'Nâu'];
        $allSizes = [36, 37, 38, 39, 40, 41, 42, 43, 44, 45];
        $shoeWords = ['Pro', 'Max', 'Ultra', 'Boost', 'Classic', 'OG', 'Zoom', 'Force', 'Light', 'Elite'];

        $allVariantIds = [];
        $allShoeIds = [];

        for ($i = 1; $i <= 120; $i++) {
            $bId = $brandIds[array_rand($brandIds)];
            $cId = $categoryIds[array_rand($categoryIds)];
            $shoeName = $brandNames[$bId] . ' ' . ucfirst($faker->word()) . ' ' . $shoeWords[array_rand($shoeWords)];

            $shoeId = DB::table('shoes')->insertGetId([
                'category_id' => $cId,
                'brand_id' => $bId,
                'name' => $shoeName,
                'description' => $faker->paragraphs(2, true),
                'price' => rand(10, 80) * 100000,
                'created_at' => $faker->dateTimeBetween('-6 months', 'now'),
            ]);
            $allShoeIds[] = $shoeId;

            $numImages = rand(2, 4);
            for ($img = 0; $img < $numImages; $img++) {
                DB::table('shoe_images')->insert([
                    'shoe_id' => $shoeId,
                    'image_url' => 'https://picsum.photos/seed/shoes_' . $shoeId . '_' . $img . '/800/800',
                    'is_primary' => $img === 0,
                ]);
            }

            $numVariants = rand(2, 5);
            $usedVariants = [];
            for ($v = 0; $v < $numVariants; $v++) {
                $color = $allColors[array_rand($allColors)];
                $size = $allSizes[array_rand($allSizes)];
                $key = $color . '-' . $size;

                if (! in_array($key, $usedVariants)) {
                    $usedVariants[] = $key;
                    $variantId = DB::table('shoe_variants')->insertGetId([
                        'shoe_id' => $shoeId,
                        'color' => $color,
                        'size' => $size,
                        'stock_quantity' => rand(0, 100),
                    ]);
                    $allVariantIds[] = $variantId;
                }
            }
        }

        for ($i = 0; $i < 300; $i++) {
            DB::table('reviews')->insert([
                'user_id' => $userIds[array_rand($userIds)],
                'shoe_id' => $allShoeIds[array_rand($allShoeIds)],
                'rating' => rand(3, 5),
                'comment' => $faker->realText(100),
                'created_at' => $faker->dateTimeBetween('-3 months', 'now'),
            ]);
        }

        $statuses = ['Pending', 'Processing', 'Shipped', 'Delivered', 'Cancelled'];
        $payMethods = ['COD', 'Bank Transfer', 'VNPay', 'Momo'];

        for ($i = 0; $i < 150; $i++) {
            $totalAmount = 0;
            $orderDate = $faker->dateTimeBetween('-4 months', 'now');
            $status = $statuses[array_rand($statuses)];
            $payMethod = $payMethods[array_rand($payMethods)];

            $orderId = DB::table('orders')->insertGetId([
                'user_id' => $userIds[array_rand($userIds)],
                'total_amount' => 0,
                'shipping_address' => $faker->address(),
                'payment_method' => $payMethod,
                'status' => $status,
                'order_date' => $orderDate,
            ]);

            $numDetails = rand(1, 4);
            for ($d = 0; $d < $numDetails; $d++) {
                $variantId = $allVariantIds[array_rand($allVariantIds)];
                $qty = rand(1, 3);
                $unitPrice = rand(10, 80) * 100000;
                $totalAmount += $qty * $unitPrice;

                DB::table('order_details')->insert([
                    'order_id' => $orderId,
                    'shoe_variant_id' => $variantId,
                    'quantity' => $qty,
                    'unit_price' => $unitPrice,
                ]);
            }

            DB::table('orders')->where('id', $orderId)->update(['total_amount' => $totalAmount]);

            $payStatus = $status === 'Delivered' ? 'Completed' : ($status === 'Cancelled' ? 'Failed' : 'Pending');

            DB::table('payments')->insert([
                'order_id' => $orderId,
                'transaction_id' => 'TXN-' . strtoupper($faker->bothify('?????-########')),
                'amount' => $totalAmount,
                'payment_method' => $payMethod,
                'payment_status' => $payStatus,
                'payment_date' => $orderDate,
            ]);
        }
    }
}
