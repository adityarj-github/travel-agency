<?php

namespace Database\Seeders;

use App\Models\Blog;
use App\Models\BlogCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class BlogSeeder extends Seeder
{
    public function run(): void
    {
        $categories = ['Travel Tips', 'Destinations', 'Adventure', 'Food & Culture'];
        $catModels = [];
        foreach ($categories as $name) {
            $catModels[$name] = BlogCategory::updateOrCreate(
                ['slug' => Str::slug($name)],
                ['name' => $name, 'is_active' => true]
            );
        }

        $img = fn ($id) => "https://images.unsplash.com/photo-{$id}?auto=format&fit=crop&w=1200&q=80";

        $posts = [
            ['title' => '10 Essential Tips for First-Time International Travellers', 'category' => 'Travel Tips', 'author' => 'Emma Carter', 'image' => '1488646953014-85cb44e25828',
             'excerpt' => 'Heading abroad for the first time? These practical tips will help you travel smarter, safer and stress-free.'],
            ['title' => 'Top 7 Must-Visit Destinations in 2026', 'category' => 'Destinations', 'author' => 'James Wilson', 'image' => '1469854523086-cc02fe5d8800',
             'excerpt' => 'From hidden island gems to bustling cultural capitals, here are the destinations topping every traveller\'s list this year.'],
            ['title' => 'A Beginner\'s Guide to Mountain Trekking', 'category' => 'Adventure', 'author' => 'Sofia Martinez', 'image' => '1454496522488-7a8e488e8606',
             'excerpt' => 'Everything you need to know before lacing up your boots for your first alpine adventure.'],
            ['title' => 'Street Food Around the World: A Culinary Journey', 'category' => 'Food & Culture', 'author' => 'Liam Chen', 'image' => '1504674900247-0877df9cc836',
             'excerpt' => 'Discover the flavours that define cultures, from Bangkok night markets to Mexican taquerias.'],
            ['title' => 'How to Pack Light for Any Trip', 'category' => 'Travel Tips', 'author' => 'Emma Carter', 'image' => '1553062407-98eeb64c6a62',
             'excerpt' => 'Master the art of minimalist packing and never check a bag again with these expert strategies.'],
            ['title' => 'The Ultimate Bali Itinerary for 7 Days', 'category' => 'Destinations', 'author' => 'James Wilson', 'image' => '1537996194471-e657df975ab4',
             'excerpt' => 'Make the most of a week in paradise with our day-by-day guide to the best of Bali.'],
        ];

        $body = '<p>Travel has the power to transform the way we see the world. In this article, we share insights and practical advice gathered from years of exploration.</p><h2>Plan Ahead, But Stay Flexible</h2><p>The best trips balance careful planning with room for spontaneity. Research your destination, but leave space for unexpected discoveries.</p><h2>Embrace Local Culture</h2><p>Immersing yourself in local customs, cuisine and conversations is what turns a holiday into a story worth telling.</p><blockquote>The world is a book, and those who do not travel read only one page.</blockquote><p>Whether you are a seasoned globetrotter or planning your very first trip, we hope these tips inspire your next adventure.</p>';

        foreach ($posts as $i => $post) {
            Blog::updateOrCreate(
                ['slug' => Str::slug($post['title'])],
                [
                    'blog_category_id' => $catModels[$post['category']]->id,
                    'title' => $post['title'],
                    'author' => $post['author'],
                    'featured_image' => $img($post['image']),
                    'excerpt' => $post['excerpt'],
                    'content' => $body,
                    'meta_title' => $post['title'],
                    'meta_description' => $post['excerpt'],
                    'is_published' => true,
                    'published_at' => now()->subDays(($i + 1) * 3),
                    'views' => rand(50, 800),
                ]
            );
        }
    }
}
