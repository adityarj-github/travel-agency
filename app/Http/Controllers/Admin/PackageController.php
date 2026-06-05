<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Concerns\HandlesImageUploads;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PackageRequest;
use App\Models\Destination;
use App\Models\Package;
use App\Models\PackageImage;
use Illuminate\Http\Request;

class PackageController extends Controller
{
    use HandlesImageUploads;

    public function index(Request $request)
    {
        $query = Package::with('destination')->latest();

        if ($search = $request->input('search')) {
            $query->where('title', 'like', "%{$search}%");
        }
        if ($request->filled('destination')) {
            $query->where('destination_id', $request->input('destination'));
        }
        if ($request->filled('status')) {
            $query->where('is_active', $request->input('status') === 'active');
        }

        $packages = $query->paginate(12)->withQueryString();
        $destinations = Destination::orderBy('name')->get();

        return view('admin.packages.index', compact('packages', 'destinations'));
    }

    public function create()
    {
        $destinations = Destination::orderBy('name')->get();

        return view('admin.packages.create', compact('destinations'));
    }

    public function store(PackageRequest $request)
    {
        $data = $this->prepareData($request);

        if ($request->hasFile('main_image')) {
            $data['main_image'] = $this->storeImage($request->file('main_image'), 'packages');
        }

        $package = Package::create($data);

        $this->syncGallery($request, $package);

        return redirect()->route('admin.packages.index')->with('success', 'Package created successfully.');
    }

    public function edit(Package $package)
    {
        $package->load('images');
        $destinations = Destination::orderBy('name')->get();

        return view('admin.packages.edit', compact('package', 'destinations'));
    }

    public function update(PackageRequest $request, Package $package)
    {
        $data = $this->prepareData($request);

        if ($request->hasFile('main_image')) {
            $data['main_image'] = $this->replaceImage($request->file('main_image'), $package->main_image, 'packages');
        }

        $package->update($data);

        $this->syncGallery($request, $package);

        return redirect()->route('admin.packages.index')->with('success', 'Package updated successfully.');
    }

    public function destroy(Package $package)
    {
        $this->deleteImage($package->main_image);
        foreach ($package->images as $img) {
            $this->deleteImage($img->image_path);
        }
        $package->delete();

        return back()->with('success', 'Package deleted successfully.');
    }

    public function toggle(Package $package)
    {
        $package->update(['is_active' => ! $package->is_active]);

        return back()->with('success', 'Package status updated.');
    }

    public function deleteImage(PackageImage $image)
    {
        $this->deleteImage($image->image_path);
        $image->delete();

        return back()->with('success', 'Gallery image removed.');
    }

    /* --------------------------------------------------------------------- */

    private function prepareData(PackageRequest $request): array
    {
        $data = $request->safe()->except(['main_image', 'gallery_images', 'inclusions', 'exclusions', 'available_dates', 'itinerary']);

        // Convert line-separated text fields into arrays.
        $data['inclusions'] = $this->linesToArray($request->input('inclusions'));
        $data['exclusions'] = $this->linesToArray($request->input('exclusions'));
        $data['available_dates'] = $this->linesToArray($request->input('available_dates'));

        // Filter out empty itinerary rows.
        $itinerary = collect($request->input('itinerary', []))
            ->filter(fn ($row) => ! empty($row['title']) || ! empty($row['detail']))
            ->values()
            ->map(fn ($row, $i) => [
                'day' => $row['day'] ?? (string) ($i + 1),
                'title' => $row['title'] ?? '',
                'detail' => $row['detail'] ?? '',
            ])
            ->all();

        $data['itinerary'] = $itinerary ?: null;

        return $data;
    }

    private function linesToArray(?string $text): ?array
    {
        if (blank($text)) {
            return null;
        }

        return collect(preg_split('/\r\n|\r|\n/', $text))
            ->map(fn ($line) => trim($line))
            ->filter()
            ->values()
            ->all();
    }

    private function syncGallery(Request $request, Package $package): void
    {
        if ($request->hasFile('gallery_images')) {
            foreach ($request->file('gallery_images') as $file) {
                $package->images()->create([
                    'image_path' => $this->storeImage($file, 'packages/gallery'),
                ]);
            }
        }
    }
}
