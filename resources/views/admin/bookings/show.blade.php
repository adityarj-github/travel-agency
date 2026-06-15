@extends('layouts.admin')

@section('title', 'Booking ' . $booking->reference)
@section('page_title', 'Booking ' . $booking->reference)
@section('breadcrumb', 'Bookings / Details')

@section('page_actions')
    <a href="{{ route('admin.bookings.voucher', $booking) }}" class="btn-primary">Download Voucher (PDF)</a>
    <a href="{{ route('admin.bookings.index') }}" class="btn-outline">&larr; Back to Bookings</a>
@endsection

@section('content')
    <div class="grid gap-6 lg:grid-cols-3">
        {{-- Customer & trip details --}}
        <div class="space-y-6 lg:col-span-2">
            <div class="rounded-2xl bg-white p-6 shadow-sm">
                <h3 class="mb-4 font-bold text-slate-900">Customer &amp; Trip Details</h3>
                <dl class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <dt class="text-xs uppercase text-slate-400">Name</dt>
                        <dd class="text-slate-800">{{ $booking->name }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs uppercase text-slate-400">Status</dt>
                        <dd><span class="rounded-full px-2.5 py-1 text-xs font-semibold {{ $booking->status_badge }}">{{ ucfirst($booking->status) }}</span></dd>
                    </div>
                    <div>
                        <dt class="text-xs uppercase text-slate-400">Email</dt>
                        <dd><a href="mailto:{{ $booking->email }}" class="text-brand-600 hover:underline">{{ $booking->email }}</a></dd>
                    </div>
                    <div>
                        <dt class="text-xs uppercase text-slate-400">Phone</dt>
                        <dd><a href="tel:{{ $booking->phone }}" class="text-brand-600 hover:underline">{{ $booking->phone }}</a></dd>
                    </div>
                    <div>
                        <dt class="text-xs uppercase text-slate-400">Travel Date</dt>
                        <dd class="text-slate-800">{{ $booking->travel_date ? $booking->travel_date->format('M d, Y') : '—' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs uppercase text-slate-400">Travellers</dt>
                        <dd class="text-slate-800">{{ $booking->adults }} adults, {{ $booking->children }} children</dd>
                    </div>
                    <div>
                        <dt class="text-xs uppercase text-slate-400">Package</dt>
                        <dd class="text-slate-800">{{ optional($booking->package)->title ?? '—' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs uppercase text-slate-400">Destination</dt>
                        <dd class="text-slate-800">{{ optional($booking->destination)->name ?? '—' }}</dd>
                    </div>
                    <div class="sm:col-span-2">
                        <dt class="text-xs uppercase text-slate-400">Message</dt>
                        <dd class="whitespace-pre-line text-slate-800">{{ $booking->message ?: '—' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs uppercase text-slate-400">Account</dt>
                        <dd class="text-slate-800">
                            @if ($booking->user)
                                {{ $booking->user->name }} <span class="text-xs text-slate-400">(registered)</span>
                            @else
                                <span class="text-slate-400">Guest</span>
                            @endif
                        </dd>
                    </div>
                    <div>
                        <dt class="text-xs uppercase text-slate-400">Submitted</dt>
                        <dd class="text-slate-800">{{ $booking->created_at->format('M d, Y \a\t g:i A') }}</dd>
                    </div>
                </dl>
            </div>

            {{-- Pricing --}}
            @if ($booking->subtotal !== null && (float) $booking->subtotal > 0)
                <div class="rounded-2xl bg-white p-6 shadow-sm">
                    <h3 class="mb-4 font-bold text-slate-900">Pricing</h3>
                    @php $symbol = setting('currency_symbol', '$'); @endphp
                    <dl class="space-y-2 text-sm">
                        <div class="flex justify-between"><dt class="text-slate-500">Subtotal</dt><dd class="text-slate-800">{{ $symbol }}{{ number_format($booking->subtotal, 2) }}</dd></div>
                        @if ((float) $booking->discount_amount > 0)
                            <div class="flex justify-between text-green-600">
                                <dt>Discount @if($booking->coupon_code)<span class="font-mono text-xs">({{ $booking->coupon_code }})</span>@endif</dt>
                                <dd>- {{ $symbol }}{{ number_format($booking->discount_amount, 2) }}</dd>
                            </div>
                        @endif
                        <div class="flex justify-between border-t border-slate-100 pt-2 text-base font-bold text-slate-900">
                            <dt>Total</dt><dd>{{ $symbol }}{{ number_format($booking->total, 2) }}</dd>
                        </div>
                    </dl>
                </div>

                {{-- Payment --}}
                <div class="rounded-2xl bg-white p-6 shadow-sm">
                    <h3 class="mb-4 font-bold text-slate-900">Payment</h3>
                    <dl class="grid gap-4 sm:grid-cols-2">
                        <div>
                            <dt class="text-xs uppercase text-slate-400">Status</dt>
                            <dd><span class="rounded-full px-2.5 py-1 text-xs font-semibold {{ $booking->payment_badge }}">{{ ucfirst($booking->payment_status) }}</span></dd>
                        </div>
                        <div>
                            <dt class="text-xs uppercase text-slate-400">Method</dt>
                            <dd class="text-slate-800">{{ $booking->payment_method ? ucfirst($booking->payment_method) : '—' }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs uppercase text-slate-400">Amount Paid</dt>
                            <dd class="text-slate-800">{{ $booking->amount_paid !== null ? $symbol . number_format($booking->amount_paid, 2) : '—' }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs uppercase text-slate-400">Paid At</dt>
                            <dd class="text-slate-800">{{ $booking->paid_at ? $booking->paid_at->format('M d, Y \a\t g:i A') : '—' }}</dd>
                        </div>
                        @if ($booking->razorpay_payment_id)
                            <div class="sm:col-span-2">
                                <dt class="text-xs uppercase text-slate-400">Razorpay Payment ID</dt>
                                <dd class="font-mono text-xs text-slate-600">{{ $booking->razorpay_payment_id }}</dd>
                            </div>
                        @endif
                        @if ($booking->razorpay_order_id)
                            <div class="sm:col-span-2">
                                <dt class="text-xs uppercase text-slate-400">Razorpay Order ID</dt>
                                <dd class="font-mono text-xs text-slate-600">{{ $booking->razorpay_order_id }}</dd>
                            </div>
                        @endif
                    </dl>
                </div>
            @endif
        </div>

        {{-- Status management --}}
        <div class="space-y-6">
            <div class="rounded-2xl bg-white p-6 shadow-sm">
                <h3 class="mb-4 font-bold text-slate-900">Manage Booking</h3>
                <form method="POST" action="{{ route('admin.bookings.update', $booking) }}">
                    @csrf
                    @method('PUT')
                    <div class="space-y-4">
                        <div>
                            <label class="form-label">Status</label>
                            <select name="status" class="form-input-base">
                                @php $statusOptions = $statuses ?? ['pending', 'confirmed', 'cancelled', 'completed']; @endphp
                                @foreach ($statusOptions as $status)
                                    <option value="{{ $status }}" @selected(old('status', $booking->status) == $status)>{{ ucfirst($status) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="form-label">Admin Note</label>
                            <textarea name="admin_note" rows="4" class="form-input-base" placeholder="Internal note...">{{ old('admin_note', $booking->admin_note) }}</textarea>
                        </div>
                        <button type="submit" class="btn-primary w-full">Update Booking</button>
                    </div>
                </form>
            </div>

            <div class="rounded-2xl bg-white p-6 shadow-sm">
                <h3 class="mb-4 font-bold text-slate-900">Danger Zone</h3>
                <x-admin.delete-form :action="route('admin.bookings.destroy', $booking)" label="Delete Booking" message="Delete this booking? This cannot be undone." class="text-red-600 hover:text-red-800 text-sm font-medium" />
            </div>
        </div>
    </div>
@endsection
