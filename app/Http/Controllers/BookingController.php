<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBookingRequest;
use App\Models\Booking;
use App\Models\Coupon;
use App\Models\Destination;
use App\Models\Package;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    public function create(Request $request)
    {
        $packages = Package::active()->orderBy('title')->get(['id', 'title', 'destination_id', 'price', 'discount_price']);
        $destinations = Destination::active()->orderBy('name')->get(['id', 'name']);

        $selectedPackage = $request->filled('package')
            ? Package::active()->where('slug', $request->input('package'))->first()
            : null;

        return view('frontend.booking', compact('packages', 'destinations', 'selectedPackage'));
    }

    public function store(StoreBookingRequest $request)
    {
        $data = $request->validated();
        $data['status'] = 'pending';

        // Link to the signed-in customer (guests stay null).
        if (Auth::check() && Auth::user()->isCustomer()) {
            $data['user_id'] = Auth::id();
        }

        // Derive destination from package if not supplied.
        $package = ! empty($data['package_id']) ? Package::find($data['package_id']) : null;
        if (empty($data['destination_id']) && $package) {
            $data['destination_id'] = $package->destination_id;
        }

        // Pricing snapshot.
        $travelers = (int) $data['adults'] + (int) ($data['children'] ?? 0);
        $subtotal = $package ? round((float) $package->effective_price * $travelers, 2) : 0.0;

        $coupon = $this->resolveCoupon($data['coupon_code'] ?? null, $subtotal);
        $discount = $coupon ? $coupon->discountFor($subtotal) : 0.0;

        $data['subtotal'] = $subtotal;
        $data['discount_amount'] = $discount;
        $data['total'] = max(0, round($subtotal - $discount, 2));
        $data['coupon_id'] = $coupon?->id;
        $data['coupon_code'] = $coupon?->code;

        $booking = Booking::create($data);

        if ($coupon) {
            $coupon->increment('used_count');
        }

        return redirect()
            ->route('booking.create')
            ->with('success', "Your booking inquiry ({$booking->reference}) has been submitted! Our team will contact you soon.");
    }

    /**
     * Live coupon validation for the booking form (AJAX). Returns the computed
     * discount for the supplied subtotal without persisting anything.
     */
    public function applyCoupon(Request $request)
    {
        $request->validate([
            'code' => ['required', 'string', 'max:40'],
            'subtotal' => ['required', 'numeric', 'min:0'],
        ]);

        $subtotal = (float) $request->input('subtotal');
        $coupon = $this->resolveCoupon($request->input('code'), $subtotal);

        if (! $coupon) {
            return response()->json([
                'valid' => false,
                'message' => 'This code is invalid, expired, or not applicable to your selection.',
            ], 422);
        }

        $discount = $coupon->discountFor($subtotal);

        return response()->json([
            'valid' => true,
            'code' => $coupon->code,
            'discount' => $discount,
            'total' => max(0, round($subtotal - $discount, 2)),
            'message' => 'Coupon applied — you save ' . setting('currency_symbol', '$') . number_format($discount, 2) . '.',
        ]);
    }

    /** Find a redeemable coupon by code for the given subtotal, or null. */
    private function resolveCoupon(?string $code, float $subtotal): ?Coupon
    {
        if (blank($code) || $subtotal <= 0) {
            return null;
        }

        $coupon = Coupon::active()->whereRaw('UPPER(code) = ?', [strtoupper(trim($code))])->first();

        return $coupon && $coupon->isRedeemable($subtotal) ? $coupon : null;
    }
}
