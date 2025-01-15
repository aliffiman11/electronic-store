<?php

namespace Database\Seeders;

use App\Models\User;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Admin User
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'), // Default password
            'role' => 'admin',
        ]);

        // Customer Users
        User::create([
            'name' => 'Customer One',
            'email' => 'customer1@example.com',
            'password' => Hash::make('password'), // Default password
            'role' => 'customer',
        ]);

        User::create([
            'name' => 'Customer Two',
            'email' => 'customer2@example.com',
            'password' => Hash::make('password'), // Default password
            'role' => 'customer',
        ]);

        User::create([
            'name' => 'Customer Three',
            'email' => 'customer3@example.com',
            'password' => Hash::make('password'), // Default password
            'role' => 'customer',
        ]);

    }
}
