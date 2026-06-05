<?php

namespace Database\Seeders;

use App\Models\Slider;
use Illuminate\Database\Seeder;

class SliderSeeder extends Seeder
{
    public function run(): void
    {
        $slides = [
            ['title' => 'Discover Your Next Adventure', 'subtitle' => 'Explore the world with us', 'button_text' => 'Browse Packages', 'button_link' => '/packages',
             'image' => 'https://images.unsplash.com/photo-1488646953014-85cb44e25828?auto=format&fit=crop&w=1920&q=80'],
            ['title' => 'Unforgettable Island Escapes', 'subtitle' => 'Paradise awaits', 'button_text' => 'View Destinations', 'button_link' => '/destinations',
             'image' => 'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?auto=format&fit=crop&w=1920&q=80'],
            ['title' => 'Adventure in Every Direction', 'subtitle' => 'Make memories that last', 'button_text' => 'Plan Your Trip', 'button_link' => '/booking',
             'image' => 'https://images.unsplash.com/photo-1469474968028-56623f02e42e?auto=format&fit=crop&w=1920&q=80'],
        ];

        foreach ($slides as $i => $slide) {
            Slider::updateOrCreate(
                ['title' => $slide['title']],
                array_merge($slide, ['sort_order' => $i, 'is_active' => true])
            );
        }
    }
}
