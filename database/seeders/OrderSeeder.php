<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Order;
use App\Models\Product;
use App\Models\CustAddress;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Faker\Factory as Faker;


class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();
        $users = User::pluck('id')->toArray();
        $products = Product::pluck('id')->toArray();
        $addresses = CustAddress::pluck('id')->toArray();

        // Define uneven distribution for orders per month
        $monthlyOrders = [
            '01' => rand(5, 10),
            '02' => rand(8, 12),
            '03' => rand(10, 15),
            '04' => rand(12, 18),
            '05' => rand(8, 14),
            '06' => rand(15, 20),
            '07' => rand(10, 18),
            '08' => rand(12, 20),
            '09' => rand(15, 25),
            '10' => rand(10, 15),
            '11' => rand(8, 14),
            '12' => rand(10, 20),
        ];

        foreach ($monthlyOrders as $month => $count) {
            for ($i = 0; $i < $count; $i++) {
                // Randomize product, user, and quantity
                $product_id = $faker->randomElement($products);
                $user_id = $faker->randomElement($users);
                $quantity = rand(1, 5);

                $product = Product::find($product_id);
                $total_price = $product->price * $quantity;

                // Generate random address and order details
                $address_id = $faker->randomElement($addresses);
                $orderReference = Str::uuid();

                Order::create([
                    'order_reference' => $orderReference,
                    'user_id' => $user_id,
                    'product_id' => $product_id,
                    'quantity' => $quantity,
                    'price' => $product->price,
                    'total_price' => $total_price,
                    'status' => $faker->randomElement(['Pending', 'Paid', 'Cancelled']),
                    'created_at' => $faker->dateTimeBetween("2024-$month-01", \Carbon\Carbon::create(2024, $month)->endOfMonth()),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
