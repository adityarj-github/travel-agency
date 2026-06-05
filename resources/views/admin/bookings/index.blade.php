@extends('layouts.admin')

@section('title', 'Bookings')
@section('page_title', 'Bookings')
@section('breadcrumb', 'Manage customer bookings')

@section('content')
    {{-- Filters --}}
    <form method="GET" class="mb-5 grid gap-3 rounded-2xl bg-white p-4 shadow-sm sm:grid-cols-5">
        <input type="text" name="search" value="{{ request('search') }}" class="form-input-base" placeholder="Search reference, name, email...">
        <select name="status" class="form-input-base">
            <option value="">All statuses</option>
            @foreach ($statuses as $status)
                <option value="{{ $status }}" @selected(request('status') == $status)>{{ ucfirst($status) }}</option>
            @endforeach
        </select>
        <select name="package" class="form-input-base">
            <option value="">All packages</option>
            @foreach ($packages as $package)
                <option value="{{ $package->id }}" @selected(request('package') == $package->id)>{{ $package->title }}</option>
            @endforeach
        </select>
        <input type="date" name="date" value="{{ request('date') }}" class="form-input-base">
        <div class="flex gap-2">
            <button class="btn-primary flex-1 !py-2">Filter</button>
            <a href="{{ route('admin.bookings.index') }}" class="btn-outline !py-2">Reset</a>
        </div>
    </form>

    <div class="overflow-hidden rounded-2xl bg-white shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-slate-50 text-xs uppercase text-slate-400">
                    <tr>
                        <th class="px-5 py-3">Reference</th>
                        <th class="px-5 py-3">Customer</th>
                        <th class="px-5 py-3">Package</th>
                        <th class="px-5 py-3">Travel Date</th>
                        <th class="px-5 py-3">Travellers</th>
                        <th class="px-5 py-3">Status</th>
                        <th class="px-5 py-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($bookings as $booking)
                        <tr class="hover:bg-slate-50">
                            <td class="px-5 py-3 font-mono text-xs text-slate-500">{{ $booking->reference }}</td>
                            <td class="px-5 py-3">
                                <p class="font-medium text-slate-800">{{ $booking->name }}</p>
                                <p class="text-xs text-slate-400">{{ $booking->email }}</p>
                                <p class="text-xs text-slate-400">{{ $booking->phone }}</p>
                            </td>
                            <td class="px-5 py-3 text-slate-600">{{ optional($booking->package)->title ?? '—' }}</td>
                            <td class="px-5 py-3 text-slate-600">{{ $booking->travel_date ? $booking->travel_date->format('M d, Y') : '—' }}</td>
                            <td class="px-5 py-3 text-slate-600">{{ $booking->adults }} adults, {{ $booking->children }} children</td>
                            <td class="px-5 py-3">
                                <span class="rounded-full px-2.5 py-1 text-xs font-semibold {{ $booking->status_badge }}">{{ ucfirst($booking->status) }}</span>
                            </td>
                            <td class="px-5 py-3">
                                <div class="flex items-center justify-end gap-3">
                                    <a href="{{ route('admin.bookings.show', $booking) }}" class="text-brand-600 hover:underline">View</a>
                                    <x-admin.delete-form :action="route('admin.bookings.destroy', $booking)" message="Delete this booking?" />
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="px-5 py-12 text-center text-slate-400">No bookings found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-5">{{ $bookings->links() }}</div>
@endsection
