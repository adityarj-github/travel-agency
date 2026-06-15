@extends('layouts.frontend')

@section('title', 'Complete Payment — ' . setting('site_name', config('app.name')))

@section('content')
    @php $symbol = setting('currency_symbol', '$'); @endphp

    <x-page-hero title="Complete Your Payment"
                 subtitle="You're one step away from confirming your trip."
                 :breadcrumbs="['Booking' => route('booking.create'), 'Payment' => null]" />

    <section class="py-16">
        <div class="container max-w-lg">
            <div class="rounded-2xl bg-white p-8 card-shadow">
                <div class="mb-6 flex items-center justify-between">
                    <h2 class="text-2xl font-bold text-slate-900">Order Summary</h2>
                    <span class="font-mono text-xs text-slate-400">{{ $booking->reference }}</span>
                </div>

                <dl class="space-y-3 text-sm">
                    @if ($booking->package)
                        <div class="flex justify-between">
                            <dt class="text-slate-500">Package</dt>
                            <dd class="font-medium text-slate-800">{{ $booking->package->title }}</dd>
                        </div>
                    @endif
                    <div class="flex justify-between">
                        <dt class="text-slate-500">Travellers</dt>
                        <dd class="text-slate-800">{{ $booking->adults }} adult(s){{ $booking->children ? ', ' . $booking->children . ' child(ren)' : '' }}</dd>
                    </div>
                    @if ($booking->travel_date)
                        <div class="flex justify-between">
                            <dt class="text-slate-500">Travel date</dt>
                            <dd class="text-slate-800">{{ $booking->travel_date->format('M d, Y') }}</dd>
                        </div>
                    @endif
                    <div class="flex justify-between">
                        <dt class="text-slate-500">Subtotal</dt>
                        <dd class="text-slate-800">{{ $symbol }}{{ number_format($booking->subtotal, 2) }}</dd>
                    </div>
                    @if ((float) $booking->discount_amount > 0)
                        <div class="flex justify-between text-green-600">
                            <dt>Discount @if($booking->coupon_code)<span class="font-mono text-xs">({{ $booking->coupon_code }})</span>@endif</dt>
                            <dd>- {{ $symbol }}{{ number_format($booking->discount_amount, 2) }}</dd>
                        </div>
                    @endif
                    <div class="flex justify-between border-t border-slate-100 pt-3 text-lg font-bold text-slate-900">
                        <dt>Amount payable</dt>
                        <dd>{{ $symbol }}{{ number_format($booking->total, 2) }}</dd>
                    </div>
                </dl>

                <button type="button" id="rzp-pay-btn" class="btn-primary mt-8 w-full">
                    Pay {{ $symbol }}{{ number_format($booking->total, 2) }} securely
                </button>

                <p class="mt-3 text-center text-xs text-slate-400">
                    Payments are processed securely by Razorpay. You will be charged
                    {{ $currency }} {{ number_format($amountMinor / 100, 2) }}.
                </p>

                <a href="{{ route('booking.create') }}" class="mt-4 block text-center text-xs text-slate-400 hover:text-brand-600">
                    Cancel and return to booking
                </a>
            </div>
        </div>
    </section>

    {{-- Hidden form that relays Razorpay's verified response back to the server. --}}
    <form id="rzp-result-form" method="POST" action="{{ route('booking.payment.callback', $booking) }}" class="hidden">
        @csrf
        <input type="hidden" name="token" value="{{ $booking->payment_token }}">
        <input type="hidden" name="razorpay_payment_id" id="razorpay_payment_id">
        <input type="hidden" name="razorpay_order_id" id="razorpay_order_id">
        <input type="hidden" name="razorpay_signature" id="razorpay_signature">
    </form>

    {{-- Script lives in the body so the button/form elements exist when it runs. --}}
    <script>
        (function () {
            var options = {
                key: @json($razorpayKey),
                amount: @json($amountMinor),
                currency: @json($currency),
                name: @json(setting('site_name', config('app.name'))),
                description: @json('Booking ' . $booking->reference),
                order_id: @json($orderId),
                prefill: {
                    name: @json($booking->name),
                    email: @json($booking->email),
                    contact: @json($booking->phone),
                },
                theme: { color: '#0d8e85' },
                handler: function (response) {
                    document.getElementById('razorpay_payment_id').value = response.razorpay_payment_id;
                    document.getElementById('razorpay_order_id').value = response.razorpay_order_id;
                    document.getElementById('razorpay_signature').value = response.razorpay_signature;
                    document.getElementById('rzp-result-form').submit();
                },
            };

            var rzp = new Razorpay(options);

            rzp.on('payment.failed', function () {
                alert('Payment failed or was cancelled. You can try again.');
            });

            document.getElementById('rzp-pay-btn').addEventListener('click', function () {
                rzp.open();
            });
        })();
    </script>
@endsection

@push('head')
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
@endpush
