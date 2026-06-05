<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            AdminUserSeeder::class,
            SettingsSeeder::class,
            CouponSeeder::class,
            DestinationSeeder::class,
            PackageSeeder::class,
            BlogSeeder::class,
            TestimonialSeeder::class,
            SliderSeeder::class,
            GallerySeeder::class,
            BookingSeeder::class,
        ]);
    }
}
