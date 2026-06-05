<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use App\Models\Package;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    public function index()
    {
        $packages = Auth::user()->wishlist()
            ->with('destination')
            ->orderByDesc('wishlists.created_at')
            ->paginate(9);

        return view('frontend.account.wishlist', compact('packages'));
    }

    /** Toggle a package in/out of the authenticated customer's wishlist. */
    public function toggle(Request $request, Package $package)
    {
        $result = Auth::user()->wishlist()->toggle($package->id);
        $added = ! empty($result['attached']);

        if ($request->expectsJson()) {
            return response()->json([
                'added' => $added,
                'count' => Auth::user()->wishlist()->count(),
            ]);
        }

        return back()->with('success', $added
            ? 'Added to your wishlist.'
            : 'Removed from your wishlist.');
    }

    public function destroy(Package $package)
    {
        Auth::user()->wishlist()->detach($package->id);

        return back()->with('success', 'Removed from your wishlist.');
    }
}
