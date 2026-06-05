<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\BlogCategory;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    public function index(Request $request)
    {
        $query = Blog::published()->with('category');

        if ($search = $request->input('q')) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('excerpt', 'like', "%{$search}%");
            });
        }

        if ($category = $request->input('category')) {
            $query->whereHas('category', fn ($q) => $q->where('slug', $category));
        }

        $blogs = $query->latest('published_at')->latest()->paginate(9)->withQueryString();

        $categories = BlogCategory::active()->withCount(['blogs' => fn ($q) => $q->published()])->orderBy('name')->get();
        $recentPosts = Blog::published()->latest('published_at')->take(5)->get();

        return view('frontend.blog.index', compact('blogs', 'categories', 'recentPosts'));
    }

    public function show(Blog $blog)
    {
        abort_unless($blog->is_published, 404);

        $blog->load('category');
        $blog->increment('views');

        $related = Blog::published()
            ->where('id', '!=', $blog->id)
            ->when($blog->blog_category_id, fn ($q) => $q->where('blog_category_id', $blog->blog_category_id))
            ->latest('published_at')
            ->take(3)->get();

        if ($related->count() < 3) {
            $related = Blog::published()->where('id', '!=', $blog->id)->latest('published_at')->take(3)->get();
        }

        $categories = BlogCategory::active()->withCount(['blogs' => fn ($q) => $q->published()])->orderBy('name')->get();
        $recentPosts = Blog::published()->where('id', '!=', $blog->id)->latest('published_at')->take(5)->get();

        return view('frontend.blog.show', compact('blog', 'related', 'categories', 'recentPosts'));
    }
}
