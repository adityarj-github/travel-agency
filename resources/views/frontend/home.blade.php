@extends('layouts.frontend')

@php
    // Map the existing travel-agency data onto a lakefront cottage layout.
    $cottages   = $featuredPackages->take(3);
    $heroImage  = optional($sliders->first())->image_url
        ?? 'https://images.unsplash.com/photo-1610641818989-c2051b5e2cfd?auto=format&fit=crop&w=2000&q=80';
@endphp

@section('content')

{{-- ============================================================= --}}
{{-- HERO --}}
{{-- ============================================================= --}}
<section class="relative min-h-[760px] overflow-hidden bg-forest-950 lg:min-h-[88vh]">
    <img src="{{ $heroImage }}" alt="Lakefront cottages at sunset" fetchpriority="high" decoding="async"
         class="absolute inset-0 h-full w-full object-cover">
    <div class="absolute inset-0 bg-gradient-to-r from-forest-950/80 via-forest-950/40 to-forest-950/20"></div>
    <div class="absolute inset-0 bg-gradient-to-t from-forest-950/70 via-transparent to-forest-950/30"></div>

    <div class="container relative grid items-center gap-10 pt-32 pb-20 lg:grid-cols-[1.1fr_minmax(340px,420px)] lg:gap-16 lg:pt-40">
        {{-- Headline column --}}
        <div class="max-w-xl text-white">
            <p class="animate-fade-up mb-6 text-xs font-semibold uppercase tracking-[0.28em] text-forest-100/90">
                Private Waterfront Escape
            </p>
            <h1 class="animate-fade-up delay-100 font-display text-5xl font-semibold leading-[1.08] drop-shadow-lg sm:text-6xl lg:text-7xl">
                Where Killarney<br>Meets French River
            </h1>
            <p class="animate-fade-up delay-200 mt-6 max-w-md text-lg leading-relaxed text-white/85">
                Three modern lakefront cottages.<br class="hidden sm:block">
                One unforgettable place to gather.
            </p>
            <div class="animate-fade-up delay-300 mt-9 flex flex-wrap items-center gap-4">
                <a href="#plan" class="inline-flex items-center justify-center gap-2 rounded-sm bg-forest-700 px-8 py-4 text-sm font-semibold uppercase tracking-wider text-white shadow-lg transition hover:bg-forest-800">
                    Book Your Stay
                </a>
                <a href="#cottages" class="inline-flex items-center justify-center gap-2 rounded-sm border border-white/60 bg-white/5 px-8 py-4 text-sm font-semibold uppercase tracking-wider text-white backdrop-blur transition hover:bg-white hover:text-forest-900">
                    Explore the Cottages
                </a>
            </div>
        </div>

        {{-- Inquiry form card --}}
        <div id="plan" class="animate-fade-up delay-200 w-full rounded-md bg-white p-7 shadow-2xl shadow-forest-950/30 sm:p-8">
            <h2 class="font-display text-2xl font-semibold text-forest-900">Plan Your Getaway</h2>
            <p class="mt-1 text-sm text-slate-500">Tell us a little about your trip and we'll be in touch.</p>

            @if ($errors->any())
                <div class="mt-4 rounded-sm bg-red-50 px-4 py-3 text-sm text-red-700">
                    Please double-check the highlighted fields below.
                </div>
            @endif

            <form method="POST" action="{{ route('booking.store') }}" class="mt-5 space-y-3.5">
                @csrf
                <div>
                    <label class="sr-only">Your Name</label>
                    <input type="text" name="name" value="{{ old('name', auth()->user()->name ?? '') }}" required
                           placeholder="Your Name"
                           class="w-full rounded-sm border-slate-200 bg-sand-50/60 px-4 py-3 text-sm text-slate-700 placeholder-slate-400 focus:border-forest-500 focus:ring-forest-500">
                </div>
                <div>
                    <input type="email" name="email" value="{{ old('email', auth()->user()->email ?? '') }}" required
                           placeholder="Email Address"
                           class="w-full rounded-sm border-slate-200 bg-sand-50/60 px-4 py-3 text-sm text-slate-700 placeholder-slate-400 focus:border-forest-500 focus:ring-forest-500">
                </div>
                <div>
                    <input type="text" name="phone" value="{{ old('phone', auth()->user()->phone ?? '') }}" required
                           placeholder="Phone Number"
                           class="w-full rounded-sm border-slate-200 bg-sand-50/60 px-4 py-3 text-sm text-slate-700 placeholder-slate-400 focus:border-forest-500 focus:ring-forest-500">
                </div>
                <div class="grid grid-cols-2 gap-3.5">
                    <div>
                        <label class="mb-1 block text-xs font-medium text-slate-500">Check-in Date</label>
                        <input type="date" name="travel_date" value="{{ old('travel_date') }}" min="{{ date('Y-m-d') }}"
                               class="w-full rounded-sm border-slate-200 bg-sand-50/60 px-3 py-2.5 text-sm text-slate-600 focus:border-forest-500 focus:ring-forest-500">
                    </div>
                    <div>
                        <label class="mb-1 block text-xs font-medium text-slate-500">Guests</label>
                        <select name="adults"
                                class="w-full rounded-sm border-slate-200 bg-sand-50/60 px-3 py-2.5 text-sm text-slate-600 focus:border-forest-500 focus:ring-forest-500">
                            @for ($g = 1; $g <= 12; $g++)
                                <option value="{{ $g }}" @selected(old('adults', 2) == $g)>{{ $g }} {{ Str::plural('guest', $g) }}</option>
                            @endfor
                        </select>
                    </div>
                </div>
                <div>
                    <textarea name="message" rows="2" placeholder="Message / Special Requests"
                              class="w-full rounded-sm border-slate-200 bg-sand-50/60 px-4 py-3 text-sm text-slate-700 placeholder-slate-400 focus:border-forest-500 focus:ring-forest-500">{{ old('message') }}</textarea>
                </div>
                <button type="submit"
                        class="w-full rounded-sm bg-forest-700 px-6 py-3.5 text-sm font-semibold uppercase tracking-wider text-white transition hover:bg-forest-800">
                    Send Inquiry
                </button>
            </form>
        </div>
    </div>
</section>

{{-- ============================================================= --}}
{{-- AMENITIES STRIP --}}
{{-- ============================================================= --}}
<section class="border-b border-sand-100 bg-white py-12">
    <div class="container">
        @php
            $amenities = [
                ['t' => '3 Private Cottages', 'd' => 'Spacious, modern & perfect for families or groups.',         'i' => 'M3 10.5 12 3l9 7.5M5 9.5V21h14V9.5M9 21v-6h6v6'],
                ['t' => 'Waterfront',         'd' => 'Private access to the lake with docks, kayaks & more.',       'i' => 'M3 16c1.5 1.5 3 1.5 4.5 0s3-1.5 4.5 0 3 1.5 4.5 0 3-1.5 4.5 0M4 12l8-8 8 8M6 12v4'],
                ['t' => 'Hot Tubs',           'd' => 'Relax under the stars in your private hot tub.',             'i' => 'M4 12h16v6a3 3 0 0 1-3 3H7a3 3 0 0 1-3-3v-6ZM7 12V7a2 2 0 0 1 4 0M9 4v.01'],
                ['t' => 'Pet Friendly',       'd' => 'Furry friends are always welcome here.',                     'i' => 'M5.5 11a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3Zm13 0a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3ZM9 7a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3Zm6 0a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3Zm-3 4c-2.5 0-4.5 2.5-4.5 5a2 2 0 0 0 2 2c1 0 1.5-.5 2.5-.5s1.5.5 2.5.5a2 2 0 0 0 2-2c0-2.5-2-5-4.5-5Z'],
                ['t' => 'Ideal for Groups',   'd' => 'Perfect for reunions, retreats & special celebrations.',     'i' => 'M17 20h5v-2a4 4 0 0 0-3-3.87M9 20H4v-2a4 4 0 0 1 3-3.87m6-1.13a4 4 0 1 0-4-4 4 4 0 0 0 4 4Z'],
            ];
        @endphp
        <div data-animate-group class="grid grid-cols-2 gap-8 sm:grid-cols-3 lg:grid-cols-5">
            @foreach ($amenities as $a)
                <div class="flex flex-col items-center text-center">
                    <svg class="h-9 w-9 text-forest-600" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="{{ $a['i'] }}"/>
                    </svg>
                    <h3 class="mt-3 text-xs font-bold uppercase tracking-wider text-forest-900">{{ $a['t'] }}</h3>
                    <p class="mt-1.5 text-xs leading-relaxed text-slate-500">{{ $a['d'] }}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ============================================================= --}}
{{-- RECONNECT / RECHARGE --}}
{{-- ============================================================= --}}
<section class="bg-sand-50 py-20 lg:py-24">
    <div class="container grid items-center gap-12 lg:grid-cols-2 lg:gap-16">
        <div data-animate="right">
            <p class="mb-4 text-xs font-semibold uppercase tracking-[0.28em] text-forest-600">Made for Memory Makers</p>
            <h2 class="font-display text-4xl font-semibold leading-tight text-forest-900 sm:text-5xl">
                Reconnect. Recharge.<br>Make It Yours.
            </h2>
            <p class="mt-6 max-w-md leading-relaxed text-slate-600">
                Tucked away in {{ setting('site_name', 'Hartley Bay') }}, along the shores of the French River, our three cottages
                offer the perfect blend of modern comfort and natural beauty. Swim, paddle, fish, or simply relax and take in
                the view. This is cottage life — made for you.
            </p>
            <a href="{{ route('packages.index') }}"
               class="mt-8 inline-flex items-center gap-2 rounded-sm bg-forest-700 px-7 py-3.5 text-sm font-semibold uppercase tracking-wider text-white transition hover:bg-forest-800">
                Discover the Experience
            </a>
        </div>

        <div data-animate="left" class="relative overflow-hidden rounded-md card-shadow">
            <img src="https://images.unsplash.com/photo-1542718610-a1d656d1884c?auto=format&fit=crop&w=1100&q=80"
                 alt="Cottages by the water" loading="lazy" decoding="async"
                 class="aspect-[4/3] w-full object-cover">
            <div class="absolute bottom-5 left-5 rounded-sm bg-forest-950/75 px-5 py-3 text-white backdrop-blur">
                <p class="text-xs font-semibold uppercase tracking-wider text-forest-100">Hartley Bay, Killarney</p>
                <p class="text-sm text-white/85">Peaceful. Private. Yours.</p>
            </div>
        </div>
    </div>
</section>

{{-- ============================================================= --}}
{{-- FIND YOUR PERFECT STAY --}}
{{-- ============================================================= --}}
<section id="cottages" class="bg-white py-20 lg:py-24">
    <div class="container">
        <div data-animate="up" class="mx-auto max-w-2xl text-center">
            <p class="mb-3 text-xs font-semibold uppercase tracking-[0.28em] text-forest-600">Three Cottages. Endless Possibilities.</p>
            <h2 class="font-display text-4xl font-semibold text-forest-900 sm:text-5xl">Find Your Perfect Stay</h2>
        </div>

        @if ($cottages->isNotEmpty())
            <div data-animate-group class="mt-14 grid gap-8 md:grid-cols-3">
                @foreach ($cottages as $cottage)
                    <article class="group flex h-full flex-col overflow-hidden rounded-md bg-white card-shadow transition hover:-translate-y-1">
                        <div class="relative h-56 overflow-hidden">
                            <img src="{{ $cottage->main_image_url }}" alt="{{ $cottage->title }}" loading="lazy" decoding="async"
                                 class="h-full w-full object-cover transition duration-700 group-hover:scale-110">
                            <span class="absolute left-4 top-4 rounded-sm bg-white/90 px-3 py-1 text-xs font-semibold uppercase tracking-wider text-forest-800">
                                {{ $cottage->category ?? 'Cottage' }}
                            </span>
                        </div>
                        <div class="flex flex-1 flex-col p-6">
                            <h3 class="font-display text-xl font-semibold text-forest-900">{{ $cottage->title }}</h3>

                            <div class="mt-3 flex flex-wrap items-center gap-x-4 gap-y-2 text-xs text-slate-500">
                                <span class="inline-flex items-center gap-1.5">
                                    <svg class="h-4 w-4 text-forest-600" fill="none" stroke="currentColor" stroke-width="1.7" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a4 4 0 0 0-3-3.87M9 20H4v-2a4 4 0 0 1 3-3.87m6-1.13a4 4 0 1 0-4-4 4 4 0 0 0 4 4Z"/></svg>
                                    Sleeps {{ $cottage->max_people ?? 8 }}
                                </span>
                                <span class="inline-flex items-center gap-1.5">
                                    <svg class="h-4 w-4 text-forest-600" fill="none" stroke="currentColor" stroke-width="1.7" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 10.5 12 3l9 7.5M5 9.5V21h14V9.5"/></svg>
                                    {{ $cottage->duration_label }}
                                </span>
                                <span class="inline-flex items-center gap-1.5">
                                    <svg class="h-4 w-4 text-forest-600" fill="none" stroke="currentColor" stroke-width="1.7" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657 13.414 20.9a2 2 0 0 1-2.827 0l-4.244-4.243a8 8 0 1 1 11.314 0Z"/><circle cx="12" cy="11" r="3"/></svg>
                                    {{ $cottage->location ?? optional($cottage->destination)->name ?? 'Waterfront' }}
                                </span>
                            </div>

                            <p class="mt-4 line-clamp-2 flex-1 text-sm leading-relaxed text-slate-500">
                                {{ $cottage->short_description }}
                            </p>

                            <div class="mt-5 flex items-center justify-between border-t border-sand-100 pt-4">
                                <div>
                                    <span class="text-xs text-slate-400">From</span>
                                    <div class="text-lg font-semibold text-forest-800">
                                        {{ setting('currency_symbol', '$') }}{{ number_format($cottage->effective_price, 0) }}
                                    </div>
                                </div>
                                <a href="{{ route('packages.show', $cottage->slug) }}"
                                   class="inline-flex items-center gap-1.5 text-sm font-semibold uppercase tracking-wider text-forest-700 transition hover:text-forest-900">
                                    View Cottage
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                                </a>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>
        @else
            <p class="mt-12 rounded-md border border-dashed border-sand-200 py-16 text-center text-slate-400">
                Cottages will appear here once added from the admin panel.
            </p>
        @endif
    </div>
</section>

{{-- ============================================================= --}}
{{-- EXPERIENCE THE BEST OF — dark photo band --}}
{{-- ============================================================= --}}
<section class="relative overflow-hidden bg-forest-950 py-20 text-white lg:py-24">
    <img src="https://images.unsplash.com/photo-1501785888041-af3ef285b470?auto=format&fit=crop&w=2000&q=80"
         alt="Kayaking on the lake" loading="lazy" decoding="async"
         class="absolute inset-0 h-full w-full object-cover opacity-25">
    <div class="absolute inset-0 bg-gradient-to-r from-forest-950 via-forest-950/85 to-forest-900/60"></div>

    <div class="container relative grid items-center gap-12 lg:grid-cols-[1.3fr_1fr] lg:gap-16">
        <div data-animate="right">
            <p class="mb-4 text-xs font-semibold uppercase tracking-[0.28em] text-forest-200">More Than a Stay</p>
            <h2 class="font-display text-4xl font-semibold leading-tight sm:text-5xl">
                Experience the Best of<br>Killarney &amp; French River
            </h2>
            <p class="mt-5 max-w-lg leading-relaxed text-white/80">
                Subscribe for updates, special offers & travel inspiration — and discover everything there is to do
                right outside your door, in every season.
            </p>

            @php
                $activities = [
                    ['t' => 'Fishing',      'i' => 'M2 12c4-5 10-5 14 0M16 12c2 0 4 1 6 0M6 12a1 1 0 1 0 0-.01'],
                    ['t' => 'Kayaking',     'i' => 'M3 16c1.5 1.5 3 1.5 4.5 0s3-1.5 4.5 0 3 1.5 4.5 0 3-1.5 4.5 0M7 13l5-9 5 9'],
                    ['t' => 'Hiking',       'i' => 'M13 4a1.5 1.5 0 1 0 0-.01M9 21l3-6 2 3h4M12 15l-1-4 4 2 2-2'],
                    ['t' => 'Snowmobiling', 'i' => 'M4 17h9l4-4h3M4 17l3-5h6l3 3M7 20a2 2 0 1 0 0-.01M17 20a2 2 0 1 0 0-.01'],
                    ['t' => 'Local Dining', 'i' => 'M5 3v8a2 2 0 0 0 4 0V3M7 11v10M17 3c-1.5 0-2.5 2-2.5 5s1 4 2.5 4v9'],
                ];
            @endphp
            <div class="mt-9 flex flex-wrap gap-x-10 gap-y-6">
                @foreach ($activities as $act)
                    <div class="flex flex-col items-center gap-2 text-center">
                        <span class="flex h-12 w-12 items-center justify-center rounded-full border border-white/25 bg-white/5">
                            <svg class="h-6 w-6 text-forest-100" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $act['i'] }}"/></svg>
                        </span>
                        <span class="text-xs font-semibold uppercase tracking-wider text-white/80">{{ $act['t'] }}</span>
                    </div>
                @endforeach
                <div class="flex items-center">
                    <span class="text-xs font-semibold uppercase tracking-wider text-forest-200">&amp; More</span>
                </div>
            </div>
        </div>

        {{-- Review card --}}
        @php $review = $testimonials->first(); @endphp
        <div data-animate="left" class="rounded-md bg-white/10 p-8 ring-1 ring-white/15 backdrop-blur">
            <div class="flex justify-center gap-1 text-amber-300">
                @for ($i = 0; $i < 5; $i++)
                    <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 0 0 .95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 0 0-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 0 0-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 0 0-.364-1.118L2.45 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 0 0 .951-.69l1.07-3.292Z"/></svg>
                @endfor
            </div>
            <blockquote class="mt-5 text-center font-display text-xl font-medium italic leading-relaxed text-white">
                “{{ $review->message ?? 'The perfect group getaway. Beautiful cottages, amazing views, and so peaceful. We will absolutely be back!' }}”
            </blockquote>
            <p class="mt-6 text-center text-sm font-semibold uppercase tracking-wider text-forest-200">
                — {{ $review->name ?? 'Local Guest' }}
            </p>
        </div>
    </div>
</section>

{{-- ============================================================= --}}
{{-- NEWSLETTER CTA --}}
{{-- ============================================================= --}}
<section class="bg-sand-50 py-16">
    <div class="container">
        <div class="flex flex-col items-center justify-between gap-6 rounded-md bg-white px-8 py-10 card-shadow lg:flex-row lg:gap-10">
            <div class="text-center lg:text-left">
                <h2 class="font-display text-2xl font-semibold text-forest-900 sm:text-3xl">Ready to plan your getaway?</h2>
                <p class="mt-2 text-sm text-slate-500">Subscribe for updates, special offers & travel inspiration.</p>
            </div>
            <form action="{{ route('contact.store') }}" method="POST" class="flex w-full max-w-md gap-3">
                @csrf
                <input type="hidden" name="name" value="Newsletter Subscriber">
                <input type="hidden" name="subject" value="Newsletter Signup">
                <input type="hidden" name="message" value="Please add me to the newsletter.">
                <input type="email" name="email" required placeholder="Enter your email"
                       class="w-full rounded-sm border-slate-200 bg-sand-50/60 px-4 py-3 text-sm text-slate-700 placeholder-slate-400 focus:border-forest-500 focus:ring-forest-500">
                <button type="submit"
                        class="flex-none rounded-sm bg-forest-700 px-7 py-3 text-sm font-semibold uppercase tracking-wider text-white transition hover:bg-forest-800">
                    Subscribe
                </button>
            </form>
        </div>
    </div>
</section>

@endsection
