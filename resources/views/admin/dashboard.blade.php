@extends('layouts.admin')

@section('title', 'Dashboard')
@section('page_title', 'Dashboard')
@section('breadcrumb', 'Overview of your travel agency')

@section('content')
    {{-- Stat cards --}}
    <div class="grid gap-5 sm:grid-cols-2 xl:grid-cols-4">
        @php
            $cards = [
                ['label' => 'Total Packages', 'value' => $stats['packages'], 'color' => 'bg-brand-500', 'route' => route('admin.packages.index'), 'icon' => 'M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2'],
                ['label' => 'Total Bookings', 'value' => $stats['bookings'], 'color' => 'bg-indigo-500', 'route' => route('admin.bookings.index'), 'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2'],
                ['label' => 'Total Blogs', 'value' => $stats['blogs'], 'color' => 'bg-amber-500', 'route' => route('admin.blogs.index'), 'icon' => 'M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1'],
                ['label' => 'Destinations', 'value' => $stats['destinations'], 'color' => 'bg-emerald-500', 'route' => route('admin.destinations.index'), 'icon' => 'M17.657 16.657L13.414 20.9a2 2 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z'],
            ];
        @endphp
        @foreach ($cards as $c)
            <a href="{{ $c['route'] }}" class="flex items-center gap-4 rounded-2xl bg-white p-5 shadow-sm transition hover:shadow-md">
                <div class="flex h-12 w-12 items-center justify-center rounded-xl {{ $c['color'] }} text-white">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $c['icon'] }}"/></svg>
                </div>
                <div>
                    <p class="text-2xl font-bold text-slate-900">{{ $c['value'] }}</p>
                    <p class="text-sm text-slate-400">{{ $c['label'] }}</p>
                </div>
            </a>
        @endforeach
    </div>

    {{-- Booking status cards --}}
    <div class="mt-6 grid gap-5 sm:grid-cols-2 xl:grid-cols-4">
        @php
            $bookingCards = [
                ['label' => 'Pending', 'value' => $stats['pending'], 'class' => 'border-yellow-200 text-yellow-700', 'dot' => 'bg-yellow-400'],
                ['label' => 'Confirmed', 'value' => $stats['confirmed'], 'class' => 'border-green-200 text-green-700', 'dot' => 'bg-green-500'],
                ['label' => 'Completed', 'value' => $stats['completed'], 'class' => 'border-blue-200 text-blue-700', 'dot' => 'bg-blue-500'],
                ['label' => 'Cancelled', 'value' => $stats['cancelled'], 'class' => 'border-red-200 text-red-700', 'dot' => 'bg-red-500'],
            ];
        @endphp
        @foreach ($bookingCards as $c)
            <div class="rounded-2xl border bg-white p-5 {{ $c['class'] }}">
                <div class="flex items-center gap-2">
                    <span class="h-2.5 w-2.5 rounded-full {{ $c['dot'] }}"></span>
                    <p class="text-sm font-medium">{{ $c['label'] }} Bookings</p>
                </div>
                <p class="mt-2 text-3xl font-bold">{{ $c['value'] }}</p>
            </div>
        @endforeach
    </div>

    <div class="mt-6 grid gap-6 lg:grid-cols-3">
        {{-- Recent bookings --}}
        <div class="lg:col-span-2 rounded-2xl bg-white shadow-sm">
            <div class="flex items-center justify-between border-b border-slate-100 p-5">
                <h2 class="font-bold text-slate-900">Recent Booking Inquiries</h2>
                <a href="{{ route('admin.bookings.index') }}" class="text-sm font-medium text-brand-600 hover:underline">View all</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="bg-slate-50 text-xs uppercase text-slate-400">
                        <tr>
                            <th class="px-5 py-3">Ref</th>
                            <th class="px-5 py-3">Customer</th>
                            <th class="px-5 py-3">Package</th>
                            <th class="px-5 py-3">Status</th>
                            <th class="px-5 py-3"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($recentBookings as $booking)
                            <tr class="hover:bg-slate-50">
                                <td class="px-5 py-3 font-mono text-xs text-slate-500">{{ $booking->reference }}</td>
                                <td class="px-5 py-3">
                                    <p class="font-medium text-slate-800">{{ $booking->name }}</p>
                                    <p class="text-xs text-slate-400">{{ $booking->email }}</p>
                                </td>
                                <td class="px-5 py-3 text-slate-600">{{ optional($booking->package)->title ?? '—' }}</td>
                                <td class="px-5 py-3"><span class="rounded-full px-2.5 py-1 text-xs font-semibold {{ $booking->status_badge }}">{{ ucfirst($booking->status) }}</span></td>
                                <td class="px-5 py-3 text-right"><a href="{{ route('admin.bookings.show', $booking) }}" class="text-brand-600 hover:underline">View</a></td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="px-5 py-8 text-center text-slate-400">No bookings yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Side column --}}
        <div class="space-y-6">
            {{-- Quick actions --}}
            <div class="rounded-2xl bg-white p-5 shadow-sm">
                <h2 class="mb-4 font-bold text-slate-900">Quick Actions</h2>
                <div class="grid grid-cols-2 gap-3">
                    <a href="{{ route('admin.packages.create') }}" class="rounded-lg bg-brand-50 p-3 text-center text-sm font-medium text-brand-700 hover:bg-brand-100">+ Package</a>
                    <a href="{{ route('admin.blogs.create') }}" class="rounded-lg bg-amber-50 p-3 text-center text-sm font-medium text-amber-700 hover:bg-amber-100">+ Blog</a>
                    <a href="{{ route('admin.destinations.create') }}" class="rounded-lg bg-emerald-50 p-3 text-center text-sm font-medium text-emerald-700 hover:bg-emerald-100">+ Destination</a>
                    <a href="{{ route('admin.sliders.create') }}" class="rounded-lg bg-indigo-50 p-3 text-center text-sm font-medium text-indigo-700 hover:bg-indigo-100">+ Slider</a>
                </div>
            </div>

            {{-- Status chart --}}
            <div class="rounded-2xl bg-white p-5 shadow-sm">
                <h2 class="mb-4 font-bold text-slate-900">Bookings Breakdown</h2>
                @php $max = max(array_values($statusChart) ?: [1]); $max = $max ?: 1; @endphp
                <div class="space-y-3">
                    @foreach ($statusChart as $label => $value)
                        <div>
                            <div class="mb-1 flex justify-between text-xs text-slate-500"><span>{{ $label }}</span><span>{{ $value }}</span></div>
                            <div class="h-2 w-full rounded-full bg-slate-100">
                                <div class="h-2 rounded-full bg-brand-500" style="width: {{ ($value / $max) * 100 }}%"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Recent inquiries --}}
            <div class="rounded-2xl bg-white p-5 shadow-sm">
                <div class="mb-4 flex items-center justify-between">
                    <h2 class="font-bold text-slate-900">Recent Inquiries</h2>
                    <a href="{{ route('admin.inquiries.index') }}" class="text-sm font-medium text-brand-600 hover:underline">All</a>
                </div>
                <ul class="space-y-3">
                    @forelse ($recentInquiries as $inq)
                        <li class="flex items-start justify-between gap-2">
                            <div>
                                <a href="{{ route('admin.inquiries.show', $inq) }}" class="text-sm font-medium text-slate-800 hover:text-brand-600">{{ $inq->name }}</a>
                                <p class="line-clamp-1 text-xs text-slate-400">{{ $inq->subject ?: $inq->message }}</p>
                            </div>
                            @unless ($inq->is_read)<span class="mt-1 h-2 w-2 shrink-0 rounded-full bg-red-500"></span>@endunless
                        </li>
                    @empty
                        <li class="text-sm text-slate-400">No inquiries yet.</li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>
@endsection
