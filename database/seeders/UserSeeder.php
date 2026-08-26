<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Admin IT (Super User)
        User::updateOrCreate(
            ['email' => 'adminit@inventaris.com'],
            [
                'name'     => 'Admin IT (Super User)',
                'password' => Hash::make('password123'),
                'role'     => 'it',
            ]
        );

        // 2. Admin SARPRAS
        User::updateOrCreate(
            ['email' => 'sarpras@inventaris.com'],
            [
                'name'     => 'Admin Sarpras',
                'password' => Hash::make('password123'),
                'role'     => 'sarpras',
            ]
        );
    }
}
