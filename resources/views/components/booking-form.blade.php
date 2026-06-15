@props([
    'packages' => null,
    'destinations' => null,
    'selectedPackage' => null,
    'compact' => false,
])

@php
    $symbol = setting('currency_symbol', '$');
    $selectedUnit = $selectedPackage ? (float) $selectedPackage->effective_price : 0;
@endphp

<form method="POST" action="{{ route('booking.store') }}" class="space-y-4"
      x-data="bookingForm({
          symbol: '{{ $symbol }}',
          unit: {{ $selectedUnit }},
          adults: {{ (int) old('adults', 1) }},
          children: {{ (int) old('children', 0) }},
          applyUrl: '{{ route('booking.apply-coupon') }}',
      })">
    @csrf

    <div class="grid gap-4 sm:grid-cols-2">
        <div>
            <label class="form-label">Full Name <span class="text-red-500">*</span></label>
            <input type="text" name="name" value="{{ old('name', auth()->user()->name ?? '') }}" required class="form-input-base" placeholder="John Doe">
        </div>
        <div>
            <label class="form-label">Email <span class="text-red-500">*</span></label>
            <input type="email" name="email" value="{{ old('email', auth()->user()->email ?? '') }}" required class="form-input-base" placeholder="john@example.com">
        </div>
    </div>

    <div class="grid gap-4 sm:grid-cols-2">
        <div>
            <label class="form-label">Phone</label>
            <input type="text" name="phone" value="{{ old('phone', auth()->user()->phone ?? '') }}" class="form-input-base" placeholder="+1 555 000 0000">
        </div>
        <div>
            <label class="form-label">Travel Date</label>
            <input type="date" name="travel_date" value="{{ old('travel_date') }}" min="{{ date('Y-m-d') }}" class="form-input-base">
        </div>
    </div>

    @if ($selectedPackage)
        <input type="hidden" name="package_id" value="{{ $selectedPackage->id }}">
        <div>
            <label class="form-label">Selected Package</label>
            <input type="text" value="{{ $selectedPackage->title }}" disabled class="form-input-base bg-slate-50">
        </div>
    @else
        <div class="grid gap-4 sm:grid-cols-2">
            <div>
                <label class="form-label">Destination</label>
                <select name="destination_id" class="form-input-base">
                    <option value="">Select destination</option>
                    @foreach ($destinations ?? [] as $d)
                        <option value="{{ $d->id }}" @selected(old('destination_id') == $d->id)>{{ $d->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="form-label">Package</label>
                <select name="package_id" class="form-input-base"
                        @change="unit = parseFloat($event.target.selectedOptions[0]?.dataset.price || 0); resetCoupon()">
                    <option value="" data-price="0">Select package</option>
                    @foreach ($packages ?? [] as $p)
                        @php $unit = ($p->discount_price && $p->discount_price > 0) ? $p->discount_price : $p->price; @endphp
                        <option value="{{ $p->id }}" data-price="{{ $unit }}" @selected(old('package_id') == $p->id)>{{ $p->title }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    @endif

    <div class="grid gap-4 sm:grid-cols-2">
        <div>
            <label class="form-label">Adults <span class="text-red-500">*</span></label>
            <input type="number" name="adults" x-model.number="adults" min="1" max="50" required class="form-input-base">
        </div>
        <div>
            <label class="form-label">Children</label>
            <input type="number" name="children" x-model.number="children" min="0" max="50" class="form-input-base">
        </div>
    </div>

    <div>
        <label class="form-label">Message / Special Requests</label>
        <textarea name="message" rows="{{ $compact ? 3 : 4 }}" class="form-input-base" placeholder="Tell us about your dream trip...">{{ old('message') }}</textarea>
    </div>

    {{-- Coupon --}}
    <div x-show="unit > 0" x-cloak>
        <label class="form-label">Coupon Code</label>
        <div class="flex gap-2">
            <input type="text" name="coupon_code" x-model="code" @input="resetCoupon()"
                   class="form-input-base uppercase" placeholder="Enter promo code">
            <button type="button" @click="apply" :disabled="loading || !code"
                    class="btn-outline whitespace-nowrap !px-4 !py-2 text-sm">
                <span x-show="!loading">Apply</span>
                <span x-show="loading" x-cloak>…</span>
            </button>
        </div>
        <p x-show="message" x-cloak class="mt-1 text-xs" :class="applied ? 'text-green-600' : 'text-red-600'" x-text="message"></p>
    </div>

    {{-- Price summary --}}
    <div x-show="unit > 0" x-cloak class="rounded-xl bg-slate-50 p-4 text-sm">
        <div class="flex justify-between py-1 text-slate-600">
            <span>Estimated subtotal (<span x-text="travelers"></span> traveller(s))</span>
            <span x-text="format(subtotal)"></span>
        </div>
        <div x-show="discount > 0" x-cloak class="flex justify-between py-1 text-green-600">
            <span>Discount</span>
            <span x-text="'- ' + format(discount)"></span>
        </div>
        <div class="mt-1 flex justify-between border-t border-slate-200 pt-2 font-bold text-slate-900">
            <span>Estimated total</span>
            <span x-text="format(total)"></span>
        </div>
        <p class="mt-2 text-xs text-slate-400">Final price is confirmed by our team. This is an estimate only.</p>
    </div>

    <button type="submit" class="btn-primary w-full">
        <span x-show="total > 0" x-cloak>Proceed to Secure Payment</span>
        <span x-show="total <= 0">Submit Booking Inquiry</span>
    </button>
    <p class="text-center text-xs text-slate-400">
        <span x-show="total > 0" x-cloak>You'll be taken to our secure payment partner (Razorpay) to confirm your booking.</span>
        <span x-show="total <= 0">Your inquiry is free and our team will respond within 24 hours.</span>
    </p>
</form>
