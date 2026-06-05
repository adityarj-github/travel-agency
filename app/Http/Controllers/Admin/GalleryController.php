<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Concerns\HandlesImageUploads;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\GalleryRequest;
use App\Models\Gallery;
use Illuminate\Http\Request;

class GalleryController extends Controller
{
    use HandlesImageUploads;

    public function index(Request $request)
    {
        $query = Gallery::orderBy('sort_order')->latest();

        if ($request->filled('category')) {
            $query->where('category', $request->input('category'));
        }

        $images = $query->paginate(24)->withQueryString();
        $categories = Gallery::whereNotNull('category')->distinct()->pluck('category');

        return view('admin.galleries.index', compact('images', 'categories'));
    }

    public function create()
    {
        return view('admin.galleries.create');
    }

    public function store(GalleryRequest $request)
    {
        foreach ($request->file('images') as $file) {
            Gallery::create([
                'title' => $request->input('title'),
                'category' => $request->input('category'),
                'is_active' => $request->boolean('is_active', true),
                'image' => $this->storeImage($file, 'gallery'),
            ]);
        }

        return redirect()->route('admin.galleries.index')->with('success', 'Image(s) uploaded successfully.');
    }

    public function edit(Gallery $gallery)
    {
        return view('admin.galleries.edit', compact('gallery'));
    }

    public function update(GalleryRequest $request, Gallery $gallery)
    {
        $data = [
            'title' => $request->input('title'),
            'category' => $request->input('category'),
            'is_active' => $request->boolean('is_active'),
        ];

        if ($request->hasFile('image')) {
            $data['image'] = $this->replaceImage($request->file('image'), $gallery->image, 'gallery');
        }

        $gallery->update($data);

        return redirect()->route('admin.galleries.index')->with('success', 'Image updated successfully.');
    }

    public function destroy(Gallery $gallery)
    {
        $this->deleteImage($gallery->image);
        $gallery->delete();

        return back()->with('success', 'Image deleted successfully.');
    }

    public function toggle(Gallery $gallery)
    {
        $gallery->update(['is_active' => ! $gallery->is_active]);

        return back()->with('success', 'Image status updated.');
    }
}
