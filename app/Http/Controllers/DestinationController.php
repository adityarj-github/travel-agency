<?php

namespace App\Http\Controllers;

use App\Models\Destination;
use Illuminate\Http\Request;

class DestinationController extends Controller
{
    public function index()
    {
        $destinations = Destination::active()
            ->withCount('activePackages')
            ->orderBy('sort_order')
            ->orderBy('name')
            ->paginate(12);

        return view('frontend.destinations.index', compact('destinations'));
    }

    public function show(Request $request, Destination $destination)
    {
        abort_unless($destination->is_active, 404);

        // All active tours for this destination — the set the page is built around.
        $base = $destination->packages()->where('is_active', true);

        // Summary for the stats strip (computed on the whole destination, before filtering).
        $effectivePrice = 'CASE WHEN discount_price IS NOT NULL AND discount_price > 0 THEN discount_price ELSE price END';
        $stats = [
            'count'    => (clone $base)->count(),
            'from'     => (clone $base)->selectRaw("MIN($effectivePrice) AS p")->value('p'),
            'min_days' => (clone $base)->min('duration_days'),
            'max_days' => (clone $base)->max('duration_days'),
        ];

        // Category chips — only categories that actually exist for this destination.
        $categories = (clone $base)
            ->whereNotNull('category')->where('category', '!=', '')
            ->distinct()->orderBy('category')->pluck('category');

        // Apply the visitor's filter + sort to the displayed list.
        $query = (clone $base)->with('destination');

        if ($request->filled('category')) {
            $query->where('category', $request->string('category'));
        }

        match ($request->get('sort')) {
            'price_asc'  => $query->orderByRaw("$effectivePrice ASC"),
            'price_desc' => $query->orderByRaw("$effectivePrice DESC"),
            'duration'   => $query->orderBy('duration_days')->orderBy('duration_nights'),
            'name'       => $query->orderBy('title'),
            default      => $query->orderByDesc('is_featured')->latest(),
        };

        $packages = $query->paginate(9)->withQueryString();

        // Cross-navigation: a few other destinations to explore.
        $otherDestinations = Destination::active()
            ->whereKeyNot($destination->getKey())
            ->withCount('activePackages')
            ->orderByDesc('is_featured')
            ->orderBy('sort_order')
            ->take(3)
            ->get();

        return view('frontend.destinations.show', compact(
            'destination', 'packages', 'stats', 'categories', 'otherDestinations'
        ));
    }
}
