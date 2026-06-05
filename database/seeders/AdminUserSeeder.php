<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        // Super administrator
        User::updateOrCreate(
            ['email' => 'admin@travel.test'],
            [
                'name' => 'Site Administrator',
                'password' => Hash::make('password'),
                'is_admin' => true,
                'role' => User::ROLE_ADMIN,
                'phone' => '+1 555 010 2030',
                'email_verified_at' => now(),
            ]
        );

        // Manager (bookings, coupons, inquiries + content)
        User::updateOrCreate(
            ['email' => 'manager@travel.test'],
            [
                'name' => 'Booking Manager',
                'password' => Hash::make('password'),
                'is_admin' => true,
                'role' => User::ROLE_MANAGER,
                'phone' => '+1 555 010 4050',
                'email_verified_at' => now(),
            ]
        );

        // Editor (content only)
        User::updateOrCreate(
            ['email' => 'editor@travel.test'],
            [
                'name' => 'Content Editor',
                'password' => Hash::make('password'),
                'is_admin' => true,
                'role' => User::ROLE_EDITOR,
                'phone' => '+1 555 010 6070',
                'email_verified_at' => now(),
            ]
        );

        // Demo customer
        User::updateOrCreate(
            ['email' => 'customer@travel.test'],
            [
                'name' => 'Jane Traveller',
                'password' => Hash::make('password'),
                'is_admin' => false,
                'role' => User::ROLE_CUSTOMER,
                'phone' => '+1 555 080 9000',
                'email_verified_at' => now(),
            ]
        );
    }
}
