<?php

namespace Database\Seeders;

use App\Models\Testimonial;
use Illuminate\Database\Seeder;

class TestimonialSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            ['name' => 'Olivia Bennett', 'location' => 'London, UK', 'rating' => 5, 'message' => 'Absolutely flawless experience from start to finish. Every detail was taken care of and our Bali trip exceeded all expectations!'],
            ['name' => 'Marcus Reid', 'location' => 'Sydney, Australia', 'rating' => 5, 'message' => 'The Swiss Alps trek was the adventure of a lifetime. Professional guides, stunning scenery and incredible organisation.'],
            ['name' => 'Aisha Khan', 'location' => 'Dubai, UAE', 'rating' => 4, 'message' => 'Our honeymoon in Santorini was magical. The team listened to exactly what we wanted and delivered perfectly.'],
            ['name' => 'Daniel Lee', 'location' => 'Toronto, Canada', 'rating' => 5, 'message' => 'Best travel agency we have ever used. Transparent pricing, responsive support and unforgettable memories.'],
            ['name' => 'Sophie Müller', 'location' => 'Berlin, Germany', 'rating' => 5, 'message' => 'The Maldives package was pure bliss. From the overwater villa to the snorkelling trips, everything was perfect.'],
            ['name' => 'Carlos Rivera', 'location' => 'Madrid, Spain', 'rating' => 4, 'message' => 'Wonderful cultural tour of Kyoto. Knowledgeable guides and a thoughtfully planned itinerary. Highly recommend!'],
        ];

        foreach ($items as $i => $item) {
            Testimonial::updateOrCreate(
                ['name' => $item['name']],
                array_merge($item, [
                    'image' => 'https://i.pravatar.cc/150?img=' . ($i + 11),
                    'is_active' => true,
                    'sort_order' => $i,
                ])
            );
        }
    }
}
