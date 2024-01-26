<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'User 1',
            'email' => 'one@example.com',
            'password' => HasH::make('password'),
        ]);

        User::create([
            'name' => 'Admin User',
            'email' => 'two@example.com',
            'password' => HasH::make('password'),
        ]);
    }
}
