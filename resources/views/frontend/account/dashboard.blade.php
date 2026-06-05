@extends('layouts.frontend')

@section('title', 'My Account — ' . setting('site_name', config('app.name')))

@section('content')
    <x-page-hero title="My Account" :subtitle="'Welcome back, ' . $user->name" :breadcrumbs="['Account' => null]" />

    <section class="py-12">
        <div class="container grid gap-8 lg:grid-cols-4">
            <div class="lg:col-span-1">@include('frontend.account._nav')</div>

            <div class="space-y-8 lg:col-span-3">
                {{-- Stat cards --}}
                <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                    @php
                        $cards = [
                            ['label' => 'Total Bookings', 'value' => $stats['bookings'], 'color' => 'text-brand-600'],
                            ['label' => 'Pending', 'value' => $stats['pending'], 'color' => 'text-yellow-600'],
                            ['label' => 'Confirmed', 'value' => $stats['confirmed'], 'color' => 'text-green-600'],
                            ['label' => 'Wishlist', 'value' => $stats['wishlist'], 'color' => 'text-pink-600'],
                        ];
                    @endphp
                    @foreach ($cards as $card)
                        <div class="rounded-2xl border border-slate-100 bg-white p-5 card-shadow">
                            <p class="text-sm text-slate-400">{{ $card['label'] }}</p>
                            <p class="mt-1 text-3xl font-bold {{ $card['color'] }}">{{ $card['value'] }}</p>
                        </div>
                    @endforeach
                </div>

                {{-- Recent bookings --}}
                <div class="rounded-2xl border border-slate-100 bg-white p-6 card-shadow">
                    <div class="mb-4 flex items-center justify-between">
                        <h3 class="font-bold text-slate-900">Recent Bookings</h3>
                        <a href="{{ route('account.bookings') }}" class="text-sm font-semibold text-brand-600 hover:underline">View all</a>
                    </div>

                    @forelse ($bookings as $booking)
                        <div class="flex flex-wrap items-center justify-between gap-3 border-b border-slate-100 py-3 last:border-0">
                            <div>
                                <p class="font-medium text-slate-800">{{ optional($booking->package)->title ?? 'Custom inquiry' }}</p>
                                <p class="text-xs text-slate-400">{{ $booking->reference }} · {{ $booking->created_at->format('M d, Y') }}</p>
                            </div>
                            <div class="flex items-center gap-3">
                                <span class="rounded-full px-2.5 py-1 text-xs font-semibold {{ $booking->status_badge }}">{{ ucfirst($booking->status) }}</span>
                                <a href="{{ route('account.bookings.voucher', $booking) }}" class="text-sm font-medium text-brand-600 hover:underline">Voucher</a>
                            </div>
                        </div>
                    @empty
                        <p class="py-6 text-center text-sm text-slate-400">No bookings yet. <a href="{{ route('packages.index') }}" class="text-brand-600 hover:underline">Browse packages</a>.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </section>
@endsection
