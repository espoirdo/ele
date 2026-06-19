<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Super Admin',
            'email' => 'admin@eledji.com',
            'password' => Hash::make('Admin@2026!'),
            'role' => 'admin',
            'is_blocked' => false,
        ]);
    }
}