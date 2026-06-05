<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Concerns\HandlesImageUploads;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\BlogRequest;
use App\Models\Blog;
use App\Models\BlogCategory;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    use HandlesImageUploads;

    public function index(Request $request)
    {
        $query = Blog::with('category')->latest();

        if ($search = $request->input('search')) {
            $query->where('title', 'like', "%{$search}%");
        }
        if ($request->filled('category')) {
            $query->where('blog_category_id', $request->input('category'));
        }
        if ($request->filled('status')) {
            $query->where('is_published', $request->input('status') === 'published');
        }

        $blogs = $query->paginate(12)->withQueryString();
        $categories = BlogCategory::orderBy('name')->get();

        return view('admin.blogs.index', compact('blogs', 'categories'));
    }

    public function create()
    {
        $categories = BlogCategory::orderBy('name')->get();

        return view('admin.blogs.create', compact('categories'));
    }

    public function store(BlogRequest $request)
    {
        $data = $this->prepareData($request);

        if ($request->hasFile('featured_image')) {
            $data['featured_image'] = $this->storeImage($request->file('featured_image'), 'blogs');
        }

        Blog::create($data);

        return redirect()->route('admin.blogs.index')->with('success', 'Blog post created successfully.');
    }

    public function edit(Blog $blog)
    {
        $categories = BlogCategory::orderBy('name')->get();

        return view('admin.blogs.edit', compact('blog', 'categories'));
    }

    public function update(BlogRequest $request, Blog $blog)
    {
        $data = $this->prepareData($request);

        if ($request->hasFile('featured_image')) {
            $data['featured_image'] = $this->replaceImage($request->file('featured_image'), $blog->featured_image, 'blogs');
        }

        $blog->update($data);

        return redirect()->route('admin.blogs.index')->with('success', 'Blog post updated successfully.');
    }

    public function destroy(Blog $blog)
    {
        $this->deleteImage($blog->featured_image);
        $blog->delete();

        return back()->with('success', 'Blog post deleted successfully.');
    }

    public function toggle(Blog $blog)
    {
        $publishing = ! $blog->is_published;
        $blog->update([
            'is_published' => $publishing,
            'published_at' => $publishing && ! $blog->published_at ? now() : $blog->published_at,
        ]);

        return back()->with('success', 'Blog publish status updated.');
    }

    private function prepareData(BlogRequest $request): array
    {
        $data = $request->validated();

        if ($data['is_published'] && empty($data['published_at'])) {
            $data['published_at'] = now();
        }

        return $data;
    }
}
