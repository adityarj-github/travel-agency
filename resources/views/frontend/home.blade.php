@extends('layouts.frontend')

@section('content')

{{-- ============================================================= --}}
{{-- HERO --}}
{{-- ============================================================= --}}
<section class="relative">
    <div class="relative h-[92vh] min-h-[620px] overflow-hidden bg-slate-900">

        {{-- Background: rotating sliders, or a single fallback image --}}
        @if ($sliders->isNotEmpty())
            <div x-data="{
                    active: 0,
                    total: {{ $sliders->count() }},
                    init() { if (this.total > 1) this.timer = setInterval(() => this.next(), 6500) },
                    next() { this.active = (this.active + 1) % this.total },
                    prev() { this.active = (this.active - 1 + this.total) % this.total },
                    go(i) { this.active = i }
                 }" class="absolute inset-0">
                @foreach ($sliders as $i => $slider)
                    <div x-show="active === {{ $i }}"
                         x-transition:enter="transition ease-out duration-1000" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                         x-transition:leave="transition ease-in duration-700" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                         class="absolute inset-0">
                        <img src="{{ $slider->image_url }}" alt="{{ $slider->title }}"
                             class="h-full w-full object-cover animate-zoom"
                             @if ($i === 0) fetchpriority="high" @else loading="lazy" @endif decoding="async">
                    </div>
                @endforeach

                {{-- Slider navigation --}}
                @if ($sliders->count() > 1)
                    <div class="absolute bottom-8 right-6 z-20 hidden items-center gap-3 sm:flex">
                        <button @click="prev()" aria-label="Previous slide"
                                class="flex h-11 w-11 items-center justify-center rounded-full border border-white/40 text-white backdrop-blur transition hover:bg-white hover:text-slate-900">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                        </button>
                        <button @click="next()" aria-label="Next slide"
                                class="flex h-11 w-11 items-center justify-center rounded-full border border-white/40 text-white backdrop-blur transition hover:bg-white hover:text-slate-900">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                        </button>
                    </div>
                    <div class="absolute bottom-8 left-1/2 z-20 flex -translate-x-1/2 gap-2">
                        @foreach ($sliders as $i => $s)
                            <button @click="go({{ $i }})" aria-label="Go to slide {{ $i + 1 }}"
                                    class="h-1.5 rounded-full bg-white transition-all duration-300"
                                    :class="active === {{ $i }} ? 'w-10 opacity-100' : 'w-4 opacity-40'"></button>
                        @endforeach
                    </div>
                @endif
            </div>
        @else
            <img src="https://images.unsplash.com/photo-1488646953014-85cb44e25828?auto=format&fit=crop&w=1920&q=80"
                 fetchpriority="high" decoding="async"
                 class="absolute inset-0 h-full w-full object-cover animate-zoom" alt="Travel the world">
        @endif

        {{-- Overlays --}}
        <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-900/55 to-slate-900/40"></div>
        <div class="absolute inset-0 bg-gradient-to-r from-slate-950/80 via-slate-950/20 to-transparent"></div>

        {{-- Hero content --}}
        @php
            $heroSlide   = $sliders->first();
            $heroEyebrow = $heroSlide->subtitle ?? 'Explore the world with us';
            $heroTitle   = $heroSlide->title ?? 'Discover your next unforgettable journey';
            $heroCtaText = $heroSlide->button_text ?? 'Browse Packages';
            $heroCtaLink = ($heroSlide && $heroSlide->button_link) ? $heroSlide->button_link : route('packages.index');
        @endphp
        <div class="container relative flex h-full flex-col justify-center pb-28">
            <div class="max-w-3xl text-white">
                <p class="animate-fade-up mb-5 inline-flex items-center gap-2 rounded-full border border-white/20 bg-white/10 px-4 py-1.5 text-xs font-semibold uppercase tracking-[0.2em] text-brand-200 backdrop-blur">
                    <span class="flex h-2 w-2 rounded-full bg-brand-400"></span>
                    {{ $heroEyebrow }}
                </p>
                <h1 class="animate-fade-up delay-100 text-4xl font-bold leading-[1.05] drop-shadow-xl sm:text-6xl lg:text-7xl">
                    {{ $heroTitle }}
                </h1>
                <p class="animate-fade-up delay-200 mt-6 max-w-xl text-lg leading-relaxed text-white/85">
                    Handpicked tours, breathtaking destinations and seamless travel experiences crafted around the way you love to explore.
                </p>
                <div class="animate-fade-up delay-300 mt-9 flex flex-wrap items-center gap-4">
                    <a href="{{ $heroCtaLink }}" class="btn-primary !px-8 !py-4 text-base shadow-lg shadow-brand-900/40">
                        {{ $heroCtaText }}
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                    </a>
                    <a href="{{ route('contact') }}" class="btn border border-white/40 text-white backdrop-blur transition hover:bg-white hover:text-slate-900 !px-8 !py-4 text-base">
                        Talk to an Expert
                    </a>
                </div>

                {{-- Mini trust row --}}
                <div class="animate-fade-up delay-500 mt-10 flex flex-wrap items-center gap-x-8 gap-y-4 text-sm text-white/80">
                    <div class="flex items-center gap-2">
                        <x-star-rating :rating="5" />
                        <span>Loved by {{ number_format($stats['travelers']) }}+ travellers</span>
                    </div>
                    <div class="hidden h-4 w-px bg-white/25 sm:block"></div>
                    <div class="flex items-center gap-2">
                        <svg class="h-5 w-5 text-brand-300" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span>100% secure booking</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ============ SEARCH BAR (overlaps hero) ============ --}}
    <div class="container relative z-30 -mt-20">
        <form action="{{ route('packages.index') }}" method="GET"
              class="glass grid gap-4 rounded-3xl p-5 shadow-2xl shadow-slate-900/10 sm:p-6 lg:grid-cols-[1fr_1fr_1fr_auto]">
            <div>
                <label class="form-label flex items-center gap-1.5 text-slate-600">
                    <svg class="h-4 w-4 text-brand-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="11" cy="11" r="7"/><path stroke-linecap="round" d="M21 21l-4-4"/></svg>
                    Search
                </label>
                <input type="text" name="q" placeholder="Where to? e.g. Bali" class="form-input-base !rounded-xl">
            </div>
            <div>
                <label class="form-label flex items-center gap-1.5 text-slate-600">
                    <svg class="h-4 w-4 text-brand-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a2 2 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><circle cx="12" cy="11" r="3"/></svg>
                    Destination
                </label>
                <select name="destination" class="form-input-base !rounded-xl">
                    <option value="">Any destination</option>
                    @foreach ($allDestinations as $d)
                        <option value="{{ $d->slug }}">{{ $d->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="form-label flex items-center gap-1.5 text-slate-600">
                    <svg class="h-4 w-4 text-brand-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h7"/></svg>
                    Category
                </label>
                <select name="category" class="form-input-base !rounded-xl">
                    <option value="">Any category</option>
                    @foreach ($categories as $c)
                        <option value="{{ $c }}">{{ $c }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex items-end">
                <button type="submit" class="btn-primary h-[46px] w-full !rounded-xl lg:!px-9">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="11" cy="11" r="7"/><path stroke-linecap="round" d="M21 21l-4-4"/></svg>
                    Search
                </button>
            </div>
        </form>
    </div>
</section>

{{-- ============================================================= --}}
{{-- STATS --}}
{{-- ============================================================= --}}
<section class="py-16 sm:py-20">
    <div class="container">
        @php
            $statItems = [
                ['value' => $stats['destinations'], 'suffix' => '+', 'label' => 'Destinations',     'icon' => 'M17.657 16.657L13.414 20.9a2 2 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z'],
                ['value' => $stats['packages'],     'suffix' => '+', 'label' => 'Tour Packages',     'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2'],
                ['value' => $stats['travelers'],    'suffix' => '+', 'label' => 'Happy Travellers',   'icon' => 'M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m6-1.13a4 4 0 10-4-4 4 4 0 004 4z'],
                ['value' => $stats['reviews'],      'suffix' => '+', 'label' => '5-Star Reviews',     'icon' => 'M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.977-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.539-1.118l1.518-4.674a1 1 0 00-.362-1.118L2.342 9.79c-.783-.57-.38-1.81.588-1.81h4.915a1 1 0 00.95-.69l1.519-4.674z'],
            ];
        @endphp
        <div data-animate-group class="grid grid-cols-2 gap-4 lg:grid-cols-4 lg:gap-6">
            @foreach ($statItems as $s)
                <div class="group flex items-center gap-4 rounded-2xl border border-slate-100 bg-white p-6 transition hover:border-brand-200 hover:shadow-lg">
                    <div class="flex h-14 w-14 flex-none items-center justify-center rounded-2xl bg-brand-50 text-brand-600 transition group-hover:bg-brand-600 group-hover:text-white">
                        <svg class="h-7 w-7" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $s['icon'] }}"/></svg>
                    </div>
                    <div>
                        <div class="text-2xl font-bold text-slate-900 sm:text-3xl"
                             data-count="{{ $s['value'] }}" data-suffix="{{ $s['suffix'] }}">{{ number_format($s['value']) }}{{ $s['suffix'] }}</div>
                        <div class="text-sm text-slate-500">{{ $s['label'] }}</div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ============================================================= --}}
{{-- FEATURED PACKAGES --}}
{{-- ============================================================= --}}
<section class="pb-20">
    <div class="container">
        <div class="flex flex-col items-start justify-between gap-6 sm:flex-row sm:items-end">
            <div data-animate="up" class="max-w-xl">
                <span class="mb-2 inline-block text-sm font-semibold uppercase tracking-widest text-brand-600">Top Picks</span>
                <h2 class="section-title">Featured Travel Packages</h2>
                <p class="mt-3 text-slate-500">Our most-loved tours, curated for extraordinary experiences from start to finish.</p>
            </div>
            <a href="{{ route('packages.index') }}" data-animate="left" class="btn-outline flex-none">
                View All Packages
                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
            </a>
        </div>

        @if ($featuredPackages->isNotEmpty())
            <div data-animate-group class="mt-12 grid gap-7 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($featuredPackages as $package)
                    <x-package-card :package="$package" />
                @endforeach
            </div>
        @else
            <p class="mt-12 rounded-2xl border border-dashed border-slate-200 py-16 text-center text-slate-400">
                Packages will appear here once added from the admin panel.
            </p>
        @endif
    </div>
</section>

{{-- ============================================================= --}}
{{-- POPULAR DESTINATIONS — bento grid --}}
{{-- ============================================================= --}}
@if ($destinations->isNotEmpty())
<section class="bg-slate-50 py-20">
    <div class="container">
        <x-section-heading data-animate="up" eyebrow="Where to go" title="Popular Destinations"
                           subtitle="Explore the world's most stunning places with our expertly guided tours." />

        @php $bento = $destinations->take(5); @endphp
        <div data-animate-group class="mt-12 grid auto-rows-[220px] grid-cols-2 gap-5 lg:grid-cols-4">
            @foreach ($bento as $i => $destination)
                {{-- First card spans larger on big screens for an editorial feel --}}
                <a href="{{ route('destinations.show', $destination->slug) }}"
                   class="group relative block overflow-hidden rounded-3xl card-shadow
                          {{ $i === 0 ? 'col-span-2 row-span-2' : '' }}">
                    <img src="{{ $destination->image_url }}" alt="{{ $destination->name }}" loading="lazy" decoding="async"
                         class="h-full w-full object-cover transition duration-700 group-hover:scale-110">
                    <div class="absolute inset-0 bg-gradient-to-t from-slate-950/85 via-slate-900/20 to-transparent"></div>
                    <div class="absolute inset-x-0 bottom-0 flex items-end justify-between p-5 text-white">
                        <div>
                            <p class="text-xs uppercase tracking-widest text-brand-200">{{ $destination->country ?? 'Destination' }}</p>
                            <h3 class="mt-1 font-bold {{ $i === 0 ? 'text-2xl sm:text-3xl' : 'text-lg' }}">{{ $destination->name }}</h3>
                            <p class="mt-1 text-sm text-white/80">{{ $destination->packages_count ?? 0 }} tour package(s)</p>
                        </div>
                        <span class="flex h-10 w-10 flex-none translate-y-2 items-center justify-center rounded-full bg-white/15 opacity-0 backdrop-blur transition group-hover:translate-y-0 group-hover:opacity-100">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                        </span>
                    </div>
                </a>
            @endforeach
        </div>

        <div class="mt-10 text-center">
            <a href="{{ route('destinations.index') }}" class="btn-outline">Explore All Destinations</a>
        </div>
    </div>
</section>
@endif

{{-- ============================================================= --}}
{{-- WHY CHOOSE US — split layout --}}
{{-- ============================================================= --}}
<section class="py-20">
    <div class="container grid items-center gap-14 lg:grid-cols-2">
        {{-- Image collage --}}
        <div data-animate="right" class="relative">
            <div class="overflow-hidden rounded-[2rem] card-shadow">
                <img src="https://images.unsplash.com/photo-1469854523086-cc02fe5d8800?auto=format&fit=crop&w=1000&q=80"
                     alt="Travellers on an adventure" loading="lazy" decoding="async"
                     class="aspect-[4/5] w-full object-cover">
            </div>
            <div class="absolute -bottom-8 -right-4 hidden w-48 overflow-hidden rounded-2xl border-4 border-white card-shadow animate-float sm:block lg:-right-8 lg:w-56">
                <img src="https://images.unsplash.com/photo-1502920917128-1aa500764cbd?auto=format&fit=crop&w=600&q=80"
                     alt="Scenic view" loading="lazy" decoding="async" class="aspect-square w-full object-cover">
            </div>
            {{-- Floating rating badge --}}
            <div class="absolute -left-4 top-8 flex items-center gap-3 rounded-2xl bg-white p-4 card-shadow lg:-left-8">
                <div class="flex h-12 w-12 items-center justify-center rounded-full bg-brand-600 text-white">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                </div>
                <div>
                    <div class="text-sm font-bold text-slate-900">Best Price</div>
                    <div class="text-xs text-slate-500">Guaranteed</div>
                </div>
            </div>
        </div>

        {{-- Feature list --}}
        <div data-animate="left">
            <span class="mb-2 inline-block text-sm font-semibold uppercase tracking-widest text-brand-600">Why travel with us</span>
            <h2 class="section-title">The {{ setting('site_name', config('app.name')) }} Difference</h2>
            <p class="mt-3 text-slate-500">We obsess over the details so you can focus on the adventure. Here's what sets every one of our journeys apart.</p>

            @php
                $features = [
                    ['t' => 'Handpicked Tours', 'd' => 'Every itinerary is carefully designed by travel experts for unforgettable moments.', 'i' => 'M5 13l4 4L19 7'],
                    ['t' => 'Best Price Guarantee', 'd' => 'Premium experiences at competitive prices with no hidden charges, ever.', 'i' => 'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1'],
                    ['t' => '24/7 Support', 'd' => 'Our dedicated team is always one message away, wherever in the world you are.', 'i' => 'M18.364 5.636a9 9 0 010 12.728m0 0l-3.536-3.536m3.536 3.536L12 12m6.364 6.364A9 9 0 015.636 5.636'],
                    ['t' => 'Trusted by Travellers', 'd' => 'Thousands of happy customers and glowing reviews from around the world.', 'i' => 'M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.977-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.539-1.118l1.518-4.674a1 1 0 00-.362-1.118L2.342 9.79c-.783-.57-.38-1.81.588-1.81h4.915a1 1 0 00.95-.69l1.519-4.674z'],
                ];
            @endphp
            <div data-animate-group class="mt-8 grid gap-6 sm:grid-cols-2">
                @foreach ($features as $f)
                    <div class="flex gap-4">
                        <div class="flex h-12 w-12 flex-none items-center justify-center rounded-2xl bg-brand-50 text-brand-600">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $f['i'] }}"/></svg>
                        </div>
                        <div>
                            <h3 class="mb-1 font-bold text-slate-900">{{ $f['t'] }}</h3>
                            <p class="text-sm text-slate-500">{{ $f['d'] }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</section>

{{-- ============================================================= --}}
{{-- HOW IT WORKS --}}
{{-- ============================================================= --}}
<section class="bg-brand-950 py-20 text-white">
    <div class="container">
        <div data-animate="up" class="mx-auto max-w-2xl text-center">
            <span class="mb-2 inline-block text-sm font-semibold uppercase tracking-widest text-brand-300">Simple &amp; seamless</span>
            <h2 class="section-title text-white">Plan Your Trip in 3 Easy Steps</h2>
            <p class="mt-3 text-white/70">From the first spark of wanderlust to wheels-up, we make the whole journey effortless.</p>
        </div>

        @php
            $steps = [
                ['n' => '01', 't' => 'Find Your Trip', 'd' => 'Browse handpicked packages and destinations, or search by what matters most to you.', 'i' => 'M21 21l-4-4m2-5a7 7 0 11-14 0 7 7 0 0114 0z'],
                ['n' => '02', 't' => 'Book Securely', 'd' => 'Reserve your spot in minutes with secure checkout and instant confirmation.', 'i' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
                ['n' => '03', 't' => 'Pack & Go', 'd' => 'Get your travel voucher, meet your guide and set off on an unforgettable adventure.', 'i' => 'M3 21l1.9-5.7a8.38 8.38 0 1113.8 0L20.6 21H3z M9 21v-6a3 3 0 016 0v6'],
            ];
        @endphp
        <div class="relative mt-14">
            {{-- connecting line --}}
            <div class="absolute left-1/2 top-12 hidden h-px w-2/3 -translate-x-1/2 bg-gradient-to-r from-transparent via-white/20 to-transparent md:block"></div>
            <div data-animate-group class="grid gap-8 md:grid-cols-3">
                @foreach ($steps as $step)
                    <div class="relative rounded-3xl bg-white/5 p-8 text-center ring-1 ring-white/10 transition hover:bg-white/10">
                        <div class="mx-auto mb-5 flex h-16 w-16 items-center justify-center rounded-2xl bg-brand-600 text-white shadow-lg shadow-brand-900/50">
                            <svg class="h-8 w-8" fill="none" stroke="currentColor" stroke-width="1.7" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $step['i'] }}"/></svg>
                        </div>
                        <div class="mb-1 text-sm font-bold tracking-widest text-brand-300">{{ $step['n'] }}</div>
                        <h3 class="mb-2 text-xl font-bold text-white">{{ $step['t'] }}</h3>
                        <p class="text-sm text-white/70">{{ $step['d'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="mt-12 text-center">
            <a href="{{ route('packages.index') }}" class="btn-white">Start Planning</a>
        </div>
    </div>
</section>

{{-- ============================================================= --}}
{{-- TESTIMONIALS --}}
{{-- ============================================================= --}}
@if ($testimonials->isNotEmpty())
<section class="py-20">
    <div class="container">
        <x-section-heading data-animate="up" eyebrow="Happy Travellers" title="What Our Customers Say"
                           subtitle="Real stories from real adventurers who explored the world with us." />
        <div data-animate-group class="mt-12 grid gap-7 md:grid-cols-2 lg:grid-cols-3">
            @foreach ($testimonials as $t)
                <figure class="relative flex h-full flex-col rounded-3xl border border-slate-100 bg-white p-7 card-shadow">
                    <svg class="absolute right-7 top-7 h-10 w-10 text-brand-100" fill="currentColor" viewBox="0 0 24 24"><path d="M9.983 3v7.391c0 5.704-3.731 9.57-8.983 10.609l-.995-2.151c2.432-.917 3.995-3.638 3.995-5.849h-4v-10h9.983zm14.017 0v7.391c0 5.704-3.748 9.571-9 10.609l-.996-2.151c2.433-.917 3.996-3.638 3.996-5.849h-3.983v-10h9.983z"/></svg>
                    <x-star-rating :rating="$t->rating" />
                    <blockquote class="mt-4 flex-1 text-sm leading-relaxed text-slate-600">“{{ $t->message }}”</blockquote>
                    <figcaption class="mt-6 flex items-center gap-3 border-t border-slate-100 pt-5">
                        <img src="{{ $t->image_url }}" alt="{{ $t->name }}" loading="lazy" decoding="async" width="48" height="48" class="h-12 w-12 rounded-full object-cover ring-2 ring-brand-100">
                        <div>
                            <p class="font-semibold text-slate-900">{{ $t->name }}</p>
                            @if ($t->location)<p class="text-xs text-slate-500">{{ $t->location }}</p>@endif
                        </div>
                    </figcaption>
                </figure>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- ============================================================= --}}
{{-- BLOG PREVIEW --}}
{{-- ============================================================= --}}
@if ($latestBlogs->isNotEmpty())
<section class="bg-slate-50 py-20">
    <div class="container">
        <div class="flex flex-col items-start justify-between gap-6 sm:flex-row sm:items-end">
            <div data-animate="up" class="max-w-xl">
                <span class="mb-2 inline-block text-sm font-semibold uppercase tracking-widest text-brand-600">Travel Stories</span>
                <h2 class="section-title">From Our Blog</h2>
                <p class="mt-3 text-slate-500">Tips, guides and inspiration for your next adventure.</p>
            </div>
            <a href="{{ route('blog.index') }}" data-animate="left" class="btn-outline flex-none">
                Read More Articles
                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
            </a>
        </div>
        <div data-animate-group class="mt-12 grid gap-7 sm:grid-cols-2 lg:grid-cols-3">
            @foreach ($latestBlogs as $blog)
                <x-blog-card :blog="$blog" />
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- ============================================================= --}}
{{-- CALL TO ACTION --}}
{{-- ============================================================= --}}
<section class="py-20">
    <div class="container">
        <div data-animate="zoom" class="relative overflow-hidden rounded-[2.5rem] px-6 py-16 text-center text-white sm:px-12 sm:py-20">
            <img src="https://images.unsplash.com/photo-1507525428034-b723cf961d3e?auto=format&fit=crop&w=1920&q=80"
                 loading="lazy" decoding="async" data-parallax="0.12" class="absolute left-0 top-[-20%] h-[130%] w-full object-cover" alt="">
            <div class="absolute inset-0 bg-gradient-to-r from-brand-950/95 via-brand-900/85 to-brand-800/75"></div>
            <div class="relative mx-auto max-w-2xl">
                <span class="mb-3 inline-block text-sm font-semibold uppercase tracking-widest text-brand-200">Your adventure awaits</span>
                <h2 class="text-3xl font-bold sm:text-4xl lg:text-5xl">Ready for Your Next Adventure?</h2>
                <p class="mx-auto mt-4 max-w-xl text-white/85">Let our travel experts craft the perfect journey for you. Send an inquiry today and start exploring the world.</p>
                <div class="mt-9 flex flex-wrap justify-center gap-4">
                    <a href="{{ route('booking.create') }}" class="btn-white !px-8 !py-4 text-base">Make an Inquiry</a>
                    <a href="{{ route('contact') }}" class="btn border border-white/50 text-white transition hover:bg-white/10 !px-8 !py-4 text-base">Contact Us</a>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
