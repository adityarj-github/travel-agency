<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            'site_name' => 'Wanderlust Travels',
            'currency_symbol' => '$',
            'footer_text' => 'Wanderlust Travels crafts personalised journeys to the world\'s most beautiful destinations. Your adventure begins with us.',
            'phone' => '+1 555 010 2030',
            'email' => 'hello@wanderlust.test',
            'whatsapp' => '+15550102030',
            'address' => '123 Explorer Avenue, Suite 100, New York, NY 10001',
            'map_embed' => '<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3022.1!2d-73.9857!3d40.7484!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zNDDCsDQ0JzU0LjIiTiA3M8KwNTknMDguNSJX!5e0!3m2!1sen!2sus!4v1600000000000" width="100%" height="400" style="border:0;" allowfullscreen="" loading="lazy"></iframe>',
            'facebook' => 'https://facebook.com',
            'instagram' => 'https://instagram.com',
            'twitter' => 'https://x.com',
            'youtube' => 'https://youtube.com',
            'meta_title' => 'Wanderlust Travels — Unforgettable Tours & Travel Packages',
            'meta_description' => 'Discover curated travel packages, breathtaking destinations and seamless booking with Wanderlust Travels.',
            'about_heading' => 'Your Trusted Travel Partner Since 2014',
            'about_content' => "We are a passionate team of travel experts dedicated to turning your travel dreams into reality. With over a decade of experience, we craft personalised journeys that create lifelong memories.\n\nFrom exotic beach getaways to thrilling mountain adventures, we handle every detail so you can focus on enjoying the experience.",
            'mission' => 'To make extraordinary travel accessible to everyone by delivering seamless, personalised and memorable journeys at exceptional value.',
            'vision' => 'To be the most trusted and loved travel agency, inspiring people to explore the world and connect with diverse cultures.',
            'stat_travellers' => '12,000+',
            'stat_destinations' => '85+',
            'stat_years' => '10+',
        ];

        Setting::setMany($settings);
    }
}
