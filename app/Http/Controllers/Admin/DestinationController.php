<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Concerns\HandlesImageUploads;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\DestinationRequest;
use App\Models\Destination;
use Illuminate\Http\Request;

class DestinationController extends Controller
{
    use HandlesImageUploads;

    public function index(Request $request)
    {
        $query = Destination::withCount('packages')->orderBy('sort_order')->orderBy('name');

        if ($search = $request->input('search')) {
            $query->where('name', 'like', "%{$search}%");
        }

        $destinations = $query->paginate(12)->withQueryString();

        return view('admin.destinations.index', compact('destinations'));
    }

    public function create()
    {
        return view('admin.destinations.create');
    }

    public function store(DestinationRequest $request)
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {
            $data['image'] = $this->storeImage($request->file('image'), 'destinations');
        }

        Destination::create($data);

        return redirect()->route('admin.destinations.index')->with('success', 'Destination created successfully.');
    }

    public function edit(Destination $destination)
    {
        return view('admin.destinations.edit', compact('destination'));
    }

    public function update(DestinationRequest $request, Destination $destination)
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {
            $data['image'] = $this->replaceImage($request->file('image'), $destination->image, 'destinations');
        }

        $destination->update($data);

        return redirect()->route('admin.destinations.index')->with('success', 'Destination updated successfully.');
    }

    public function destroy(Destination $destination)
    {
        $this->deleteImage($destination->image);
        $destination->delete();

        return back()->with('success', 'Destination deleted successfully.');
    }

    public function toggle(Destination $destination)
    {
        $destination->update(['is_active' => ! $destination->is_active]);

        return back()->with('success', 'Destination status updated.');
    }
}
