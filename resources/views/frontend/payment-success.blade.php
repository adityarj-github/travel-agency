@extends('layouts.frontend')

@section('title', 'Booking Confirmed — ' . setting('site_name', config('app.name')))

@section('content')
    @php $symbol = setting('currency_symbol', '$'); @endphp

    <section class="py-20">
        <div class="container max-w-xl text-center">
            <div class="rounded-2xl bg-white p-10 card-shadow">
                <div class="mx-auto mb-6 flex h-16 w-16 items-center justify-center rounded-full bg-green-100 text-green-600">
                    <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                    </svg>
                </div>

                @if ($booking->isPaid())
                    <h1 class="mb-2 text-3xl font-bold text-slate-900">Payment Successful!</h1>
                    <p class="mb-6 text-slate-500">
                        Thank you, {{ $booking->name }}. Your booking is confirmed and a receipt has been recorded.
                    </p>

                    <dl class="mx-auto max-w-sm space-y-2 rounded-xl bg-slate-50 p-5 text-left text-sm">
                        <div class="flex justify-between"><dt class="text-slate-500">Reference</dt><dd class="font-mono font-medium text-slate-800">{{ $booking->reference }}</dd></div>
                        @if ($booking->package)
                            <div class="flex justify-between"><dt class="text-slate-500">Package</dt><dd class="text-slate-800">{{ $booking->package->title }}</dd></div>
                        @endif
                        <div class="flex justify-between"><dt class="text-slate-500">Amount paid</dt><dd class="font-semibold text-slate-900">{{ $symbol }}{{ number_format($booking->amount_paid ?? $booking->total, 2) }}</dd></div>
                        @if ($booking->razorpay_payment_id)
                            <div class="flex justify-between"><dt class="text-slate-500">Payment ID</dt><dd class="font-mono text-xs text-slate-600">{{ $booking->razorpay_payment_id }}</dd></div>
                        @endif
                    </dl>
                @else
                    <h1 class="mb-2 text-3xl font-bold text-slate-900">Booking Received</h1>
                    <p class="mb-6 text-slate-500">
                        Thank you, {{ $booking->name }}. Your booking reference is
                        <span class="font-mono font-medium text-slate-700">{{ $booking->reference }}</span>.
                    </p>
                @endif

                <div class="mt-8 flex flex-wrap justify-center gap-3">
                    @auth
                        <a href="{{ route('account.bookings') }}" class="btn-primary">View My Bookings</a>
                        <a href="{{ route('account.bookings.voucher', $booking) }}" class="btn-outline">Download Voucher</a>
                    @else
                        <a href="{{ route('packages.index') }}" class="btn-primary">Explore More Tours</a>
                        <a href="{{ route('home') }}" class="btn-outline">Back to Home</a>
                    @endauth
                </div>
            </div>
        </div>
    </section>
@endsection
