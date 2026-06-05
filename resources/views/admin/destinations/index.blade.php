@extends('layouts.admin')

@section('title', 'Destinations')
@section('page_title', 'Destinations')
@section('breadcrumb', 'Manage your travel destinations')

@section('page_actions')
    <a href="{{ route('admin.destinations.create') }}" class="btn-primary">+ Add Destination</a>
@endsection

@section('content')
    {{-- Filters --}}
    <form method="GET" class="mb-5 grid gap-3 rounded-2xl bg-white p-4 shadow-sm sm:grid-cols-4">
        <input type="text" name="search" value="{{ request('search') }}" class="form-input-base sm:col-span-3" placeholder="Search by name...">
        <div class="flex gap-2">
            <button class="btn-primary flex-1 !py-2">Filter</button>
            <a href="{{ route('admin.destinations.index') }}" class="btn-outline !py-2">Reset</a>
        </div>
    </form>

    <div class="overflow-hidden rounded-2xl bg-white shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-slate-50 text-xs uppercase text-slate-400">
                    <tr>
                        <th class="px-5 py-3">Destination</th>
                        <th class="px-5 py-3">Country</th>
                        <th class="px-5 py-3">Packages</th>
                        <th class="px-5 py-3">Status</th>
                        <th class="px-5 py-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($destinations as $destination)
                        <tr class="hover:bg-slate-50">
                            <td class="px-5 py-3">
                                <div class="flex items-center gap-3">
                                    <img src="{{ $destination->image_url }}" loading="lazy" decoding="async" width="64" height="48" class="h-12 w-16 rounded-lg object-cover" alt="">
                                    <div>
                                        <p class="font-medium text-slate-800">{{ $destination->name }}</p>
                                        @if ($destination->is_featured)
                                            <p class="text-xs text-slate-400"><span class="rounded bg-amber-100 px-1.5 py-0.5 text-amber-700">Featured</span></p>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-5 py-3 text-slate-600">{{ $destination->country ?: '—' }}</td>
                            <td class="px-5 py-3 text-slate-600">{{ $destination->packages_count }}</td>
                            <td class="px-5 py-3">
                                <form method="POST" action="{{ route('admin.destinations.toggle', $destination) }}">
                                    @csrf @method('PATCH')
                                    <button class="rounded-full px-2.5 py-1 text-xs font-semibold {{ $destination->is_active ? 'bg-green-100 text-green-700' : 'bg-slate-100 text-slate-500' }}">
                                        {{ $destination->is_active ? 'Active' : 'Inactive' }}
                                    </button>
                                </form>
                            </td>
                            <td class="px-5 py-3">
                                <div class="flex items-center justify-end gap-3">
                                    <a href="{{ route('admin.destinations.edit', $destination) }}" class="text-brand-600 hover:underline">Edit</a>
                                    <x-admin.delete-form :action="route('admin.destinations.destroy', $destination)" message="Delete this destination?" />
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="px-5 py-12 text-center text-slate-400">No destinations found. <a href="{{ route('admin.destinations.create') }}" class="text-brand-600 underline">Add one</a>.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-5">{{ $destinations->links() }}</div>
@endsection
