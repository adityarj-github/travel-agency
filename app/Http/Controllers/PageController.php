<?php

namespace App\Http\Controllers;

use App\Models\Testimonial;

class PageController extends Controller
{
    public function about()
    {
        $testimonials = Testimonial::active()->orderBy('sort_order')->take(6)->get();

        return view('frontend.about', compact('testimonials'));
    }
}
