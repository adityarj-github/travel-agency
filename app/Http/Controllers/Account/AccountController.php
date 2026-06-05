<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class AccountController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();

        $bookings = $user->bookings()->with('package', 'destination')->latest()->take(5)->get();

        $stats = [
            'bookings' => $user->bookings()->count(),
            'pending' => $user->bookings()->where('status', 'pending')->count(),
            'confirmed' => $user->bookings()->where('status', 'confirmed')->count(),
            'wishlist' => $user->wishlist()->count(),
        ];

        return view('frontend.account.dashboard', compact('user', 'bookings', 'stats'));
    }

    public function bookings()
    {
        $bookings = Auth::user()->bookings()
            ->with('package', 'destination')
            ->latest()
            ->paginate(10);

        return view('frontend.account.bookings', compact('bookings'));
    }

    public function profile()
    {
        return view('frontend.account.profile', ['user' => Auth::user()]);
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'phone' => ['nullable', 'string', 'max:30'],
        ]);

        $user->update($data);

        return back()->with('success', 'Your profile has been updated.');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);

        Auth::user()->update(['password' => $request->input('password')]);

        return back()->with('success', 'Your password has been changed.');
    }

    /** Download the PDF voucher for one of the customer's own bookings. */
    public function voucher(Booking $booking)
    {
        abort_unless($booking->user_id === Auth::id(), 403);

        return $this->streamVoucher($booking);
    }

    public static function streamVoucher(Booking $booking)
    {
        $booking->loadMissing('package', 'destination', 'coupon');

        $pdf = Pdf::loadView('pdf.voucher', ['booking' => $booking])
            ->setPaper('a4');

        return $pdf->download('voucher-' . $booking->reference . '.pdf');
    }
}
