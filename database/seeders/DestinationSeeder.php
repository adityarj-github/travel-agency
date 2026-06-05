<?php

namespace Database\Seeders;

use App\Models\Destination;
use Illuminate\Database\Seeder;

class DestinationSeeder extends Seeder
{
    public function run(): void
    {
        $destinations = [
            ['name' => 'Bali', 'country' => 'Indonesia', 'image' => 'https://images.unsplash.com/photo-1537996194471-e657df975ab4?auto=format&fit=crop&w=1200&q=80', 'is_featured' => true,
             'description' => 'Bali is a living postcard, an Indonesian paradise that feels like a fantasy. Soak up the sun on a stretch of fine white sand, or commune with the tropical creatures as you dive along coral ridges.'],
            ['name' => 'Santorini', 'country' => 'Greece', 'image' => 'https://images.unsplash.com/photo-1570077188670-e3a8d69ac5ff?auto=format&fit=crop&w=1200&q=80', 'is_featured' => true,
             'description' => 'Santorini is one of the Cyclades islands in the Aegean Sea, famed for its dramatic views, stunning sunsets, white-washed houses and blue-domed churches.'],
            ['name' => 'Swiss Alps', 'country' => 'Switzerland', 'image' => 'https://images.unsplash.com/photo-1531366936337-7c912a4589a7?auto=format&fit=crop&w=1200&q=80', 'is_featured' => true,
             'description' => 'The Swiss Alps offer breathtaking mountain scenery, world-class skiing, charming villages and pristine alpine lakes for the ultimate adventure escape.'],
            ['name' => 'Dubai', 'country' => 'UAE', 'image' => 'https://images.unsplash.com/photo-1512453979798-5ea266f8880c?auto=format&fit=crop&w=1200&q=80', 'is_featured' => true,
             'description' => 'Dubai is a city of superlatives — towering skyscrapers, luxury shopping, desert safaris and golden beaches make it a dazzling destination.'],
            ['name' => 'Kyoto', 'country' => 'Japan', 'image' => 'https://images.unsplash.com/photo-1493976040374-85c8e12f0c0e?auto=format&fit=crop&w=1200&q=80',
             'description' => 'Kyoto, once the capital of Japan, is a city of timeless temples, traditional wooden houses, serene gardens and exquisite cuisine.'],
            ['name' => 'Maldives', 'country' => 'Maldives', 'image' => 'https://images.unsplash.com/photo-1514282401047-d79a71a590e8?auto=format&fit=crop&w=1200&q=80', 'is_featured' => true,
             'description' => 'The Maldives is a tropical paradise of crystal-clear lagoons, overwater villas and vibrant coral reefs — perfect for honeymooners and divers alike.'],
        ];

        foreach ($destinations as $i => $data) {
            Destination::updateOrCreate(
                ['slug' => \Illuminate\Support\Str::slug($data['name'])],
                array_merge($data, ['is_active' => true, 'sort_order' => $i]),
            );
        }
    }
}
