@extends('layouts.frontend')

@section('title', ($package->meta_title ?: $package->title) . ' — ' . setting('site_name', config('app.name')))
@section('meta_description', $package->meta_description ?: \Illuminate\Support\Str::limit(strip_tags($package->short_description), 150))
@section('og_image', $package->main_image_url)
@section('og_type', 'product')

@push('schema')
@php
    $productSchema = [
        '@context' => 'https://schema.org',
        '@type' => 'Product',
        'name' => $package->title,
        'image' => [$package->main_image_url],
        'description' => \Illuminate\Support\Str::limit(strip_tags($package->short_description ?: $package->description), 300),
        'sku' => 'PKG-' . $package->id,
        'category' => $package->category,
        'brand' => ['@type' => 'Brand', 'name' => setting('site_name', config('app.name'))],
        'offers' => [
            '@type' => 'Offer',
            'price' => (float) $package->effective_price,
            'priceCurrency' => setting('currency_code', 'USD'),
            'availability' => 'https://schema.org/InStock',
            'url' => route('packages.show', $package->slug),
        ],
    ];
    $breadcrumbSchema = [
        '@context' => 'https://schema.org',
        '@type' => 'BreadcrumbList',
        'itemListElement' => [
            ['@type' => 'ListItem', 'position' => 1, 'name' => 'Home', 'item' => route('home')],
            ['@type' => 'ListItem', 'position' => 2, 'name' => 'Packages', 'item' => route('packages.index')],
            ['@type' => 'ListItem', 'position' => 3, 'name' => $package->title, 'item' => route('packages.show', $package->slug)],
        ],
    ];
@endphp
<script type="application/ld+json">{!! json_encode($productSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>
<script type="application/ld+json">{!! json_encode($breadcrumbSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>
@endpush

@section('content')
    <x-page-hero :title="$package->title" :image="$package->main_image_url"
                 :subtitle="$package->location ?? optional($package->destination)->name"
                 :breadcrumbs="['Packages' => route('packages.index'), $package->title => null]" />

    <section class="py-12">
        <div class="container grid gap-10 lg:grid-cols-3">
            {{-- Main content --}}
            <div class="lg:col-span-2">
                {{-- Gallery --}}
                <div x-data="{ main: '{{ $package->main_image_url }}' }">
                    <div class="overflow-hidden rounded-2xl">
                        <img :src="main" alt="{{ $package->title }}" fetchpriority="high" decoding="async" class="h-[420px] w-full object-cover">
                    </div>
                    @if ($package->images->isNotEmpty())
                        <div class="mt-3 flex gap-3 overflow-x-auto no-scrollbar">
                            <button @click="main = '{{ $package->main_image_url }}'" class="shrink-0">
                                <img src="{{ $package->main_image_url }}" loading="lazy" decoding="async" width="112" height="80" class="h-20 w-28 rounded-lg object-cover ring-2 ring-transparent hover:ring-brand-500">
                            </button>
                            @foreach ($package->images as $img)
                                <button @click="main = '{{ $img->url }}'" class="shrink-0">
                                    <img src="{{ $img->url }}" loading="lazy" decoding="async" width="112" height="80" class="h-20 w-28 rounded-lg object-cover ring-2 ring-transparent hover:ring-brand-500">
                                </button>
                            @endforeach
                        </div>
                    @endif
                </div>

                {{-- Quick facts --}}
                <div class="mt-8 grid grid-cols-2 gap-4 rounded-2xl bg-slate-50 p-6 sm:grid-cols-4">
                    <div>
                        <p class="text-xs uppercase tracking-wide text-slate-400">Duration</p>
                        <p class="mt-1 font-semibold text-slate-800">{{ $package->duration_label }}</p>
                    </div>
                    <div>
                        <p class="text-xs uppercase tracking-wide text-slate-400">Tour Type</p>
                        <p class="mt-1 font-semibold text-slate-800">{{ $package->tour_type ?? 'Group' }}</p>
                    </div>
                    <div>
                        <p class="text-xs uppercase tracking-wide text-slate-400">Category</p>
                        <p class="mt-1 font-semibold text-slate-800">{{ $package->category ?? 'General' }}</p>
                    </div>
                    <div>
                        <p class="text-xs uppercase tracking-wide text-slate-400">Destination</p>
                        <p class="mt-1 font-semibold text-slate-800">{{ optional($package->destination)->name ?? $package->location ?? '—' }}</p>
                    </div>
                </div>

                {{-- Tabs --}}
                <div x-data="{ tab: 'overview' }" class="mt-10">
                    <div class="flex flex-wrap gap-2 border-b border-slate-200">
                        @php
                            $tabs = ['overview' => 'Overview', 'itinerary' => 'Itinerary', 'inclusions' => 'Inclusions', 'terms' => 'Terms'];
                        @endphp
                        @foreach ($tabs as $key => $label)
                            <button @click="tab = '{{ $key }}'"
                                    class="-mb-px border-b-2 px-4 py-3 text-sm font-semibold transition"
                                    :class="tab === '{{ $key }}' ? 'border-brand-600 text-brand-600' : 'border-transparent text-slate-500 hover:text-slate-800'">
                                {{ $label }}
                            </button>
                        @endforeach
                    </div>

                    {{-- Overview --}}
                    <div x-show="tab === 'overview'" class="py-6">
                        @if ($package->short_description)
                            <p class="mb-4 text-lg text-slate-600">{{ $package->short_description }}</p>
                        @endif
                        <div class="prose-content">{!! $package->description !!}</div>
                    </div>

                    {{-- Itinerary --}}
                    <div x-show="tab === 'itinerary'" x-cloak class="py-6">
                        @if (!empty($package->itinerary))
                            <div class="space-y-4">
                                @foreach ($package->itinerary as $i => $day)
                                    <div class="relative rounded-xl border border-slate-100 bg-white p-5 pl-16 card-shadow">
                                        <span class="absolute left-4 top-5 flex h-8 w-8 items-center justify-center rounded-full bg-brand-600 text-sm font-bold text-white">{{ $day['day'] ?? $i + 1 }}</span>
                                        <h4 class="font-bold text-slate-900">{{ $day['title'] ?? 'Day ' . ($i + 1) }}</h4>
                                        @if (!empty($day['detail']))<p class="mt-1 text-sm text-slate-600">{{ $day['detail'] }}</p>@endif
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-slate-400">Detailed itinerary will be shared upon inquiry.</p>
                        @endif
                    </div>

                    {{-- Inclusions / Exclusions --}}
                    <div x-show="tab === 'inclusions'" x-cloak class="py-6">
                        <div class="grid gap-8 sm:grid-cols-2">
                            <div>
                                <h4 class="mb-3 font-bold text-slate-900">What's Included</h4>
                                <ul class="space-y-2 text-sm">
                                    @forelse ($package->inclusions ?? [] as $inc)
                                        <li class="flex gap-2 text-slate-600"><span class="text-green-500">✓</span> {{ $inc }}</li>
                                    @empty
                                        <li class="text-slate-400">Details available on request.</li>
                                    @endforelse
                                </ul>
                            </div>
                            <div>
                                <h4 class="mb-3 font-bold text-slate-900">What's Excluded</h4>
                                <ul class="space-y-2 text-sm">
                                    @forelse ($package->exclusions ?? [] as $exc)
                                        <li class="flex gap-2 text-slate-600"><span class="text-red-500">✕</span> {{ $exc }}</li>
                                    @empty
                                        <li class="text-slate-400">Details available on request.</li>
                                    @endforelse
                                </ul>
                            </div>
                        </div>
                    </div>

                    {{-- Terms --}}
                    <div x-show="tab === 'terms'" x-cloak class="py-6">
                        @if ($package->terms)
                            <div class="prose-content">{!! nl2br(e($package->terms)) !!}</div>
                        @else
                            <p class="text-slate-400">Standard terms and conditions apply. Contact us for details.</p>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Sidebar: price + booking --}}
            <aside class="space-y-6">
                <div class="sticky top-24 space-y-6">
                    <div class="rounded-2xl border border-slate-100 bg-white p-6 card-shadow">
                        <div class="flex items-end justify-between border-b border-slate-100 pb-4">
                            <div>
                                <span class="text-sm text-slate-400">Starting from</span>
                                <div class="flex items-baseline gap-2">
                                    <span class="text-3xl font-bold text-brand-600">{{ setting('currency_symbol', '$') }}{{ number_format($package->effective_price, 0) }}</span>
                                    @if ($package->has_discount)
                                        <span class="text-slate-400 line-through">{{ setting('currency_symbol', '$') }}{{ number_format($package->price, 0) }}</span>
                                    @endif
                                </div>
                                <span class="text-xs text-slate-400">per person</span>
                            </div>
                            @if ($package->has_discount)
                                <span class="rounded-full bg-red-100 px-3 py-1 text-xs font-semibold text-red-600">Save {{ $package->discount_percent }}%</span>
                            @endif
                        </div>

                        @if (!empty($package->available_dates))
                            <div class="border-b border-slate-100 py-4">
                                <p class="mb-2 text-sm font-semibold text-slate-700">Available Dates</p>
                                <div class="flex flex-wrap gap-2">
                                    @foreach ($package->available_dates as $date)
                                        <span class="rounded-full bg-brand-50 px-3 py-1 text-xs font-medium text-brand-700">
                                            {{ \Illuminate\Support\Carbon::parse($date)->format('M d, Y') }}
                                        </span>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <div class="pt-4">
                            <x-wishlist-button :package="$package" variant="button" />
                        </div>

                        <div class="pt-4">
                            <h3 class="mb-3 text-lg font-bold text-slate-900">Book This Tour</h3>
                            <x-booking-form :selectedPackage="$package" :compact="true" />
                        </div>
                    </div>
                </div>
            </aside>
        </div>
    </section>

    {{-- Related packages --}}
    @if ($related->isNotEmpty())
    <section class="bg-slate-50 py-16">
        <div class="container">
            <x-section-heading title="Related Packages" :center="false" />
            <div class="mt-8 grid gap-7 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($related as $rel)
                    <x-package-card :package="$rel" />
                @endforeach
            </div>
        </div>
    </section>
    @endif
@endsection
