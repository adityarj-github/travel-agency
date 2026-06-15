<?php

namespace App\Services;

use App\Models\Booking;
use Illuminate\Support\Facades\Http;
use RuntimeException;

/**
 * Thin Razorpay client built on Laravel's HTTP client. Avoids pulling in the
 * full SDK — order creation is a single authenticated POST and every signature
 * check is a plain HMAC-SHA256, so there is nothing the SDK buys us here.
 *
 * @see https://razorpay.com/docs/payments/server-integration/php/
 */
class RazorpayService
{
    private const API_BASE = 'https://api.razorpay.com/v1';

    public function __construct(
        private readonly ?string $key = null,
        private readonly ?string $secret = null,
        private readonly string $currency = 'INR',
    ) {
    }

    public static function fromConfig(): self
    {
        return new self(
            config('services.razorpay.key'),
            config('services.razorpay.secret'),
            config('services.razorpay.currency', 'INR'),
        );
    }

    /** Whether usable credentials are configured. */
    public function enabled(): bool
    {
        return filled($this->key) && filled($this->secret);
    }

    public function key(): ?string
    {
        return $this->key;
    }

    public function currency(): string
    {
        return strtoupper($this->currency);
    }

    /**
     * Create (or reuse) a Razorpay order for a booking and persist its id.
     * Amount is sent in the smallest currency unit (paise for INR).
     */
    public function createOrderFor(Booking $booking): string
    {
        $this->ensureEnabled();

        $amount = (int) round((float) $booking->total * 100);

        if ($amount <= 0) {
            throw new RuntimeException('Cannot create a payment order for a zero-amount booking.');
        }

        $response = Http::withBasicAuth($this->key, $this->secret)
            ->acceptJson()
            ->post(self::API_BASE . '/orders', [
                'amount' => $amount,
                'currency' => $this->currency(),
                'receipt' => $booking->reference,
                'notes' => [
                    'booking_reference' => $booking->reference,
                    'booking_id' => (string) $booking->id,
                ],
            ]);

        if ($response->failed()) {
            $message = $response->json('error.description') ?? 'Unknown error';
            throw new RuntimeException("Razorpay order creation failed: {$message}");
        }

        $orderId = $response->json('id');

        $booking->forceFill([
            'razorpay_order_id' => $orderId,
            'payment_method' => 'razorpay',
        ])->save();

        return $orderId;
    }

    /**
     * Verify the checkout handler signature: HMAC-SHA256(order_id|payment_id, secret).
     */
    public function verifyPaymentSignature(string $orderId, string $paymentId, string $signature): bool
    {
        $expected = hash_hmac('sha256', $orderId . '|' . $paymentId, (string) $this->secret);

        return hash_equals($expected, $signature);
    }

    /**
     * Verify a webhook payload against the configured webhook secret.
     */
    public function verifyWebhookSignature(string $payload, string $signature): bool
    {
        $secret = config('services.razorpay.webhook_secret');

        if (blank($secret)) {
            return false;
        }

        $expected = hash_hmac('sha256', $payload, $secret);

        return hash_equals($expected, $signature);
    }

    private function ensureEnabled(): void
    {
        if (! $this->enabled()) {
            throw new RuntimeException('Razorpay credentials are not configured.');
        }
    }
}
