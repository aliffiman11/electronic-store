<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run()
    {
        $products = [
            ['name' => 'Blender', 'description' => 'High-speed blender for smoothies and more.', 'price' => 59.99, 'stock' => 'available', 'image' => 'blender.png'],
            ['name' => 'Brewing Machine', 'description' => 'Automatic brewing machine for coffee lovers.', 'price' => 129.99, 'stock' => 'available', 'image' => 'brewing-machine.png'],
            ['name' => 'Electric Stove', 'description' => 'Modern electric stove with multiple burners.', 'price' => 199.99, 'stock' => 'unavailable', 'image' => 'electric-stove.jpg'],
            ['name' => 'Extension Cord', 'description' => '5-socket extension cord with surge protection.', 'price' => 19.99, 'stock' => 'available', 'image' => 'extension.jpg'],
            ['name' => 'Fan', 'description' => 'Oscillating table fan for cooling comfort.', 'price' => 39.99, 'stock' => 'available', 'image' => 'fan.jpg'],
            ['name' => 'Hair Dryer', 'description' => 'Compact and powerful hair dryer.', 'price' => 24.99, 'stock' => 'available', 'image' => 'hairdryer.jpg'],
            ['name' => 'Iron', 'description' => 'Steam iron with non-stick soleplate.', 'price' => 29.99, 'stock' => 'unavailable', 'image' => 'iron.jpg'],
            ['name' => 'Electric Kettle', 'description' => '1.7L electric kettle with auto shut-off.', 'price' => 34.99, 'stock' => 'available', 'image' => 'kettle.jpg'],
            ['name' => 'Portable Vacuum', 'description' => 'Handheld vacuum cleaner for quick cleanups.', 'price' => 49.99, 'stock' => 'available', 'image' => 'portable-vacum.jpg'],
            ['name' => 'Toaster', 'description' => '2-slice toaster with adjustable browning.', 'price' => 27.99, 'stock' => 'available', 'image' => 'toaster.jpg'],
        ];

        foreach ($products as $product) {
            $imagePath = 'product-images/' . $product['image']; // Use relative path

            // Ensure the file exists
            if (!file_exists(storage_path('app/public/' . $imagePath))) {
                echo "Error: Image file not found - " . $product['image'] . "\n";
                $imagePath = null; // Set to null if the image doesn't exist
            }

            Product::create([
                'name' => $product['name'],
                'description' => $product['description'],
                'price' => $product['price'],
                'stock' => $product['stock'],
                'image' => $imagePath, // Store the relative path
            ]);
        }
    }
}
