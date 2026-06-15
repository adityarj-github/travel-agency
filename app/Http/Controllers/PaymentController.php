<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Services\RazorpayService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    public function __construct(private readonly RazorpayService $razorpay)
    {
    }

    /**
     * Razorpay checkout page for a pending booking. Reachable by the guest who
     * created it (via the secret payment_token) or its owning customer.
     */
    public function show(Request $request, Booking $booking)
    {
        $this->authorizeBooking($request, $booking);

        if ($booking->isPaid()) {
            return redirect()->route('booking.payment.success', [
                'booking' => $booking,
                'token' => $booking->payment_token,
            ]);
        }

        abort_unless($booking->requiresPayment(), 404);

        if (! $this->razorpay->enabled()) {
            // No keys configured — fall back to the inquiry experience.
            return redirect()->route('booking.create')
                ->with('success', "Your booking ({$booking->reference}) was received. Our team will contact you to arrange payment.");
        }

        try {
            $orderId = $booking->razorpay_order_id ?: $this->razorpay->createOrderFor($booking);
        } catch (\Throwable $e) {
            Log::error('Razorpay order creation failed', ['booking' => $booking->id, 'error' => $e->getMessage()]);

            return redirect()->route('booking.create')
                ->with('error', 'We could not start the payment right now. Your booking ('
                    . $booking->reference . ') is saved — please try again or contact us.');
        }

        return view('frontend.payment', [
            'booking' => $booking,
            'razorpayKey' => $this->razorpay->key(),
            'orderId' => $orderId,
            'currency' => $this->razorpay->currency(),
            'amountMinor' => (int) round((float) $booking->total * 100),
        ]);
    }

    /**
     * Handle Razorpay checkout's success handler post. Verifies the signature
     * before marking the booking paid + confirmed.
     */
    public function callback(Request $request, Booking $booking)
    {
        $this->authorizeBooking($request, $booking);

        $data = $request->validate([
            'razorpay_payment_id' => ['required', 'string'],
            'razorpay_order_id' => ['required', 'string'],
            'razorpay_signature' => ['required', 'string'],
        ]);

        $valid = $data['razorpay_order_id'] === $booking->razorpay_order_id
            && $this->razorpay->verifyPaymentSignature(
                $data['razorpay_order_id'],
                $data['razorpay_payment_id'],
                $data['razorpay_signature'],
            );

        if (! $valid) {
            $booking->forceFill(['payment_status' => 'failed'])->save();

            return redirect()->route('booking.pay', ['booking' => $booking, 'token' => $booking->payment_token])
                ->with('error', 'Payment could not be verified. Please try again.');
        }

        $this->markPaid($booking, $data['razorpay_payment_id'], $data['razorpay_signature']);

        return redirect()->route('booking.payment.success', [
            'booking' => $booking,
            'token' => $booking->payment_token,
        ]);
    }

    /** Thank-you page shown after a verified payment. */
    public function success(Request $request, Booking $booking)
    {
        $this->authorizeBooking($request, $booking);

        return view('frontend.payment-success', compact('booking'));
    }

    /**
     * Server-to-server confirmation. Idempotent: marks the booking paid even if
     * the browser handler never fired (e.g. the customer closed the tab).
     * CSRF-exempt; authenticated by the webhook signature.
     */
    public function webhook(Request $request)
    {
        $payload = $request->getContent();
        $signature = $request->header('X-Razorpay-Signature', '');

        if (! $this->razorpay->verifyWebhookSignature($payload, $signature)) {
            return response()->json(['status' => 'invalid signature'], 400);
        }

        $event = $request->input('event');
        $entity = $request->input('payload.payment.entity', []);
        $orderId = $entity['order_id'] ?? null;

        if (in_array($event, ['payment.captured', 'order.paid'], true) && $orderId) {
            $booking = Booking::where('razorpay_order_id', $orderId)->first();

            if ($booking && ! $booking->isPaid()) {
                $this->markPaid($booking, $entity['id'] ?? null, null);
            }
        }

        return response()->json(['status' => 'ok']);
    }

    /** Persist a successful payment and confirm the booking. */
    private function markPaid(Booking $booking, ?string $paymentId, ?string $signature): void
    {
        $booking->forceFill([
            'payment_status' => 'paid',
            'payment_method' => 'razorpay',
            'razorpay_payment_id' => $paymentId,
            'razorpay_signature' => $signature ?? $booking->razorpay_signature,
            'amount_paid' => $booking->total,
            'paid_at' => now(),
            // A paid booking is a real, confirmed booking — not a loose inquiry.
            'status' => $booking->status === 'pending' ? 'confirmed' : $booking->status,
        ])->save();
    }

    /**
     * Allow access when the request carries the booking's payment_token, or the
     * signed-in customer owns the booking.
     */
    private function authorizeBooking(Request $request, Booking $booking): void
    {
        $token = $request->input('token');

        if (filled($token) && hash_equals((string) $booking->payment_token, (string) $token)) {
            return;
        }

        abort_unless(
            $request->user() && $booking->user_id === $request->user()->id,
            403,
        );
    }
}
