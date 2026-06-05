@extends('layouts.admin')

@section('title', 'Packages')
@section('page_title', 'Packages')
@section('breadcrumb', 'Manage your travel packages')

@section('page_actions')
    <a href="{{ route('admin.packages.create') }}" class="btn-primary">+ Add Package</a>
@endsection

@section('content')
    {{-- Filters --}}
    <form method="GET" class="mb-5 grid gap-3 rounded-2xl bg-white p-4 shadow-sm sm:grid-cols-4">
        <input type="text" name="search" value="{{ request('search') }}" class="form-input-base" placeholder="Search by title...">
        <select name="destination" class="form-input-base">
            <option value="">All destinations</option>
            @foreach ($destinations as $d)
                <option value="{{ $d->id }}" @selected(request('destination') == $d->id)>{{ $d->name }}</option>
            @endforeach
        </select>
        <select name="status" class="form-input-base">
            <option value="">All statuses</option>
            <option value="active" @selected(request('status') == 'active')>Active</option>
            <option value="inactive" @selected(request('status') == 'inactive')>Inactive</option>
        </select>
        <div class="flex gap-2">
            <button class="btn-primary flex-1 !py-2">Filter</button>
            <a href="{{ route('admin.packages.index') }}" class="btn-outline !py-2">Reset</a>
        </div>
    </form>

    <div class="overflow-hidden rounded-2xl bg-white shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-slate-50 text-xs uppercase text-slate-400">
                    <tr>
                        <th class="px-5 py-3">Package</th>
                        <th class="px-5 py-3">Destination</th>
                        <th class="px-5 py-3">Price</th>
                        <th class="px-5 py-3">Duration</th>
                        <th class="px-5 py-3">Status</th>
                        <th class="px-5 py-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($packages as $package)
                        <tr class="hover:bg-slate-50">
                            <td class="px-5 py-3">
                                <div class="flex items-center gap-3">
                                    <img src="{{ $package->main_image_url }}" loading="lazy" decoding="async" width="64" height="48" class="h-12 w-16 rounded-lg object-cover" alt="">
                                    <div>
                                        <p class="font-medium text-slate-800">{{ $package->title }}</p>
                                        <p class="text-xs text-slate-400">
                                            {{ $package->category }}
                                            @if ($package->is_featured)<span class="ml-1 rounded bg-amber-100 px-1.5 py-0.5 text-amber-700">Featured</span>@endif
                                        </p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-5 py-3 text-slate-600">{{ optional($package->destination)->name ?? '—' }}</td>
                            <td class="px-5 py-3 text-slate-600">{{ setting('currency_symbol', '$') }}{{ number_format($package->effective_price, 0) }}</td>
                            <td class="px-5 py-3 text-slate-600">{{ $package->duration_label }}</td>
                            <td class="px-5 py-3">
                                <form method="POST" action="{{ route('admin.packages.toggle', $package) }}">
                                    @csrf @method('PATCH')
                                    <button class="rounded-full px-2.5 py-1 text-xs font-semibold {{ $package->is_active ? 'bg-green-100 text-green-700' : 'bg-slate-100 text-slate-500' }}">
                                        {{ $package->is_active ? 'Active' : 'Inactive' }}
                                    </button>
                                </form>
                            </td>
                            <td class="px-5 py-3">
                                <div class="flex items-center justify-end gap-3">
                                    <a href="{{ route('packages.show', $package->slug) }}" target="_blank" class="text-slate-400 hover:text-brand-600" title="View">↗</a>
                                    <a href="{{ route('admin.packages.edit', $package) }}" class="text-brand-600 hover:underline">Edit</a>
                                    <x-admin.delete-form :action="route('admin.packages.destroy', $package)" message="Delete this package?" />
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="px-5 py-12 text-center text-slate-400">No packages found. <a href="{{ route('admin.packages.create') }}" class="text-brand-600 underline">Add one</a>.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-5">{{ $packages->links() }}</div>
@endsection
