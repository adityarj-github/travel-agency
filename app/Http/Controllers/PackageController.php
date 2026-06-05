<?php

namespace App\Http\Controllers;

use App\Models\Destination;
use App\Models\Package;
use Illuminate\Http\Request;

class PackageController extends Controller
{
    public function index(Request $request)
    {
        $query = Package::active()->with('destination');

        // Keyword search
        if ($search = $request->input('q')) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('location', 'like', "%{$search}%")
                  ->orWhere('short_description', 'like', "%{$search}%");
            });
        }

        // Destination filter (by slug or id)
        if ($destination = $request->input('destination')) {
            $query->whereHas('destination', function ($q) use ($destination) {
                $q->where('slug', $destination)->orWhere('id', $destination);
            });
        }

        // Category & type filters
        if ($category = $request->input('category')) {
            $query->where('category', $category);
        }
        if ($type = $request->input('type')) {
            $query->where('package_type', $type);
        }

        // Price range
        if ($request->filled('min_price')) {
            $query->where('price', '>=', (float) $request->input('min_price'));
        }
        if ($request->filled('max_price')) {
            $query->where('price', '<=', (float) $request->input('max_price'));
        }

        // Duration filter
        if ($request->filled('duration')) {
            [$min, $max] = match ($request->input('duration')) {
                '1-3' => [1, 3],
                '4-7' => [4, 7],
                '8-14' => [8, 14],
                '15+' => [15, 999],
                default => [null, null],
            };
            if ($min !== null) {
                $query->whereBetween('duration_days', [$min, $max]);
            }
        }

        // Sorting
        match ($request->input('sort')) {
            'price_asc'  => $query->orderBy('price'),
            'price_desc' => $query->orderByDesc('price'),
            'name'       => $query->orderBy('title'),
            default      => $query->orderByDesc('is_featured')->latest(),
        };

        $packages = $query->paginate(9)->withQueryString();

        $destinations = Destination::active()->orderBy('name')->get();
        $categories = Package::active()->whereNotNull('category')->distinct()->orderBy('category')->pluck('category');
        $types = Package::active()->whereNotNull('package_type')->distinct()->orderBy('package_type')->pluck('package_type');

        return view('frontend.packages.index', compact('packages', 'destinations', 'categories', 'types'));
    }

    public function show(Package $package)
    {
        abort_unless($package->is_active, 404);

        $package->load('destination', 'images');
        $package->increment('views');

        $related = Package::active()
            ->where('id', '!=', $package->id)
            ->where(function ($q) use ($package) {
                $q->where('destination_id', $package->destination_id)
                  ->orWhere('category', $package->category);
            })
            ->take(3)->get();

        if ($related->count() < 3) {
            $related = Package::active()->where('id', '!=', $package->id)->latest()->take(3)->get();
        }

        return view('frontend.packages.show', compact('package', 'related'));
    }
}
