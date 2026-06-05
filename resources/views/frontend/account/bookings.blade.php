@extends('layouts.frontend')

@section('title', 'My Bookings — ' . setting('site_name', config('app.name')))

@section('content')
    <x-page-hero title="My Bookings" :breadcrumbs="['Account' => route('account.dashboard'), 'Bookings' => null]" />

    <section class="py-12">
        <div class="container grid gap-8 lg:grid-cols-4">
            <div class="lg:col-span-1">@include('frontend.account._nav')</div>

            <div class="lg:col-span-3">
                <div class="overflow-hidden rounded-2xl border border-slate-100 bg-white card-shadow">
                    @if ($bookings->isEmpty())
                        <p class="p-10 text-center text-sm text-slate-400">
                            You have no bookings yet. <a href="{{ route('packages.index') }}" class="text-brand-600 hover:underline">Explore tours</a>.
                        </p>
                    @else
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm">
                                <thead class="bg-slate-50 text-left text-xs uppercase tracking-wide text-slate-400">
                                    <tr>
                                        <th class="px-5 py-3">Reference</th>
                                        <th class="px-5 py-3">Package</th>
                                        <th class="px-5 py-3">Travel Date</th>
                                        <th class="px-5 py-3">Total</th>
                                        <th class="px-5 py-3">Status</th>
                                        <th class="px-5 py-3"></th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100">
                                    @foreach ($bookings as $booking)
                                        <tr class="hover:bg-slate-50">
                                            <td class="px-5 py-4 font-mono text-xs text-slate-500">{{ $booking->reference }}</td>
                                            <td class="px-5 py-4">
                                                <p class="font-medium text-slate-800">{{ optional($booking->package)->title ?? 'Custom inquiry' }}</p>
                                                <p class="text-xs text-slate-400">{{ optional($booking->destination)->name }}</p>
                                            </td>
                                            <td class="px-5 py-4 text-slate-600">{{ $booking->travel_date ? $booking->travel_date->format('M d, Y') : '—' }}</td>
                                            <td class="px-5 py-4 text-slate-700">
                                                @if ((float) $booking->total > 0){{ setting('currency_symbol', '$') }}{{ number_format($booking->total, 0) }}@else—@endif
                                            </td>
                                            <td class="px-5 py-4"><span class="rounded-full px-2.5 py-1 text-xs font-semibold {{ $booking->status_badge }}">{{ ucfirst($booking->status) }}</span></td>
                                            <td class="px-5 py-4 text-right">
                                                <a href="{{ route('account.bookings.voucher', $booking) }}" class="font-medium text-brand-600 hover:underline">Download Voucher</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>

                <div class="mt-6">{{ $bookings->links() }}</div>
            </div>
        </div>
    </section>
@endsection
