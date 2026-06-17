@extends('layouts.frontend')

@section('title', $destination->name . ' — ' . setting('site_name', config('app.name')))
@section('meta_description', $destination->meta_description ?: \Illuminate\Support\Str::limit(strip_tags($destination->description), 150))

@php
    $symbol = setting('currency_symbol', '$');
    // Preserve the active sort when building category-chip links.
    $carry = array_filter(['sort' => request('sort')]);
    $durationLabel = $stats['min_days']
        ? ($stats['min_days'] == $stats['max_days']
            ? $stats['min_days'] . ' days'
            : $stats['min_days'] . '–' . $stats['max_days'] . ' days')
        : '—';
@endphp

@section('content')
    <x-page-hero :title="$destination->name" :image="$destination->image_url"
                 :subtitle="$destination->country"
                 :breadcrumbs="['Destinations' => route('destinations.index'), $destination->name => null]" />

    {{-- ===== Stats strip ===== --}}
    <section class="border-b border-slate-100 bg-white">
        <div class="container grid grid-cols-2 gap-y-6 py-6 sm:grid-cols-4 sm:divide-x sm:divide-slate-100">
            <div class="text-center sm:px-4">
                <p class="text-2xl font-bold text-brand-600">{{ $stats['count'] }}</p>
                <p class="mt-0.5 text-xs uppercase tracking-wide text-slate-400">Tour{{ $stats['count'] == 1 ? '' : 's' }}</p>
            </div>
            <div class="text-center sm:px-4">
                <p class="text-2xl font-bold text-brand-600">{{ $stats['from'] ? $symbol . number_format($stats['from'], 0) : '—' }}</p>
                <p class="mt-0.5 text-xs uppercase tracking-wide text-slate-400">Starting From</p>
            </div>
            <div class="text-center sm:px-4">
                <p class="truncate text-2xl font-bold text-slate-900">{{ $destination->country ?: 'Worldwide' }}</p>
                <p class="mt-0.5 text-xs uppercase tracking-wide text-slate-400">Country</p>
            </div>
            <div class="text-center sm:px-4">
                <p class="text-2xl font-bold text-slate-900">{{ $durationLabel }}</p>
                <p class="mt-0.5 text-xs uppercase tracking-wide text-slate-400">Trip Length</p>
            </div>
        </div>
    </section>

    {{-- ===== Description ===== --}}
    @if ($destination->description)
        <section class="pt-12 sm:pt-16">
            <div class="container mx-auto max-w-3xl text-center">
                <h2 class="section-title mb-4">About {{ $destination->name }}</h2>
                <div class="prose-content text-left sm:text-center">{!! nl2br(e($destination->description)) !!}</div>
            </div>
        </section>
    @endif

    {{-- ===== Tours ===== --}}
    <section class="py-12 sm:py-16">
        <div class="container">
            {{-- Toolbar --}}
            <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
                <div>
                    <x-section-heading title="Tours in {{ $destination->name }}" :center="false" />
                    <p class="mt-1 text-sm text-slate-500">
                        {{ $packages->total() }} tour{{ $packages->total() == 1 ? '' : 's' }}
                        @if (request('category')) in <span class="font-medium text-slate-700">{{ request('category') }}</span>@endif
                    </p>
                </div>

                @if ($stats['count'] > 0)
                    <form method="GET" action="{{ route('destinations.show', $destination->slug) }}" class="shrink-0">
                        @if (request('category'))
                            <input type="hidden" name="category" value="{{ request('category') }}">
                        @endif
                        <label class="sr-only" for="sort">Sort tours</label>
                        <select id="sort" name="sort" onchange="this.form.submit()" class="form-select-base !py-2 text-sm">
                            <option value="">Sort: Featured</option>
                            <option value="price_asc" @selected(request('sort') == 'price_asc')>Price: Low to High</option>
                            <option value="price_desc" @selected(request('sort') == 'price_desc')>Price: High to Low</option>
                            <option value="duration" @selected(request('sort') == 'duration')>Duration: Shortest</option>
                            <option value="name" @selected(request('sort') == 'name')>Name: A – Z</option>
                        </select>
                    </form>
                @endif
            </div>

            {{-- Category chips --}}
            @if ($categories->isNotEmpty())
                <div class="mt-6 flex flex-wrap gap-2">
                    <a href="{{ route('destinations.show', array_merge(['destination' => $destination->slug], $carry)) }}"
                       @class([
                           'rounded-full px-4 py-1.5 text-sm font-medium transition',
                           'bg-brand-600 text-white' => !request('category'),
                           'bg-slate-100 text-slate-600 hover:bg-slate-200' => request('category'),
                       ])>All</a>
                    @foreach ($categories as $cat)
                        <a href="{{ route('destinations.show', array_merge(['destination' => $destination->slug, 'category' => $cat], $carry)) }}"
                           @class([
                               'rounded-full px-4 py-1.5 text-sm font-medium transition',
                               'bg-brand-600 text-white' => request('category') === $cat,
                               'bg-slate-100 text-slate-600 hover:bg-slate-200' => request('category') !== $cat,
                           ])>{{ $cat }}</a>
                    @endforeach
                </div>
            @endif

            {{-- Grid / empty states --}}
            @if ($packages->isNotEmpty())
                <div class="mt-8 grid gap-7 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach ($packages as $package)
                        <x-package-card :package="$package" />
                    @endforeach
                </div>
                <div class="mt-10">{{ $packages->links() }}</div>
            @elseif ($stats['count'] > 0)
                {{-- Filter returned nothing, but the destination does have tours --}}
                <div class="mt-8 rounded-2xl border border-dashed border-slate-200 p-10 text-center sm:p-16">
                    <p class="text-lg font-semibold text-slate-700">No tours match this filter</p>
                    <p class="mt-1 text-sm text-slate-400">Try a different category or clear the filter.</p>
                    <a href="{{ route('destinations.show', $destination->slug) }}" class="btn-outline mt-5">Show all tours</a>
                </div>
            @else
                {{-- Destination has no tours at all --}}
                <div class="mt-8 rounded-2xl border border-dashed border-slate-200 p-10 text-center sm:p-16">
                    <p class="text-lg font-semibold text-slate-700">No tours here yet</p>
                    <p class="mt-1 text-sm text-slate-400">We're still curating trips for {{ $destination->name }}.</p>
                    <a href="{{ route('booking.create') }}" class="btn-primary mt-5">Request a custom tour</a>
                </div>
            @endif
        </div>
    </section>

    {{-- ===== Explore other destinations ===== --}}
    @if ($otherDestinations->isNotEmpty())
        <section class="bg-slate-50 py-12 sm:py-16">
            <div class="container">
                <x-section-heading eyebrow="Keep exploring" title="Other Destinations" :center="false" />
                <div class="mt-8 grid gap-7 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach ($otherDestinations as $other)
                        <x-destination-card :destination="$other" />
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- ===== CTA ===== --}}
    <section class="py-12 sm:py-16">
        <div class="container">
            <div class="flex flex-col items-center gap-5 rounded-2xl bg-brand-950 px-6 py-10 text-center text-white sm:px-10 sm:py-12">
                <div>
                    <h2 class="text-2xl font-bold sm:text-3xl">Can't find the perfect trip to {{ $destination->name }}?</h2>
                    <p class="mx-auto mt-2 max-w-xl text-sm text-white/70">Tell us what you have in mind and our travel experts will craft a tailor-made itinerary just for you.</p>
                </div>
                <a href="{{ route('booking.create') }}" class="btn-white">Plan My Trip</a>
            </div>
        </div>
    </section>
@endsection
