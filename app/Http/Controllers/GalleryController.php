<?php

namespace App\Http\Controllers;

use App\Models\Gallery;
use Illuminate\Http\Request;

class GalleryController extends Controller
{
    public function index(Request $request)
    {
        $categories = Gallery::active()->whereNotNull('category')->distinct()->orderBy('category')->pluck('category');

        $query = Gallery::active()->orderBy('sort_order')->latest();

        if ($category = $request->input('category')) {
            $query->where('category', $category);
        }

        $images = $query->paginate(16)->withQueryString();

        return view('frontend.gallery', compact('images', 'categories'));
    }
}
