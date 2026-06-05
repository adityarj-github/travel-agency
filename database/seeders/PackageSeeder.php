<?php

namespace Database\Seeders;

use App\Models\Destination;
use App\Models\Package;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PackageSeeder extends Seeder
{
    public function run(): void
    {
        $img = fn ($id) => "https://images.unsplash.com/photo-{$id}?auto=format&fit=crop&w=1200&q=80";

        $packages = [
            [
                'title' => 'Bali Tropical Paradise Escape', 'destination' => 'Bali', 'category' => 'Beach', 'package_type' => 'International', 'tour_type' => 'Group',
                'price' => 1299, 'discount_price' => 999, 'duration_days' => 6, 'duration_nights' => 5, 'location' => 'Bali, Indonesia', 'is_featured' => true,
                'main_image' => '1537996194471-e657df975ab4', 'short' => 'Sun-kissed beaches, lush rice terraces and vibrant culture await in this unforgettable Bali getaway.',
                'gallery' => ['1518548419970-58e3b4079ab2', '1539367628448-4bc5c9d171c8', '1604999565976-8913ad2ddb7c'],
            ],
            [
                'title' => 'Santorini Sunset Romance', 'destination' => 'Santorini', 'category' => 'Honeymoon', 'package_type' => 'International', 'tour_type' => 'Private',
                'price' => 1899, 'discount_price' => null, 'duration_days' => 5, 'duration_nights' => 4, 'location' => 'Santorini, Greece', 'is_featured' => true,
                'main_image' => '1570077188670-e3a8d69ac5ff', 'short' => 'Whitewashed villages, caldera views and legendary sunsets make this the ultimate romantic escape.',
                'gallery' => ['1613395877344-13d4a8e0d49e', '1601581875309-fafbf2d3ed3a'],
            ],
            [
                'title' => 'Swiss Alps Adventure Trek', 'destination' => 'Swiss Alps', 'category' => 'Adventure', 'package_type' => 'International', 'tour_type' => 'Group',
                'price' => 2199, 'discount_price' => 1899, 'duration_days' => 8, 'duration_nights' => 7, 'location' => 'Interlaken, Switzerland', 'is_featured' => true,
                'main_image' => '1531366936337-7c912a4589a7', 'short' => 'Conquer alpine trails, ride scenic railways and breathe the crisp mountain air of the Swiss Alps.',
                'gallery' => ['1527668752968-14dc70a27c95', '1506905925346-21bda4d32df4'],
            ],
            [
                'title' => 'Dubai Luxury City Experience', 'destination' => 'Dubai', 'category' => 'Luxury', 'package_type' => 'International', 'tour_type' => 'Private',
                'price' => 1599, 'discount_price' => 1399, 'duration_days' => 5, 'duration_nights' => 4, 'location' => 'Dubai, UAE', 'is_featured' => true,
                'main_image' => '1512453979798-5ea266f8880c', 'short' => 'Skyscrapers, desert safaris and world-class luxury — discover the dazzling city of Dubai.',
                'gallery' => ['1518684079-3c830dcef090', '1546412414-e1885259563a'],
            ],
            [
                'title' => 'Kyoto Cultural Discovery', 'destination' => 'Kyoto', 'category' => 'Cultural', 'package_type' => 'International', 'tour_type' => 'Group',
                'price' => 1749, 'discount_price' => null, 'duration_days' => 7, 'duration_nights' => 6, 'location' => 'Kyoto, Japan', 'is_featured' => false,
                'main_image' => '1493976040374-85c8e12f0c0e', 'short' => 'Ancient temples, geisha districts and tranquil gardens reveal the soul of traditional Japan.',
                'gallery' => ['1545569341-9eb8b30979d9', '1528360983277-13d401cdc186'],
            ],
            [
                'title' => 'Maldives Overwater Bliss', 'destination' => 'Maldives', 'category' => 'Honeymoon', 'package_type' => 'International', 'tour_type' => 'Private',
                'price' => 2499, 'discount_price' => 2199, 'duration_days' => 6, 'duration_nights' => 5, 'location' => 'Malé, Maldives', 'is_featured' => true,
                'main_image' => '1514282401047-d79a71a590e8', 'short' => 'Wake up over turquoise lagoons in a private overwater villa in this dreamy island paradise.',
                'gallery' => ['1573843981267-be1999ff37cd', '1506744038136-46273834b3fb'],
            ],
            [
                'title' => 'Bali Family Fun Holiday', 'destination' => 'Bali', 'category' => 'Family', 'package_type' => 'International', 'tour_type' => 'Group',
                'price' => 1099, 'discount_price' => null, 'duration_days' => 5, 'duration_nights' => 4, 'location' => 'Bali, Indonesia', 'is_featured' => false,
                'main_image' => '1518548419970-58e3b4079ab2', 'short' => 'Water parks, gentle beaches and cultural fun — a perfect Bali adventure for the whole family.',
                'gallery' => ['1604999565976-8913ad2ddb7c'],
            ],
        ];

        foreach ($packages as $p) {
            $destination = Destination::where('name', $p['destination'])->first();

            $package = Package::updateOrCreate(
                ['slug' => Str::slug($p['title'])],
                [
                    'title' => $p['title'],
                    'destination_id' => $destination?->id,
                    'category' => $p['category'],
                    'package_type' => $p['package_type'],
                    'tour_type' => $p['tour_type'],
                    'price' => $p['price'],
                    'discount_price' => $p['discount_price'],
                    'duration_days' => $p['duration_days'],
                    'duration_nights' => $p['duration_nights'],
                    'location' => $p['location'],
                    'max_people' => 16,
                    'main_image' => $img($p['main_image']),
                    'short_description' => $p['short'],
                    'description' => '<p>'.$p['short'].'</p><p>This expertly curated tour combines comfort, adventure and authentic local experiences. Our knowledgeable guides ensure every moment is memorable, from handpicked accommodation to seamless transfers and unforgettable excursions.</p><h3>Highlights</h3><ul><li>Handpicked 4 &amp; 5-star accommodation</li><li>Daily breakfast and selected meals</li><li>Expert local guides throughout</li><li>All entrance fees and transfers included</li></ul>',
                    'itinerary' => [
                        ['day' => '1', 'title' => 'Arrival & Welcome', 'detail' => 'Arrive at your destination, private transfer to your hotel and a welcome dinner with your tour group.'],
                        ['day' => '2', 'title' => 'Guided City & Highlights Tour', 'detail' => 'Explore the must-see landmarks with your expert guide, including cultural sites and local markets.'],
                        ['day' => '3', 'title' => 'Adventure & Excursion Day', 'detail' => 'Full-day excursion to the region\'s most iconic natural attractions with lunch included.'],
                        ['day' => '4', 'title' => 'Leisure & Optional Activities', 'detail' => 'Enjoy a free morning followed by optional activities such as spa, water sports or shopping.'],
                        ['day' => '5', 'title' => 'Departure', 'detail' => 'Final breakfast, last-minute shopping and private transfer to the airport for your departure.'],
                    ],
                    'inclusions' => ['Accommodation in selected hotels', 'Daily breakfast', 'Airport transfers', 'Guided tours as per itinerary', 'All entrance fees', 'Local English-speaking guide'],
                    'exclusions' => ['International flights', 'Travel insurance', 'Personal expenses', 'Optional activities', 'Visa fees', 'Tips and gratuities'],
                    'terms' => "A 30% deposit is required to confirm your booking. Full payment is due 30 days before departure. Cancellations made 30+ days before departure receive a full refund minus the deposit. Prices are per person based on double occupancy and subject to availability.",
                    'available_dates' => [
                        now()->addMonths(1)->startOfMonth()->addDays(9)->toDateString(),
                        now()->addMonths(2)->startOfMonth()->addDays(14)->toDateString(),
                        now()->addMonths(3)->startOfMonth()->addDays(4)->toDateString(),
                    ],
                    'is_featured' => $p['is_featured'],
                    'is_active' => true,
                    'meta_title' => $p['title'],
                    'meta_description' => $p['short'],
                ]
            );

            // Gallery images
            $package->images()->delete();
            foreach ($p['gallery'] as $sort => $gid) {
                $package->images()->create([
                    'image_path' => $img($gid),
                    'sort_order' => $sort,
                ]);
            }
        }
    }
}
