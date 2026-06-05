<?php

namespace Database\Seeders;

use App\Models\Booking;
use App\Models\Package;
use Illuminate\Database\Seeder;

class BookingSeeder extends Seeder
{
    public function run(): void
    {
        $packages = Package::with('destination')->get();
        if ($packages->isEmpty()) {
            return;
        }

        $samples = [
            ['name' => 'John Smith', 'email' => 'john.smith@example.com', 'phone' => '+1 555 234 1100', 'adults' => 2, 'children' => 1, 'status' => 'pending'],
            ['name' => 'Maria Garcia', 'email' => 'maria.g@example.com', 'phone' => '+34 612 345 678', 'adults' => 2, 'children' => 0, 'status' => 'confirmed'],
            ['name' => 'David Johnson', 'email' => 'davidj@example.com', 'phone' => '+44 7700 900123', 'adults' => 4, 'children' => 2, 'status' => 'completed'],
            ['name' => 'Linda Brown', 'email' => 'linda.brown@example.com', 'phone' => '+1 555 987 4321', 'adults' => 1, 'children' => 0, 'status' => 'cancelled'],
            ['name' => 'Kenji Tanaka', 'email' => 'kenji.t@example.com', 'phone' => '+81 90 1234 5678', 'adults' => 2, 'children' => 0, 'status' => 'pending'],
        ];

        foreach ($samples as $i => $s) {
            $package = $packages[$i % $packages->count()];

            Booking::updateOrCreate(
                ['email' => $s['email'], 'package_id' => $package->id],
                [
                    'name' => $s['name'],
                    'phone' => $s['phone'],
                    'destination_id' => $package->destination_id,
                    'travel_date' => now()->addMonths($i + 1)->toDateString(),
                    'adults' => $s['adults'],
                    'children' => $s['children'],
                    'message' => 'Looking forward to this trip. Please send more details about availability.',
                    'status' => $s['status'],
                ]
            );
        }
    }
}
