<?php

namespace App\Http\Controllers;

use App\Models\Destination;

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

    public function show(Destination $destination)
    {
        abort_unless($destination->is_active, 404);

        $packages = $destination->packages()
            ->where('is_active', true)
            ->with('destination')
            ->latest()
            ->paginate(9);

        return view('frontend.destinations.show', compact('destination', 'packages'));
    }
}
