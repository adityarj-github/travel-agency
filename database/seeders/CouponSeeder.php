<?php

namespace Database\Seeders;

use App\Models\Coupon;
use Illuminate\Database\Seeder;

class CouponSeeder extends Seeder
{
    public function run(): void
    {
        $coupons = [
            [
                'code' => 'WELCOME10',
                'description' => 'New customer — 10% off',
                'type' => 'percent',
                'value' => 10,
                'max_discount' => 250,
                'is_active' => true,
            ],
            [
                'code' => 'SUMMER25',
                'description' => 'Summer sale — 25% off (min spend 1000)',
                'type' => 'percent',
                'value' => 25,
                'min_amount' => 1000,
                'max_discount' => 600,
                'usage_limit' => 100,
                'expires_at' => now()->addMonths(3)->toDateString(),
                'is_active' => true,
            ],
            [
                'code' => 'FLAT100',
                'description' => 'Flat 100 off any booking',
                'type' => 'fixed',
                'value' => 100,
                'min_amount' => 500,
                'is_active' => true,
            ],
        ];

        foreach ($coupons as $coupon) {
            Coupon::updateOrCreate(['code' => $coupon['code']], $coupon);
        }
    }
}
