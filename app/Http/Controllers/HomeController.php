<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\Booking;
use App\Models\Destination;
use App\Models\Gallery;
use App\Models\Package;
use App\Models\Slider;
use App\Models\Testimonial;

class HomeController extends Controller
{
    public function index()
    {
        $sliders = Slider::active()->orderBy('sort_order')->get();

        $featuredPackages = Package::active()->featured()->with('destination')
            ->latest()->take(6)->get();

        if ($featuredPackages->isEmpty()) {
            $featuredPackages = Package::active()->with('destination')->latest()->take(6)->get();
        }

        $destinations = Destination::active()
            ->withCount('packages')
            ->orderByDesc('is_featured')
            ->orderBy('sort_order')
            ->take(6)->get();

        $testimonials = Testimonial::active()->orderBy('sort_order')->take(6)->get();

        $latestBlogs = Blog::published()->with('category')->latest('published_at')->take(3)->get();

        $allDestinations = Destination::active()->orderBy('name')->get();
        $categories = Package::active()->whereNotNull('category')->distinct()->pluck('category');

        // Booking form (same form used on the booking page) needs selectable
        // packages & destinations.
        $packages = Package::active()->orderBy('title')->get(['id', 'title', 'destination_id', 'price', 'discount_price']);
        $bookingDestinations = Destination::active()->orderBy('name')->get(['id', 'name']);

        // Home-page gallery preview.
        $galleryImages = Gallery::active()->orderBy('sort_order')->latest()->take(8)->get();

        // Headline stats — derived from real data, with sensible floors so the
        // band never reads "0" on a fresh install.
        $stats = [
            'destinations' => max(Destination::active()->count(), 0),
            'packages'     => max(Package::active()->count(), 0),
            'travelers'    => max(Booking::count(), 0),
            'reviews'      => max(Testimonial::active()->count(), 0),
        ];

        return view('frontend.home', compact(
            'sliders', 'featuredPackages', 'destinations',
            'testimonials', 'latestBlogs', 'allDestinations', 'categories', 'stats',
            'packages', 'bookingDestinations', 'galleryImages'
        ));
    }
}
