<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;

class PaymentLinkController extends Controller
{
    public function index()
    {
        $links = Booking::paymentLinks()->latest()->paginate(15);

        return view('admin.payment-links.index', compact('links'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:30'],
            'amount' => ['required', 'numeric', 'min:1', 'max:9999999'],
            'description' => ['nullable', 'string', 'max:1000'],
        ]);

        $amount = round((float) $data['amount'], 2);

        $booking = Booking::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'] ?? null,
            'message' => $data['description'] ?? null,
            'adults' => 1,
            'children' => 0,
            'subtotal' => $amount,
            'discount_amount' => 0,
            'total' => $amount,
            'status' => 'pending',
            'payment_status' => 'unpaid',
            'payment_method' => 'razorpay',
            'is_payment_link' => true,
        ]);

        return redirect()->route('admin.payment-links.index')
            ->with('success', "Payment link created for {$booking->name} ({$booking->reference}).")
            ->with('new_payment_link', $booking->pay_url);
    }

    public function destroy(Booking $paymentLink)
    {
        abort_unless($paymentLink->is_payment_link, 404);

        $paymentLink->delete();

        return back()->with('success', 'Payment link deleted.');
    }
}
