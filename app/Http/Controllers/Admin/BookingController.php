<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Account\AccountController;
use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Package;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function index(Request $request)
    {
        $query = Booking::with('package')->latest();

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('reference', 'like', "%{$search}%");
            });
        }
        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }
        if ($request->filled('package')) {
            $query->where('package_id', $request->input('package'));
        }
        if ($request->filled('date')) {
            $query->whereDate('travel_date', $request->input('date'));
        }

        $bookings = $query->paginate(15)->withQueryString();
        $packages = Package::orderBy('title')->get(['id', 'title']);
        $statuses = Booking::STATUSES;

        return view('admin.bookings.index', compact('bookings', 'packages', 'statuses'));
    }

    public function show(Booking $booking)
    {
        $booking->load('package', 'destination', 'coupon', 'user');

        return view('admin.bookings.show', compact('booking'));
    }

    /** Download the PDF voucher / invoice for a booking. */
    public function voucher(Booking $booking)
    {
        return AccountController::streamVoucher($booking);
    }

    public function update(Request $request, Booking $booking)
    {
        $data = $request->validate([
            'status' => ['required', 'in:' . implode(',', Booking::STATUSES)],
            'admin_note' => ['nullable', 'string', 'max:2000'],
        ]);

        $booking->update($data);

        return back()->with('success', 'Booking updated successfully.');
    }

    public function updateStatus(Request $request, Booking $booking)
    {
        $data = $request->validate([
            'status' => ['required', 'in:' . implode(',', Booking::STATUSES)],
        ]);

        $booking->update($data);

        return back()->with('success', "Booking marked as {$data['status']}.");
    }

    public function destroy(Booking $booking)
    {
        $booking->delete();

        return redirect()->route('admin.bookings.index')->with('success', 'Booking deleted successfully.');
    }
}
