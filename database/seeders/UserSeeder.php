<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@eventticket.test'],
            [
                'name' => 'Admin EventTicket',
                'password' => Hash::make('password'),
                'is_admin' => true,
            ]
        );

        User::updateOrCreate(
            ['email' => 'customer@example.com'],
            [
                'name' => 'Customer Demo',
                'password' => Hash::make('password'),
                'is_admin' => false,
            ]
        );
    }
}
